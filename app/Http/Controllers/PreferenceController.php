<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    public function create()
    {
        $kategoriWisata = \App\Models\KategoriWisata::orderBy('nama_kategori')->get();
        $kategoriPenginapan = \App\Models\KategoriPenginapan::orderBy('nama_kategori')->get();
        $kategoriKuliner = \App\Models\KategoriKuliner::orderBy('nama_kategori')->get();

        return view('preference.create', compact('kategoriWisata', 'kategoriPenginapan', 'kategoriKuliner'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kota_preferensi' => 'required',
            'minat_wisata' => 'required|array|min:1',
            'budget' => 'required',
        ]);

        UserPreference::updateOrCreate(
            [
                'id_user' => Auth::id()
            ],
            [
                'kota_preferensi' => $request->kota_preferensi,
                'minat_wisata' => $request->minat_wisata,
                'hidden_gem' => $request->boolean('hidden_gem'),
                'budget' => $request->budget,
            ]
        );

        return redirect()
            ->route('home')
            ->with('success', 'Preferensi berhasil disimpan!');
    }
}
