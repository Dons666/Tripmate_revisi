@php
    $unreadAppealsCount = \App\Models\Appeal::where('is_read', false)->count();
@endphp

<style>
    .layout { min-height: 100vh; display: flex; }
    .sidebar { width: 260px; min-width: 260px; background: linear-gradient(180deg, #1f2937 0%, #111827 100%); color: #fff; padding: 24px 16px; display: flex; flex-direction: column; gap: 20px; flex-shrink: 0; box-shadow: 4px 0 15px rgba(0,0,0,0.05); }
    .sidebar .brand { font-size: 20px; font-weight: 800; padding: 8px 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.15); color: #ffffff; letter-spacing: 0.5px; }
    .sidebar .menu { display: flex; flex-direction: column; gap: 6px; }
    .sidebar .menu a, .sidebar .menu button { width: 100%; text-align: left; padding: 11px 14px; border-radius: 10px; border: none; background: transparent; color: #9ca3af; text-decoration: none; font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .sidebar .menu a:hover, .sidebar .menu button:hover { background: rgba(255, 255, 255, 0.1); color: #ffffff; }
    .sidebar .menu a.active { background: #0284c7; color: #ffffff; font-weight: 800; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3); }

    .badge-appeal-alert {
        background: #ef4444;
        color: #ffffff;
        font-size: 10px;
        font-weight: 900;
        padding: 2px 7px;
        border-radius: 99px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        animation: pulse-red 1.5s infinite;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.6);
    }
    @keyframes pulse-red {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1.08); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    @media (max-width: 980px) {
        .layout { flex-direction: column; }
        .sidebar { width: 100%; min-width: 100%; }
    }
</style>

<aside class="sidebar">
    <div class="brand">Admin Panel</div>
    <div class="menu">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span>📊 Home</span></a>
        <a href="{{ route('admin.places.index') }}" class="{{ request()->routeIs('admin.places.*') ? 'active' : '' }}"><span>📍 Manage Tempat</span></a>
        <a href="{{ route('admin.comments.index') }}" class="{{ request()->routeIs('admin.comments.*') ? 'active' : '' }}"><span>💬 Manage Komentar</span></a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><span>👥 Manage Member</span></a>
        <a href="{{ route('admin.appeals.index') }}" class="{{ request()->routeIs('admin.appeals.*') ? 'active' : '' }}">
            <span>📩 Kotak Banding Akun</span>
            @if($unreadAppealsCount > 0)
                <span class="badge-appeal-alert">❗️ {{ $unreadAppealsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.logs') }}" class="{{ request()->routeIs('admin.logs*') ? 'active' : '' }}"><span>📜 Admin Logs</span></a>
        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah yakin ingin logout?')">
            @csrf
            <button type="submit">🚪 Logout</button>
        </form>
    </div>
</aside>
