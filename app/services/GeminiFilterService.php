<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiFilterService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', config('services.gemini.api_key', ''));
    }

    public function getRecommendedPlaces(string $kota, string $kategori): array
    {
        $prompt = "Berikan 10 rekomendasi tempat wisata di kota {$kota} untuk kategori {$kategori}. " .
                  "Respon HARUS HANYA berupa JSON array valid tanpa format markdown. " .
                  "Format skema JSON: [{\"nama_tempat\": \"Nama Lokasi\", \"estimasi_biaya\": 50000}]. " .
                  "Nilai estimasi_biaya harus angka integer Rupiah.";

        // Model Gemini terbaru yang aktif
        $models = [
            'gemini-2.5-flash',
            'gemini-2.0-flash',
            'gemini-1.5-flash'
        ];

        if (!empty($this->apiKey)) {
            foreach ($models as $modelName) {
                try {
                    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$this->apiKey}";

                    $response = Http::retry(2, 500)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($url, [
                            'contents' => [
                                ['parts' => [['text' => $prompt]]]
                            ]
                        ]);

                    if ($response->successful()) {
                        $text = $response->json('candidates.0.content.parts.0.text');

                        if ($text) {
                            $cleanJson = preg_replace('/^```json\s*|\s*```$/m', '', trim($text));
                            $data = json_decode($cleanJson, true);

                            if (is_array($data) && !empty($data)) {
                                return $data;
                            }
                        }
                    } else {
                        Log::warning("Gemini Model {$modelName} HTTP Error: " . $response->status() . " - " . $response->body());
                    }

                } catch (\Exception $e) {
                    Log::error("Gemini Exception ({$modelName}): " . $e->getMessage());
                }
            }
        } else {
            Log::error("Gemini API Key tidak ditemukan di .env!");
        }

        Log::warning("Menggunakan Fallback Local Data karena Gemini API tidak dapat dijangkau.");

        // Fallback Data agar sistem Dijkstra & Optimasi Budget kamu tetap bisa di-test berjalan
        return [
            ['nama_tempat' => "Alun-Alun {$kota}", 'estimasi_biaya' => 0],
            ['nama_tempat' => "Museum Kota {$kota}", 'estimasi_biaya' => 20000],
            ['nama_tempat' => "Taman Kota {$kota}", 'estimasi_biaya' => 10000],
            ['nama_tempat' => "Hutan Pinus {$kota}", 'estimasi_biaya' => 35000],
            ['nama_tempat' => "Wisata Kuliner Lokal {$kota}", 'estimasi_biaya' => 50000],
            ['nama_tempat' => "Puncak Pandangan {$kota}", 'estimasi_biaya' => 25000],
        ];
    }

    /**
     * Moderasi komentar/review dengan Gemini AI, fallback ke local filter jika error/offline.
     */
    public function analyzeComment(string $comment): array
    {
        $prompt = "Lakukan moderasi konten pada komentar berikut. Periksa apakah komentar ini aman (tidak mengandung ujaran kebencian, sara, pornografi, pelecehan, spam, atau kata-kata kasar/kotor ekstrem).\n\n" .
                  "Komentar: \"{$comment}\"\n\n" .
                  "Respon HARUS HANYA berupa JSON valid tanpa format markdown dengan format berikut:\n" .
                  "{\"is_safe\": true/false, \"reason\": \"Alasan pemblokiran (jika tidak aman, kosongkan jika aman)\"}\n" .
                  "PENTING: Jangan berikan teks penjelasan lain, hanya JSON saja.";

        $models = [
            'gemini-2.5-flash',
            'gemini-2.0-flash',
            'gemini-1.5-flash'
        ];

        if (!empty($this->apiKey)) {
            foreach ($models as $modelName) {
                try {
                    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$this->apiKey}";

                    $response = Http::retry(2, 500)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($url, [
                            'contents' => [
                                ['parts' => [['text' => $prompt]]]
                            ]
                        ]);

                    if ($response->successful()) {
                        $text = $response->json('candidates.0.content.parts.0.text');

                        if ($text) {
                            $cleanJson = preg_replace('/^```json\s*|\s*```$/m', '', trim($text));
                            $data = json_decode($cleanJson, true);

                            if (is_array($data) && isset($data['is_safe'])) {
                                return [
                                    'is_safe' => (bool)$data['is_safe'],
                                    'reason'  => $data['reason'] ?? null,
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Gemini Exception in analyzeComment ({$modelName}): " . $e->getMessage());
                }
            }
        }

        // Fallback local moderation if Gemini fails/API key missing
        $badWords = ['anjing', 'bangsat', 'babi', 'goblok', 'tolol', 'kntl', 'kontol', 'memek', 'ngentot', 'asoe'];
        $isSafe = true;
        $reason = null;

        $lowerComment = strtolower($comment);
        foreach ($badWords as $word) {
            if (str_contains($lowerComment, $word)) {
                $isSafe = false;
                $reason = "Mengandung kata kasar: {$word}";
                break;
            }
        }

        return [
            'is_safe' => $isSafe,
            'reason'  => $reason
        ];
    }
}

