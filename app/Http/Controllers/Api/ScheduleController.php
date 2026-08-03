<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\TravelPlan;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * List semua schedule milik travel plan.
     */
    public function index(Request $request, string $planId)
    {
        $plan = TravelPlan::where('user_id', $request->user()->id)->findOrFail($planId);

        $schedules = $plan->schedules()->with('destinasi:id,nama_destinasi,kota,gambar')->get();

        return response()->json($schedules);
    }

    /**
     * Tambah jadwal baru ke travel plan.
     */
    public function store(Request $request, string $planId)
    {
        $plan = TravelPlan::where('user_id', $request->user()->id)->findOrFail($planId);

        $request->validate([
            'judul'        => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'deskripsi'    => 'nullable|string',
            'jam_mulai'    => 'nullable|date_format:H:i',
            'jam_selesai'  => 'nullable|date_format:H:i',
            'destinasi_id' => 'nullable|exists:destinasi,id',
        ]);

        $schedule = $plan->schedules()->create([
            'destinasi_id' => $request->destinasi_id,
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
        ]);

        return response()->json([
            'status'   => 'success',
            'message'  => 'Jadwal berhasil ditambahkan!',
            'schedule' => $schedule->load('destinasi:id,nama_destinasi,kota'),
        ], 201);
    }

    /**
     * Edit/Update jadwal.
     */
    public function update(Request $request, string $planId, string $scheduleId)
    {
        $plan = TravelPlan::where('user_id', $request->user()->id)->findOrFail($planId);

        $schedule = $plan->schedules()->findOrFail($scheduleId);

        $request->validate([
            'judul'        => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'deskripsi'    => 'nullable|string',
            'jam_mulai'    => 'nullable|date_format:H:i',
            'jam_selesai'  => 'nullable|date_format:H:i',
            'destinasi_id' => 'nullable|exists:destinasi,id',
        ]);

        $schedule->update([
            'destinasi_id' => $request->destinasi_id,
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
        ]);

        return response()->json([
            'status'   => 'success',
            'message'  => 'Jadwal berhasil diperbarui!',
            'schedule' => $schedule->load('destinasi:id,nama_destinasi,kota'),
        ]);
    }

    /**
     * Hapus jadwal.
     */
    public function destroy(Request $request, string $planId, string $scheduleId)
    {
        $plan = TravelPlan::where('user_id', $request->user()->id)->findOrFail($planId);

        $schedule = $plan->schedules()->findOrFail($scheduleId);
        $schedule->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Jadwal berhasil dihapus.',
        ]);
    }
}
