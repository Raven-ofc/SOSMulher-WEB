@extends('layouts.admin', ['pageTitle' => 'Monitoramento', 'active' => 'admin.monitoring'])
@section('admin-content')
<div class="monitoring-grid">
    <section class="admin-panel monitoring-map-panel"><h1>Mapa de monitoramento</h1>
        <div class="monitoring-map admin-empty"><x-admin-icon name="monitor" /><h2>Localizações ainda não disponíveis</h2><p>O mapa será exibido quando houver uma fonte de localização configurada.</p></div>
    </section>
    <aside class="monitoring-side" aria-label="Resumo do monitoramento">
        <form method="GET" action="{{ route('admin.monitoring') }}" class="admin-search"><label class="sr-only" for="monitor-search">Pesquisar dispositivos</label><input id="monitor-search" name="search" placeholder="pesquisar..." value="{{ request('search') }}"><button type="submit" aria-label="Pesquisar"><x-admin-icon name="search" /></button></form>
        <x-admin-stats monitoring />
        <x-admin-alerts />
    </aside>
</div>
@endsection
