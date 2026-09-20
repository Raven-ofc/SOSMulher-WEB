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
        Schema::create('tbagressor', function (Blueprint $table) {
            $table->id();
            $table->string('imagemAgressor', 255)->nullable() ;
            $table->string('nomeAgressor', 100);
            $table->string('cpfAgressor', 14)->unique();
            $table->string('logradouroAgressor', 255);
            $table->string('numLogradouroAgressor', 100);
            $table->string('bairroAgressor', 100);
            $table->string('cidadeAgressor', 100);
            $table->string('ufAgressor', 10);
            $table->string('complementoAgressor', 100)->nullable();
            $table->date('dataNascimentoAgressor');
            $table->string('statusAgressor', 30);
            $table->foreignId('idTornozeleira')->unique()->constrained('tbtornozeleira')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbagressor');
    }
};
