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
        Schema::create('riwayat_diagnosa', function (Blueprint $table) {
            $table->uuid('id_diagnosa')->primary();
            $table->string('nama_pasien');
            $table->timestamp('tanggal_konsultasi')->nullable();
            $table->json('gejala_dipilih'); // Use standard json for compatibility
            $table->string('hasil_penyakit');
            $table->text('solusi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_diagnosa');
    }
};
