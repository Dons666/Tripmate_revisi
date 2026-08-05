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
        if (Schema::hasColumn('ratings', 'destinasi_id')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->unsignedBigInteger('destinasi_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('ratings', 'destinasi_id')) {
            Schema::table('ratings', function (Blueprint $table) {
                $table->unsignedBigInteger('destinasi_id')->nullable(false)->change();
            });
        }
    }
};
