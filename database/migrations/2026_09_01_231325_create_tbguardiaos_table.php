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
        Schema::create('tbGuardiao', function (Blueprint $table) {
            $table->id('idGuardiao');
            $table->string('imagemGuardiao')->nullable();
            $table->string('nomeGuardiao');
            $table->string('emailGuardiao')->unique();
            $table->string('senhaGuardiao');
            $table->char('cpfGuardiao', 14)->unique();
            $table->dateTime('dataNascGuardiao');
            $table->string('statusGuardiao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbguardiaos');
    }
};
