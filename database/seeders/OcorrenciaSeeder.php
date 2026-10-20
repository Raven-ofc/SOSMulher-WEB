<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Support\Regiao;
use Illuminate\Support\Facades\DB;

class OcorrenciaSeeder extends Seeder
{
    public function run(): void
    {
        $ocorrencias = [
            [
                'id' => 1,
                'dataOcorrencia' => '2026-09-11',
                'dataHoraOcorrencia' => '2026-09-11 21:40:00',
                'descricaoOcorrencia' => 'Vítima acionou o botão de pânico após identificar o agressor parado em frente à residência. Equipe chegou ao local em 9 minutos e o agressor já havia se evadido.',
                'tipoOcorrencia' => 'Descumprimento de Medida Protetiva',
                'gravidadeOcorrencia' => 'alta',
                'cep' => '08410-000',
                'localOcorrencia' => 'Rua Inácio Moreira, 444',
                'bairroOcorrencia' => 'Guaianases',
                'statusAtendimento' => 'realizado',
                'idVitima' => 1,
                'idAgressor' => 1,
                'idAutoridade' => 3,
            ],
            [
                'id' => 2,
                'dataOcorrencia' => '2026-09-13',
                'dataHoraOcorrencia' => '2026-09-13 23:15:00',
                'descricaoOcorrencia' => 'Agressor permaneceu na calçada do prédio por mais de vinte minutos efetuando ligações insistentes. Conduzido ao distrito policial para lavratura do termo circunstanciado.',
                'tipoOcorrencia' => 'Perseguição (Stalking)',
                'gravidadeOcorrencia' => 'alta',
                'cep' => '01011-010',
                'localOcorrencia' => 'Rua Coronel Xavier de Toledo, 132',
                'bairroOcorrencia' => 'República',
                'statusAtendimento' => 'realizado',
                'idVitima' => 2,
                'idAgressor' => 2,
                'idAutoridade' => 6,
            ],
            [
                'id' => 3,
                'dataOcorrencia' => '2026-09-08',
                'dataHoraOcorrencia' => '2026-09-08 05:10:00',
                'descricaoOcorrencia' => 'Rompimento da tornozeleira eletrônica registrado pela central. Agressor localizado horas depois na casa de um familiar e recolhido ao sistema prisional.',
                'tipoOcorrencia' => 'Violação de Perímetro (Tornozeleira)',
                'gravidadeOcorrencia' => 'alta',
                'cep' => '08473-000',
                'localOcorrencia' => 'Avenida Souza Ramos, 740',
                'bairroOcorrencia' => 'Cidade Tiradentes',
                'statusAtendimento' => 'realizado',
                'idVitima' => 3,
                'idAgressor' => 3,
                'idAutoridade' => 8,
            ],
            [
                'id' => 4,
                'dataOcorrencia' => '2026-09-16',
                'dataHoraOcorrencia' => '2026-09-16 08:55:00',
                'descricaoOcorrencia' => 'Agressor abordado nas proximidades do local de trabalho da vítima. Alegou estar de passagem, mas o histórico de deslocamento contradisse a versão apresentada.',
                'tipoOcorrencia' => 'Descumprimento de Medida Protetiva',
                'gravidadeOcorrencia' => 'media',
                'cep' => '03306-000',
                'localOcorrencia' => 'Rua Serra de Botucatu, 2075',
                'bairroOcorrencia' => 'Tatuapé',
                'statusAtendimento' => 'andamento',
                'idVitima' => 4,
                'idAgressor' => 4,
                'idAutoridade' => 4,
            ],
            [
                'id' => 5,
                'dataOcorrencia' => '2026-06-30',
                'dataHoraOcorrencia' => '2026-06-30 15:45:00',
                'descricaoOcorrencia' => 'Falha prolongada no sinal do dispositivo. Verificação técnica não apontou indício de adulteração; equipamento substituído preventivamente.',
                'tipoOcorrencia' => 'Falha Técnica de Equipamento',
                'gravidadeOcorrencia' => 'baixa',
                'cep' => '05882-000',
                'localOcorrencia' => 'Rua Baltazar dos Reis, 318',
                'bairroOcorrencia' => 'Capão Redondo',
                'statusAtendimento' => 'realizado',
                'idVitima' => 5,
                'idAgressor' => 5,
                'idAutoridade' => 9,
            ],
            [
                'id' => 6,
                'dataOcorrencia' => '2026-09-14',
                'dataHoraOcorrencia' => '2026-09-14 19:20:00',
                'descricaoOcorrencia' => 'Agressor tentou entrar no condomínio alegando visita a outro morador. Portaria reteve o acesso e acionou a Patrulha Maria da Penha.',
                'tipoOcorrencia' => 'Descumprimento de Medida Protetiva',
                'gravidadeOcorrencia' => 'alta',
                'cep' => '02731-000',
                'localOcorrencia' => 'Avenida Itaberaba, 1044',
                'bairroOcorrencia' => 'Freguesia do Ó',
                'statusAtendimento' => 'realizado',
                'idVitima' => 6,
                'idAgressor' => 6,
                'idAutoridade' => 7,
            ],
            [
                'id' => 7,
                'dataOcorrencia' => '2026-09-17',
                'dataHoraOcorrencia' => '2026-09-17 02:40:00',
                'descricaoOcorrencia' => 'Tornozeleira encontrada rompida em via pública. Vítima removida para endereço protegido e difusão de busca emitida para toda a região leste.',
                'tipoOcorrencia' => 'Violação de Perímetro (Tornozeleira)',
                'gravidadeOcorrencia' => 'alta',
                'cep' => '03971-000',
                'localOcorrencia' => 'Rua Sapopemba, 7260',
                'bairroOcorrencia' => 'Sapopemba',
                'statusAtendimento' => 'aberta',
                'idVitima' => 7,
                'idAgressor' => 7,
                'idAutoridade' => 8,
            ],
            [
                'id' => 8,
                'dataOcorrencia' => '2026-09-18',
                'dataHoraOcorrencia' => '2026-09-18 06:30:00',
                'descricaoOcorrencia' => 'Dispositivo com bateria crítica por mais de doze horas. Agressor intimado a comparecer à central de monitoramento no mesmo dia.',
                'tipoOcorrencia' => 'Falha Técnica de Equipamento',
                'gravidadeOcorrencia' => 'media',
                'cep' => '05758-001',
                'localOcorrencia' => 'Avenida Carlos Caldeira Filho, 2210',
                'bairroOcorrencia' => 'Campo Limpo',
                'statusAtendimento' => 'andamento',
                'idVitima' => 8,
                'idAgressor' => 8,
                'idAutoridade' => 9,
            ],
            [
                'id' => 9,
                'dataOcorrencia' => '2026-08-17',
                'dataHoraOcorrencia' => '2026-08-17 18:05:00',
                'descricaoOcorrencia' => 'Agressor localizado a poucos metros da residência da vítima, com histórico de ameaças por mensagem. Prisão preventiva representada e deferida dois dias depois.',
                'tipoOcorrencia' => 'Ameaça',
                'gravidadeOcorrencia' => 'alta',
                'cep' => '05110-000',
                'localOcorrencia' => 'Avenida Mutinga, 2830',
                'bairroOcorrencia' => 'Pirituba',
                'statusAtendimento' => 'realizado',
                'idVitima' => 9,
                'idAgressor' => 9,
                'idAutoridade' => 5,
            ],
            [
                'id' => 10,
                'dataOcorrencia' => '2026-09-18',
                'dataHoraOcorrencia' => '2026-09-18 14:10:00',
                'descricaoOcorrencia' => 'Alerta gerado por imprecisão de GPS em área de sombra de sinal. Conferência da trilha confirmou que o agressor estava fora da zona de exclusão.',
                'tipoOcorrencia' => 'Alerta Improcedente',
                'gravidadeOcorrencia' => 'baixa',
                'cep' => '04941-003',
                'localOcorrencia' => 'Estrada do M\'Boi Mirim, 5190',
                'bairroOcorrencia' => 'Jardim Ângela',
                'statusAtendimento' => 'realizado',
                'idVitima' => 10,
                'idAgressor' => 10,
                'idAutoridade' => 9,
            ],
        ];

        /*
         * Ocorrências 11 a 30: datas RELATIVAS a "hoje", para que o dashboard, o mapa e as estatísticas
         * sempre tenham movimento recente (abertas = vermelho, em andamento = amarelo, encerradas = verde).
         * Formato: [id, dias atrás, hora, tipo, gravidade, CEP, local, bairro, status, par vítima/agressor, autoridade, descrição, relato (só encerradas)]
         */
        $extras = [
            [11, 0, '07:25', 'Descumprimento de Medida Protetiva', 'alta', '03306-000', 'Rua Tuiuti, 1200', 'Tatuapé', 'aberta', 4, 4, 'Vítima aciona o botão de pânico: agressor visto na porta do prédio.', null],
            [12, 0, '09:40', 'Violação de Perímetro (Tornozeleira)', 'alta', '02012-000', 'Avenida Cruzeiro do Sul, 980', 'Santana', 'aberta', 6, 6, 'Alerta de entrada na zona de exclusão de 500 metros.', null],
            [13, 1, '21:10', 'Ameaça', 'media', '08410-000', 'Rua Feliciano de Mendonça, 212', 'Guaianases', 'aberta', 1, 1, 'Mensagens ameaçadoras reportadas pela vítima ao canal de atendimento.', null],
            [14, 1, '23:50', 'Perseguição (Stalking)', 'alta', '05882-000', 'Estrada de Itapecerica, 4100', 'Capão Redondo', 'aberta', 5, 5, 'Veículo do agressor seguiu a vítima do trabalho até a residência.', null],
            [15, 2, '06:05', 'Falha Técnica de Equipamento', 'baixa', '03971-000', 'Avenida Sapopemba, 7000', 'Sapopemba', 'aberta', 7, 7, 'Tornozeleira sem comunicação há mais de seis horas.', null],
            [16, 3, '19:30', 'Agressão Física / Lesão Corporal', 'alta', '04941-003', 'Estrada do M\'Boi Mirim, 4900', 'Jardim Ângela', 'andamento', 10, 10, 'Vítima com lesões leves, atendida e encaminhada para exame de corpo de delito.', null],
            [17, 4, '14:20', 'Descumprimento de Medida Protetiva', 'media', '05110-000', 'Avenida Mutinga, 2900', 'Pirituba', 'andamento', 9, 9, 'Agressor abordado a 150 metros da residência da vítima.', null],
            [18, 5, '10:15', 'Violação de Perímetro (Tornozeleira)', 'media', '08473-000', 'Rua Inácio Monteiro, 6500', 'Cidade Tiradentes', 'andamento', 3, 3, 'Duas entradas consecutivas no raio de alerta registradas pela central.', null],
            [19, 5, '20:45', 'Ameaça', 'alta', '01011-010', 'Rua Barão de Itapetininga, 90', 'República', 'andamento', 2, 2, 'Ameaça de morte feita por telefone, ligação gravada pela vítima.', null],
            [20, 6, '16:00', 'Alerta Improcedente', 'baixa', '05758-001', 'Avenida Carlos Caldeira Filho, 2300', 'Campo Limpo', 'andamento', 8, 8, 'Alerta por sinal de GPS instável; trilha em conferência.', null],
            [21, 9, '22:30', 'Descumprimento de Medida Protetiva', 'alta', '02731-000', 'Avenida Itaberaba, 1100', 'Freguesia do Ó', 'realizado', 6, 6, 'Agressor tentou contato presencial com a vítima em via pública.', 'Equipe acionada, agressor conduzido ao distrito e encaminhado ao juízo por descumprimento. Vítima orientada e acompanhada até a residência.'],
            [22, 14, '13:10', 'Falha Técnica de Equipamento', 'baixa', '03306-000', 'Rua Serra de Botucatu, 2100', 'Tatuapé', 'realizado', 4, 4, 'Bateria da tornozeleira não carregava corretamente.', 'Equipamento substituído na central de monitoramento. Agressor recebeu orientação sobre o carregamento e novo dispositivo foi instalado.'],
            [23, 21, '03:40', 'Violação de Perímetro (Tornozeleira)', 'alta', '08410-000', 'Rua Inácio Moreira, 450', 'Guaianases', 'realizado', 1, 1, 'Rompimento de lacre da tornozeleira durante a madrugada.', 'Agressor localizado em residência de familiar, dispositivo recolhido e ocorrência encaminhada ao Ministério Público.'],
            [24, 30, '18:20', 'Perseguição (Stalking)', 'media', '05882-000', 'Rua Baltazar dos Reis, 320', 'Capão Redondo', 'realizado', 5, 5, 'Agressor frequentou o ponto de ônibus da vítima por três dias seguidos.', 'Reforço de patrulhamento no trajeto da vítima, advertência formal ao agressor e ampliação do raio de alerta.'],
            [25, 45, '11:05', 'Ameaça', 'baixa', '03971-000', 'Rua Sapopemba, 7300', 'Sapopemba', 'realizado', 7, 7, 'Mensagem ofensiva enviada por rede social.', 'Mensagens preservadas como prova e anexadas ao processo. Agressor notificado sobre as consequências do descumprimento.'],
            [26, 70, '17:45', 'Agressão Física / Lesão Corporal', 'alta', '02012-000', 'Rua Voluntários da Pátria, 2400', 'Santana', 'realizado', 6, 6, 'Vítima agredida na saída do trabalho.', 'Vítima socorrida e encaminhada ao hospital. Agressor preso em flagrante e recolhido; medida protetiva reforçada.'],
            [27, 110, '08:30', 'Descumprimento de Medida Protetiva', 'media', '05110-000', 'Rua Elísio Teixeira Leite, 700', 'Pirituba', 'realizado', 9, 9, 'Agressor esteve em frente à escola dos filhos.', 'Direção da escola e vítima orientadas; agressor advertido e aumento da distância mínima solicitado ao juízo.'],
            [28, 160, '21:15', 'Alerta Improcedente', 'baixa', '04941-003', 'Estrada do M\'Boi Mirim, 5200', 'Jardim Ângela', 'realizado', 10, 10, 'Alerta de perímetro causado por imprecisão do GPS.', 'Conferência da trilha confirmou que o agressor estava fora da zona de exclusão. Nenhuma providência adicional.'],
            [29, 220, '15:00', 'Violação de Perímetro (Tornozeleira)', 'media', '08473-000', 'Avenida Souza Ramos, 800', 'Cidade Tiradentes', 'realizado', 3, 3, 'Agressor circulou no raio de alerta durante a tarde.', 'Agressor intimado a comparecer à central; foi reorientado sobre os limites do perímetro.'],
            [30, 320, '09:10', 'Ameaça', 'media', '01011-010', 'Rua Coronel Xavier de Toledo, 200', 'República', 'realizado', 2, 2, 'Ameaças reiteradas por telefone ao local de trabalho.', 'Linha do agressor bloqueada para a vítima, boletim registrado e acompanhamento psicossocial oferecido.'],
        ];

        $relatorios = [];

        foreach ($extras as [$id, $dias, $hora, $tipo, $gravidade, $cep, $local, $bairro, $status, $par, $autoridade, $descricao, $relato]) {
            $dia = now()->subDays($dias)->format('Y-m-d');

            $ocorrencias[] = [
                'id' => $id,
                'dataOcorrencia' => $dia,
                'dataHoraOcorrencia' => $dia . ' ' . $hora . ':00',
                'descricaoOcorrencia' => $descricao,
                'tipoOcorrencia' => $tipo,
                'gravidadeOcorrencia' => $gravidade,
                'cep' => $cep,
                'localOcorrencia' => $local,
                'bairroOcorrencia' => $bairro,
                'statusAtendimento' => $status,
                'idVitima' => $par,
                'idAgressor' => $par,
                'idAutoridade' => $autoridade,
            ];

            if ($relato) {
                $inicio = $dia . ' ' . $hora . ':00';
                $relatorios[] = [
                    'ocorrencia_id' => $id,
                    'idAutoridade' => $autoridade,
                    'inicio' => $inicio,
                    'fim' => date('Y-m-d H:i:s', strtotime($inicio . ' +2 hours')),
                    'relato' => $relato,
                ];
            }
        }

        // Coordenadas para o mapa: centro do bairro com um pequeno deslocamento para os pontos não se sobreporem.
        foreach ($ocorrencias as $i => $ocorrencia) {
            [$lat, $lng] = Regiao::centroDoBairro($ocorrencia['bairroOcorrencia']);

            if ($lat !== null) {
                $desloc = (($ocorrencia['id'] * 37) % 100 - 50) / 100000; // até ±0,0005° (~55 m)
                $ocorrencias[$i]['latitudeOcorrencia'] = round($lat + $desloc, 7);
                $ocorrencias[$i]['longitudeOcorrencia'] = round($lng - $desloc * 1.3, 7);
            }
        }

        foreach ($ocorrencias as $ocorrencia) {
            DB::table('tbocorrencia')->updateOrInsert(
                ['id' => $ocorrencia['id']],
                array_merge($ocorrencia, [
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ])
            );
        }

        foreach ($relatorios as $relatorio) {
            DB::table('relatorios_atendimento')->updateOrInsert(
                ['ocorrencia_id' => $relatorio['ocorrencia_id']],
                array_merge($relatorio, [
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ])
            );
        }
    }
}
