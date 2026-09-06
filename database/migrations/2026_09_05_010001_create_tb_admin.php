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
        Schema::create('tbadmin', function (Blueprint $table) {
            $table->id();
            $table->string('nomeAdmin');
            $table->string('emailAdmin')->unique();
            $table->string('senhaAdmin');
            $table->string('cpfAdmin')->unique();
            $table->date('dataNascAdmin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbadmin');
    }
};
