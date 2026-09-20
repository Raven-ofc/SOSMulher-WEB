<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ViolacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Registros de descumprimento das medidas protetivas detectados
     * pelo monitoramento eletrônico da tornozeleira.
     */
    public function run(): void
    {
        DB::table('tbviolacao')->insert([
            [
                'id' => 1,
                'tipoViolacao' => 'Aproximação da zona de exclusão',
                'descricaoViolacao' => 'Agressor detectado a 180 metros da residência da vítima, abaixo do limite de 300 metros fixado judicialmente.',
                'statusViolacao' => 'Confirmada',
                'latitudeViolacao' => -23.5504870,
                'longitudeViolacao' => -46.4017320,
                'dataHoraViolacao' => '2026-09-11 21:34:00',
                'idMedida' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'tipoViolacao' => 'Invasão de zona de exclusão',
                'descricaoViolacao' => 'Permanência de 22 minutos dentro do raio de 800 metros da residência da vítima, no período noturno.',
                'statusViolacao' => 'Confirmada',
                'latitudeViolacao' => -23.5464910,
                'longitudeViolacao' => -46.6401250,
                'dataHoraViolacao' => '2026-09-13 23:08:00',
                'idMedida' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'tipoViolacao' => 'Rompimento do dispositivo',
                'descricaoViolacao' => 'Sensor de integridade acusou tentativa de rompimento da cinta da tornozeleira TZ003.',
                'statusViolacao' => 'Confirmada',
                'latitudeViolacao' => -23.5840360,
                'longitudeViolacao' => -46.4041780,
                'dataHoraViolacao' => '2026-09-08 04:52:00',
                'idMedida' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'tipoViolacao' => 'Aproximação da zona de exclusão',
                'descricaoViolacao' => 'Deslocamento em direção ao local de trabalho da vítima, com entrada no perímetro monitorado.',
                'statusViolacao' => 'Confirmada',
                'latitudeViolacao' => -23.5551920,
                'longitudeViolacao' => -46.5660440,
                'dataHoraViolacao' => '2026-09-16 08:47:00',
                'idMedida' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'tipoViolacao' => 'Perda de sinal do dispositivo',
                'descricaoViolacao' => 'Sinal de GPS interrompido por 3 horas e 15 minutos; restabelecido em endereço diverso do informado.',
                'statusViolacao' => 'Em análise',
                'latitudeViolacao' => -23.6651040,
                'longitudeViolacao' => -46.7596620,
                'dataHoraViolacao' => '2026-06-30 15:20:00',
                'idMedida' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'tipoViolacao' => 'Invasão de zona de exclusão',
                'descricaoViolacao' => 'Agressor permaneceu na portaria do condomínio da vítima por cerca de 10 minutos.',
                'statusViolacao' => 'Confirmada',
                'latitudeViolacao' => -23.4939740,
                'longitudeViolacao' => -46.6842110,
                'dataHoraViolacao' => '2026-09-14 19:12:00',
                'idMedida' => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'tipoViolacao' => 'Rompimento do dispositivo',
                'descricaoViolacao' => 'Tornozeleira TZ007 rompida e abandonada em via pública; agressor em local desconhecido.',
                'statusViolacao' => 'Confirmada',
                'latitudeViolacao' => -23.6008550,
                'longitudeViolacao' => -46.5171930,
                'dataHoraViolacao' => '2026-09-17 02:26:00',
                'idMedida' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'tipoViolacao' => 'Bateria crítica sem recarga',
                'descricaoViolacao' => 'Dispositivo abaixo de 20% por mais de 12 horas, sem recarga após três notificações automáticas.',
                'statusViolacao' => 'Em análise',
                'latitudeViolacao' => -23.6487290,
                'longitudeViolacao' => -46.7587340,
                'dataHoraViolacao' => '2026-09-18 06:05:00',
                'idMedida' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'tipoViolacao' => 'Aproximação da zona de exclusão',
                'descricaoViolacao' => 'Registro anterior à prisão preventiva; agressor localizado a 140 metros da residência da vítima.',
                'statusViolacao' => 'Arquivada',
                'latitudeViolacao' => -23.4876830,
                'longitudeViolacao' => -46.7299450,
                'dataHoraViolacao' => '2026-08-17 17:41:00',
                'idMedida' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 10,
                'tipoViolacao' => 'Alerta indevido',
                'descricaoViolacao' => 'Disparo causado por imprecisão de GPS em área de sombra; posição real fora da zona de exclusão.',
                'statusViolacao' => 'Descartada',
                'latitudeViolacao' => -23.6908910,
                'longitudeViolacao' => -46.7544270,
                'dataHoraViolacao' => '2026-09-18 13:59:00',
                'idMedida' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
