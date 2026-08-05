<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    use HasFactory;

    protected $table = 'destinasi';

    protected $fillable = [

        // Informasi Utama
        'nama_destinasi',
        'tipe',
        'kota',
        'kategori',
        'harga',
        'hidden_gem',

        // Informasi Destinasi
        'deskripsi',
        'fasilitas',
        'alamat',
        'latitude',
        'longitude',
        'transportasi',
        'hari_operasional',
        'jam_buka',
        'jam_tutup',

        // Sistem Rekomendasi
        'fitur_cbf',

        // Media
        'gambar',

        // Rating
        'rating_destinasi',

    ];

    protected function casts(): array
    {
        return [

            'harga' => 'decimal:2',

            'latitude' => 'decimal:7',

            'longitude' => 'decimal:7',

            'hidden_gem' => 'boolean',

            'rating_destinasi' => 'decimal:2',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function kategoris()
    {
        return $this->belongsToMany(
            Kategori::class,
            'destinasi_kategori'
        );
    }

    public function images()
    {
        return $this->hasMany(DestinasiImage::class);
    }

    public function thumbnail()
    {
        return $this->hasOne(DestinasiImage::class)
                    ->where('is_thumbnail', true);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'destinasi_id', 'id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function travelPlans()
    {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */

    public function getAverageRatingAttribute()
    {
        if (isset($this->attributes['ratings_avg_skor_rating'])) {
            return (float) $this->attributes['ratings_avg_skor_rating'];
        }

        return (float) ($this->ratings()->avg('skor_rating') ?? 0);
    }

    public function getRatingAttribute(): float
    {
        return (float) $this->average_rating;
    }

    public function getNameAttribute(): string
    {
        return (string) ($this->nama_destinasi ?? '');
    }

    public function getIdDestinationsAttribute(): int
    {
        return (int) $this->id;
    }

    public function getIdCulinariesAttribute(): int
    {
        return (int) $this->id;
    }

    public function getIdStaysAttribute(): int
    {
        return (int) $this->id;
    }

    public function getCategoryAttribute(): ?string
    {
        return $this->kategori;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->deskripsi;
    }

    public function getStatusLokasiAttribute(): string
    {
        return $this->hidden_gem ? 'hidden gem' : 'terkenal';
    }

    public function getCityAttribute(): ?string
    {
        return $this->kota;
    }

    public function getPlaceAddressAttribute(): ?string
    {
        [$address] = $this->splitAddressAndProvince();

        return $address;
    }

    public function getProvinceAttribute(): ?string
    {
        [, $province] = $this->splitAddressAndProvince();

        return $province;
    }

    public function getLocationAttribute(): string
    {
        $address = $this->place_address ?? '-';
        $city = $this->city ?? '-';

        return $address . ', ' . $city;
    }

    public function getPriceAttribute(): float
    {
        return (float) ($this->harga ?? 0);
    }

    public function getUserRatingAvgAttribute(): float
    {
        if (isset($this->attributes['ratings_avg_skor_rating'])) {
            return (float) $this->attributes['ratings_avg_skor_rating'];
        }

        return (float) ($this->average_rating ?? 0);
    }

    public function getCuisineTypeAttribute(): ?string
    {
        return $this->kategori;
    }

    public function getAmenitiesAttribute(): ?string
    {
        return $this->fasilitas;
    }

    public function getTransportModesAttribute(): array
    {
        if (empty($this->transportasi)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->transportasi))));
    }

    public function getImageUrlsAttribute(): array
    {
        if (empty($this->gambar)) {
            return [];
        }

        $decoded = json_decode($this->gambar, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter($decoded));
        }

        return array_values(array_filter(array_map('trim', explode(',', (string) $this->gambar))));
    }

    public function getImageUrlAttribute(): ?string
    {
        $images = $this->image_urls;

        return $images[0] ?? null;
    }

    public function getOperationalScheduleAttribute(): array
    {
        $schedule = [];

        if (!empty($this->hari_operasional) && str_contains($this->hari_operasional, ':')) {
            foreach (explode(';', (string) $this->hari_operasional) as $segment) {
                if (!str_contains($segment, ':')) {
                    continue;
                }

                [$day, $value] = explode(':', $segment, 2);

                if ($value === 'closed') {
                    $schedule[$day] = [
                        'status' => 'closed',
                        'open_time' => '',
                        'close_time' => '',
                    ];
                    continue;
                }

                [$openTime, $closeTime] = array_pad(explode('-', $value, 2), 2, '');
                $schedule[$day] = [
                    'status' => 'open',
                    'open_time' => $openTime,
                    'close_time' => $closeTime,
                ];
            }

            if (!empty($schedule)) {
                return $schedule;
            }
        }

        // Fallback jika jam_buka / jam_tutup berupa string terpisah (misal: "06:00", "22:00")
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $openTime = $this->jam_buka ?: '';
        $closeTime = $this->jam_tutup ?: '';
        $hasTime = !empty($openTime) || !empty($closeTime);

        foreach ($days as $day) {
            $schedule[$day] = [
                'status' => $hasTime ? 'open' : 'closed',
                'open_time' => $openTime,
                'close_time' => $closeTime,
            ];
        }

        return $schedule;
    }

    private function splitAddressAndProvince(): array
    {
        if (empty($this->alamat)) {
            return [null, null];
        }

        if (!str_contains($this->alamat, '||')) {
            return [$this->alamat, null];
        }

        [$address, $province] = explode('||', (string) $this->alamat, 2);

        return [trim($address), trim($province) ?: null];
    }
}