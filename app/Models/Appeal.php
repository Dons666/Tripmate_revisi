<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_appeals';

    protected $fillable = [
        'id_user',
        'user_id',
        'nama',
        'email',
        'password',
        'reason',
        'status',
        'admin_notes',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

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
}
