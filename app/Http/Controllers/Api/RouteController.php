<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DijkstraService;
use App\Services\GoogleMapsDistanceService;
use Illuminate\Http\JsonResponse;

class RouteController extends Controller
{
    protected DijkstraService $dijkstraService;
    protected GoogleMapsDistanceService $googleMapsService;

    public function __construct(
        DijkstraService $dijkstraService,
        GoogleMapsDistanceService $googleMapsService
    ) {
        $this->dijkstraService = $dijkstraService;
        $this->googleMapsService = $googleMapsService;
    }

    /**
     * Hitung rute terpendek (Nearest-Neighbor / Dijkstra-like)
     * antara dua destinasi berdasarkan nama.
     *
     * GET /api/dijkstra/{start}/{end}
     */
    public function show(string $start, string $end): JsonResponse
    {
        $startName = urldecode($start);
        $endName   = urldecode($end);

        $route = $this->dijkstraService->calculateRoute($startName, $endName, collect());

        if (is_array($route) && isset($route['error'])) {
            return response()->json([
                'status'  => 'error',
                'message' => $route['error'],
            ], 404);
        }

        $nodes = collect($route);

        $totalDistance = 0.0;
        $totalDuration = 0;
        $formatted = [];

        for ($i = 0; $i < $nodes->count(); $i++) {
            $d = $nodes[$i];
            $distanceFromPrev = 0.0;
            $durationFromPrev = 0;

            if ($i > 0) {
                $prev = $nodes[$i - 1];
                // Dapatkan jarak via Google Maps Service
                $distData = $this->googleMapsService->getDistanceAndDuration(
                    (float)$prev->latitude, (float)$prev->longitude,
                    (float)$d->latitude, (float)$d->longitude
                );
                $distanceFromPrev = $distData['distance'];
                $durationFromPrev = $distData['duration'];

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

        return response()->json([
            'status'         => 'success',
            'route'          => $formatted,
            'total_nodes'    => $nodes->count(),
            'total_distance' => round($totalDistance, 2),
            'total_duration' => $totalDuration,
            'total_cost'     => (float) $nodes->sum('harga'),
            'saran_biaya_transport' => max(15000.0, round($totalDistance * 3000, -3)),
            'saran_biaya_makan'     => max(25000.0, $nodes->count() * 25000),
            'distance_source'=> 'Haversine Geographic Engine (Offline & Fast)',
        ]);
    }
}
