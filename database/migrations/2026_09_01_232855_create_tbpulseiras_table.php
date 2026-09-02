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
        Schema::create('tbPulseira', function (Blueprint $table) {
            $table->id('idPulseira');
            $table->string('numeroSerieTornozeleira');
            $table->string('statusTornozeleira');
            $table->dateTime('dataInstalacaoPulseira');
            $table->integer('bateriaPulseira')->nullable();
    
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
        Schema::dropIfExists('tbpulseiras');
    }
};
