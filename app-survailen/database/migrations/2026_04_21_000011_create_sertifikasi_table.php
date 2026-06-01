<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikasi', function (Blueprint $table) {
            $table->id('id_sertifikasi');
            $table->unsignedBigInteger('id_perusahaan')->nullable();
            $table->unsignedBigInteger('id_lab')->nullable();
            $table->string('no_referensi', 100)->nullable();
            $table->string('no_sni', 100)->nullable();
            $table->date('tgl_permohonan')->nullable();
            $table->date('tgl_kontrak')->nullable();
            $table->date('tgl_audit_kecukupan')->nullable();
            $table->date('tgl_pemberitahuan_verifikasi')->nullable();
            $table->date('tgl_mulai_audit_lapangan')->nullable();
            $table->date('tgl_selesai_audit_lapangan')->nullable();
            $table->date('tgl_rapat_teknis')->nullable();
            $table->date('tgl_sertifikasi')->nullable();
            $table->string('lama_sertifikasi', 100)->nullable();
            $table->string('status_permohonan', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan')->onDelete('set null');
            $table->foreign('id_lab')->references('id_lab')->on('lab')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikasi');
    }
};
