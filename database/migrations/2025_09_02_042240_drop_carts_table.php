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
        Schema::dropIfExists('carts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
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
