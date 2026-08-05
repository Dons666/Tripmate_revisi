<?php

namespace App\Services;

use App\Models\Destinasi;
use Illuminate\Support\Facades\Log;

class DijkstraService
{
    /**
     * Menghitung rute optimal menggunakan jarak nyata berbasis Haversine (offline, instan & bebas DB).
     */
    public function calculateRoute($startName, $endName, $destinations)
    {
        $startDest = Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($startName) . '%')->first();
        $endDest   = Destinasi::where('nama_destinasi', 'LIKE', '%' . trim($endName) . '%')->first();

        if (!$startDest || !$endDest) {
            return ['error' => "Destinasi awal '$startName' atau akhir '$endName' tidak ditemukan di database."];
        }

        if (empty($destinations) || $destinations->isEmpty()) {
            $destinations = Destinasi::all();
        }

        // FILTER JARAK: Batasi semua destinasi agar berjarak maksimal 10 km dari titik awal
        $destinations = $destinations->filter(function ($d) use ($startDest) {
            $dist = $this->calculateHaversineDistance(
                (float)$startDest->latitude, (float)$startDest->longitude,
                (float)$d->latitude, (float)$d->longitude
            );
            return $dist <= 10.0; // Maksimal 10 km dari titik awal
        });

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

        $startNode = $nodes[0];
        $endNode = $nodes[$count - 1];

        $intermediateNodes = collect();
        for ($i = 1; $i < $count - 1; $i++) {
            $intermediateNodes->push($nodes[$i]);
        }

        $orderedDestinations = [];
        $orderedDestinations[] = $startNode;
        
        $currentNode = $startNode;
        while ($intermediateNodes->isNotEmpty()) {
            $nearestIndex = null;
            $minDist = PHP_FLOAT_MAX;

            foreach ($intermediateNodes as $key => $node) {
                $dist = $this->calculateHaversineDistance(
                    (float)$currentNode->latitude, (float)$currentNode->longitude,
                    (float)$node->latitude, (float)$node->longitude
                );
                if ($dist < $minDist) {
                    $minDist = $dist;
                    $nearestIndex = $key;
                }
            }

            $nextNode = $intermediateNodes[$nearestIndex];
            $orderedDestinations[] = $nextNode;
            $currentNode = $nextNode;
            $intermediateNodes->forget($nearestIndex);
        }

        if ($startNode->id !== $endNode->id) {
            $orderedDestinations[] = $endNode;
        }

        return $orderedDestinations;
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
