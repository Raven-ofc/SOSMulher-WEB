<?php

/**
 * Regiões usadas nos filtros do mapa e nas estatísticas.
 *
 * Cada bairro conhecido tem a zona e um ponto central (usado no mapa quando a
 * ocorrência ainda não tem coordenadas próprias). Para atender outra cidade, basta
 * acrescentar bairros aqui; bairros desconhecidos caem em "Outras regiões".
 */
return [
    'padrao' => 'Outras regiões',

    'zonas' => ['Centro', 'Zona Norte', 'Zona Sul', 'Zona Leste', 'Zona Oeste'],

    // Prefixos de CEP (3 primeiros dígitos) da capital paulista, usados quando o bairro não está listado.
    'prefixos_cep' => [
        '010' => 'Centro', '011' => 'Centro', '013' => 'Centro', '014' => 'Zona Oeste', '015' => 'Zona Oeste',
        '020' => 'Zona Norte', '021' => 'Zona Norte', '022' => 'Zona Norte', '023' => 'Zona Norte',
        '024' => 'Zona Norte', '025' => 'Zona Norte', '026' => 'Zona Norte', '027' => 'Zona Norte',
        '028' => 'Zona Norte', '029' => 'Zona Norte',
        '030' => 'Zona Leste', '031' => 'Zona Leste', '032' => 'Zona Leste', '033' => 'Zona Leste',
        '034' => 'Zona Leste', '035' => 'Zona Leste', '036' => 'Zona Leste', '037' => 'Zona Leste',
        '038' => 'Zona Leste', '039' => 'Zona Leste', '080' => 'Zona Leste', '081' => 'Zona Leste',
        '082' => 'Zona Leste', '083' => 'Zona Leste', '084' => 'Zona Leste', '085' => 'Zona Leste',
        '040' => 'Zona Sul', '041' => 'Zona Sul', '042' => 'Zona Sul', '043' => 'Zona Sul',
        '044' => 'Zona Sul', '045' => 'Zona Sul', '046' => 'Zona Sul', '047' => 'Zona Sul',
        '048' => 'Zona Sul', '049' => 'Zona Sul', '057' => 'Zona Sul', '058' => 'Zona Sul',
        '050' => 'Zona Oeste', '051' => 'Zona Norte', '052' => 'Zona Norte', '053' => 'Zona Oeste',
        '054' => 'Zona Oeste', '055' => 'Zona Oeste', '056' => 'Zona Oeste',
    ],

    'bairros' => [
        'Sé'                 => ['zona' => 'Centro',     'lat' => -23.5505, 'lng' => -46.6333],
        'República'          => ['zona' => 'Centro',     'lat' => -23.5434, 'lng' => -46.6429],
        'Consolação'         => ['zona' => 'Centro',     'lat' => -23.5527, 'lng' => -46.6600],
        'Bela Vista'         => ['zona' => 'Centro',     'lat' => -23.5646, 'lng' => -46.6485],
        'Liberdade'          => ['zona' => 'Centro',     'lat' => -23.5593, 'lng' => -46.6350],
        'Santa Cecília'      => ['zona' => 'Centro',     'lat' => -23.5384, 'lng' => -46.6504],
        'Brás'               => ['zona' => 'Centro',     'lat' => -23.5451, 'lng' => -46.6182],

        'Santana'            => ['zona' => 'Zona Norte', 'lat' => -23.5017, 'lng' => -46.6256],
        'Freguesia do Ó'     => ['zona' => 'Zona Norte', 'lat' => -23.4954, 'lng' => -46.6955],
        'Brasilândia'        => ['zona' => 'Zona Norte', 'lat' => -23.4579, 'lng' => -46.6908],
        'Pirituba'           => ['zona' => 'Zona Norte', 'lat' => -23.4833, 'lng' => -46.7313],
        'Casa Verde'         => ['zona' => 'Zona Norte', 'lat' => -23.5091, 'lng' => -46.6523],
        'Tucuruvi'           => ['zona' => 'Zona Norte', 'lat' => -23.4741, 'lng' => -46.6035],
        'Vila Maria'         => ['zona' => 'Zona Norte', 'lat' => -23.5203, 'lng' => -46.5836],

        'Tatuapé'            => ['zona' => 'Zona Leste', 'lat' => -23.5405, 'lng' => -46.5768],
        'Mooca'              => ['zona' => 'Zona Leste', 'lat' => -23.5586, 'lng' => -46.5993],
        'Penha'              => ['zona' => 'Zona Leste', 'lat' => -23.5258, 'lng' => -46.5424],
        'Itaquera'           => ['zona' => 'Zona Leste', 'lat' => -23.5400, 'lng' => -46.4570],
        'Guaianases'         => ['zona' => 'Zona Leste', 'lat' => -23.5416, 'lng' => -46.4147],
        'Cidade Tiradentes'  => ['zona' => 'Zona Leste', 'lat' => -23.5827, 'lng' => -46.4004],
        'Sapopemba'          => ['zona' => 'Zona Leste', 'lat' => -23.6151, 'lng' => -46.5082],
        'São Miguel Paulista' => ['zona' => 'Zona Leste', 'lat' => -23.4937, 'lng' => -46.4440],
        'Vila Prudente'      => ['zona' => 'Zona Leste', 'lat' => -23.5848, 'lng' => -46.5817],

        'Santo Amaro'        => ['zona' => 'Zona Sul',   'lat' => -23.6506, 'lng' => -46.7102],
        'Capão Redondo'      => ['zona' => 'Zona Sul',   'lat' => -23.6597, 'lng' => -46.7792],
        'Campo Limpo'        => ['zona' => 'Zona Sul',   'lat' => -23.6244, 'lng' => -46.7620],
        'Jardim Ângela'      => ['zona' => 'Zona Sul',   'lat' => -23.6918, 'lng' => -46.7728],
        'Interlagos'         => ['zona' => 'Zona Sul',   'lat' => -23.6810, 'lng' => -46.6780],
        'Grajaú'             => ['zona' => 'Zona Sul',   'lat' => -23.7568, 'lng' => -46.6970],
        'Ipiranga'           => ['zona' => 'Zona Sul',   'lat' => -23.5876, 'lng' => -46.6094],
        'Vila Mariana'       => ['zona' => 'Zona Sul',   'lat' => -23.5894, 'lng' => -46.6345],
        'Moema'              => ['zona' => 'Zona Sul',   'lat' => -23.6015, 'lng' => -46.6652],

        'Pinheiros'          => ['zona' => 'Zona Oeste', 'lat' => -23.5614, 'lng' => -46.6925],
        'Lapa'               => ['zona' => 'Zona Oeste', 'lat' => -23.5222, 'lng' => -46.7019],
        'Butantã'            => ['zona' => 'Zona Oeste', 'lat' => -23.5712, 'lng' => -46.7258],
        'Vila Leopoldina'    => ['zona' => 'Zona Oeste', 'lat' => -23.5276, 'lng' => -46.7336],
        'Perdizes'           => ['zona' => 'Zona Oeste', 'lat' => -23.5365, 'lng' => -46.6783],
        'Rio Pequeno'        => ['zona' => 'Zona Oeste', 'lat' => -23.5683, 'lng' => -46.7467],
    ],
];
