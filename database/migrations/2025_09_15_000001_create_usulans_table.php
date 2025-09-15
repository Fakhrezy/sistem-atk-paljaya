<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('barang_id');
            $table->integer('jumlah');
            $table->string('keterangan');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('barang_id')
                  ->references('id_barang')
                  ->on('barang')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usulans');
    }
};
