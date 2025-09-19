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
        Schema::create('usulan_pengadaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('jenis');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->string('bidang');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['diajukan', 'diproses', 'diterima', 'ditolak'])->default('diajukan');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_pengadaan');
    }
};
