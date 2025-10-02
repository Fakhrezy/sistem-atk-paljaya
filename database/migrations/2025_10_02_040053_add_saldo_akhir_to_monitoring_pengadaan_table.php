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
            $table->integer('saldo_akhir')->default(0)->after('debit')->comment('Saldo stok setelah pengadaan selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_pengadaan', function (Blueprint $table) {
            $table->dropColumn('saldo_akhir');
        });
    }
};
