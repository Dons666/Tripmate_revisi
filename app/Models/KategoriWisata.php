<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriWisata extends Model
{
    use HasFactory;

    protected $table = 'kategori_wisata';
    protected $primaryKey = 'id_kategori_wisata';

    protected $fillable = [
        'nama_kategori',
    ];

    public function destinasi()
    {
        return $this->hasMany(Destinasi::class, 'kategori_wisata_id');
    }

    public function wisata()
    {
        return $this->hasMany(Wisata::class, 'kategori_wisata_id');
    }
}
