<?php

namespace App\Imports;

use App\Models\Destinasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DestinasiImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Abaikan baris jika nama kosong
        if (empty($row['nama'])) {
            return null;
        }

        // Cek duplikasi di database berdasarkan nama dan kota
        $existing = \App\Models\Destinasi::where('nama_destinasi', $row['nama'])
            ->where('kota', $row['kota'] ?? null)
            ->first();

        if ($existing) {
            return null; // Lewati jika destinasi sudah ada
        }

        // Transformasi tipe
        $tipe = strtolower(trim($row['tipe'] ?? 'wisata'));
        if ($tipe === 'destinasi') {
            $tipe = 'wisata';
        }

        // Transformasi Hidden Gem
        $hiddenGem = isset($row['hidden_gem']) &&
                     strtolower(trim($row['hidden_gem'])) === 'ya';

        // Harga
        $harga = (isset($row['harga']) && is_numeric($row['harga']))
            ? (float) $row['harga']
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Sistem Rekomendasi (CBF)
        |--------------------------------------------------------------------------
        | Prioritas:
        | 1. fitur_cbf_final (hasil preprocessing terbaru)
        | 2. fitur_cbf_clean
        | 3. fitur_cbf (dataset lama)
        |--------------------------------------------------------------------------
        */
        $fiturCbfFinal = $row['fitur_cbf_final'] ?? null;

        $fiturCbfClean = $row['fitur_cbf_clean']
            ?? ($row['fitur_c_b_f_clean'] ?? null);

        $fiturCbf = $row['fitur_cbf']
            ?? ($row['fitur_c_b_f'] ?? null);

        // Kategori
        $kategoriAsli = $row['kategori_asli']
            ?? ($row['kategori'] ?? 'Umum');

        // Link gambar
        $gambar = $row['gambar']
            ?? ($row['link_gambar']
            ?? ($row['url_gambar'] ?? null));

        // Rating
        $ratingDestinasi = (isset($row['rating_destinasi']) &&
                            is_numeric($row['rating_destinasi']))
            ? (float) $row['rating_destinasi']
            : 0.00;

        // Latitude & Longitude Parsing
        $latitude = null;
        if (isset($row['latitude']) && is_numeric($row['latitude'])) {
            $latVal = (float) $row['latitude'];
            if (abs($latVal) > 15) {
                while (abs($latVal) > 15) {
                    $latVal /= 10;
                }
            }
            $latitude = $latVal;
        }

        $longitude = null;
        if (isset($row['longitude']) && is_numeric($row['longitude'])) {
            $lonVal = (float) $row['longitude'];
            if (abs($lonVal) > 180) {
                while (abs($lonVal) > 180) {
                    $lonVal /= 10;
                }
            }
            $longitude = $lonVal;
        }

        // Alamat & Provinsi
        $alamat = $row['alamat'] ?? null;
        $provinsi = $row['provinsi'] ?? null;
        if ($alamat && $provinsi) {
            $alamat = trim($alamat) . ' || ' . trim($provinsi);
        }

        return new Destinasi([

            // Data utama
            'nama_destinasi'   => $row['nama'],
            'tipe'             => $tipe,
            'kota'             => $row['kota'] ?? null,
            'kategori'         => $kategoriAsli,
            'harga'            => $harga,
            'hidden_gem'       => $hiddenGem,

            // Informasi destinasi
            'deskripsi'        => $row['deskripsi'] ?? null,
            'fasilitas'        => $row['fasilitas'] ?? 'Tidak tersedia',
            'alamat'           => $alamat,
            'latitude'         => $latitude,
            'longitude'        => $longitude,
            'transportasi'     => $row['transportasi'] ?? null,
            'jam_buka'         => $row['jam_buka'] ?? null,
            'jam_tutup'        => $row['jam_tutup'] ?? null,
            'hari_operasional' => $row['hari_operasional'] ?? null,

            // Sistem rekomendasi
            'fitur_cbf' => $fiturCbfFinal
                            ?? $fiturCbfClean
                            ?? $fiturCbf,

            // Gambar
            'gambar' => $gambar,

            // Rating
            'rating_destinasi' => $ratingDestinasi,

        ]);
    }
}
