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
        Schema::create('tbocorrencia', function (Blueprint $table) {
            $table->id();

            $table->date('dataOcorrencia');
            $table->dateTime('dataHoraOcorrencia');

            $table->text('descricaoOcorrencia')->nullable();

            $table->string('tipoOcorrencia');
            $table->string('gravidadeOcorrencia');
            $table->string('statusAtendimento');

            $table->string('bairroOcorrencia', 100);
            $table->string('localOcorrencia', 255);

            $table->foreignId('idVitima')
                ->constrained('tbvitima')
                ->onDelete('cascade');

            $table->foreignId('idAgressor')
                ->constrained('tbagressor')
                ->onDelete('cascade');

            $table->foreignId('idAutoridade')
                ->constrained('tbautoridade')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbocorrencia');
    }
};