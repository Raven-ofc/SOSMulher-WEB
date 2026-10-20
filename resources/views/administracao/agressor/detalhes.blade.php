@extends('estruturas.administracao',['pageTitle'=>'Agressores','active'=>'admin.agressores.index'])
@section('admin-content')
@php
    $imagem = $agressor->imagemAgressor;
    $fotoUrl = $imagem ? (\Illuminate\Support\Str::startsWith($imagem, ['http://', 'https://']) ? $imagem : asset('storage/' . ltrim($imagem, '/'))) : null;
@endphp
<header class="admin-heading">
    <h1>Agressores</h1>
</header>
<a class="admin-back" href="{{ route('admin.agressores.index') }}">‹ voltar</a>
<section class="admin-panel victim-detail">
    <div class="victim-profile">
        <div class="victim-profile-heading">
            @if($fotoUrl)
                <img class="avatar-detalhe" src="{{ $fotoUrl }}" alt="Foto de {{ $agressor->nomeAgressor }}">
            @endif
            <h2>{{ $agressor->nomeAgressor }}</h2>
            <span class="status-neutral">{{ $agressor->statusAgressor }}</span>
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
            <div><dt>Tornozeleira:</dt><dd>{{ $agressor->tornozeleira ? $agressor->tornozeleira->numeroSerieTornozeleira . ' (' . $agressor->tornozeleira->statusTornozeleira . ')' : 'Não atribuída' }}</dd></div>
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
    </div>
</section>
<div class="victim-detail-footer">
    <button data-print type="button">Imprimir / salvar PDF</button>
</div>
@endsection
