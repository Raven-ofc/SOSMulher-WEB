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
        Schema::create('tbalerta', function (Blueprint $table) {
            $table->id();
            $table->string('tipoAlerta', 100);
            $table->string('descricaoAlerta', 255);
            $table->datetime('dataHoraAlerta');
            $table->string('statusAlerta', 20);
            $table->foreignId('idViolacao')->constrained('tbviolacao')->onDelete('cascade');
            $table->foreignId('idAutoridade')->constrained('tbautoridade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbalerta');
    }
};
