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
        if (!Schema::hasTable('armadas')) {
            Schema::create('armadas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('nama_kendaraan');
                $table->string('nomor_polisi')->nullable();
                $table->integer('kapasitas_kursi')->default(1);
                $table->timestamps();

                $userPk = Schema::hasColumn('users', 'id_user') ? 'id_user' : 'id';
                $table->foreign('user_id')->references($userPk)->on('users')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('armadas');
    }
};
