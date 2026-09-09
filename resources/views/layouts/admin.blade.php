@extends('layouts.app', ['title' => $pageTitle ?? 'Administração'])
@section('content')
<div class="admin-shell">
    <a class="admin-skip" href="#admin-content">Ir para o conteúdo</a>
    <aside class="admin-sidebar" aria-label="Menu principal">
        <a class="admin-brand" href="{{ route('dashboard') }}" aria-label="Página inicial"><x-logo /></a>
        <nav class="admin-nav">
            @foreach ([['dashboard', 'home', 'Home'], ['admin.occurrences', 'clip', 'Ocorrências'], ['admin.monitoring', 'monitor', 'Monitoramento']] as [$name, $icon, $label])
                <a href="{{ route($name) }}" class="admin-nav-item {{ ($active ?? '') === $name ? 'is-active' : '' }}" @if(($active ?? '') === $name) aria-current="page" @endif><x-admin-icon :name="$icon" />{{ $label }}</a>
            @endforeach
            <a href="{{ route('admin.statistics') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.statistics' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.statistics') aria-current="page" @endif><x-admin-icon name="chart" />Estatísticas</a>
            <a href="{{ route('admin.reports') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.reports' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.reports') aria-current="page" @endif><x-admin-icon name="archive" />Relatórios</a>
            <div class="registration-menu">
                <a href="{{ route('admin.victims') }}" class="admin-nav-item {{ in_array($active ?? '', ['admin.victims', 'admin.aggressors', 'admin.devices', 'admin.measures']) ? 'is-active' : '' }}"><x-admin-icon name="book" />Cadastros</a>
                @if(in_array($active ?? '', ['admin.victims', 'admin.aggressors', 'admin.devices', 'admin.measures']))
                <div class="registration-subnav" aria-label="Tipos de cadastro">
                    <a href="{{ route('admin.victims') }}" @if(($active ?? '') === 'admin.victims') aria-current="page" @endif>Vítimas</a>
                    <a href="{{ route('admin.aggressors') }}" @if(($active ?? '') === 'admin.aggressors') aria-current="page" @endif>Agressores</a>
                    <a href="{{ route('admin.devices') }}" @if(($active ?? '') === 'admin.devices') aria-current="page" @endif>Tornozeleiras</a>
                    <a href="{{ route('admin.measures') }}" @if(($active ?? '') === 'admin.measures') aria-current="page" @endif>Medidas protetivas</a>
                </div>
                @endif
            </div>
        </nav>
        <div class="admin-sidebar-bottom">
            <a href="{{ route('admin.profile') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.profile' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.profile') aria-current="page" @endif><x-admin-icon name="settings" />Configurações</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="admin-nav-item admin-logout" type="submit"><x-admin-icon name="exit" />Sair</button></form>
        </div>
    </aside>
    <main class="admin-main" id="admin-content">
        @if(session('status'))<div class="admin-panel backend-feedback" role="status">{{ session('status') }}</div>@endif
@if($errors->any())<div class="admin-panel backend-feedback" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('admin-content')
    </main>
</div>
@endsection
