@extends('layouts.admin',['pageTitle'=>'Solicitações de locais seguros','active'=>'admin.victims'])
@section('admin-content')
<header class="admin-heading"><h1>Solicitações de locais seguros</h1></header><a class="admin-back" href="{{ route('admin.victims') }}">‹ voltar</a>
<p><a class="victim-primary" href="{{ route('admin.places.create') }}">+ Registrar solicitação</a></p>
<div class="safe-request-grid">@forelse($records as $record)<section class="admin-panel safe-request-card"><h2>{{ $record->vitima->nomeVitima }}</h2><dl class="victim-info"><div><dt>CPF:</dt><dd>{{ $record->vitima->cpfVitima }}</dd></div><div><dt>Local:</dt><dd>{{ $record->tipoSolicitacao }}</dd></div><div><dt>Endereço:</dt><dd>{{ $record->logradouroSolicitacao }}, {{ $record->numLogradouroSolicitacao }} — {{ $record->cidadeSolicitacao }}</dd></div></dl>
<form class="safe-request-actions" method="POST" action="{{ route('admin.places.decide',$record->id) }}">@csrf @method('PATCH')<button class="admin-action secondary" name="decision" value="reprovado">Reprovar</button><button class="admin-action" name="decision" value="aprovado">Aprovar</button></form></section>
@empty<section class="admin-panel">Nenhuma solicitação pendente.</section>@endforelse</div>{{ $records->links() }}
@endsection
