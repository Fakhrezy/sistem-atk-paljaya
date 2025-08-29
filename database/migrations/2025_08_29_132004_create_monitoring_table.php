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
        Schema::create('monitoring', function (Blueprint $table) {
            $table->string('id_monitoring')->primary();
            $table->timestamp('tanggal');
            $table->string('keperluan');
            $table->string('pengambil');
            $table->string('id_barang');
            $table->integer('debit')->default(0);
            $table->integer('kredit')->default(0);
            $table->integer('saldo');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring');
    }
};
