<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\TravelPlan;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * List expense milik user (opsional filter per travel plan).
     */
    public function index(Request $request)
    {
        $query = Expense::whereHas('travelPlan', function ($q) use ($request) {
                $q->where('id_user', $request->user()->id_user);
            })
            ->with('travelPlan')
            ->latest();

        if ($request->filled('travel_plan_id')) {
            $query->where('travel_plan_id', $request->travel_plan_id);
        }

        return response()->json($query->get());
    }

    /**
     * Tambah expense baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengeluaran' => 'required|string|max:255',
            'jumlah'           => 'required|numeric|min:0',
            'kategori'         => 'nullable|string',
            'tanggal'          => 'nullable|date',
            'travel_plan_id'   => 'required|exists:travel_plans,id_perencanaan',
        ]);

        // Pastikan travel_plan_id milik user ini
        TravelPlan::where('id_user', $request->user()->id_user)
            ->findOrFail($request->travel_plan_id);

        $expense = Expense::create([
            'user_id'          => $request->user()->id_user,
            'travel_plan_id'   => $request->travel_plan_id,
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'jumlah'           => $request->jumlah,
            'kategori'         => $request->kategori,
            'tanggal'          => $request->tanggal ?? now()->toDateString(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengeluaran berhasil ditambahkan!',
            'expense' => $expense,
        ], 201);
    }

    /**
     * Hapus expense.
     */
    public function destroy(Request $request, string $id)
    {
        $expense = Expense::whereHas('travelPlan', function ($q) use ($request) {
                $q->where('id_user', $request->user()->id_user);
            })
            ->findOrFail($id);

        $expense->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengeluaran berhasil dihapus.',
        ]);
    }
}
