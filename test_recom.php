<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(\App\Services\RecommendationService::class);
$userId = 1;
$corpus = $service->buildCorpus($userId);
echo "Corpus size: " . count($corpus) . PHP_EOL;

if (count($corpus) > 1) {
    try {
        $documents = $service->tokenizeCorpus($corpus);
        echo "Documents: " . count($documents) . PHP_EOL;
        $vocabulary = $service->buildVocabulary($documents);
        $wordFreq = $service->calculateWordFrequency($documents);
        $tf = $service->calculateTermFrequency($wordFreq);
        $df = $service->calculateDocumentFrequency($documents, $vocabulary);
        $idf = $service->calculateInverseDocumentFrequency($df, count($documents));
        $tfidf = $service->calculateTfIdfMatrix($tf, $idf);
        $allSim = $service->calculateAllCosineSimilarity($tfidf);
        $ranking = $service->rankRecommendations($allSim);
        echo "Ranking size: " . count($ranking) . PHP_EOL;
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . PHP_EOL;
    }
} else {
    echo "No corpus or too small corpus.\n";
}
