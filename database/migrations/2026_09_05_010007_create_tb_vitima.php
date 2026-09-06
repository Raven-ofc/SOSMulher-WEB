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
        Schema::create('tbvitima', function (Blueprint $table) {
            $table->id();
            $table->string('imagemVitima', 255);
            $table->string('nomeVitima', 100);
            $table->string('cpfVitima', 14)->unique();
            $table->string('emailVitima', 100)->unique();
            $table->string('senhaVitima', 255);
            $table->date('dataNascimentoVitima');
            $table->string('statusVitima', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbvitima');
    }
};
