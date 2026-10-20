@extends('estruturas.administracao', ['pageTitle' => 'Ocorrência ' . $ocorrencia->id, 'active' => 'admin.ocorrencias.index'])

@section('admin-content')

<header class="admin-heading"> <h1>Detalhes da Ocorrência</h1> </header>
<a class="admin-back" href="{{ route('admin.ocorrencias.index') }}">‹ voltar</a>

<article class="admin-panel occurrence-detail"> 
    <section class="occurrence-summary resumo-{{ $ocorrencia->statusClasse() }}"> 
        <div class="occurrence-heading"> 
            <h2>{{ $ocorrencia->tipoOcorrencia }}</h2> 
            <span>{{ $ocorrencia->dataHoraOcorrencia ?? $ocorrencia->dataOcorrencia }}</span> 
        </div> 
        <dl> 
            <div> <dt>CEP:</dt> <dd>{{ $ocorrencia->cep ?? '—' }}</dd> </div>
            <div> <dt>Local:</dt> <dd>{{ $ocorrencia->localOcorrencia ?? '—' }}</dd> </div> 
            <div> <dt>Bairro:</dt> <dd>{{ $ocorrencia->bairroOcorrencia ?? '—' }}</dd> </div> 
            <div> <dt>Agressor:</dt> <dd>{{ $ocorrencia->agressor->nomeAgressor }}</dd> </div> 
            <div> <dt>Vítima:</dt> <dd>{{ $ocorrencia->vitima->nomeVitima }}</dd> </div> 
            <div> <dt>Status:</dt> <dd>@include('parciais.status-ocorrencia', ['ocorrencia' => $ocorrencia])</dd> </div> 
        </dl> 
        <p>{{ $ocorrencia->descricaoOcorrencia }}</p> 
    </section>

@if($relatorio)
    <section class="report-section">
        <h2>Relatório</h2>
        <p>
            Início: {{ $relatorio->inicio }}
            · Final: {{ $relatorio->fim }}
        </p>
        <p style="white-space:pre-wrap;overflow-wrap:anywhere">{{ $relatorio->relato }}</p>
    </section>
@else
    <div class="detail-actions">
        @if($ocorrencia->statusAtendimento === \App\Models\TbOcorrencia::STATUS_ABERTA)
            <form method="POST" action="{{ route('admin.ocorrencias.iniciar', $ocorrencia->id) }}">
                @csrf
                <button class="admin-action" type="submit">Iniciar atendimento</button>
            </form>
        @else
            <a class="admin-action" href="{{ route('admin.ocorrencias.relatorio', $ocorrencia->id) }}">Finalizar atendimento</a>
        @endif
    </div>
@endif
</article> 
@endsection