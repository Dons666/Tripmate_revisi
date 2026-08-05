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
        if (!Schema::hasTable('travels')) {
            Schema::create('travels', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('nama_travel');
                $table->string('slug')->unique();
                $table->string('layanan')->nullable();
                $table->text('deskripsi')->nullable();
                $table->decimal('harga_paket', 12, 2)->default(0);
                $table->decimal('rating', 3, 2)->default(4.5);
                $table->string('kota')->nullable();
                $table->string('kontak')->nullable();
                $table->text('gambar')->nullable();
                $table->timestamps();

                $userPk = Schema::hasColumn('users', 'id_user') ? 'id_user' : 'id';
                $table->foreign('user_id')->references($userPk)->on('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travels');
    }
};
