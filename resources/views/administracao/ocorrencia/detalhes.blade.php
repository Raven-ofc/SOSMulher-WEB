@extends('estruturas.administracao',['pageTitle'=>'Ocorrência '.$ocorrencia->id,'active'=>$relatorio?'admin.relatorios.index':'admin.ocorrencias.index'])
@section('admin-content')
<header class="admin-heading">
    <h1>{{ $relatorio?'Relatório de atendimento':'Ocorrências' }}</h1>
</header>
<a class="admin-back" href="{{ route($relatorio?'admin.relatorios.index':'admin.ocorrencias.index') }}">‹ voltar</a>
<article class="admin-panel occurrence-detail">
    <section class="occurrence-summary">
        <div class="occurrence-heading">
            <h2>{{ $ocorrencia->tipoOcorrencia }}</h2>
            <span>{{ $ocorrencia->dataHoraOcorrencia ?? $ocorrencia->dataOcorrencia }}</span>
        </div>
        <dl>
            <div>
                <dt>Local:</dt>
                <dd>{{ $ocorrencia->localOcorrencia ?? '—' }}</dd>
            </div>
            <div>
                <dt>Bairro:</dt>
                <dd>{{ $ocorrencia->bairroOcorrencia ?? '—' }}</dd>
            </div>
            <div>
                <dt>Agressor:</dt>
                <dd>{{ $ocorrencia->agressor->nomeAgressor }}</dd>
            </div>
            <div>
                <dt>Vítima:</dt>
                <dd>{{ $ocorrencia->vitima->nomeVitima }}</dd>
            </div>
            <div>
                <dt>Status:</dt>
                <dd>{{ $ocorrencia->statusAtendimento }}</dd>
            </div>
        </dl>
        <p>{{ $ocorrencia->descricaoOcorrencia }}</p>
    </section>
    @if($relatorio)
        <section class="report-section">
            <h2>Relatório</h2>
            <p>
                Início:
                {{ $relatorio->inicio }}
                · Final:
                {{ $relatorio->fim }}
            </p>
            <p style="white-space:pre-wrap;overflow-wrap:anywhere">{{ $relatorio->relato }}</p>
        </section>
    @else
        <div class="detail-actions">
            <a class="admin-action" href="{{ route('admin.ocorrencias.relatorio',$ocorrencia->id) }}">Finalizar atendimento</a>
        </div>
    @endif
</article>
@if($relatorio)
    <div class="victim-detail-footer">
        <button type="button" data-print>Imprimir / salvar PDF</button>
    </div>
@endif
@endsection