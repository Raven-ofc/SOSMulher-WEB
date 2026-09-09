@extends('estruturas.administracao',['pageTitle'=>'Usuárias/Vítimas','active'=>'admin.victims'])
@section('admin-content')
<header class="admin-heading">
    <h1>Usuárias/Vítimas</h1>
</header>
<a class="admin-back" href="{{ route('admin.victims') }}">‹ voltar</a>
<section class="admin-panel victim-detail">
    <div class="victim-profile">
        <div class="victim-profile-heading">
            <h2>{{ $vitima->nomeVitima }}</h2>
            <span class="status-neutral">{{ ucfirst($vitima->statusVitima) }}</span>
        </div>
        <dl class="victim-info">
            <div>
                <dt>CPF:</dt>
                <dd>{{ $vitima->cpfVitima }}</dd>
            </div>
            <div>
                <dt>Nascimento:</dt>
                <dd>{{ \Carbon\Carbon::parse($vitima->dataNascimentoVitima)->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt>Telefone:</dt>
                <dd>{{ ($vitima->telefones()->first()?->numeroTelefoneVitima ?? '—') }}</dd>
            </div>
            <div>
                <dt>E-mail:</dt>
                <dd>{{ $vitima->emailVitima }}</dd>
            </div>
        </dl>
        <div class="victim-profile-actions">
            <a class="admin-action secondary" href="{{ route('admin.victims.edit',$vitima->id) }}">editar info</a>
            <form method="POST" action="{{ route('admin.victims.status',$vitima->id) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $vitima->statusVitima==='ativo'?'inativo':'ativo' }}">
                <button class="admin-action">{{ $vitima->statusVitima==='ativo'?'Inativar':'Ativar' }}</button>
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
        <section>
            <h2>Locais de segurança</h2>
            @forelse($places as $place)
                <div class="victim-place-preview">
                    <span>
                        {{ $place->tipoSolicitacao }}
                        <br>
                        {{ $place->logradouroSolicitacao }}
                        ,
                        {{ $place->numLogradouroSolicitacao }}
                        —
                        {{ $place->cidadeSolicitacao }}
                    </span>
                    <form method="POST" action="{{ route('admin.places.remove',$place->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">excluir</button>
                    </form>
                </div>
            @empty
                <p class="victim-related-empty">Nenhum local aprovado.</p>
            @endforelse
        </section>
    </div>
</section>
<div class="victim-detail-footer">
    <button data-print type="button">Imprimir / salvar PDF</button>
</div>
@endsection
