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
        if (Schema::hasColumn('kategori_wisata', 'deskripsi')) {
            Schema::table('kategori_wisata', function (Blueprint $table) {
                $table->dropColumn('deskripsi');
            });
        }

        if (Schema::hasColumn('kategori_penginapan', 'deskripsi')) {
            Schema::table('kategori_penginapan', function (Blueprint $table) {
                $table->dropColumn('deskripsi');
            });
        }

        if (Schema::hasColumn('kategori_kuliner', 'deskripsi')) {
            Schema::table('kategori_kuliner', function (Blueprint $table) {
                $table->dropColumn('deskripsi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('kategori_wisata', 'deskripsi')) {
            Schema::table('kategori_wisata', function (Blueprint $table) {
                $table->text('deskripsi')->nullable()->after('nama_kategori');
            });
        }

        if (!Schema::hasColumn('kategori_penginapan', 'deskripsi')) {
            Schema::table('kategori_penginapan', function (Blueprint $table) {
                $table->text('deskripsi')->nullable()->after('nama_kategori');
            });
        }

        if (!Schema::hasColumn('kategori_kuliner', 'deskripsi')) {
            Schema::table('kategori_kuliner', function (Blueprint $table) {
                $table->text('deskripsi')->nullable()->after('nama_kategori');
            });
        }
    }
};
