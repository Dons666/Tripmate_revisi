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
        if (!Schema::hasTable('admin_logs')) {
            Schema::create('admin_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('admin_name')->nullable();
                $table->string('action'); // create, update, delete, approve, reject, verify, warning
                $table->string('entity_type'); // destinasi, kuliner, penginapan, user, appeal, penyedia_travel, escrow, komentar
                $table->unsignedBigInteger('entity_id')->nullable();
                $table->string('entity_name')->nullable();
                $table->string('summary');
                $table->json('changes')->nullable();
                $table->string('location')->nullable();
                $table->timestamp('changed_at')->nullable();
                $table->timestamps();

                $userPk = Schema::hasColumn('users', 'id_user') ? 'id_user' : 'id';
                $table->foreign('user_id')->references($userPk)->on('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
