@extends('layouts.admin',['pageTitle'=>'Ocorrência '.$case->id,'active'=>$report?'admin.reports':'admin.occurrences'])
@section('admin-content')
<header class="admin-heading"><h1>{{ $report?'Relatório de atendimento':'Ocorrências' }}</h1></header><a class="admin-back" href="{{ route($report?'admin.reports':'admin.occurrences') }}">‹ voltar</a>
<article class="admin-panel occurrence-detail"><section class="occurrence-summary"><div class="occurrence-heading"><h2>{{ $case->tipoOcorrencia }}</h2><span>{{ $case->dataHoraOcorrencia ?? $case->dataOcorrencia }}</span></div>
<dl><div><dt>Local:</dt><dd>{{ $case->localOcorrencia ?? '—' }}</dd></div><div><dt>Bairro:</dt><dd>{{ $case->bairroOcorrencia ?? '—' }}</dd></div><div><dt>Agressor:</dt><dd>{{ $case->agressor->nomeAgressor }}</dd></div><div><dt>Vítima:</dt><dd>{{ $case->vitima->nomeVitima }}</dd></div><div><dt>Status:</dt><dd>{{ $case->statusAtendimento }}</dd></div></dl><p>{{ $case->descricaoOcorrencia }}</p></section>
@if($report)<section class="report-section"><h2>Relatório</h2><p>Início: {{ $report->inicio }} · Final: {{ $report->fim }}</p><p style="white-space:pre-wrap;overflow-wrap:anywhere">{{ $report->relato }}</p></section>
@else<div class="detail-actions"><a class="admin-action" href="{{ route('admin.occurrences.finish',$case->id) }}">Finalizar atendimento</a></div>@endif</article>
@if($report)<div class="victim-detail-footer"><button type="button" onclick="window.print()">Imprimir / salvar PDF</button></div>@endif
@endsection
