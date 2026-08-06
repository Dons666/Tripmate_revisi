<?php

namespace Database\Seeders;

use App\Models\Destinasi;
use App\Models\KategoriKuliner;
use App\Models\KategoriPenginapan;
use App\Models\KategoriWisata;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Kategori Wisata
        $kategoriWisataList = [
            'Agrowisata',
            'Desa Wisata',
            'Ekowisata',
            'Taman Hiburan',
            'Tempat Prasejarah',
            'Wisata Alam',
            'Wisata Bahari',
            'Wisata Buatan',
            'Wisata Budaya',
            'Wisata Edukasi',
            'Wisata Kuliner',
            'Wisata Religi',
            'Wisata Sejarah',
            'Lainnya',
        ];

        foreach ($kategoriWisataList as $nama) {
            KategoriWisata::firstOrCreate(
                ['nama_kategori' => $nama]
            );
        }

        // 2. Seed Kategori Penginapan
        $kategoriPenginapanList = [
            'Hotel',
            'Resort',
            'Villa',
            'Homestay',
            'Glamping',
            'Hostel',
            'Apartemen',
            'Penginapan',
        ];

        foreach ($kategoriPenginapanList as $nama) {
            KategoriPenginapan::firstOrCreate(
                ['nama_kategori' => $nama]
            );
        }

        // 3. Seed Kategori Kuliner
        $kategoriKulinerList = [
            'Makanan Tradisional',
            'Makanan Khas Daerah',
            'Makanan Ringan',
            'Jajanan Kaki Lima',
            'Makanan Laut',
            'Makanan Cepat Saji',
            'Makanan Penutup',
            'Minuman',
            'Wisata Kuliner',
            'Restoran',
            'Kafe',
        ];

        foreach ($kategoriKulinerList as $nama) {
            KategoriKuliner::firstOrCreate(
                ['nama_kategori' => $nama]
            );
        }

        // 4. Update relasi data destinasi yang sudah ada
        $allWisataCat = KategoriWisata::all();
        $allPenginapanCat = KategoriPenginapan::all();
        $allKulinerCat = KategoriKuliner::all();

        Destinasi::all()->each(function (Destinasi $item) use ($allWisataCat, $allPenginapanCat, $allKulinerCat) {
            $catName = trim(strtolower((string) $item->kategori));

            if ($item->tipe === 'wisata') {
                $matched = $allWisataCat->first(fn($k) => strtolower($k->nama_kategori) === $catName);
                if ($matched) {
                    $item->kategori_wisata_id = $matched->id_kategori_wisata;
                } else {
                    $lainnya = $allWisataCat->first(fn($k) => strtolower($k->nama_kategori) === 'lainnya');
                    if ($lainnya) {
                        $item->kategori_wisata_id = $lainnya->id_kategori_wisata;
                    }
                }
            } elseif ($item->tipe === 'penginapan') {
                $matched = $allPenginapanCat->first(fn($k) => strtolower($k->nama_kategori) === $catName);
                if ($matched) {
                    $item->kategori_penginapan_id = $matched->id_kategori_penginapan;
                } else {
                    $default = $allPenginapanCat->first(fn($k) => strtolower($k->nama_kategori) === 'penginapan');
                    if ($default) {
                        $item->kategori_penginapan_id = $default->id_kategori_penginapan;
                    }
                }
            } elseif ($item->tipe === 'kuliner') {
                $matched = $allKulinerCat->first(fn($k) => strtolower($k->nama_kategori) === $catName);
                if ($matched) {
                    $item->kategori_kuliner_id = $matched->id_kategori_kuliner;
                } else {
                    $default = $allKulinerCat->first(fn($k) => strtolower($k->nama_kategori) === 'wisata kuliner');
                    if ($default) {
                        $item->kategori_kuliner_id = $default->id_kategori_kuliner;
                    }
                }
            }

            $item->save();
        });
    }
}
