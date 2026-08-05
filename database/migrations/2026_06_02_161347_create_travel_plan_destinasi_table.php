<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('travel_plan_destinasi')) {
            Schema::create('travel_plan_destinasi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('travel_plan_id');
                $table->unsignedBigInteger('destinasi_id');
                $table->timestamps();

                $planPk = Schema::hasColumn('travel_plans', 'id_perencanaan') ? 'id_perencanaan' : 'id';
                $table->foreign('travel_plan_id')->references($planPk)->on('travel_plans')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_plan_destinasi');
    }
};