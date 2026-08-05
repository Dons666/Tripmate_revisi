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
        if (!Schema::hasTable('destinasi_travel')) {
            Schema::create('destinasi_travel', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('travel_id');
                $table->unsignedBigInteger('destinasi_id');
                $table->timestamps();

                $table->foreign('travel_id')->references('id')->on('travels')->cascadeOnDelete();
                $table->foreign('destinasi_id')->references('id')->on('destinasi')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinasi_travel');
    }
};
