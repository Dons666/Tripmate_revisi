<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuliner extends Model
{
    protected $table = 'kuliner';
    protected $guarded = [];

    public function getNamaDestinasiAttribute()
    {
        return $this->attributes['nama'] ?? null;
    }

    public function setNamaDestinasiAttribute($value)
    {
        $this->attributes['nama'] = $value;
    }

    public function getKategoriAttribute()
    {
        return $this->attributes['kategori_kuliner'] ?? $this->attributes['tipe_kuliner'] ?? null;
    }

    public function kategoriKuliner()
    {
        return $this->belongsTo(KategoriKuliner::class, 'kategori_kuliner_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'destinasi_id', 'id');
    }
}
