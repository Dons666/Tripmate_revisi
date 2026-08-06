<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'user_id',
        'destinasi_id',
        'keyword_search',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class);
    }

    public function getUserIdAttribute()
    {
        return $this->attributes['id_user'] ?? $this->attributes['user_id'] ?? null;
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['id_user'] = $value;
        $this->attributes['user_id'] = $value;
    }
}
