@extends('layouts.admin', ['pageTitle' => 'Ocorrências', 'active' => 'admin.occurrences'])
@section('admin-content')
<header class="admin-heading"><h1>Ocorrências</h1></header>
<x-admin-stats />
<form class="occurrence-filters" method="GET" action="{{ route('admin.occurrences') }}">
    <div class="admin-search"><label class="sr-only" for="occurrence-search">Pesquisar ocorrências</label><input id="occurrence-search" name="search" placeholder="pesquisar..." value="{{ request('search') }}"><button aria-label="Pesquisar" type="submit"><x-admin-icon name="search" /></button></div>
    <div class="admin-date-filter"><label>De<input type="date" name="from" value="{{ request('from') }}"></label><label>Até<input type="date" name="to" value="{{ request('to') }}"></label></div>
    <label class="sr-only" for="occurrence-status">Status</label>
    <select id="occurrence-status" name="status"><option value="">Status</option><option value="andamento" @selected(request('status') === 'andamento')>Andamento</option><option value="finalizado" @selected(request('status') === 'finalizado')>Finalizado</option></select>
</form>
<section class="admin-panel occurrence-table-panel" aria-label="Lista de ocorrências">
    <div class="admin-table-scroll"><table class="admin-table"><thead><tr><th>Data/Hora</th><th>Ocorrência</th><th>Local</th><th>Bairro</th><th>Tornozeleira</th><th>Status</th></tr></thead>
    <tbody><tr><td colspan="6"><div class="admin-empty table-empty"><x-admin-icon name="clip" /><h2>Ocorrências ainda não disponíveis</h2><p>Os registros serão exibidos aqui quando estiverem disponíveis.</p><a class="admin-text-link" href="{{ route('admin.occurrences.preview') }}">Visualizar tela de atendimento</a></div></td></tr></tbody></table></div>
</section>
@endsection
