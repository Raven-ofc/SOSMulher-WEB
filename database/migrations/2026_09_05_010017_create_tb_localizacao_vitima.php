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
        Schema::create('tblocalizacaovitima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idVitima')->constrained('tbvitima')->onDelete('cascade');
            $table->decimal('latitudeLocalizacao', 10, 7);
            $table->decimal('longitudeLocalizacao', 10, 7);
            $table->dateTime('dataHoraLocalizacao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblocalizacaovitima');
    }
};
