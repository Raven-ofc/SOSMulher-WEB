<?php

namespace App\Http\Controllers;

use App\Models\tbocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $r)
    {
        $weekly = tbocorrencia::whereDate('dataOcorrencia', '>=', today()->subDays(6))->whereDate('dataOcorrencia', '<=', today())->select('tipoOcorrencia', DB::raw('count(*) as total'))->groupBy('tipoOcorrencia')->orderBy('tipoOcorrencia')->get();
        $months = collect(range(5, 0))->map(function ($n) {
            $date = today()->startOfMonth()->subMonths($n);

            return ['label' => $date->format('m/Y'), 'total' => tbocorrencia::whereBetween('dataOcorrencia', [$date->toDateString(), $date->copy()->endOfMonth()->toDateString()])->count()];
        });
        $metrics = [
            'Ocorrências hoje' => tbocorrencia::whereDate('dataOcorrencia', today())->count(),
            'Em andamento' => tbocorrencia::where('statusAtendimento', 'andamento')->count(),
            'Atendimentos finalizados' => tbocorrencia::where('statusAtendimento', 'realizado')->count(),
        ];
        $recent = tbocorrencia::latest('dataOcorrencia')->limit(5)->get();
        $statistics = $r->routeIs('admin.statistics');

        return view('admin.overview', compact('weekly', 'months', 'metrics', 'recent', 'statistics'));
    }
}
