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
        Schema::create('tbViolacao', function (Blueprint $table) {
            $table->id('idViolacao');
            $table->string('tipoViolacao');
            $table->text('descricaoViolacao');
            $table->double('latitudeViolacao');
            $table->double('longitudeViolacao');
            $table->string('statusViolacao');
    
            // Chaves estrangeiras
            $table->unsignedBigInteger('idVitima');
            $table->foreign('idVitima')->references('idVitima')->on('tbVitima')->onDelete('cascade');

            $table->unsignedBigInteger('idMedida');
            $table->foreign('idMedida')->references('idMedida')->on('tbMedida')->onDelete('cascade');

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
        Schema::dropIfExists('tbviolacaos');
    }
};
