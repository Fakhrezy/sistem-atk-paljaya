<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Daftar bidang baru yang konsisten
        $newBidangList = [
            'keuangan',
            'strategi_korporasi',
            'pengelolaan_limbah_b3_transportasi',
            'sekretaris_perusahaan',
            'umum',
            'tu_hukum',
            'op',
            'teknik',
            'pemasaran',
            'mmk3l',
            'spi'
        ];

        // Update table users - ganti enum dengan string
        Schema::table('users', function (Blueprint $table) {
            $table->string('bidang_new')->nullable()->after('bidang');
        });

        // Mapping bidang lama ke bidang baru
        $mappingBidang = [
            'teknik' => 'teknik',
            'pemasaran' => 'pemasaran',
            'umum' => 'umum',
            'keuangan' => 'keuangan',
            'lainnya' => 'umum',
            'perencanaan' => 'strategi_korporasi',
            'operasional' => 'op'
        ];

        // Update data users berdasarkan mapping
        foreach ($mappingBidang as $old => $new) {
            DB::statement("UPDATE users SET bidang_new = ? WHERE bidang = ?", [$new, $old]);
        }

        // Set default untuk yang null
        DB::statement("UPDATE users SET bidang_new = 'umum' WHERE bidang_new IS NULL");

        // Drop kolom lama dan rename kolom baru
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bidang');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('bidang_new', 'bidang');
        });

        // Update table monitoring_barang jika ada
        if (Schema::hasTable('monitoring_barang')) {
            Schema::table('monitoring_barang', function (Blueprint $table) {
                $table->string('bidang_new')->nullable()->after('bidang');
            });

            // Update data monitoring_barang
            foreach ($mappingBidang as $old => $new) {
                DB::statement("UPDATE monitoring_barang SET bidang_new = ? WHERE bidang = ?", [$new, $old]);
            }

            DB::statement("UPDATE monitoring_barang SET bidang_new = 'umum' WHERE bidang_new IS NULL");

            Schema::table('monitoring_barang', function (Blueprint $table) {
                $table->dropColumn('bidang');
            });

            Schema::table('monitoring_barang', function (Blueprint $table) {
                $table->renameColumn('bidang_new', 'bidang');
            });
        }

        // Update table detail_monitoring_barang jika ada
        if (Schema::hasTable('detail_monitoring_barang')) {
            Schema::table('detail_monitoring_barang', function (Blueprint $table) {
                $table->string('bidang_new')->nullable()->after('bidang');
            });

            // Update data detail_monitoring_barang
            foreach ($mappingBidang as $old => $new) {
                DB::statement("UPDATE detail_monitoring_barang SET bidang_new = ? WHERE bidang = ?", [$new, $old]);
            }

            DB::statement("UPDATE detail_monitoring_barang SET bidang_new = 'umum' WHERE bidang_new IS NULL");

            Schema::table('detail_monitoring_barang', function (Blueprint $table) {
                $table->dropColumn('bidang');
            });

            Schema::table('detail_monitoring_barang', function (Blueprint $table) {
                $table->renameColumn('bidang_new', 'bidang');
            });
        }

        // Update table usulan_pengadaan jika ada
        if (Schema::hasTable('usulan_pengadaan')) {
            Schema::table('usulan_pengadaan', function (Blueprint $table) {
                $table->string('bidang_new')->nullable()->after('bidang');
            });

            // Update data usulan_pengadaan
            foreach ($mappingBidang as $old => $new) {
                DB::statement("UPDATE usulan_pengadaan SET bidang_new = ? WHERE bidang = ?", [$new, $old]);
            }

            DB::statement("UPDATE usulan_pengadaan SET bidang_new = 'umum' WHERE bidang_new IS NULL");

            Schema::table('usulan_pengadaan', function (Blueprint $table) {
                $table->dropColumn('bidang');
            });

            Schema::table('usulan_pengadaan', function (Blueprint $table) {
                $table->renameColumn('bidang_new', 'bidang');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback mapping (bidang baru ke bidang lama)
        $rollbackMapping = [
            'teknik' => 'teknik',
            'pemasaran' => 'pemasaran',
            'umum' => 'umum',
            'keuangan' => 'keuangan',
            'strategi_korporasi' => 'perencanaan',
            'op' => 'operasional',
            'pengelolaan_limbah_b3_transportasi' => 'operasional',
            'sekretaris_perusahaan' => 'umum',
            'tu_hukum' => 'umum',
            'mmk3l' => 'operasional',
            'spi' => 'umum'
        ];

        // Rollback untuk setiap table
        $tables = ['users', 'monitoring_barang', 'detail_monitoring_barang', 'usulan_pengadaan'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'bidang')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('bidang_old')->nullable()->after('bidang');
                });

                foreach ($rollbackMapping as $new => $old) {
                    DB::statement("UPDATE {$tableName} SET bidang_old = ? WHERE bidang = ?", [$old, $new]);
                }

                DB::statement("UPDATE {$tableName} SET bidang_old = 'umum' WHERE bidang_old IS NULL");

                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('bidang');
                });

                Schema::table($tableName, function (Blueprint $table) {
                    $table->renameColumn('bidang_old', 'bidang');
                });
            }
        }
    }
};
