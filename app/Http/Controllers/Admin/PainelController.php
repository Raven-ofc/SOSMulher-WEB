<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbOcorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PainelController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $semanal = TbOcorrencia::whereDate('dataOcorrencia', '>=', today()->subDays(6))
            ->whereDate('dataOcorrencia', '<=', today())
            ->select('tipoOcorrencia', DB::raw('count(*) as total'))
            ->groupBy('tipoOcorrencia')
            ->orderBy('tipoOcorrencia')
            ->get();

        $mensal = collect(range(5, 0))->map(function ($n) {
            $data = today()->startOfMonth()->subMonths($n);
            return [
                'rotulo' => $data->format('m/Y'),
                'total' => TbOcorrencia::whereBetween('dataOcorrencia', [$data->toDateString(), $data->copy()->endOfMonth()->toDateString()])->count(),
            ];
        });

        $metricas = [
            'Ocorrências hoje' => TbOcorrencia::whereDate('dataOcorrencia', today())->count(),
            'Em andamento' => TbOcorrencia::where('statusAtendimento', 'andamento')->count(),
            'Atendimentos finalizados' => TbOcorrencia::where('statusAtendimento', 'realizado')->count(),
        ];

        $recentes = TbOcorrencia::latest('dataOcorrencia')->limit(5)->get();
        $estatisticas = $request->routeIs('admin.estatisticas');

        $total = $semanal->sum('total');
        $offset = 0;
        $paradas = [];
        $cores = ['#ff6a59', '#ffc64f', '#49b5ee', '#35b76d', '#a174ee'];

        foreach ($semanal as $index => $ocorrencia) {
            $proximo = $offset + $ocorrencia->total / max(1, $total) * 100;
            $paradas[] = $cores[$index % count($cores)] . ' ' . $offset . '% ' . $proximo . '%';
            $offset = $proximo;
        }

        return view('administracao.painel', compact(
            'semanal', 'mensal', 'metricas', 'recentes', 'estatisticas', 'total', 'paradas'
        ));
    }
}