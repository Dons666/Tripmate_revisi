<nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="{{ (Auth::check() && Auth::user()->role === 'admin') ? route('admin.dashboard') : ((Auth::check() && Auth::user()->role === 'travel') ? route('travel.dashboard') : route('home')) }}" class="text-2xl font-bold tracking-tight text-sky-600 flex items-center gap-2">
                <span>TripMate</span>
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <span class="text-[10px] font-black uppercase tracking-wider bg-slate-900 text-white px-2 py-0.5 rounded-md">ADMIN</span>
                @elseif(Auth::check() && Auth::user()->role === 'travel')
                    <span class="text-[10px] font-black uppercase tracking-wider bg-sky-100 text-sky-700 px-2 py-0.5 rounded-md">PARTNER</span>
                @endif
            </a>

            @if(Auth::check() && Auth::user()->role === 'admin')
                <!-- ADMIN EXCLUSIVE NAV LINKS -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-lg text-xs font-extrabold text-slate-700 hover:text-sky-600 hover:bg-sky-50 transition {{ request()->routeIs('admin.dashboard') ? 'bg-sky-50 text-sky-600 font-black' : '' }}">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('admin.places.index') }}" class="px-3.5 py-2 rounded-lg text-xs font-extrabold text-slate-700 hover:text-sky-600 hover:bg-sky-50 transition {{ request()->routeIs('admin.places.*') ? 'bg-sky-50 text-sky-600 font-black' : '' }}">
                        📍 Kelola Tempat
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-lg text-xs font-extrabold text-slate-700 hover:text-sky-600 hover:bg-sky-50 transition {{ request()->routeIs('admin.users.*') ? 'bg-sky-50 text-sky-600 font-black' : '' }}">
                        👥 Users
                    </a>
                    <a href="{{ route('admin.appeals.index') }}" class="relative px-3.5 py-2 rounded-lg text-xs font-extrabold text-slate-700 hover:text-sky-600 hover:bg-sky-50 transition {{ request()->routeIs('admin.appeals.*') ? 'bg-sky-50 text-sky-600 font-black' : '' }}">
                        📩 Banding
                        @php
                            $navUnreadAppeals = \App\Models\Appeal::where('is_read', false)->count();
                        @endphp
                        @if($navUnreadAppeals > 0)
                            <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center animate-bounce shadow">
                                !
                            </span>
                        @endif
                    </a>
                </div>
            @elseif(!Auth::check() || Auth::user()->role !== 'travel')
                <!-- USER NAV LINKS -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:text-sky-600 hover:bg-sky-50 transition {{ request()->routeIs('home') ? 'nav-active' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('travel-plans.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:text-sky-600 hover:bg-sky-50 transition {{ request()->routeIs('travel-plans.index') ? 'nav-active' : '' }}">
                        Rencana
                    </a>
                    <a href="{{ route('bookmarks.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:text-sky-600 hover:bg-sky-50 transition {{ request()->routeIs('bookmarks.index') ? 'nav-active' : '' }}">
                        Bookmarks
                    </a>
                    <a href="{{ route('penyedia-travel.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:text-sky-600 hover:bg-sky-50 transition {{ request()->routeIs('penyedia-travel.*') ? 'nav-active' : '' }}">
                        Daftar Travel
                    </a>
                </div>
            @endif

            <div class="flex items-center gap-3">
                @if(!request()->routeIs('home') && (!Auth::check() || (Auth::user()->role !== 'travel' && Auth::user()->role !== 'admin')))
                    <a href="javascript:history.back()" class="text-sm text-gray-500 hover:text-sky-600 font-medium flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        <span class="hidden sm:inline">Kembali</span>
                    </a>
                @endif

                @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-sky-600 hover:text-sky-700 hover:bg-sky-50 rounded-xl transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-sky-600 hover:bg-sky-500 rounded-xl shadow-sm transition">
                        Daftar
                    </a>
                @endguest

                @auth
                    <!-- NOTIFICATION BELL -->
                    @php
                        $userNotifications = \App\Models\UserNotification::where('user_id', Auth::id())
                            ->orderByDesc('created_at')
                            ->take(5)
                            ->get();
                        $unreadCount = \App\Models\UserNotification::where('user_id', Auth::id())
                            ->where('is_read', false)
                            ->count();
                    @endphp

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-sky-600 hover:bg-sky-50 rounded-xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-1 right-1 bg-rose-500 text-white text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center animate-bounce">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 py-3 px-4 z-50 space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                <h4 class="text-xs font-black uppercase text-slate-800 tracking-wider">🔔 Notifikasi Anda</h4>
                                <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-full">{{ $userNotifications->count() }} Terkini</span>
                            </div>

                            <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                                @forelse($userNotifications as $notif)
                                    <div class="p-3 rounded-xl {{ $notif->is_read ? 'bg-slate-50 border border-slate-100' : 'bg-sky-50/80 border border-sky-200' }} transition">
                                        <p class="text-xs font-extrabold text-slate-800">{{ $notif->title }}</p>
                                        <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                                        <span class="text-[10px] text-slate-400 mt-1 block">{{ $notif->created_at ? $notif->created_at->diffForHumans() : '' }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 text-center py-4">Belum ada notifikasi.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-black transition flex items-center gap-1.5 shadow-sm">
                            ⚙️ Admin Dashboard
                        </a>
                    @elseif(Auth::check() && Auth::user()->role === 'travel')
                        <a href="{{ route('travel.dashboard') }}" class="px-3.5 py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-black transition flex items-center gap-1.5 shadow-sm">
                            🚌 Partner Dashboard
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                        @if(Auth::user()->avatar)
                            <img src="{{ str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . ltrim(str_replace(['public/', 'storage/'], '', Auth::user()->avatar), '/')) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border-2 border-sky-100 shadow-sm" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-8 h-8 bg-sky-100 text-sky-600 rounded-full flex items-center justify-center text-xs font-bold border-2 border-white shadow-sm" style="display:none;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @else
                            <div class="w-8 h-8 bg-sky-100 text-sky-600 rounded-full flex items-center justify-center text-xs font-bold border-2 border-white shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="text-sm font-medium text-gray-700 hidden sm:inline">{{ Auth::user()->name }}</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>