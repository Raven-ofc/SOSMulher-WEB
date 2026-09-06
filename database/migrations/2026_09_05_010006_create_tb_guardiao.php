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
        Schema::create('tbguardiao', function (Blueprint $table) {
            $table->id();
            $table->string('imagemGuardiao', 255);
            $table->string('nomeGuardiao', 100);
            $table->string('cpfGuardiao', 14)->unique();
            $table->string('emailGuardiao', 100)->unique();
            $table->string('senhaGuardiao', 255);
            $table->date('dataNascimentoGuardiao');
            $table->string('statusGuardiao', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbguardiao');
    }
};
