<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbvitima', function (Blueprint $table) {
            $table->string('nomeMaeVitima', 100)->nullable()->after('dataNascimentoVitima');
            $table->string('nomePaiVitima', 100)->nullable()->after('nomeMaeVitima');
        });
    }

    public function down(): void
    {
        Schema::table('tbvitima', function (Blueprint $table) {
            $table->dropColumn(['nomeMaeVitima', 'nomePaiVitima']);
        });
    }
};