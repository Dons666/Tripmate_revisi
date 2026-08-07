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
        Schema::disableForeignKeyConstraints();

        // 1. Wisata
        if (Schema::hasTable('wisata') && Schema::hasColumn('wisata', 'id_wisata') && !Schema::hasColumn('wisata', 'id')) {
            DB::statement("ALTER TABLE `wisata` CHANGE `id_wisata` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        // 2. Penginapan
        if (Schema::hasTable('penginapan') && Schema::hasColumn('penginapan', 'id_penginapan') && !Schema::hasColumn('penginapan', 'id')) {
            DB::statement("ALTER TABLE `penginapan` CHANGE `id_penginapan` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        // 3. Kuliner
        if (Schema::hasTable('kuliner') && Schema::hasColumn('kuliner', 'id_kuliner') && !Schema::hasColumn('kuliner', 'id')) {
            DB::statement("ALTER TABLE `kuliner` CHANGE `id_kuliner` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        // 4. Users
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'id_user') && !Schema::hasColumn('users', 'id')) {
            DB::statement("ALTER TABLE `users` CHANGE `id_user` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        // 5. Travel Plans
        if (Schema::hasTable('travel_plans') && Schema::hasColumn('travel_plans', 'id_perencanaan') && !Schema::hasColumn('travel_plans', 'id')) {
            DB::statement("ALTER TABLE `travel_plans` CHANGE `id_perencanaan` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        // 6. Ratings
        if (Schema::hasTable('ratings') && Schema::hasColumn('ratings', 'id_ulasan') && !Schema::hasColumn('ratings', 'id')) {
            DB::statement("ALTER TABLE `ratings` CHANGE `id_ulasan` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        // 7. Bookmarks
        if (Schema::hasTable('bookmarks') && Schema::hasColumn('bookmarks', 'id_bookmark') && !Schema::hasColumn('bookmarks', 'id')) {
            DB::statement("ALTER TABLE `bookmarks` CHANGE `id_bookmark` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        // 8. Appeals
        if (Schema::hasTable('appeals') && Schema::hasColumn('appeals', 'id_appeals') && !Schema::hasColumn('appeals', 'id')) {
            DB::statement("ALTER TABLE `appeals` CHANGE `id_appeals` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        if (Schema::hasTable('wisata') && Schema::hasColumn('wisata', 'id')) {
            DB::statement("ALTER TABLE `wisata` CHANGE `id` `id_wisata` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }
        if (Schema::hasTable('penginapan') && Schema::hasColumn('penginapan', 'id')) {
            DB::statement("ALTER TABLE `penginapan` CHANGE `id` `id_penginapan` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }
        if (Schema::hasTable('kuliner') && Schema::hasColumn('kuliner', 'id')) {
            DB::statement("ALTER TABLE `kuliner` CHANGE `id` `id_kuliner` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'id')) {
            DB::statement("ALTER TABLE `users` CHANGE `id` `id_user` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }
        if (Schema::hasTable('travel_plans') && Schema::hasColumn('travel_plans', 'id')) {
            DB::statement("ALTER TABLE `travel_plans` CHANGE `id` `id_perencanaan` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }
        if (Schema::hasTable('ratings') && Schema::hasColumn('ratings', 'id')) {
            DB::statement("ALTER TABLE `ratings` CHANGE `id` `id_ulasan` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }
        if (Schema::hasTable('bookmarks') && Schema::hasColumn('bookmarks', 'id')) {
            DB::statement("ALTER TABLE `bookmarks` CHANGE `id` `id_bookmark` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }
        if (Schema::hasTable('appeals') && Schema::hasColumn('appeals', 'id')) {
            DB::statement("ALTER TABLE `appeals` CHANGE `id` `id_appeals` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        Schema::enableForeignKeyConstraints();
    }
};
