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
        Schema::create('tbAlerta', function (Blueprint $table) {
            $table->id('idAlerta');
            $table->string('tipoAlerta');
            $table->dateTime('dataHoraAlerta');
            $table->text('mensagemAlerta');
            $table->string('statusAlerta');
    
            // Chaves estrangeiras
            $table->unsignedBigInteger('idVitima');
            $table->foreign('idVitima')->references('idVitima')->on('tbVitima')->onDelete('cascade');

            $table->unsignedBigInteger('idAutoridade')->nullable();
            $table->foreign('idAutoridade')->references('idAutoridade')->on('tbAutoridade')->onDelete('set null');

            $table->unsignedBigInteger('idViolacao');
            $table->foreign('idViolacao')->references('idViolacao')->on('tbViolacao')->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbalertas');
    }
};
