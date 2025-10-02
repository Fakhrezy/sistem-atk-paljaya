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
        // Step 1: First add 'selesai' to enum while keeping 'terima'
        DB::statement("ALTER TABLE monitoring_pengadaan MODIFY COLUMN status ENUM('proses', 'terima', 'selesai') DEFAULT 'proses'");

        // Step 2: Update existing 'terima' status to 'selesai'
        DB::statement("UPDATE monitoring_pengadaan SET status = 'selesai' WHERE status = 'terima'");

        // Step 3: Remove 'terima' from enum, keeping only 'proses' and 'selesai'
        DB::statement("ALTER TABLE monitoring_pengadaan MODIFY COLUMN status ENUM('proses', 'selesai') DEFAULT 'proses'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Add 'terima' back to enum
        DB::statement("ALTER TABLE monitoring_pengadaan MODIFY COLUMN status ENUM('proses', 'selesai', 'terima') DEFAULT 'proses'");

        // Step 2: Revert 'selesai' back to 'terima'
        DB::statement("UPDATE monitoring_pengadaan SET status = 'terima' WHERE status = 'selesai'");

        // Step 3: Remove 'selesai' from enum, keeping original values
        DB::statement("ALTER TABLE monitoring_pengadaan MODIFY COLUMN status ENUM('proses', 'terima') DEFAULT 'proses'");
    }
};
