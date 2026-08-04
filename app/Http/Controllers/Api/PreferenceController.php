<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    /**
     * GET /api/preferences
     * Ambil preference user yang sedang login.
     */
    public function show(Request $request)
    {
        $userId = $request->user()?->id_user ?? Auth::id();
        $pref = UserPreference::where('id_user', $userId)->first();

        if (!$pref) {
            return response()->json(['preference' => null]);
        }

        return response()->json([
            'preference' => [
                'kota_preferensi' => $pref->kota_preferensi,
                'minat_wisata'    => $pref->minat_wisata,
                'hidden_gem'      => (bool) $pref->hidden_gem,
                'budget'          => $pref->budget,
            ],
        ]);
    }

    /**
     * POST /api/preferences
     * Simpan atau update preference user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kota_preferensi' => ['required', 'string'],
            'minat_wisata'    => ['required', 'array', 'min:1'],
            'minat_wisata.*'  => ['string'],
            'budget'          => ['required', 'string', 'in:Gratis,Murah,Sedang,Mahal'],
            'hidden_gem'      => ['boolean'],
        ]);

        $userId = $request->user()?->id_user ?? Auth::id();

        $pref = UserPreference::updateOrCreate(
            ['id_user' => $userId],
            [
                'kota_preferensi' => $validated['kota_preferensi'],
                'minat_wisata'    => $validated['minat_wisata'],
                'hidden_gem'      => $request->boolean('hidden_gem'),
                'budget'          => $validated['budget'],
            ]
        );

        return response()->json([
            'message' => 'Preferensi berhasil disimpan.',
            'preference' => [
                'kota_preferensi' => $pref->kota_preferensi,
                'minat_wisata'    => $pref->minat_wisata,
                'hidden_gem'      => (bool) $pref->hidden_gem,
                'budget'          => $pref->budget,
            ],
        ]);
    }
}
