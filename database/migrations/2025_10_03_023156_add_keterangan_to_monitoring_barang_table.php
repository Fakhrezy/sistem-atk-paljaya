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
        Schema::table('monitoring_barang', function (Blueprint $table) {
            $table->text('keterangan')->nullable()->after('status')->comment('Keterangan tambahan dari pengambilan barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_barang', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};
