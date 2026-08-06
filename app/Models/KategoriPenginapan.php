<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPenginapan extends Model
{
    use HasFactory;

    protected $table = 'kategori_penginapan';

    protected $fillable = [
        'nama_kategori',
    ];

    public function destinasi()
    {
        return $this->hasMany(Destinasi::class, 'kategori_penginapan_id');
    }

    public function penginapan()
    {
        return $this->hasMany(Penginapan::class, 'kategori_penginapan_id');
    }
}
