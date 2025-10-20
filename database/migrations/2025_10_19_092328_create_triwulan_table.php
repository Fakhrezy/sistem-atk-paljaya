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
        Schema::create('triwulan', function (Blueprint $table) {
            $table->id();

            // Referensi ke tabel barang
            $table->string('id_barang')->comment('Foreign key ke tabel barang');
            $table->string('nama_barang')->comment('Referensi nama barang dari tabel barang');
            $table->string('satuan')->comment('Referensi satuan dari tabel barang');
            $table->decimal('harga_satuan', 15, 2)->comment('Referensi harga barang dari tabel barang');

            // Data triwulan
            $table->integer('tahun')->comment('Tahun triwulan (misal: 2025)');
            $table->integer('triwulan')->comment('Nomor triwulan (1-4)');

            // Data perhitungan dari detail_monitoring_barang
            $table->integer('saldo_awal_triwulan')->default(0)->comment('Stok barang pada awal triwulan');
            $table->integer('total_kredit_triwulan')->default(0)->comment('Total kredit selama 1 triwulan');
            $table->decimal('total_harga_kredit', 15, 2)->default(0)->comment('harga_satuan * total_kredit_triwulan');
            $table->integer('total_debit_triwulan')->default(0)->comment('Total debit selama 1 triwulan');
            $table->decimal('total_harga_debit', 15, 2)->default(0)->comment('harga_satuan * total_debit_triwulan');
            $table->integer('total_persediaan_triwulan')->default(0)->comment('saldo_awal + debit - kredit');
            $table->decimal('total_harga_persediaan', 15, 2)->default(0)->comment('harga_satuan * total_persediaan_triwulan');

            // Indexing untuk performa
            $table->index(['tahun', 'triwulan']);
            $table->index('id_barang');
            $table->unique(['id_barang', 'tahun', 'triwulan'], 'unique_barang_triwulan');

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triwulan');
    }
};
