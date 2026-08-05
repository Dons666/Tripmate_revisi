<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleMapsDistanceService
{
    /**
     * Dapatkan jarak dan durasi menggunakan Cache lalu OSRM API (Fallback Haversine)
     * Mengembalikan array: ['distance' => km, 'duration' => menit, 'source' => 'osrm'|'haversine']
     */
    public function getDistanceAndDuration(float $lat1, float $lon1, float $lat2, float $lon2): array
    {
        $cacheKey = "dist_osrm_v1_{$lat1}_{$lon1}_{$lat2}_{$lon2}";
        
        // 1. Cek apakah ada data valid yang sudah di-cache dari OSRM sebelumnya
        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);
            if (is_array($cachedData) && ($cachedData['source'] ?? '') === 'osrm') {
                return $cachedData;
            }
        }

        // 2. Coba tembak OSRM API (Gratis, Tanpa API Key & Tanpa Billing)
        try {
            // OSRM menggunakan format Longitude dulu baru Latitude
            $url = "http://router.project-osrm.org/route/v1/driving/{$lon1},{$lat1};{$lon2},{$lat2}?overview=false";
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
                if (($data['code'] ?? '') === 'Ok' && isset($data['routes'][0])) {
                    $route = $data['routes'][0];
                    
                    // Jarak dari OSRM dalam meter, konversi ke KM
                    $distanceKm = $route['distance'] / 1000.0;
                    
                    // Durasi dari OSRM dalam detik, konversi ke Menit
                    $durationSec = $route['duration'];
                    $durationMin = (int) ceil($durationSec / 60.0);

                    $result = [
                        'distance' => round($distanceKm, 2),
                        'duration' => $durationMin,
                        'source'   => 'osrm'
                    ];

                    // Simpan di Cache jika responnya SUKSES
                    Cache::put($cacheKey, $result, now()->addDays(30));
                    
                    return $result;
                }
            }
        } catch (\Exception $e) {
            Log::error("OSRM API Exception: " . $e->getMessage());
        }

        // 3. Fallback ke Rumus Haversine jika OSRM API gagal
        return $this->calculateFallbackHaversine($lat1, $lon1, $lat2, $lon2);
    }

    /**
     * Kalkulasi Jarak & Waktu (Rumus Matematika Haversine)
     */
    private function calculateFallbackHaversine(float $lat1, float $lon1, float $lat2, float $lon2): array
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distanceLine = $earthRadius * $c;
        
        // Kalikan dengan faktor pembengkokan jalan (Winding factor)
        $distanceKm = $distanceLine * 1.3;
        
        // Estimasi kecepatan 40km/jam -> 1.5 menit per km
        $durationMin = (int) ceil($distanceKm * 1.5);

        return [
            'distance' => round($distanceKm, 2),
            'duration' => $durationMin,
            'source'   => 'haversine'
        ];
    }
}
