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
        if (!Schema::hasTable('appeals')) {
            Schema::create('appeals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('email');
                $table->text('reason');
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->text('admin_notes')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamps();

                $userPk = Schema::hasColumn('users', 'id_user') ? 'id_user' : 'id';
                $table->foreign('user_id')->references($userPk)->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appeals');
    }
};
