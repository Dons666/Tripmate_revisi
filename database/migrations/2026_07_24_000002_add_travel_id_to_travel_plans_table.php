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
        Schema::table('travel_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_plans', 'travel_id')) {
                $afterCol = Schema::hasColumn('travel_plans', 'id_user') ? 'id_user' : (Schema::hasColumn('travel_plans', 'user_id') ? 'user_id' : 'id_perencanaan');
                $table->unsignedBigInteger('travel_id')->nullable()->after($afterCol);
            }
            if (!Schema::hasColumn('travel_plans', 'is_checkout')) {
                $table->boolean('is_checkout')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            $cols = array_filter(['travel_id', 'is_checkout'], fn($c) => Schema::hasColumn('travel_plans', $c));
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
