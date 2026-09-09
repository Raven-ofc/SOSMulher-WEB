<aside class="admin-sidebar" aria-label="Menu principal">
    <a class="admin-brand" href="{{ route('dashboard') }}" aria-label="Página inicial"><img class="logo" src="{{ asset('img/logo.svg') }}" alt="Símbolo de proteção à mulher"></a>
    <nav class="admin-nav">
        <a href="{{ route('dashboard') }}" class="admin-nav-item {{ ($active ?? '') === 'dashboard' ? 'is-active' : '' }}" @if(($active ?? '') === 'dashboard') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'home'])
            Home
        </a>
        <a href="{{ route('admin.occurrences') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.occurrences' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.occurrences') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'clip'])
            Ocorrências
        </a>
        <a href="{{ route('admin.monitoring') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.monitoring' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.monitoring') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'monitor'])
            Monitoramento
        </a>
        <a href="{{ route('admin.statistics') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.statistics' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.statistics') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'chart'])
            Estatísticas
        </a>
        <a href="{{ route('admin.reports') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.reports' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.reports') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'archive'])
            Relatórios
        </a>
        <div class="registration-menu">
            <a href="{{ route('admin.victims') }}" class="admin-nav-item {{ in_array($active ?? '', ['admin.victims', 'admin.aggressors', 'admin.devices', 'admin.measures']) ? 'is-active' : '' }}">
                @include('parciais.icone', ['name' => 'book'])
                Cadastros
            </a>
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
        <a href="{{ route('admin.profile') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.profile' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.profile') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'settings'])
            Configurações
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="admin-nav-item admin-logout" type="submit">
                @include('parciais.icone', ['name' => 'exit'])
                Sair
            </button>
        </form>
    </div>
</aside>
