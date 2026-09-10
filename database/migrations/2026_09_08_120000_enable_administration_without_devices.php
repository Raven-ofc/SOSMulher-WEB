<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('cpf', 11)->nullable()->unique();
            $t->string('phone', 15)->nullable();
            $t->string('photo_path')->nullable();
        });
        Schema::table('tbvitima', function (Blueprint $t) {
            $t->string('imagemVitima', 255)->nullable()->change();
            $t->string('senhaVitima', 255)->nullable()->change();
        });
        Schema::table('tbagressor', function (Blueprint $t) {
            foreach (['imagemAgressor' => 255, 'logradouroAgressor' => 255, 'numLogradouroAgressor' => 10, 'bairroAgressor' => 100, 'cidadeAgressor' => 100, 'ufAgressor' => 2, 'complementoAgressor' => 9] as $name => $size) {
                $t->string($name, $size)->nullable()->change();
            }
            $t->unsignedBigInteger('idTornozeleira')->nullable()->change();
            $t->string('telefoneAgressor', 15)->nullable();
        });
        Schema::table('tbocorrencia', function (Blueprint $t) {
            $t->unsignedBigInteger('idAutoridade')->nullable()->change();
            $t->string('statusAtendimento', 20)->default('andamento')->index();
            $t->dateTime('dataHoraOcorrencia')->nullable()->index();
            $t->string('localOcorrencia')->nullable();
            $t->string('bairroOcorrencia', 100)->nullable();
        });
        Schema::table('tbsolicitacao', function (Blueprint $t) {
            $t->unsignedBigInteger('idAutoridade')->nullable()->change();
            $t->string('complementoSolicitacao', 100)->nullable()->change();
            $t->foreignId('analisadoPor')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('removidoEm')->nullable();
        });
        Schema::create('relatorios_atendimento', function (Blueprint $t) {
            $t->id();
            $t->foreignId('ocorrencia_id')->unique()->constrained('tbocorrencia')->restrictOnDelete();
            $t->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $t->dateTime('inicio');
            $t->dateTime('fim');
            $t->text('relato');
            $t->timestamps();
        });
        Schema::create('administration_audit', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('entity', 80);
            $t->unsignedBigInteger('entity_id');
            $t->string('action', 40);
            $t->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administration_audit');
        Schema::dropIfExists('relatorios_atendimento');
        Schema::table('tbsolicitacao', function (Blueprint $t) {
            $t->dropConstrainedForeignId('analisadoPor');
            $t->dropColumn('removidoEm');
        });
        Schema::table('tbocorrencia', function (Blueprint $t) {
            $t->dropColumn(['statusAtendimento', 'dataHoraOcorrencia', 'localOcorrencia', 'bairroOcorrencia']);
        });
        Schema::table('tbagressor', fn (Blueprint $t) => $t->dropColumn('telefoneAgressor'));
        Schema::table('users', function (Blueprint $t) {
            $t->dropUnique(['cpf']);
            $t->dropColumn(['cpf', 'phone', 'photo_path']);
        });
    }
};
