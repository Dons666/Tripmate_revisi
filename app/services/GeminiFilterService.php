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
}