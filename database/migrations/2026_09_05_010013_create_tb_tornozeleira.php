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
        Schema::create('tbtornozeleira', function (Blueprint $table) {
            $table->id();
            $table->string('numeroSerieTornozeleira', 100);
            $table->string('statusTornozeleira', 20);
            $table->date('dataInstalacaoTornozeleira');
            $table->integer('bateriaTornozeleira');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbtornozeleira');
    }
};
