@extends('layouts.admin', ['pageTitle' => 'Gerenciamento de Tornozeleiras', 'active' => 'admin.devices'])
@section('admin-content')
<header class="admin-heading"><h1>Gerenciamento de Tornozeleiras</h1></header>
<form class="occurrence-filters victims-filters" method="GET" action="{{ route('admin.devices') }}">
    <a class="victim-primary" href="{{ route('admin.devices.create') }}">+ Adicionar</a>
    <div class="admin-search"><label class="sr-only" for="device-search">Pesquisar tornozeleiras</label><input id="device-search" name="search" value="{{ request('search') }}" placeholder="pesquisar..."><button type="submit" aria-label="Pesquisar"><x-admin-icon name="search" /></button></div>
    <label class="sr-only" for="device-status">Status</label><select name="status" id="device-status"><option value="">Status</option><option value="ativa" @selected(request('status') === 'ativa')>Ativa</option><option value="inativa" @selected(request('status') === 'inativa')>Inativa</option></select>
</form>
<div class="device-grid">
    <section class="admin-panel device-card" aria-label="Prévia de um cartão de tornozeleira">
        <header><h2>Tornozeleira —</h2><span class="device-state">— <i aria-hidden="true"></i><span class="sr-only">Status não disponível</span></span></header>
        <dl class="victim-info">
            <div><dt>Agressor:</dt><dd>—</dd></div>
            <div><dt>Bateria:</dt><dd class="device-battery"><span aria-hidden="true"></span>—</dd></div>
            <div><dt>Localização:</dt><dd>—</dd></div>
        </dl>
        <p id="device-preview-note">Prévia visual. Dispositivos ainda não disponíveis.</p>
        <footer><button class="admin-action" type="button" disabled aria-describedby="device-preview-note">Inativar</button></footer>
    </section>
</div>
@endsection
