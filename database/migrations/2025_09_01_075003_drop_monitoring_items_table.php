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
        Schema::dropIfExists('monitoring_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('monitoring_items', function (Blueprint $table) {
            $table->id();
            $table->string('id_monitoring');
            $table->string('id_barang');
            $table->integer('debit')->default(0);
            $table->integer('kredit')->default(0);
            $table->integer('saldo')->default(0);
            $table->text('keterangan_item')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_monitoring')->references('id_monitoring')->on('monitoring')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');

            // Index
            $table->index(['id_monitoring', 'id_barang']);
        });
    }
};
