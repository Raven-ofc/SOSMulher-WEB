<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbAgressor;
use App\Models\TbMedida;
use App\Models\TbOcorrencia;
use App\Models\TbTornozeleira;
use App\Models\TbVitima;
use App\Support\Regiao;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Dashboard (home) e página de Estatísticas.
 * Os agrupamentos são feitos em PHP (sobre colunas leves) para funcionar igual em MySQL, MariaDB e SQLite.
 */
class PainelController extends Controller
{
    private const DIAS_SEMANA = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

    public function index(Request $request)
    {
        $estatisticas = $request->routeIs('admin.estatisticas');

        $ocorrencias = TbOcorrencia::select([
            'id', 'dataOcorrencia', 'dataHoraOcorrencia', 'tipoOcorrencia', 'gravidadeOcorrencia',
            'statusAtendimento', 'bairroOcorrencia', 'cep', 'idAgressor',
        ])->get();

        $recentes = TbOcorrencia::with(['vitima', 'agressor'])
            ->orderByDesc('dataHoraOcorrencia')
            ->limit(5)
            ->get();

        $hoje = today();

        $metricas = [
            'Total de ocorrências' => $ocorrencias->count(),
            'Ocorrências hoje' => $ocorrencias->filter(fn ($o) => substr((string) $o->dataOcorrencia, 0, 10) === $hoje->toDateString())->count(),
            'Abertas' => $ocorrencias->where('statusAtendimento', TbOcorrencia::STATUS_ABERTA)->count(),
            'Em andamento' => $ocorrencias->where('statusAtendimento', TbOcorrencia::STATUS_ANDAMENTO)->count(),
            'Encerradas' => $ocorrencias->where('statusAtendimento', TbOcorrencia::STATUS_ENCERRADA)->count(),
            'Ocorrências graves' => $ocorrencias->where('gravidadeOcorrencia', 'alta')->count(),
        ];

        $dados = compact('estatisticas', 'metricas', 'recentes');

        if (!$estatisticas) {
            $dados['porTipo'] = $this->contar($ocorrencias, 'tipoOcorrencia');
            $dados['mensal'] = $this->serieMensal($ocorrencias, 6);
            $dados['total'] = $ocorrencias->count();
            $dados['paradas'] = $this->paradasDonut($dados['porTipo']);

            return view('administracao.painel', $dados);
        }

        // --------------------------- Estatísticas completas ---------------------------
        $bateriaCritica = TbTornozeleira::where('statusTornozeleira', 'Ativa')->where('bateriaTornozeleira', '<', 20)->count();

        $tempos = DB::table('relatorios_atendimento')->get(['inicio', 'fim'])
            ->map(fn ($r) => Carbon::parse($r->inicio)->diffInMinutes(Carbon::parse($r->fim)));
        $tempoMedio = $tempos->isEmpty() ? null : (int) round($tempos->avg());

        $kpis = [
            'Vítimas ativas' => TbVitima::where('statusVitima', 'ativo')->count(),
            'Agressores monitorados' => TbAgressor::where('statusAgressor', 'Monitorado')->count(),
            'Medidas ativas' => TbMedida::where('statusMedida', 'Ativa')->count(),
            'Tornozeleiras ativas' => TbTornozeleira::where('statusTornozeleira', 'Ativa')->count(),
            'Bateria crítica (<20%)' => $bateriaCritica,
            'Tempo médio de atendimento' => $tempoMedio === null ? '—' : $this->formatarMinutos($tempoMedio),
        ];

        $porRegiao = $ocorrencias
            ->groupBy(fn ($o) => Regiao::deLocal($o->bairroOcorrencia, $o->cep))
            ->map->count()
            ->sortDesc();

        $porFaixa = ['Madrugada (0h–6h)' => 0, 'Manhã (6h–12h)' => 0, 'Tarde (12h–18h)' => 0, 'Noite (18h–24h)' => 0];
        $porDiaSemana = array_fill_keys(self::DIAS_SEMANA, 0);
        foreach ($ocorrencias as $o) {
            $momento = Carbon::parse($o->dataHoraOcorrencia);
            $hora = (int) $momento->format('G');
            $faixa = $hora < 6 ? 'Madrugada (0h–6h)' : ($hora < 12 ? 'Manhã (6h–12h)' : ($hora < 18 ? 'Tarde (12h–18h)' : 'Noite (18h–24h)'));
            $porFaixa[$faixa]++;
            $porDiaSemana[self::DIAS_SEMANA[(int) $momento->format('w')]]++;
        }

        $reincidentes = $ocorrencias->groupBy('idAgressor')->map->count()->filter(fn ($n) => $n > 1)->count();

        $graficos = [
            'tipo' => $this->paraGrafico($this->contar($ocorrencias, 'tipoOcorrencia')),
            'gravidade' => $this->paraGrafico($this->contar($ocorrencias, 'gravidadeOcorrencia', ['alta', 'media', 'baixa']), fn ($k) => ucfirst($k === 'media' ? 'média' : $k)),
            'status' => $this->paraGrafico($this->contar($ocorrencias, 'statusAtendimento', array_keys(TbOcorrencia::STATUS)), fn ($k) => TbOcorrencia::STATUS[$k][0] ?? $k),
            'bairro' => $this->paraGrafico($this->contar($ocorrencias, 'bairroOcorrencia')->take(8)),
            'regiao' => $this->paraGrafico($porRegiao),
            'faixa' => ['labels' => array_keys($porFaixa), 'values' => array_values($porFaixa)],
            'diaSemana' => ['labels' => array_keys($porDiaSemana), 'values' => array_values($porDiaSemana)],
            'mensal' => [
                'labels' => $this->serieMensal($ocorrencias, 12)->pluck('rotulo')->all(),
                'values' => $this->serieMensal($ocorrencias, 12)->pluck('total')->all(),
            ],
            'diario' => $this->serieDiaria($ocorrencias, 30),
            'medidas' => $this->paraGrafico(
                TbMedida::select('statusMedida', DB::raw('COUNT(*) as total'))->groupBy('statusMedida')->pluck('total', 'statusMedida')
            ),
            'tornozeleiras' => $this->paraGrafico(
                TbTornozeleira::select('statusTornozeleira', DB::raw('COUNT(*) as total'))->groupBy('statusTornozeleira')->pluck('total', 'statusTornozeleira')
            ),
            'risco' => $this->paraGrafico(
                TbMedida::select('nivelRiscoMedida', DB::raw('COUNT(*) as total'))->groupBy('nivelRiscoMedida')->pluck('total', 'nivelRiscoMedida'),
                fn ($k) => TbMedida::RISCOS[$k] ?? $k
            ),
            'bateria' => $this->faixasBateria(),
        ];

        $dados += compact('kpis', 'graficos', 'reincidentes');
        $dados['total'] = $ocorrencias->count();

        return view('administracao.painel', $dados);
    }

    /** @return \Illuminate\Support\Collection<string,int> */
    private function contar($ocorrencias, string $campo, array $ordemFixa = [])
    {
        $contagem = $ocorrencias->groupBy($campo)->map->count();

        if ($ordemFixa) {
            $ordenada = collect();
            foreach ($ordemFixa as $chave) {
                $ordenada[$chave] = (int) ($contagem[$chave] ?? 0);
            }

            return $ordenada;
        }

        return $contagem->sortDesc();
    }

    /** Converte uma coleção chave => total em {labels, values}. */
    private function paraGrafico($colecao, ?callable $rotulo = null): array
    {
        $colecao = collect($colecao);

        return [
            'labels' => $colecao->keys()->map(fn ($k) => $rotulo ? $rotulo($k) : (string) $k)->values()->all(),
            'values' => $colecao->values()->map(fn ($v) => (int) $v)->all(),
        ];
    }

    private function serieMensal($ocorrencias, int $meses)
    {
        return collect(range($meses - 1, 0))->map(function ($n) use ($ocorrencias) {
            $inicio = today()->startOfMonth()->subMonths($n);
            $chave = $inicio->format('Y-m');

            return [
                'rotulo' => $inicio->format('m/Y'),
                'total' => $ocorrencias->filter(fn ($o) => substr((string) $o->dataOcorrencia, 0, 7) === $chave)->count(),
            ];
        });
    }

    private function serieDiaria($ocorrencias, int $dias): array
    {
        $porDia = $ocorrencias->groupBy(fn ($o) => substr((string) $o->dataOcorrencia, 0, 10))->map->count();
        $labels = [];
        $values = [];

        for ($i = $dias - 1; $i >= 0; $i--) {
            $dia = today()->subDays($i);
            $labels[] = $dia->format('d/m');
            $values[] = (int) ($porDia[$dia->toDateString()] ?? 0);
        }

        return compact('labels', 'values');
    }

    private function faixasBateria(): array
    {
        $baterias = TbTornozeleira::where('statusTornozeleira', 'Ativa')->pluck('bateriaTornozeleira');

        $faixas = ['0–19%' => 0, '20–49%' => 0, '50–79%' => 0, '80–100%' => 0];
        foreach ($baterias as $b) {
            $faixas[$b < 20 ? '0–19%' : ($b < 50 ? '20–49%' : ($b < 80 ? '50–79%' : '80–100%'))]++;
        }

        return ['labels' => array_keys($faixas), 'values' => array_values($faixas)];
    }

    private function formatarMinutos(int $minutos): string
    {
        return intdiv($minutos, 60) . 'h ' . str_pad((string) ($minutos % 60), 2, '0', STR_PAD_LEFT) . 'min';
    }

    /** Fatias do gráfico de rosca em CSS puro usado na home. */
    private function paradasDonut($porTipo): array
    {
        $total = max(1, $porTipo->sum());
        $cores = ['#ff6a59', '#ffc64f', '#49b5ee', '#35b76d', '#a174ee', '#f48fb1', '#80cbc4'];
        $offset = 0;
        $paradas = [];
        $i = 0;

        foreach ($porTipo as $quantidade) {
            $proximo = $offset + ($quantidade / $total) * 100;
            $paradas[] = $cores[$i % count($cores)] . ' ' . round($offset, 2) . '% ' . round($proximo, 2) . '%';
            $offset = $proximo;
            $i++;
        }

        return $paradas;
    }
}
