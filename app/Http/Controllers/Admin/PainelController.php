<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbOcorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PainelController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | TODAS AS OCORRÊNCIAS
        |--------------------------------------------------------------------------
        */

        $ocorrencias = TbOcorrencia::with([
            'vitima',
            'agressor',
            'autoridade',
        ])
        ->orderByDesc('dataHoraOcorrencia')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | OCORRÊNCIAS DA ÚLTIMA SEMANA
        |--------------------------------------------------------------------------
        */

        $semanal = TbOcorrencia::whereDate(
            'dataOcorrencia',
            '>=',
            today()->subDays(6)
        )
        ->whereDate(
            'dataOcorrencia',
            '<=',
            today()
        )
        ->select(
            'tipoOcorrencia',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('tipoOcorrencia')
        ->orderBy('tipoOcorrencia')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | TODAS AS OCORRÊNCIAS POR TIPO
        |
        | É ESTE DADO QUE ALIMENTA O GRÁFICO DE ROSCA.
        |--------------------------------------------------------------------------
        */

        $porTipo = TbOcorrencia::select(
            'tipoOcorrencia',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('tipoOcorrencia')
        ->orderByDesc('total')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | OCORRÊNCIAS POR GRAVIDADE
        |--------------------------------------------------------------------------
        */

        $porGravidade = TbOcorrencia::select(
            'gravidadeOcorrencia',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('gravidadeOcorrencia')
        ->orderByDesc('total')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | OCORRÊNCIAS POR STATUS
        |--------------------------------------------------------------------------
        */

        $porStatus = TbOcorrencia::select(
            'statusAtendimento',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('statusAtendimento')
        ->orderByDesc('total')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | OCORRÊNCIAS POR BAIRRO
        |--------------------------------------------------------------------------
        */

        $porBairro = TbOcorrencia::select(
            'bairroOcorrencia',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('bairroOcorrencia')
        ->orderByDesc('total')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | OCORRÊNCIAS POR LOCAL
        |--------------------------------------------------------------------------
        */

        $porLocal = TbOcorrencia::select(
            'localOcorrencia',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('localOcorrencia')
        ->orderByDesc('total')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | ÚLTIMO SEMESTRE
        |--------------------------------------------------------------------------
        */

        $mensal = collect(range(5, 0))->map(function ($n) {

            $data = today()
                ->startOfMonth()
                ->subMonths($n);

            return [
                'rotulo' => $data->format('m/Y'),

                'total' => TbOcorrencia::whereBetween(
                    'dataOcorrencia',
                    [
                        $data->toDateString(),
                        $data->copy()->endOfMonth()->toDateString()
                    ]
                )->count(),
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | MÉTRICAS PRINCIPAIS
        |--------------------------------------------------------------------------
        */

        $metricas = [

            'Total de ocorrências' =>
                TbOcorrencia::count(),

            'Ocorrências hoje' =>
                TbOcorrencia::whereDate(
                    'dataOcorrencia',
                    today()
                )->count(),

            'Em andamento' =>
                TbOcorrencia::where(
                    'statusAtendimento',
                    'andamento'
                )->count(),

            'Atendimentos finalizados' =>
                TbOcorrencia::where(
                    'statusAtendimento',
                    'concluido'
                )->count(),

            'Ocorrências graves' =>
                TbOcorrencia::where(
                    'gravidadeOcorrencia',
                    'Alta'
                )->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | 5 OCORRÊNCIAS MAIS RECENTES
        |--------------------------------------------------------------------------
        */

        $recentes = TbOcorrencia::with([
            'vitima',
            'agressor',
            'autoridade',
        ])
        ->orderByDesc('dataHoraOcorrencia')
        ->limit(5)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | TOTAL PARA O GRÁFICO
        |
        | Aqui usamos TODAS as ocorrências.
        |--------------------------------------------------------------------------
        */

        $total = $porTipo->sum('total');


        /*
        |--------------------------------------------------------------------------
        | CORES DO GRÁFICO DE ROSCA
        |--------------------------------------------------------------------------
        */

        $offset = 0;

        $paradas = [];

        $cores = [
            '#ff6a59',
            '#ffc64f',
            '#49b5ee',
            '#35b76d',
            '#a174ee',
        ];


        /*
        |--------------------------------------------------------------------------
        | CADA FATIA REPRESENTA UM TIPO DE OCORRÊNCIA
        |--------------------------------------------------------------------------
        */

        foreach ($porTipo as $index => $ocorrencia) {

            $proximo =
                $offset +
                ($ocorrencia->total / max(1, $total)) * 100;

            $paradas[] =
                $cores[$index % count($cores)] .
                ' ' .
                $offset .
                '% ' .
                $proximo .
                '%';

            $offset = $proximo;
        }


        /*
        |--------------------------------------------------------------------------
        | IDENTIFICA ESTATÍSTICAS OU DASHBOARD
        |--------------------------------------------------------------------------
        */

        $estatisticas = $request->routeIs(
            'admin.estatisticas'
        );


        /*
        |--------------------------------------------------------------------------
        | ENVIA OS DADOS PARA A VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'administracao.painel',
            compact(
                'ocorrencias',
                'semanal',
                'mensal',
                'metricas',
                'recentes',
                'estatisticas',
                'total',
                'paradas',
                'porTipo',
                'porGravidade',
                'porStatus',
                'porBairro',
                'porLocal'
            )
        );
    }
}