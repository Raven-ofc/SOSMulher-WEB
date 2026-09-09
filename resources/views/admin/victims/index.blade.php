@extends('layouts.admin', ['pageTitle' => 'Usuárias/Vítimas', 'active' => 'admin.victims'])
@section('admin-content')
<header class="admin-heading victims-heading">
    <h1>Usuárias/Vítimas</h1>
    <a class="safe-requests-link" href="{{ route('admin.victims.requests') }}"><span class="safe-home"><x-admin-icon name="home" /></span><span>Pedidos para adição de locais seguros</span><strong aria-label="Quantidade não disponível">—</strong><span aria-hidden="true">›</span></a>
</header>
<form class="occurrence-filters victims-filters" method="GET" action="{{ route('admin.victims') }}">
    <a class="victim-primary" href="{{ route('admin.victims.create') }}">+ Adicionar</a>
    <div class="admin-search"><label class="sr-only" for="victim-search">Pesquisar vítimas</label><input id="victim-search" name="search" value="{{ request('search') }}" placeholder="pesquisar..."><button type="submit" aria-label="Pesquisar"><x-admin-icon name="search" /></button></div>
    <label class="sr-only" for="victim-status">Status</label><select name="status" id="victim-status"><option value="">Status</option><option value="ativo" @selected(request('status') === 'ativo')>Ativo</option><option value="inativo" @selected(request('status') === 'inativo')>Inativo</option></select>
</form>
<section class="admin-panel occurrence-table-panel victims-table" aria-label="Lista de vítimas">
    <div class="admin-table-scroll"><table class="admin-table"><thead><tr><th>Nome</th><th>CPF</th><th>Telefone</th><th>E-mail</th><th>Nº Medida</th><th>Status</th></tr></thead>
    <tbody><tr><td colspan="6"><div class="admin-empty table-empty"><x-admin-icon name="book" /><h2>Cadastros ainda não disponíveis</h2><p>Os dados das vítimas serão exibidos aqui quando estiverem disponíveis.</p><a class="admin-text-link" href="{{ route('admin.victims.preview') }}">Visualizar tela individual</a></div></td></tr></tbody></table></div>
</section>
@endsection
