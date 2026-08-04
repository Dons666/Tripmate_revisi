<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jarak_destinasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asal_id');
            $table->unsignedBigInteger('tujuan_id');
            $table->double('jarak')->comment('dalam kilometer');
            $table->integer('durasi')->comment('dalam detik');
            $table->timestamps();

            $table->unique(['asal_id', 'tujuan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jarak_destinasi');
    }
};
