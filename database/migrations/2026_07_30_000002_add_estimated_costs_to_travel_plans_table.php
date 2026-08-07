<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_plans', 'total_cost')) {
                $table->decimal('total_cost', 15, 2)->default(0.00);
            }
            if (!Schema::hasColumn('travel_plans', 'estimasi_makan_per_orang')) {
                $table->decimal('estimasi_makan_per_orang', 15, 2)->default(0.00)->after('total_cost');
            }
            if (!Schema::hasColumn('travel_plans', 'estimasi_transport_per_orang')) {
                $table->decimal('estimasi_transport_per_orang', 15, 2)->default(0.00)->after('estimasi_makan_per_orang');
            }
        });
    }

    public function down(): void
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            if (Schema::hasColumn('travel_plans', 'total_cost')) {
                $table->dropColumn('total_cost');
            }
            if (Schema::hasColumn('travel_plans', 'estimasi_makan_per_orang')) {
                $table->dropColumn('estimasi_makan_per_orang');
            }
            if (Schema::hasColumn('travel_plans', 'estimasi_transport_per_orang')) {
                $table->dropColumn('estimasi_transport_per_orang');
            }
        });
    }
};
