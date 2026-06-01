<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surveillance_petugas_pengambil_contoh', function (Blueprint $table) {
            $table->unsignedBigInteger('id_surveillance');
            $table->unsignedBigInteger('id_ppc');
            $table->primary(['id_surveillance', 'id_ppc']);

            $table->foreign('id_surveillance', 'fk_surv_ppc_surveillance')->references('id_surveillance')->on('surveillance')->onDelete('cascade');
            $table->foreign('id_ppc', 'fk_surv_ppc_ppc')->references('id_ppc')->on('petugas_pengambil_contoh')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveillance_petugas_pengambil_contoh');
    }
};
