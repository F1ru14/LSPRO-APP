<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikasi_auditor', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sertifikasi');
            $table->unsignedBigInteger('id_auditor');
            $table->string('peran')->nullable();
            $table->primary(['id_sertifikasi', 'id_auditor']);
            $table->foreign('id_sertifikasi')->references('id_sertifikasi')->on('sertifikasi')->onDelete('cascade');
            $table->foreign('id_auditor')->references('id_auditor')->on('auditor')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikasi_auditor');
    }
};
