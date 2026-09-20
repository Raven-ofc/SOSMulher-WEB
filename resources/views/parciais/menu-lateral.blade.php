<aside class="admin-sidebar" aria-label="Menu principal">
    <a class="admin-brand" href="{{ route('admin.painel') }}" aria-label="Página inicial"><img class="logo" src="{{ asset('img/logo.svg') }}" alt="Símbolo de proteção à mulher"></a>
    <nav class="admin-nav">
        <a href="{{ route('admin.painel') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.painel' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.painel') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'home'])
            Home
        </a>
        <a href="{{ route('admin.ocorrencias.index') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.ocorrencias.index' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.ocorrencias.index') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'clip'])
            Ocorrências
        </a>
        <a href="{{ route('admin.monitoramento') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.monitoramento' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.monitoramento') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'monitor'])
            Monitoramento
        </a>
        <a href="{{ route('admin.estatisticas') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.estatisticas' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.estatisticas') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'chart'])
            Estatísticas
        </a>
        <a href="{{ route('admin.relatorios.index') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.relatorios.index' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.relatorios.index') aria-current="page" @endif>
            @include('parciais.icone', ['name' => 'archive'])
            Relatórios
        </a>
        <div class="registration-menu">
            <a href="{{ route('admin.vitimas.index') }}" class="admin-nav-item {{ in_array($active ?? '', ['admin.vitimas.index', 'admin.agressores.index', 'admin.tornozeleiras.index', 'admin.medidas-protetivas']) ? 'is-active' : '' }}">
                @include('parciais.icone', ['name' => 'book'])
                Cadastros
            </a>
            @if(in_array($active ?? '', ['admin.vitimas.index', 'admin.agressores.index', 'admin.tornozeleiras.index', 'admin.medidas-protetivas']))
                <div class="registration-subnav" aria-label="Tipos de cadastro">
                    <a href="{{ route('admin.vitimas.index') }}" @if(($active ?? '') === 'admin.vitimas.index') aria-current="page" @endif>Vítimas</a>
                    <a href="{{ route('admin.agressores.index') }}" @if(($active ?? '') === 'admin.agressores.index') aria-current="page" @endif>Agressores</a>
                    <a href="{{ route('admin.tornozeleiras.index') }}" @if(($active ?? '') === 'admin.tornozeleiras.index') aria-current="page" @endif>Tornozeleiras</a>
                    <a href="{{ route('admin.medidas-protetivas') }}" @if(($active ?? '') === 'admin.medidas-protetivas') aria-current="page" @endif>Medidas protetivas</a>
                </div>
            @endif
        </div>
    </nav>
    <div class="admin-sidebar-bottom">
        <a href="{{ route('admin.perfil') }}" class="admin-nav-item {{ ($active ?? '') === 'admin.perfil' ? 'is-active' : '' }}" @if(($active ?? '') === 'admin.perfil') aria-current="page" @endif>
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