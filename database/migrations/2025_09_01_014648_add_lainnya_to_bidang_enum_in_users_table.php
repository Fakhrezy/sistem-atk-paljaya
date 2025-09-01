<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('bidang_new', ['teknik', 'pemasaran', 'umum', 'keuangan', 'lainnya'])->default('umum')->after('bidang');
        });

        // Copy data from old column to new column
        DB::statement('UPDATE users SET bidang_new = bidang');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bidang');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('bidang_new', 'bidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('bidang_old', ['teknik', 'pemasaran', 'umum', 'keuangan'])->default('umum')->after('bidang');
        });

        // Copy data from current column to old column, converting 'lainnya' to 'umum'
        DB::statement("UPDATE users SET bidang_old = CASE WHEN bidang = 'lainnya' THEN 'umum' ELSE bidang END");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bidang');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('bidang_old', 'bidang');
        });
    }
};
