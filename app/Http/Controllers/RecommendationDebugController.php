<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\RecommendationService;

class RecommendationDebugController extends Controller
{
    protected RecommendationService $recommendationService;

    public function __construct(
        RecommendationService $recommendationService
    ) {
        $this->recommendationService = $recommendationService;
    }

    public function index()
    {
        $userId = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | STEP 1 - Query User
        |--------------------------------------------------------------------------
        */
        $query = $this->recommendationService
            ->buildPreferenceQuery($userId);

        /*
        |--------------------------------------------------------------------------
        | STEP 2 - Candidate Filtering
        |--------------------------------------------------------------------------
        */
        $candidateFiltering = $this->recommendationService
            ->getCandidateFiltering($userId);

        /*
        |--------------------------------------------------------------------------
        | STEP 3 - Corpus
        |--------------------------------------------------------------------------
        */
        $corpus = $this->recommendationService
            ->buildCorpus($userId);

        /*
        |--------------------------------------------------------------------------
        | STEP 4 - Tokenisasi Corpus
        |--------------------------------------------------------------------------
        */
        $documents = $this->recommendationService
            ->tokenizeCorpus($corpus);

        /*
        |--------------------------------------------------------------------------
        | STEP 5 - Vocabulary
        |--------------------------------------------------------------------------
        */
        $vocabulary = $this->recommendationService
            ->buildVocabulary($documents);

        /*
        |--------------------------------------------------------------------------
        | STEP 6 - Word Frequency
        |--------------------------------------------------------------------------
        */
        $wordFrequency = $this->recommendationService
            ->calculateWordFrequency($documents);

        /*
        |--------------------------------------------------------------------------
        | STEP 7 - Term Frequency
        |--------------------------------------------------------------------------
        */
        $tf = $this->recommendationService
            ->calculateTermFrequency($wordFrequency);

        /*
        |--------------------------------------------------------------------------
        | STEP 8 - Document Frequency
        |--------------------------------------------------------------------------
        */
        $df = $this->recommendationService
            ->calculateDocumentFrequency(
                $documents,
                $vocabulary
            );

        /*
        |--------------------------------------------------------------------------
        | STEP 9 - Inverse Document Frequency
        |--------------------------------------------------------------------------
        */
        $idf = $this->recommendationService
            ->calculateInverseDocumentFrequency(
                $df,
                count($documents)
            );

        /*
        |--------------------------------------------------------------------------
        | STEP 10 - TF-IDF Matrix
        |--------------------------------------------------------------------------
        */
        $tfidf = $this->recommendationService
            ->calculateTfIdfMatrix(
                $tf,
                $idf
            );

        /*
        |--------------------------------------------------------------------------
        | STEP 11 - Cosine Similarity
        |--------------------------------------------------------------------------
        */
        $similarity = $this->recommendationService
            ->calculateAllCosineSimilarity(
                $tfidf
            );

        /*
        |--------------------------------------------------------------------------
        | STEP 12 - Ranking
        |--------------------------------------------------------------------------
        */
        $ranking = $this->recommendationService
            ->rankRecommendations(
                $similarity
            );

        /*
        |--------------------------------------------------------------------------
        | STEP 13 - Top Recommendation
        |--------------------------------------------------------------------------
        */
        $top = $this->recommendationService
            ->getTopRecommendations(
                $ranking,
                10
            );

        return view(
            'recommendations.debug',
            compact(
                'query',
                'candidateFiltering',
                'corpus',
                'documents',
                'vocabulary',
                'wordFrequency',
                'tf',
                'df',
                'idf',
                'tfidf',
                'similarity',
                'ranking',
                'top'
            )
        );
    }
}
