@extends('layouts.admin', ['pageTitle' => 'Agressores', 'active' => 'admin.aggressors'])
@section('admin-content')
<header class="admin-heading"><h1>Agressores</h1></header>
<form class="occurrence-filters victims-filters" method="GET" action="{{ route('admin.aggressors') }}">
    <a class="victim-primary" href="{{ route('admin.aggressors.create') }}">+ Adicionar</a>
    <div class="admin-search"><label class="sr-only" for="aggressor-search">Pesquisar agressores</label><input id="aggressor-search" name="search" value="{{ request('search') }}" placeholder="pesquisar..."><button type="submit" aria-label="Pesquisar"><x-admin-icon name="search" /></button></div>
    <label class="sr-only" for="aggressor-status">Status</label><select name="status" id="aggressor-status"><option value="">Status</option><option value="ativo" @selected(request('status') === 'ativo')>Ativo</option><option value="inativo" @selected(request('status') === 'inativo')>Inativo</option></select>
</form>
<section class="admin-panel occurrence-table-panel victims-table" aria-label="Lista de agressores">
    <div class="admin-table-scroll"><table class="admin-table"><thead><tr><th>Nome</th><th>CPF</th><th>Telefone</th><th>Tornozeleira</th><th>Nº Medida</th><th>Status</th></tr></thead>
    <tbody><tr><td colspan="6"><div class="admin-empty table-empty"><x-admin-icon name="book" /><h2>Cadastros ainda não disponíveis</h2><p>Os dados dos agressores serão exibidos aqui quando estiverem disponíveis.</p><a class="admin-text-link" href="{{ route('admin.aggressors.preview') }}">Visualizar tela individual</a></div></td></tr></tbody></table></div>
</section>
@endsection
