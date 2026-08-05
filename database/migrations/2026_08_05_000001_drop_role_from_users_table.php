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
        if (Schema::hasColumn('users', 'role')) {
            // Ensure existing user roles are copied to roles weak entity table
            if (Schema::hasTable('roles')) {
                $userPkColumn = Schema::hasColumn('users', 'id_user') ? 'id_user' : 'id';
                $users = DB::table('users')->whereNotNull('role')->get([$userPkColumn . ' as id', 'role', 'created_at', 'updated_at']);
                $now = now();
                foreach ($users as $user) {
                    if (!empty($user->role)) {
                        DB::table('roles')->updateOrInsert(
                            [
                                'user_id' => $user->id,
                                'role'    => strtolower(trim($user->role)),
                            ],
                            [
                                'created_at' => $user->created_at ?? $now,
                                'updated_at' => $user->updated_at ?? $now,
                            ]
                        );
                    }
                }
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 50)->default('user')->after('password');
            });
        }
    }
};
