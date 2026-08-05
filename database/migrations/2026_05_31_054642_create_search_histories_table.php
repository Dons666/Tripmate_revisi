<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('search_histories')) {
            Schema::create('search_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('destinasi_id')->nullable();
                $table->string('keyword_search');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('search_histories');
    }
};