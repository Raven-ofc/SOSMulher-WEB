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
        Schema::create('tbsolicitacao', function (Blueprint $table) {
            $table->id();
            $table->string('descricaoSolicitacao')->nullable();
            $table->string('tipoSolicitacao');
            $table->string('logradouroSolicitacao', 255);
            $table->string('numLogradouroSolicitacao', 10);
            $table->string('bairroSolicitacao', 100);
            $table->string('cidadeSolicitacao', 100);
            $table->string('ufSolicitacao', 100);
            $table->string('complementoSolicitacao', 100);
            $table->string('cepSolicitacao', 10);
            $table->decimal('latitudeSolicitacao', 10, 7);
            $table->decimal('longitudeSolicitacao', 10, 7);
            $table->string('statusSolicitacao', 20); 
            $table->date('dataSolicitacao');
            $table->date('dataAnalise')->nullable();
            $table->foreignId('idVitima')->constrained('tbvitima')->onDelete('cascade');
            $table->foreignId('idAutoridade')->constrained('tbautoridade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbsolicitacao');
    }
};
