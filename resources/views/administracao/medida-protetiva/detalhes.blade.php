@extends('estruturas.administracao', ['pageTitle' => 'Medida ' . $medida->numeroFormatado(), 'active' => 'admin.medidas-protetivas'])
@section('admin-content')
@php
    $tz = $medida->agressor?->tornozeleira;
    $loc = $tz?->localizacoes->first();
    $fmt = fn ($d) => $d ? \Carbon\Carbon::parse($d)->format('d/m/Y') : null;
@endphp
<header class="admin-heading detail-heading">
    <h1>Medida protetiva {{ $medida->numeroFormatado() }}</h1>
</header>
<a class="admin-back" href="{{ route('admin.medidas-protetivas') }}">‹ voltar</a>

@if(session('aviso'))
    <div class="admin-panel backend-feedback aviso" role="alert">{{ session('aviso') }}</div>
@endif

<section class="admin-panel measure-detail">
    <div class="measure-summary">
        <section>
            <h2>Medida {{ $medida->numeroFormatado() }}</h2>
            <dl class="victim-info">
                <div><dt>Início:</dt><dd>{{ $fmt($medida->dataInicioMedida) }}</dd></div>
                <div><dt>Fim:</dt><dd>{{ $fmt($medida->dataFimMedida) ?? 'Prazo indeterminado' }}</dd></div>
                <div><dt>Distância mínima:</dt><dd>{{ $medida->distanciaMedida }} m</dd></div>
                <div><dt>Raio de alerta:</dt><dd>{{ $medida->raioAlertaMedida }} m</dd></div>
                <div><dt>Nível de risco:</dt><dd><span class="selo-risco risco-{{ $medida->nivelRiscoMedida }}">{{ $medida->riscoRotulo() }}</span></dd></div>
                <div><dt>Processo:</dt><dd>{{ $medida->numeroProcessoMedida ?? '—' }}</dd></div>
                <div><dt>Vara / juízo:</dt><dd>{{ $medida->varaMedida ?? '—' }}</dd></div>
                <div><dt>Registrada por:</dt><dd>{{ $medida->autoridade?->nomeAutoridade ?? '—' }}</dd></div>
            </dl>

            <h3>Restrições aplicadas</h3>
            @if(count($medida->tiposRotulos()))
                <ul class="lista-simples">
                    @foreach($medida->tiposRotulos() as $rotulo)<li>{{ $rotulo }}</li>@endforeach
                </ul>
            @else
                <p class="victim-related-empty">Não informado.</p>
            @endif
            <p class="descricao-medida">{{ $medida->descricaoMedida }}</p>
            @if($medida->observacoesMedida)
                <h3>Observações</h3>
                <p class="descricao-medida">{{ $medida->observacoesMedida }}</p>
            @endif

            <h3>Tornozeleira</h3>
            <dl class="victim-info">
                <div><dt>Número série:</dt><dd>{{ $tz?->numeroSerieTornozeleira ?? '—' }}</dd></div>
                <div><dt>Status:</dt><dd>{{ $tz?->statusTornozeleira ?? 'Não atribuída' }}</dd></div>
                <div><dt>Bateria:</dt><dd>{{ $tz ? $tz->bateriaTornozeleira . '%' : '—' }}</dd></div>
                <div><dt>Último sinal:</dt><dd>{{ $loc ? \Carbon\Carbon::parse($loc->dataHoraLocalizacao)->format('d/m/Y H:i') : '—' }}</dd></div>
            </dl>
        </section>
        <div class="measure-people">
            <section>
                <h3>Informações da Vítima</h3>
                <dl class="victim-info">
                    <div><dt>Nome:</dt><dd><a class="admin-text-link" href="{{ route('admin.vitimas.visualizar', $medida->idVitima) }}">{{ $medida->vitima?->nomeVitima ?? '—' }}</a></dd></div>
                    <div><dt>CPF:</dt><dd>{{ $medida->vitima?->cpfVitima ?? '—' }}</dd></div>
                </dl>
            </section>
            <section>
                <h3>Informações do Agressor</h3>
                <dl class="victim-info">
                    <div><dt>Nome:</dt><dd><a class="admin-text-link" href="{{ route('admin.agressores.visualizar', $medida->idAgressor) }}">{{ $medida->agressor?->nomeAgressor ?? '—' }}</a></dd></div>
                    <div><dt>CPF:</dt><dd>{{ $medida->agressor?->cpfAgressor ?? '—' }}</dd></div>
                </dl>
            </section>
        </div>
        <span class="status-neutral measure-status">{{ $medida->statusMedida }}</span>
    </div>

    <form class="form-status" method="POST" action="{{ route('admin.medidas-protetivas.status', $medida->id) }}">
        @csrf
        @method('PATCH')
        <label for="status-medida">Alterar status da medida</label>
        <select id="status-medida" name="status">
            @foreach(\App\Http\Controllers\Admin\MedidaController::STATUS as $s)
                <option value="{{ $s }}" @selected($medida->statusMedida === $s)>{{ $s }}</option>
            @endforeach
        </select>
        <button class="admin-action" type="submit">Atualizar</button>
    </form>

    <section class="measure-occurrences">
        <h2>Ocorrências:</h2>
        @forelse($ocorrencias as $item)
            <p class="victim-related-empty item-status item-{{ $item->statusClasse() }}">
                <a href="{{ route('admin.ocorrencias.visualizar', $item->id) }}">
                    {{ $item->tipoOcorrencia }} — {{ \Carbon\Carbon::parse($item->dataHoraOcorrencia)->format('d/m/Y H:i') }}
                </a>
                @include('parciais.status-ocorrencia', ['ocorrencia' => $item])
            </p>
        @empty
            <div class="measure-occurrences-empty">Nenhuma ocorrência registrada para este par vítima/agressor.</div>
        @endforelse
    </section>
</section>
<div class="victim-detail-footer">
    <button data-print type="button">Imprimir / salvar PDF <span aria-hidden="true">⇩</span></button>
</div>
@endsection
