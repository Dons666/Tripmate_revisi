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
            if (!Schema::hasColumn('travel_plans', 'travel_id')) {
                $table->unsignedBigInteger('travel_id')->nullable()->after('id_user');
            }
            if (!Schema::hasColumn('travel_plans', 'budget')) {
                $table->decimal('budget', 15, 2)->default(0)->after('catatan');
            }
            if (!Schema::hasColumn('travel_plans', 'jumlah_peserta')) {
                $table->integer('jumlah_peserta')->default(1)->after('budget');
            }
            if (!Schema::hasColumn('travel_plans', 'status')) {
                $table->string('status')->default('Perencanaan Aktif')->after('jumlah_peserta');
            }
            if (!Schema::hasColumn('travel_plans', 'is_checkout')) {
                $table->boolean('is_checkout')->default(false)->after('status');
            }
            if (!Schema::hasColumn('travel_plans', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('is_checkout');
            }
            if (!Schema::hasColumn('travel_plans', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('travel_plans', 'trip_status')) {
                $table->string('trip_status')->default('upcoming')->after('payment_proof');
            }
            if (!Schema::hasColumn('travel_plans', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('trip_status');
            }
            if (!Schema::hasColumn('travel_plans', 'payment_ref')) {
                $table->string('payment_ref')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('travel_plans', 'trip_started_at')) {
                $table->timestamp('trip_started_at')->nullable()->after('payment_ref');
            }
            if (!Schema::hasColumn('travel_plans', 'trip_ended_at')) {
                $table->timestamp('trip_ended_at')->nullable()->after('trip_started_at');
            }
            if (!Schema::hasColumn('travel_plans', 'payout_released_at')) {
                $table->timestamp('payout_released_at')->nullable()->after('trip_ended_at');
            }
            if (!Schema::hasColumn('travel_plans', 'estimasi_makan_per_orang')) {
                $table->decimal('estimasi_makan_per_orang', 15, 2)->default(0)->after('payout_released_at');
            }
            if (!Schema::hasColumn('travel_plans', 'estimasi_transport_per_orang')) {
                $table->decimal('estimasi_transport_per_orang', 15, 2)->default(0)->after('estimasi_makan_per_orang');
            }
            if (!Schema::hasColumn('travel_plans', 'total_cost')) {
                $table->decimal('total_cost', 15, 2)->default(0)->after('estimasi_transport_per_orang');
            }
            if (!Schema::hasColumn('travel_plans', 'schedules_json')) {
                $table->json('schedules_json')->nullable()->after('total_cost');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            $columnsToDrop = array_filter([
                'travel_id',
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
                'estimasi_makan_per_orang',
                'estimasi_transport_per_orang',
                'total_cost',
                'schedules_json',
            ], fn($col) => Schema::hasColumn('travel_plans', $col));

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
