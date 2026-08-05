<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_plans', 'jumlah_peserta')) {
                $table->integer('jumlah_peserta')->default(1);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            if (Schema::hasColumn('travel_plans', 'jumlah_peserta')) {
                $table->dropColumn('jumlah_peserta');
            }
        });
    }
};
