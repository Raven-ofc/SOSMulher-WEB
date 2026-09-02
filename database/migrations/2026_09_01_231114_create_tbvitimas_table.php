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
        Schema::create('tbVitima', function (Blueprint $table) {
            $table->id('idVitima');
            $table->string('imagemVitima')->nullable();
            $table->string('nomeVitima');
            $table->char('cpfVitima', 14)->unique();
            $table->string('emailVitima')->unique();
            $table->string('senhaVitima');
            $table->dateTime('dataNascVitima');
            $table->string('statusVitima');
            $table->double('latitudeVitima')->nullable();
            $table->double('longitudeVitima')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbvitimas');
    }
};
