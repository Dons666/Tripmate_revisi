<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripMate - Preferensi Perjalanan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-sky-50 via-white to-slate-100 min-h-screen">

<div class="max-w-7xl mx-auto px-4 py-10">

    <div class="grid lg:grid-cols-12 gap-6">

        <!-- SIDEBAR KIRI -->
     

        </div>

        <!-- KONTEN UTAMA -->
        <div class="lg:col-span-8">

            <div class="bg-white rounded-[40px] shadow-2xl border border-slate-100 p-8 md:p-12">

                <!-- HEADER -->

                <div class="text-center mb-12">

                    <p class="text-sky-600 uppercase tracking-[0.3em] font-bold">
                        TRIPMATE
                    </p>

                    <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mt-4 leading-tight">
                        Temukan Destinasi<br>
                        yang Sesuai dengan Anda
                    </h1>

                    <p class="text-slate-500 mt-5 max-w-2xl mx-auto">
                        Pilih preferensi perjalanan untuk mendapatkan rekomendasi
                        destinasi yang lebih personal dan sesuai minat Anda.
                    </p>

                </div>

                <!-- ERROR -->

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
                        <ul class="list-disc pl-5 text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('preference.store') }}" method="POST" class="space-y-8">

                    @csrf

                    <!-- KOTA TUJUAN -->

                    <section class="border rounded-3xl p-6">

                        <h2 class="text-2xl font-bold text-slate-900 mb-2">
                            1. Kota Tujuan
                        </h2>

                        <p class="text-slate-500 mb-6">
                            Pilih kota yang ingin Anda jelajahi.
                        </p>

                        <div class="grid md:grid-cols-2 gap-5">

                            <!-- BANDUNG -->

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="kota_preferensi"
                                    value="Bandung"
                                    class="peer hidden">

                                <div class="rounded-3xl overflow-hidden border-2 border-slate-200 peer-checked:border-sky-600">

                                    <img src="/images/gedung-sate.jpg"
                                         class="h-52 w-full object-cover">

                                    <div class="p-4 text-center">

                                        <div class="font-bold text-lg">
                                            Bandung
                                        </div>

                                    </div>

                                </div>

                            </label>

                            <!-- JAKARTA -->

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="kota_preferensi"
                                    value="Jakarta"
                                    class="peer hidden">

                                <div class="rounded-3xl overflow-hidden border-2 border-slate-200 peer-checked:border-sky-600">

                                    <img src="/images/monas-jkt.jpg"
                                         class="h-52 w-full object-cover">

                                    <div class="p-4 text-center">

                                        <div class="font-bold text-lg">
                                            Jakarta
                                        </div>

                                    </div>

                                </div>

                            </label>

                        </div>

                    </section>

                    <!-- MINAT -->

                    <section class="border rounded-3xl p-6">

                        <h2 class="text-2xl font-bold text-slate-900 mb-2">
                            2. Minat Wisata & Kategori Tempat
                        </h2>

                        <p class="text-slate-500 mb-6">
                            Pilih satu atau lebih kategori wisata, kuliner, atau penginapan yang Anda sukai.
                        </p>

                        <!-- Wisata -->
                        <div class="mb-6">
                            <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <span>🌴</span> Destinasi Wisata
                            </h3>
                            <div class="grid md:grid-cols-3 gap-4">
                                @php
                                    $wisataList = isset($kategoriWisata) ? $kategoriWisata : \App\Models\KategoriWisata::orderBy('nama_kategori')->get();
                                @endphp
                                @foreach($wisataList as $kat)
                                    <label class="cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="minat_wisata[]"
                                            value="{{ $kat->nama_kategori }}"
                                            class="peer hidden">

                                        <div class="border rounded-2xl p-4 text-center font-medium 
                                                    peer-checked:bg-sky-600
                                                    peer-checked:text-white
                                                    peer-checked:border-sky-600 transition hover:border-sky-300">
                                            {{ $kat->nama_kategori }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Kuliner -->
                        <div class="mb-6">
                            <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <span>🍜</span> Wisata Kuliner
                            </h3>
                            <div class="grid md:grid-cols-3 gap-4">
                                @php
                                    $kulinerList = isset($kategoriKuliner) ? $kategoriKuliner : \App\Models\KategoriKuliner::orderBy('nama_kategori')->get();
                                @endphp
                                @foreach($kulinerList as $kat)
                                    <label class="cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="minat_wisata[]"
                                            value="{{ $kat->nama_kategori }}"
                                            class="peer hidden">

                                        <div class="border rounded-2xl p-4 text-center font-medium 
                                                    peer-checked:bg-amber-600
                                                    peer-checked:text-white
                                                    peer-checked:border-amber-600 transition hover:border-amber-300">
                                            {{ $kat->nama_kategori }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Penginapan -->
                        <div>
                            <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <span>🏨</span> Penginapan
                            </h3>
                            <div class="grid md:grid-cols-3 gap-4">
                                @php
                                    $penginapanList = isset($kategoriPenginapan) ? $kategoriPenginapan : \App\Models\KategoriPenginapan::orderBy('nama_kategori')->get();
                                @endphp
                                @foreach($penginapanList as $kat)
                                    <label class="cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="minat_wisata[]"
                                            value="{{ $kat->nama_kategori }}"
                                            class="peer hidden">

                                        <div class="border rounded-2xl p-4 text-center font-medium 
                                                    peer-checked:bg-emerald-600
                                                    peer-checked:text-white
                                                    peer-checked:border-emerald-600 transition hover:border-emerald-300">
                                            {{ $kat->nama_kategori }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </section>

                    <!-- Informasi Pemilihan Kategori
<div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4">
    <div class="flex items-start gap-3">
        <div>
            <p class="font-semibold text-amber-800">
                Informasi Pemilihan Kategori
            </p>

            <p class="text-sm text-amber-700 mt-1">
                Anda dapat memilih lebih dari satu kategori wisata untuk memperoleh
                rekomendasi yang lebih beragam. Namun, apabila memilih
                <strong>Penginapan</strong>, disarankan tidak mengombinasikannya
                dengan kategori wisata lainnya agar hasil rekomendasi dan
                rentang budget lebih sesuai.
            </p>
        </div>
    </div>
</div> -->

                    <!-- BUDGET -->

                    <section class="border rounded-3xl p-6">

                        <h2 class="text-2xl font-bold text-slate-900 mb-2">
                            3. Budget Perjalanan
                        </h2>

                        <p class="text-slate-500 mb-6">
    Pilih kategori budget yang sesuai. Rentang budget akan disesuaikan secara otomatis berdasarkan kategori yang dipilih.
</p>

                        <div class="grid md:grid-cols-4 gap-4">

                            @foreach([
                                'Gratis',
                                'Murah',
                                'Sedang',
                                'Mahal'
                            ] as $budget)

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="budget"
                                    value="{{ $budget }}"
                                    class="peer hidden">

                                <div class="border rounded-2xl p-5 text-center
                                            peer-checked:bg-sky-600
                                            peer-checked:text-white
                                            peer-checked:border-sky-600">

                                    <div class="font-bold text-lg">
                                        {{ $budget }}
                                    </div>

                                </div>

                            </label>

                            @endforeach

                        </div>

                    </section>

                    <!-- HIDDEN GEM -->

                    <section class="border rounded-3xl p-6">

                        <h2 class="text-2xl font-bold text-slate-900 mb-2">
                            4. Hidden Gem
                        </h2>

                        <p class="text-slate-500 mb-5">
                            Tampilkan destinasi unik yang belum banyak diketahui wisatawan.
                        </p>

                        <label class="flex items-center gap-3">

                            <input
                                type="checkbox"
                                name="hidden_gem"
                                value="1"
                                class="w-5 h-5">

                            <span class="font-medium">
                                Tampilkan Hidden Gem
                            </span>

                        </label>

                    </section>

                    <!-- SUBMIT -->

                    <button
                        type="submit"
                        class="w-full bg-sky-600 hover:bg-sky-700 transition text-white text-lg font-bold py-5 rounded-2xl shadow-lg">

                        Simpan Preferensi & Lihat Rekomendasi

                    </button>

                </form>

            </div>

        </div>

        <!-- SIDEBAR KANAN -->

     

        </div>

    </div>

</div>

</body>
</html>