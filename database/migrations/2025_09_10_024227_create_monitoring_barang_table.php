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
        Schema::create('monitoring_barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('nama_pengambil');
            $table->string('bidang');
            $table->date('tanggal_ambil');
            $table->integer('saldo'); // stok sebelum kredit
            $table->integer('saldo_akhir'); // stok setelah kredit
            $table->integer('kredit'); // pengeluaran barang
            $table->enum('status', ['diajukan', 'diterima'])->default('diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_barang');
    }
};
