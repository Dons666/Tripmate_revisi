<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\Rating;
use App\Models\User;
use App\Models\Appeal;
use App\Models\PenyediaTravel;
use App\Models\Travel;
use App\Models\TravelPlan;
use App\Models\UserNotification;
use App\Models\AdminLog;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Services\GeminiFilterService;


/**
 * Controller Khusus Admin Panel
 * --------------------------------------------------------------------------
 * Mengelola seluruh fungsi manajemen admin, mencakup:
 * 1. Dashboard Statistik & Notifikasi Pengajuan Banding Akun
 * 2. CRUD Tempat (Wisata, Kuliner, Penginapan)
 * 3. Moderasi Komentar & Pengiriman Teguran Member
 * 4. Pengelolaan Akun Member & Penonaktifan Akun (dengan alasan)
 * 5. Persetujuan & Penolakan Pengajuan Banding Akun
 */
class AdminController extends Controller
{
    private const DEACTIVATION_REASONS = [
        'spam' => 'Spam atau promosi berlebihan',
        'abuse' => 'Bahasa kasar / pelecehan',
        'fraud' => 'Indikasi akun palsu / penipuan',
        'other' => 'Lainnya',
    ];

    /**
     * Halaman Dashboard Utama Admin
     * Menampilkan statistik total data dan kotak notifikasi pengajuan banding.
     */
    public function dashboard()
    {
        $destinationCount = Destinasi::where('tipe', 'wisata')->count();
        $culinaryCount = Destinasi::where('tipe', 'kuliner')->count();
        $stayCount = Destinasi::where('tipe', 'penginapan')->count();
        $totalPlaces = $destinationCount + $culinaryCount + $stayCount;
        $commentCount = Rating::count();

        // Escrow & Financial Metrics
        $totalEscrowHolding = TravelPlan::where('payment_status', 'escrow_held')->sum('budget');
        $totalEscrowReleased = TravelPlan::where('payment_status', 'payout_released')->sum('budget');
        $pendingEscrowCount = TravelPlan::whereIn('payment_status', ['escrow_held', 'pending_admin'])->count();

        // User & Partner Metrics
        $userCount = User::count();
        $activeUserCount = User::where('is_active', true)->count();
        $deactivatedUserCount = User::where('is_active', false)->count();

        $travelCount = PenyediaTravel::count();
        $pendingTravelCount = PenyediaTravel::where('status', 'pending')->count();
        $approvedTravelCount = PenyediaTravel::where('status', 'approved')->count();

        $topPlaces = Destinasi::withCount('ratings')
            ->withAvg('ratings', 'skor_rating')
            ->having('ratings_count', '>', 0)
            ->orderByDesc('ratings_avg_skor_rating')
            ->orderByDesc('ratings_count')
            ->limit(3)
            ->get()
            ->map(function (Destinasi $item) {
                return [
                    'type' => $this->labelForType($item->tipe),
                    'type_class' => $this->classForType($item->tipe),
                    'name' => $item->name,
                    'rating' => (float) ($item->ratings_avg_skor_rating ?? 0),
                    'comments_count' => (int) ($item->ratings_count ?? 0),
                    'detail_url' => $this->detailRouteForType($item),
                ];
            });

        // Ambil data pengajuan banding akun dari pengguna
        $appeals = Appeal::with('user')->latest()->get();

        $notifications = $appeals->map(function (Appeal $appeal) {
            return [
                'id' => $appeal->id,
                'title' => 'Pengajuan Banding Akun',
                'user_name' => $appeal->user?->name ?? $appeal->email,
                'user_email' => $appeal->email,
                'reason' => $appeal->reason,
                'status' => $appeal->status,
                'is_unread' => !$appeal->is_read,
                'time' => $appeal->created_at?->diffForHumans() ?? '-',
                'message' => 'Member (' . $appeal->email . ') mengajukan banding: "' . $appeal->reason . '"',
            ];
        });

        $unreadNotificationCount = Appeal::where('is_read', false)->count();

        // Recent Activity Summaries
        $recentBookings = TravelPlan::with(['user', 'travel'])
            ->whereNotNull('travel_id')
            ->latest()
            ->take(5)
            ->get();

        $recentPendingProviders = PenyediaTravel::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $placeTypeStats = [
            [
                'label' => 'Destinasi Wisata',
                'type' => 'wisata',
                'icon' => '🌴',
                'color' => '#0284c7',
                'bg_color' => '#f0f9ff',
                'border_color' => '#bae6fd',
                'place_count' => $destinationCount,
                'comment_count' => Rating::whereHas('destinasi', fn($q) => $q->where('tipe', 'wisata'))->count(),
            ],
            [
                'label' => 'Wisata Kuliner',
                'type' => 'kuliner',
                'icon' => '🍜',
                'color' => '#c2410c',
                'bg_color' => '#fff7ed',
                'border_color' => '#ffedd5',
                'place_count' => $culinaryCount,
                'comment_count' => Rating::whereHas('destinasi', fn($q) => $q->where('tipe', 'kuliner'))->count(),
            ],
            [
                'label' => 'Penginapan',
                'type' => 'penginapan',
                'icon' => '🏨',
                'color' => '#059669',
                'bg_color' => '#ecfdf5',
                'border_color' => '#a7f3d0',
                'place_count' => $stayCount,
                'comment_count' => Rating::whereHas('destinasi', fn($q) => $q->where('tipe', 'penginapan'))->count(),
            ],
        ];

        return view('admin.adminDashboard', [
            'destinationCount' => $destinationCount,
            'culinaryCount' => $culinaryCount,
            'stayCount' => $stayCount,
            'totalPlaces' => $totalPlaces,
            'commentCount' => $commentCount,
            'totalEscrowHolding' => $totalEscrowHolding,
            'totalEscrowReleased' => $totalEscrowReleased,
            'pendingEscrowCount' => $pendingEscrowCount,
            'userCount' => $userCount,
            'activeUserCount' => $activeUserCount,
            'deactivatedUserCount' => $deactivatedUserCount,
            'travelCount' => $travelCount,
            'pendingTravelCount' => $pendingTravelCount,
            'approvedTravelCount' => $approvedTravelCount,
            'topPlaces' => $topPlaces,
            'notifications' => $notifications,
            'unreadNotificationCount' => $unreadNotificationCount,
            'recentBookings' => $recentBookings,
            'recentPendingProviders' => $recentPendingProviders,
            'placeTypeStats' => $placeTypeStats,
        ]);
    }

    public function placesIndex()
    {
        $destinations = $this->queryByType('wisata');
        $culinary = $this->queryByType('kuliner');
        $stays = $this->queryByType('penginapan');

        return view('admin.managePlaces', [
            'destinationCount' => $destinations->count(),
            'culinaryCount' => $culinary->count(),
            'stayCount' => $stays->count(),
            'destinations' => $destinations,
            'culinary' => $culinary,
            'stays' => $stays,
        ]);
    }

    public function createDestination()
    {
        return view('admin.destinations.form', [
            'destination' => new Destinasi(),
            'isEdit' => false,
        ]);
    }

    public function storeDestination(Request $request): RedirectResponse
    {
        $data = $this->validatePlaceRequest($request, true);
        $place = new Destinasi();
        $this->fillPlace($place, $data, 'wisata', true);

        AdminLog::record(
            action: 'create',
            entityType: 'destinasi',
            entityId: $place->id,
            entityName: $place->nama_destinasi,
            summary: "Menambahkan destinasi wisata baru '{$place->nama_destinasi}'",
            changes: ['Nama Tempat', 'Kota', 'Kategori', 'Harga Tiket'],
            location: $place->kota
        );

        return redirect()
            ->route('admin.places.index')
            ->with('success', 'Destinasi berhasil ditambahkan.');
    }

    public function showDestination(Destinasi $destination)
    {
        $this->ensureType($destination, 'wisata');

        return view('admin.destinations.show', ['destination' => $destination]);
    }

    public function editDestination(Destinasi $destination)
    {
        $this->ensureType($destination, 'wisata');

        return view('admin.destinations.form', [
            'destination' => $destination,
            'isEdit' => true,
        ]);
    }

    public function updateDestination(Request $request, Destinasi $destination): RedirectResponse
    {
        $this->ensureType($destination, 'wisata');
        $data = $this->validatePlaceRequest($request, false);
        $this->fillPlace($destination, $data, 'wisata', false);

        AdminLog::record(
            action: 'update',
            entityType: 'destinasi',
            entityId: $destination->id,
            entityName: $destination->nama_destinasi,
            summary: "Mengubah data destinasi wisata '{$destination->nama_destinasi}'",
            changes: ['Nama Tempat', 'Alamat', 'Harga Tiket', 'Jam Operasional'],
            location: $destination->kota
        );

        return redirect()
            ->route('admin.destinations.show', $destination)
            ->with('success', 'Destinasi berhasil diperbarui.');
    }

    public function destroyDestination(Destinasi $destination): RedirectResponse
    {
        $this->ensureType($destination, 'wisata');
        $name = $destination->nama_destinasi;
        $city = $destination->kota;
        $id = $destination->id;
        $destination->delete();

        AdminLog::record(
            action: 'delete',
            entityType: 'destinasi',
            entityId: $id,
            entityName: $name,
            summary: "Menghapus destinasi wisata '{$name}'",
            location: $city
        );

        return redirect()
            ->route('admin.places.index')
            ->with('success', 'Destinasi berhasil dihapus.');
    }

    public function createCulinary()
    {
        return view('admin.culinaries.form', [
            'culinary' => new Destinasi(),
            'isEdit' => false,
        ]);
    }

    public function storeCulinary(Request $request): RedirectResponse
    {
        $data = $this->validatePlaceRequest($request, true, true);
        $place = new Destinasi();
        $this->fillPlace($place, $data, 'kuliner', true);

        AdminLog::record(
            action: 'create',
            entityType: 'kuliner',
            entityId: $place->id,
            entityName: $place->nama_destinasi,
            summary: "Menambahkan tempat kuliner baru '{$place->nama_destinasi}'",
            changes: ['Nama Tempat', 'Jenis Masakan', 'Kota'],
            location: $place->kota
        );

        return redirect()
            ->route('admin.places.index')
            ->with('success', 'Data kuliner berhasil ditambahkan.');
    }

    public function showCulinary(Destinasi $culinary)
    {
        $this->ensureType($culinary, 'kuliner');

        return view('admin.culinaries.show', ['culinary' => $culinary]);
    }

    public function editCulinary(Destinasi $culinary)
    {
        $this->ensureType($culinary, 'kuliner');

        return view('admin.culinaries.form', [
            'culinary' => $culinary,
            'isEdit' => true,
        ]);
    }

    public function updateCulinary(Request $request, Destinasi $culinary): RedirectResponse
    {
        $this->ensureType($culinary, 'kuliner');
        $data = $this->validatePlaceRequest($request, false, true);
        $this->fillPlace($culinary, $data, 'kuliner', false);

        AdminLog::record(
            action: 'update',
            entityType: 'kuliner',
            entityId: $culinary->id,
            entityName: $culinary->nama_destinasi,
            summary: "Mengubah data tempat kuliner '{$culinary->nama_destinasi}'",
            changes: ['Nama Tempat', 'Jenis Masakan', 'Alamat'],
            location: $culinary->kota
        );

        return redirect()
            ->route('admin.culinaries.show', $culinary)
            ->with('success', 'Data kuliner berhasil diperbarui.');
    }

    public function destroyCulinary(Destinasi $culinary): RedirectResponse
    {
        $this->ensureType($culinary, 'kuliner');
        $name = $culinary->nama_destinasi;
        $city = $culinary->kota;
        $id = $culinary->id;
        $culinary->delete();

        AdminLog::record(
            action: 'delete',
            entityType: 'kuliner',
            entityId: $id,
            entityName: $name,
            summary: "Menghapus tempat kuliner '{$name}'",
            location: $city
        );

        return redirect()
            ->route('admin.places.index')
            ->with('success', 'Data kuliner berhasil dihapus.');
    }

    public function createStay()
    {
        return view('admin.stays.form', [
            'stay' => new Destinasi(),
            'isEdit' => false,
        ]);
    }

    public function storeStay(Request $request): RedirectResponse
    {
        $data = $this->validatePlaceRequest($request, true);
        $place = new Destinasi();
        $this->fillPlace($place, $data, 'penginapan', true);

        AdminLog::record(
            action: 'create',
            entityType: 'penginapan',
            entityId: $place->id,
            entityName: $place->nama_destinasi,
            summary: "Menambahkan tempat penginapan baru '{$place->nama_destinasi}'",
            changes: ['Nama Tempat', 'Kategori', 'Kota', 'Harga Kamar'],
            location: $place->kota
        );

        return redirect()
            ->route('admin.places.index')
            ->with('success', 'Data penginapan berhasil ditambahkan.');
    }

    public function showStay(Destinasi $stay)
    {
        $this->ensureType($stay, 'penginapan');

        return view('admin.stays.show', ['stay' => $stay]);
    }

    public function editStay(Destinasi $stay)
    {
        $this->ensureType($stay, 'penginapan');

        return view('admin.stays.form', [
            'stay' => $stay,
            'isEdit' => true,
        ]);
    }

    public function updateStay(Request $request, Destinasi $stay): RedirectResponse
    {
        $this->ensureType($stay, 'penginapan');
        $data = $this->validatePlaceRequest($request, false);
        $this->fillPlace($stay, $data, 'penginapan', false);

        AdminLog::record(
            action: 'update',
            entityType: 'penginapan',
            entityId: $stay->id,
            entityName: $stay->nama_destinasi,
            summary: "Mengubah data penginapan '{$stay->nama_destinasi}'",
            changes: ['Nama Tempat', 'Alamat', 'Harga Kamar'],
            location: $stay->kota
        );

        return redirect()
            ->route('admin.stays.show', $stay)
            ->with('success', 'Data penginapan berhasil diperbarui.');
    }

    public function destroyStay(Destinasi $stay): RedirectResponse
    {
        $this->ensureType($stay, 'penginapan');
        $name = $stay->nama_destinasi;
        $city = $stay->kota;
        $id = $stay->id;
        $stay->delete();

        AdminLog::record(
            action: 'delete',
            entityType: 'penginapan',
            entityId: $id,
            entityName: $name,
            summary: "Menghapus tempat penginapan '{$name}'",
            location: $city
        );

        return redirect()
            ->route('admin.places.index')
            ->with('success', 'Data penginapan berhasil dihapus.');
    }

    public function commentsIndex(Request $request)
    {
        $query = Rating::with(['user', 'destinasi']);

        // Filter: Tipe Tempat
        if ($request->filled('type') && in_array($request->type, ['wisata', 'kuliner', 'penginapan'])) {
            $query->whereHas('destinasi', function ($q) use ($request) {
                $q->where('tipe', $request->type);
            });
        }

        // Filter: Rating
        if ($request->filled('rating') && is_numeric($request->rating)) {
            $query->where('skor_rating', (float) $request->rating);
        }

        // Filter: Status Moderasi AI
        if (Schema::hasColumn('ratings', 'is_flagged') && $request->filled('ai_status')) {
            if ($request->ai_status === 'flagged') {
                $query->where('is_flagged', true);
            } elseif ($request->ai_status === 'clean') {
                $query->where('is_flagged', false)->whereNotNull('ai_checked_at');
            } elseif ($request->ai_status === 'unchecked') {
                $query->whereNull('ai_checked_at');
            }
        }

        // Filter: Search Keyword
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('komentar', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function ($uq) use ($keyword) {
                      $uq->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('destinasi', function ($dq) use ($keyword) {
                      $dq->where('nama_destinasi', 'like', "%{$keyword}%");
                  });
            });
        }

        $comments = $query->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(function (Rating $rating) {
                $rating->setAttribute('review', $rating->komentar);
                $rating->setAttribute('rating', $rating->skor_rating);
                $rating->setAttribute('rateable', $rating->destinasi);
                $rating->setAttribute('rateable_type', $this->labelForType($rating->destinasi?->tipe));

                return $rating;
            });

        $totalComments = Rating::count();
        $flaggedCount = Schema::hasColumn('ratings', 'is_flagged') ? Rating::where('is_flagged', true)->count() : 0;
        $uncheckedCount = Schema::hasColumn('ratings', 'ai_checked_at') ? Rating::whereNull('ai_checked_at')->count() : $totalComments;

        return view('admin.comments.index', [
            'comments' => $comments,
            'maxWarningCount' => 3,
            'totalComments' => $totalComments,
            'flaggedCount' => $flaggedCount,
            'uncheckedCount' => $uncheckedCount,
        ]);
    }

    public function scanCommentsWithAi(GeminiFilterService $filterService): RedirectResponse
    {
        if (!Schema::hasColumn('ratings', 'is_flagged')) {
            return back()->withErrors(['warning' => 'Kolom moderasi AI belum dimigrasikan ke database.']);
        }

        $ratings = Rating::whereNotNull('komentar')->where('komentar', '!=', '')->get();
        $scannedCount = 0;
        $flaggedCount = 0;

        foreach ($ratings as $rating) {
            $analysis = $filterService->analyzeComment($rating->komentar);
            $rating->is_flagged = !$analysis['is_safe'];
            $rating->flag_reason = $analysis['reason'];
            $rating->ai_checked_at = now();
            $rating->save();

            $scannedCount++;
            if (!$analysis['is_safe']) {
                $flaggedCount++;
            }
        }

        return back()->with('success', "Scan AI Selesai: Berhasil memindai {$scannedCount} komentar. Ditemukan {$flaggedCount} komentar terindikasi bermasalah.");
    }

    public function recheckCommentWithAi(Rating $comment, GeminiFilterService $filterService): RedirectResponse
    {
        if (empty($comment->komentar)) {
            return back()->with('success', 'Komentar kosong, tidak perlu di-scan.');
        }

        $analysis = $filterService->analyzeComment($comment->komentar);

        if (Schema::hasColumn('ratings', 'is_flagged')) {
            $comment->is_flagged = !$analysis['is_safe'];
            $comment->flag_reason = $analysis['reason'];
            $comment->ai_checked_at = now();
            $comment->save();
        }

        $statusMsg = $analysis['is_safe'] ? 'Aman (Clean)' : 'Terindikasi bermasalah: ' . ($analysis['reason'] ?? 'Konten tidak pantas');
        return back()->with('success', "Analisis Gemini AI untuk komentar ini: {$statusMsg}");
    }

    public function destroyComment(Rating $comment): RedirectResponse
    {
        $userName = $comment->user?->name ?? 'Pengguna';
        $placeName = $comment->destinasi?->nama_destinasi ?? 'Tempat';
        $id = $comment->id;
        $comment->delete();

        AdminLog::record(
            action: 'delete',
            entityType: 'komentar',
            entityId: $id,
            entityName: $userName,
            summary: "Menghapus komentar dari '{$userName}' di {$placeName}",
            changes: ['Komentar Member']
        );

        return redirect()
            ->route('admin.comments.index')
            ->with('success', 'Komentar berhasil dihapus.');
    }

    public function sendWarning(Rating $comment): RedirectResponse
    {
        $user = $comment->user;

        if (!$user) {
            return back()->withErrors(['warning' => 'Member tidak ditemukan untuk komentar ini.']);
        }

        $user->warning_count = (int) ($user->warning_count ?? 0) + 1;
        $user->save();

        AdminLog::record(
            action: 'warning',
            entityType: 'user',
            entityId: $user->id,
            entityName: $user->name,
            summary: "Mengirim peringatan teguran ke pengguna '{$user->name}' (Total Teguran: {$user->warning_count})",
            changes: ['Teguran Member']
        );

        return back()->with('success', 'Peringatan berhasil dikirim.');
    }


    public function usersIndex()
    {
        return view('admin.users.index', [
            'users' => User::orderByDesc('id')->paginate(10),
            'deactivationReasons' => self::DEACTIVATION_REASONS,
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:member,admin,user'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'] === 'member' ? 'user' : $data['role'],
            'is_active' => true,
        ]);

        AdminLog::record(
            action: 'create',
            entityType: 'user',
            entityId: $user->id,
            entityName: $user->name,
            summary: "Menambahkan akun pengguna baru '{$user->name}' ({$user->role})",
            changes: ['Username', 'Email', 'Role', 'Status Akun']
        );

        return back()->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function resetUserPassword(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($data['password']);
        $user->save();

        AdminLog::record(
            action: 'update',
            entityType: 'user',
            entityId: $user->id,
            entityName: $user->name,
            summary: "Mereset password pengguna '{$user->name}'",
            changes: ['Password Pengguna']
        );

        return back()->with('success', 'Password pengguna berhasil direset.');
    }

    public function updateUserStatus(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
            'reason_code' => ['nullable', 'in:' . implode(',', array_keys(self::DEACTIVATION_REASONS))],
            'reason_detail' => ['nullable', 'string', 'min:10', 'max:1000'],
        ]);

        $user->is_active = (bool) $data['is_active'];

        if ($user->is_active) {
            $user->deactivation_reason_code = null;
            $user->deactivation_reason_detail = null;
        } else {
            $user->deactivation_reason_code = $data['reason_code'] ?? 'other';
            $user->deactivation_reason_detail = $data['reason_detail'] ?? null;
        }

        if ($user->id === Auth::id() && !$user->is_active) {
            return back()->withErrors(['is_active' => 'Akun admin aktif tidak dapat menonaktifkan dirinya sendiri.']);
        }

        $user->save();

        $statusStr = $user->is_active ? 'mengaktifkan kembali' : 'menonaktifkan';
        AdminLog::record(
            action: 'update',
            entityType: 'user',
            entityId: $user->id,
            entityName: $user->name,
            summary: "Admin {$statusStr} akun pengguna '{$user->name}'",
            changes: ['Status Akun', 'Alasan Penonaktifan']
        );

        return back()->with('success', 'Status pengguna berhasil diperbarui.');
    }

    public function logs()
    {
        try {
            if (Schema::hasTable('admin_logs')) {
                $logs = AdminLog::with('user')->latest()->paginate(10)->withQueryString();
            } else {
                $logs = new LengthAwarePaginator([], 0, 10, 1, [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]);
            }
        } catch (\Throwable $e) {
            $logs = new LengthAwarePaginator([], 0, 10, 1, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        }

        return view('admin.logs.index', compact('logs'));
    }

    public function appealsIndex(Request $request)
    {
        $query = Appeal::with('user')->latest();

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $appeals = $query->paginate(10)->withQueryString();

        $pendingCount = Appeal::where('status', 'pending')->count();
        $approvedCount = Appeal::where('status', 'approved')->count();
        $rejectedCount = Appeal::where('status', 'rejected')->count();
        $totalCount = Appeal::count();

        // Mark visible unread appeals as read
        Appeal::where('is_read', false)->update(['is_read' => true]);

        return view('admin.appeals.index', compact(
            'appeals',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount'
        ));
    }

    public function approveAppeal(Appeal $appeal): RedirectResponse
    {
        $user = $appeal->user;

        if ($user) {
            $user->is_active = true;
            $user->deactivation_reason_code = null;
            $user->deactivation_reason_detail = null;
            $user->save();
        }

        $appeal->status = 'approved';
        $appeal->is_read = true;
        $appeal->save();

        $userName = $user?->name ?? ($user?->email ?? $appeal->email);
        AdminLog::record(
            action: 'approve',
            entityType: 'appeal',
            entityId: $appeal->id,
            entityName: $userName,
            summary: "Menyetujui pengajuan banding akun '{$userName}'",
            changes: ['Status Banding', 'Status Akun Pengguna']
        );

        return back()->with('success', 'Pengajuan banding disetujui. Akun pengguna (' . ($user?->email ?? $appeal->email) . ') telah diaktifkan kembali.');
    }

    public function rejectAppeal(Appeal $appeal): RedirectResponse
    {
        $appeal->status = 'rejected';
        $appeal->is_read = true;
        $appeal->save();

        $userName = $appeal->user?->name ?? $appeal->email;
        AdminLog::record(
            action: 'reject',
            entityType: 'appeal',
            entityId: $appeal->id,
            entityName: $userName,
            summary: "Menolak pengajuan banding akun '{$userName}'",
            changes: ['Status Banding']
        );

        return back()->with('success', 'Pengajuan banding telah ditolak.');
    }

    public function markAllNotificationsRead(): RedirectResponse
    {
        Appeal::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi pengajuan banding ditandai sudah dibaca.');
    }

    private function queryByType(string $type)
    {
        return Destinasi::where('tipe', $type)
            ->withCount('ratings')
            ->withAvg('ratings', 'skor_rating')
            ->orderByDesc('updated_at')
            ->get();
    }

    private function validatePlaceRequest(Request $request, bool $requireDescription, bool $isCulinary = false): array
    {
        $descriptionRule = $requireDescription ? 'required|string' : 'nullable|string';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'place_address' => ['required', 'string', 'max:1000'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'price_option' => ['required', 'in:gratis,berbayar'],
            'price_custom' => ['nullable', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'string'],
            'image_files.*' => ['nullable', 'image', 'max:4096'],
            'transport_modes' => ['nullable', 'array'],
            'transport_modes.*' => ['string', 'max:50'],
            'status_lokasi' => ['required', 'in:terkenal,hidden gem'],
            'description' => $descriptionRule,
            'operational_schedule' => ['nullable', 'array'],
            'operational_schedule.*.status' => ['nullable', 'in:open,full_day,closed'],
            'operational_schedule.*.open_time' => ['nullable', 'string'],
            'operational_schedule.*.close_time' => ['nullable', 'string'],
        ];

        if ($isCulinary) {
            $rules['cuisine_type'] = ['required', 'string', 'max:255'];
            $rules['amenities'] = ['nullable', 'array'];
            $rules['amenities.*'] = ['string', 'max:120'];
        } else {
            $rules['category'] = ['required', 'string', 'max:255'];
            $rules['amenities'] = ['nullable', 'array'];
            $rules['amenities.*'] = ['string', 'max:120'];
        }

        return $request->validate($rules);
    }

    private function fillPlace(Destinasi $place, array $data, string $type, bool $isCreate): void
    {
        $price = $data['price_option'] === 'gratis' ? 0 : (float) ($data['price_custom'] ?? 0);
        $category = $type === 'kuliner' ? $data['cuisine_type'] : $data['category'];
        $transport = isset($data['transport_modes']) ? implode(', ', $data['transport_modes']) : null;
        $amenities = isset($data['amenities']) ? implode(', ', $data['amenities']) : null;

        $place->nama_destinasi = $data['name'];
        $place->tipe = $type;
        $place->kota = $data['city'];
        $place->kategori = $category;
        $place->harga = $price;
        $place->hidden_gem = $data['status_lokasi'] === 'hidden gem';
        $place->deskripsi = $data['description'] ?? null;
        $place->fasilitas = $amenities;
        $place->alamat = $this->encodeAddress($data['place_address'], $data['province'] ?? null);
        $place->latitude = $this->normalizeLatitude($data['latitude'] ?? null);
        $place->longitude = $this->normalizeLongitude($data['longitude'] ?? null);
        $place->transportasi = $transport;
        $place->hari_operasional = $this->serializeSchedule($data['operational_schedule'] ?? []);

        if (!empty($data['image_url'])) {
            $place->gambar = trim($data['image_url']);
        } elseif (!empty($data['image_files']) && is_array($data['image_files'])) {
            $paths = [];
            $dir = storage_path('app/public/destinasi-images');
            if (!file_exists($dir)) {
                @mkdir($dir, 0755, true);
            }

            foreach ($data['image_files'] as $image) {
                if ($image && $image->isValid()) {
                    $storedPath = $image->store('destinasi-images', 'public');
                    $paths[] = $storedPath;

                    // Dual-store copy to public_path
                    try {
                        $publicCopy = public_path('storage/' . $storedPath);
                        @mkdir(dirname($publicCopy), 0755, true);
                        @copy(storage_path('app/public/' . $storedPath), $publicCopy);
                    } catch (\Throwable $e) {}
                }
            }

            if (count($paths) === 1) {
                $place->gambar = $paths[0];
            } elseif (count($paths) > 1) {
                $place->gambar = json_encode($paths);
            }
        } elseif ($isCreate) {
            $place->gambar = $place->gambar ?: null;
        }

        $place->save();
    }

    private function normalizeLatitude($val): ?float
    {
        if ($val === null || $val === '') {
            return null;
        }

        $num = (float) $val;

        while (abs($num) > 90) {
            $num /= 10;
        }

        return round($num, 7);
    }

    private function normalizeLongitude($val): ?float
    {
        if ($val === null || $val === '') {
            return null;
        }

        $num = (float) $val;

        while (abs($num) > 180) {
            $num /= 10;
        }

        return round($num, 7);
    }

    private function serializeSchedule(array $schedule): ?string
    {
        if (empty($schedule)) {
            return null;
        }

        $rows = [];

        foreach ($schedule as $day => $item) {
            $status = $item['status'] ?? 'closed';

            if ($status === 'open') {
                $open = $item['open_time'] ?? '00:00';
                $close = $item['close_time'] ?? '23:59';
                $rows[] = $day . ':' . $open . '-' . $close;
            } elseif ($status === 'full_day') {
                $rows[] = $day . ':00:00-23:59';
            } else {
                $rows[] = $day . ':closed';
            }
        }

        return implode(';', $rows);
    }

    private function encodeAddress(string $address, ?string $province): string
    {
        if (!$province) {
            return $address;
        }

        return $address . '||' . $province;
    }

    private function ensureType(Destinasi $place, string $expectedType): void
    {
        abort_unless($place->tipe === $expectedType, 404);
    }

    private function labelForType(?string $type): string
    {
        return match ($type) {
            'kuliner' => 'Kuliner',
            'penginapan' => 'Penginapan',
            default => 'Destinasi',
        };
    }

    private function classForType(?string $type): string
    {
        return match ($type) {
            'kuliner' => 'type-culinary',
            'penginapan' => 'type-stay',
            default => 'type-destination',
        };
    }

    private function detailRouteForType(Destinasi $item): string
    {
        return match ($item->tipe) {
            'kuliner' => route('admin.culinaries.show', $item),
            'penginapan' => route('admin.stays.show', $item),
            default => route('admin.destinations.show', $item),
        };
    }

    /**
     * Manajemen Penyedia Travel (Admin Index)
     */
    public function penyediaTravelIndex(Request $request)
    {
        $query = PenyediaTravel::query();

        if ($search = $request->input('search')) {
            $query->where('nama_travel', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('kota_asal_travel', 'like', "%{$search}%")
                  ->orWhere('jenis_kendaraan', 'like', "%{$search}%")
                  ->orWhere('nomor_hp_pemilik_travel', 'like', "%{$search}%");
        }

        $penyediaTravels = $query->latest()->paginate(10)->withQueryString();

        return view('admin.penyedia_travel.index', compact('penyediaTravels'));
    }

    /**
     * Form Tambah Penyedia Travel untuk Admin
     */
    public function penyediaTravelCreate()
    {
        return view('admin.penyedia_travel.create');
    }

    /**
     * Simpan Penyedia Travel Baru dari Admin
     */
    public function penyediaTravelStore(Request $request)
    {
        $validated = $request->validate([
            'nama_travel' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'alamat_travel' => 'required|string',
            'kota_asal_travel' => 'required|string|max:255',
            'jenis_kendaraan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'jadwal_ketersediaan' => 'required|string',
            'rekening' => 'required|string|max:255',
            'surat_izin_usaha_travel' => 'required',
            'ktp_pemilik' => 'required',
            'nomor_hp_pemilik_travel' => 'required|string|max:30',
        ]);

        if ($request->hasFile('surat_izin_usaha_travel')) {
            $validated['surat_izin_usaha_travel'] = $request->file('surat_izin_usaha_travel')->store('travel_docs/surat_izin', 'public');
        } elseif (is_string($request->input('surat_izin_usaha_travel'))) {
            $validated['surat_izin_usaha_travel'] = $request->input('surat_izin_usaha_travel');
        }

        if ($request->hasFile('ktp_pemilik')) {
            $validated['ktp_pemilik'] = $request->file('ktp_pemilik')->store('travel_docs/ktp', 'public');
        } elseif (is_string($request->input('ktp_pemilik'))) {
            $validated['ktp_pemilik'] = $request->input('ktp_pemilik');
        }

        $penyedia = PenyediaTravel::create(array_merge($validated, ['status' => 'approved']));

        User::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['nama_travel'],
                'password' => Hash::make($validated['password']),
                'role' => 'travel',
                'is_active' => true,
            ]
        );

        AdminLog::record(
            action: 'create',
            entityType: 'penyedia_travel',
            entityId: $penyedia->id,
            entityName: $validated['nama_travel'],
            summary: "Menambahkan dan menyetujui penyedia travel baru '{$validated['nama_travel']}'",
            changes: ['Nama Travel', 'Email', 'Kendaraan', 'Status Approved'],
            location: $validated['kota_asal_travel']
        );

        return redirect()->route('admin.penyedia-travel.index')->with('success', 'Data penyedia travel berhasil ditambahkan dan disetujui!');
    }

    /**
     * Setujui / ACC Pendaftaran Penyedia Travel
     */
    public function penyediaTravelApprove(PenyediaTravel $penyediaTravel)
    {
        $penyediaTravel->update(['status' => 'approved']);

        if ($penyediaTravel->email) {
            $user = User::updateOrCreate(
                ['email' => $penyediaTravel->email],
                [
                    'name' => $penyediaTravel->nama_travel,
                    'password' => $penyediaTravel->password ?? Hash::make('password123'),
                    'role' => 'travel',
                    'is_active' => true,
                ]
            );

            // Create or update the Travel package data
            Travel::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_travel' => $penyediaTravel->nama_travel,
                    'slug' => Str::slug($penyediaTravel->nama_travel),
                    'layanan' => $penyediaTravel->jenis_kendaraan ?? 'Paket Tur Dasar',
                    'deskripsi' => $penyediaTravel->alamat_travel . ' - ' . $penyediaTravel->jadwal_ketersediaan,
                    'harga_paket' => $penyediaTravel->harga ?? 100000,
                    'kota' => $penyediaTravel->kota_asal_travel ?? 'Belum Ditentukan',
                    'kontak' => $penyediaTravel->nomor_hp_pemilik_travel ?? '-',
                    'gambar' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
                    'rating' => 5.0
                ]
            );
        }

        AdminLog::record(
            action: 'approve',
            entityType: 'penyedia_travel',
            entityId: $penyediaTravel->id,
            entityName: $penyediaTravel->nama_travel,
            summary: "Menyetujui (ACC) pendaftaran penyedia travel '{$penyediaTravel->nama_travel}'",
            changes: ['Status Pendaftaran'],
            location: $penyediaTravel->kota_asal_travel
        );

        return redirect()->route('admin.penyedia-travel.index')->with('success', "Penyedia travel '{$penyediaTravel->nama_travel}' telah disetujui (ACC) dan berhasil ditambahkan ke daftar paket travel!");
    }

    /**
     * Tolak Pendaftaran Penyedia Travel
     */
    public function penyediaTravelReject(PenyediaTravel $penyediaTravel)
    {
        $penyediaTravel->update(['status' => 'rejected']);

        if ($penyediaTravel->email) {
            User::where('email', $penyediaTravel->email)->update(['is_active' => false]);
        }

        AdminLog::record(
            action: 'reject',
            entityType: 'penyedia_travel',
            entityId: $penyediaTravel->id,
            entityName: $penyediaTravel->nama_travel,
            summary: "Menolak pendaftaran penyedia travel '{$penyediaTravel->nama_travel}'",
            changes: ['Status Pendaftaran'],
            location: $penyediaTravel->kota_asal_travel
        );

        return redirect()->route('admin.penyedia-travel.index')->with('success', "Penyedia travel '{$penyediaTravel->nama_travel}' telah ditolak!");
    }

    /**
     * Form Edit Penyedia Travel untuk Admin
     */
    public function penyediaTravelEdit(PenyediaTravel $penyediaTravel)
    {
        return view('admin.penyedia_travel.edit', compact('penyediaTravel'));
    }

    /**
     * Update Penyedia Travel dari Admin
     */
    public function penyediaTravelUpdate(Request $request, PenyediaTravel $penyediaTravel)
    {
        $validated = $request->validate([
            'nama_travel' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8',
            'alamat_travel' => 'nullable|string',
            'kota_asal_travel' => 'nullable|string|max:255',
            'jenis_kendaraan' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric|min:0',
            'jadwal_ketersediaan' => 'nullable|string',
            'rekening' => 'nullable|string|max:255',
            'surat_izin_usaha_travel' => 'nullable',
            'ktp_pemilik' => 'nullable',
            'nomor_hp_pemilik_travel' => 'required|string|max:30',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        if ($request->hasFile('surat_izin_usaha_travel')) {
            $validated['surat_izin_usaha_travel'] = $request->file('surat_izin_usaha_travel')->store('travel_docs/surat_izin', 'public');
        } elseif ($request->filled('surat_izin_usaha_travel')) {
            $validated['surat_izin_usaha_travel'] = $request->input('surat_izin_usaha_travel');
        } else {
            unset($validated['surat_izin_usaha_travel']);
        }

        if ($request->hasFile('ktp_pemilik')) {
            $validated['ktp_pemilik'] = $request->file('ktp_pemilik')->store('travel_docs/ktp', 'public');
        } elseif ($request->filled('ktp_pemilik')) {
            $validated['ktp_pemilik'] = $request->input('ktp_pemilik');
        } else {
            unset($validated['ktp_pemilik']);
        }

        $penyediaTravel->update($validated);

        AdminLog::record(
            action: 'update',
            entityType: 'penyedia_travel',
            entityId: $penyediaTravel->id,
            entityName: $penyediaTravel->nama_travel,
            summary: "Mengubah data penyedia travel '{$penyediaTravel->nama_travel}'",
            changes: ['Informasi Travel', 'Kontak'],
            location: $penyediaTravel->kota_asal_travel
        );

        return redirect()->route('admin.penyedia-travel.index')->with('success', 'Data penyedia travel berhasil diperbarui!');
    }

    /**
     * Hapus Penyedia Travel dari Admin
     */
    public function penyediaTravelDestroy(PenyediaTravel $penyediaTravel)
    {
        $name = $penyediaTravel->nama_travel;
        $city = $penyediaTravel->kota_asal_travel;
        $id = $penyediaTravel->id;
        $penyediaTravel->delete();

        AdminLog::record(
            action: 'delete',
            entityType: 'penyedia_travel',
            entityId: $id,
            entityName: $name,
            summary: "Menghapus data penyedia travel '{$name}'",
            location: $city
        );

        return redirect()->route('admin.penyedia-travel.index')->with('success', 'Data penyedia travel berhasil dihapus!');
    }

    /**
     * Lihat / Unduh Dokumen Penyedia Travel (Surat Izin / KTP) Aman Khusus Admin
     */
    public function penyediaTravelDocument(PenyediaTravel $penyediaTravel, string $type)
    {
        $path = match($type) {
            'surat_izin' => $penyediaTravel->surat_izin_usaha_travel,
            'ktp' => $penyediaTravel->ktp_pemilik,
            default => null
        };

        if (!$path) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path);
        }

        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath)) {
            return response()->file($fullPath);
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return redirect()->away($path);
        }

        return response($path, 200)->header('Content-Type', 'text/plain');
    }

    public function escrowProof(TravelPlan $travelPlan)
    {
        $path = $travelPlan->payment_proof;

        if (!$path) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path);
        }

        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath)) {
            return response()->file($fullPath);
        }

        abort(404, 'File bukti pembayaran hilang atau rusak.');
    }

    /**
     * Dashboard Escrow Admin: Pantau Dana Holding Pembayaran Paket Travel
     */
    public function escrowDashboard()
    {
        $escrowPlans = TravelPlan::whereNotNull('travel_id')
            ->where('is_checkout', true)
            ->with(['user', 'travel', 'destinasis'])
            ->orderByDesc('updated_at')
            ->get();

        $totalHolding = $escrowPlans->where('payment_status', 'escrow_held')->sum('budget');

        $totalReleased = $escrowPlans->where('payment_status', 'payout_released')->sum('budget');

        return view('admin.escrow.index', compact('escrowPlans', 'totalHolding', 'totalReleased'));
    }

    /**
     * Verifikasi Bukti Pembayaran dari User
     */
    public function verifyPayment(TravelPlan $travelPlan)
    {
        $travelPlan->load('travel');

        if ($travelPlan->payment_status !== 'pending_admin') {
            return back()->with('error', 'Status pembayaran tidak valid untuk diverifikasi.');
        }

        $travelPlan->update([
            'payment_status' => 'escrow_held',
            'trip_status'    => 'ready',
            'status'         => 'Sedang Berjalan',
        ]);

        // 1. Notifikasi ke User Pemesan bahwa pembayaran valid
        UserNotification::sendNotification(
            $travelPlan->user_id,
            '✅ Pembayaran Berhasil Diverifikasi!',
            'Bukti pembayaran paket travel "' . ($travelPlan->travel->nama_travel ?? 'Travel') . '" sebesar Rp ' . number_format($travelPlan->budget, 0, ',', '.') . ' telah diverifikasi. Dana Anda kini disimpan aman oleh Admin (Escrow) hingga trip selesai.',
            'success'
        );

        // 2. Notifikasi ke Agen Travel bahwa ada pemesanan baru yang valid
        if ($travelPlan->travel && $travelPlan->travel->user_id) {
            UserNotification::sendNotification(
                $travelPlan->travel->user_id,
                '📅 Pemesanan Tur Baru (Lunas)!',
                'User telah melunasi paket tur "' . $travelPlan->nama_perjalanan . '" untuk tanggal ' . ($travelPlan->tanggal_mulai ? $travelPlan->tanggal_mulai->format('d M Y') : 'jadwal terpilih') . '. Uang disimpan Admin, silakan mulai persiapan.',
                'info'
            );
        }

        AdminLog::record(
            action: 'verify',
            entityType: 'escrow',
            entityId: $travelPlan->id,
            entityName: $travelPlan->nama_perjalanan,
            summary: "Memverifikasi pembayaran paket travel '{$travelPlan->nama_perjalanan}' (Dana Escrow: Rp " . number_format($travelPlan->budget, 0, ',', '.') . ")",
            changes: ['Status Pembayaran', 'Status Escrow']
        );

        return back()->with('success', 'Bukti pembayaran berhasil diverifikasi! Dana masuk ke status Escrow.');
    }

    /**
     * Admin Transfer Uang Escrow ke Agen Travel setelah tur selesai
     */
    public function releasePayout(TravelPlan $travelPlan)
    {
        $travelPlan->load('travel');

        if (!$travelPlan->travel) {
            return back()->with('error', 'Travel Plan tidak memiliki data Agen Travel.');
        }

        $travelPlan->update([
            'payment_status'     => 'payout_released',
            'payout_released_at' => now(),
        ]);

        $amount = number_format($travelPlan->budget ?? 0, 0, ',', '.');

        // Notifikasi ke Agen Travel
        if ($travelPlan->travel->user_id) {
            UserNotification::sendNotification(
                $travelPlan->travel->user_id,
                '💸 Pencairan Dana Escrow Berhasil!',
                'Admin telah secara resmi mentransfer dana sebesar Rp ' . $amount . ' ke rekening Agen Travel Anda untuk pesanan "' . $travelPlan->nama_perjalanan . '". Terima kasih atas pelayanan terbaik Anda!',
                'success'
            );
        }

        // Notifikasi ke User
        UserNotification::sendNotification(
            $travelPlan->user_id,
            '✅ Transaksi Perjalanan Selesai Sepenuhnya',
            'Seluruh proses perjalanan tur Anda bersama "' . ($travelPlan->travel->nama_travel ?? 'Travel') . '" telah selesai dan dana telah resmi disalurkan oleh Admin.',
            'info'
        );

        AdminLog::record(
            action: 'verify',
            entityType: 'escrow',
            entityId: $travelPlan->id,
            entityName: $travelPlan->nama_perjalanan,
            summary: "Mencairkan dana Escrow sebesar Rp {$amount} ke Agen Travel '{$travelPlan->travel->nama_travel}'",
            changes: ['Status Pencairan Escrow', 'Waktu Pencairan']
        );

        return back()->with('success', 'Dana Escrow sebesar Rp ' . $amount . ' berhasil disalurkan dari Admin ke Agen Travel!');
    }
}
