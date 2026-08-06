<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'id_user',
        'user_id',
        'travel_plan_id',
        'nama_pengeluaran',
        'jumlah',
        'tanggal',
        'kategori',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function travelPlan()
    {
        return $this->belongsTo(TravelPlan::class, 'travel_plan_id', 'id_travel_plan');
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
