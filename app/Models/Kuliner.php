<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Kuliner extends Destinasi
{
    protected $table = 'destinasi';

    protected static function booted(): void
    {
        static::addGlobalScope('kuliner_type', function (Builder $builder) {
            $builder->where('tipe', 'kuliner');
        });
    }

    public function kategoriKuliner()
    {
        return $this->belongsTo(KategoriKuliner::class, 'kategori_kuliner_id');
    }
}
