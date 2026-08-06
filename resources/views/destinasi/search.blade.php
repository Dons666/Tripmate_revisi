<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Destinasi - TripMate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

    <!-- Navbar -->
    @include('layouts.navigation')

   <!-- Hero -->
<div class="bg-gradient-to-r from-sky-600 via-sky-500 to-cyan-500">

    <div class="max-w-7xl mx-auto px-6 py-14 text-center">

        <h1 class="text-4xl md:text-5xl font-bold text-white mt-3">
            Cari Destinasi
        </h1>

        <p class="text-sky-100 mt-4 max-w-2xl mx-auto">
            Temukan destinasi wisata, kuliner, dan penginapan sesuai kebutuhan perjalanan Anda.
        </p>

    </div>

</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">

    <!-- Search Card -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 mb-10">

        <form action="{{ route('destinasi.search') }}" method="GET">

            <!-- Keyword -->
            <div class="mb-6">

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Kata Kunci
                </label>

                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="Cari wisata, kuliner, atau penginapan..."
                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-sky-500 text-base">

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">

                <!-- Kota -->

                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Kota
                    </label>

                    <select
                        name="kota"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:ring-2 focus:ring-sky-500">

                        <option value="">Semua Kota</option>

                        @foreach($kotas as $kota)

                        <option
                            value="{{ $kota->kota }}"
                            {{ request('kota')==$kota->kota ? 'selected':'' }}>

                            {{ $kota->kota }}

                        </option>

                        @endforeach

                    </select>

                </div>


                <!-- Kategori -->

<div>

    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Kategori
    </label>

    <select
        name="kategori"
        class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:ring-2 focus:ring-sky-500">

        <option value="">Semua Kategori</option>

        @if(isset($kategoriWisata) && count($kategoriWisata) > 0)
            <optgroup label="🌴 Wisata">
                @foreach($kategoriWisata as $kat)
                    <option
                        value="{{ $kat->nama_kategori }}"
                        {{ request('kategori') == $kat->nama_kategori ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </optgroup>
        @endif

        @if(isset($kategoriKuliner) && count($kategoriKuliner) > 0)
            <optgroup label="🍜 Wisata Kuliner">
                @foreach($kategoriKuliner as $kat)
                    <option
                        value="{{ $kat->nama_kategori }}"
                        {{ request('kategori') == $kat->nama_kategori ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </optgroup>
        @endif

        @if(isset($kategoriPenginapan) && count($kategoriPenginapan) > 0)
            <optgroup label="🏨 Penginapan">
                @foreach($kategoriPenginapan as $kat)
                    <option
                        value="{{ $kat->nama_kategori }}"
                        {{ request('kategori') == $kat->nama_kategori ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </optgroup>
        @endif

    </select>

</div>

                <!-- Budget -->

                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Budget
                    </label>

                    <select
                        name="budget"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:ring-2 focus:ring-sky-500">

                        <option value="">Semua Budget</option>

                        <option value="Gratis">Gratis</option>
                        <option value="Murah">Murah</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Mahal">Mahal</option>

                    </select>

                </div>

                <!-- Hidden Gem -->

                <div class="flex items-end">

                    <label class="flex items-center gap-3 bg-gray-50 rounded-2xl px-4 py-3 w-full border border-gray-200">

                        <input
                            type="checkbox"
                            name="hidden_gem"
                            value="1"
                            {{ request('hidden_gem') ? 'checked' : '' }}
                            class="rounded text-sky-600">

                        <span class="font-medium text-gray-700">
                            💎 Hidden Gem
                        </span>

                    </label>

                </div>

                <!-- Button -->

                <div class="flex items-end">

                    <button
                        type="submit"
                        class="w-full bg-sky-600 hover:bg-sky-700 transition text-white font-bold rounded-2xl py-3.5 shadow-lg">

                       Cari Destinasi

                    </button>

                </div>

            </div>

        </form>

    </div>

    <!-- Header Hasil -->

    <div class="flex justify-between items-center mb-6">

        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Hasil Pencarian

            </h2>

            <p class="text-gray-500 mt-1">

                Daftar destinasi sesuai kata kunci dan filter yang dipilih.

            </p>

        </div>

        <span class="bg-sky-100 text-sky-700 px-5 py-2 rounded-full font-semibold">

            {{ $destinasis->total() }} Destinasi

        </span>

    </div>

        <!-- Hasil Pencarian -->
        <h2 class="text-xl font-bold text-gray-800 mb-4">Hasil Pencarian ({{ $destinasis->total() }} ditemukan)</h2>
        
        @if($destinasis->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($destinasis as $destinasi)
                <a href="{{ route('destinasi.show', $destinasi->id) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group block">
                    
                    <!-- Bagian Gambar dengan Logika IF -->
                    <div class="h-48 w-full bg-gray-200 relative overflow-hidden">
                        @if($destinasi->gambar)
                            <img src="{{ $destinasi->gambar }}" alt="{{ $destinasi->nama_destinasi }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        @if($destinasi->hidden_gem)
                        <span class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full">Hidden Gem 💎</span>
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
                        <div class="flex justify-between items-center">
                            @if($destinasi->harga == 0)

    <span class="text-green-600 font-bold">
        Gratis
    </span>

@else

    <span class="text-sky-600 font-bold">
        Rp {{ number_format($destinasi->harga, 0, ',', '.') }}
    </span>

@endif
                            <span class="text-sm text-sky-600 hover:underline font-medium">Detail →</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $destinasis->links() }}
        </div>

        @else
        <div class="text-center py-16 text-gray-400">
            <p class="text-5xl mb-4">🔍</p>
            <p class="font-bold text-xl text-gray-600">Tidak ada hasil ditemukan</p>
            <p class="text-sm mt-2">Coba ubah filter atau kata kunci pencarian Anda.</p>
        </div>
        @endif

    </div>

</body>
</html>