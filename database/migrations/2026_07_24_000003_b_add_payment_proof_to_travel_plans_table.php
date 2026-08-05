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
            if (!Schema::hasColumn('travel_plans', 'payment_proof')) {
                $table->string('payment_proof')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            if (Schema::hasColumn('travel_plans', 'payment_proof')) {
                $table->dropColumn('payment_proof');
            }
        });
    }
};
