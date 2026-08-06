<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ringkasan Eksekutif Sistem</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; color: #1f2937; }
        .layout { min-height: 100vh; display: flex; }
        .content { flex: 1; padding: 28px; }
        .top-info { display: flex; justify-content: flex-end; margin-bottom: 12px; color: #6b7280; font-weight: 600; font-size: 13px; }
        .container { max-width: 1200px; margin: 0 auto; }
        
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .admin-header h1 { color: #111827; font-size: 26px; font-weight: 800; }
        .admin-title { color: #e74c3c; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Grid Cards */
        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 28px; }
        .summary-card { background: #fff; border-radius: 16px; padding: 22px; border: 1px solid #e5e7eb; box-shadow: 0 4px 12px rgba(0,0,0,0.03); transition: transform 0.2s; }
        .summary-card:hover { transform: translateY(-2px); }
        .summary-card-title { font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: flex; items-center; justify-content: space-between; }
        .summary-card-num { font-size: 28px; font-weight: 800; color: #111827; margin-bottom: 6px; }
        .summary-card-sub { font-size: 12px; color: #4b5563; font-weight: 500; }

        .card-emerald { border-left: 5px solid #10b981; }
        .card-sky { border-left: 5px solid #0284c7; }
        .card-amber { border-left: 5px solid #f59e0b; }
        .card-rose { border-left: 5px solid #f43f5e; }

        /* Quick Action Bar */
        .quick-actions { background: #1e293b; color: #fff; border-radius: 16px; padding: 20px; margin-bottom: 28px; }
        .quick-actions h3 { font-size: 14px; font-weight: 700; text-transform: uppercase; tracking-wider; margin-bottom: 14px; color: #94a3b8; }
        .action-btns { display: flex; flex-wrap: wrap; gap: 10px; }
        .action-btn { background: rgba(255, 255, 255, 0.1); color: #fff; text-decoration: none; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; transition: background 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .action-btn:hover { background: rgba(255, 255, 255, 0.2); }
        .action-btn .badge-count { background: #ef4444; color: white; padding: 2px 7px; border-radius: 99px; font-size: 11px; }

        /* Tables & Content Block */
        .section-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 24px; margin-bottom: 28px; }
        .section-card { background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e5e7eb; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .section-title { font-size: 18px; font-weight: 800; color: #111827; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; }
        
        table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: left; }
        th { background: #f8fafc; padding: 10px 12px; color: #475569; font-weight: 700; border-bottom: 1px solid #e2e8f0; }
        td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        
        .badge-status { padding: 3px 8px; border-radius: 99px; font-size: 11px; font-weight: 700; display: inline-block; }
        .status-paid { background: #ecfdf5; color: #047857; }
        .status-pending { background: #fef3c7; color: #b45309; }
        .status-released { background: #e0e7ff; color: #3730a3; }

        .btn-link { color: #0284c7; text-decoration: none; font-weight: 700; font-size: 12px; }
        .btn-link:hover { text-decoration: underline; }

        @media (max-width: 980px) {
            .layout { flex-direction: column; }
            .content { padding: 16px; }
            .section-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="layout">
        @include('admin.partials.sidebar')

        <main class="content">
            <div class="top-info">
                <span>Halo, <strong>{{ Auth::user()->name ?? Auth::user()->username }}</strong> (Administrator)</span>
            </div>

            <div class="container">
                <!-- Header -->
                <div class="admin-header">
                    <div>
                        <span class="admin-title">Executive Summary Dashboard</span>
                        <h1>Ringkasan Seluruh Aktivitas Admin</h1>
                    </div>
                    <span style="background: #dcfce7; color: #166534; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 99px; border: 1px solid #bbf7d0;">
                        🟢 Sistem Berjalan Normal
                    </span>
                </div>

                <!-- Quick Action Bar -->
                <div class="quick-actions">
                    <h3>⚡ Aksi Cepat Administrasi</h3>
                    <div class="action-btns">
                        <a href="{{ route('admin.appeals.index') }}" class="action-btn">
                            📩 Banding Akun Member
                            @if($unreadNotificationCount > 0)
                                <span class="badge-count" style="background: #ef4444; font-weight: 900;">❗️ {{ $unreadNotificationCount }} Baru</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.places.index') }}" class="action-btn">
                            📍 Kelola Tempat Wisata
                        </a>
                        <a href="{{ route('admin.comments.index') }}" class="action-btn">
                            💬 AI Moderasi Komentar
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="action-btn">
                            👥 Kelola Pengguna
                        </a>
                    </div>
                </div>

                <!-- Ringkasan Statistik Utama Cards -->
                <div class="summary-grid">
                    <!-- Total Tempat -->
                    <div class="summary-card card-sky">
                        <div class="summary-card-title">
                            <span>📍 Total Tempat Terdaftar</span>
                            <span>🏛️</span>
                        </div>
                        <div class="summary-card-num">
                            {{ number_format($totalPlaces) }}
                        </div>
                        <div class="summary-card-sub">
                            {{ $destinationCount }} Wisata • {{ $culinaryCount }} Kuliner • {{ $stayCount }} Penginapan
                        </div>
                    </div>

                    <!-- Pengguna & Member -->
                    <div class="summary-card card-rose">
                        <div class="summary-card-title">
                            <span>👥 Akun Pengguna</span>
                            <span>👤</span>
                        </div>
                        <div class="summary-card-num">
                            {{ $userCount }} Member
                        </div>
                        <div class="summary-card-sub">
                            {{ $activeUserCount }} Aktif • <span style="color: #e11d48;">{{ $deactivatedUserCount }} Nonaktif</span>
                        </div>
                    </div>

                    <!-- Total Ulasan -->
                    <div class="summary-card card-emerald">
                        <div class="summary-card-title">
                            <span>💬 Ulasan & Komentar</span>
                            <span>⭐</span>
                        </div>
                        <div class="summary-card-num">
                            {{ number_format($commentCount) }}
                        </div>
                        <div class="summary-card-sub">
                            Total ulasan yang terdaftar pada sistem
                        </div>
                    </div>
                </div>

                <!-- Section Card: Jumlah Tempat & Komentar Menurut Tipe Tempat -->
                <div class="section-card" style="margin-bottom: 28px;">
                    <div class="section-title">
                        <span>📊 Jumlah Tempat & Komentar (Destinasi Wisata, Kuliner, Penginapan)</span>
                        <a href="{{ route('admin.places.index') }}" class="btn-link">Kelola Tempat &rarr;</a>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 16px;">
                        @foreach($placeTypeStats as $stat)
                            <div style="background: {{ $stat['bg_color'] }}; padding: 20px; border-radius: 16px; border: 1px solid {{ $stat['border_color'] }}; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; color: {{ $stat['color'] }}; letter-spacing: 0.5px;">
                                        {{ $stat['icon'] }} {{ $stat['label'] }}
                                    </span>
                                    <span style="font-size: 11px; font-weight: 700; background: #ffffff; color: {{ $stat['color'] }}; padding: 3px 8px; border-radius: 99px; border: 1px solid {{ $stat['border_color'] }};">
                                        Tipe Tempat
                                    </span>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; background: #ffffff; padding: 14px; border-radius: 12px; border: 1px solid {{ $stat['border_color'] }};">
                                    <div>
                                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Jumlah Tempat</div>
                                        <div style="font-size: 24px; font-weight: 900; color: #0f172a; margin-top: 2px;">{{ number_format($stat['place_count']) }}</div>
                                    </div>
                                    <div style="border-left: 1px solid #f1f5f9; padding-left: 12px;">
                                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">💬 Komentar</div>
                                        <div style="font-size: 24px; font-weight: 900; color: {{ $stat['color'] }}; margin-top: 2px;">{{ number_format($stat['comment_count']) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Notification Box Pengajuan Banding -->
                @include('partials.notification-box', [
                    'title' => 'Kotak Notifikasi & Pengajuan Banding Akun',
                    'description' => 'Pantau dan kelola pengajuan banding dari pengguna yang akunnya dinonaktifkan.',
                    'notifications' => $notifications,
                    'unreadNotificationCount' => $unreadNotificationCount,
                    'emptyText' => 'Belum ada pengajuan banding akun dari pengguna.',
                    'markAllReadRoute' => route('notifications.mark-all-read'),
                ])

                <!-- 3 Tempat Terbaik -->
                <div class="section-card">
                    <div class="section-title">
                        <span>🏆 3 Tempat Wisata / Kuliner Terbaik</span>
                        <a href="{{ route('admin.places.index') }}" class="btn-link">Kelola Semua Tempat &rarr;</a>
                    </div>

                    @if($topPlaces->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                            @foreach($topPlaces as $place)
                                <div style="background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
                                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #0284c7;">{{ $place['type'] }}</span>
                                    <h4 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 6px 0;">{{ $place['name'] }}</h4>
                                    <div style="font-size: 20px; font-weight: 900; color: #f59e0b;">★ {{ number_format($place['rating'], 2) }}</div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 4px;">{{ $place['comments_count'] }} ulasan terdaftar</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; color: #64748b; font-size: 13px; padding: 20px 0;">Belum ada tempat dengan ulasan.</div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</body>
</html>