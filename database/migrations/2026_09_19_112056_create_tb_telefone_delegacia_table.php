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
        Schema::create('tbTelefoneDelegacia', function (Blueprint $table) {
            $table->id();
            $table->string('numeroTelefoneDelegacia', 15);
            $table->foreignId('idDelegacia')->constrained('tbDelegacia')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbTelefoneDelegacia');
    }
};
