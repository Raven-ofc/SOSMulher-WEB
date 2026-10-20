<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Prepara o banco para o cadastro unificado (vítima > agressor > tornozeleira > medida protetiva):
 *  - agressor passa a ter os mesmos dados da vítima (filiação, contato, CEP e coordenadas);
 *  - coordenadas do endereço da vítima passam a ser opcionais (a API de CEP nem sempre devolve);
 *  - ocorrência ganha coordenadas (usadas no mapa de monitoramento);
 *  - medida protetiva ganha os campos jurídicos/operacionais.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbagressor', function (Blueprint $table) {
            if (!Schema::hasColumn('tbagressor', 'nomeMaeAgressor')) {
                $table->string('nomeMaeAgressor', 100)->nullable()->after('dataNascimentoAgressor');
            }
            if (!Schema::hasColumn('tbagressor', 'nomePaiAgressor')) {
                $table->string('nomePaiAgressor', 100)->nullable()->after('nomeMaeAgressor');
            }
            if (!Schema::hasColumn('tbagressor', 'telefoneAgressor')) {
                $table->string('telefoneAgressor', 20)->nullable()->after('nomePaiAgressor');
            }
            if (!Schema::hasColumn('tbagressor', 'emailAgressor')) {
                $table->string('emailAgressor', 100)->nullable()->after('telefoneAgressor');
            }
            if (!Schema::hasColumn('tbagressor', 'cepAgressor')) {
                $table->string('cepAgressor', 10)->nullable()->after('numLogradouroAgressor');
            }
            if (!Schema::hasColumn('tbagressor', 'latitudeAgressor')) {
                $table->decimal('latitudeAgressor', 10, 7)->nullable();
                $table->decimal('longitudeAgressor', 10, 7)->nullable();
            }
        });

        Schema::table('tbenderecovitima', function (Blueprint $table) {
            $table->decimal('latitudeVitima', 10, 7)->nullable()->change();
            $table->decimal('longitudeVitima', 10, 7)->nullable()->change();
        });

        Schema::table('tbocorrencia', function (Blueprint $table) {
            if (!Schema::hasColumn('tbocorrencia', 'latitudeOcorrencia')) {
                $table->decimal('latitudeOcorrencia', 10, 7)->nullable();
                $table->decimal('longitudeOcorrencia', 10, 7)->nullable();
            }
        });

        Schema::table('tbmedida', function (Blueprint $table) {
            if (!Schema::hasColumn('tbmedida', 'numeroProcessoMedida')) {
                $table->string('numeroProcessoMedida', 40)->nullable()->after('descricaoMedida');
            }
            if (!Schema::hasColumn('tbmedida', 'varaMedida')) {
                $table->string('varaMedida', 100)->nullable()->after('numeroProcessoMedida');
            }
            if (!Schema::hasColumn('tbmedida', 'tiposMedida')) {
                $table->text('tiposMedida')->nullable()->after('varaMedida');
            }
            if (!Schema::hasColumn('tbmedida', 'nivelRiscoMedida')) {
                $table->string('nivelRiscoMedida', 20)->default('medio')->after('tiposMedida');
            }
            if (!Schema::hasColumn('tbmedida', 'observacoesMedida')) {
                $table->text('observacoesMedida')->nullable()->after('nivelRiscoMedida');
            }
            if (!Schema::hasColumn('tbmedida', 'idAutoridade')) {
                $table->unsignedBigInteger('idAutoridade')->nullable()->after('idAgressor');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tbmedida', function (Blueprint $table) {
            foreach (['numeroProcessoMedida', 'varaMedida', 'tiposMedida', 'nivelRiscoMedida', 'observacoesMedida', 'idAutoridade'] as $coluna) {
                if (Schema::hasColumn('tbmedida', $coluna)) {
                    $table->dropColumn($coluna);
                }
            }
        });

        Schema::table('tbocorrencia', function (Blueprint $table) {
            foreach (['latitudeOcorrencia', 'longitudeOcorrencia'] as $coluna) {
                if (Schema::hasColumn('tbocorrencia', $coluna)) {
                    $table->dropColumn($coluna);
                }
            }
        });

        Schema::table('tbagressor', function (Blueprint $table) {
            foreach (['nomeMaeAgressor', 'nomePaiAgressor', 'telefoneAgressor', 'emailAgressor', 'cepAgressor', 'latitudeAgressor', 'longitudeAgressor'] as $coluna) {
                if (Schema::hasColumn('tbagressor', $coluna)) {
                    $table->dropColumn($coluna);
                }
            }
        });
    }
};
