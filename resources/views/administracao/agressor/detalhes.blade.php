@extends('estruturas.administracao',['pageTitle'=>'Agressores','active'=>'admin.aggressors'])
@section('admin-content')
<header class="admin-heading">
    <h1>Agressores</h1>
</header>
<a class="admin-back" href="{{ route('admin.aggressors') }}">‹ voltar</a>
<section class="admin-panel victim-detail">
    <div class="victim-profile">
        <div class="victim-profile-heading">
            <h2>{{ $agressor->nomeAgressor }}</h2>
            <span class="status-neutral">{{ ucfirst($agressor->statusAgressor) }}</span>
        </div>
        <dl class="victim-info">
            <div>
                <dt>CPF:</dt>
                <dd>{{ $agressor->cpfAgressor }}</dd>
            </div>
            <div>
                <dt>Nascimento:</dt>
                <dd>{{ \Carbon\Carbon::parse($agressor->dataNascimentoAgressor)->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt>Telefone:</dt>
                <dd>{{ ($agressor->telefoneAgressor ?? '—') }}</dd>
            </div>
        </dl>
        <div class="victim-profile-actions">
            <a class="admin-action secondary" href="{{ route('admin.aggressors.edit',$agressor->id) }}">editar info</a>
            <form method="POST" action="{{ route('admin.aggressors.status',$agressor->id) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $agressor->statusAgressor==='ativo'?'inativo':'ativo' }}">
                <button class="admin-action">{{ $agressor->statusAgressor==='ativo'?'Inativar':'Ativar' }}</button>
            </form>
        </div>
    </div>
    <div class="victim-related">
        <section>
            <h2>Ocorrências</h2>
            @forelse($occurrences as $item)
                <p class="victim-related-empty">
                    <a href="{{ route('admin.occurrences.show',$item->id) }}">
                        {{ $item->tipoOcorrencia }}
                        —
                        {{ $item->dataOcorrencia }}
                    </a>
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
