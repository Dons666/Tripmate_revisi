<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BudgetRecommendationService;
use App\Services\DijkstraService;
use App\Models\TravelPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BudgetController extends Controller
{
    protected BudgetRecommendationService $budgetService;
    protected DijkstraService $dijkstraService;

    public function __construct(
        BudgetRecommendationService $budgetService,
        DijkstraService $dijkstraService
    ) {
        $this->budgetService   = $budgetService;
        $this->dijkstraService = $dijkstraService;
    }

    /**
     * Rekomendasi destinasi berdasarkan budget, kategori, dan kota.
     *
     * POST /api/budget-recommendation
     * Body: { budget: float, kategori?: string, kota?: string }
     */
    public function recommend(Request $request)
    {
        $request->validate([
            'budget'   => 'required|numeric|min:0',
            'kategori' => 'nullable|string|max:100',
            'kota'     => 'nullable|string|max:100',
            'jumlah_orang' => 'nullable|integer|min:1',
        ]);

        $data = $this->budgetService->recommend(
            $request->budget,
            $request->kategori,
            $request->kota,
            $request->input('jumlah_orang', 1)
        );

        return response()->json([
            'status'           => 'success',
            'recommendations'  => $data['recommendations'],
            'total_cost'       => $data['total_cost'],
            'remaining_budget' => $data['remaining_budget'],
            'count'            => $data['count'],
            'budget_max'       => $data['budget_max'],
        ]);
    }

    /**
     * Hitung rute terintegrasi berdasarkan budget dan titik awal–akhir.
     *
     * POST /api/integrated-route
     * Body: { start: string, end: string, budget: float, kategori?: string, kota?: string }
     */
    public function getIntegratedRoute(Request $request)
    {
        $request->validate([
            'start'    => 'required|string',
            'end'      => 'required|string',
            'budget'   => 'required|numeric',
            'kategori' => 'nullable|string',
            'kota'     => 'nullable|string',
        ]);

        // Resolve start and end destinations first to check coordinates and kota
        $startDest = \App\Models\Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($request->start) . '%')->first();
        $endDest   = \App\Models\Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($request->end) . '%')->first();

        if (!$startDest || !$endDest) {
            return response()->json(['message' => "Destinasi awal atau akhir tidak ditemukan di database."], 404);
        }

        $jumlah_orang = (int) $request->input('jumlah_orang', 1);

        // 1. Dapatkan kandidat destinasi dalam budget DAN berada di dalam koridor rute (bounding box)
        $query = \App\Models\Destinasi::query();
        
        // Filter tiket per orang tidak melebihi total budget
        $query->where('harga', '<=', $request->budget / max(1, $jumlah_orang));

        // Filter by category if provided
        if ($request->kategori) {
            $query->where('kategori', 'LIKE', '%' . $request->kategori . '%');
        }

        // Filter by city: if start and end are in the same city, filter by that city.
        // Otherwise, use geographic bounding box.
        if ($request->kota) {
            $query->where('kota', 'LIKE', '%' . $request->kota . '%');
        } else {
            // Toleransi pencocokan kota untuk area Bandung (Bandung, Bandung Barat, Lembang)
            $startCityClean = strtolower(str_replace(['kabupaten', 'kota', ' '], '', $startDest->kota ?? ''));
            $endCityClean = strtolower(str_replace(['kabupaten', 'kota', ' '], '', $endDest->kota ?? ''));
            
            if (!empty($startCityClean) && !empty($endCityClean) && 
                (str_contains($startCityClean, 'bandung') || str_contains($startCityClean, 'lembang')) && 
                (str_contains($endCityClean, 'bandung') || str_contains($endCityClean, 'lembang'))) {
                $query->where(function($q) {
                    $q->where('kota', 'LIKE', '%Bandung%')
                      ->orWhere('kota', 'LIKE', '%Lembang%');
                });
            } else if (!empty($startCityClean) && ($startCityClean === $endCityClean)) {
                $query->where('kota', $startDest->kota);
            } else {
                // Gunakan pembatasan Bounding Box geografis agar rekomendasinya searah/dilewati
                $minLat = min((float) $startDest->latitude, (float) $endDest->latitude) - 0.05;
                $maxLat = max((float) $startDest->latitude, (float) $endDest->latitude) + 0.05;
                $minLng = min((float) $startDest->longitude, (float) $endDest->longitude) - 0.05;
                $maxLng = max((float) $startDest->longitude, (float) $endDest->longitude) + 0.05;

                $query->whereBetween('latitude', [$minLat, $maxLat])
                      ->whereBetween('longitude', [$minLng, $maxLng]);
            }
        }

        // Ambil maksimal 50 destinasi kandidat, prioritaskan tempat gratis & murah dulu
        $destinations = $query->orderBy('harga', 'asc')->limit(50)->get();

        // 2. Hitung rute waypoint menggunakan Dijkstra
        $rawRoute = $this->dijkstraService->calculateRoute(
            $request->start,
            $request->end,
            $destinations
        );

        if (is_array($rawRoute) && isset($rawRoute['error'])) {
            return response()->json(['message' => $rawRoute['error']], 404);
        }

        $jumlah_orang = (int) $request->input('jumlah_orang', 1);
        $biayaMakanFlatPerTrip = 30000 * $jumlah_orang;
        $extraBiayaMakan = 0.0;

        $finalRoute      = [];
        $baseCost = ($biayaMakanFlatPerTrip);
        $maxBudget       = (float) $request->budget;
        
        $accumulatedCost = $baseCost;
        if ($accumulatedCost > $maxBudget) {
            $accumulatedCost = $maxBudget;
        }

        $totalDistance = 0.0;
        $totalDuration = 0;
        $formatted = [];

        $endNode = count($rawRoute) > 1 ? $rawRoute[count($rawRoute) - 1] : null;
        $costTiketEnd = $endNode ? (((float) ($endNode->harga ?? 0)) * $jumlah_orang) : 0.0;

        foreach ($rawRoute as $index => $d) {
            $costTiket = ((float) ($d->harga ?? 0)) * $jumlah_orang;
            $distanceFromPrev = 0.0;
            $durationFromPrev = 0;
            $costTransport = 0.0;

            if ($index > 0) {
                $prev = $finalRoute[count($finalRoute) - 1];
                $distRecord = \App\Models\JarakDestinasi::where('asal_id', $prev->id)
                    ->where('tujuan_id', $d->id)
                    ->first();
                if ($distRecord) {
                    $distanceFromPrev = (double) $distRecord->jarak;
                    $durationFromPrev = (int) $distRecord->durasi;
                } else {
                    $dx = (float)$d->latitude - (float)$prev->latitude;
                    $dy = (float)$d->longitude - (float)$prev->longitude;
                    $distanceFromPrev = sqrt($dx*$dx + $dy*$dy) * 111.0 * 1.3;
                    $durationFromPrev = (int) ($distanceFromPrev * 60);
                }
                // Transportasi: 1 liter per 35 km, 1 liter = 15000
                $costTransport = ($distanceFromPrev / 35.0) * 15000.0;
            }

            $totalItemCost = $costTiket + $costTransport;

            // Deteksi tempat kuliner / makanan
            $kat = strtolower(($d->kategori ?? '') . ' ' . ($d->tipe ?? '') . ' ' . ($d->nama_destinasi ?? ''));
            $isKuliner = str_contains($kat, 'kuliner') || str_contains($kat, 'makan') || 
                         str_contains($kat, 'resto') || str_contains($kat, 'warung') || 
                         str_contains($kat, 'cafe') || str_contains($kat, 'kopi') || 
                         str_contains($kat, 'bubur') || str_contains($kat, 'batagor') ||
                         str_contains($kat, 'artisan tea');

            // Titik awal dan akhir selalu dimasukkan (jika budget cukup)
            if ($index === 0 || $index === (count($rawRoute) - 1)) {
                if (($accumulatedCost + $totalItemCost) > $maxBudget) {
                    continue; // Skip jika budget benar-benar tidak cukup
                }
                $accumulatedCost += $totalItemCost;
                if ($isKuliner) {
                    $extraBiayaMakan += $costTiket;
                }
                $finalRoute[] = $d;
                $totalDistance += $distanceFromPrev;
                $totalDuration += $durationFromPrev;

                $formatted[] = [
                    'id'             => $d->id,
                    'nama_destinasi' => $d->nama_destinasi,
                    'kategori'       => $d->kategori,
                    'kota'           => $d->kota,
                    'harga'          => (float) $d->harga,
                    'latitude'       => (float) $d->latitude,
                    'longitude'      => (float) $d->longitude,
                    'gambar'         => $d->image_url,
                    'jarak_dari_sebelumnya' => round($distanceFromPrev, 2),
                    'durasi_dari_sebelumnya' => $durationFromPrev,
                    'is_kuliner'     => $isKuliner,
                ];
                continue;
            }

            // Estimasi sisa biaya perjalanan dari tempat perantara ini ke titik akhir (End Node)
            $distanceToEnd = 0.0;
            if ($endNode) {
                $distRecordEnd = \App\Models\JarakDestinasi::where('asal_id', $d->id)
                    ->where('tujuan_id', $endNode->id)
                    ->first();
                if ($distRecordEnd) {
                    $distanceToEnd = (double) $distRecordEnd->jarak;
                } else {
                    $dx = (float)$endNode->latitude - (float)$d->latitude;
                    $dy = (float)$endNode->longitude - (float)$d->longitude;
                    $distanceToEnd = sqrt($dx*$dx + $dy*$dy) * 111.0 * 1.3;
                }
            }
            $costTransportEnd = ($distanceToEnd / 35.0) * 15000.0;
            $projectedEndCost = $costTiketEnd + $costTransportEnd;

            // Masukkan tempat perantara hanya jika (biaya terakumulasi + biaya tempat ini + estimasi biaya pulang/ke titik akhir) <= budget
            if (($accumulatedCost + $totalItemCost + $projectedEndCost) <= $maxBudget) {
                $accumulatedCost += $totalItemCost;
                if ($isKuliner) {
                    $extraBiayaMakan += $costTiket;
                }
                $finalRoute[] = $d;
                $totalDistance += $distanceFromPrev;
                $totalDuration += $durationFromPrev;

                $formatted[] = [
                    'id'             => $d->id,
                    'nama_destinasi' => $d->nama_destinasi,
                    'kategori'       => $d->kategori,
                    'kota'           => $d->kota,
                    'harga'          => (float) $d->harga,
                    'latitude'       => (float) $d->latitude,
                    'longitude'      => (float) $d->longitude,
                    'gambar'         => $d->image_url,
                    'jarak_dari_sebelumnya' => round($distanceFromPrev, 2),
                    'durasi_dari_sebelumnya' => $durationFromPrev,
                    'is_kuliner'     => $isKuliner,
                ];
            }
        }

        $apiKey = env('GOOGLE_MAPS_API_KEY', '');
        $saranTransport = round(($totalDistance / 35.0) * 15000.0, -2);
        $saranMakanTotal = round($biayaMakanFlatPerTrip + $extraBiayaMakan, -2);

        return response()->json([
            'status'           => 'success',
            'route'            => $formatted,
            'total_cost'       => round($accumulatedCost, -2),
            'remaining_budget' => max(0.0, round($maxBudget - $accumulatedCost, -2)),
            'total_nodes'      => count($finalRoute),
            'total_distance'   => round($totalDistance, 2),
            'total_duration'   => $totalDuration,
            'saran_biaya_transport' => $saranTransport,
            'saran_biaya_makan'     => $saranMakanTotal,
            'distance_source'  => !empty($apiKey) ? 'Google Maps Road API' : 'Haversine Geographic Fallback',
        ]);
    }

    /**
     * Simpan hasil rute ke Travel Plan.
     *
     * POST /api/save-trip-plan  (auth:sanctum)
     * Body: { nama_perjalanan: string, budget: float, total_cost: float, destinasi_ids: int[] }
     */
    public function saveToPlan(Request $request)
    {
        $request->validate([
            'nama_perjalanan' => 'required|string|max:255',
            'budget'          => 'required|numeric',
            'total_cost'      => 'required|numeric',
            'destinasi_ids'   => 'required|array|min:1',
            'destinasi_ids.*' => 'exists:destinasi,id',
            'jumlah_peserta'  => 'nullable|integer|min:1',
            'estimasi_makan_per_orang' => 'nullable|numeric|min:0',
            'estimasi_transport_per_orang' => 'nullable|numeric|min:0',
            'schedules'       => 'nullable|array',
            'schedules.*.destinasi_id' => 'required|exists:destinasi,id',
            'schedules.*.tanggal'      => 'required|date',
            'schedules.*.jam_mulai'    => 'nullable|date_format:H:i',
            'schedules.*.jam_selesai'  => 'nullable|date_format:H:i',
            'schedules.*.deskripsi'    => 'nullable|string',
        ]);

        try {
            $schedulesPayload = [];
            $schedulesInput = $request->schedules ?: [];

            if (empty($schedulesInput)) {
                // Jika jadwal kosong, buat slot jadwal kosong default untuk semua destinasi terpilih
                foreach ($request->destinasi_ids as $destId) {
                    $schedulesPayload[] = [
                        'destinasi_id' => (int) $destId,
                        'is_visited'   => false,
                        'tanggal'      => now()->toDateString(),
                        'jam_mulai'    => null,
                        'jam_selesai'  => null,
                        'catatan'      => null,
                    ];
                }
            } else {
                foreach ($schedulesInput as $sch) {
                    $schedulesPayload[] = [
                        'destinasi_id' => (int) $sch['destinasi_id'],
                        'is_visited'   => false,
                        'tanggal'      => $sch['tanggal'],
                        'jam_mulai'    => $sch['jam_mulai'] ?? null,
                        'jam_selesai'  => $sch['jam_selesai'] ?? null,
                        'catatan'      => $sch['deskripsi'] ?? null,
                    ];
                }
            }

            $firstDestId = $request->destinasi_ids[0] ?? null;
            $tujuan = 'Bandung';
            if ($firstDestId) {
                $dest = \App\Models\Destinasi::find($firstDestId);
                if ($dest && $dest->kota) {
                    $tujuan = $dest->kota;
                }
            }

            /** @var \App\Models\TravelPlan $plan */
            $plan = $request->user()->travelPlans()->create([
                'nama_perjalanan' => $request->nama_perjalanan,
                'tujuan'          => $tujuan,
                'budget'          => $request->budget,
                'total_cost'      => $request->total_cost,
                'jumlah_peserta'  => $request->jumlah_peserta ?? 1,
                'estimasi_makan_per_orang' => $request->estimasi_makan_per_orang ?? 0,
                'estimasi_transport_per_orang' => $request->estimasi_transport_per_orang ?? 0,
                'schedules_json'  => $schedulesPayload,
                'status'          => 'planning',
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Rencana perjalanan berhasil disimpan!',
                'plan'    => $plan,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan plan: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan rencana perjalanan: ' . $e->getMessage(),
            ], 500);
        }
    }
}