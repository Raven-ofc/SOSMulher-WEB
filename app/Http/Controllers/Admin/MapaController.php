<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbAgressor;
use App\Models\TbAlerta;
use App\Models\TbLocalizacaoTornozeleira;
use App\Models\TbLocalizacaoVitima;
use App\Models\TbOcorrencia;
use App\Models\TbTornozeleira;
use App\Support\Regiao;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Tela de monitoramento: mapa com ocorrências, tornozeleiras e (opcionalmente) a última posição das vítimas.
 * Os filtros de região, CEP e bairro rodam no navegador sobre os pontos entregues aqui.
 */
class MapaController extends Controller
{
    public function index(Request $request)
    {
        $pontos = [
            'ocorrencias' => $this->ocorrencias(),
            'tornozeleiras' => $this->tornozeleiras(),
            'vitimas' => $this->vitimas(),
        ];

        $resumo = [
            'aparelhosAtivos' => TbTornozeleira::where('statusTornozeleira', 'Ativa')->count(),
            'tornozeleirasOnline' => collect($pontos['tornozeleiras'])->where('online', true)->count(),
            'ocorrenciasHoje' => TbOcorrencia::whereDate('dataOcorrencia', today())->count(),
            'ocorrenciasAbertas' => TbOcorrencia::where('statusAtendimento', TbOcorrencia::STATUS_ABERTA)->count(),
        ];

        $alertas = TbAlerta::with('violacao')->orderByDesc('dataHoraAlerta')->limit(6)->get();

        return view('administracao.monitoramento', [
            'pontos' => $pontos,
            'resumo' => $resumo,
            'alertas' => $alertas,
            'regioes' => Regiao::todas(),
            'statusOcorrencia' => TbOcorrencia::STATUS,
        ]);
    }

    private function ocorrencias(): array
    {
        return TbOcorrencia::with('vitima:id,nomeVitima')
            ->orderByDesc('dataHoraOcorrencia')
            ->get()
            ->map(function (TbOcorrencia $o) {
                $lat = $o->latitudeOcorrencia;
                $lng = $o->longitudeOcorrencia;
                if ($lat === null || $lng === null) {
                    [$lat, $lng] = Regiao::centroDoBairro($o->bairroOcorrencia);
                }

                return [
                    'id' => $o->id,
                    'titulo' => $o->tipoOcorrencia,
                    'status' => $o->statusAtendimento,
                    'statusRotulo' => $o->statusRotulo(),
                    'classe' => $o->statusClasse(),
                    'gravidade' => $o->gravidadeOcorrencia,
                    'local' => $o->localOcorrencia,
                    'bairro' => $o->bairroOcorrencia,
                    'cep' => $o->cep,
                    'regiao' => Regiao::deLocal($o->bairroOcorrencia, $o->cep),
                    'data' => Carbon::parse($o->dataHoraOcorrencia)->format('d/m/Y H:i'),
                    'lat' => $lat !== null ? (float) $lat : null,
                    'lng' => $lng !== null ? (float) $lng : null,
                    'url' => route('admin.ocorrencias.visualizar', $o->id),
                ];
            })
            ->filter(fn ($p) => $p['lat'] !== null && $p['lng'] !== null)
            ->values()
            ->all();
    }

    private function tornozeleiras(): array
    {
        $ultimas = TbLocalizacaoTornozeleira::orderByDesc('dataHoraLocalizacao')->get()->unique('idTornozeleira')->keyBy('idTornozeleira');

        return TbAgressor::with('tornozeleira')
            ->whereNotNull('idTornozeleira')
            ->get()
            ->map(function (TbAgressor $a) use ($ultimas) {
                $tz = $a->tornozeleira;
                $loc = $tz ? $ultimas->get($tz->id) : null;
                if (!$tz || !$loc) {
                    return null;
                }

                $momento = Carbon::parse($loc->dataHoraLocalizacao);

                return [
                    'id' => $tz->id,
                    'titulo' => 'Tornozeleira ' . $tz->numeroSerieTornozeleira,
                    'agressor' => $a->nomeAgressor,
                    'status' => $tz->statusTornozeleira,
                    'bateria' => (int) $tz->bateriaTornozeleira,
                    'bairro' => $a->bairroAgressor,
                    'cep' => $a->cepAgressor,
                    'regiao' => Regiao::deLocal($a->bairroAgressor, $a->cepAgressor),
                    'data' => $momento->format('d/m/Y H:i'),
                    'online' => $momento->greaterThanOrEqualTo(now()->subDay()),
                    'lat' => (float) $loc->latitudeLocalizacao,
                    'lng' => (float) $loc->longitudeLocalizacao,
                    'url' => route('admin.tornozeleiras.visualizar', $tz->id),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function vitimas(): array
    {
        $ultimas = TbLocalizacaoVitima::with('vitima.endereco')
            ->orderByDesc('dataHoraLocalizacao')
            ->get()
            ->unique('idVitima');

        return $ultimas->map(function (TbLocalizacaoVitima $l) {
            $endereco = $l->vitima?->endereco;

            return [
                'id' => $l->idVitima,
                'titulo' => $l->vitima?->nomeVitima ?? 'Vítima',
                'bairro' => $endereco?->bairroVitima,
                'cep' => $endereco?->cepVitima,
                'regiao' => Regiao::deLocal($endereco?->bairroVitima, $endereco?->cepVitima),
                'data' => Carbon::parse($l->dataHoraLocalizacao)->format('d/m/Y H:i'),
                'lat' => (float) $l->latitudeLocalizacao,
                'lng' => (float) $l->longitudeLocalizacao,
                'url' => route('admin.vitimas.visualizar', $l->idVitima),
            ];
        })->values()->all();
    }
}
