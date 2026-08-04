<?php

namespace App\Http\Controllers;

use App\Models\PenyediaTravel;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TravelDashboardController extends Controller
{
    /**
     * Tampilkan Dashboard Khusus Mitra Travel Provider.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Khusus Penyedia Travel (role = 'travel') dan Admin
        if ($user->role !== 'travel' && $user->role !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Akses ditolak. Dashboard ini khusus untuk Mitra Penyedia Travel.');
        }

        // Cari data penyedia travel khusus milik akun yang sedang login
        $penyediaTravel = PenyediaTravel::where('email', $user->email)->first();

        // Cari semua entity Travel (Paket) yang terhubung ke user ID ini
        $packages = Travel::where('user_id', $user->id)->get();
        
        $packageIds = $packages->pluck('id');

        $bookings = \App\Models\TravelPlan::whereIn('travel_id', $packageIds)
            ->with(['user', 'travel'])
            ->orderByDesc('created_at')
            ->get();

        return view('travel.dashboard', compact('user', 'penyediaTravel', 'packages', 'bookings'));
    }

    /**
     * Tampilkan Form Edit Profil Travel.
     */
    public function edit()
    {
        $user = Auth::user();

        if ($user->role !== 'travel' && $user->role !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Akses ditolak. Halaman ini khusus Mitra Penyedia Travel.');
        }

        $penyediaTravel = PenyediaTravel::where('email', $user->email)->firstOrFail();

        return view('travel.edit', compact('user', 'penyediaTravel'));
    }

    /**
     * Update Data Usaha & Profil Travel oleh Mitra (Membutuhkan ACC Admin kembali).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'travel' && $user->role !== 'admin') {
            return redirect()->route('home')
                ->with('error', 'Akses ditolak. Akses ini khusus Mitra Penyedia Travel.');
        }

        $penyediaTravel = PenyediaTravel::where('email', $user->email)->firstOrFail();

        $validated = $request->validate([
            'nama_travel' => 'required|string|max:255',
            'kota_asal_travel' => 'nullable|string|max:255',
            'alamat_travel' => 'nullable|string',
            'jenis_kendaraan' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric|min:0',
            'foto_kendaraan' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'jadwal_ketersediaan' => 'nullable|string',
            'rekening' => 'nullable|string|max:255',
            'nomor_hp_pemilik_travel' => 'required|string|max:30',
        ], [
            'nama_travel.required' => 'Nama travel wajib diisi.',
            'nomor_hp_pemilik_travel.required' => 'Nomor HP pemilik wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
        ]);

        // Handle File Upload for Foto Kendaraan (Per-Vehicle Photos or Global Photo)
        $fotoArmadaPaths = [];
        $existingFotos = $request->input('existing_foto_armada', []);

        if ($request->hasFile('foto_armada')) {
            $uploadedFiles = $request->file('foto_armada');
            foreach ($uploadedFiles as $index => $file) {
                if ($file && $file->isValid()) {
                    $storedPath = $file->store('travel_docs/foto_kendaraan', 'public');
                    $fotoArmadaPaths[$index] = $storedPath;
                    try {
                        $publicCopy = public_path('storage/' . $storedPath);
                        @mkdir(dirname($publicCopy), 0755, true);
                        @copy(storage_path('app/public/' . $storedPath), $publicCopy);
                    } catch (\Throwable $e) {}
                } elseif (isset($existingFotos[$index]) && !empty($existingFotos[$index])) {
                    $fotoArmadaPaths[$index] = $existingFotos[$index];
                }
            }
            foreach ($existingFotos as $index => $path) {
                if (!isset($fotoArmadaPaths[$index]) && !empty($path)) {
                    $fotoArmadaPaths[$index] = $path;
                }
            }
        } elseif (!empty($existingFotos)) {
            $fotoArmadaPaths = array_values(array_filter($existingFotos));
        }

        if (!empty($fotoArmadaPaths)) {
            ksort($fotoArmadaPaths);
            $validated['foto_kendaraan'] = json_encode(array_values($fotoArmadaPaths));
        } elseif ($request->hasFile('foto_kendaraan')) {
            $storedPath = $request->file('foto_kendaraan')->store('travel_docs/foto_kendaraan', 'public');
            $validated['foto_kendaraan'] = $storedPath;
            try {
                $publicCopy = public_path('storage/' . $storedPath);
                @mkdir(dirname($publicCopy), 0755, true);
                @copy(storage_path('app/public/' . $storedPath), $publicCopy);
            } catch (\Throwable $e) {}
        }

        // Handle File Upload for Surat Izin Usaha jika diunggah baru
        if ($request->hasFile('surat_izin_usaha_travel')) {
            $validated['surat_izin_usaha_travel'] = $request->file('surat_izin_usaha_travel')->store('travel_docs/surat_izin', 'public');
        }

        // Handle File Upload for KTP Pemilik jika diunggah baru
        if ($request->hasFile('ktp_pemilik')) {
            $validated['ktp_pemilik'] = $request->file('ktp_pemilik')->store('travel_docs/ktp', 'public');
        }

        // Jika status mitra sudah 'approved', pertahankan status 'approved' agar kemitraan tetap aktif
        if ($penyediaTravel->status === 'approved') {
            $validated['status'] = 'approved';
        }

        $penyediaTravel->update($validated);

        // Sync name di tabel User dan pastikan akun tetap aktif
        $user->update([
            'name' => $validated['nama_travel'],
            'is_active' => true,
        ]);

        return redirect()->route('travel.dashboard')->with('success', 'Perubahan data travel Anda berhasil diperbarui! Status kemitraan Anda tetap Aktif (ACC).');
    }

    /**
     * Tampilkan Form Tambah Paket
     */
    public function createPackage()
    {
        $armadas = Auth::user()->armadas;
        $wisatas = \App\Models\Destinasi::where('tipe', 'wisata')->orderBy('nama_destinasi')->get();
        $kuliners = \App\Models\Destinasi::where('tipe', 'kuliner')->orderBy('nama_destinasi')->get();
        $penginapans = \App\Models\Destinasi::where('tipe', 'penginapan')->orderBy('nama_destinasi')->get();
        return view('travel.packages.create', compact('armadas', 'wisatas', 'kuliners', 'penginapans'));
    }

    /**
     * Simpan Paket Baru
     */
    public function storePackage(Request $request)
    {
        $validated = $request->validate([
            'nama_travel' => 'required|string|max:255',
            'layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_paket' => 'required|numeric|min:0',
            'tanggal_keberangkatan' => 'required|date',
            'kota' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'armada_id' => 'required|exists:armadas,id',
            'wisata_ids' => 'nullable|array',
            'wisata_ids.*' => 'exists:destinasi,id',
            'kuliner_ids' => 'nullable|array',
            'kuliner_ids.*' => 'exists:destinasi,id',
            'penginapan_ids' => 'nullable|array',
            'penginapan_ids.*' => 'exists:destinasi,id',
        ]);

        // Cek kepemilikan armada
        $armada = \App\Models\Armada::where('id', $validated['armada_id'])->where('user_id', Auth::id())->firstOrFail();

        $travelData = collect($validated)->except(['wisata_ids', 'kuliner_ids', 'penginapan_ids'])->toArray();
        $travelData['user_id'] = Auth::id();
        $travelData['slug'] = Str::slug($travelData['nama_travel']) . '-' . uniqid();
        $travelData['rating'] = 5.0; // Default rating

        if ($request->hasFile('gambar')) {
            $dir = storage_path('app/public/travel-covers');
            if (!file_exists($dir)) {
                @mkdir($dir, 0755, true);
            }
            $storedPath = $request->file('gambar')->store('travel-covers', 'public');
            $travelData['gambar'] = $storedPath;
            try {
                $publicCopy = public_path('storage/' . $storedPath);
                @mkdir(dirname($publicCopy), 0755, true);
                @copy(storage_path('app/public/' . $storedPath), $publicCopy);
            } catch (\Throwable $e) {}
        } else {
            $travelData['gambar'] = 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80';
        }

        $travel = Travel::create($travelData);
        
        $allDestinasis = array_merge(
            $request->wisata_ids ?? [],
            $request->kuliner_ids ?? [],
            $request->penginapan_ids ?? []
        );
        $travel->destinasis()->sync($allDestinasis);

        return redirect()->route('travel.dashboard')->with('success', 'Paket Perjalanan berhasil ditambahkan!');
    }

    /**
     * Tampilkan Form Edit Paket
     */
    public function editPackage(Travel $travel)
    {
        if ((int) $travel->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit paket perjalanan ini.');
        }
        $armadas = Auth::user()->armadas;
        $wisatas = \App\Models\Destinasi::where('tipe', 'wisata')->orderBy('nama_destinasi')->get();
        $kuliners = \App\Models\Destinasi::where('tipe', 'kuliner')->orderBy('nama_destinasi')->get();
        $penginapans = \App\Models\Destinasi::where('tipe', 'penginapan')->orderBy('nama_destinasi')->get();
        return view('travel.packages.edit', compact('travel', 'armadas', 'wisatas', 'kuliners', 'penginapans'));
    }

    /**
     * Update Paket
     */
    public function updatePackage(Request $request, Travel $travel)
    {
        if ((int) $travel->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk memperbarui paket perjalanan ini.');
        }

        $validated = $request->validate([
            'nama_travel' => 'required|string|max:255',
            'layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_paket' => 'required|numeric|min:0',
            'tanggal_keberangkatan' => 'required|date',
            'kota' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'armada_id' => 'required|exists:armadas,id',
            'wisata_ids' => 'nullable|array',
            'wisata_ids.*' => 'exists:destinasi,id',
            'kuliner_ids' => 'nullable|array',
            'kuliner_ids.*' => 'exists:destinasi,id',
            'penginapan_ids' => 'nullable|array',
            'penginapan_ids.*' => 'exists:destinasi,id',
        ]);

        // Cek kepemilikan armada
        $armada = \App\Models\Armada::where('id', $validated['armada_id'])->where('user_id', Auth::id())->firstOrFail();

        $travelData = collect($validated)->except(['wisata_ids', 'kuliner_ids', 'penginapan_ids'])->toArray();
        $travelData['slug'] = Str::slug($travelData['nama_travel']) . '-' . uniqid();

        if ($request->hasFile('gambar')) {
            $dir = storage_path('app/public/travel-covers');
            if (!file_exists($dir)) {
                @mkdir($dir, 0755, true);
            }
            $storedPath = $request->file('gambar')->store('travel-covers', 'public');
            $travelData['gambar'] = $storedPath;
            try {
                $publicCopy = public_path('storage/' . $storedPath);
                @mkdir(dirname($publicCopy), 0755, true);
                @copy(storage_path('app/public/' . $storedPath), $publicCopy);
            } catch (\Throwable $e) {}
        }

        $travel->update($travelData);
        
        $allDestinasis = array_merge(
            $request->wisata_ids ?? [],
            $request->kuliner_ids ?? [],
            $request->penginapan_ids ?? []
        );
        $travel->destinasis()->sync($allDestinasis);

        return redirect()->route('travel.dashboard')->with('success', 'Paket Perjalanan berhasil diupdate!');
    }

    /**
     * Hapus Paket
     */
    public function destroyPackage(Travel $travel)
    {
        if ($travel->user_id !== Auth::id()) {
            abort(403);
        }
        
        $travel->delete();
        
        return redirect()->route('travel.dashboard')->with('success', 'Paket Perjalanan berhasil dihapus!');
    }
}
