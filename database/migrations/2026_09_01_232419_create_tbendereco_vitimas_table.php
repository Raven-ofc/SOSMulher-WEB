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
        Schema::create('tbEnderecovitima', function (Blueprint $table) {
            $table->id('idEnderecovitima');
            $table->string('logradouroVitima');
            $table->string('numLogradouroVitima');
            $table->char('cepVitima', 9);
            $table->string('bairroVitima');
            $table->string('cidadeVitima');
            $table->string('complementoVitima')->nullable();
            $table->char('ufVitima', 2);
            $table->double('latitudeVitima')->nullable();
            $table->double('longitudeVitima')->nullable();
    
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
        Schema::dropIfExists('tbendereco_vitimas');
    }
};
