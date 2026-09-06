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
        Schema::create('tbenderecovitima', function (Blueprint $table) {
            $table->id();
            $table->string('logradouroVitima', 255);    
            $table->string('numLogradouroVitima', 10);
            $table->string('bairroVitima', 100);
            $table->string('cidadeVitima', 100);
            $table->string('ufVitima', 100);
            $table->string('complementoVitima', 100);
            $table->string('cepVitima', 10);
            $table->foreignId('idVitima')->constrained('tbvitima')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbenderecovitima');
    }
};
