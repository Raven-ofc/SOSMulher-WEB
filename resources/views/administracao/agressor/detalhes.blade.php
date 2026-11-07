@extends('estruturas.administracao',['pageTitle'=>'Agressores','active'=>'admin.agressores.index'])
@section('admin-content')
@php
    $imagem1 = $agressor->imagemAgressor1;
    $imagem2 = $agressor->imagemAgressor2;
    $imagem3 = $agressor->imagemAgressor3;
    $imagem4 = $agressor->imagemAgressor4;
    $fotoUrl1 = $imagem1 ? (\Illuminate\Support\Str::startsWith($imagem1, ['http://', 'https://']) ? $imagem1 : asset('storage/' . ltrim($imagem1, '/'))) : null;
    $fotoUrl2 = $imagem2 ? (\Illuminate\Support\Str::startsWith($imagem2, ['http://', 'https://']) ? $imagem2 : asset('storage/' . ltrim($imagem2, '/'))) : null;
    $fotoUrl3 = $imagem3 ? (\Illuminate\Support\Str::startsWith($imagem3, ['http://', 'https://']) ? $imagem3 : asset('storage/' . ltrim($imagem3, '/'))) : null;
    $fotoUrl4 = $imagem4 ? (\Illuminate\Support\Str::startsWith($imagem4, ['http://', 'https://']) ? $imagem4 : asset('storage/' . ltrim($imagem4, '/'))) : null;
@endphp
<header class="admin-heading">
    <h1>Agressores</h1>
</header>
<a class="admin-back" href="{{ route('admin.agressores.index') }}">‹ voltar</a>
<section class="admin-panel victim-detail">
    <div class="victim-profile">
        <div class="victim-profile-heading">
            <div>
                <h2>{{ $agressor->nomeAgressor }}</h2>
                <span class="status-neutral">{{ $agressor->statusAgressor }}</span>
            </div>
            <div class="avatar-class">
                @if($fotoUrl1)
                    <img class="avatar-detalhe" src="{{ $fotoUrl1 }}" alt="Foto de {{ $agressor->nomeAgressor }}">
                @endif
                @if($fotoUrl2)
                    <img class="avatar-detalhe" src="{{ $fotoUrl2 }}" alt="Foto de {{ $agressor->nomeAgressor }}">
                @endif
                @if($fotoUrl3)
                    <img class="avatar-detalhe" src="{{ $fotoUrl3 }}" alt="Foto de {{ $agressor->nomeAgressor }}">
                @endif
                @if($fotoUrl4)
                    <img class="avatar-detalhe" src="{{ $fotoUrl4 }}" alt="Foto de {{ $agressor->nomeAgressor }}">
                @endif
            </div>
        </div>
        <dl class="victim-info">
            <div><dt>CPF:</dt><dd>{{ $agressor->cpfAgressor }}</dd></div>
            <div><dt>Nascimento:</dt><dd>{{ \Carbon\Carbon::parse($agressor->dataNascimentoAgressor)->format('d/m/Y') }}</dd></div>
            <div><dt>Mãe:</dt><dd>{{ $agressor->nomeMaeAgressor ?? '—' }}</dd></div>
            <div><dt>Pai:</dt><dd>{{ $agressor->nomePaiAgressor ?? '—' }}</dd></div>
            <div><dt>Telefone:</dt><dd>{{ $agressor->telefoneAgressor ?? '—' }}</dd></div>
            <div><dt>E-mail:</dt><dd>{{ $agressor->emailAgressor ?? '—' }}</dd></div>
            <div>
                <dt>Endereço:</dt>
                <dd>
                    {{ $agressor->logradouroAgressor }}, {{ $agressor->numLogradouroAgressor }}
                    @if($agressor->complementoAgressor) — {{ $agressor->complementoAgressor }} @endif
                    <br>{{ $agressor->bairroAgressor }} · {{ $agressor->cidadeAgressor }}/{{ $agressor->ufAgressor }}
                    @if($agressor->cepAgressor) · CEP {{ $agressor->cepAgressor }} @endif
                </dd>
            </div>
        </dl>
        <div class="victim-profile-actions">
            <a class="admin-action secondary" href="{{ route('admin.agressores.editar',$agressor->id) }}">editar info</a>
            <form class="form-status" method="POST" action="{{ route('admin.agressores.status',$agressor->id) }}">
                @csrf
                @method('PATCH')
                <label class="somente-leitor" for="status-agressor">Alterar status</label>
                <select id="status-agressor" name="status">
                    @foreach(\App\Http\Controllers\Admin\AgressorController::STATUS as $s)
                        <option value="{{ $s }}" @selected($agressor->statusAgressor === $s)>{{ $s }}</option>
                    @endforeach
                </select>
                <button class="admin-action">Atualizar status</button>
            </form>
        </div>
    </div>
    <div class="victim-related">
        <section>
            <h2>Ocorrências</h2>
            @forelse($occurrences as $item)
                <p class="victim-related-empty item-status item-{{ $item->statusClasse() }}">
                    <a href="{{ route('admin.ocorrencias.visualizar',$item->id) }}">
                        {{ $item->tipoOcorrencia }} — {{ \Carbon\Carbon::parse($item->dataOcorrencia)->format('d/m/Y') }}
                    </a>
                    @include('parciais.status-ocorrencia', ['ocorrencia' => $item])
                </p>
            @empty
                <p class="victim-related-empty">Nenhuma ocorrência registrada.</p>
            @endforelse
            {{ $occurrences->links() }}
        </section>
        <section>
            <h2>Tornozeleira {{ $agressor->tornozeleira ? $agressor->tornozeleira->numeroSerieTornozeleira : 'Não atribuída' }}</h2>
            <p>Status: </p><p class="status-neutral">{{ $agressor->tornozeleira ? $agressor->tornozeleira->statusTornozeleira : 'Não atribuída' }}</p>
            <p>Bateria: {{ $agressor->tornozeleira ? $agressor->tornozeleira->bateriaTornozeleira : 'Não atribuída' }}%</p>
        </section>
    </div>
</section>
<div class="victim-detail-footer">
    <button data-print type="button">Imprimir / salvar PDF</button>
</div>
@endsection
