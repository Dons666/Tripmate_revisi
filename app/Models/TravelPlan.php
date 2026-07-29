<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelPlan extends Model
{
    protected $fillable = [
        'user_id',
        'travel_id',
        'nama_perjalanan',
        'tujuan',
        'catatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'budget',
        'jumlah_peserta',
        'status',
        'is_checkout',
        'payment_status',
        'payment_proof',
        'trip_status',
        'payment_method',
        'payment_ref',
        'trip_started_at',
        'trip_ended_at',
        'payout_released_at',
        'foto_sampul',
    ];

    protected $casts = [
        'user_id'            => 'integer',
        'travel_id'          => 'integer',
        'tanggal_mulai'      => 'date',
        'tanggal_selesai'    => 'date',
        'is_checkout'        => 'boolean',
        'trip_started_at'    => 'datetime',
        'trip_ended_at'      => 'datetime',
        'payout_released_at' => 'datetime',
    ];

    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->sum('jumlah');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    public function destinasis()
    {
        return $this->belongsToMany(Destinasi::class, 'travel_plan_destinasi')
            ->withPivot('id')
            ->orderBy('travel_plan_destinasi.id', 'asc');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class)->orderBy('tanggal')->orderBy('jam_mulai');
    }
}

