<?php

namespace App\Http\Controllers;

use App\Models\tbocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PainelController extends Controller
{
    public function index(Request $request)
    {
        $weekly = tbocorrencia::whereDate('dataOcorrencia', '>=', today()->subDays(6))->whereDate('dataOcorrencia', '<=', today())->select('tipoOcorrencia', DB::raw('count(*) as total'))->groupBy('tipoOcorrencia')->orderBy('tipoOcorrencia')->get();
        $months = collect(range(5, 0))->map(function ($n) {
            $date = today()->startOfMonth()->subMonths($n);

            return [
                'label' => $date->format('m/Y'),
                'total' => tbocorrencia::whereBetween('dataOcorrencia', [$date->toDateString(), $date->copy()->endOfMonth()->toDateString()])->count(),
            ];
        });
        $metrics = [
            'Ocorrências hoje' => tbocorrencia::whereDate('dataOcorrencia', today())->count(),
            'Em andamento' => tbocorrencia::where('statusAtendimento', 'andamento')->count(),
            'Atendimentos finalizados' => tbocorrencia::where('statusAtendimento', 'realizado')->count(),
        ];
        $recent = tbocorrencia::latest('dataOcorrencia')->limit(5)->get();
        $statistics = $request->routeIs('admin.statistics');

        $total = $weekly->sum('total');
        $offset = 0;
        $stops = [];
        $colors = ['#ff6a59', '#ffc64f', '#49b5ee', '#35b76d', '#a174ee'];

        foreach ($weekly as $index => $occurrence) {
            $next = $offset + $occurrence->total / max(1, $total) * 100;
            $stops[] = $colors[$index % count($colors)].' '.$offset.'% '.$next.'%';
            $offset = $next;
        }

        return view('administracao.painel', compact(
            'weekly', 'months', 'metrics', 'recent', 'statistics', 'total', 'stops'
        ));
    }
}
