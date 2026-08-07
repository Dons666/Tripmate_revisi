<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penginapan extends Model
{
    protected $table = 'penginapan';
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
        return $this->attributes['kategori_penginapan'] ?? $this->attributes['tipe_penginapan'] ?? null;
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'destinasi_id', 'id_penginapan');
    }
}
