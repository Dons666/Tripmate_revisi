<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_plans', 'tujuan')) {
                $table->string('tujuan')->nullable()->after('nama_perjalanan');
            }
            if (!Schema::hasColumn('travel_plans', 'catatan')) {
                $table->text('catatan')->nullable()->after('tujuan');
            }
            if (!Schema::hasColumn('travel_plans', 'status')) {
                $table->string('status')->default('Perencanaan Aktif')->after('budget');
            }
            if (!Schema::hasColumn('travel_plans', 'foto_sampul')) {
                $table->string('foto_sampul')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            $cols = array_filter(['tujuan', 'catatan', 'status', 'foto_sampul'], fn($c) => Schema::hasColumn('travel_plans', $c));
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};