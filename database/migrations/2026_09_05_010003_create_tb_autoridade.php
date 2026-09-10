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
        Schema::create('tbautoridade', function (Blueprint $table) {
            $table->id();
            $table->string('imagemAutoridade', 255);
            $table->string('nomeAutoridade', 100);
            $table->string('emailAutoridade', 100)->unique();
            $table->string('cpfAutoridade', 14)->unique();
            $table->string('matriculaAutoridade', 20)->unique();
            $table->string('cargoAutoridade', 100);
            $table->string('unidadeAutoridade', 100);
            $table->string('senhaAutoridade');
            $table->string('statusAutoridade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbautoridade');
    }
};
