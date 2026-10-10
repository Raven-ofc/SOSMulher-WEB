<aside class="admin-sidebar" aria-label="Menu principal">
    <a class="admin-brand" href="{{ route('admin.painel') }}" aria-label="Página inicial"><img class="logo" src="{{ asset('img/logo.svg') }}" alt="Símbolo de proteção à mulher"></a>
    <nav class="admin-nav">
        <a href="{{ route('admin.painel') }}" class="admin-nav-item {{ request()->routeIs('admin.painel') ? 'is-active' : '' }}" @if(request()->routeIs('admin.painel')) aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'home'])
            Home
        </a>
        <a href="{{ route('admin.ocorrencias.index') }}" class="admin-nav-item {{ request()->routeIs('admin.ocorrencias.*') ? 'is-active' : '' }}" @if(request()->routeIs('admin.ocorrencias.*')) aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'clip'])
            Ocorrências
        </a>
        <a href="{{ route('admin.monitoramento') }}" class="admin-nav-item {{ request()->routeIs('admin.monitoramento') ? 'is-active' : '' }}" @if(request()->routeIs('admin.monitoramento')) aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'monitor'])
            Monitoramento
        </a>
        <a href="{{ route('admin.estatisticas') }}" class="admin-nav-item {{ request()->routeIs('admin.estatisticas') ? 'is-active' : '' }}" @if(request()->routeIs('admin.estatisticas')) aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'chart'])
            Estatísticas
        </a>
        <a href="{{ route('admin.relatorios.index') }}" class="admin-nav-item {{ request()->routeIs('admin.relatorios.*') ? 'is-active' : '' }}" @if(request()->routeIs('admin.relatorios.*')) aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'archive'])
            Relatórios
        </a>
        <div class="registration-menu">
            <a href="{{ route('admin.vitimas.index') }}" class="admin-nav-item {{ in_array(request()->route()->getName(), ['admin.vitimas.index', 'admin.vitimas.create', 'admin.vitimas.show', 'admin.vitimas.edit', 'admin.agressores.index', 'admin.agressores.create', 'admin.agressores.show', 'admin.agressores.edit', 'admin.tornozeleiras.index', 'admin.tornozeleiras.create', 'admin.tornozeleiras.show', 'admin.tornozeleiras.edit', 'admin.medidas-protetivas', 'admin.medidas-protetivas.create', 'admin.medidas-protetivas.show', 'admin.medidas-protetivas.edit']) ? 'is-active' : '' }}">
                @include('parciais.icone', ['name' => 'book'])
                Cadastros
            </a>
            @if(in_array(request()->route()->getName(), ['admin.vitimas.index', 'admin.vitimas.create', 'admin.vitimas.show', 'admin.vitimas.edit', 'admin.agressores.index', 'admin.agressores.create', 'admin.agressores.show', 'admin.agressores.edit', 'admin.tornozeleiras.index', 'admin.tornozeleiras.create', 'admin.tornozeleiras.show', 'admin.tornozeleiras.edit', 'admin.medidas-protetivas', 'admin.medidas-protetivas.create', 'admin.medidas-protetivas.show', 'admin.medidas-protetivas.edit']))
                <div class="registration-subnav" aria-label="Tipos de cadastro">
                    <a href="{{ route('admin.vitimas.index') }}" @if(request()->routeIs('admin.vitimas.*')) aria-current="page" @endif>Vítimas</a>
                    <a href="{{ route('admin.agressores.index') }}" @if(request()->routeIs('admin.agressores.*')) aria-current="page" @endif>Agressores</a>
                    <a href="{{ route('admin.tornozeleiras.index') }}" @if(request()->routeIs('admin.tornozeleiras.*')) aria-current="page" @endif>Tornozeleiras</a>
                    <a href="{{ route('admin.medidas-protetivas') }}" @if(request()->routeIs('admin.medidas-protetivas*')) aria-current="page" @endif>Medidas protetivas</a>
                </div>
            @endif
        </div>
    </nav>
    <div class="admin-sidebar-bottom">
        <a href="{{ route('admin.perfil') }}" class="admin-nav-item {{ request()->routeIs('admin.perfil') ? 'is-active' : '' }}" @if(request()->routeIs('admin.perfil')) aria-current="page" @endif>
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