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
        Schema::create('tbSolicitacao', function (Blueprint $table) {
            $table->id('idSolicitacao');
            $table->string('tipoSolicitacao');
            $table->string('statusSolicitacao');
            $table->text('descricaoSolicitacao');
            $table->string('logradouroSolicitacao');
            $table->string('numLogradouroSolicitacao');
            $table->char('cepSolicitacao', 9);
            $table->string('bairroSolicitacao');
            $table->string('cidadeSolicitacao');
            $table->string('complementoSolicitacao')->nullable();
            $table->char('ufSolicitacao', 2);
            $table->double('latitudeSolicitacao')->nullable();
            $table->double('longitudeSolicitacao')->nullable();
            $table->dateTime('dataCriacaoSolicitacao');
            $table->dateTime('dataAnaliseSolicitacao')->nullable();
    
            // Chaves estrangeiras
            $table->unsignedBigInteger('idVitima');
            $table->foreign('idVitima')->references('idVitima')->on('tbVitima')->onDelete('cascade');

            $table->unsignedBigInteger('idEnderecovitima');
            $table->foreign('idEnderecovitima')->references('idEnderecovitima')->on('tbEnderecovitima')->onDelete('cascade');

            $table->unsignedBigInteger('idAutoridade')->nullable();
            $table->foreign('idAutoridade')->references('idAutoridade')->on('tbAutoridade')->onDelete('set null');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbsolicitacaos');
    }
};
