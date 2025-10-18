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
        Schema::create('laporan_triwulan', function (Blueprint $table) {
            $table->id();
            $table->string('barang_id'); // ID barang dari tabel barang
            $table->string('nama_barang'); // Cache nama barang
            $table->string('satuan'); // Cache satuan
            $table->year('tahun'); // Tahun laporan
            $table->tinyInteger('triwulan')->comment('1=Q1, 2=Q2, 3=Q3, 4=Q4'); // Triwulan (1-4)
            $table->integer('saldo_akhir_sebelumnya')->default(0); // Saldo akhir triwulan sebelumnya
            $table->integer('jumlah_pengadaan')->default(0); // Total debit dalam triwulan
            $table->decimal('harga_satuan', 15, 2); // Harga per unit
            $table->decimal('jumlah_harga', 20, 2)->default(0); // harga_satuan * saldo_akhir_sebelumnya
            $table->integer('jumlah_pemakaian')->default(0); // Total kredit dalam triwulan
            $table->integer('saldo_tersedia')->default(0); // Stok tersedia akhir triwulan
            $table->decimal('total_harga', 20, 2)->default(0); // harga_satuan * saldo_tersedia
            $table->timestamps();

            // Index untuk query yang lebih cepat
            $table->index(['tahun', 'triwulan']);
            $table->index(['barang_id', 'tahun', 'triwulan']);
            $table->unique(['barang_id', 'tahun', 'triwulan']); // Prevent duplicate per barang per periode

            // Foreign key constraint
            $table->foreign('barang_id')->references('id_barang')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_triwulan');
    }
};
