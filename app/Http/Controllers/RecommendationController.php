<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
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

        $corpus = $this->recommendationService
            ->buildCorpus($userId);

        $documents = $this->recommendationService
            ->tokenizeCorpus($corpus);

        $vocabulary = $this->recommendationService
            ->buildVocabulary($documents);

        $wordFrequency = $this->recommendationService
            ->calculateWordFrequency($documents);

        $tf = $this->recommendationService
            ->calculateTermFrequency($wordFrequency);

        $df = $this->recommendationService
            ->calculateDocumentFrequency(
                $documents,
                $vocabulary
            );

        $idf = $this->recommendationService
            ->calculateInverseDocumentFrequency(
                $df,
                count($documents)
            );

        $tfidf = $this->recommendationService
            ->calculateTfIdfMatrix(
                $tf,
                $idf
            );

  $allSimilarity = $this->recommendationService
    ->calculateAllCosineSimilarity(
        $tfidf
    );

$ranking = $this->recommendationService
    ->rankRecommendations(
        $allSimilarity
    );

$topRecommendations = $this->recommendationService
    ->mixHiddenGemRecommendations(
        $ranking,
        $userId,
        15
    );

$recommendations = $this->recommendationService
    ->getRecommendationResults(
        $topRecommendations
    );

return view(
    'recommendations.index',
    compact('recommendations')
);
    }
}
