<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikasi_tim_teknis', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sertifikasi');
            $table->unsignedBigInteger('id_teknis');
            $table->primary(['id_sertifikasi', 'id_teknis']);
            $table->foreign('id_sertifikasi')->references('id_sertifikasi')->on('sertifikasi')->onDelete('cascade');
            $table->foreign('id_teknis')->references('id_teknis')->on('tim_teknis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikasi_tim_teknis');
    }
};
