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
        Schema::create('tbmedida', function (Blueprint $table) {
            $table->id();
            $table->date('dataInicioMedida');
            $table->date('dataFimMedida')->nullable();
            $table->integer('distanciaMedida');
            $table->integer('raioAlertaMedida');
            $table->string('descricaoMedida', 255);
            $table->string('statusMedida', 20); 
            $table->foreignId('idVitima')->constrained('tbvitima')->onDelete('cascade');
            $table->foreignId('idAgressor')->constrained('tbagressor')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbmedida');
    }
};
