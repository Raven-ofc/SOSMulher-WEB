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
        Schema::create('tbdelegacia', function (Blueprint $table) {
            $table->id();
            $table->string('nomeDelegacia', 300);
            $table->string('tipoDelegacia', 100);
            $table->string('statusDelegacia', 200);
            $table->string('logradouroDelegacia', 255);    
            $table->string('numLogradouroDelegacia', 10);
            $table->string('bairroDelegacia', 100);
            $table->string('cidadeDelegacia', 100);
            $table->string('ufDelegacia', 100);
            $table->string('cepDelegacia', 10);
            $table->decimal('latitudedelegacia', 10, 7);
            $table->decimal('longitudedelegacia', 10, 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbenderecodelegacia');
    }
};
