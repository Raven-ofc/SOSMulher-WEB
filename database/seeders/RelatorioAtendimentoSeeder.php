<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelatorioAtendimentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Um relatório por ocorrência (a coluna ocorrencia_id é única).
     */
    public function run(): void
    {
        DB::table('relatorios_atendimento')->insert([
            [
                'ocorrencia_id' => 1,
                'idAutoridade' => 2,
                'inicio' => '2026-09-11 21:40:00',
                'fim' => '2026-09-11 23:05:00',
                'relato' => 'Equipe chegou ao endereço nove minutos após o acionamento. O agressor já havia deixado o local. A vítima foi encontrada abalada, porém sem lesões. Foram reforçadas as orientações de uso do botão de pânico e o guardião cadastrado foi comunicado por telefone.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ocorrencia_id' => 2,
                'idAutoridade' => 3,
                'inicio' => '2026-09-13 23:15:00',
                'fim' => '2026-09-14 02:30:00',
                'relato' => 'Agressor localizado na calçada do edifício e conduzido ao distrito policial. Foram apreendidos registros de trinta e duas chamadas realizadas para a vítima no intervalo de duas horas. Termo circunstanciado lavrado e representação por descumprimento encaminhada ao juízo competente.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ocorrencia_id' => 3,
                'idAutoridade' => 4,
                'inicio' => '2026-09-08 05:10:00',
                'fim' => '2026-09-08 14:20:00',
                'relato' => 'Após o rompimento do dispositivo, a central repassou o último ponto conhecido. O agressor foi localizado na residência de um irmão, no mesmo distrito, e recolhido ao sistema prisional. A vítima foi informada da prisão ainda na manhã do mesmo dia.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ocorrencia_id' => 4,
                'idAutoridade' => 5,
                'inicio' => '2026-09-16 08:55:00',
                'fim' => '2026-09-16 10:40:00',
                'relato' => 'Agressor abordado a duzentos metros do local de trabalho da vítima. Alegou estar a caminho de uma entrevista de emprego, versão não confirmada pelo histórico de deslocamento do dispositivo. Caso encaminhado à delegacia para análise de descumprimento.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ocorrencia_id' => 5,
                'idAutoridade' => 6,
                'inicio' => '2026-06-30 15:45:00',
                'fim' => '2026-06-30 18:10:00',
                'relato' => 'Verificação técnica realizada na central de monitoramento. A cinta e os lacres estavam íntegros, e a falha foi atribuída à antena de GPS do equipamento. O dispositivo foi substituído e o histórico do período foi anexado ao procedimento.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ocorrencia_id' => 6,
                'idAutoridade' => 7,
                'inicio' => '2026-09-14 19:20:00',
                'fim' => '2026-09-14 21:15:00',
                'relato' => 'A portaria impediu o acesso do agressor e acionou a Patrulha Maria da Penha. Imagens do circuito interno foram requisitadas e anexadas ao boletim de ocorrência. A vítima optou por permanecer na casa da irmã durante a semana seguinte.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ocorrencia_id' => 7,
                'idAutoridade' => 8,
                'inicio' => '2026-09-17 02:40:00',
                'fim' => '2026-09-17 09:55:00',
                'relato' => 'Tornozeleira localizada rompida sobre a calçada, a cerca de um quilômetro da residência do agressor. A vítima foi removida para endereço protegido com apoio da assistência social e difusão de busca foi emitida para as unidades da região leste.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ocorrencia_id' => 8,
                'idAutoridade' => 9,
                'inicio' => '2026-09-18 06:30:00',
                'fim' => '2026-09-18 08:05:00',
                'relato' => 'Constatada bateria crítica por mais de doze horas sem recarga. O agressor foi intimado por telefone a comparecer à central de monitoramento no mesmo dia, sob pena de representação por descumprimento das condições do monitoramento.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ocorrencia_id' => 9,
                'idAutoridade' => 10,
                'inicio' => '2026-08-17 18:05:00',
                'fim' => '2026-08-17 22:40:00',
                'relato' => 'Reunidas as mensagens ameaçadoras enviadas nas semanas anteriores e o histórico de aproximação do dispositivo. A representação pela prisão preventiva foi protocolada na mesma noite e deferida em dezenove de agosto de dois mil e vinte e seis.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ocorrencia_id' => 10,
                'idAutoridade' => 1,
                'inicio' => '2026-09-18 14:10:00',
                'fim' => '2026-09-18 15:00:00',
                'relato' => 'Conferência da trilha de localização apontou erro de posicionamento em área de sombra de sinal. O agressor permaneceu fora da zona de exclusão durante todo o intervalo analisado e o alerta foi classificado como improcedente.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
