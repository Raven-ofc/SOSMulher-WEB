@extends('layouts.admin', ['pageTitle' => 'Relatórios Boletins de Ocorrências', 'active' => 'admin.reports'])
@section('admin-content')
<header class="admin-heading"><h1>Relatórios Boletins de Ocorrências</h1></header>
<form class="occurrence-filters" method="GET" action="{{ route('admin.reports') }}">
    <div class="admin-search"><label class="sr-only" for="report-search">Pesquisar relatórios</label><input id="report-search" name="search" placeholder="pesquisar..." value="{{ request('search') }}"><button type="submit" aria-label="Pesquisar"><x-admin-icon name="search" /></button></div>
    <div class="admin-date-filter"><label>De<input type="date" name="from" value="{{ request('from') }}"></label><label>Até<input type="date" name="to" value="{{ request('to') }}"></label></div>
    <label class="sr-only" for="report-status">Status</label><select id="report-status" name="status"><option value="">Status</option><option value="realizado" @selected(request('status') === 'realizado')>Realizado</option><option value="andamento" @selected(request('status') === 'andamento')>Andamento</option></select>
</form>
<section class="admin-panel occurrence-table-panel" aria-label="Lista de relatórios">
    <div class="admin-table-scroll"><table class="admin-table"><thead><tr><th>Data/Hora</th><th>Ocorrência</th><th>Local</th><th>Bairro</th><th>Tornozeleira</th><th><span class="sr-only">Abrir relatório</span></th></tr></thead>
    <tbody><tr><td colspan="6"><div class="admin-empty table-empty"><x-admin-icon name="archive" /><h2>Relatórios ainda não disponíveis</h2><p>Os boletins de ocorrências serão exibidos aqui quando estiverem disponíveis.</p><a class="admin-text-link" href="{{ route('admin.reports.preview') }}">Visualizar tela individual <span aria-hidden="true">↗</span></a></div></td></tr></tbody></table></div>
</section>
@endsection
