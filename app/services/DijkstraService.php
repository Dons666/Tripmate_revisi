<?php

namespace App\Services;

use App\Models\Destinasi;
use App\Models\JarakDestinasi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DijkstraService
{
    protected string $googleMapsApiKey;

    public function __construct()
    {
        $this->googleMapsApiKey = env('GOOGLE_MAPS_API_KEY', '');
    }

    /**
     * Menghitung rute optimal menggunakan jarak nyata (Google Maps API / Cache DB).
     */
    public function calculateRoute($startName, $endName, $destinations)
    {
        // 1. Ambil data koordinat untuk Titik Awal dan Titik Akhir langsung dari DB
        $startDest = Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($startName) . '%')->first();
        $endDest   = Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($endName) . '%')->first();

        if (!$startDest || !$endDest) {
            return ['error' => "Destinasi awal '$startName' atau akhir '$endName' tidak ditemukan di database."];
        }

        // 2. Dapatkan kandidat dalam koridor area (Bounding Box) jika $destinations kosong
        if (empty($destinations) || $destinations->isEmpty()) {
            $minLat = min((float) $startDest->latitude, (float) $endDest->latitude) - 0.02;
            $maxLat = max((float) $startDest->latitude, (float) $endDest->latitude) + 0.02;
            $minLng = min((float) $startDest->longitude, (float) $endDest->longitude) - 0.02;
            $maxLng = max((float) $startDest->longitude, (float) $endDest->longitude) + 0.02;

            $destinations = Destinasi::whereBetween('latitude', [$minLat, $maxLat])
                ->whereBetween('longitude', [$minLng, $maxLng])
                ->get();
        }

        // Kumpulkan semua titik unik yang terlibat dalam rute
        $nodes = collect();
        $nodes->push($startDest);
        foreach ($destinations as $d) {
            if ($d->id !== $startDest->id && $d->id !== $endDest->id) {
                $nodes->push($d);
            }
        }
        if ($startDest->id !== $endDest->id) {
            $nodes->push($endDest);
        }

        $nodes = $nodes->values();
        $count = $nodes->count();

        if ($count <= 1) {
            return $nodes->all();
        }

        // 3. Bangun matriks jarak geografis (Haversine) secara offline agar instan/cepat
        $distanceMatrix = [];
        for ($i = 0; $i < $count; $i++) {
            for ($j = 0; $j < $count; $j++) {
                if ($i === $j) {
                    $distanceMatrix[$i][$j] = 0.0;
                } else {
                    $distanceMatrix[$i][$j] = $this->calculateHaversineDistance(
                        (float)$nodes[$i]->latitude, (float)$nodes[$i]->longitude,
                        (float)$nodes[$j]->latitude, (float)$nodes[$j]->longitude
                    );
                }
            }
        }

        // 4. Jalankan Algoritma Greedy Nearest Neighbor berbasis matriks jarak Haversine
        $visited = array_fill(0, $count, false);
        $route = [0]; // Mulai dari startNode (index 0)
        $visited[0] = true;

        $currentNode = 0;
        $endNodeIndex = $count - 1; // EndNode diletakkan di akhir

        for ($i = 1; $i < $count - 1; $i++) {
            $nearestNode = null;
            $minDist = INF;

            for ($neighbor = 1; $neighbor < $count - 1; $neighbor++) {
                if (!$visited[$neighbor]) {
                    $dist = $distanceMatrix[$currentNode][$neighbor];
                    if ($dist < $minDist) {
                        $minDist = $dist;
                        $nearestNode = $neighbor;
                    }
                }
            }

            if ($nearestNode !== null) {
                $visited[$nearestNode] = true;
                $route[] = $nearestNode;
                $currentNode = $nearestNode;
            }
        }

        // Terakhir, tambahkan endNode jika startDest != endDest
        if ($startDest->id !== $endDest->id) {
            $route[] = $endNodeIndex;
        }

        // Susun daftar model Destinasi sesuai urutan rute optimal
        $orderedDestinations = [];
        foreach ($route as $index) {
            $orderedDestinations[] = $nodes[$index];
        }

        // 5. ENRICHMENT: Ambil jarak nyata dari Google Maps API (atau Cache DB)
        // HANYA untuk segmen berurutan pada rute final!
        $this->enrichRouteSegments($orderedDestinations);

        return $orderedDestinations;
    }

    /**
     * Mengambil jarak jalan raya nyata (Google Maps) hanya untuk segmen rute final terpilih.
     */
    protected function enrichRouteSegments(array $orderedRoute): void
    {
        $count = count($orderedRoute);
        if ($count <= 1) return;

        for ($i = 0; $i < $count - 1; $i++) {
            $asal = $orderedRoute[$i];
            $tujuan = $orderedRoute[$i + 1];

            // 1. Cek cache DB terlebih dahulu
            $cache = JarakDestinasi::where('asal_id', $asal->id)
                ->where('tujuan_id', $tujuan->id)
                ->first();

            if ($cache) {
                continue; // Sudah tercache, lanjut ke segmen berikutnya
            }

            // 2. Jika tidak ada di cache, panggil Google Maps API
            $distanceKm = null;
            $durationSec = null;

            if (!empty($this->googleMapsApiKey)) {
                try {
                    $response = Http::timeout(5)->get("https://maps.googleapis.com/maps/api/distancematrix/json", [
                        'origins' => "{$asal->latitude},{$asal->longitude}",
                        'destinations' => "{$tujuan->latitude},{$tujuan->longitude}",
                        'key' => $this->googleMapsApiKey
                    ]);

                    if ($response->successful() && ($response->json('status') === 'OK')) {
                        $elements = $response->json('rows.0.elements.0');
                        if (isset($elements['status']) && $elements['status'] === 'OK') {
                            $distanceKm = (double) ($elements['distance']['value'] / 1000);
                            $durationSec = (int) $elements['duration']['value'];
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Gagal memanggil Google Maps API di DijkstraService: " . $e->getMessage());
                }
            }

            // 3. Fallback Haversine jika Google API gagal atau key kosong
            if ($distanceKm === null) {
                $havDistance = $this->calculateHaversineDistance(
                    (float)$asal->latitude, (float)$asal->longitude,
                    (float)$tujuan->latitude, (float)$tujuan->longitude
                );
                $distanceKm = $havDistance * 1.3; // Estimasi berliku
                $durationSec = (int) ($distanceKm * 60); // Kecepatan rata-rata 60km/jam
            }

            // 4. Simpan ke database cache
            JarakDestinasi::updateOrCreate(
                ['asal_id' => $asal->id, 'tujuan_id' => $tujuan->id],
                ['jarak' => $distanceKm, 'durasi' => $durationSec]
            );
        }
    }

    /**
     * Rumus Haversine untuk jarak garis lurus bumi (dalam km).
     */
    protected function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}