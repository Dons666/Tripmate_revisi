<?php

namespace App\Services;

use App\Models\Destinasi;
use App\Models\UserPreference;

class RecommendationService
{
    /**
     * Mengambil data destinasi
     * untuk ditampilkan pada halaman rekomendasi.
     */
    public function getDestinations()
    {
        return Destinasi::select(
            'id',
            'nama_destinasi',
            'kategori',
            'kota',
            'harga',
            'fitur_cbf',
            'gambar'
        )->withAvg('ratings', 'rating')
         ->withCount('ratings')
         ->paginate(12);
    }

    /**
     * Mengambil preferensi pengguna.
     */
    public function getUserPreference($userId)
    {
        return UserPreference::where('id_user', $userId)->first();
    }

    /**
     * STEP 6
     * Membentuk query berdasarkan preferensi pengguna.
     *
     * Contoh:
     * bandung wisata alam wisata budaya murah hidden gem
     */
    public function buildPreferenceQuery($userId)
    {
        $preference = $this->getUserPreference($userId);

        if (!$preference) {
            return null;
        }

        $query = [];

        /*
        |--------------------------------------------------------------------------
        | Kota
        |--------------------------------------------------------------------------
        */
        if (!empty($preference->kota_preferensi)) {
            $query[] = strtolower($preference->kota_preferensi);
        }

        /*
        |--------------------------------------------------------------------------
        | Minat Wisata
        |--------------------------------------------------------------------------
        */
        if (!empty($preference->minat_wisata)) {

            foreach ($preference->minat_wisata as $item) {
                $query[] = strtolower($item);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Budget
        |--------------------------------------------------------------------------
        */
        if (!empty($preference->budget)) {
            $query[] = strtolower($preference->budget);
        }

        /*
        |--------------------------------------------------------------------------
        | Hidden Gem
        |--------------------------------------------------------------------------
        */
        if ($preference->hidden_gem) {
            $query[] = "hidden gem";
        }

        return implode(' ', $query);
    }

    /**
     * Mengambil seluruh fitur destinasi
     * yang akan digunakan pada proses rekomendasi.
     *
     * Candidate Filtering:
     * - Kota
     * - Budget
     * - Hidden Gem
     *
     * Kategori TIDAK difilter di SQL karena sudah
     * diperhitungkan oleh algoritma TF-IDF dan
     * Cosine Similarity melalui fitur_cbf.
     */
    public function getDestinationFeatures($userId)
    {
        $preference = $this->getUserPreference($userId);

        $query = Destinasi::select(
            'id',
            'nama_destinasi',
            'fitur_cbf'
        );

        /*
        |--------------------------------------------------------------------------
        | Filter Kota
        |--------------------------------------------------------------------------
        */
        if (
            $preference &&
            !empty($preference->kota_preferensi)
        ) {
            $query->where(
                'kota',
                'like',
                '%' . $preference->kota_preferensi . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Budget
        |--------------------------------------------------------------------------
        */
        if (
            $preference &&
            !empty($preference->budget)
        ) {

            /*
            |--------------------------------------------------------------------------
            | Identifikasi Jenis Destinasi
            |--------------------------------------------------------------------------
            */
            $minat = $preference->minat_wisata ?? [];

            $isPenginapan = in_array('Penginapan', $minat);

            $isKuliner = in_array('Wisata Kuliner', $minat);

            /*
            |--------------------------------------------------------------------------
            | Candidate Filtering Kategori
            |--------------------------------------------------------------------------
            */
            if ($isPenginapan) {
                $query->whereIn('kategori', [
                    'Hotel',
                    'Boutique Hotel',
                    'Guesthouse',
                    'Villa',
                    'Resort'
                ]);
            }

            if ($isKuliner) {
                $query->where('kategori', 'Wisata Kuliner');
            }

            switch (strtolower($preference->budget)) {

                case 'gratis':
                    if (!$isPenginapan && !$isKuliner) {
                        $query->where('harga', 0);
                    }
                    break;

                case 'murah':
                    if ($isPenginapan) {
                        $query->where('harga', '<=', 300000);
                    } elseif ($isKuliner) {
                        $query->where('harga', '<=', 100000);
                    } else {
                        $query->where('harga', '<=', 50000);
                    }
                    break;

                case 'sedang':
                    if ($isPenginapan) {
                        $query
                            ->where('harga', '>', 300000)
                            ->where('harga', '<=', 600000);
                    } elseif ($isKuliner) {
                        $query
                            ->where('harga', '>', 100000)
                            ->where('harga', '<=', 250000);
                    } else {
                        $query
                            ->where('harga', '>', 50000)
                            ->where('harga', '<=', 150000);
                    }
                    break;

                case 'mahal':
                    if ($isPenginapan) {
                        $query->where('harga', '>', 600000);
                    } elseif ($isKuliner) {
                        $query->where('harga', '>', 250000);
                    } else {
                        $query->where('harga', '>', 150000);
                    }
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Hidden Gem
        |--------------------------------------------------------------------------
        */
        if (
            $preference &&
            $preference->hidden_gem
        ) {
            // $query->where('hidden_gem', 1);
        }

        $hasil = $query->get();

        /*
        |--------------------------------------------------------------------------
        | [PERBAIKAN] Fallback
        | Jika filter terlalu ketat dan hasil 0,
        | longgarkan filter satu per satu.
        |--------------------------------------------------------------------------
        */
        if ($hasil->count() === 0 && $preference) {

            // Longgarkan: hanya filter kota saja
            $queryFallback = Destinasi::select('id', 'nama_destinasi', 'fitur_cbf');

            if (!empty($preference->kota_preferensi)) {
                $queryFallback->where('kota', 'like', '%' . $preference->kota_preferensi . '%');
            }

            $hasil = $queryFallback->get();
        }

        if ($hasil->count() === 0) {

            // Longgarkan lagi: ambil semua tanpa filter
            $hasil = Destinasi::select('id', 'nama_destinasi', 'fitur_cbf')->get();
        }

        return $hasil;
    }

    /**
     * STEP 7.1
     * Membentuk corpus.
     *
     * Corpus terdiri dari:
     * 1. Query pengguna
     * 2. Seluruh fitur_cbf destinasi
     */
    public function buildCorpus($userId)
    {
        $query = $this->buildPreferenceQuery($userId);

        $destinations = $this->getDestinationFeatures($userId);

        $corpus = [];

        /*
        |--------------------------------------------------------------------------
        | Dokumen pertama = Query User
        |--------------------------------------------------------------------------
        */
        $corpus[] = [
            'id' => 0,
            'nama_destinasi' => 'Query User',
            'fitur_cbf' => $query,
        ];

        /*
        |--------------------------------------------------------------------------
        | Dokumen berikutnya = Seluruh Destinasi
        |--------------------------------------------------------------------------
        */
        foreach ($destinations as $destination) {
            $corpus[] = [
                'id' => $destination->id,
                'nama_destinasi' => $destination->nama_destinasi,
                'fitur_cbf' => $destination->fitur_cbf,
            ];
        }

        return $corpus;
    }

    /**
     * STEP 7.2
     * Tokenisasi seluruh corpus.
     */
    public function tokenizeCorpus(array $corpus)
    {
        $documents = [];

        foreach ($corpus as $document) {
            $documents[] = [
                'id' => $document['id'],
                'nama_destinasi' => $document['nama_destinasi'],
                'tokens' => preg_split(
                    '/\s+/',
                    trim($document['fitur_cbf'] ?? '')
                ),
            ];
        }

        return $documents;
    }

    /**
     * STEP 7.3
     * Membentuk Vocabulary
     * (Seluruh kata unik dari semua dokumen).
     */
    public function buildVocabulary(array $documents)
    {
        $vocabulary = [];

        foreach ($documents as $document) {
            foreach ($document['tokens'] as $token) {
                $vocabulary[] = $token;
            }
        }

        $vocabulary = array_unique($vocabulary);
        sort($vocabulary);

        return array_values($vocabulary);
    }

    /**
     * STEP 7.4.1
     * Menghitung frekuensi setiap kata
     * pada masing-masing dokumen.
     */
    public function calculateWordFrequency(array $documents)
    {
        $frequencies = [];

        foreach ($documents as $document) {
            $frequencies[] = [
                'id' => $document['id'],
                'nama_destinasi' => $document['nama_destinasi'],
                'total_terms' => count($document['tokens']),
                'word_count' => array_count_values(
                    $document['tokens']
                )
            ];
        }

        return $frequencies;
    }

    /**
     * STEP 7.4.2
     * Menghitung nilai Term Frequency (TF).
     *
     * [PERBAIKAN] Menggunakan Sublinear TF
     *
     * Sebelum: TF(t,d) = f(t,d) / total_terms
     * Sesudah: TF(t,d) = 1 + log10( f(t,d) )
     *
     * Alasan: Raw TF mendiskriminasi dokumen pendek (query user)
     * terhadap dokumen panjang (fitur destinasi), menyebabkan
     * skor cosine similarity hampir sama untuk semua destinasi.
     */
    public function calculateTermFrequency(array $frequencies)
    {
        $tfDocuments = [];

        foreach ($frequencies as $document) {
            $tf = [];

            foreach ($document['word_count'] as $word => $count) {

                // Sublinear TF: 1 + log10(count)
                $tf[$word] = 1 + log10($count);

            }

            $tfDocuments[] = [
                'id' => $document['id'],
                'nama_destinasi' => $document['nama_destinasi'],
                'total_terms' => $document['total_terms'],
                'tf' => $tf
            ];
        }

        return $tfDocuments;
    }

    /**
     * STEP 7.5.1
     * Menghitung Document Frequency (DF).
     *
     * DF = jumlah dokumen yang mengandung suatu kata.
     */
    public function calculateDocumentFrequency(
        array $documents,
        array $vocabulary
    ) {
        $documentFrequency = [];

        foreach ($vocabulary as $word) {
            $documentFrequency[$word] = 0;

            foreach ($documents as $document) {
                if (in_array($word, $document['tokens'])) {
                    $documentFrequency[$word]++;
                }
            }
        }

        return $documentFrequency;
    }

    /**
     * STEP 7.5.2
     * Menghitung nilai Inverse Document Frequency (IDF).
     *
     * Rumus: IDF = log10(N / DF)
     */
    public function calculateInverseDocumentFrequency(
        array $documentFrequency,
        int $totalDocuments
    ) {
        $idf = [];

        foreach ($documentFrequency as $word => $df) {
            $idf[$word] = log10(
                $totalDocuments / $df
            );
        }

        return $idf;
    }

    /**
     * STEP 7.6
     * Membentuk Matriks TF-IDF.
     *
     * Rumus: TF-IDF = TF × IDF
     */
    public function calculateTfIdfMatrix(
        array $tfDocuments,
        array $idf
    ) {
        $tfidfDocuments = [];

        foreach ($tfDocuments as $document) {
            $vector = [];

            foreach ($document['tf'] as $word => $tf) {
                $vector[$word] = $tf * $idf[$word];
            }

            $tfidfDocuments[] = [
                'id' => $document['id'],
                'nama_destinasi' => $document['nama_destinasi'],
                'tfidf' => $vector
            ];
        }

        return $tfidfDocuments;
    }

    /**
     * STEP 12.1
     * Menghitung Dot Product
     * antara dua vektor TF-IDF.
     */
    public function calculateDotProduct(
        array $vectorA,
        array $vectorB
    ) {
        $dotProduct = 0;

        foreach ($vectorA as $word => $weight) {
            if (isset($vectorB[$word])) {
                $dotProduct += $weight * $vectorB[$word];
            }
        }

        return $dotProduct;
    }

    /**
     * STEP 12.2
     * Menghitung panjang (Magnitude) suatu vektor TF-IDF.
     *
     * Rumus: ||A|| = √Σ(A²)
     */
    public function calculateVectorMagnitude(array $vector)
    {
        $sum = 0;

        foreach ($vector as $weight) {
            $sum += pow($weight, 2);
        }

        return sqrt($sum);
    }

    /**
     * STEP 12.3
     * Menghitung Cosine Similarity.
     *
     * Rumus: Cosine = Dot Product / (|A| × |B|)
     */
    public function calculateCosineSimilarity(
        float $dotProduct,
        float $magnitudeA,
        float $magnitudeB
    ) {
        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0;
        }

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }

    /**
     * STEP 13
     * Menghitung Cosine Similarity
     * seluruh destinasi terhadap Query User.
     *
     * [PERBAIKAN] Menghapus dd() yang menghentikan pipeline.
     */
    public function calculateAllCosineSimilarity(array $tfidfDocuments)
    {
        // Query User adalah dokumen pertama
        $query = $tfidfDocuments[0];

        $queryVector = $query['tfidf'];

        $queryMagnitude = $this->calculateVectorMagnitude(
            $queryVector
        );

        $results = [];

        // Mulai dari index 1
        for ($i = 1; $i < count($tfidfDocuments); $i++) {

            $document = $tfidfDocuments[$i];

            $documentVector = $document['tfidf'];

            $dotProduct = $this->calculateDotProduct(
                $queryVector,
                $documentVector
            );

            $documentMagnitude = $this->calculateVectorMagnitude(
                $documentVector
            );

            $score = $this->calculateCosineSimilarity(
                $dotProduct,
                $queryMagnitude,
                $documentMagnitude
            );

            $results[] = [
                'id' => $document['id'],
                'nama_destinasi' => $document['nama_destinasi'],
                'score' => $score
            ];
        }

        return $results;
    }

    /**
     * STEP 14
     * Mengurutkan hasil rekomendasi
     * berdasarkan nilai Cosine Similarity
     * secara menurun (Descending).
     */
    public function rankRecommendations(array $results)
    {
        usort($results, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_values($results);
    }

    /**
     * STEP 14.5
     * Menyeimbangkan rekomendasi apabila
     * pengguna memilih Hidden Gem.
     */
    public function mixHiddenGemRecommendations(
        array $ranking,
        int $userId,
        int $limit = 15
    ) {
        $preference = $this->getUserPreference($userId);

        if (!$preference || !$preference->hidden_gem) {
            return array_slice($ranking, 0, $limit);
        }

        $candidateLimit = max($limit * 3, 30);

        $candidates = array_slice($ranking, 0, $candidateLimit);

        $hiddenGem = [];
        $regular = [];

        foreach ($candidates as $item) {
            $destination = Destinasi::find($item['id']);

            if (!$destination) {
                continue;
            }

            if (false /* $destination->hidden_gem */) {
                $hiddenGem[] = $item;
            } else {
                $regular[] = $item;
            }
        }

        $hiddenTarget = ceil($limit / 2);
        $regularTarget = $limit - $hiddenTarget;

        if (count($hiddenGem) < $hiddenTarget) {
            $regularTarget += $hiddenTarget - count($hiddenGem);
            $hiddenTarget = count($hiddenGem);
        }

        if (count($regular) < $regularTarget) {
            $hiddenTarget += $regularTarget - count($regular);
            $regularTarget = count($regular);
        }

        $result = array_merge(
            array_slice($hiddenGem, 0, $hiddenTarget),
            array_slice($regular, 0, $regularTarget)
        );

        usort($result, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return $result;
    }

    /**
     * STEP 15
     * Mengambil Top Recommendation
     * dengan prioritas Hidden Gem.
     *
     * [PERBAIKAN] Memperbaiki index slice
     * pada pengambilan sisa hidden gem dan normal.
     */
    public function getTopRecommendations(
        array $ranking,
        int $limit = 15,
        $userId = null
    ) {
        if (!$userId) {
            return array_slice($ranking, 0, $limit);
        }

        $preference = $this->getUserPreference($userId);

        if (!$preference || !$preference->hidden_gem) {
            return array_slice($ranking, 0, $limit);
        }

        $hiddenGem = [];
        $normal = [];

        foreach ($ranking as $item) {
            $destinasi = Destinasi::find($item['id']);

            if (!$destinasi) {
                continue;
            }

            if (false /* $destinasi->hidden_gem */) {
                $hiddenGem[] = $item;
            } else {
                $normal[] = $item;
            }
        }

        $half = ceil($limit / 2);

        $result = array_merge(
            array_slice($hiddenGem, 0, $half),
            array_slice($normal, 0, $limit - $half)
        );

        if (count($result) < $limit) {
            $remaining = array_slice($hiddenGem, $half);
            $result = array_merge(
                $result,
                array_slice($remaining, 0, $limit - count($result))
            );
        }

        if (count($result) < $limit) {
            $remaining = array_slice($normal, $limit - $half);
            $result = array_merge(
                $result,
                array_slice($remaining, 0, $limit - count($result))
            );
        }

        return $result;
    }

    /**
     * STEP 16
     * Mengambil data lengkap destinasi
     * berdasarkan hasil Top Recommendation.
     */
    public function getRecommendationResults(array $topRecommendations)
    {
        $results = [];

        foreach ($topRecommendations as $recommendation) {
            $destination = Destinasi::withAvg('ratings', 'rating')
                ->withCount('ratings')
                ->find($recommendation['id']);

            if (!$destination) {
                continue;
            }

            $destination->score = $recommendation['score'];

            $results[] = $destination;
        }

        return collect($results);
    }

    /**
     * STEP 17
     * Mengambil destinasi serupa
     * berdasarkan fitur_cbf destinasi aktif.
     */
    public function getSimilarDestinations(
        int $destinationId,
        int $limit = 4
    ) {
        $currentDestination = Destinasi::find($destinationId);

        if (!$currentDestination) {
            return collect();
        }

        $destinations = Destinasi::select(
            'id',
            'nama_destinasi',
            'fitur_cbf'
        )->withAvg('ratings', 'rating')
         ->withCount('ratings')
         ->get();

        $corpus = [];

        $corpus[] = [
            'id' => 0,
            'nama_destinasi' => 'Current Destination',
            'fitur_cbf' => $currentDestination->fitur_cbf,
        ];

        foreach ($destinations as $destination) {
            if ($destination->id == $destinationId) {
                continue;
            }

            $corpus[] = [
                'id' => $destination->id,
                'nama_destinasi' => $destination->nama_destinasi,
                'fitur_cbf' => $destination->fitur_cbf,
            ];
        }

        $documents = $this->tokenizeCorpus($corpus);
        $vocabulary = $this->buildVocabulary($documents);
        $wordFrequency = $this->calculateWordFrequency($documents);
        $tf = $this->calculateTermFrequency($wordFrequency);
        $df = $this->calculateDocumentFrequency($documents, $vocabulary);
        $idf = $this->calculateInverseDocumentFrequency($df, count($documents));
        $tfidf = $this->calculateTfIdfMatrix($tf, $idf);
        $similarity = $this->calculateAllCosineSimilarity($tfidf);
        $ranking = $this->rankRecommendations($similarity);
        $top = $this->getTopRecommendations($ranking, $limit);

        return $this->getRecommendationResults($top);
    }



        /**
     * Mengambil hasil candidate filtering
     * (Digunakan oleh RecommendationDebugController).
     */
    public function getCandidateFiltering($userId)
    {
        return $this->getDestinationFeatures($userId);
    }

    /**
     * Mengambil destinasi dengan rating yang sudah dihitung
     * menggunakan Bayesian Average formula untuk menghindari bias
     * dari destinasi dengan sedikit rating.
     * 
     * Sorted by Bayesian Average rating (descending).
     */
    public function getDestinationsWithBayesianRating($limit = null)
    {
        $destinations = Destinasi::select(
            'id',
            'nama_destinasi',
            'kategori',
            'kota',
            'harga',
            'fitur_cbf',
            'gambar'
        )->withAvg('ratings', 'rating')
         ->withCount('ratings')
         ->orderByDesc('ratings_avg_skor_rating');

        if ($limit) {
            return $destinations->limit($limit)->get();
        }

        return $destinations->paginate(12);
    }

    /**
     * Recalculate Bayesian Average untuk semua destinasi
     * Berguna untuk batch update setelah import data besar
     */
    public function recalculateAllBayesianAverages()
    {
        $destinasiIds = Destinasi::pluck('id');
        
        foreach ($destinasiIds as $destinasiId) {
            \App\Models\Rating::updateDestinationRating($destinasiId);
        }
        
        return count($destinasiIds);
    }
}
