<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\Kategori;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Services\RecommendationService;
use App\Services\GeminiFilterService;


class DestinasiController extends Controller
{
    protected RecommendationService $recommendationService;
    protected GeminiFilterService $geminiFilterService;

    public function __construct(
        RecommendationService $recommendationService,
        GeminiFilterService $geminiFilterService
    ) {
        $this->recommendationService = $recommendationService;
        $this->geminiFilterService = $geminiFilterService;
    }

    // Halaman Search & Filter
    public function search(Request $request)
    {
        $query = Destinasi::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_destinasi', 'like', '%' . $keyword . '%')
                  ->orWhere('deskripsi', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('kota')) {
            $query->where('kota', $request->kota);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('kategori')) {
            $category = $request->kategori;
            $query->where(function($q) use ($category) {
                $q->where('kategori', $category)
                  ->orWhereHas('kategoriWisata', fn($kw) => $kw->where('nama_kategori', $category))
                  ->orWhereHas('kategoriPenginapan', fn($kp) => $kp->where('nama_kategori', $category))
                  ->orWhereHas('kategoriKuliner', fn($kk) => $kk->where('nama_kategori', $category));
            });
        }

        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }
        if ($request->filled('hidden_gem')) {
            $query->where('hidden_gem', 1);
        }

        $destinasis = $query
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->paginate(12);
        $kotas = Destinasi::select('kota')->distinct()->whereNotNull('kota')->orderBy('kota')->get();

        $kategoriWisata = \App\Models\KategoriWisata::orderBy('nama_kategori')->get();
        $kategoriPenginapan = \App\Models\KategoriPenginapan::orderBy('nama_kategori')->get();
        $kategoriKuliner = \App\Models\KategoriKuliner::orderBy('nama_kategori')->get();

        return view('destinasi.search', compact('destinasis', 'kotas', 'kategoriWisata', 'kategoriPenginapan', 'kategoriKuliner'));
    }

    // Halaman Detail Destinasi
    public function show($id)
    {
        // with('ratings.user') = Eager loading agar komentar ikut bawa nama user yang nulis
        $destinasi = Destinasi::with('ratings.user')->findOrFail($id);

        $isBookmarked = false;

        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $isBookmarked = $user
                ->bookmarks()
                ->where('destinasi_id', $id)
                ->exists();
        }

        $similarDestinations = $this->recommendationService->getSimilarDestinations($id);

        return view(
            'destinasi.show',
            compact(
                'destinasi',
                'isBookmarked',
                'similarDestinations'
            )
        );
    }

    // Simpan Rating & Komentar dengan Filter AI Gemini
    public function storeRating(Request $request, $id)
    {
        $request->validate([
            'skor_rating' => 'required|numeric|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        $komentar = $request->komentar;
        $isFlagged = false;
        $flagReason = null;
        $aiCheckedAt = null;

        if (!empty($komentar) && trim($komentar) !== '') {
            $aiAnalysis = $this->geminiFilterService->analyzeComment($komentar);
            $aiCheckedAt = now();

            if (!$aiAnalysis['is_safe']) {
                return back()->with('error', 'Komentar Anda tidak dapat dipublikasikan karena terdeteksi mengandung konten tidak pantas oleh AI Filter (' . ($aiAnalysis['reason'] ?? 'Pelanggaran konten') . ').')->withInput();
            }

            $isFlagged = !$aiAnalysis['is_safe'];
            $flagReason = $aiAnalysis['reason'];
        }

        $ratingData = [
            'skor_rating' => $request->skor_rating,
            'komentar' => $komentar,
        ];

        if (Schema::hasColumn('ratings', 'is_flagged')) {
            $ratingData['is_flagged'] = $isFlagged;
            $ratingData['flag_reason'] = $flagReason;
            $ratingData['ai_checked_at'] = $aiCheckedAt;
        }

        Rating::updateOrCreate(
            [
                'id_user' => Auth::id(),
                'destinasi_id' => $id,
            ],
            $ratingData
        );

        Rating::updateDestinationRating($id);

        return back()->with('success', 'Ulasan berhasil ditambahkan/diperbarui!');
    }

}
