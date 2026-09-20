<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Medidas protetivas de urgência (Lei 11.340/2006 - Lei Maria da Penha).
     * distanciaMedida = distância mínima de aproximação, em metros.
     * raioAlertaMedida = raio da zona de exclusão monitorada, em metros.
     */
    public function run(): void
    {
        DB::table('tbmedida')->insert([
            [
                'id' => 1,
                'dataInicioMedida' => '2026-09-06',
                'dataFimMedida' => '2027-03-06',
                'distanciaMedida' => 300,
                'raioAlertaMedida' => 500,
                'descricaoMedida' => 'Proibição de aproximação da vítima, de seus familiares e do local de trabalho, com distância mínima de 300 metros.',
                'statusMedida' => 'Ativa',
                'idVitima' => 1,
                'idAgressor' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'dataInicioMedida' => '2026-09-10',
                'dataFimMedida' => '2027-09-10',
                'distanciaMedida' => 500,
                'raioAlertaMedida' => 800,
                'descricaoMedida' => 'Afastamento do lar e proibição de contato por qualquer meio, inclusive redes sociais e aplicativos de mensagem.',
                'statusMedida' => 'Ativa',
                'idVitima' => 2,
                'idAgressor' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'dataInicioMedida' => '2026-08-20',
                'dataFimMedida' => null,
                'distanciaMedida' => 200,
                'raioAlertaMedida' => 400,
                'descricaoMedida' => 'Proibição de frequentar a residência da vítima e a escola dos filhos, por prazo indeterminado até nova decisão judicial.',
                'statusMedida' => 'Ativa',
                'idVitima' => 3,
                'idAgressor' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'dataInicioMedida' => '2026-09-01',
                'dataFimMedida' => '2027-03-01',
                'distanciaMedida' => 300,
                'raioAlertaMedida' => 600,
                'descricaoMedida' => 'Proibição de aproximação e suspensão do porte de arma, com monitoramento eletrônico contínuo.',
                'statusMedida' => 'Ativa',
                'idVitima' => 4,
                'idAgressor' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'dataInicioMedida' => '2026-01-15',
                'dataFimMedida' => '2026-07-15',
                'distanciaMedida' => 250,
                'raioAlertaMedida' => 500,
                'descricaoMedida' => 'Medida protetiva encerrada após decurso do prazo, sem registro de novas violações no período.',
                'statusMedida' => 'Encerrada',
                'idVitima' => 5,
                'idAgressor' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'dataInicioMedida' => '2026-06-28',
                'dataFimMedida' => '2027-06-28',
                'distanciaMedida' => 400,
                'raioAlertaMedida' => 700,
                'descricaoMedida' => 'Proibição de contato com a vítima e testemunhas, além de afastamento do condomínio onde ela reside.',
                'statusMedida' => 'Ativa',
                'idVitima' => 6,
                'idAgressor' => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'dataInicioMedida' => '2026-08-05',
                'dataFimMedida' => null,
                'distanciaMedida' => 500,
                'raioAlertaMedida' => 900,
                'descricaoMedida' => 'Proibição de aproximação com raio ampliado em razão de descumprimento reiterado da medida anterior.',
                'statusMedida' => 'Ativa',
                'idVitima' => 7,
                'idAgressor' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'dataInicioMedida' => '2026-09-12',
                'dataFimMedida' => '2027-03-12',
                'distanciaMedida' => 300,
                'raioAlertaMedida' => 500,
                'descricaoMedida' => 'Afastamento imediato do lar comum e proibição de aproximação da vítima e da mãe dela.',
                'statusMedida' => 'Ativa',
                'idVitima' => 8,
                'idAgressor' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'dataInicioMedida' => '2026-05-19',
                'dataFimMedida' => '2026-08-19',
                'distanciaMedida' => 200,
                'raioAlertaMedida' => 400,
                'descricaoMedida' => 'Medida revogada a pedido do Ministério Público após prisão preventiva do agressor.',
                'statusMedida' => 'Revogada',
                'idVitima' => 9,
                'idAgressor' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 10,
                'dataInicioMedida' => '2026-09-15',
                'dataFimMedida' => '2027-09-15',
                'distanciaMedida' => 350,
                'raioAlertaMedida' => 600,
                'descricaoMedida' => 'Proibição de aproximação da vítima e do local de trabalho dela, com encaminhamento a grupo reflexivo.',
                'statusMedida' => 'Ativa',
                'idVitima' => 10,
                'idAgressor' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
