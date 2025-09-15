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
        // Drop the table if it exists
        Schema::dropIfExists('monitoring_pengadaan');

        Schema::create('monitoring_pengadaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('barang_id');
            $table->integer('debit');
            $table->string('keterangan')->nullable();
            $table->enum('status', ['proses', 'terima'])->default('proses');
            $table->timestamp('tanggal');
            $table->timestamps();

            $table->foreign('barang_id')
                  ->references('id_barang')
                  ->on('barang')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_pengadaan');
    }
};
