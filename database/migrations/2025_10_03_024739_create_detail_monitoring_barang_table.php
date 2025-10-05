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
        Schema::create('detail_monitoring_barang', function (Blueprint $table) {
            $table->id();

            // Fields wajib (NOT NULL)
            $table->string('nama_barang')->comment('Referensi nama barang dari tabel barang');
            $table->date('tanggal')->comment('Tanggal dari monitoring barang atau monitoring pengadaan');
            $table->integer('saldo')->default(0)->comment('Referensi stok dari tabel barang');

            // Fields opsional (NULLABLE)
            $table->text('keterangan')->nullable()->comment('Referensi keterangan dari monitoring barang atau monitoring pengadaan');
            $table->string('bidang')->nullable()->comment('Referensi bidang dari monitoring barang');
            $table->string('pengambil')->nullable()->comment('Referensi nama pengambil dari monitoring barang');
            $table->integer('debit')->nullable()->default(0)->comment('Referensi debit dari monitoring pengadaan');
            $table->integer('kredit')->nullable()->default(0)->comment('Referensi kredit dari monitoring barang');

            // Foreign keys untuk referensi
            $table->string('id_barang')->nullable()->comment('Foreign key ke tabel barang');
            $table->unsignedBigInteger('monitoring_barang_id')->nullable()->comment('Foreign key ke monitoring_barang');
            $table->unsignedBigInteger('monitoring_pengadaan_id')->nullable()->comment('Foreign key ke monitoring_pengadaan');

            // Indexing untuk performa
            $table->index(['tanggal', 'nama_barang']);
            $table->index('id_barang');
            $table->index('monitoring_barang_id');
            $table->index('monitoring_pengadaan_id');

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreign('monitoring_barang_id')->references('id')->on('monitoring_barang')->onDelete('cascade');
            $table->foreign('monitoring_pengadaan_id')->references('id')->on('monitoring_pengadaan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_monitoring_barang');
    }
};
