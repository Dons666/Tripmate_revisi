<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKuliner extends Model
{
    use HasFactory;

    protected $table = 'kategori_kuliner';

    protected $fillable = [
        'nama_kategori',
    ];

    public function destinasi()
    {
        return $this->hasMany(Destinasi::class, 'kategori_kuliner_id');
    }

    public function kuliner()
    {
        return $this->hasMany(Kuliner::class, 'kategori_kuliner_id');
    }
}
