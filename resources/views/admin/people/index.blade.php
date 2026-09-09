@extends('layouts.admin',['pageTitle'=>$c['label'],'active'=>$c['base']])
@section('admin-content')
<header class="admin-heading victims-heading"><h1>{{ $c['label'] }}</h1>@if($c['victim'])<a class="safe-requests-link" href="{{ route('admin.victims.requests') }}">Pedidos de locais seguros</a>@endif</header>
<form class="occurrence-filters" method="GET">
<a class="victim-primary" href="{{ route($c['base'].'.create') }}">+ Adicionar</a>
<div class="admin-search"><input name="search" aria-label="Pesquisar por nome ou CPF" placeholder="Nome ou CPF" value="{{ request('search') }}"><button type="submit" aria-label="Pesquisar"><x-admin-icon name="search" /></button></div>
<select name="status" aria-label="Status"><option value="">Todos os status</option>@foreach(['ativo','inativo'] as $status)<option @selected(request('status')===$status)>{{ $status }}</option>@endforeach</select><button class="admin-action secondary">Filtrar</button>
</form>
<section class="admin-panel occurrence-table-panel"><div class="admin-table-scroll"><table class="admin-table"><thead><tr><th>Nome</th><th>CPF</th><th>Telefone</th>@if($c['victim'])<th>E-mail</th>@endif<th>Status</th><th>Abrir</th></tr></thead><tbody>
@forelse($records as $record)
<tr><td>{{ $record->{'nome'.$c['suffix']} }}</td><td>{{ $record->{'cpf'.$c['suffix']} }}</td><td>{{ $c['victim'] ? ($record->telefones->first()?->numeroTelefoneVitima ?? '—') : ($record->telefoneAgressor ?? '—') }}</td>@if($c['victim'])<td>{{ $record->emailVitima }}</td>@endif<td>{{ ucfirst($record->{'status'.$c['suffix']}) }}</td><td><a class="admin-text-link" href="{{ route($c['base'].'.show',$record->id) }}">Ver cadastro</a></td></tr>
@empty<tr><td colspan="6"><div class="admin-empty table-empty">Nenhum cadastro encontrado.</div></td></tr>@endforelse
</tbody></table></div>{{ $records->links() }}</section>
@endsection
