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
        // Tabel carts sudah dihapus secara manual
        // Migration ini hanya untuk mencatat bahwa tabel sudah dihapus
        if (Schema::hasTable('carts')) {
            Schema::dropIfExists('carts');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate carts table if needed for rollback
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('bidang', ['umum', 'perencanaan', 'keuangan', 'operasional', 'lainnya']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }
};
