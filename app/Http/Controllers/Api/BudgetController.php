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
        ]);

        $data = $this->budgetService->recommend(
            $request->budget,
            $request->kategori,
            $request->kota,
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

        // 1. Dapatkan kandidat destinasi dalam budget DAN berada di dalam koridor rute (bounding box)
        $query = \App\Models\Destinasi::query();
        
        // Filter by budget
        $query->where('harga', '<=', $request->budget);

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

        // Ambil maksimal 30 destinasi kandidat
        $destinations = $query->orderBy('harga', 'asc')->limit(30)->get();

        // 2. Hitung rute waypoint menggunakan Dijkstra
        $rawRoute = $this->dijkstraService->calculateRoute(
            $request->start,
            $request->end,
            $destinations
        );

        if (is_array($rawRoute) && isset($rawRoute['error'])) {
            return response()->json(['message' => $rawRoute['error']], 404);
        }

        // 3. Pembatasan budget: akumulasikan biaya per destinasi
        $finalRoute      = [];
        $accumulatedCost = 0;
        $maxBudget       = (float) $request->budget;

        foreach ($rawRoute as $index => $place) {
            $cost = (float) ($place->harga ?? 0);

            // Titik awal dan akhir selalu dimasukkan
            if ($index === 0 || $index === (count($rawRoute) - 1)) {
                $accumulatedCost += $cost;
                $finalRoute[] = $place;
                continue;
            }

            if (($accumulatedCost + $cost) <= $maxBudget) {
                $accumulatedCost += $cost;
                $finalRoute[] = $place;
            }
        }

        $totalDistance = 0.0;
        $totalDuration = 0;
        $formatted = [];

        for ($i = 0; $i < count($finalRoute); $i++) {
            $d = $finalRoute[$i];
            $distanceFromPrev = 0.0;
            $durationFromPrev = 0;

            if ($i > 0) {
                $prev = $finalRoute[$i - 1];
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
                $totalDistance += $distanceFromPrev;
                $totalDuration += $durationFromPrev;
            }

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
            ];
        }

        $apiKey = env('GOOGLE_MAPS_API_KEY', '');

        return response()->json([
            'status'           => 'success',
            'route'            => $formatted,
            'total_cost'       => $accumulatedCost,
            'remaining_budget' => max(0.0, $maxBudget - $accumulatedCost),
            'total_nodes'      => count($finalRoute),
            'total_distance'   => round($totalDistance, 2),
            'total_duration'   => $totalDuration,
            'saran_biaya_transport' => max(15000.0, round($totalDistance * 3000, -3)),
            'saran_biaya_makan'     => max(25000.0, count($finalRoute) * 25000),
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
            'estimasi_makan_per_orang' => 'nullable|numeric|min:0',
            'estimasi_transport_per_orang' => 'nullable|numeric|min:0',
            'schedules'       => 'required|array',
            'schedules.*.destinasi_id' => 'required|exists:destinasi,id',
            'schedules.*.tanggal'      => 'required|date',
            'schedules.*.jam_mulai'    => 'nullable|date_format:H:i',
            'schedules.*.jam_selesai'  => 'nullable|date_format:H:i',
            'schedules.*.deskripsi'    => 'nullable|string',
        ]);

        // Validasi agar semua destinasi yang direkomendasikan wajib diatur jadwalnya
        $scheduleDestIds = collect($request->schedules)->pluck('destinasi_id')->toArray();
        foreach ($request->destinasi_ids as $destId) {
            if (!in_array($destId, $scheduleDestIds)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Setiap destinasi terpilih wajib diatur jadwal kunjungannya terlebih dahulu!',
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            /** @var \App\Models\TravelPlan $plan */
            $plan = $request->user()->travelPlans()->create([
                'nama_perjalanan' => $request->nama_perjalanan,
                'budget'          => $request->budget,
                'total_cost'      => $request->total_cost,
                'estimasi_makan_per_orang' => $request->estimasi_makan_per_orang ?? 0,
                'estimasi_transport_per_orang' => $request->estimasi_transport_per_orang ?? 0,
                'status'          => 'planning',
            ]);

            foreach ($request->destinasi_ids as $destinasiId) {
                $plan->destinasis()->attach($destinasiId);
            }

            // Simpan jadwal (schedules)
            foreach ($request->schedules as $sch) {
                $destName = \App\Models\Destinasi::find($sch['destinasi_id'])->nama_destinasi ?? '';
                $plan->schedules()->create([
                    'destinasi_id' => $sch['destinasi_id'],
                    'judul'        => 'Kunjungan ' . $destName,
                    'tanggal'      => $sch['tanggal'],
                    'jam_mulai'    => $sch['jam_mulai'] ?? null,
                    'jam_selesai'  => $sch['jam_selesai'] ?? null,
                    'deskripsi'    => $sch['deskripsi'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Rencana perjalanan berhasil disimpan!',
                'plan'    => $plan->load(['destinasis', 'schedules']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan plan: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan rencana perjalanan: ' . $e->getMessage(),
            ], 500);
        }
    }
}