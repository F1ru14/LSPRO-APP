<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveillance', function (Blueprint $table) {
            $table->id('id_surveillance');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_sertifikasi')->nullable();
            $table->unsignedBigInteger('id_lab')->nullable();
            $table->integer('periode')->nullable();
            $table->date('tgl_pelaksanaan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->foreign('id_sertifikasi')->references('id_sertifikasi')->on('sertifikasi')->onDelete('set null');
            $table->foreign('id_lab')->references('id_lab')->on('lab')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveillance');
    }
};
