<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Wisata extends Destinasi
{
    protected $table = 'destinasi';

    protected static function booted(): void
    {
        static::addGlobalScope('wisata_type', function (Builder $builder) {
            $builder->where('tipe', 'wisata');
        });
    }

    public function kategoriWisata()
    {
        return $this->belongsTo(KategoriWisata::class, 'kategori_wisata_id');
    }
}
