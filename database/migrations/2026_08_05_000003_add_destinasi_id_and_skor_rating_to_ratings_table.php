<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            if (!Schema::hasColumn('ratings', 'destinasi_id')) {
                $table->unsignedBigInteger('destinasi_id')->nullable()->after('id_user');
            }
            if (!Schema::hasColumn('ratings', 'skor_rating')) {
                $table->decimal('skor_rating', 3, 2)->nullable()->after('rating');
            }
        });

        // Copy existing values if columns exist
        if (Schema::hasColumn('ratings', 'id_wisata') && Schema::hasColumn('ratings', 'destinasi_id')) {
            DB::statement("UPDATE ratings SET destinasi_id = id_wisata WHERE destinasi_id IS NULL AND id_wisata IS NOT NULL");
        }
        if (Schema::hasColumn('ratings', 'rating') && Schema::hasColumn('ratings', 'skor_rating')) {
            DB::statement("UPDATE ratings SET skor_rating = rating WHERE skor_rating IS NULL AND rating IS NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $cols = array_filter(['destinasi_id', 'skor_rating'], fn($c) => Schema::hasColumn('ratings', $c));
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
