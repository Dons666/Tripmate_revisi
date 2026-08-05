<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('jarak_destinasi');
    }

    public function down(): void
    {
        // Option to recreate it, although practically we're dropping it forever
        Schema::create('jarak_destinasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asal_id');
            $table->unsignedBigInteger('tujuan_id');
            $table->decimal('jarak', 10, 2)->default(0);
            $table->integer('durasi')->default(0);
            $table->timestamps();
        });
    }
};
