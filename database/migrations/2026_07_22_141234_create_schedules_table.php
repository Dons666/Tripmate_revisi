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
        if (!Schema::hasTable('schedules')) {
            Schema::create('schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('travel_plan_id');
                $table->unsignedBigInteger('destinasi_id')->nullable();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->date('tanggal');
                $table->time('jam_mulai')->nullable();
                $table->time('jam_selesai')->nullable();
                $table->timestamps();

                $planPk = Schema::hasColumn('travel_plans', 'id_perencanaan') ? 'id_perencanaan' : 'id';
                $table->foreign('travel_plan_id')->references($planPk)->on('travel_plans')->onDelete('cascade');
            });
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
