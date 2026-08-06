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
        // 1. Tabel Kategori Wisata
        Schema::create('kategori_wisata', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Kategori Penginapan
        Schema::create('kategori_penginapan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Kategori Kuliner
        Schema::create('kategori_kuliner', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 4. Foreign Key Columns di Tabel Destinasi
        Schema::table('destinasi', function (Blueprint $table) {
            $table->foreignId('kategori_wisata_id')
                ->nullable()
                ->after('kategori')
                ->constrained('kategori_wisata')
                ->nullOnDelete();

            $table->foreignId('kategori_penginapan_id')
                ->nullable()
                ->after('kategori_wisata_id')
                ->constrained('kategori_penginapan')
                ->nullOnDelete();

            $table->foreignId('kategori_kuliner_id')
                ->nullable()
                ->after('kategori_penginapan_id')
                ->constrained('kategori_kuliner')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinasi', function (Blueprint $table) {
            $table->dropForeign(['kategori_wisata_id']);
            $table->dropForeign(['kategori_penginapan_id']);
            $table->dropForeign(['kategori_kuliner_id']);

            $table->dropColumn([
                'kategori_wisata_id',
                'kategori_penginapan_id',
                'kategori_kuliner_id',
            ]);
        });

        Schema::dropIfExists('kategori_kuliner');
        Schema::dropIfExists('kategori_penginapan');
        Schema::dropIfExists('kategori_wisata');
    }
};
