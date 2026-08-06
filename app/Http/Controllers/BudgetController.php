<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\Kategori;
use App\Services\BudgetRecommendationService;
use App\Services\DijkstraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BudgetController extends Controller
{
    protected BudgetRecommendationService $budgetService;
    protected DijkstraService $dijkstraService;

    public function __construct(
        BudgetRecommendationService $budgetService,
        DijkstraService $dijkstraService
    ) {
        $this->budgetService   = $budgetService;
        $this->dijkstraService = $dijkstraService;
    }

    /**
     * Tampilkan halaman utama Optimasi Budget & Rute.
     */
    public function index()
    {
        $kategoriWisata = \App\Models\KategoriWisata::orderBy('nama_kategori')->pluck('nama_kategori');
        $kategoriPenginapan = \App\Models\KategoriPenginapan::orderBy('nama_kategori')->pluck('nama_kategori');
        $kategoriKuliner = \App\Models\KategoriKuliner::orderBy('nama_kategori')->pluck('nama_kategori');
        $kategoriList = $kategoriWisata->concat($kategoriPenginapan)->concat($kategoriKuliner)->unique()->values();

        $kotas       = Destinasi::distinct()->pluck('kota')->filter()->values();
        $destinasis  = Destinasi::orderBy('nama_destinasi')->get(['id', 'nama_destinasi', 'kota', 'kategori', 'harga']);

        return view('budget-planner.index', [
            'kategoris'  => $kategoriList->map(fn($item) => (object)['nama_kategori' => $item]),
            'kotas'      => $kotas,
            'destinasis' => $destinasis,
        ]);
    }

    /**
     * Hitung rute terintegrasi + optimasi budget via AJAX.
     */
    public function integratedRoute(Request $request)
    {
        $request->validate([
            'start'    => 'required|string',
            'end'      => 'required|string',
            'budget'   => 'required|numeric|min:0',
            'kategori' => 'nullable|string',
            'kota'     => 'nullable|string',
        ]);

        // 1. Dapatkan kandidat destinasi dalam budget
        $data         = $this->budgetService->recommend(
            $request->budget,
            $request->kategori,
            $request->kota
        );
        $destinations = $data['recommendations'];

        // 2. Hitung rute waypoint Dijkstra
        $rawRoute = $this->dijkstraService->calculateRoute(
            $request->start,
            $request->end,
            $destinations
        );

        if (is_array($rawRoute) && isset($rawRoute['error'])) {
            return response()->json([
                'status'  => 'error',
                'message' => $rawRoute['error']
            ], 422);
        }

        // 3. Akumulasikan biaya per destinasi dalam rute
        $finalRoute      = [];
        $accumulatedCost = 0;
        $maxBudget       = (float) $request->budget;

        foreach ($rawRoute as $index => $place) {
            $cost = (float) ($place->harga ?? 0);

            // Titik awal dan akhir selalu dimasukkan
            if ($index === 0 || $index === (count($rawRoute) - 1)) {
                $accumulatedCost += $cost;
                $finalRoute[] = $place;
                continue;
            }

            if (($accumulatedCost + $cost) <= $maxBudget) {
                $accumulatedCost += $cost;
                $finalRoute[] = $place;
            }
        }

        $formatted = collect($finalRoute)->map(fn ($d) => [
            'id'             => $d->id,
            'nama_destinasi' => $d->nama_destinasi,
            'kategori'       => $d->kategori,
            'kota'           => $d->kota,
            'harga'          => (float) $d->harga,
            'latitude'       => (float) $d->latitude,
            'longitude'      => (float) $d->longitude,
            'gambar'         => $d->gambar,
        ]);

        return response()->json([
            'status'           => 'success',
            'route'            => $formatted,
            'total_cost'       => $accumulatedCost,
            'remaining_budget' => max(0.0, $maxBudget - $accumulatedCost),
            'total_nodes'      => $formatted->count(),
        ]);
    }

    /**
     * Simpan rute hasil kalkulasi langsung menjadi Travel Plan.
     */
    public function savePlan(Request $request)
    {
        $request->validate([
            'nama_perjalanan' => 'required|string|max:255',
            'budget'          => 'required|numeric|min:0',
            'destinasi_ids'   => 'required|array|min:1',
            'destinasi_ids.*' => 'exists:destinasi,id',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            DB::beginTransaction();

            $firstDest = Destinasi::find($request->destinasi_ids[0]);

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

            $totalCost = Destinasi::whereIn('id', $request->destinasi_ids)->sum('harga');

            $plan = Auth::user()->travelPlans()->create([
                'nama_perjalanan' => $request->nama_perjalanan,
                'tujuan'          => $firstDest->kota ?? 'Perjalanan Rute',
                'budget'          => $request->budget,
                'total_cost'      => $totalCost,
                'status'          => 'Perencanaan Aktif',
                'tanggal_berangkat' => $request->tanggal_mulai ?? now()->format('Y-m-d'),
                'tanggal_selesai' => $request->tanggal_selesai ?? now()->addDays(2)->format('Y-m-d'),
                'schedules_json'  => $schedulesPayload,
            ]);

            DB::commit();

            return redirect()->route('travel-plans.show', $plan->id_perencanaan)
                ->with('success', 'Rencana perjalanan "' . $plan->nama_perjalanan . '" berhasil dibuat dari Optimasi Budget & Rute!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan plan dari BudgetController: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan rencana perjalanan.');
        }
    }
}
