<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripMate - Rekomendasi Perjalanan Wisata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Sembunyikan scrollbar untuk efek horizontal scroll yang bersih */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Navbar -->
    @include('layouts.navigation')

    <!-- Hero Section & Search Box -->
<section class="relative h-[650px] overflow-hidden">

    <div id="hero-slider" class="absolute inset-0">

        <img src="{{ asset('images/gedung-sate.jpg') }}"
             class="hero-slide absolute inset-0 w-full h-full object-cover">

        <img src="{{ asset('images/monas-jkt.jpg') }}"
             class="hero-slide absolute inset-0 w-full h-full object-cover hidden">

        <img src="{{ asset('images/kawah-putih.jpg') }}"
             class="hero-slide absolute inset-0 w-full h-full object-cover hidden">

        <img src="{{ asset('images/city_lights.jpg') }}"
             class="hero-slide absolute inset-0 w-full h-full object-cover hidden">

    </div>

    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative z-10 max-w-7xl mx-auto h-full px-8">

    <div class="flex items-center h-full">

        <div class="max-w-2xl">


            <h1 class="text-5xl md:text-7xl font-extrabold text-white leading-tight">
                Temukan
                <br>
                Petualangan
                <br>
                Terbaikmu
            </h1>

            <p class="mt-6 text-lg md:text-xl text-white/90 leading-relaxed">
                Jelajahi wisata, kuliner, dan penginapan sesuai seleramu
                dengan rekomendasi dari TripMate.
            </p>

  <div class="mt-10">

    <form action="{{ route('destinasi.search') }}" method="GET">

        <div class="bg-white/20 backdrop-blur-2xl rounded-3xl p-3 shadow-2xl max-w-3xl border border-white/30">
            <div class="flex items-center gap-3">
                <div class="pl-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    name="keyword"
                    placeholder="Cari destinasi impianmu..."
                    class="flex-1 border-0 focus:ring-0 text-white placeholder-white/70 text-lg bg-transparent">
                <button
                    type="submit"
                    class="bg-white text-sky-600 hover:bg-sky-50 px-8 py-4 rounded-2xl font-bold transition shadow-lg">
                    Cari
                </button>
            </div>
        </div>

    </form>

    <!-- Quick Tags -->
    <div class="flex flex-wrap gap-3 mt-5">
        @if(isset($kategoriWisata))
            @foreach($kategoriWisata->take(3) as $kat)
                <a href="{{ route('destinasi.search', ['kategori' => $kat->nama_kategori]) }}"
                    class="bg-white/20 backdrop-blur text-white px-4 py-2 rounded-full text-sm hover:bg-white/30 transition">
                    {{ $kat->nama_kategori }}
                </a>
            @endforeach
        @endif
        @if(isset($kategoriKuliner) && $kategoriKuliner->first())
            <a href="{{ route('destinasi.search', ['kategori' => $kategoriKuliner->first()->nama_kategori]) }}"
                class="bg-white/20 backdrop-blur text-white px-4 py-2 rounded-full text-sm hover:bg-white/30 transition">
                Kuliner
            </a>
        @endif
        @if(isset($kategoriPenginapan) && $kategoriPenginapan->first())
            <a href="{{ route('destinasi.search', ['kategori' => $kategoriPenginapan->first()->nama_kategori]) }}"
                class="bg-white/20 backdrop-blur text-white px-4 py-2 rounded-full text-sm hover:bg-white/30 transition">
                Penginapan
            </a>
        @endif
        <a href="{{ route('destinasi.search', ['hidden_gem' => 1]) }}"
            class="bg-white/20 backdrop-blur text-white px-4 py-2 rounded-full text-sm hover:bg-white/30 transition">
            Hidden Gems
        </a>
    </div>

</div>
            </div>

        </div>

    </div>

</div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const slides = document.querySelectorAll('.hero-slide');
    let current = 0;

    setInterval(() => {

        slides[current].classList.add('hidden');

        current = (current + 1) % slides.length;

        slides[current].classList.remove('hidden');

    }, 5000);

});
</script>
    <!-- BANNER: RUTE CERDAS DIJKSTRA -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="bg-gradient-to-r from-sky-600 to-indigo-600 rounded-3xl p-8 md:p-10 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute -top-16 -right-16 w-64 h-64 bg-white opacity-10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest backdrop-blur-sm border border-white/10">Fitur Baru</span>
                <h2 class="text-3xl md:text-4xl font-black mt-3 mb-2 text-white">Rute Cerdas Dijkstra</h2>
                <p class="text-sky-100 max-w-xl text-sm md:text-base">Temukan rute perjalanan terpendek antar destinasi wisata untuk menghemat waktu dan biaya transportasi Anda.</p>
            </div>
            
            <div class="relative z-10 shrink-0">
                <a href="{{ route('rute.dijkstra') }}" class="inline-flex items-center justify-center bg-white text-sky-700 font-extrabold px-6 py-4 rounded-xl shadow-lg hover:bg-sky-50 hover:scale-105 transition-all duration-300 gap-2">
                    <span>Coba Sekarang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </section>    <!-- =================================---------------------------------------- -->
    <!-- BAGIAN: 3 TEMPAT TERBAIK (BERDASARKAN RUMUS BAYESIAN AVERAGE)           -->
    <!-- Rumus: (C * m + S * r) / (C + S)                                         -->
    <!-- =================================---------------------------------------- -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-bold mb-2">
                    🏆 Pemeringkatan Bayesian Average
                </div>
                <h2 class="text-3xl font-bold text-slate-900">
                    3 Tempat Terbaik
                </h2>
                <p class="text-slate-500 mt-1 text-sm">
                    Destinasi teratas dengan bobot rating paling objektif berdasarkan rumus statistik Bayesian.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($top3Bayesian as $index => $destinasi)
                <div class="relative bg-white rounded-2xl shadow-sm border border-amber-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <!-- Badge Juara 1, 2, 3 -->
                    <div class="absolute top-3 left-3 z-10 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-extrabold px-3 py-1 rounded-full shadow text-xs flex items-center gap-1">
                        #{{ $index + 1 }} Terbaik
                    </div>

                    <div class="h-48 w-full bg-gray-100 relative overflow-hidden">
                        @if($destinasi->gambar)
                            <img src="{{ $destinasi->gambar }}" alt="{{ $destinasi->nama_destinasi }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400 text-xs">
                                Tidak ada gambar
                            </div>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-900 text-lg leading-snug">{{ Str::limit($destinasi->nama_destinasi, 24) }}</h3>
                            <span class="bg-amber-50 text-amber-700 font-extrabold text-xs px-2.5 py-1 rounded-lg border border-amber-200">
                                ⭐ {{ number_format($destinasi->rating_destinasi, 2) }}
                            </span>
                        </div>
                        <p class="text-gray-500 text-xs mb-3">📍 {{ $destinasi->kota }} · {{ $destinasi->kategori }}</p>
                        
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <span class="text-xs text-gray-500">
                                {{ $destinasi->ratings_count ?? $destinasi->ratings()->count() }} Ulasan Pengunjung
                            </span>
                            <a href="{{ route('destinasi.show', $destinasi->id) }}" class="text-sm font-semibold text-sky-600 hover:text-sky-800 hover:underline">Detail →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- =================================---------------------------------------- -->
    <!-- AKHIR BAGIAN 3 TEMPAT TERBAIK (RUMUS BAYESIAN)                            -->
    <!-- =================================---------------------------------------- -->

    <!-- BAGIAN BARU: Rekomendasi untuk Anda (Horizontal Scroll) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-2">

    <div>

        <h2 class="text-3xl font-bold text-slate-900">
            Rekomendasi untuk Anda 
        </h2>

        <p class="text-slate-500 mt-1">
            Destinasi pilihan yang cocok dengan preferensi Anda.
        </p>

    </div>

    <a
        href="{{ route('recommendations.index') }}"
        class="text-sky-600 font-semibold hover:text-sky-700">

        Lihat Semua →

    </a>

</div>
      
        <div class="flex space-x-5 overflow-x-auto pb-4 scrollbar-hide snap-x">
            @foreach($recommendations as $destinasi)
                <div class="flex-shrink-0 w-72 snap-start bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-2 transition-all duration-300 ease-out group active:scale-[0.98]">
                    <!-- Bagian Gambar -->
                    <div class="h-40 w-full bg-gray-200 relative overflow-hidden">
                        @if($destinasi->gambar)
                            <img src="{{ $destinasi->gambar }}" alt="{{ $destinasi->nama_destinasi }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            
                            <div class="w-full h-full items-center justify-center text-gray-400 group-hover:scale-110 transition-transform duration-500" style="display: none;">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs mt-2 block font-medium text-gray-400">Gambar Gagal Dimuat</span>
                                </div>
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 group-hover:scale-110 transition-transform duration-500">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs mt-2 block font-medium text-gray-400">Tidak Ada Gambar</span>
                                </div>
                            </div>
                        @endif
                        
                        @if($destinasi->hidden_gem)
                        <span class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full z-10">Hidden Gem 💎</span>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-900 leading-tight">{{ Str::limit($destinasi->nama_destinasi, 20) }}</h3>
                            <span class="text-yellow-500 text-sm font-semibold flex items-center gap-1 flex-shrink-0">
                                @if(($destinasi->ratings_count ?? 0) > 0)
                                    ⭐ {{ number_format($destinasi->average_rating, 1) }}
                                @else
                                    Belum ada ulasan
                                @endif
                            </span>
                        </div>
                        <p class="text-gray-500 text-sm mb-4">📍 {{ $destinasi->kota }}</p>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                           @if($destinasi->harga == 0)

    <span class="text-green-600 font-bold">
        Gratis
    </span>

@else

    <span class="text-sky-600 font-bold">
        Rp {{ number_format($destinasi->harga, 0, ',', '.') }}
    </span>

@endif
                            <a href="{{ route('destinasi.show', $destinasi->id) }}" class="text-sm text-sky-500 hover:text-sky-700 font-medium hover:underline transition">Detail →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>






</section>

    <!-- BAGIAN BARU: Penyedia Layanan Travel & Rental Armada -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-slate-100">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-sky-50 text-sky-700 rounded-full text-xs font-extrabold border border-sky-200 mb-2">
                    <span>🚌 Kemitraan Travel Terpercaya</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Penyedia Layanan Travel & Rental Armada
                </h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Sewa mobil, mini bus, dan layanan travel antarkota dengan tarif transparan dan armada siap jalan.
                </p>
            </div>
            <a href="{{ route('penyedia-travel.index') }}" class="px-5 py-2.5 bg-slate-900 hover:bg-sky-600 text-white text-xs font-extrabold rounded-2xl transition-colors duration-200 shadow-md flex items-center justify-center gap-1.5 shrink-0 w-full sm:w-auto">
                <span>Lihat Semua Travel</span>
                <span>→</span>
            </a>
        </div>

        @if(isset($penyediaTravels) && count($penyediaTravels) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($penyediaTravels as $travel)
                    @php
                        $providerUser = \App\Models\User::where('email', $travel->email)->first();
                        $targetPackage = $providerUser ? \App\Models\Travel::where('user_id', $providerUser->id)->first() : null;
                        if (!$targetPackage) {
                            $targetPackage = \App\Models\Travel::where('nama_travel', 'like', "%{$travel->nama_travel}%")->first();
                        }
                        $detailUrl = $targetPackage ? route('penyedia-travel.show', $targetPackage->id) : route('penyedia-travel.index', ['search' => $travel->nama_travel]);
                    @endphp
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                        <div class="space-y-4">
                            <!-- Top Info Header -->
                            <div class="flex items-start justify-between gap-3 pb-4 border-b border-slate-100">
                                <a href="{{ $detailUrl }}" class="flex items-center gap-3 min-w-0 group/link">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 text-white flex items-center justify-center font-black text-xl shadow-md shrink-0 group-hover/link:scale-105 transition-transform">
                                        🚌
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-extrabold text-slate-900 text-base leading-snug group-hover/link:text-sky-600 transition-colors truncate">
                                            {{ $travel->nama_travel }} &rarr;
                                        </h3>
                                        <p class="text-slate-500 text-xs font-medium truncate mt-0.5">
                                            📍 {{ $travel->kota_asal_travel ?? 'Kota Terdaftar' }}
                                        </p>
                                    </div>
                                </a>
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-lg border border-emerald-200 shrink-0">
                                    ✓ Verified
                                </span>
                            </div>

                            <!-- Vehicle & Price Showcase -->
                            <div>
                                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-2">Daftar Armada Kendaraan</span>
                                @if(!empty($travel->jenis_kendaraan))
                                    @php
                                        $armadaList = array_values(array_filter(array_map('trim', explode(',', $travel->jenis_kendaraan))));
                                        $fotosArray = $travel->fotos_array;
                                    @endphp
                                    <div class="space-y-2">
                                        @foreach(array_slice($armadaList, 0, 2) as $index => $armada)
                                            @php
                                                $armadaPhoto = $fotosArray[$index] ?? null;
                                                $rawItem = $armada;
                                                $extractedPrice = null;
                                                if (preg_match('/^(.*?)\s*\((?:Rp\s*)?([\d\.]+)\)$/i', $rawItem, $priceMatches)) {
                                                    $rawItem = trim($priceMatches[1]);
                                                    $cleanNum = str_replace('.', '', $priceMatches[2]);
                                                    if (is_numeric($cleanNum) && (float)$cleanNum > 0) {
                                                        $extractedPrice = 'Rp ' . number_format((float)$cleanNum, 0, ',', '.');
                                                    }
                                                }
                                                if (!$extractedPrice && ($travel->harga ?? 0) > 0) {
                                                    $extractedPrice = 'Rp ' . number_format($travel->harga, 0, ',', '.');
                                                }
                                                $extractedSeats = null;
                                                if (preg_match('/^(.*?)\s*\((?:(\d+)\s*(?:Kursi|Orang|Pax|Seat))\)$/i', $rawItem, $seatMatches)) {
                                                    $rawItem = trim($seatMatches[1]);
                                                    $extractedSeats = $seatMatches[2] . ' Kursi';
                                                }
                                                $extractedQty = null;
                                                if (preg_match('/^(\d+\s*Unit)\s+(.*)$/i', $rawItem, $qtyMatches)) {
                                                    $extractedQty = $qtyMatches[1];
                                                    $rawItem = trim($qtyMatches[2]);
                                                }
                                            @endphp
                                            <a href="{{ $detailUrl }}" class="p-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white flex items-center justify-between gap-2 shadow-sm transition block">
                                                <div class="flex items-center gap-2.5 min-w-0">
                                                    @if($armadaPhoto)
                                                        <img src="{{ asset('storage/' . $armadaPhoto) }}" alt="{{ $rawItem }}" class="w-9 h-9 object-cover rounded-xl border border-slate-700 shrink-0">
                                                    @else
                                                        <div class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center text-sm shrink-0 border border-slate-700">
                                                            🚐
                                                        </div>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <span class="text-xs font-bold truncate block">
                                                            @if($extractedQty)
                                                                <span class="text-sky-400 font-extrabold">{{ $extractedQty }}</span>
                                                            @endif
                                                            {{ $rawItem }}
                                                        </span>
                                                        @if($extractedSeats)
                                                            <span class="text-[10px] text-sky-300 font-semibold block">
                                                                👥 {{ $extractedSeats }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($extractedPrice)
                                                    <span class="px-2 py-1 bg-emerald-500 text-white rounded-lg text-[10px] font-black shrink-0">
                                                        {{ $extractedPrice }} / hari
                                                    </span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Armada siap dihubungi</span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer WhatsApp CTA & Detail Link -->
                        <div class="pt-4 border-t border-slate-100 mt-5 flex items-center justify-between gap-2">
                            <a href="{{ $detailUrl }}" class="px-3.5 py-2 bg-sky-50 hover:bg-sky-100 text-sky-700 text-xs font-extrabold rounded-xl transition border border-sky-200 shadow-sm flex items-center gap-1 shrink-0">
                                <span>👁️ Detail Travel</span>
                            </a>
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $travel->nomor_hp_pemilik_travel ?? '');
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                                $waUrl = !empty($cleanPhone) ? "https://wa.me/{$cleanPhone}?text=" . urlencode("Halo {$travel->nama_travel}, saya menemukan travel Anda di TripMate dan ingin menanyakan armada & reservasi.") : '#';
                            @endphp
                            @if(!empty($cleanPhone))
                                <a href="{{ $waUrl }}" target="_blank" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-extrabold rounded-xl transition shadow-sm flex items-center gap-1 shrink-0">
                                    <span>💬 Hubungi WA</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 bg-slate-50 rounded-3xl border border-slate-200">
                <span class="text-4xl block mb-2">🚌</span>
                <p class="text-xs text-slate-500 font-bold">Layanan travel terverifikasi siap melayani perjalanan Anda.</p>
                <a href="{{ route('penyedia-travel.index') }}" class="inline-block mt-3 px-5 py-2.5 bg-sky-600 text-white rounded-2xl text-xs font-bold shadow">
                    Lihat Katalog Travel
                </a>
            </div>
        @endif
    </section>

    <!-- Section: Paket Travel Tersedia -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Paket Travel Menarik 🧳
                </h2>
                <p class="text-sm text-slate-500 mt-2 font-medium">Pesan paket liburan lengkap dari mitra terpercaya</p>
            </div>
            <a href="{{ route('penyedia-travel.index') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-bold text-sky-600 hover:text-sky-700 transition">
                Lihat Semua &rarr;
            </a>
        </div>

        @if(isset($paketTravels) && count($paketTravels) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($paketTravels as $paket)
                    <a href="{{ route('penyedia-travel.show', $paket) }}" class="block bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <div class="relative h-48 w-full rounded-2xl overflow-hidden mb-4 bg-slate-100">
                            @if($paket->gambar)
                                <img src="{{ asset('storage/' . $paket->gambar) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Paket Travel">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <span class="text-4xl mb-2">🏖️</span>
                                    <span class="text-xs font-bold uppercase tracking-widest">TripMate</span>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-xl text-xs font-black text-sky-700 shadow-sm">
                                Rp {{ number_format($paket->harga_paket, 0, ',', '.') }} / Pax
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-sky-600 transition-colors line-clamp-1">
                                    {{ $paket->nama_travel }}
                                </h3>
                                <p class="text-xs font-medium text-slate-500 mt-1 line-clamp-1">By: {{ $paket->user->name ?? 'Mitra Travel' }}</p>
                            </div>
                            
                            <div class="flex items-center gap-2 text-[11px] font-bold text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                <span>🚐</span>
                                <span class="truncate">{{ $paket->armada->nama_kendaraan ?? 'Kendaraan Nyaman' }}</span>
                                <span class="text-slate-300 mx-1">•</span>
                                <span class="text-sky-600">{{ $paket->armada->kapasitas_kursi ?? '-' }} Kursi</span>
                            </div>

                            @if($paket->destinasis->count() > 0)
                                <div class="text-[11px] font-medium text-slate-500 line-clamp-1">
                                    <span class="font-bold text-slate-700">Rute:</span> 
                                    {{ $paket->destinasis->pluck('nama_destinasi')->join(', ') }}
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 bg-slate-50 rounded-3xl border border-slate-200">
                <p class="text-xs text-slate-500 font-bold">Belum ada paket travel yang tersedia.</p>
            </div>
        @endif
    </section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="rounded-3xl bg-gradient-to-r from-sky-50 to-cyan-50 overflow-hidden shadow-lg border border-sky-100">

        <div class="grid md:grid-cols-3 items-center">

            <div class="p-8 flex justify-center relative">
                <!-- Slider Container -->
                <div class="relative w-52 h-52 rounded-2xl overflow-hidden shadow-md">
                    
                    <!-- Slides -->
                    <img src="{{ asset('images/plan.jpg') }}" alt="Plan" class="slide-img absolute inset-0 w-full h-full object-cover opacity-100 transition-all duration-700 ease-in-out">
                    <img src="{{ asset('images/solo-traveling.jpg') }}" alt="Travel 1" class="slide-img absolute inset-0 w-full h-full object-cover opacity-0 transition-all duration-700 ease-in-out">
                    <img src="{{ asset('images/solo-travelling.jpg') }}" alt="Travel 2" class="slide-img absolute inset-0 w-full h-full object-cover opacity-0 transition-all duration-700 ease-in-out">
                    <img src="{{ asset('images/manfaat-traveling.jpg') }}" alt="Travel 3" class="slide-img absolute inset-0 w-full h-full object-cover opacity-0 transition-all duration-700 ease-in-out">

                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none rounded-2xl"></div>

                    <!-- Dots Indicator -->
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
                        <span class="slide-dot w-2 h-2 rounded-full bg-white/90 shadow transition-all duration-300"></span>
                        <span class="slide-dot w-2 h-2 rounded-full bg-white/40 shadow transition-all duration-300"></span>
                        <span class="slide-dot w-2 h-2 rounded-full bg-white/40 shadow transition-all duration-300"></span>
                        <span class="slide-dot w-2 h-2 rounded-full bg-white/40 shadow transition-all duration-300"></span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 p-8">

                <h2 class="text-3xl font-bold text-slate-900">
                    Rencanakan Perjalanan Impianmu
                </h2>

                <p class="text-slate-600 mt-4 leading-relaxed max-w-2xl">
                    Kelola itinerary, budget perjalanan, pengeluaran, dan destinasi favorit dalam satu aplikasi. TripMate membantu perjalananmu menjadi lebih terencana dan nyaman.
                </p>

                <a href="{{ route('travel-plans.index') }}" class="inline-flex items-center gap-2 mt-8 bg-sky-600 hover:bg-sky-700 text-white px-7 py-3 rounded-2xl font-semibold transition active:scale-95 shadow-lg">
                    Mulai Rencana
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

            </div>

        </div>

    </div>

</section>



<!-- Script Slider -->
<script>
(function() {
    const images = document.querySelectorAll('.slide-img');
    const dots = document.querySelectorAll('.slide-dot');
    let current = 0;
    let interval;

    function goTo(index) {
        // Fade out semua gambar
        images.forEach((img, i) => {
            if (i === index) {
                img.style.opacity = '1';
                img.style.transform = 'scale(1)';
            } else {
                img.style.opacity = '0';
                img.style.transform = 'scale(1.08)';
            }
        });

        // Update dots
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.remove('bg-white/40');
                dot.classList.add('bg-white/90');
                dot.style.width = '1.25rem';
            } else {
                dot.classList.remove('bg-white/90');
                dot.classList.add('bg-white/40');
                dot.style.width = '0.5rem';
            }
        });

        current = index;
    }

    function next() {
        goTo((current + 1) % images.length);
    }

    function startAuto() {
        interval = setInterval(next, 3500);
    }

    function stopAuto() {
        clearInterval(interval);
    }

    // Klik dot untuk pindah slide
    dots.forEach((dot, i) => {
        dot.style.cursor = 'pointer';
        dot.addEventListener('click', () => {
            stopAuto();
            goTo(i);
            startAuto();
        });
    });

    // Pause saat hover
    const container = images[0]?.parentElement;
    if (container) {
        container.addEventListener('mouseenter', stopAuto);
        container.addEventListener('mouseleave', startAuto);
    }

    // Mulai
    goTo(0);
    startAuto();
})();
</script>



    <!-- Layout Grid: Sidebar & Destinasi Populer -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
<!-- SIDEBAR FILTER -->
<aside class="hidden lg:block lg:col-span-1">

    <div class="sticky top-20 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Header -->
        <div class="px-5 py-5 bg-sky-50 border-b">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">Jelajahi Destinasi</h2>
            <p class="text-sm text-slate-500 mt-1">Temukan destinasi sesuai kebutuhan Anda.</p>
        </div>

        <form action="{{ route('home') }}" method="GET" class="divide-y divide-gray-100">

            <!-- KOTA -->
            <details class="group" open>
                <summary class="cursor-pointer list-none flex justify-between items-center px-5 py-4 font-semibold text-gray-800">
                    <span>Wilayah</span>
                    <svg class="w-4 h-4 group-open:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="px-5 pb-4 space-y-2">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="kota[]" value="Bandung" {{ in_array('Bandung', request('kota', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-sky-600">
                        Bandung
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="kota[]" value="Jakarta" {{ in_array('Jakarta', request('kota', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-sky-600">
                        Jakarta
                    </label>
                </div>
            </details>

            <!-- KATEGORI -->
            <details class="group" open>
                <summary class="cursor-pointer list-none flex justify-between items-center px-5 py-4 font-semibold text-gray-800">
                    <span>Kategori</span>
                    <svg class="w-4 h-4 group-open:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="px-5 pb-4 space-y-2 max-h-72 overflow-y-auto">
                    @foreach($kategoris as $kategori)
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="kategori[]" value="{{ $kategori->nama_kategori }}" {{ in_array($kategori->nama_kategori, request('kategori', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                            <span>{{ $kategori->nama_kategori }}</span>
                        </label>
                    @endforeach
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="kategori[]" value="Penginapan" {{ in_array('Penginapan', request('kategori', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                        <span>Penginapan</span>
                    </label>
                </div>
            </details>

            <!-- BUDGET -->
            <details class="group">
                <summary class="cursor-pointer list-none flex justify-between items-center px-5 py-4 font-semibold text-gray-800">
                    <span>Budget</span>
                    <svg class="w-4 h-4 group-open:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="px-5 pb-4 space-y-3">
                    <label class="flex items-center gap-3">
                        <input type="radio" name="budget" value="Gratis" {{ request('budget') == 'Gratis' ? 'checked' : '' }} class="text-sky-600">
                        <span>Gratis</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="radio" name="budget" value="Murah" {{ request('budget') == 'Murah' ? 'checked' : '' }} class="text-sky-600">
                        <span>Murah</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="radio" name="budget" value="Sedang" {{ request('budget') == 'Sedang' ? 'checked' : '' }} class="text-sky-600">
                        <span>Sedang</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="radio" name="budget" value="Mahal" {{ request('budget') == 'Mahal' ? 'checked' : '' }} class="text-sky-600">
                        <span>Mahal</span>
                    </label>
                </div>
            </details>

            <!-- HIDDEN GEM -->
            <div class="px-5 py-5">
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="hidden_gem" value="1" {{ request()->has('hidden_gem') ? 'checked' : '' }} class="rounded border-gray-300 text-sky-600">
                    <span class="font-medium">💎 Hidden Gem</span>
                </label>
            </div>

            <!-- BUTTON -->
            <div class="p-5">
                <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-semibold py-3 rounded-xl transition">
                    Terapkan Filter
                </button>
            </div>

        </form>

    </div>

</aside>
            <!-- KONTEN UTAMA (Destinasi) -->
            <div class="lg:col-span-3">
                
                <!-- Dropdown Kategori -->
                <div class="lg:hidden mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kategori</label>
                    <select onchange="if (this.value) window.location.href=this.value" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-400 font-medium">
                        <option value="{{ route('destinasi.search') }}">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ route('destinasi.search', ['kategori' => $kategori->nama_kategori]) }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Jelajahi Destinasi</h2>
                    <a href="{{ route('destinasi.search') }}" class="text-sky-500 hover:text-sky-700 font-medium text-sm transition hidden sm:block">     Lihat Semua Destinasi →</a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($destinasiPopuler as $destinasi)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-2 transition-all duration-300 ease-out group active:scale-[0.98]">
                            <!-- Bagian Gambar dengan Logika IF -->
                            <div class="h-48 w-full bg-gray-200 relative overflow-hidden">
                                @if($destinasi->gambar)
                                    <img src="{{ $destinasi->gambar }}" alt="{{ $destinasi->nama_destinasi }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    
                                    <div class="w-full h-full items-center justify-center text-gray-400 group-hover:scale-110 transition-transform duration-500" style="display: none;">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs mt-2 block font-medium text-gray-400">Gambar Gagal Dimuat</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 group-hover:scale-110 transition-transform duration-500">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs mt-2 block font-medium text-gray-400">Tidak Ada Gambar</span>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($destinasi->hidden_gem)
                                <span class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full z-10">Hidden Gem 💎</span>
                                @endif
                            </div>

                            <div class="p-5">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-gray-900 leading-tight">{{ Str::limit($destinasi->nama_destinasi, 25) }}</h3>
                                    <span class="text-yellow-500 text-sm font-semibold flex items-center gap-1">
                                        @if(($destinasi->ratings_count ?? 0) > 0)
                                            ⭐ {{ number_format($destinasi->average_rating, 1) }}
                                        @else
                                            Belum ada ulasan
                                        @endif
                                    </span>
                                </div>
                                <p class="text-gray-500 text-sm mb-4">📍 {{ $destinasi->kota }} • {{ $destinasi->kategori }}</p>
                                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                    @if($destinasi->harga == 0)

    <span class="text-green-600 font-bold">
        Gratis
    </span>

@else

    <span class="text-sky-600 font-bold">
        Rp {{ number_format($destinasi->harga, 0, ',', '.') }}
    </span>

@endif
                                    <a href="{{ route('destinasi.show', $destinasi->id) }}" class="text-sm text-sky-500 hover:text-sky-700 font-medium hover:underline transition">Detail →</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

 <!-- Footer TripMate - Selaras dengan tema Home -->
<footer class="bg-white border-t border-gray-100 mt-20">
    <div class="max-w-7xl mx-auto px-6 py-16">

        <div class="grid md:grid-cols-12 gap-12">

            <!-- Logo & Deskripsi -->
            <div class="md:col-span-5">
                <h2 class="text-4xl font-bold tracking-tight text-slate-900">
                    <span class="text-sky-600">TripMate</span>
                </h2>
                
                <p class="mt-5 text-gray-500 leading-relaxed text-[15px]">
                    Temukan destinasi wisata, kuliner, dan penginapan terbaik 
                    yang sesuai dengan gaya dan preferensi perjalanan Anda.
                </p>

               

                <!-- Social Media -->
                <div class="mt-10">
                    <p class="text-gray-500 text-sm mb-4">Ikuti Kami</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-9 h-9 bg-sky-50 hover:bg-sky-500 transition-all duration-300 rounded-xl flex items-center justify-center text-sky-600 hover:text-white">
                            <!-- Instagram -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.074 4.771 1.588 4.859 4.84.078 1.265.09 1.645.09 4.849 0 3.204-.012 3.584-.07 4.85-.074 3.252-1.588 4.771-4.84 4.859-1.265.078-1.645.09-4.849.09-3.204 0-3.584-.012-4.85-.07-3.252-.074-4.771-1.588-4.859-4.84-.078-1.265-.09-1.645-.09-4.849 0-3.204.012-3.584.07-4.85.074-3.252 1.588-4.771 4.84-4.859 1.265-.078 1.645-.09 4.849-.09z"/>
                                <path d="M12 5.838c-3.403 0-6.162 2.759-6.162 6.162 0 3.403 2.759 6.162 6.162 6.162 3.403 0 6.162-2.759 6.162-6.162 0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4 0-2.209 1.791-4 4-4 2.209 0 4 1.791 4 4 0 2.209-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.441 0 .796.645 1.441 1.441 1.441.796 0 1.441-.645 1.441-1.441 0-.796-.645-1.441-1.441-1.441z"/>
                            </svg>
                        </a>
                        
                        <a href="#" class="w-9 h-9 bg-sky-50 hover:bg-sky-500 transition-all duration-300 rounded-xl flex items-center justify-center text-sky-600 hover:text-white">
                            <!-- Twitter/X -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25l-.01 3.75h-3.75v3.75h3.75v3.75h-3.75v3.75h-3.75V13.5H6.244v-3.75H2.494V6H6.244V2.25h3.75v3.75h3.75V2.25z"/>
                            </svg>
                        </a>
                        
                        <a href="#" class="w-9 h-9 bg-sky-50 hover:bg-sky-500 transition-all duration-300 rounded-xl flex items-center justify-center text-sky-600 hover:text-white">
                            <!-- Facebook -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.9-4.27 4.25 0 .33.04.66.11.97-3.55-.18-6.7-1.88-8.81-4.47-.37.63-.58 1.37-.58 2.15 0 1.48.75 2.79 1.9 3.56-.7-.02-1.36-.21-1.94-.53v.05c0 2.06 1.46 3.78 3.4 4.17-.36.1-.73.15-1.11.15-.27 0-.53-.03-.79-.08.53 1.66 2.07 2.87 3.9 2.9-1.46 1.14-3.3 1.82-5.3 1.82-.34 0-.68-.02-1.01-.06 1.88 1.21 4.1 1.91 6.5 1.91 7.8 0 12.06-6.46 12.06-12.06 0-.18 0-.36-.01-.54.83-.6 1.55-1.35 2.12-2.2z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <div class="md:col-span-3">
                <h3 class="font-semibold text-lg mb-5 text-slate-900">
                    Jelajahi
                </h3>
                <ul class="space-y-3 text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-sky-600 transition-all duration-200">Home</a></li>
                    <li><a href="{{ route('travel-plans.index') }}" class="hover:text-sky-600 transition-all duration-200">Rencana Perjalanan</a></li>
                    <li><a href="{{ route('bookmarks.index') }}" class="hover:text-sky-600 transition-all duration-200">Bookmarks</a></li>
                    <li><a href="{{ route('preference.create') }}" class="hover:text-sky-600 transition-all duration-200">Preferensi</a></li>
                    <li><a href="{{ route('penyedia-travel.create') }}" class="text-sky-600 font-semibold hover:underline transition-all duration-200">Daftar Travel (Mitra)</a></li>
                </ul>
            </div>

            <!-- Tentang -->
            <div class="md:col-span-4">
                <h3 class="font-semibold text-lg mb-5 text-slate-900">
                    Tentang TripMate
                </h3>
                <p class="text-gray-500 leading-relaxed text-[15px]">
                    Platform perjalanan yang membantu Anda 
                    menemukan pengalaman liburan paling sesuai dengan minat dan kebutuhan Anda.
                </p>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-100 mt-16 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
                <p class="text-gray-400">
                    © {{ date('Y') }} TripMate. All rights reserved.
                </p>
                
                <div class="flex items-center gap-6 text-gray-400">
                    <a href="#" class="hover:text-sky-600 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-sky-600 transition-colors">Terms</a>
                    <a href="#" class="hover:text-sky-600 transition-colors">Contact</a>
                </div>

                <p class="text-gray-400">
                    Developed by <span class="text-sky-600 font-medium">Muhammad Diaz</span>
                </p>
            </div>
        </div>

    </div>
</footer>


</body>
</html>