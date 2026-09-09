@extends('estruturas.administracao', ['pageTitle' => 'Monitoramento', 'active' => 'admin.monitoring'])
@section('admin-content')
<div class="monitoring-grid">
    <section class="admin-panel monitoring-map-panel">
        <h1>Mapa de monitoramento</h1>
        <div class="monitoring-map admin-empty">
            @include('parciais.icone', ['name' => 'monitor'])
            <h2>Localizações ainda não disponíveis</h2>
            <p>O mapa será exibido quando houver uma fonte de localização configurada.</p>
        </div>
    </section>
    <aside class="monitoring-side" aria-label="Resumo do monitoramento">
        <form method="GET" action="{{ route('admin.monitoring') }}" class="admin-search">
            <label class="somente-leitor" for="monitor-search">Pesquisar dispositivos</label>
            <input id="monitor-search" name="search" placeholder="pesquisar..." value="{{ request('search') }}">
            <button type="submit" aria-label="Pesquisar">@include('parciais.icone', ['name' => 'search'])</button>
        </form>
        @include('parciais.resumo-monitoramento', ['monitoring' => true])
        @include('parciais.alertas')
    </aside>
</div>
@endsection
