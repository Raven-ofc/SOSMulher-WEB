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
        Schema::create('tbAutoridade', function (Blueprint $table) {
            $table->id('idAutoridade');
            $table->string('imagemAutoridade')->nullable();
            $table->string('nomeAutoridade');
            $table->char('matriculaAutoridade', 50)->unique();
            $table->string('cargoAutoridade');
            $table->string('emailAutoridade')->unique();
            $table->string('senhaAutoridade');
            $table->string('unidadeAutoridade');
            $table->string('statusAutoridade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbautoridades');
    }
};
