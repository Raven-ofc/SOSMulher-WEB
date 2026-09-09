@extends('layouts.admin', ['pageTitle' => 'Medidas Protetivas', 'active' => 'admin.measures'])
@section('admin-content')
<header class="admin-heading"><h1>Medidas Protetivas</h1></header>
<form class="occurrence-filters victims-filters" method="GET" action="{{ route('admin.measures') }}">
    <a class="victim-primary" href="{{ route('admin.measures.create') }}">+ Adicionar Medida</a>
    <div class="admin-search"><label class="sr-only" for="measure-search">Pesquisar medidas protetivas</label><input id="measure-search" name="search" value="{{ request('search') }}" placeholder="pesquisar..."><button type="submit" aria-label="Pesquisar"><x-admin-icon name="search" /></button></div>
    <label class="sr-only" for="measure-status">Status</label><select name="status" id="measure-status"><option value="">Status</option><option value="ativo" @selected(request('status') === 'ativo')>Ativo</option><option value="inativo" @selected(request('status') === 'inativo')>Inativo</option></select>
</form>
<section class="admin-panel occurrence-table-panel victims-table" aria-label="Lista de medidas protetivas">
    <div class="admin-table-scroll"><table class="admin-table"><thead><tr><th>Nº Medida</th><th>Agressor</th><th>Tornozeleira</th><th>Bateria</th><th>Vítima</th><th>Raio do perímetro</th><th>Status</th></tr></thead>
    <tbody><tr><td colspan="7"><div class="admin-empty table-empty"><x-admin-icon name="book" /><h2>Medidas ainda não disponíveis</h2><p>Os registros serão exibidos aqui quando estiverem disponíveis.</p><a class="admin-text-link" href="{{ route('admin.measures.preview') }}">Visualizar tela individual</a></div></td></tr></tbody></table></div>
</section>
@endsection
