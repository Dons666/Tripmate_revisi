<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_plan_destinasi', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_plan_destinasi', 'is_visited')) {
                $table->boolean('is_visited')->default(false)->after('destinasi_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('travel_plan_destinasi', function (Blueprint $table) {
            if (Schema::hasColumn('travel_plan_destinasi', 'is_visited')) {
                $table->dropColumn('is_visited');
            }
        });
    }
};
