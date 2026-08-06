<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\Kategori;
use App\Models\PenyediaTravel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RecommendationService;

class HomeController extends Controller
{
    protected RecommendationService $recommendationService;

    public function __construct(
        RecommendationService $recommendationService
    ) {
        $this->recommendationService = $recommendationService;
    }

    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::check() && Auth::user()->isTravel()) {
            return redirect()->route('travel.dashboard');
        }

        // Ambil kategori dari database
        $kategoriWisata = \App\Models\KategoriWisata::orderBy('nama_kategori')->get();
        $kategoriPenginapan = \App\Models\KategoriPenginapan::orderBy('nama_kategori')->get();
        $kategoriKuliner = \App\Models\KategoriKuliner::orderBy('nama_kategori')->get();
        $kategoris = $kategoriWisata;

        /*
        |--------------------------------------------------------------------------
        | RUMUS BAYESIAN AVERAGE: 3 Tempat Terbaik
        | Rumus: (C * m + S * r) / (C + S)
        | - m: Global Average Rating seluruh destinasi ($globalAverage)
        | - C: Confidence threshold / Bobot (misal 50)
        | - S: Jumlah ulasan destinasi ($itemVotes)
        | - r: Average rating destinasi ($itemRating)
        |--------------------------------------------------------------------------
        */
        $top3Bayesian = Destinasi::query()
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->orderByDesc('rating_destinasi') // hasil perhitungan Bayesian Average
            ->take(3)
            ->get();

/*
|--------------------------------------------------------------------------
| Destinasi Populer
|--------------------------------------------------------------------------
*/

$query = Destinasi::query();

/*
|--------------------------------------------------------------------------
| Filter Kota
|--------------------------------------------------------------------------
*/

if ($request->filled('kota')) {

    $query->whereIn(
        'kota',
        $request->kota
    );

}

/*
|--------------------------------------------------------------------------
| Filter Kategori
|--------------------------------------------------------------------------
*/

if ($request->filled('kategori')) {

    $query->whereIn(
        'kategori',
        $request->kategori
    );

}

/*
|--------------------------------------------------------------------------
| Filter Budget
|--------------------------------------------------------------------------
*/

if ($request->filled('budget')) {

    switch ($request->budget) {

        case 'Gratis':

            $query->where('harga', 0);

            break;

        case 'Murah':

            $query
                ->where('harga', '>', 0)
                ->where('harga', '<=', 50000);

            break;

        case 'Sedang':

            $query
                ->where('harga', '>', 50000)
                ->where('harga', '<=', 150000);

            break;

        case 'Mahal':

            $query
                ->where('harga', '>', 150000);

            break;

    }

}

/*
|--------------------------------------------------------------------------
| Filter Hidden Gem
|--------------------------------------------------------------------------
*/

if ($request->filled('hidden_gem')) {

    $query->where(
        'hidden_gem',
        1
    );

}

/*
|--------------------------------------------------------------------------
| Ambil Destinasi
|--------------------------------------------------------------------------
*/

$destinasiPopuler = $query
    ->withAvg('ratings', 'rating')
    ->withCount('ratings')
    ->inRandomOrder()
    ->limit(6)
    ->get();




    
        // Default jika belum login
        $recommendations = collect();

        if (Auth::check()) {

            $userId = Auth::id();

            // STEP 1 - Corpus
            $corpus = $this->recommendationService
                ->buildCorpus($userId);

            // STEP 2 - Tokenisasi
            $documents = $this->recommendationService
                ->tokenizeCorpus($corpus);

            // STEP 3 - Vocabulary
            $vocabulary = $this->recommendationService
                ->buildVocabulary($documents);

            // STEP 4 - Word Frequency
            $wordFrequency = $this->recommendationService
                ->calculateWordFrequency($documents);

            // STEP 5 - TF
            $tf = $this->recommendationService
                ->calculateTermFrequency($wordFrequency);

            // STEP 6 - DF
            $df = $this->recommendationService
                ->calculateDocumentFrequency(
                    $documents,
                    $vocabulary
                );

            // STEP 7 - IDF
            $idf = $this->recommendationService
                ->calculateInverseDocumentFrequency(
                    $df,
                    count($documents)
                );

            // STEP 8 - TF-IDF
            $tfidf = $this->recommendationService
                ->calculateTfIdfMatrix(
                    $tf,
                    $idf
                );

            // STEP 9 - Cosine Similarity
            $similarity = $this->recommendationService
                ->calculateAllCosineSimilarity(
                    $tfidf
                );

            // STEP 10 - Ranking
            $ranking = $this->recommendationService
                ->rankRecommendations(
                    $similarity
                );

            // STEP 11 - Top 6 Recommendation
            $topRecommendations = $this->recommendationService
                ->getTopRecommendations(
                    $ranking,
                    6
                );

            // STEP 12 - Ambil data lengkap destinasi
            $recommendations = $this->recommendationService
                ->getRecommendationResults(
                    $topRecommendations
                );
        }

        $penyediaTravels = PenyediaTravel::where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        $paketTravels = \App\Models\Travel::with('user', 'destinasis', 'armada')
            ->latest()
            ->take(6)
            ->get();

        return view(
            'home',
            compact(
                'kategoris',
                'kategoriWisata',
                'kategoriPenginapan',
                'kategoriKuliner',
                'destinasiPopuler',
                'recommendations',
                'top3Bayesian',
                'penyediaTravels',
                'paketTravels'
            )
        );
    }

    public function dijkstra()
    {
        $destinasis = Destinasi::select('id', 'nama_destinasi', 'kota')->get();
        return view('rute-dijkstra', compact('destinasis'));
    }
}
