<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $table = 'user_preferences';

    protected $primaryKey = 'id_user_preferences';

    protected $fillable = [
        'id_user',
        'kota_preferensi',
        'minat_wisata',
        'hidden_gem',
        'budget'
    ];

    protected function casts(): array
    {
        return [
            'minat_wisata' => 'array',
            'hidden_gem' => 'boolean',
        ];
    }

    /**
     * Alias user_id ke id_user
     */
    public function getUserIdAttribute()
    {
        return $this->id_user;
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['id_user'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}