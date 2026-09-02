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
        Schema::create('tbTelefoneAdmin', function (Blueprint $table) {
            $table->id('idTelefoneAdmin');
            $table->string('numTelefoneAdmin');
    
            // Chave estrangeira ligando ao Admin
            $table->unsignedBigInteger('idAdmin');
            $table->foreign('idAdmin')->references('idAdmin')->on('tbAdmin')->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbtelefone_admins');
    }
};
