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
        Schema::create('aturan_rule', function (Blueprint $table) {
            $table->id('id_rule');
            
            // Foreign key to penyakit table
            $table->unsignedBigInteger('id_penyakit');
            $table->foreign('id_penyakit')
                  ->references('id_penyakit')
                  ->on('penyakit')
                  ->onDelete('cascade');

            // Foreign key to gejala table
            $table->unsignedBigInteger('id_gejala');
            $table->foreign('id_gejala')
                  ->references('id_gejala')
                  ->on('gejala')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aturan_rule');
    }
};
