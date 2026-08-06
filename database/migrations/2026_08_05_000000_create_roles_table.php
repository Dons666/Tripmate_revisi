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
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('role', 50);
                $table->timestamps();

                $userPkColumn = Schema::hasColumn('users', 'id_user') ? 'id_user' : 'id';
                $table->foreign('user_id')->references($userPkColumn)->on('users')->onDelete('cascade');
                $table->unique(['user_id', 'role']);
            });
        }

        // Seed roles table from existing users
        if (Schema::hasColumn('users', 'role')) {
            $userPkColumn = Schema::hasColumn('users', 'id_user') ? 'id_user' : 'id';
            $existingUsers = DB::table('users')->whereNotNull('role')->get([$userPkColumn . ' as id', 'role', 'created_at', 'updated_at']);
            $now = now();

            foreach ($existingUsers as $user) {
                if (!empty($user->role)) {
                    DB::table('roles')->updateOrInsert(
                        [
                            'user_id' => $user->id,
                            'role' => strtolower(trim($user->role)),
                        ],
                        [
                            'created_at' => $user->created_at ?? $now,
                            'updated_at' => $user->updated_at ?? $now,
                        ]
                    );
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
