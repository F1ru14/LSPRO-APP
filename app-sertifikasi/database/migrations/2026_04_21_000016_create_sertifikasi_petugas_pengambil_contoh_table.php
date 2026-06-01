<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikasi_petugas_pengambil_contoh', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sertifikasi');
            $table->unsignedBigInteger('id_ppc');
            $table->primary(['id_sertifikasi', 'id_ppc']);
            $table->foreign('id_sertifikasi')->references('id_sertifikasi')->on('sertifikasi')->onDelete('cascade');
            $table->foreign('id_ppc')->references('id_ppc')->on('petugas_pengambil_contoh')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikasi_petugas_pengambil_contoh');
    }
};
