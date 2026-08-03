<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JarakDestinasi extends Model
{
    use HasFactory;

    protected $table = 'jarak_destinasi';

    protected $fillable = [
        'asal_id',
        'tujuan_id',
        'jarak',
        'durasi',
    ];

    public function asal()
    {
        return $this->belongsTo(Destinasi::class, 'asal_id');
    }

    public function tujuan()
    {
        return $this->belongsTo(Destinasi::class, 'tujuan_id');
    }
}
