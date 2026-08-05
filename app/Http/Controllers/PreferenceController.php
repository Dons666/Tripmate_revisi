<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    public function create()
    {
        return view('preference.create');
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
                'user_id' => Auth::id()
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
