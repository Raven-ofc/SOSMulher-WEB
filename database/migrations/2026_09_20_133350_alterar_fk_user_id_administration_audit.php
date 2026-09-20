<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('administration_audit', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('tbautoridade')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('administration_audit', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
};