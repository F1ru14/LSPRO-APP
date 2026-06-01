<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id('id_surat');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_surveillance')->nullable();
            $table->string('jenis_surat', 100)->nullable();
            $table->date('tgl_terbit')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->foreign('id_surveillance')->references('id_surveillance')->on('surveillance')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
