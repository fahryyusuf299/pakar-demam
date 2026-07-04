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
        Schema::create('aturan_rule', function (Blueprint $table) {
            $table->id('id_rule');
            
            $table->string('id_penyakit');
            $table->foreign('id_penyakit')
                  ->references('id_penyakit')
                  ->on('penyakit')
                  ->onDelete('cascade');

            $table->string('id_gejala');
            $table->foreign('id_gejala')
                  ->references('id_gejala')
                  ->on('gejala')
                  ->onDelete('cascade');
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
