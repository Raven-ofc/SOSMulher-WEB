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
        Schema::create('relatorios_atendimento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ocorrencia_id')
                ->unique()
                ->constrained('tbocorrencia')
                ->onDelete('cascade');

            $table->foreignId('idAutoridade')
                ->constrained('tbautoridade')
                ->onDelete('cascade');

            $table->dateTime('inicio');
            $table->dateTime('fim');
            $table->text('relato');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorios_atendimento');
    }
};
