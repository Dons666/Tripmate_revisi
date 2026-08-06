<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Models\Rating;
use App\Services\GeminiFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    protected GeminiFilterService $geminiFilterService;

    public function __construct(GeminiFilterService $geminiFilterService)
    {
        $this->geminiFilterService = $geminiFilterService;
    }

    /**
     * GET /api/ratings/destinasi/{id}
     * Ambil ulasan/rating untuk destinasi tertentu.
     */
    public function index(int $id)
    {
        $query = Rating::query();
        $query = Rating::queryByDestinasi($query, $id);

        $ratings = $query->with(['user'])
            ->latest()
            ->get()
            ->map(function ($r) {
                return [
                    'id'          => $r->id_ulasan,
                    'skor_rating' => (float) $r->rating,
                    'komentar'    => $r->komentar,
                    'user_name'   => $r->user?->name ?? 'Anonim',
                    'created_at'  => $r->created_at?->toDateString(),
                ];
            });

        return response()->json(['ratings' => $ratings]);
    }

    /**
     * POST /api/ratings/destinasi/{id}
     * Submit atau update rating user untuk destinasi ini (auth).
     */
    public function store(Request $request, int $id)
    {
        $destinasi = Destinasi::findOrFail($id);

        $validated = $request->validate([
            'skor_rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'komentar'    => ['nullable', 'string', 'max:500'],
        ]);

        $komentar = $validated['komentar'] ?? null;

        if (!empty($komentar) && trim($komentar) !== '') {
            $aiAnalysis = $this->geminiFilterService->analyzeComment($komentar);

            if (!$aiAnalysis['is_safe']) {
                return response()->json([
                    'status'      => 'error',
                    'message'     => 'Komentar Anda tidak dapat dipublikasikan karena terdeteksi mengandung konten tidak pantas oleh AI Filter Gemini (' . ($aiAnalysis['reason'] ?? 'Pelanggaran konten') . ').',
                    'ai_analysis' => $aiAnalysis,
                ], 422);
            }
        }

        $userId = $request->user()?->id_user ?? Auth::id();

        // Cari record ulasan destinasi yang sudah ada
        $rating = Rating::where('id_user', $userId);
        $rating = Rating::queryByDestinasi($rating, $id)->first();

        if (!$rating) {
            $rating = new Rating();
            $rating->id_user = $userId;
            $rating->destinasi_id = $id;
        }

        $rating->rating = $validated['skor_rating'];
        $rating->komentar = $komentar;
        $rating->save();

        // Update Bayesian average rating di tabel destinasi asli
        Rating::updateDestinationRating($id);

        return response()->json([
            'status'     => 'success',
            'message'    => 'Rating berhasil disimpan.',
            'rating'     => [
                'id'          => $rating->id_ulasan,
                'skor_rating' => (float) $rating->rating,
                'komentar'    => $rating->komentar,
            ],
            'avg_rating' => Destinasi::find($id)?->rating_destinasi,
        ]);
    }

    public function my(Request $request)
    {
        $userId = $request->user()?->id_user ?? Auth::id();
        
        $ratings = Rating::where('id_user', $userId)
            ->with(['travel:id,nama_travel'])
            ->latest()
            ->get()
            ->map(function ($r) {
                $destName = null;
                if ($r->destinasi_id) {
                    $dest = Destinasi::find($r->destinasi_id);
                    $destName = $dest?->nama_destinasi;
                }
                return [
                    'id'             => $r->id_ulasan,
                    'destinasi_id'   => $r->destinasi_id,
                    'nama_destinasi' => $destName,
                    'travel_id'      => $r->travel_id,
                    'nama_travel'    => $r->travel?->nama_travel,
                    'skor_rating'    => (float) $r->rating,
                    'komentar'       => $r->komentar,
                    'created_at'     => $r->created_at?->toDateString(),
                ];
            });

        return response()->json(['ratings' => $ratings]);
    }

    /**
     * POST /api/ratings/travel/{id}
     * Submit atau update rating user untuk agen travel ini (auth).
     */
    public function storeTravel(Request $request, int $id)
    {
        $travel = \App\Models\Travel::findOrFail($id);

        $validated = $request->validate([
            'skor_rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'komentar'    => ['nullable', 'string', 'max:500'],
        ]);

        $komentar = $validated['komentar'] ?? null;

        if (!empty($komentar) && trim($komentar) !== '') {
            $aiAnalysis = $this->geminiFilterService->analyzeComment($komentar);

            if (!$aiAnalysis['is_safe']) {
                return response()->json([
                    'status'      => 'error',
                    'message'     => 'Komentar Anda tidak dapat dipublikasikan karena terdeteksi mengandung konten tidak pantas oleh AI Filter Gemini (' . ($aiAnalysis['reason'] ?? 'Pelanggaran konten') . ').',
                    'ai_analysis' => $aiAnalysis,
                ], 422);
            }
        }

        $userId = $request->user()?->id_user ?? Auth::id();

        $rating = Rating::where('id_user', $userId)
            ->where('travel_id', $id)
            ->first();

        if (!$rating) {
            $rating = new Rating();
            $rating->id_user = $userId;
            $rating->travel_id = $id;
        }

        $rating->rating = $validated['skor_rating'];
        $rating->komentar = $komentar;
        $rating->save();

        $average = Rating::where('travel_id', $id)->avg('rating') ?? 5.0;
        $travel->update(['rating' => $average]);

        return response()->json([
            'message'    => 'Rating travel berhasil disimpan.',
            'rating'     => [
                'id'          => $rating->id_ulasan,
                'skor_rating' => (float) $rating->rating,
                'komentar'    => $rating->komentar,
            ],
            'avg_rating' => (float) $travel->rating,
        ]);
    }
}
