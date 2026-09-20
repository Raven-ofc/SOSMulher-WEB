@extends('estruturas.administracao',['pageTitle'=>'Solicitações de locais seguros','active'=>'admin.vitimas.solicitacoes'])
@section('admin-content')
<header class="admin-heading">
    <h1>Solicitações de locais seguros</h1>
</header>
<a class="admin-back" href="{{ route('admin.vitimas.index') }}">‹ voltar</a>
<p>
    <a class="victim-primary" href="{{ route('admin.locais-seguros.criar') }}">+ Registrar solicitação</a>
</p>
<div class="safe-request-grid">
    @forelse($solicitacoes as $solicitacao)
        <section class="admin-panel safe-request-card">
            <h2>{{ $solicitacao->vitima->nomeVitima }}</h2>
            <dl class="victim-info">
                <div>
                    <dt>CPF:</dt>
                    <dd>{{ $solicitacao->vitima->cpfVitima }}</dd>
                </div>
                <div>
                    <dt>Local:</dt>
                    <dd>{{ $solicitacao->tipoSolicitacao }}</dd>
                </div>
                <div>
                    <dt>Endereço:</dt>
                    <dd>
                        {{ $solicitacao->logradouroSolicitacao }}
                        ,
                        {{ $solicitacao->numLogradouroSolicitacao }}
                        —
                        {{ $solicitacao->cidadeSolicitacao }}
                    </dd>
                </div>
            </dl>
            <form class="safe-request-actions" method="POST" action="{{ route('admin.locais-seguros.decidir',$solicitacao->id) }}">
                @csrf
                @method('PATCH')
                <button class="admin-action secondary" name="decision" value="reprovado">Reprovar</button>
                <button class="admin-action" name="decision" value="aprovado">Aprovar</button>
            </form>
        </section>
    @empty
        <section class="admin-panel">
            Nenhuma solicitação pendente.
        </section>
    @endforelse
</div>
{{ $solicitacoes->links() }}
@endsection