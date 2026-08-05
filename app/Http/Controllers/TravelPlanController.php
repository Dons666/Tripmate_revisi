<?php

namespace App\Http\Controllers;

use App\Models\TravelPlan;
use App\Models\Destinasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TravelPlanController extends Controller
{
    public function index()
    {
        return view('travel-plans.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perjalanan' => 'required|string|max:255',
            'tujuan'          => 'required|string|max:255',
            'catatan'         => 'nullable|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'budget'          => 'nullable|numeric|min:0',
            'status'          => 'nullable|string|in:Perencanaan Aktif,Sedang Berjalan,Selesai,Dibatalkan',
            'foto_sampul'     => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'travel_id'       => 'nullable|exists:travels,id',
        ]);

        $data = $request->except('_token');

        if ($request->hasFile('foto_sampul')) {
            $dir = storage_path('app/public/travel-covers');
            if (!file_exists($dir)) {
                @mkdir($dir, 0755, true);
            }
            $storedPath = $request->file('foto_sampul')->store('travel-covers', 'public');
            $data['foto_sampul'] = $storedPath;

            // Dual-store copy to public_path for cPanel compatibility
            try {
                $publicCopy = public_path('storage/' . $storedPath);
                @mkdir(dirname($publicCopy), 0755, true);
                @copy(storage_path('app/public/' . $storedPath), $publicCopy);
            } catch (\Throwable $e) {}
        }

        if (empty($data['status'])) {
            $data['status'] = 'Perencanaan Aktif';
        }

        Auth::user()->travelPlans()->create($data);

        return back()->with('success', 'Rencana perjalanan berhasil dibuat!');
    }

    public function show(TravelPlan $travelPlan)
    {
        if ((int) $travelPlan->user_id !== (int) Auth::id() && (!Auth::check() || !Auth::user()->isAdmin())) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat Rencana Perjalanan milik pengguna lain.');
        }

        $travelPlan->load('expenses');
        $travels = \App\Models\Travel::latest()->get();

        return view('travel-plans.show', compact('travelPlan', 'travels'));
    }

    public function attachTravel(Request $request, TravelPlan $travelPlan)
    {
        if ((int) $travelPlan->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah Rencana Perjalanan ini.');
        }

        $request->validate([
            'travel_id' => 'nullable|exists:travels,id',
        ]);

        $travelPlan->update([
            'travel_id' => $request->travel_id ?: null,
        ]);

        $msg = $request->travel_id ? 'Mitra Agen Travel berhasil dipasang pada rencana perjalanan!' : 'Agen Travel dilepas. Perencanaan diubah ke Mandiri.';

        return back()->with('success', $msg);
    }

    public function checkout(TravelPlan $travelPlan)
    {
        if ((int) $travelPlan->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan checkout Rencana Perjalanan ini.');
        }

        if (!$travelPlan->travel_id) {
            return redirect()->route('travel-plans.show', $travelPlan)
                ->with('error', 'Checkout hanya tersedia jika Rencana Perjalanan menggunakan Agen Travel.');
        }

        $travelPlan->load('expenses');

        return view('travel-plans.checkout', compact('travelPlan'));
    }

    public function processCheckout(Request $request, TravelPlan $travelPlan)
    {
        if ((int) $travelPlan->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk memproses checkout Rencana Perjalanan ini.');
        }

        if (!$travelPlan->travel_id) {
            return redirect()->route('travel-plans.show', $travelPlan)
                ->with('error', 'Checkout tidak dapat diproses tanpa Agen Travel.');
        }

        $request->validate([
            'metode_pembayaran' => 'required|string',
            'payment_proof'     => 'required|image|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        $travelPlan->update([
            'is_checkout' => true,
            'payment_method' => $request->metode_pembayaran,
            'payment_proof' => $proofPath,
            'payment_status' => 'pending_admin',
            'trip_status' => 'pending',
            'status'      => 'Menunggu Konfirmasi',
        ]);

        return redirect()->route('travel-plans.receipt', $travelPlan)
            ->with('success', 'Pembayaran Checkout Paket Travel berhasil diajukan! Menunggu verifikasi dari Admin.');
    }

    public function addDestinasi(Request $request, TravelPlan $travelPlan)
    {
        $request->validate(['destinasi_id' => 'required|exists:destinasi,id']);

        if ((int) $travelPlan->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menambah destinasi ke Rencana Perjalanan ini.');
        }

        $schedules = $travelPlan->schedules_json ?: [];
        $exists = collect($schedules)->contains('destinasi_id', (int) $request->destinasi_id);
        if ($exists) {
            return redirect()->route('destinasi.show', $request->destinasi_id)
                ->with('error', 'Destinasi sudah ada di rencana "' . $travelPlan->nama_perjalanan . '".');
        }

        $schedules[] = [
            'destinasi_id' => (int) $request->destinasi_id,
            'is_visited'   => false,
            'tanggal'      => now()->toDateString(),
            'jam_mulai'    => null,
            'jam_selesai'  => null,
            'catatan'      => null,
        ];

        $travelPlan->schedules_json = $schedules;
        $destIds = collect($schedules)->pluck('destinasi_id')->toArray();
        $travelPlan->total_cost = \App\Models\Destinasi::whereIn('id', $destIds)->sum('harga');
        $travelPlan->save();

        return redirect()->route('destinasi.show', $request->destinasi_id)
            ->with('success', 'Destinasi ditambahkan ke rencana "' . $travelPlan->nama_perjalanan . '"!');
    }

    public function quickAdd(Request $request)
    {
        $request->validate([
            'nama_perjalanan' => 'required|string|max:255',
            'destinasi_id'    => 'required|exists:destinasi,id',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'budget'          => 'nullable|numeric|min:0',
        ]);

        $destinasi = Destinasi::find($request->destinasi_id);

        $schedules = [
            [
                'destinasi_id' => (int) $request->destinasi_id,
                'is_visited'   => false,
                'tanggal'      => now()->toDateString(),
                'jam_mulai'    => null,
                'jam_selesai'  => null,
                'catatan'      => null,
            ]
        ];

        $plan = Auth::user()->travelPlans()->create([
            'nama_perjalanan' => $request->nama_perjalanan,
            'tujuan'          => $destinasi->kota ?: 'Bandung',
            'tanggal_berangkat' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'budget'          => $request->budget ?: 0,
            'total_cost'      => $destinasi->harga ?: 0,
            'status'          => 'Perencanaan Aktif',
            'schedules_json'  => $schedules,
        ]);

        return redirect()->route('destinasi.show', $request->destinasi_id)
            ->with('success', 'Rencana "' . $plan->nama_perjalanan . '" dibuat dan destinasi ditambahkan!');
    }

    public function saveIntegratedRoute(Request $request)
    {
        $request->validate([
            'nama_perjalanan' => 'required|string|max:255',
            'budget'          => 'required|numeric|min:0',
            'destinasi_ids'   => 'required|array|min:1',
            'destinasi_ids.*' => 'exists:destinasi,id',
        ]);

        $schedulesPayload = [];
        foreach ($request->destinasi_ids as $destId) {
            $schedulesPayload[] = [
                'destinasi_id' => (int) $destId,
                'is_visited'   => false,
                'tanggal'      => now()->toDateString(),
                'jam_mulai'    => null,
                'jam_selesai'  => null,
                'catatan'      => null,
            ];
        }

        $totalCost = \App\Models\Destinasi::whereIn('id', $request->destinasi_ids)->sum('harga');

        $firstDestId = $request->destinasi_ids[0] ?? null;
        $tujuan = 'Bandung';
        if ($firstDestId) {
            $dest = \App\Models\Destinasi::find($firstDestId);
            if ($dest && $dest->kota) {
                $tujuan = $dest->kota;
            }
        }

        $plan = Auth::user()->travelPlans()->create([
            'nama_perjalanan' => $request->nama_perjalanan,
            'tujuan'          => $tujuan,
            'budget'          => $request->budget,
            'total_cost'      => $totalCost,
            'status'          => 'Perencanaan Aktif',
            'schedules_json'  => $schedulesPayload,
        ]);

        return redirect()->route('travel-plans.show', $plan->id_perencanaan)
            ->with('success', 'Rute terpendek berhasil disimpan sebagai rencana perjalanan!');
    }

    public function removeDestinasi(TravelPlan $travelPlan, Destinasi $destinasi)
    {
        if ((int) $travelPlan->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus destinasi dari Rencana Perjalanan ini.');
        }

        $schedules = $travelPlan->schedules_json ?: [];
        $filteredSchedules = collect($schedules)->filter(function ($s) use ($destinasi) {
            return (int) $s['destinasi_id'] !== (int) $destinasi->id;
        })->values()->all();

        $travelPlan->schedules_json = $filteredSchedules;
        $destIds = collect($filteredSchedules)->pluck('destinasi_id')->toArray();
        $travelPlan->total_cost = \App\Models\Destinasi::whereIn('id', $destIds)->sum('harga');
        $travelPlan->save();

        return back()->with('success', 'Destinasi dihapus dari rencana.');
    }

    public function destroy(TravelPlan $travelPlan)
    {
        if ((int) $travelPlan->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus Rencana Perjalanan ini.');
        }

        if ($travelPlan->foto_sampul && Storage::disk('public')->exists($travelPlan->foto_sampul)) {
            Storage::disk('public')->delete($travelPlan->foto_sampul);
        }

        $travelPlan->delete();
        return redirect()->route('travel-plans.index')->with('success', 'Rencana dihapus.');
    }

    public function complete(TravelPlan $travelPlan)
    {
        if ((int) $travelPlan->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menyelesaikan Rencana Perjalanan ini.');
        }

        $travelPlan->update(['status' => 'Selesai']);

        return redirect()->route('travel-plans.receipt', $travelPlan)
            ->with('success', 'Selamat! Perjalanan Anda telah selesai. Berikut struk dan ringkasan perjalanannya.');
    }

    public function receipt(TravelPlan $travelPlan)
    {
        if ((int) $travelPlan->user_id !== (int) Auth::id() && (!Auth::check() || !Auth::user()->isAdmin())) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat Struk Rencana Perjalanan milik pengguna lain.');
        }

        $travelPlan->load('expenses', 'travel');

        $expensesByCategory = $travelPlan->expenses->groupBy(function ($expense) {
            return $expense->kategori ? ucfirst($expense->kategori) : 'Lain-lain';
        });

        $userRatings = \App\Models\Rating::where('user_id', Auth::id())->get();

        return view('travel-plans.receipt', compact('travelPlan', 'expensesByCategory', 'userRatings'));
    }

    public function bookPackage(Request $request, $travelId)
    {
        $request->validate([
            'jumlah_peserta' => 'required|integer|min:1',
        ]);

        $travel = \App\Models\Travel::findOrFail($travelId);
        $travel->load('armada');
        
        $totalCapacity = $travel->armada ? $travel->armada->kapasitas_kursi : 0;
        
        $bookedSeats = \App\Models\TravelPlan::where('travel_id', $travel->id)
            ->where('status', '!=', 'Dibatalkan')
            ->sum('jumlah_peserta');
            
        $availableSeats = max(0, $totalCapacity - $bookedSeats);

        if ($request->jumlah_peserta > $availableSeats) {
            return back()->with('error', 'Maaf, sisa kursi tidak mencukupi. Sisa kursi tersedia: ' . $availableSeats);
        }

        $plan = Auth::user()->travelPlans()->create([
            'nama_perjalanan' => 'Trip ' . $travel->nama_travel,
            'tujuan'          => $travel->kota,
            'tanggal_mulai'   => $travel->tanggal_keberangkatan,
            'tanggal_selesai' => $travel->tanggal_keberangkatan,
            'budget'          => $travel->harga_paket * $request->jumlah_peserta,
            'travel_id'       => $travel->id,
            'jumlah_peserta'  => $request->jumlah_peserta,
            'status'          => 'Perencanaan Aktif',
        ]);

        return redirect()->route('travel-plans.show', $plan->id)
            ->with('success', 'Paket travel berhasil dipesan! Anda langsung diarahkan ke rencana perjalanan baru.');
    }

    public function checkAvailability(\App\Models\Travel $travel)
    {
        $travel->load('armada');
        
        $totalCapacity = $travel->armada ? $travel->armada->kapasitas_kursi : 0;
        
        $bookedSeats = \App\Models\TravelPlan::where('travel_id', $travel->id)
            ->where('status', '!=', 'Dibatalkan')
            ->sum('jumlah_peserta');
            
        $availableSeats = max(0, $totalCapacity - $bookedSeats);
        
        return response()->json([
            'available_seats' => $availableSeats,
            'armada_name' => $travel->armada ? $travel->armada->nama_kendaraan : 'Tidak diketahui'
        ]);
    }
}
