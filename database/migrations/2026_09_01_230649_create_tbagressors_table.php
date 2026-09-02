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
        Schema::create('tbAgressor', function (Blueprint $table) {
            $table->id('idAgressor');
            $table->string('nomeAgressor');
            $table->char('cpfAgressor', 14)->unique();
            $table->string('logradouroAgressor');
            $table->string('numLogradouroAgressor');
            $table->char('cepAgressor', 9);
            $table->string('bairroAgressor');
            $table->string('cidadeAgressor');
            $table->char('ufAgressor', 2);
            $table->string('complementoAgressor')->nullable();
            $table->dateTime('dataNascAgressor');
            $table->string('statusAgressor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbagressors');
    }
};
