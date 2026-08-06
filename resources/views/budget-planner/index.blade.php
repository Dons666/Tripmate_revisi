<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ⚡ Optimasi Budget & Rute Terpendek (Dijkstra)
        </h2>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-sm font-medium flex items-center gap-2 shadow-sm">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            <!-- HERO HEADER CARD -->
            <div class="relative overflow-hidden bg-gradient-to-r from-sky-600 via-indigo-600 to-sky-700 text-white rounded-3xl p-8 shadow-xl">
                <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative z-10 max-w-3xl">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-md mb-3 border border-white/30">
                        ✨ Fitur Pintar Tripmate
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Cari Rute & Alokasi Anggaran Wisata Terbaik</h1>
                    <p class="text-sky-100 mt-2 text-sm sm:text-base leading-relaxed">
                        Masukkan budget awal dan tujuan Anda. Algoritma **Dijkstra Nearest-Neighbor** kami akan merencanakan rute terintegrasi paling efisien tanpa melebihi batas anggaran Anda!
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- FORM CALCULATOR -->
                <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-200/80">
                    <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                        <span>🎯</span> Parameter Perjalanan
                    </h3>

                    <form id="routeForm" onsubmit="calculateRoute(event)" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Titik Awal (Start)</label>
                            <input list="destinasiList" type="text" name="start" id="startInput" placeholder="Ketik atau pilih destinasi awal..." class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Titik Akhir (Tujuan)</label>
                            <input list="destinasiList" type="text" name="end" id="endInput" placeholder="Ketik atau pilih destinasi tujuan..." class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition" required>
                        </div>

                        <datalist id="destinasiList">
                            @foreach($destinasis as $d)
                                <option value="{{ $d->nama_destinasi }}">{{ $d->kota }} — Rp {{ number_format($d->harga, 0, ',', '.') }}</option>
                            @endforeach
                        </datalist>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Batas Anggaran Maksimal (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-slate-400 font-medium text-sm">Rp</span>
                                <input type="number" name="budget" id="budgetInput" placeholder="200000" min="0" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Filter Kota (Opsional)</label>
                                <select name="kota" id="kotaInput" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                                    <option value="">Semua Kota</option>
                                    @foreach($kotas as $k)
                                        <option value="{{ $k }}">{{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Kategori (Opsional)</label>
                                <select name="kategori" id="kategoriInput" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit" class="mt-4 w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                            <span>🔍</span> Hitung Optimasi Rute & Budget
                        </button>
                    </form>
                </div>

                <!-- DISPLAY RESULTS -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- LOADING STATE -->
                    <div id="loadingState" style="display: none;" class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center shadow-sm">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-sky-600 border-t-transparent mb-4"></div>
                        <p class="text-slate-700 font-bold text-base">Sedang Menghitung Rute Optimum...</p>
                        <p class="text-xs text-slate-400 mt-1">Mengalkulasi koridor geografis Dijkstra & batas budget.</p>
                    </div>

                    <!-- EMPTY INITIAL STATE -->
                    <div id="emptyState" class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center shadow-sm">
                        <div class="w-16 h-16 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center mx-auto text-3xl mb-4">
                            🗺️
                        </div>
                        <h4 class="text-base font-bold text-slate-800">Hasil Rute Terintegrasi Akan Tampil Di Sini</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                            Isi titik awal, titik akhir, dan batas budget di sebelah kiri, lalu klik tombol **Hitung Optimasi** untuk memunculkan rekomendasi rute.
                        </p>
                    </div>

                    <!-- ERROR STATE -->
                    <div id="errorState" style="display: none;" class="bg-red-50 p-6 rounded-3xl border border-red-200 text-red-700 text-sm font-medium">
                        <p id="errorMessage" class="font-semibold"></p>
                    </div>

                    <!-- RESULTS CONTAINER -->
                    <div id="resultsContainer" style="display: none;" class="space-y-6">
                        <!-- STATS CARDS -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                                <p class="text-xs text-slate-400 font-medium">Estimasi Biaya</p>
                                <p id="resTotalCost" class="text-lg sm:text-xl font-extrabold text-sky-600 mt-0.5">Rp 0</p>
                            </div>
                            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                                <p class="text-xs text-slate-400 font-medium">Sisa Budget</p>
                                <p id="resRemainingBudget" class="text-lg sm:text-xl font-extrabold text-emerald-600 mt-0.5">Rp 0</p>
                            </div>
                            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                                <p class="text-xs text-slate-400 font-medium">Total Destinasi</p>
                                <p id="resTotalNodes" class="text-lg sm:text-xl font-extrabold text-indigo-600 mt-0.5">0 Tempat</p>
                            </div>
                        </div>

                        <!-- ROUTE WAYPOINTS LIST -->
                        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-base">Rute Perjalanan Terurut (Dijkstra)</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Urutan titik singgah paling efisien secara geografis.</p>
                                </div>
                                <span class="text-xs font-semibold px-3 py-1 bg-sky-100 text-sky-700 rounded-full">Nearest Neighbor</span>
                            </div>

                            <div id="routeTimeline" class="space-y-3 relative before:absolute before:left-5 before:top-4 before:bottom-4 before:w-0.5 before:bg-sky-200">
                                <!-- Dynamic JavaScript Content -->
                            </div>
                        </div>

                        <!-- SAVE TO TRAVEL PLAN FORM -->
                        <form action="{{ route('travel-plans.save-integrated-route') }}" method="POST" class="bg-gradient-to-br from-slate-900 to-sky-950 p-6 rounded-3xl shadow-xl text-white space-y-4">
                            @csrf
                            <input type="hidden" name="budget" id="saveBudgetInput">
                            <div id="saveHiddenDestinations"></div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-white text-base">Simpan Sebagai Rencana Perjalanan</h4>
                                    <p class="text-xs text-sky-200 mt-0.5">Jadikan rute ini sebagai Travel Plan baru di akun Anda.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-sky-200 mb-1">Nama Perjalanan</label>
                                    <input type="text" name="nama_perjalanan" id="savePlanName" placeholder="Contoh: Liburan Bandung Hemat" class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-sky-300 text-sm focus:ring-2 focus:ring-sky-400 outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-xs text-sky-200 mb-1">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white text-sm focus:ring-2 focus:ring-sky-400 outline-none" required>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                                <span>💾</span> Simpan Rute ke Rencana Perjalanan Saya
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        async function calculateRoute(e) {
            e.preventDefault();

            const start = document.getElementById('startInput').value;
            const end = document.getElementById('endInput').value;
            const budget = document.getElementById('budgetInput').value;
            const kota = document.getElementById('kotaInput').value;
            const kategori = document.getElementById('kategoriInput').value;

            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('errorState').style.display = 'none';
            document.getElementById('resultsContainer').style.display = 'none';
            document.getElementById('loadingState').style.display = 'block';

            try {
                const response = await fetch("{{ route('budget.integrated-route') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ start, end, budget, kota, kategori })
                });

                const data = await response.json();
                document.getElementById('loadingState').style.display = 'none';

                if (!response.ok || data.status === 'error') {
                    document.getElementById('errorMessage').innerText = data.message || "Gagal mengalkulasi rute.";
                    document.getElementById('errorState').style.display = 'block';
                    return;
                }

                // Render Summary
                document.getElementById('resTotalCost').innerText = 'Rp ' + Number(data.total_cost).toLocaleString('id-ID');
                document.getElementById('resRemainingBudget').innerText = 'Rp ' + Number(data.remaining_budget).toLocaleString('id-ID');
                document.getElementById('resTotalNodes').innerText = data.total_nodes + ' Tempat';

                // Render Timeline
                const timelineContainer = document.getElementById('routeTimeline');
                timelineContainer.innerHTML = '';

                const hiddenDestContainer = document.getElementById('saveHiddenDestinations');
                hiddenDestContainer.innerHTML = '';

                data.route.forEach((item, index) => {
                    // Hidden input for saving
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'destinasi_ids[]';
                    hiddenInput.value = item.id;
                    hiddenDestContainer.appendChild(hiddenInput);

                    // Timeline node
                    const isStart = index === 0;
                    const isEnd = index === data.route.length - 1;

                    let badgeColor = "bg-slate-100 text-slate-700 border-slate-200";
                    let badgeLabel = "Singgah #" + index;

                    if (isStart) {
                        badgeColor = "bg-emerald-100 text-emerald-800 border-emerald-300 font-bold";
                        badgeLabel = "🚀 TITIK AWAL";
                    } else if (isEnd) {
                        badgeColor = "bg-rose-100 text-rose-800 border-rose-300 font-bold";
                        badgeLabel = "🏁 TUJUAN AKHIR";
                    }

                    const card = document.createElement('div');
                    card.className = "relative flex items-start gap-4 p-4 rounded-2xl bg-slate-50 hover:bg-sky-50/50 border border-slate-200/60 transition z-10 ml-2";

                    const priceText = item.harga === 0 ? '<span class="text-emerald-600 font-bold">Gratis</span>' : 'Rp ' + Number(item.harga).toLocaleString('id-ID');

                    card.innerHTML = `
                        <div class="w-7 h-7 rounded-full ${isStart ? 'bg-emerald-500' : (isEnd ? 'bg-rose-500' : 'bg-sky-600')} text-white flex items-center justify-center text-xs font-bold shadow-md flex-shrink-0 mt-0.5">
                            ${index + 1}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs px-2.5 py-0.5 rounded-full border ${badgeColor}">${badgeLabel}</span>
                                <span class="text-xs text-slate-400">📍 ${item.kota} · ${item.kategori}</span>
                            </div>
                            <h5 class="font-bold text-slate-800 text-base mt-1 hover:text-sky-600 transition">
                                <a href="/destinasi/${item.id}" target="_blank">${item.nama_destinasi}</a>
                            </h5>
                        </div>
                        <div class="text-right flex-shrink-0 text-sm">
                            <p class="font-extrabold">${priceText}</p>
                        </div>
                    `;
                    timelineContainer.appendChild(card);
                });

                // Prepare save plan form hidden inputs
                document.getElementById('saveBudgetInput').value = budget;
                document.getElementById('savePlanName').value = "Perjalanan Rute " + start + " - " + end;

                document.getElementById('resultsContainer').style.display = 'block';

            } catch (err) {
                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('errorMessage').innerText = "Terjadi kesalahan jaringan atau server.";
                document.getElementById('errorState').style.display = 'block';
            }
        }
    </script>
</x-app-layout>
