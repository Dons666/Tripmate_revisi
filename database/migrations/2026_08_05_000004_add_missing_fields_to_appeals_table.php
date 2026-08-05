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
        Schema::table('appeals', function (Blueprint $table) {
            if (!Schema::hasColumn('appeals', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id_appeals');
            }
            if (!Schema::hasColumn('appeals', 'reason')) {
                $table->text('reason')->nullable()->after('email');
            }
            if (!Schema::hasColumn('appeals', 'status')) {
                $table->string('status')->default('pending')->after('reason');
            }
            if (!Schema::hasColumn('appeals', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('appeals', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('admin_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appeals', function (Blueprint $table) {
            $cols = array_filter(['user_id', 'reason', 'status', 'admin_notes', 'is_read'], fn($c) => Schema::hasColumn('appeals', $c));
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
