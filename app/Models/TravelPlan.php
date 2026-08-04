<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelPlan extends Model
{
    protected $primaryKey = 'id_perencanaan';

    protected $fillable = [
        'id_user',
        'travel_id',
        'nama_perjalanan',
        'tujuan',
        'catatan',
        'tanggal_berangkat',
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
        'estimasi_makan_per_orang',
        'estimasi_transport_per_orang',
        'total_cost',
        'schedules_json',
    ];

    protected $casts = [
        'id_user'            => 'integer',
        'travel_id'          => 'integer',
        'tanggal_berangkat'  => 'date',
        'tanggal_selesai'    => 'date',
        'is_checkout'        => 'boolean',
        'trip_started_at'    => 'datetime',
        'trip_ended_at'      => 'datetime',
        'payout_released_at' => 'datetime',
        'schedules_json'     => 'array',
    ];

    protected $appends = ['destinasis', 'schedules', 'total_expenses', 'tanggal_mulai'];

    public function getTanggalMulaiAttribute()
    {
        return $this->tanggal_berangkat;
    }

    public function setTanggalMulaiAttribute($value)
    {
        $this->attributes['tanggal_berangkat'] = $value;
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->sum('jumlah');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'travel_plan_id', 'id_perencanaan');
    }

    /**
     * Aksesor dinamis untuk mendapatkan daftar destinasi dari kolom JSON.
     */
    public function getDestinasisAttribute()
    {
        $schedules = $this->schedules_json ?: [];
        $destinasiIds = collect($schedules)->pluck('destinasi_id')->unique();
        if ($destinasiIds->isEmpty()) {
            return collect();
        }

        $destinations = Destinasi::whereIn('id', $destinasiIds)->get()->keyBy('id');

        return collect($schedules)->map(function ($item) use ($destinations) {
            $dest = $destinations->get($item['destinasi_id']);
            if ($dest) {
                $clone = clone $dest;
                $clone->setRelation('pivot', (object)[
                    'is_visited' => $item['is_visited'] ?? false,
                    'tanggal' => $item['tanggal'] ?? null,
                    'jam_mulai' => $item['jam_mulai'] ?? null,
                    'jam_selesai' => $item['jam_selesai'] ?? null,
                    'catatan' => $item['catatan'] ?? null,
                ]);
                return $clone;
            }
            return null;
        })->filter()->values();
    }

    /**
     * Aksesor dinamis untuk mensimulasikan jadwal (Schedule) dari data kolom JSON.
     */
    public function getSchedulesAttribute()
    {
        $schedules = $this->schedules_json ?: [];
        $destinasiIds = collect($schedules)->pluck('destinasi_id')->unique();
        $destinations = Destinasi::whereIn('id', $destinasiIds)->get()->keyBy('id');

        return collect($schedules)->map(function ($item, $index) use ($destinations) {
            $dest = $destinations->get($item['destinasi_id']);

            $sch = new Schedule([
                'id_jadwal' => $index + 1,
                'id_perencanaan' => $this->id_perencanaan,
                'id_destinasi' => $item['destinasi_id'],
                'tanggal' => $item['tanggal'] ?? null,
                'jam_mulai' => $item['jam_mulai'] ?? null,
                'jam_selesai' => $item['jam_selesai'] ?? null,
                'deskripsi' => $item['catatan'] ?? null,
            ]);

            if ($dest) {
                $sch->setRelation('destinasi', $dest);
            }

            return $sch;
        })->values();
    }
}
