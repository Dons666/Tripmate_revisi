<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori'; // <-- TAMBAHKAN INI

    protected $fillable = ['nama_kategori'];

    public function destinasi()
    {
        return $this->belongsToMany(Destinasi::class, 'destinasi_kategori');
    }
}
