<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Popula a tabela tbdelegacia.
 *
 *  - DDM          => Delegacias de Defesa da Mulher de TODO o estado de SP
 *  - Convencional => Distritos Policiais (DPs) apenas da CAPITAL
 *
 * FONTES
 *  - DDMs: relação de DDMs da Polícia Civil do Estado de SP (142 unidades),
 *    complementada com bairro/CEP da relação oficial de 2018 (STADDM) quando o
 *    endereço é o mesmo nas duas listas.
 *  - DPs da capital: Prefeitura de São Paulo (lista de 2022).
 *
 * LIMITAÇÕES (leia antes de usar em produção)
 *  - DPs do interior e da Grande SP NÃO estão incluídos: não há lista pública
 *    consolidada e verificável, e não inventamos endereços.
 *  - DDM de Jacupiranga (existe na lista oficial) ficou de fora: não foi possível
 *    associar o endereço com segurança.
 *  - Linhas marcadas com $confirmar = true usam endereço da lista de 2018 ou têm
 *    grafia duvidosa; recebem statusDelegacia = 'Ativa - endereço a confirmar'.
 *  - CEP, latitude e longitude ficam NULL quando não verificados. Geocodifique
 *    depois (Google Geocoding API, Nominatim etc.). Não há coordenadas
 *    inventadas: ponto errado pode levar uma vítima ao lugar errado.
 *  - Horário de funcionamento (24h) não é gravado: as fontes divergem.
 *
 * IDEMPOTÊNCIA
 *  - Chave: nome + tipo + cidade. Ao rodar de novo, endereço/bairro/status são
 *    atualizados; latitude/longitude só são zeradas se logradouro ou número
 *    mudaram (coordenada antiga deixaria de valer).
 */
class DelegaciaSeeder extends Seeder
{
    private const TABELA = 'tbdelegacia';

    private const TIPO_DDM = 'DDM';
    private const TIPO_CONVENCIONAL = 'Convencional';

    private const STATUS_ATIVA = 'Ativa';
    private const STATUS_CONFIRMAR = 'Ativa - endereço a confirmar';
    private const UF = 'SP';

    public function run(): void
    {
        foreach ($this->ddms() as [$nome, $cidade, $logradouro, $numero, $bairro, $cep, $confirmar]) {
            $this->gravar($nome, self::TIPO_DDM, $cidade, $logradouro, $numero, $bairro, $cep, $confirmar);
        }

        foreach ($this->distritosPoliciaisCapital() as [$numeroDp, $logradouro, $numero, $bairro]) {
            $this->gravar(
                "{$numeroDp}º DP - {$bairro}",
                self::TIPO_CONVENCIONAL,
                'São Paulo',
                $logradouro,
                $numero,
                $bairro,
                null,
                false
            );
        }
    }

    private function gravar(
        string $nome,
        string $tipo,
        string $cidade,
        string $logradouro,
        string $numero,
        ?string $bairro,
        ?string $cep,
        bool $confirmar
    ): void {
        $chave = [
            'nomeDelegacia'   => $nome,
            'tipoDelegacia'   => $tipo,
            'cidadeDelegacia' => $cidade,
        ];

        $dados = [
            'statusDelegacia'        => $confirmar ? self::STATUS_CONFIRMAR : self::STATUS_ATIVA,
            'logradouroDelegacia'    => $logradouro,
            'numLogradouroDelegacia' => $numero,
            'bairroDelegacia'        => $bairro,
            'ufDelegacia'            => self::UF,
            'cepDelegacia'           => $cep,
        ];

        $existente = DB::table(self::TABELA)->where($chave)->first();

        if (!$existente) {
            DB::table(self::TABELA)->insert($chave + $dados + [
                'latitudedelegacia'  => null,
                'longitudedelegacia' => null,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            return;
        }

        $enderecoMudou = $existente->logradouroDelegacia !== $logradouro
            || $existente->numLogradouroDelegacia !== $numero;

        // Não apaga um CEP já preenchido quando o endereço continua o mesmo.
        if ($cep === null && !$enderecoMudou) {
            unset($dados['cepDelegacia']);
        }

        $atualizacao = $dados + ['updated_at' => now()];

        if ($enderecoMudou) {
            $atualizacao['latitudedelegacia'] = null;
            $atualizacao['longitudedelegacia'] = null;
        }

        DB::table(self::TABELA)->where('id', $existente->id)->update($atualizacao);
    }

    /**
     * DDMs do estado de São Paulo.
     * Formato: [nome, cidade, logradouro, número, bairro, CEP, confirmar?]
     */
    private function ddms(): array
    {
        return [
            // ---------- Capital ----------
            // 1ª DDM funciona dentro da Casa da Mulher Brasileira
            ['1ª DDM - Centro',      'São Paulo', 'Rua Vieira Ravasco',                 '26',   'Cambuci',          '01518-030', false],
            ['2ª DDM - Sul',         'São Paulo', 'Avenida Onze de Junho',              '89',   'Vila Clementino',  '04041-050', false],
            ['3ª DDM - Oeste',       'São Paulo', 'Avenida Corifeu de Azevedo Marques', '4300', 'Jaguaré',          '05340-020', false],
            ['4ª DDM - Norte',       'São Paulo', 'Avenida Itaberaba',                  '731',  'Freguesia do Ó',   '02734-000', false],
            ['5ª DDM - Leste',       'São Paulo', 'Rua Dr. Corinto Baldoino Costa',     '400',  'Parque São Jorge', '03069-070', false],
            ['6ª DDM - Santo Amaro', 'São Paulo', 'Rua Padre José de Anchieta',         '138',  'Santo Amaro',      null,        false],
            ['7ª DDM - Itaquera',    'São Paulo', "Rua Sabbado D'Angelo",               '46',   'Itaquera',         '08040-620', false],
            ['8ª DDM - São Mateus',  'São Paulo', 'Rua Otávio Alves Dundas',            '390',  'Vila Ema',         null,        false],
            ['9ª DDM - Pirituba',    'São Paulo', 'Avenida Menotti Laudisio',           '286',  'Pirituba',         '02945-000', false],

            // ---------- Grande São Paulo ----------
            ['DDM - Barueri',               'Barueri',               'Avenida Pastor Sebastião Davino dos Reis', '756', 'Jardim Tupanci',        '06414-007', false],
            ['DDM - Carapicuíba',           'Carapicuíba',           'Avenida Rui Barbosa',                      '1582', 'Vila Santa Terezinha', '06311-001', false],
            ['DDM - Itapevi',               'Itapevi',               'Rodovia Engenheiro Renê Benedito Silva',   '830', 'Vila Boa Esperança',    null,        false],
            ['DDM - Cotia',                 'Cotia',                 'Rua Turmalina',                            '99',  'Jardim Nomura',         '06717-085', false],
            ['DDM - Santana de Parnaíba',   'Santana de Parnaíba',   'Rua Álvares de Azevedo',                   's/n', 'Jardim Anhembi',        null,        false],
            ['DDM - Diadema',               'Diadema',               'Rua Santa Rita de Cássia',                 '42',  'Centro',                '09911-270', false],
            ['DDM - Francisco Morato',      'Francisco Morato',      'Rua Gregório Gomes da Silva',              '39',  'Centro',                null,        false],
            ['DDM - Guarulhos',             'Guarulhos',             'Rua Itaverava',                            '48',  'Vila dos Camargos',     '07111-040', false],
            ['DDM - Arujá',                 'Arujá',                 'Praça Bartolomeu Bueno',                   '7',   'Centro',                null,        false],
            ['DDM - Mogi das Cruzes',       'Mogi das Cruzes',       'Avenida Antônio Nascimento Costa',         '21',  'Vila Oliveira',         null,        false],
            ['DDM - Suzano',                'Suzano',                'Rua Presidente Nereu Ramos',               '302', 'Jardim Santa Helena',   '08674-270', false],
            ['DDM - Itaquaquecetuba',       'Itaquaquecetuba',       'Avenida João Barbosa de Moraes',           '448', 'Vila Zeferina',         null,        false],
            ['DDM - Ferraz de Vasconcelos', 'Ferraz de Vasconcelos', 'Rua David Rogatis',                        '49',  'Parque Dourado',        null,        false],
            ['DDM - Osasco',                'Osasco',                'Rua Itabuna',                              '93',  'Centro',                null,        false],
            ['DDM - Santo André',           'Santo André',           'Rua Laura',                                '452', 'Vila Bastos',           null,        true],
            ['DDM - Mauá',                  'Mauá',                  'Rua General Osório',                       '75',  'Vila Bocaina',          null,        false],
            ['DDM - São Bernardo do Campo', 'São Bernardo do Campo', 'Rua Tasman',                               '540', 'Jardim do Mar',         null,        false],
            ['DDM - São Caetano do Sul',    'São Caetano do Sul',    'Rua Sivia',                                '160', 'Santa Maria',           null,        true],
            ['DDM - Taboão da Serra',       'Taboão da Serra',       'Rua Henrique de Moraes Camargo',           '162', 'Jardim Santa Cecília',  null,        false],
            ['DDM - Embu das Artes',        'Embu das Artes',        'Rua Belo Horizonte',                       '289', 'Centro',                '06803-440', false],

            // ---------- Vale do Paraíba e Litoral Norte ----------
            ['DDM - Cruzeiro',              'Cruzeiro',              'Avenida Nesralla Rubez',                   '993', 'Centro',                '12701-020', false],
            ['DDM - Guaratinguetá',         'Guaratinguetá',         'Praça Joaquim Vilela de Oliveira Marcondes', '470', null,                     null,        false],
            ['DDM - Lorena',                'Lorena',                'Rua Joaquim Cardoso Machado',              '385', 'Vila Geny',             null,        false],
            ['DDM - Jacareí',               'Jacareí',               'Rua Professor Job Aires Dias',             '201', 'Centro',                '12308-160', false],
            ['DDM - São José dos Campos',   'São José dos Campos',   'Avenida Comendador Vicente de Paula Penido', '234', 'Parque Residencial Aquarius', '12246-856', false],
            ['DDM - Caraguatatuba',         'Caraguatatuba',         'Rua Fernando Costa',                       '62',  'Sumaré',                null,        false],
            ['DDM - São Sebastião',         'São Sebastião',         'Rua Armando Sales de Oliveira',            '340', 'Vila Amélia',           null,        false],
            ['DDM - Ubatuba',               'Ubatuba',               'Rua Minas Gerais',                         '12',  'Centro',                '11680-000', false],
            ['DDM - Taubaté',               'Taubaté',               'Travessa Rochi Antonio',                   '150', null,                    null,        false],
            ['DDM - Pindamonhangaba',       'Pindamonhangaba',       'Rua Dr. Gustavo de Godoy',                 '409', null,                    null,        false],

            // ---------- Região de Campinas, Jundiaí e Bragança ----------
            ['1ª DDM - Campinas',           'Campinas',              'Avenida Dr. Antônio Carlos Sales Júnior',  '310', 'Jardim Proença',        '13100-410', false],
            ['DDM - Paulínia',              'Paulínia',              'Avenida Prefeito José Lozano Araújo',      '1515', 'Nossa Senhora Aparecida', null,     false],
            ['DDM - Valinhos',              'Valinhos',              'Avenida Onze de Agosto',                   '2499', 'Vila Embaré',          '13270-000', false],
            ['DDM - Vinhedo',               'Vinhedo',               'Avenida da Saudade',                       's/n', 'Jardim Alves Nogueira', '13280-000', false],
            ['2ª DDM - Campinas',           'Campinas',              'Rua Ferdinando Panattoni',                 '590', 'Jardim Paulicéia',      '13060-090', false],
            ['DDM - Indaiatuba',            'Indaiatuba',            'Rua Ademar de Barros',                     '125', 'Centro',                '13330-130', false],
            ['DDM - Jundiaí',               'Jundiaí',               'Avenida Nove de Julho',                    '3600', 'Jardim Paulista',      '13208-056', false],
            ['DDM - Itatiba',               'Itatiba',               'Rua Benedito da Silveira Franco',          '156', 'Vila Lanfranchi',       '13255-900', false],
            ['DDM - Itapira',               'Itapira',               'Rua Sd. Augusto',                          's/n', 'Santa Cruz',            null,        true],
            ['DDM - Mogi Guaçu',            'Mogi Guaçu',            'Avenida Nove de Abril',                    '842', 'Centro',                '13840-056', false],
            ['DDM - Mogi Mirim',            'Mogi Mirim',            'Rua Padre José',                           '601', 'Centro',                null,        false],
            ['DDM - Várzea Paulista',       'Várzea Paulista',       'Avenida José Rabelo Portela',              '417', 'Jardim Maria de Fátima', '13220-540', false],
            ['DDM - Bragança Paulista',     'Bragança Paulista',     'Avenida dos Imigrantes',                   '2',   'Taboão',                '12900-470', false],
            ['DDM - Serra Negra',           'Serra Negra',           'Rua José Maria Franco de Godoy',           '51',  'Vila Dirce',            '13930-000', false],

            // ---------- Araraquara, Ribeirão Preto e Franca ----------
            ['DDM - Araraquara',            'Araraquara',            'Alameda Rogério Pinto Ferraz',             '910', 'Jardim Primavera',      null,        false],
            ['DDM - Matão',                 'Matão',                 'Rua Affonso Maccagnan',                    '653', 'Centro',                null,        false],
            ['DDM - Itápolis',              'Itápolis',              'Avenida Duque de Caxias',                  '1384', 'Vila Santos',          '14900-000', false],
            ['DDM - Ibitinga',              'Ibitinga',              'Rua Bom Jesus',                            '986', 'Centro',                '14940-000', false],
            ['DDM - Taquaritinga',          'Taquaritinga',          'Praça Francisco Nuevo',                    's/n', 'Centro',                '15900-000', true],
            ['DDM - Barretos',              'Barretos',              'Rua 18',                                   '137', 'Centro',                null,        false],
            ['DDM - Olímpia',               'Olímpia',               'Rua David de Oliveira',                    '353', 'Centro',                '15400-000', false],
            ['DDM - Guaíra',                'Guaíra',                'Rua 26 (esq. Avenida 19)',                 's/n', 'Centro',                '14790-000', false],
            ['DDM - Bebedouro',             'Bebedouro',             'Rua Vanor Junqueira Franco',               '999', 'Centro',                '14701-320', false],
            ['DDM - Franca',                'Franca',                'Rua Voluntários da Franca',                '2557', 'São José',             null,        false],
            ['DDM - Batatais',              'Batatais',              'Rua Goiás',                                '190', 'Riachuelo',             '14300-000', false],
            ['DDM - Ribeirão Preto',        'Ribeirão Preto',        'Avenida Costábile Romano',                 '3230', 'Ribeirânia',           '14085-360', false],
            ['DDM - São Carlos',            'São Carlos',            'Rua São Sebastião',                        '1453', 'Santa Mônica',         null,        false],
            ['DDM - São Joaquim da Barra',  'São Joaquim da Barra',  'Rua Minas Gerais',                         '1192', 'Centro',               '14600-000', false],
            ['DDM - Sertãozinho',           'Sertãozinho',           'Avenida Affonso Trigo',                    '1534', 'Jardim Cinco de Dezembro', null,    false],
            ['DDM - Jaboticabal',           'Jaboticabal',           'Avenida Marechal Deodoro',                 '453', 'Centro',                '14870-180', false],
            ['DDM - Monte Alto',            'Monte Alto',            'Rua Gustavo de Godoy',                     '426', 'Centro',                '15910-000', false],

            // ---------- Bauru, Marília, Ourinhos e Tupã ----------
            ['DDM - Bauru',                 'Bauru',                 'Praça Dom Pedro II',                       '2-70', 'Centro',               null,        false],
            ['DDM - Jaú',                   'Jaú',                   'Alameda América',                          '90',  'Vila Canhos',           '17207-340', false],
            ['DDM - Lins',                  'Lins',                  'Rua Dr. Érico de Abreu Sodré',             '72',  null,                    null,        false],
            ['DDM - Marília',               'Marília',               'Rua Dr. Joaquim de Abreu Sampaio Vidal',   '48',  'Alto Cafezal',          '17506-130', false],
            ['DDM - Garça',                 'Garça',                 'Rua Carlos Gomes',                         '213', 'Vila Willians',         null,        false],
            ['DDM - Ourinhos',              'Ourinhos',              'Rua Aristides Lau Sampaio',                '159', 'Jardim Paulista',       '19907-090', false],
            ['DDM - Santa Cruz do Rio Pardo', 'Santa Cruz do Rio Pardo', 'Travessa Pedro Henrique de Oliveira',  '2',   'Estação',               '18900-000', false],
            ['DDM - Tupã',                  'Tupã',                  'Rua Mandaguaris',                          '876', 'Centro',                '17600-260', false],
            ['DDM - Bastos',                'Bastos',                'Rua 18 de Junho',                          '100', 'Centro',                '17690-000', false],

            // ---------- São José do Rio Preto e região ----------
            ['DDM - Catanduva',             'Catanduva',             'Rua Belo Horizonte',                       '297', 'Centro',                '15801-150', false],
            ['DDM - Fernandópolis',         'Fernandópolis',         'Rua Maria Batista',                        '189', 'Jardim Santa Rita',     '15600-000', false],
            ['DDM - Jales',                 'Jales',                 'Avenida João Amadeu',                      '2245', 'Centro',               '15700-006', false],
            ['DDM - Santa Fé do Sul',       'Santa Fé do Sul',       'Rua 1',                                    '960', 'Centro',                '15775-000', false],
            ['DDM - Novo Horizonte',        'Novo Horizonte',        'Rua 28 de Outubro',                        '1361', 'Vila Patti',           null,        false],
            ['DDM - São José do Rio Preto', 'São José do Rio Preto', 'Rua Floriano Peixoto',                     '2880', 'Santos Dumont',        '15020-010', false],
            ['DDM - Monte Aprazível',       'Monte Aprazível',       'Rua Dom Pedro I',                          '475', 'Centro',                '15150-000', false],
            ['DDM - José Bonifácio',        'José Bonifácio',        'Rua Domingos Fernandes Alonso',            '551', 'Centro',                '15210-000', false],
            ['DDM - Mirassol',              'Mirassol',              'Rua Benjamin Constant',                    '2405', 'Centro',               '15130-000', false],
            ['DDM - Votuporanga',           'Votuporanga',           'Rua Bahia',                                '3055', 'Patrimônio Novo',      '15500-005', false],

            // ---------- Baixada Santista e Vale do Ribeira ----------
            ['DDM - Itanhaém',              'Itanhaém',              'Rua Leopoldino de Araújo',                 '167', 'Centro',                null,        false],
            ['DDM - Mongaguá',              'Mongaguá',              'Avenida São Paulo',                        '851', 'Centro',                null,        false],
            ['DDM - Peruíbe',               'Peruíbe',               'Rua Ministro Genésio de Almeida Moura',    '76',  'Centro',                null,        false],
            ['DDM - Registro',              'Registro',              'Avenida Clara Gianotti de Souza',          '1051', 'Centro',               '11900-000', false],
            ['DDM - Praia Grande',          'Praia Grande',          'Rua Cidade de Santos',                     '499', 'Boqueirão',             null,        false],
            ['DDM - São Vicente',           'São Vicente',           'Rua Djalma Dutra',                         '132', 'Centro',                '11310-260', false],
            ['DDM - Guarujá',               'Guarujá',               'Avenida Pugliese',                         '656', 'Centro',                '11410-002', true],
            ['DDM - Cubatão',               'Cubatão',               'Avenida Brasil',                           '384', 'Jardim Casqueiro',      '11533-000', false],
            ['DDM - Santos',                'Santos',                'Rua Dr. Assis Corrêa',                     '50',  'Gonzaga',               '11055-310', false],

            // ---------- Sorocaba, Itapetininga e Avaré ----------
            ['DDM - Avaré',                 'Avaré',                 'Rua Sérgio Bernardino',                    '1069', 'Centro',               '18700-120', true],
            ['DDM - Botucatu',              'Botucatu',              'Rua Professor Gustavo Teixeira Assunção',  '159', 'Jardim Dona Nicota',    '18611-290', true],
            ['DDM - São Manuel',            'São Manuel',            'Avenida José Horácio Mellão',              '19',  'Centro',                '18650-000', true],
            ['DDM - Itapetininga',          'Itapetininga',          'Rua Higino Rolim Rosa',                    '81',  'Vila Rosa',             '18200-600', false],
            ['DDM - Tatuí',                 'Tatuí',                 'Rua Professor Joaquim Teixeira',           's/n', 'Chácara Junqueira',     null,        false],
            ['DDM - Itapeva',               'Itapeva',               'Rua Everaldo Nilton Chiavini',             '173', 'Central Park',          null,        false],
            ['DDM - Capão Bonito',          'Capão Bonito',          'Rua Shizue Titae Yao',                     '214', 'Centro',                null,        false],
            ['DDM - Sorocaba',              'Sorocaba',              'Rua Caracas',                              '846', 'Campolim',              '18046-718', false],
            ['DDM - Itu',                   'Itu',                   'Rua Goiás',                                '204', 'Brasil',                '13301-370', false],
            ['DDM - Salto',                 'Salto',                 'Rua Rodrigues Alves',                      '617', 'Centro',                '13320-140', false],
            ['DDM - São Roque',             'São Roque',             'Rua Quirino Capuzzo',                      's/n', 'Centro',                '18130-000', false],
            ['DDM - Votorantim',            'Votorantim',            'Rua Milton Novaes',                        '226', 'Jardim Icatu',          '18110-338', false],

            // ---------- Presidente Prudente, Assis e Dracena ----------
            ['DDM - Adamantina',            'Adamantina',            'Alameda Santa Cruz',                       '1171', 'Vila Joaquina',        '17800-000', false],
            ['DDM - Osvaldo Cruz',          'Osvaldo Cruz',          'Rua Hanz Klots',                           '1452', null,                   null,        false],
            ['DDM - Assis',                 'Assis',                 'Rua José Severino dos Santos',             '165', 'Vila Fiúza',            null,        false],
            ['DDM - Paraguaçu Paulista',    'Paraguaçu Paulista',    'Rua Maria Martins',                        '130', 'Vila Affini',           null,        false],
            ['DDM - Dracena',               'Dracena',               'Rua Martim Afonso',                        '1421', null,                   null,        false],
            ['DDM - Presidente Prudente',   'Presidente Prudente',   'Rua José Dias Cintra',                     '149', 'Vila Ocidental',        '19015-050', false],
            ['DDM - Rancharia',             'Rancharia',             'Rua Adamo Passianoto',                     '180', 'Jardim Primavera',      '19600-000', false],
            ['DDM - Presidente Venceslau',  'Presidente Venceslau',  'Avenida Dom Pedro II',                     '741', 'Centro',                '19400-000', false],
            ['DDM - Presidente Epitácio',   'Presidente Epitácio',   'Rua João Pepino',                          '269', 'Centro',                '19470-000', false],

            // ---------- Americana, Limeira, Piracicaba e São João da Boa Vista ----------
            ['DDM - Americana',             'Americana',             'Rua São Vito',                             '1765', 'Santa Cruz',           null,        false],
            ['DDM - Sumaré',                'Sumaré',                'Rua Dom Barreto',                          '1294', 'Centro',               '13170-903', false],
            ['DDM - Santa Bárbara d\'Oeste', 'Santa Bárbara d\'Oeste', 'Rua Duque de Caxias',                    '67',  'Centro',                null,        false],
            ['DDM - Hortolândia',           'Hortolândia',           'Rua Benedicta Pires de Assis',             '88',  'Remanso Campineiro',    null,        false],
            ['DDM - Casa Branca',           'Casa Branca',           'Rua Sete de Setembro',                     '76',  'Centro',                '13700-000', false],
            ['DDM - São José do Rio Pardo', 'São José do Rio Pardo', 'Rua Júlio de Mesquita',                    '14',  'Centro',                '13720-000', false],
            ['DDM - Araras',                'Araras',                'Rua Tiradentes',                           '904', 'Centro',                '13600-001', false],
            ['DDM - Leme',                  'Leme',                  'Avenida Vinte e Nove de Agosto',           '1190', 'Centro',               null,        false],
            ['DDM - Limeira',               'Limeira',               'Rua Presidente Humberto de Alencar Castelo Branco', '389', 'Jardim Mercedes', null,    false],
            ['DDM - Pirassununga',          'Pirassununga',          'Rua Quinze de Novembro',                   '2448', 'Centro',               '13630-140', false],
            ['DDM - Piracicaba',            'Piracicaba',            'Rua Alferes José Caetano',                 '1018', 'Centro',               '13400-120', false],
            ['DDM - Capivari',              'Capivari',              'Rua General Osório',                       '582', 'Centro',                null,        false],
            ['DDM - Rio Claro',             'Rio Claro',             'Avenida 23',                               '1300', 'Estádio',              '13500-870', false],
            ['DDM - São João da Boa Vista', 'São João da Boa Vista', 'Rua Presidente Franklin Roosevelt',        '80',  'Perpétuo Socorro',      null,        false],
            ['DDM - Vargem Grande do Sul',  'Vargem Grande do Sul',  'Rua Major Corrêa',                         '527', 'Centro',                null,        false],
            ['DDM - Espírito Santo do Pinhal', 'Espírito Santo do Pinhal', 'Praça Bento Bueno',                  's/n', 'Centro',                '13990-000', false],

            // ---------- Araçatuba e Andradina ----------
            ['DDM - Araçatuba',             'Araçatuba',             'Rua Cussy de Almeida Júnior',              '1167', 'Higienópolis',         null,        false],
            ['DDM - Birigui',               'Birigui',               'Rua Nicolau da Silva Nunes',               '291', 'Jardim Popi',           '16201-022', false],
            ['DDM - Penápolis',             'Penápolis',             'Avenida Manoel Bento da Cruz',             '223', 'Centro',                '16300-000', false],
            ['DDM - Andradina',             'Andradina',             'Rua José Augusto de Carvalho',             '1465', 'Centro',               null,        false],
            ['DDM - Ilha Solteira',         'Ilha Solteira',         'Alameda Mato Grosso (Sala B)',             '234', 'Zona Sul',              null,        false],
            ['DDM - Pereira Barreto',       'Pereira Barreto',       'Rua Francisca Senhorinha Carneiro',        '1456', 'Vila Municipal',       '15370-000', false],
        ];
    }

    /**
     * Distritos Policiais (delegacias convencionais) da CAPITAL.
     * Formato: [número do DP, logradouro, número, bairro]
     */
    private function distritosPoliciaisCapital(): array
    {
        return [
            // ---------- Centro ----------
            [1,  'Rua da Glória',                   '410', 'Liberdade'],
            [2,  'Rua Jaraguá',                     '383', 'Bom Retiro'],
            [3,  'Rua Aurora',                      '322', 'Santa Efigênia'],
            [4,  'Rua Marquês de Paranaguá',        '246', 'Consolação'],
            [5,  'Rua Professor Antonio Prudente',  '160', 'Liberdade'],
            [6,  'Rua Hermínio Lemos',              '70',  'Cambuci'],
            [12, 'Rua Rio Bonito',                  '950', 'Brás'],
            [77, 'Alameda Glete',                   '827', 'Santa Cecília'],

            // ---------- Zona Oeste ----------
            [7,  'Rua Camilo',                      '317',  'Vila Romana'],
            [14, 'Rua Deputado Lacerda Franco',     '372',  'Pinheiros'],
            [15, 'Rua Renato Paes de Barros',       '340',  'Itaim Bibi'],
            [23, 'Rua Itapecuru',                   '80',   'Perdizes'],
            [51, 'Rua Barroso Neto',                '46',   'Butantã'],
            [75, 'Rua Mario Ancona',                '530',  'Jardim Arpoador'],
            [78, 'Rua Estados Unidos',              '1608', 'Jardins'],
            [91, 'Avenida Dr. Gastão Vidigal',      '307',  'CEAGESP'],
            [93, 'Avenida Corifeu de Azevedo Marques', '4300', 'Jaguaré'],

            // ---------- Zona Leste ----------
            [8,   'Rua Sapucaia',                   '206',  'Brás'],
            [10,  'Avenida Airton Pretinin',        '69',   'Penha'],
            [18,  'Rua Juventus',                   '350',  'Alto da Mooca'],
            [21,  'Avenida Marcondes de Brito',     '1416', 'Chácara Seis de Outubro'],
            [22,  'Rua Américo Gomes da Costa',     '305',  'Vila Americana'],
            [24,  'Avenida São Miguel',             '3551', 'Ponte Rasa'],
            [29,  'Avenida Sapopemba',              '3259', 'Vila Diva'],
            [30,  'Rua Antonio de Camardo',         '69',   'Tatuapé'],
            [31,  'Avenida Conselheiro Carrão',     '2580', 'Vila Carrão'],
            [32,  "Rua Sabbado D'Angelo",           '64',   'Itaquera'],
            [41,  'Avenida Inconfidência Mineira',  '688',  'Vila Rica'],
            [42,  'Avenida Oratório',               '1588', 'Parque São Lucas'],
            [44,  'Rua Salvador Gianetti',          '386',  'Guaianases'],
            [49,  'Avenida Ragueb Chohfi',          '870',  'São Mateus'],
            [50,  'Rua Tibúrcio de Souza',          '860',  'Itaim Paulista'],
            [52,  'Rua Dr. Corinto Baldoino Costa', '400',  'Parque São Jorge'],
            [53,  'Avenida Osvaldo Pucci',          '180',  'Parque do Carmo'],
            [54,  'Avenida dos Gráficos',           '175',  'Cidade Tiradentes'],
            [56,  'Rua Dra. Esmeralda Mendes Policine', '264', 'Vila Alpina'],
            [57,  'Rua do Oratório',                '2220', 'Parque da Mooca'],
            [58,  'Rua Antúrios',                   '362',  'Vila Formosa'],
            [59,  'Rua Vistosa da Madre de Deus',   '120',  'Jardim Noêmia'],
            [62,  'Rua Ruy Pirozzelli',             '250',  'Ermelino Matarazzo'],
            [63,  'Rua Dríades',                    '50',   'Vila Jacuí'],
            [64,  'Avenida Águia de Haia (esq. Rua Sonho Gaúcho)', 's/n', 'Cidade A. E. Carvalho'],
            [65,  'Rua Estanislau de Campos',       '750',  'Artur Alvim'],
            [66,  'Avenida Osvaldo do Valle Cordeiro', '190', 'Jardim Aricanduva'],
            [67,  'Rua Severino José Fernandes',    '1900', 'Jardim Robru'],
            [68,  'Rua João da Silva Aguiar',       '850',  'Lajeado'],
            [69,  'Avenida Arquiteto Vila Nova Artigas', '720', 'Teotônio Vilela'],
            [70,  'Rua Otávio Alves Dundas',        '390',  'Sapopemba'],
            [81,  'Avenida Celso Garcia',           '2875', 'Belém'],
            [103, 'Avenida Nagib Farah Maluf',      '209',  'Itaquera'],

            // ---------- Zona Norte ----------
            [9,  'Rua dos Camarés',                 '94',   'Carandiru'],
            [13, 'Avenida Casa Verde',              '677',  'Casa Verde'],
            [19, 'Rua Amambai',                     '1181', 'Vila Maria'],
            [20, 'Rua São Zeferino',                '34',   'Jardim França'],
            [28, 'Avenida Itaberaba',               '731',  'Freguesia do Ó'],
            [33, 'Rua Joaquim de Oliveira Freitas', '1170', 'Pirituba'],
            [38, 'Rua Parada Pinto',                '2387', 'Vila Amália'],
            [39, 'Rua da Esperança',                '797',  'Vila Gustavo'],
            [40, 'Avenida Deputado Emílio Carlos',  '1251', 'Vila Santa Maria'],
            [45, 'Rua Joaquim Ferreira da Rocha',   '403',  'Vila Brasilândia'],
            [46, 'Rua Fioreli Pecicacco',           '320',  'Perus'],
            [72, 'Rua Diógenes Campos Aires',       '185',  'Vila Penteado'],
            [73, 'Rua Paulo Lincoln do Vale Pontin', '744', 'Jaçanã'],
            [74, 'Avenida Elisio Teixeira Leite',   '7461', 'Jaraguá'],
            [87, 'Avenida Menotti Laudisio',        '286',  'Vila Pereira Barreto'],
            [90, 'Alameda Terceiro Sargento Alcides de Oliveira', '52', 'Parque Novo Mundo'],

            // ---------- Zona Sul ----------
            [11,  'Rua Padre José de Anchieta',     '138',  'Santo Amaro'],
            [16,  'Avenida Onze de Junho',          '89',   'Vila Clementino'],
            [17,  'Rua Dom Luiz Lazagna',           '534',  'Ipiranga'],
            [25,  'Rua Humberto Ravello',           '9',    'Parelheiros'],
            [26,  'Avenida Padre Arlindo Vieira',   '50',   'Sacomã'],
            [27,  'Rua Demóstenes',                 '407',  'Campo Belo'],
            [34,  'Avenida Professor Francisco Morato', '2971', 'Vila Sônia'],
            [35,  'Avenida Engenheiro George Corbisier', '322', 'Jabaquara'],
            [36,  'Rua Tutóia',                     '921',  'Vila Mariana'],
            [37,  'Rua Jacaratinga',                '201',  'Campo Limpo'],
            [43,  'Rua Antonio Gil',                '1349', 'Cidade Ademar'],
            [47,  'Estrada de Itapecerica',         '5864', 'Capão Redondo'],
            [48,  'Rua Aníbal dos Anjos Carvalho',  '87',   'Cidade Dutra'],
            [55,  'Rua Aldeia de Santo Inácio',     '696',  'Parque São Rafael'],
            [80,  'Rua Leonel da Gama Beles',       's/n',  'Vila Joaniza'],
            [83,  'Rua Ângelo Bertini',             '82',   'Parque Bristol'],
            [85,  'Rua Juvenal Hudson Ferreira',    's/n',  'Jardim Mirna'],
            [89,  'Rua Domingos Simões',            '21',   'Portal do Morumbi'],
            [92,  'Rua Maria Benedita Rodrigues',   '300',  'Parque Santo Antônio'],
            [95,  'Rua Comandante Taylor',          '1180', 'Cohab Heliópolis'],
            [96,  'Avenida Engenheiro Luiz Carlos Berrini', '900', 'Brooklin'],
            [97,  'Rodovia dos Imigrantes',         'km 11,5', 'Americanópolis'],
            [98,  'Avenida Angelo Cristianini',     '467',  'Jardim Miriam'],
            [99,  'Rua Sargento Manoel Barbosa da Silva', '115', 'Campo Grande'],
            [100, 'Rua José Carlos dos Santos Marques', '301', 'Jardim Herculano'],
            [101, 'Rua Carolina Michaelis',         '370',  'Jardim das Imbuias'],
            [102, 'Avenida Robert Kennedy',         '1171', 'Socorro'],
        ];
    }
}