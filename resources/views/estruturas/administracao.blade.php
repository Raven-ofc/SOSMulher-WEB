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
            @include('parciais.menu-lateral')
            <main class="admin-main" id="admin-content">
                @if(session('status'))
                    <div class="admin-panel backend-feedback" role="status">
                        {{ session('status') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="admin-panel backend-feedback" role="alert">
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