<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('destinasi_id');
                $table->decimal('skor_rating', 3, 2);
                $table->text('komentar')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'destinasi_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};