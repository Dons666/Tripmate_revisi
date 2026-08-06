<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'user_id',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
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

    public function setIdUserAttribute($value)
    {
        $this->attributes['id_user'] = $value;
        $this->attributes['user_id'] = $value;
    }
}
