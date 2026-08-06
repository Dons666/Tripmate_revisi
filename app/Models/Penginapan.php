<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Penginapan extends Destinasi
{
    protected $table = 'destinasi';

    protected static function booted(): void
    {
        static::addGlobalScope('penginapan_type', function (Builder $builder) {
            $builder->where('tipe', 'penginapan');
        });
    }

    public function kategoriPenginapan()
    {
        return $this->belongsTo(KategoriPenginapan::class, 'kategori_penginapan_id');
    }
}
