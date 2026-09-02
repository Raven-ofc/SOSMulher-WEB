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
        Schema::create('tbLocalizacaoCriminoso', function (Blueprint $table) {
            $table->id('idLocalizacaoCriminoso');
            $table->double('latitudeLocalizacaoAgressor');
            $table->double('longitudeLocalizacaoAgressor');
            $table->dateTime('dataHoraLocalizacaoAgressor');
    
            // Chave estrangeira ligando ao Agressor
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
        Schema::dropIfExists('tblocalizacao_criminosos');
    }
};
