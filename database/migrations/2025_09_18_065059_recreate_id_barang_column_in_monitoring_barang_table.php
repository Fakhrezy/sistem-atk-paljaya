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
            // Drop kolom dan foreign key yang ada
            $table->dropForeign(['id_barang']);
            $table->dropColumn('id_barang');

            // Buat ulang kolom dengan nullable
            $table->string('id_barang')->nullable()->after('id');

            // Tambahkan foreign key
            $table->foreign('id_barang')
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
        Schema::table('monitoring_barang', function (Blueprint $table) {
            $table->dropForeign(['id_barang']);
            $table->dropColumn('id_barang');

            // Buat ulang kolom tanpa nullable
            $table->string('id_barang')->after('id');

            // Tambahkan foreign key
            $table->foreign('id_barang')
                  ->references('id_barang')
                  ->on('barang')
                  ->onDelete('cascade');
        });
    }
};
