<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petugas_pengambil_contoh', function (Blueprint $table) {
            $table->id('id_ppc');
            $table->string('nama_ppc', 100);
            $table->string('jabatan', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas_pengambil_contoh');
    }
};
