<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $table = 'user_notifications';

    protected $fillable = [
        'id_user',
        'user_id',
        'title',
        'message',
        'pesan',
        'type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('id_user', $userId);
        });
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

    public function getMessageAttribute()
    {
        return $this->attributes['message'] ?? $this->attributes['pesan'] ?? null;
    }

    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = $value;
        $this->attributes['pesan'] = $value;
    }

    public static function sendNotification($userId, $title, $message, $type = 'info')
    {
        return self::create([
            'id_user' => $userId,
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'pesan'   => $message,
            'type'    => $type,
            'is_read' => false,
        ]);
    }
}
