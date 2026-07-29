<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    protected $table = 'travels';

    protected $fillable = [
        'user_id',
        'armada_id',
        'nama_travel',
        'slug',
        'layanan',
        'deskripsi',
        'harga_paket',
        'tanggal_keberangkatan',
        'rating',
        'kota',
        'kontak',
        'gambar',
    ];

    protected $casts = [
        'harga_paket' => 'float',
        'rating'      => 'float',
        'tanggal_keberangkatan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function armada()
    {
        return $this->belongsTo(Armada::class);
    }

    public function destinasis()
    {
        return $this->belongsToMany(Destinasi::class, 'destinasi_travel')
            ->withPivot('id')
            ->orderBy('destinasi_travel.id', 'asc');
    }

    public function travelPlans()
    {
        return $this->hasMany(TravelPlan::class);
    }
}
