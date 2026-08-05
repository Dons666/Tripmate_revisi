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
        if (!Schema::hasTable('penyedia_travel')) {
            Schema::create('penyedia_travel', function (Blueprint $table) {
                $table->id();
                $table->string('nama_travel');
                $table->string('email')->nullable();
                $table->string('password')->nullable();
                $table->text('alamat_travel')->nullable();
                $table->string('kota_asal_travel')->nullable();
                $table->string('jenis_kendaraan')->nullable();
                $table->decimal('harga', 12, 2)->nullable()->default(0);
                $table->text('jadwal_ketersediaan')->nullable();
                $table->string('rekening')->nullable();
                $table->string('surat_izin_usaha_travel');
                $table->string('ktp_pemilik');
                $table->string('nomor_hp_pemilik_travel');
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyedia_travel');
    }
};
