<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    protected $table = 'wisata';
    protected $primaryKey = 'id_wisata';
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
        return $this->attributes['kategori_wisata'] ?? $this->attributes['tipe_wisata'] ?? null;
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'destinasi_id', 'id_wisata');
    }
}
