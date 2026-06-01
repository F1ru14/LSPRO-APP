<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id('id_perusahaan');
            $table->unsignedBigInteger('id_kota')->nullable();
            $table->string('nama_perusahaan', 200);
            $table->text('alamat_kantor')->nullable();
            $table->string('telp_kantor', 20)->nullable();
            $table->string('fax_kantor', 20)->nullable();
            $table->text('alamat_pabrik')->nullable();
            $table->string('telp_pabrik', 20)->nullable();
            $table->string('fax_pabrik', 20)->nullable();
            $table->text('alamat_importir')->nullable();
            $table->string('nama_importir', 100)->nullable();
            $table->string('telp_importir', 20)->nullable();
            $table->string('fax_importir', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('contact_person', 50)->nullable();
            $table->string('telp_cp', 20)->nullable();
            $table->string('merek', 100)->nullable();
            $table->string('komoditi', 100)->nullable();
            $table->string('tipe_produk', 100)->nullable();
            $table->timestamps();
            $table->foreign('id_kota')->references('id_kota')->on('kota')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
