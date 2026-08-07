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
        // 1. Tabel Wisata
        if (!Schema::hasTable('wisata')) {
            Schema::create('wisata', function (Blueprint $table) {
                $table->id();
                $table->string('nama_destinasi');
                $table->string('kota');
                $table->string('kategori')->nullable();
                $table->foreignId('kategori_wisata_id')->nullable()->constrained('kategori_wisata')->nullOnDelete();
                $table->decimal('harga', 12, 2)->default(0);
                $table->boolean('hidden_gem')->default(false);
                $table->decimal('rating_destinasi', 3, 2)->default(0.00);
                $table->text('deskripsi')->nullable();
                $table->text('fasilitas')->nullable();
                $table->text('alamat')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->text('transportasi')->nullable();
                $table->string('hari_operasional')->nullable();
                $table->string('jam_buka')->nullable();
                $table->string('jam_tutup')->nullable();
                $table->text('gambar')->nullable();
                $table->longText('fitur_cbf')->nullable();
                $table->timestamps();
            });
        }

        // 2. Tabel Penginapan
        if (!Schema::hasTable('penginapan')) {
            Schema::create('penginapan', function (Blueprint $table) {
                $table->id();
                $table->string('nama_destinasi');
                $table->string('kota');
                $table->string('kategori')->nullable();
                $table->foreignId('kategori_penginapan_id')->nullable()->constrained('kategori_penginapan')->nullOnDelete();
                $table->decimal('harga', 12, 2)->default(0);
                $table->boolean('hidden_gem')->default(false);
                $table->decimal('rating_destinasi', 3, 2)->default(0.00);
                $table->text('deskripsi')->nullable();
                $table->text('fasilitas')->nullable();
                $table->text('alamat')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->text('transportasi')->nullable();
                $table->string('hari_operasional')->nullable();
                $table->string('jam_buka')->nullable();
                $table->string('jam_tutup')->nullable();
                $table->text('gambar')->nullable();
                $table->longText('fitur_cbf')->nullable();
                $table->timestamps();
            });
        }

        // 3. Tabel Kuliner
        if (!Schema::hasTable('kuliner')) {
            Schema::create('kuliner', function (Blueprint $table) {
                $table->id();
                $table->string('nama_destinasi');
                $table->string('kota');
                $table->string('kategori')->nullable();
                $table->foreignId('kategori_kuliner_id')->nullable()->constrained('kategori_kuliner')->nullOnDelete();
                $table->decimal('harga', 12, 2)->default(0);
                $table->boolean('hidden_gem')->default(false);
                $table->decimal('rating_destinasi', 3, 2)->default(0.00);
                $table->text('deskripsi')->nullable();
                $table->text('fasilitas')->nullable();
                $table->text('alamat')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->text('transportasi')->nullable();
                $table->string('hari_operasional')->nullable();
                $table->string('jam_buka')->nullable();
                $table->string('jam_tutup')->nullable();
                $table->text('gambar')->nullable();
                $table->longText('fitur_cbf')->nullable();
                $table->timestamps();
            });
        }

        // Populate tables from existing destinasi table if present
        if (Schema::hasTable('destinasi')) {
            // Check if destinasi is a real table (not a view)
            $isView = false;
            try {
                $driver = DB::getDriverName();
                if ($driver === 'sqlite') {
                    $type = DB::select("SELECT type FROM sqlite_master WHERE name = 'destinasi'")[0]->type ?? 'table';
                    $isView = ($type === 'view');
                } else {
                    $tableType = DB::select("SELECT TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'destinasi'")[0]->TABLE_TYPE ?? 'BASE TABLE';
                    $isView = ($tableType === 'VIEW');
                }
            } catch (\Exception $e) {
                $isView = false;
            }

            if (!$isView) {
                $existingDestinasi = DB::table('destinasi')->get();

                foreach ($existingDestinasi as $d) {
                    $data = [
                        'id' => $d->id,
                        'nama_destinasi' => $d->nama_destinasi ?? '',
                        'kota' => $d->kota ?? '',
                        'kategori' => $d->kategori ?? null,
                        'harga' => $d->harga ?? 0,
                        'hidden_gem' => $d->hidden_gem ?? false,
                        'rating_destinasi' => $d->rating_destinasi ?? 0.00,
                        'deskripsi' => $d->deskripsi ?? null,
                        'fasilitas' => $d->fasilitas ?? null,
                        'alamat' => $d->alamat ?? null,
                        'latitude' => $d->latitude ?? null,
                        'longitude' => $d->longitude ?? null,
                        'transportasi' => $d->transportasi ?? null,
                        'hari_operasional' => $d->hari_operasional ?? null,
                        'jam_buka' => $d->jam_buka ?? null,
                        'jam_tutup' => $d->jam_tutup ?? null,
                        'gambar' => $d->gambar ?? null,
                        'fitur_cbf' => $d->fitur_cbf ?? null,
                        'created_at' => $d->created_at ?? now(),
                        'updated_at' => $d->updated_at ?? now(),
                    ];

                    $tipe = strtolower(trim((string) ($d->tipe ?? '')));

                    if ($tipe === 'wisata') {
                        $data['kategori_wisata_id'] = $d->kategori_wisata_id ?? null;
                        DB::table('wisata')->updateOrInsert(['id' => $data['id']], $data);
                    } elseif ($tipe === 'penginapan') {
                        $data['kategori_penginapan_id'] = $d->kategori_penginapan_id ?? null;
                        DB::table('penginapan')->updateOrInsert(['id' => $data['id']], $data);
                    } elseif ($tipe === 'kuliner') {
                        $data['kategori_kuliner_id'] = $d->kategori_kuliner_id ?? null;
                        DB::table('kuliner')->updateOrInsert(['id' => $data['id']], $data);
                    }
                }

                // Drop physical table destinasi with foreign key constraints disabled
                Schema::disableForeignKeyConstraints();
                Schema::dropIfExists('destinasi');
                Schema::enableForeignKeyConstraints();
            }
        }

        // Create view destinasi combining wisata, penginapan, and kuliner
        Schema::disableForeignKeyConstraints();
        DB::statement("DROP VIEW IF EXISTS destinasi");

        $wPk = Schema::hasColumn('wisata', 'id_wisata') ? 'id_wisata AS id' : 'id';
        $wNama = Schema::hasColumn('wisata', 'nama_destinasi') ? 'nama_destinasi' : 'nama AS nama_destinasi';
        $wKat = Schema::hasColumn('wisata', 'kategori_wisata') ? 'kategori_wisata AS kategori' : (Schema::hasColumn('wisata', 'kategori') ? 'kategori' : 'NULL AS kategori');
        $wKatId = Schema::hasColumn('wisata', 'kategori_wisata_id') ? 'kategori_wisata_id' : 'NULL AS kategori_wisata_id';

        $pPk = Schema::hasColumn('penginapan', 'id_penginapan') ? 'id_penginapan AS id' : 'id';
        $pNama = Schema::hasColumn('penginapan', 'nama_destinasi') ? 'nama_destinasi' : 'nama AS nama_destinasi';
        $pKat = Schema::hasColumn('penginapan', 'kategori_penginapan') ? 'kategori_penginapan AS kategori' : (Schema::hasColumn('penginapan', 'kategori') ? 'kategori' : 'NULL AS kategori');
        $pKatId = Schema::hasColumn('penginapan', 'kategori_penginapan_id') ? 'kategori_penginapan_id' : 'NULL AS kategori_penginapan_id';

        $kPk = Schema::hasColumn('kuliner', 'id_kuliner') ? 'id_kuliner AS id' : 'id';
        $kNama = Schema::hasColumn('kuliner', 'nama_destinasi') ? 'nama_destinasi' : 'nama AS nama_destinasi';
        $kKat = Schema::hasColumn('kuliner', 'kategori_kuliner') ? 'kategori_kuliner AS kategori' : (Schema::hasColumn('kuliner', 'kategori') ? 'kategori' : 'NULL AS kategori');
        $kKatId = Schema::hasColumn('kuliner', 'kategori_kuliner_id') ? 'kategori_kuliner_id' : 'NULL AS kategori_kuliner_id';

        DB::statement("
            CREATE VIEW destinasi AS 
            SELECT {$wPk}, {$wNama}, 'wisata' AS tipe, kota, {$wKat}, {$wKatId}, CAST(NULL AS UNSIGNED) AS kategori_penginapan_id, CAST(NULL AS UNSIGNED) AS kategori_kuliner_id, harga, deskripsi, fasilitas, alamat, latitude, longitude, transportasi, hari_operasional, jam_buka, jam_tutup, gambar, fitur_cbf, created_at, updated_at FROM wisata
            UNION ALL
            SELECT {$pPk}, {$pNama}, 'penginapan' AS tipe, kota, {$pKat}, CAST(NULL AS UNSIGNED) AS kategori_wisata_id, {$pKatId}, CAST(NULL AS UNSIGNED) AS kategori_kuliner_id, harga, deskripsi, fasilitas, alamat, latitude, longitude, transportasi, hari_operasional, jam_buka, jam_tutup, gambar, fitur_cbf, created_at, updated_at FROM penginapan
            UNION ALL
            SELECT {$kPk}, {$kNama}, 'kuliner' AS tipe, kota, {$kKat}, CAST(NULL AS UNSIGNED) AS kategori_wisata_id, CAST(NULL AS UNSIGNED) AS kategori_penginapan_id, {$kKatId}, harga, deskripsi, fasilitas, alamat, latitude, longitude, transportasi, hari_operasional, jam_buka, jam_tutup, gambar, fitur_cbf, created_at, updated_at FROM kuliner
        ");
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS destinasi");
        Schema::dropIfExists('kuliner');
        Schema::dropIfExists('penginapan');
        Schema::dropIfExists('wisata');
    }
};
