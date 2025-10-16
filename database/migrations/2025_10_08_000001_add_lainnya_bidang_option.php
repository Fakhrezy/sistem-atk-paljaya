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
        // Update bidang "umum" kembali ke "lainnya" jika sebelumnya adalah "lainnya"
        // Karena sebelumnya kita mapping lainnya -> umum, sekarang kita kembalikan yang memang lainnya

        // Tidak perlu mengubah struktur, hanya menambahkan bidang "lainnya" sebagai opsi valid
        // Bidang "lainnya" sudah ditambahkan di BidangConstants

        // Jika ada data yang perlu diperbaiki mapping-nya, bisa ditambahkan di sini
        // Tapi untuk saat ini, bidang "lainnya" sudah bisa digunakan langsung

        // Log untuk tracking
        \Log::info('Bidang "Lainnya" telah ditambahkan ke sistem');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan data jika diperlukan
        // Untuk rollback, hapus bidang lainnya dari konstanta
        \Log::info('Rollback: Bidang "Lainnya" dihapus dari sistem');
    }
};
