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
            if (!Schema::hasColumn('travel_plans', 'payment_status')) {
                $table->string('payment_status')->default('unpaid');
            }
            if (!Schema::hasColumn('travel_plans', 'trip_status')) {
                $table->string('trip_status')->default('planning');
            }
            if (!Schema::hasColumn('travel_plans', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('travel_plans', 'payment_ref')) {
                $table->string('payment_ref')->nullable();
            }
            if (!Schema::hasColumn('travel_plans', 'trip_started_at')) {
                $table->timestamp('trip_started_at')->nullable();
            }
            if (!Schema::hasColumn('travel_plans', 'trip_ended_at')) {
                $table->timestamp('trip_ended_at')->nullable();
            }
            if (!Schema::hasColumn('travel_plans', 'payout_released_at')) {
                $table->timestamp('payout_released_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            $cols = array_filter([
                'payment_status',
                'trip_status',
                'payment_method',
                'payment_ref',
                'trip_started_at',
                'trip_ended_at',
                'payout_released_at',
            ], fn($c) => Schema::hasColumn('travel_plans', $c));

            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
