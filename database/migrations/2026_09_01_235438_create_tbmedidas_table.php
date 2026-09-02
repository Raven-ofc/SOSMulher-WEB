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
        Schema::create('tbMedida', function (Blueprint $table) {
            $table->id('idMedida');
            $table->dateTime('dataInicioMedida');
            $table->dateTime('dataFimMedida');
            $table->double('distanciaMaximaMedida');
            $table->string('statusMedida');
    
            // Chaves estrangeiras
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
        Schema::dropIfExists('tbmedidas');
    }
};
