<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('travel_plan_id');
                $table->string('nama_pengeluaran');
                $table->decimal('jumlah', 12, 2);
                $table->date('tanggal');
                $table->string('kategori')->nullable();
                $table->timestamps();

                $userPk = Schema::hasColumn('users', 'id_user') ? 'id_user' : 'id';
                $planPk = Schema::hasColumn('travel_plans', 'id_perencanaan') ? 'id_perencanaan' : 'id';

                $table->foreign('user_id')->references($userPk)->on('users')->cascadeOnDelete();
                $table->foreign('travel_plan_id')->references($planPk)->on('travel_plans')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};