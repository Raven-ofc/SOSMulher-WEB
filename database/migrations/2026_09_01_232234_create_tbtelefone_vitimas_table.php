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
        Schema::create('tbTelefoneVitima', function (Blueprint $table) {
            $table->id('idTelefoneVitima');
            $table->string('numTelefoneVitima');
    
            // Chave estrangeira ligando à Vítima
            $table->unsignedBigInteger('idVitima');
            $table->foreign('idVitima')->references('idVitima')->on('tbVitima')->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbtelefone_vitimas');
    }
};
