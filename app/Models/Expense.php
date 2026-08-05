<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'travel_plan_id',
        'nama_pengeluaran',
        'jumlah',
        'tanggal',
        'kategori',
    ];

    protected $casts = [
        'tanggal' => 'date',    // <-- INI YANG PENTING
        'jumlah'  => 'decimal:2',
    ];

    public function travelPlan()
    {
        return $this->belongsTo(TravelPlan::class, 'travel_plan_id', 'id_perencanaan');
    }
}
