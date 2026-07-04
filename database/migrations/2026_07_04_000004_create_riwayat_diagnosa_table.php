<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function run(): void
    {
        Schema::create('riwayat_diagnosa', function (Blueprint $table) {
            $table->id('id_diagnosa');
            $table->string('nama_pasien');
            $table->json('gejala_dipilih'); // Store selected symptom names or IDs as JSON
            $table->string('hasil_penyakit');
            $table->text('solusi');
            $table->timestamps();
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
