<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Alertas gerados a partir das violações e encaminhados às autoridades responsáveis.
     */
    public function run(): void
    {
        DB::table('tbalerta')->insert([
            [
                'id' => 1,
                'tipoAlerta' => 'Proximidade crítica',
                'descricaoAlerta' => 'Vítima e guardião notificados via aplicativo. Viatura da Patrulha Maria da Penha acionada.',
                'dataHoraAlerta' => '2026-09-11 21:35:00',
                'statusAlerta' => 'Atendido',
                'idViolacao' => 1,
                'idAutoridade' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'tipoAlerta' => 'Proximidade crítica',
                'descricaoAlerta' => 'Alerta noturno com permanência prolongada na zona de exclusão. Equipe deslocada ao endereço.',
                'dataHoraAlerta' => '2026-09-13 23:09:00',
                'statusAlerta' => 'Atendido',
                'idViolacao' => 2,
                'idAutoridade' => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'tipoAlerta' => 'Violação de equipamento',
                'descricaoAlerta' => 'Tentativa de rompimento da tornozeleira. Representação por prisão preventiva encaminhada ao juízo.',
                'dataHoraAlerta' => '2026-09-08 04:53:00',
                'statusAlerta' => 'Encerrado',
                'idViolacao' => 3,
                'idAutoridade' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'tipoAlerta' => 'Proximidade crítica',
                'descricaoAlerta' => 'Aproximação do local de trabalho da vítima no horário de entrada. Vítima orientada a permanecer no interior do prédio.',
                'dataHoraAlerta' => '2026-09-16 08:48:00',
                'statusAlerta' => 'Atendido',
                'idViolacao' => 4,
                'idAutoridade' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'tipoAlerta' => 'Falha de sinal',
                'descricaoAlerta' => 'Perda prolongada de sinal de GPS. Verificação técnica solicitada à central de monitoramento.',
                'dataHoraAlerta' => '2026-06-30 15:22:00',
                'statusAlerta' => 'Encerrado',
                'idViolacao' => 5,
                'idAutoridade' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'tipoAlerta' => 'Proximidade crítica',
                'descricaoAlerta' => 'Agressor na portaria do condomínio. Síndico e guardiã acionados; ocorrência registrada em boletim.',
                'dataHoraAlerta' => '2026-09-14 19:13:00',
                'statusAlerta' => 'Atendido',
                'idViolacao' => 6,
                'idAutoridade' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'tipoAlerta' => 'Violação de equipamento',
                'descricaoAlerta' => 'Rompimento confirmado e agressor sem localização. Difusão de busca emitida às unidades da região.',
                'dataHoraAlerta' => '2026-09-17 02:27:00',
                'statusAlerta' => 'Em andamento',
                'idViolacao' => 7,
                'idAutoridade' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'tipoAlerta' => 'Bateria crítica',
                'descricaoAlerta' => 'Notificação automática de bateria abaixo de 20%. Agressor intimado a comparecer para recarga assistida.',
                'dataHoraAlerta' => '2026-09-18 06:07:00',
                'statusAlerta' => 'Em andamento',
                'idViolacao' => 8,
                'idAutoridade' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'tipoAlerta' => 'Proximidade crítica',
                'descricaoAlerta' => 'Alerta arquivado após decretação da prisão preventiva do agressor em 19/08/2026.',
                'dataHoraAlerta' => '2026-08-17 17:42:00',
                'statusAlerta' => 'Encerrado',
                'idViolacao' => 9,
                'idAutoridade' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 10,
                'tipoAlerta' => 'Falso positivo',
                'descricaoAlerta' => 'Alerta descartado após conferência da trilha de localização pela central de monitoramento.',
                'dataHoraAlerta' => '2026-09-18 14:01:00',
                'statusAlerta' => 'Descartado',
                'idViolacao' => 10,
                'idAutoridade' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
