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
        Schema::create('tbviolacao', function (Blueprint $table) {
            $table->id();
            $table->string('tipoViolacao', 100);
            $table->string('descricaoViolacao', 255);
            $table->string('statusViolacao', 20);
            $table->decimal('latitudeViolacao', 10, 7);
            $table->decimal('longitudeViolacao', 10, 7);
            $table->dateTime('dataHoraViolacao');
            $table->foreignId('idMedida')->constrained('tbmedida')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbviolacao');
    }
};
