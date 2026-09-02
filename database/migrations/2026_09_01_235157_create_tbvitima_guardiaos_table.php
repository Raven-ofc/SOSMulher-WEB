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
        Schema::create('tbVitimaGuardiao', function (Blueprint $table) {
            $table->id('idVitimaGuardiao');
    
            // Chaves estrangeiras
            $table->unsignedBigInteger('idVitima');
            $table->foreign('idVitima')->references('idVitima')->on('tbVitima')->onDelete('cascade');

            $table->unsignedBigInteger('idGuardiao');
            $table->foreign('idGuardiao')->references('idGuardiao')->on('tbGuardiao')->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbvitima_guardiaos');
    }
};
