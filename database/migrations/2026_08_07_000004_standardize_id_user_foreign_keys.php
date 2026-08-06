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
        $tables = [
            'roles',
            'bookmarks',
            'search_histories',
            'travels',
            'expenses',
            'armadas',
            'appeals',
            'admin_logs',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                if (!Schema::hasColumn($tableName, 'id_user')) {
                    Schema::table($tableName, function (Blueprint $table) {
                        $table->unsignedBigInteger('id_user')->nullable();
                    });
                }

                // Populate id_user from user_id or admin_id
                $sourceCol = Schema::hasColumn($tableName, 'user_id') ? 'user_id' : (Schema::hasColumn($tableName, 'admin_id') ? 'admin_id' : null);
                if ($sourceCol) {
                    DB::statement("UPDATE `{$tableName}` SET `id_user` = `{$sourceCol}` WHERE `id_user` IS NULL AND `{$sourceCol}` IS NOT NULL");
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'roles',
            'bookmarks',
            'search_histories',
            'travels',
            'expenses',
            'armadas',
            'appeals',
            'admin_logs',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'id_user')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('id_user');
                });
            }
        }
    }
};
