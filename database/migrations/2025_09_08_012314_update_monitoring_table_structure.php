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
        Schema::table('monitoring', function (Blueprint $table) {
            // Drop existing foreign key constraints if they exist
            try {
                $table->dropForeign(['id_barang']);
            } catch (Exception $e) {
                // Ignore if foreign key doesn't exist
            }

            // Remove unnecessary columns first
            if (Schema::hasColumn('monitoring', 'total_debit')) {
                $table->dropColumn('total_debit');
            }
            if (Schema::hasColumn('monitoring', 'total_kredit')) {
                $table->dropColumn('total_kredit');
            }
        });

        // Add new columns and constraints in separate operation
        Schema::table('monitoring', function (Blueprint $table) {
            // Add foreign key to pengambilan table
            $table->unsignedInteger('id_pengambilan')->after('id_monitoring')->nullable();

            // Modify existing columns to match requirements
            $table->string('bidang')->nullable()->change();
            $table->string('pengambil')->nullable()->change();
            $table->string('keterangan')->nullable()->change();
            $table->string('id_barang')->nullable()->change();
        });

        // Add foreign key constraints
        Schema::table('monitoring', function (Blueprint $table) {
            $table->foreign('id_pengambilan')->references('id_pengambilan')->on('pengambilan')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring', function (Blueprint $table) {
            // Remove foreign key constraints
            $table->dropForeign(['id_pengambilan']);
            $table->dropForeign(['id_barang']);

            // Drop id_pengambilan column
            $table->dropColumn('id_pengambilan');

            // Add back total columns
            $table->integer('total_debit')->default(0);
            $table->integer('total_kredit')->default(0);

            // Restore id_barang as nullable
            $table->string('id_barang')->nullable()->change();
        });
    }
};
