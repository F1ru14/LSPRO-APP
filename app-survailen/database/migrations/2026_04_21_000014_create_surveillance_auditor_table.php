<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveillance_auditor', function (Blueprint $table) {
            $table->unsignedBigInteger('id_surveillance');
            $table->unsignedBigInteger('id_auditor');
            $table->primary(['id_surveillance', 'id_auditor']);
            $table->foreign('id_surveillance')->references('id_surveillance')->on('surveillance')->onDelete('cascade');
            $table->foreign('id_auditor')->references('id_auditor')->on('auditor')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveillance_auditor');
    }
};
