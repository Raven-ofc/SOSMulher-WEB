<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SOSMulher</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="{{ asset('js/principal.js') }}" defer></script>
    </head>
    <body class="app-body">
        <main class="screen">
            <div class="card welcome-card">
                <img class="logo" src="{{ asset('img/logo.svg') }}" alt="Símbolo de proteção à mulher">
                <h1 class="screen-title">Sistema de Monitoramento de Medidas Protetivas</h1>
                <p class="screen-subtitle">Tecnologia para apoiar o acompanhamento e a proteção de vítimas.</p>
                <div class="welcome-actions">
                    <a class="btn btn-primary" href="{{ route('login') }}">Acessar sistema</a>
                    <p class="screen-footer">Acesso restrito às autoridades competentes</p>
                </div>
            </div>
        </main>
    </body>
</html>