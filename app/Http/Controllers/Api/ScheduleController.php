<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\TravelPlan;
use App\Models\Destinasi;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * List semua schedule milik travel plan.
     */
    public function index(Request $request, string $planId)
    {
        $plan = TravelPlan::where('id_user', $request->user()->id_user)->findOrFail($planId);

        return response()->json($plan->schedules);
    }

    /**
     * Tambah jadwal baru ke travel plan.
     */
    public function store(Request $request, string $planId)
    {
        $plan = TravelPlan::where('id_user', $request->user()->id_user)->findOrFail($planId);

        $request->validate([
            'judul'        => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'deskripsi'    => 'nullable|string',
            'jam_mulai'    => 'nullable|date_format:H:i',
            'jam_selesai'  => 'nullable|date_format:H:i',
            'destinasi_id' => 'nullable|exists:destinasi,id',
        ]);

        $schedules = $plan->schedules_json ?: [];
        $index = -1;

        if ($request->destinasi_id) {
            foreach ($schedules as $idx => $item) {
                if ($item['destinasi_id'] == $request->destinasi_id) {
                    $index = $idx;
                    break;
                }
            }
        }

        $newEntry = [
            'destinasi_id' => (int) $request->destinasi_id,
            'is_visited'   => $index !== -1 ? ($schedules[$index]['is_visited'] ?? false) : false,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
            'catatan'      => $request->deskripsi,
        ];

        if ($index !== -1) {
            $schedules[$index] = $newEntry;
        } else {
            $schedules[] = $newEntry;
        }

        $plan->update(['schedules_json' => $schedules]);

        $sch = new Schedule([
            'id_jadwal' => ($index !== -1 ? $index : count($schedules) - 1) + 1,
            'id_perencanaan' => $plan->id_perencanaan,
            'id_destinasi' => $request->destinasi_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($request->destinasi_id) {
            $sch->setRelation('destinasi', Destinasi::find($request->destinasi_id));
        }

        return response()->json([
            'status'   => 'success',
            'message'  => 'Jadwal berhasil ditambahkan!',
            'schedule' => $sch,
        ], 201);
    }

    /**
     * Edit/Update jadwal.
     */
    public function update(Request $request, string $planId, string $scheduleId)
    {
        $plan = TravelPlan::where('id_user', $request->user()->id_user)->findOrFail($planId);

        $request->validate([
            'judul'        => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'deskripsi'    => 'nullable|string',
            'jam_mulai'    => 'nullable|date_format:H:i',
            'jam_selesai'  => 'nullable|date_format:H:i',
            'destinasi_id' => 'nullable|exists:destinasi,id',
        ]);

        $schedules = $plan->schedules_json ?: [];
        $index = -1;

        if ($request->destinasi_id) {
            foreach ($schedules as $idx => $item) {
                if ($item['destinasi_id'] == $request->destinasi_id) {
                    $index = $idx;
                    break;
                }
            }
        }

        if ($index === -1) {
            $targetIndex = (int)$scheduleId - 1;
            if (isset($schedules[$targetIndex])) {
                $index = $targetIndex;
            }
        }

        if ($index === -1) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Jadwal tidak ditemukan.',
            ], 404);
        }

        $schedules[$index] = [
            'destinasi_id' => (int) ($request->destinasi_id ?? $schedules[$index]['destinasi_id']),
            'is_visited'   => $schedules[$index]['is_visited'] ?? false,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
            'catatan'      => $request->deskripsi,
        ];

        $plan->update(['schedules_json' => $schedules]);

        $sch = new Schedule([
            'id_jadwal' => $index + 1,
            'id_perencanaan' => $plan->id_perencanaan,
            'id_destinasi' => $schedules[$index]['destinasi_id'],
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($schedules[$index]['destinasi_id']) {
            $sch->setRelation('destinasi', Destinasi::find($schedules[$index]['destinasi_id']));
        }

        return response()->json([
            'status'   => 'success',
            'message'  => 'Jadwal berhasil diperbarui!',
            'schedule' => $sch,
        ]);
    }

    /**
     * Hapus jadwal.
     */
    public function destroy(Request $request, string $planId, string $scheduleId)
    {
        $plan = TravelPlan::where('id_user', $request->user()->id_user)->findOrFail($planId);

        $schedules = $plan->schedules_json ?: [];
        $index = -1;

        $targetIndex = (int)$scheduleId - 1;
        if (isset($schedules[$targetIndex])) {
            $index = $targetIndex;
        }

        if ($index === -1) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Jadwal tidak ditemukan.',
            ], 404);
        }

        array_splice($schedules, $index, 1);
        $plan->update(['schedules_json' => $schedules]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Jadwal berhasil dihapus.',
        ]);
    }
}
