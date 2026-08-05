<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinasiImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'destinasi_id', 'url_image', 'is_thumbnail'
    ];

    protected function casts(): array
    {
        return [
            'is_thumbnail' => 'boolean',
        ];
    }

    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class);
    }
}
