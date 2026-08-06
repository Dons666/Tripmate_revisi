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
        if (Schema::hasTable('user_preferences')) {
            Schema::table('user_preferences', function (Blueprint $table) {
                if (!Schema::hasColumn('user_preferences', 'minat_wisata')) {
                    $table->json('minat_wisata')->nullable()->after('kota_preferensi');
                }
                if (!Schema::hasColumn('user_preferences', 'hidden_gem')) {
                    $table->boolean('hidden_gem')->default(false)->after('minat_wisata');
                }
                $table->string('budget', 255)->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('user_preferences')) {
            Schema::table('user_preferences', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('user_preferences', 'minat_wisata')) $cols[] = 'minat_wisata';
                if (Schema::hasColumn('user_preferences', 'hidden_gem')) $cols[] = 'hidden_gem';

                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }
    }
};
