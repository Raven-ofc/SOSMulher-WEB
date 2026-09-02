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
        Schema::create('tbOcorrencia', function (Blueprint $table) {
            $table->id('idOcorrencia');
            $table->dateTime('dataHoraOcorrencia');
            $table->string('descricaoOcorrencia');
            $table->string('tipoOcorrencia');
            $table->string('gravidadeOcorrencia');
    
            // Chaves estrangeiras conforme o MER
            $table->unsignedBigInteger('idAutoridade')->nullable();
            $table->foreign('idAutoridade')->references('idAutoridade')->on('tbAutoridade')->onDelete('set null');

            $table->unsignedBigInteger('idVitima');
            $table->foreign('idVitima')->references('idVitima')->on('tbVitima')->onDelete('cascade');
    
            $table->unsignedBigInteger('idAgressor');
            $table->foreign('idAgressor')->references('idAgressor')->on('tbAgressor')->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbocorrencias');
    }
};
