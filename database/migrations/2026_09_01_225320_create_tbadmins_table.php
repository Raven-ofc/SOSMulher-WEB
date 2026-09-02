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
        Schema::create('tbAdmin', function (Blueprint $table) {
            $table->id('idAdmin');
            $table->string('nomeAdmin');
            $table->string('emailAdmin')->unique();
            $table->string('senhaAdmin');
            $table->char('cpfAdmin', 14)->unique();
            $table->dateTime('dataNascAdmin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbadmins');
    }
};
