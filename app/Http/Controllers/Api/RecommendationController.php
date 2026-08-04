<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiFilterService;
use App\Services\BudgetRecommendationService;
use App\Models\Destinasi;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected GeminiFilterService $geminiService;
    protected BudgetRecommendationService $budgetService;

    public function __construct(
        GeminiFilterService $geminiService,
        BudgetRecommendationService $budgetService
    ) {
        $this->geminiService = $geminiService;
        $this->budgetService = $budgetService;
    }

    /**
     * GET /api/recommendations
     * Ambil rekomendasi personal berdasarkan preferensi pengguna menggunakan TF-IDF + Cosine Similarity.
     */
    public function index(Request $request)
    {
        $userId = $request->user()?->id_user ?? \Illuminate\Support\Facades\Auth::id();

        /** @var \App\Services\RecommendationService $recommendationService */
        $recommendationService = app(\App\Services\RecommendationService::class);
        
        $corpus = $recommendationService->buildCorpus($userId);

        if (empty($corpus) || count($corpus) <= 1) {
            // Fallback jika data preferensi user belum ada
            $recommendations = Destinasi::withAvg('ratings', 'skor_rating')
                ->withCount('ratings')
                ->limit(15)
                ->get();
        } else {
            $documents = $recommendationService->tokenizeCorpus($corpus);
            $vocabulary = $recommendationService->buildVocabulary($documents);
            $wordFrequency = $recommendationService->calculateWordFrequency($documents);
            $tf = $recommendationService->calculateTermFrequency($wordFrequency);
            $df = $recommendationService->calculateDocumentFrequency($documents, $vocabulary);
            $idf = $recommendationService->calculateInverseDocumentFrequency($df, count($documents));
            $tfidf = $recommendationService->calculateTfIdfMatrix($tf, $idf);
            $allSimilarity = $recommendationService->calculateAllCosineSimilarity($tfidf);
            $ranking = $recommendationService->rankRecommendations($allSimilarity);
            $topRecommendations = $recommendationService->mixHiddenGemRecommendations($ranking, $userId, 15);
            $recommendations = $recommendationService->getRecommendationResults($topRecommendations);
        }

        // Sesuaikan key agar sesuai dengan parsing di model/controller mobile:
        // Di mobile: final rating = item['average_rating'] atau item['ratings_avg_skor_rating']
        // Mari kita petakan agar format output data konsisten
        $formatted = $recommendations->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_destinasi' => $item->nama_destinasi,
                'kategori' => $item->kategori,
                'kota' => $item->kota,
                'harga' => (float) $item->harga,
                'hidden_gem' => (bool) $item->hidden_gem,
                'gambar' => $item->image_url ?? $item->gambar,
                'average_rating' => $item->ratings_avg_skor_rating !== null ? (float)$item->ratings_avg_skor_rating : null,
                'ratings_count' => (int)$item->ratings_count,
            ];
        });

        return response()->json([
            'status' => 'success',
            'recommendations' => $formatted
        ]);
    }

    public function generateItinerary(Request $request)
    {
        $request->validate([
            'kota' => 'required|string',
            'kategori' => 'required|string',
            'budget' => 'required|numeric|min:0',
            'titik_awal' => 'required|string'
        ]);

        $kota = $request->input('kota');
        $kategori = $request->input('kategori');
        $budget = (float) $request->input('budget');
        $titikAwal = $request->input('titik_awal');

        // 1. Minta rekomendasi tempat wisata dari Gemini API
        $rawPlaces = $this->geminiService->getRecommendedPlaces($kota, $kategori);

        if (empty($rawPlaces) || !is_array($rawPlaces)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan data rekomendasi dari Gemini API.'
            ], 500);
        }

        // Normalisasi key
        $normalizedPlaces = array_map(function ($item) {
            return [
                'nama_tempat' => $item['nama_tempat'] ?? $item['nama'] ?? 'Tempat Wisata',
                'estimasi_biaya' => (float) ($item['estimasi_biaya'] ?? $item['biaya'] ?? 0)
            ];
        }, $rawPlaces);

        // 2. Filter tempat menggunakan Optimasi Urutan Budget
        $budgetResult = $this->budgetService->filterByBudget($normalizedPlaces, $budget);
        $destinasiTerpilih = $budgetResult['destinasi_terpilih'] ?? [];

        if (empty($destinasiTerpilih)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Budget tidak cukup untuk mengunjungi destinasi rekomendasi.',
                'budget_info' => [
                    'total_budget' => $budget,
                    'total_terpakai' => 0,
                    'sisa_budget' => $budget
                ],
                'rute_perjalanan' => []
            ]);
        }

        // 3. Susun rute terurut berdasarkan jarak terdekat dari Titik Awal
        $startDest = Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($titikAwal) . '%')->first();
        
        $orderedDestinasi = [];
        if ($startDest) {
            // FILTER JARAK: Batasi destinasi agar hanya yang berjarak <= 10 km dari startDest
            $destinasiTerpilih = array_filter($destinasiTerpilih, function ($item) use ($startDest) {
                $dest = Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($item['nama_tempat']) . '%')->first();
                if (!$dest) return false;
                
                $dist = $this->calculateHaversine(
                    (float)$startDest->latitude, (float)$startDest->longitude,
                    (float)$dest->latitude, (float)$dest->longitude
                );
                return $dist <= 10.0; // Maksimal 10 km dari titik awal
            });
            $destinasiTerpilih = array_values($destinasiTerpilih); // reset index

            // Urutkan destinasi_terpilih berdasarkan jarak ke rute sebelumnya secara berurutan (Nearest Next Waypoint)
            $orderedDestinasi = [];
            $unvisited = $destinasiTerpilih;
            
            $currentLat = (float)$startDest->latitude;
            $currentLng = (float)$startDest->longitude;

            while (!empty($unvisited)) {
                $nearestIndex = -1;
                $minDist = PHP_FLOAT_MAX;
                
                foreach ($unvisited as $key => $item) {
                    $dest = Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($item['nama_tempat']) . '%')->first();
                    $lat = $dest ? (float)$dest->latitude : $currentLat;
                    $lng = $dest ? (float)$dest->longitude : $currentLng;
                    
                    $dist = $this->calculateHaversine($currentLat, $currentLng, $lat, $lng);
                    
                    if ($dist < $minDist) {
                        $minDist = $dist;
                        $nearestIndex = $key;
                    }
                }
                
                // Tambahkan yang terdekat ke list
                $nearestItem = $unvisited[$nearestIndex];
                $orderedDestinasi[] = $nearestItem;
                
                // Update current location
                $dest = Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($nearestItem['nama_tempat']) . '%')->first();
                if ($dest) {
                    $currentLat = (float)$dest->latitude;
                    $currentLng = (float)$dest->longitude;
                }
                
                unset($unvisited[$nearestIndex]);
            }
        }

        // Buat detail rute perjalanan
        $urutanPerjalanan = [$titikAwal];
        $detailRute = [];
        $totalJarak = 0.0;
        
        $prevLat = $startDest ? (float)$startDest->latitude : 0.0;
        $prevLng = $startDest ? (float)$startDest->longitude : 0.0;
        
        foreach ($destinasiTerpilih as $idx => $item) {
            $dest = Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($item['nama_tempat']) . '%')->first();
            $lat = $dest ? (float)$dest->latitude : $prevLat;
            $lng = $dest ? (float)$dest->longitude : $prevLng;
            
            $dist = 0.0;
            if ($idx === 0) {
                if ($startDest) {
                    $dist = $this->calculateHaversine($prevLat, $prevLng, $lat, $lng) * 1.3;
                }
            } else {
                $dist = $this->calculateHaversine($prevLat, $prevLng, $lat, $lng) * 1.3;
            }
            
            $totalJarak += $dist;
            $urutanPerjalanan[] = $item['nama_tempat'];
            $detailRute[] = [
                'dari' => $idx === 0 ? $titikAwal : $destinasiTerpilih[$idx - 1]['nama_tempat'],
                'ke' => $item['nama_tempat'],
                'jarak_km' => round($dist, 2),
                'durasi_menit' => (int)($dist * 1.5),
            ];
            
            $prevLat = $lat;
            $prevLng = $lng;
        }

        return response()->json([
            'status' => 'success',
            'budget_info' => [
                'total_budget' => $budget,
                'total_terpakai' => $budgetResult['total_biaya'] ?? 0,
                'sisa_budget' => $budgetResult['sisa_budget'] ?? $budget
            ],
            'destinasi_terpilih' => $destinasiTerpilih,
            'rute_terpendek' => [
                'urutan_perjalanan' => $urutanPerjalanan,
                'detail_perjalanan' => $detailRute,
                'total_jarak' => round($totalJarak, 2) . ' KM'
            ]
        ]);
    }

    /**
     * Hitung jarak Haversine antar koordinat.
     */
    private function calculateHaversine($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}