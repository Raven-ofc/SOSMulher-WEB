<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $pageTitle ?? 'Administração' }} - SOSMulher</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="{{ asset('js/principal.js') }}" defer></script>
    </head>
    <body class="app-body">
        <div class="admin-shell">
            <a class="admin-skip" href="#admin-content">Ir para o conteúdo</a>
            <header class="admin-topbar">
                <button type="button" class="admin-menu-toggle" data-menu-toggle aria-label="Abrir menu" aria-expanded="false" aria-controls="menu-principal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <img class="admin-topbar-logo" src="{{ asset('img/logo.svg') }}" alt="SOSMulher">
            </header>
            <div class="admin-overlay" data-menu-fechar></div>
            @include('parciais.menu-lateral')
            <main class="admin-main" id="admin-content">
                @if(session('status'))
                    <div class="admin-panel backend-feedback" role="status">
                        {{ session('status') }}
                    </div>
                @endif
                @if(session('aviso'))
                    <div class="admin-panel backend-feedback aviso" role="alert">{{ session('aviso') }}</div>
                @endif
                @if($errors->any() && !request()->routeIs('admin.cadastro.*'))
                    <div class="admin-panel backend-feedback erro-geral" role="alert">
                        <strong>Corrija os campos destacados:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('admin-content')
            </main>
        </div>
        @stack('scripts')
    </body>
</html>