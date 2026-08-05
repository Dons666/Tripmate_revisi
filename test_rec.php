<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $userId = \App\Models\User::first()->id_user; 
    $rec = app(\App\Services\RecommendationService::class); 
    $corpus = $rec->buildCorpus($userId); 
    echo 'Corpus size: ' . count($corpus) . "\n"; 
    
    if(count($corpus) <= 1) { 
        $res = \App\Models\Destinasi::withAvg('ratings', 'rating')->withCount('ratings')->limit(15)->get(); 
    } else { 
        $docs = $rec->tokenizeCorpus($corpus); 
        $vocab = $rec->buildVocabulary($docs); 
        $wf = $rec->calculateWordFrequency($docs); 
        $tf = $rec->calculateTermFrequency($wf); 
        $df = $rec->calculateDocumentFrequency($docs, $vocab); 
        $idf = $rec->calculateInverseDocumentFrequency($df, count($docs)); 
        $tfidf = $rec->calculateTfIdfMatrix($tf, $idf); 
        $sim = $rec->calculateAllCosineSimilarity($tfidf); 
        $rank = $rec->rankRecommendations($sim); 
        $top = $rec->mixHiddenGemRecommendations($rank, $userId, 15); 
        $res = $rec->getRecommendationResults($top); 
    } 
    echo 'Result size: ' . count($res) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
