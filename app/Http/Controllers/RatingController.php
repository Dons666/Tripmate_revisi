<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\GeminiFilterService;
use Illuminate\Support\Facades\Schema;

class RatingController extends Controller
{
    protected GeminiFilterService $geminiFilterService;

    public function __construct(GeminiFilterService $geminiFilterService)
    {
        $this->geminiFilterService = $geminiFilterService;
    }

    public function store(Request $request, string $type, int $id): RedirectResponse
    {
        abort_unless($type === 'destination' || $type === 'travel', 404);

        $request->validate([
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:500'],
        ]);

        $review = $request->review;
        $isFlagged = false;
        $flagReason = null;
        $aiCheckedAt = null;

        if (!empty($review) && trim($review) !== '') {
            $aiAnalysis = $this->geminiFilterService->analyzeComment($review);
            $aiCheckedAt = now();

            if (!$aiAnalysis['is_safe']) {
                return back()->with('error', 'Komentar Anda tidak dapat dipublikasikan karena terdeteksi mengandung konten tidak pantas oleh AI Filter (' . ($aiAnalysis['reason'] ?? 'Pelanggaran konten') . ').')->withInput();
            }

            $isFlagged = !$aiAnalysis['is_safe'];
            $flagReason = $aiAnalysis['reason'];
        }

        $ratingData = [
            'skor_rating' => $request->rating,
            'komentar' => $review,
        ];

        if (Schema::hasColumn('ratings', 'is_flagged')) {
            $ratingData['is_flagged'] = $isFlagged;
            $ratingData['flag_reason'] = $flagReason;
            $ratingData['ai_checked_at'] = $aiCheckedAt;
        }

        if ($type === 'destination') {
            Rating::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'destinasi_id' => $id,
                ],
                $ratingData
            );
            Rating::updateDestinationRating($id);
        } else {
            Rating::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'travel_id' => $id,
                ],
                $ratingData
            );
            $average = Rating::where('travel_id', $id)->avg('rating') ?? 5.0;
            \App\Models\Travel::where('id', $id)->update(['rating' => $average]);
        }

        return back()->with('success', 'Ulasan berhasil ditambahkan/diperbarui!');
    }
}

