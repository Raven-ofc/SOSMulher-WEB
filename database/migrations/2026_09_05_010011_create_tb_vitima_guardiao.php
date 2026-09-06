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
        Schema::create('tbvitimaguardiao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idVitima')->constrained('tbvitima')->onDelete('cascade');
            $table->foreignId('idGuardiao')->constrained('tbguardiao')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbvitimaguardiao');
    }
};
