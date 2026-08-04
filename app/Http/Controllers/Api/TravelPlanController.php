<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TravelPlan;
use App\Models\Destinasi;
use Illuminate\Http\Request;

class TravelPlanController extends Controller
{
    /**
     * List travel plans milik user.
     */
    public function index(Request $request)
    {
        $plans = TravelPlan::where('id_user', $request->user()->id_user)
            ->with(['travel'])
            ->get();

        return response()->json($plans);
    }

    /**
     * Buat travel plan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_perjalanan' => 'required|string|max:255',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'budget'          => 'nullable|numeric|min:0',
            'tujuan'          => 'nullable|string|max:255',
            'catatan'         => 'nullable|string',
        ]);

        $plan = $request->user()->travelPlans()->create([
            'nama_perjalanan' => $request->nama_perjalanan,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'budget'          => $request->budget ?? 0,
            'tujuan'          => $request->tujuan,
            'catatan'         => $request->catatan,
            'status'          => 'planning',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Rencana perjalanan berhasil dibuat!',
            'plan'    => $plan,
        ], 201);
    }

    /**
     * Detail travel plan + destinations + expenses.
     */
    public function show(Request $request, string $id)
    {
        $plan = TravelPlan::with(['expenses', 'travel.destinasis'])
            ->where('id_user', $request->user()->id_user)
            ->findOrFail($id);

        return response()->json($plan);
    }

    /**
     * Update rincian anggaran travel plan.
     */
    public function update(Request $request, string $id)
    {
        $plan = TravelPlan::where('id_user', $request->user()->id_user)
            ->findOrFail($id);

        $request->validate([
            'nama_perjalanan' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'estimasi_makan_per_orang' => 'nullable|numeric|min:0',
            'estimasi_transport_per_orang' => 'nullable|numeric|min:0',
            'jumlah_peserta' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'nullable|string',
        ]);

        $plan->update([
            'nama_perjalanan' => $request->nama_perjalanan ?? $plan->nama_perjalanan,
            'budget' => $request->budget ?? $plan->budget,
            'estimasi_makan_per_orang' => $request->estimasi_makan_per_orang ?? $plan->estimasi_makan_per_orang,
            'estimasi_transport_per_orang' => $request->estimasi_transport_per_orang ?? $plan->estimasi_transport_per_orang,
            'jumlah_peserta' => $request->jumlah_peserta ?? $plan->jumlah_peserta,
            'tanggal_berangkat' => $request->tanggal_mulai ?? $plan->tanggal_berangkat,
            'tanggal_selesai' => $request->tanggal_mulai ?? $plan->tanggal_selesai,
        ]);

        if ($request->filled('tanggal_mulai')) {
            $schedules = $plan->schedules_json ?: [];
            foreach ($schedules as $idx => &$item) {
                $item['tanggal'] = $request->tanggal_mulai;
                if ($idx === 0 && $request->filled('jam_mulai')) {
                    $item['jam_mulai'] = $request->jam_mulai;
                }
            }
            $plan->update(['schedules_json' => $schedules]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Rincian anggaran berhasil diperbarui!',
            'plan'    => $plan,
        ]);
    }

    /**
     * Hapus travel plan.
     */
    public function destroy(Request $request, string $id)
    {
        $plan = TravelPlan::where('id_user', $request->user()->id_user)
            ->findOrFail($id);

        $plan->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Rencana perjalanan dihapus.',
        ]);
    }

    /**
     * Tambah destinasi ke travel plan.
     */
    public function addDestinasi(Request $request, string $planId)
    {
        $request->validate([
            'destinasi_id' => 'required|exists:destinasi,id',
        ]);

        $plan = TravelPlan::where('id_user', $request->user()->id_user)
            ->findOrFail($planId);

        $schedules = $plan->schedules_json ?: [];

        $exists = collect($schedules)->contains('destinasi_id', $request->destinasi_id);
        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Destinasi sudah ada di rencana ini.',
            ], 422);
        }

        $schedules[] = [
            'destinasi_id' => (int) $request->destinasi_id,
            'is_visited' => false,
            'tanggal' => null,
            'jam_mulai' => null,
            'jam_selesai' => null,
            'catatan' => null,
        ];

        $plan->update(['schedules_json' => $schedules]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Destinasi ditambahkan ke rencana!',
        ]);
    }

    /**
     * Hapus destinasi dari travel plan.
     */
    public function removeDestinasi(Request $request, string $planId, string $destinasiId)
    {
        $plan = TravelPlan::where('id_user', $request->user()->id_user)
            ->findOrFail($planId);

        $schedules = $plan->schedules_json ?: [];
        $schedules = collect($schedules)->filter(function ($item) use ($destinasiId) {
            return $item['destinasi_id'] != $destinasiId;
        })->values()->toArray();

        $plan->update(['schedules_json' => $schedules]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Destinasi dihapus dari rencana.',
        ]);
    }

    /**
     * Mengubah status kunjungan (checked/visited) destinasi dalam rencana perjalanan.
     */
    public function toggleVisited(Request $request, string $planId, string $destinasiId)
    {
        $plan = TravelPlan::where('id_user', $request->user()->id_user)->findOrFail($planId);

        $schedules = $plan->schedules_json ?: [];
        $found = false;
        $newStatus = false;

        foreach ($schedules as &$item) {
            if ($item['destinasi_id'] == $destinasiId) {
                $item['is_visited'] = !($item['is_visited'] ?? false);
                $newStatus = $item['is_visited'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Destinasi tidak ditemukan dalam rencana perjalanan ini.',
            ], 404);
        }

        $plan->update(['schedules_json' => $schedules]);

        return response()->json([
            'status'  => 'success',
            'message' => $newStatus ? 'Destinasi ditandai telah dikunjungi!' : 'Tanda kunjungan destinasi dihapus.',
            'is_visited' => $newStatus
        ]);
    }

    /**
     * Pasang agen travel ke rencana perjalanan.
     */
    public function attachTravel(Request $request, string $id)
    {
        $request->validate([
            'travel_id' => 'nullable|exists:travels,id',
            'jumlah_peserta' => 'nullable|integer|min:1',
        ]);

        $plan = TravelPlan::where('id_user', $request->user()->id_user)
            ->findOrFail($id);

        $plan->update([
            'travel_id' => $request->travel_id ?: null,
            'jumlah_peserta' => $request->jumlah_peserta ?: 1,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => $request->travel_id 
                ? 'Mitra Agen Travel berhasil dipasang pada rencana perjalanan!' 
                : 'Agen Travel dilepas dari rencana perjalanan.',
            'plan'    => $plan,
        ]);
    }

    /**
     * Checkout rencana perjalanan dengan upload bukti pembayaran.
     */
    public function checkoutTravel(Request $request, string $id)
    {
        $plan = TravelPlan::where('id_user', $request->user()->id_user)
            ->findOrFail($id);

        if (!$plan->travel_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Checkout hanya tersedia jika Rencana Perjalanan menggunakan Agen Travel.',
            ], 400);
        }

        $request->validate([
            'metode_pembayaran' => 'required|string',
            'payment_proof'     => 'required|image|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        $plan->update([
            'is_checkout' => true,
            'payment_method' => $request->metode_pembayaran,
            'payment_proof' => $proofPath,
            'payment_status' => 'pending_admin',
            'trip_status' => 'pending',
            'status' => 'Menunggu Konfirmasi',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pembayaran Checkout Paket Travel berhasil diajukan! Menunggu verifikasi Admin.',
            'plan'    => $plan,
        ]);
    }

    /**
     * Pesan paket travel langsung ke rencana perjalanan baru.
     */
    public function bookPackage(Request $request)
    {
        $request->validate([
            'travel_id'      => 'required|exists:travels,id',
            'jumlah_peserta' => 'required|integer|min:1',
        ]);

        $travel = \App\Models\Travel::findOrFail($request->travel_id);

        $plan = $request->user()->travelPlans()->create([
            'nama_perjalanan' => 'Trip ' . $travel->nama_travel,
            'tujuan'          => $travel->kota,
            'tanggal_mulai'   => $travel->tanggal_keberangkatan,
            'tanggal_selesai' => $travel->tanggal_keberangkatan,
            'budget'          => $travel->harga_paket * $request->jumlah_peserta,
            'travel_id'       => $travel->id,
            'jumlah_peserta'  => $request->jumlah_peserta,
            'status'          => 'planning',
        ]);

        $plan->load('travel', 'user');

        return response()->json([
            'status'  => 'success',
            'message' => 'Paket travel berhasil dipesan langsung ke rencana baru!',
            'plan'    => $plan,
        ], 201);
    }

    /**
     * Selesaikan travel plan (rencana mandiri).
     */
    public function complete(Request $request, string $id)
    {
        $plan = TravelPlan::where('id_user', $request->user()->id_user)
            ->findOrFail($id);

        $plan->update(['status' => 'Selesai']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Selamat! Perjalanan Anda telah selesai.',
            'plan'    => $plan,
        ]);
    }
}
