<?php

namespace App\Services;

use App\Models\Destinasi;

class BudgetRecommendationService
{
    /**
     * Rekomendasikan destinasi berdasarkan budget maksimum, kategori, dan kota (semua opsional)
     * langsung dari database.
     *
     * Mengembalikan destinasi yang harganya <= budget, diurutkan dari yang termurah,
     * beserta akumulasi biaya dan sisa budget.
     *
     * @param  float       $budget    Budget maksimum total perjalanan
     * @param  string|null $kategori  Filter kategori (e.g. 'Wisata Alam')
     * @param  string|null $kota      Filter kota (e.g. 'Bandung')
     * @return array
     */
    public function recommend($budget, $kategori = null, $kota = null, $jumlah_orang = 1): array
    {
        $query = Destinasi::query();

        // Filter kategori (partial match)
        if ($kategori) {
            $query->where('kategori', 'LIKE', '%' . $kategori . '%');
        }

        // Filter kota (partial match)
        if ($kota) {
            $query->where('kota', 'LIKE', '%' . $kota . '%');
        }

        // Urutkan dari yang termurah (berdasarkan harga tiket) agar budget terpakai secara efisien
        $query->orderBy('harga', 'asc');

        $destinations = $query
            ->select([
                'id', 'nama_destinasi', 'tipe', 'kategori', 'kota',
                'harga', 'deskripsi', 'gambar', 'rating_destinasi',
                'hidden_gem', 'latitude', 'longitude',
            ])
            ->limit(20)
            ->get();

        // Akumulasi biaya hingga budget habis
        $selectedDestinations = collect();
        $biayaMakanFlatPerTrip = 30000 * $jumlah_orang;
        $maxBudget            = (float) $budget;
        
        $accumulatedCost = $biayaMakanFlatPerTrip;
        if ($accumulatedCost > $maxBudget) {
            $accumulatedCost = $maxBudget; // Cap base cost so it doesn't exceed budget
        }

        foreach ($destinations as $dest) {
            $costTiket = ((float) $dest->harga) * $jumlah_orang;
            $costTransport = (10.0 / 35.0) * 15000.0;
            $totalItemCost = $costTiket + $costTransport;

            if (($accumulatedCost + $totalItemCost) <= $maxBudget) {
                $accumulatedCost += $totalItemCost;
                $selectedDestinations->push($dest);
            }
        }

        $remainingBudget = $maxBudget - $accumulatedCost;

        return [
            'recommendations'  => $selectedDestinations,
            'total_cost'       => round($accumulatedCost, -2),
            'remaining_budget' => max(0.0, round($remainingBudget, -2)),
            'count'            => $selectedDestinations->count(),
            'budget_max'       => $maxBudget,
        ];
    }

    /**
     * Memilih tempat wisata menggunakan metode Optimasi Urutan Budget (dari data array tempat).
     */
    public function filterByBudget(array $places, float $userBudget): array
    {
        if (empty($places)) {
            return [
                'total_biaya' => 0,
                'sisa_budget' => $userBudget,
                'destinasi_terpilih' => []
            ];
        }

        // Urutkan tempat berdasarkan estimasi_biaya terendah agar pas dengan budget
        usort($places, function ($a, $b) {
            return ($a['estimasi_biaya'] ?? 0) <=> ($b['estimasi_biaya'] ?? 0);
        });

        $selectedPlaces = [];
        $currentCost = 0;

        foreach ($places as $place) {
            $biaya = $place['estimasi_biaya'] ?? 0;

            if ($currentCost + $biaya <= $userBudget) {
                $selectedPlaces[] = $place;
                $currentCost += $biaya;
            }
        }

        return [
            'total_biaya' => $currentCost,
            'sisa_budget' => $userBudget - $currentCost,
            'destinasi_terpilih' => $selectedPlaces
        ];
    }
}
