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
        Schema::table('monitoring_pengadaan', function (Blueprint $table) {
            $table->integer('saldo')->default(0)->after('barang_id')->comment('Stok barang sebelum pengadaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_pengadaan', function (Blueprint $table) {
            $table->dropColumn('saldo');
        });
    }
};
