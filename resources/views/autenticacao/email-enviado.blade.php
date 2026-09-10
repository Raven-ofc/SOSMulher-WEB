<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solicitação recebida</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="{{ asset('js/principal.js') }}" defer></script>
    </head>
    <body class="app-body">
        <main class="screen">
            <div class="card sent-card">
                <a class="link back-link" href="{{ route('password.request') }}">‹ voltar</a>
                <img class="logo" src="{{ asset('img/logo.svg') }}" alt="Símbolo de proteção à mulher">
                <h1 class="screen-title">Solicitação recebida</h1>
                <p class="screen-text">Se o e-mail informado estiver cadastrado, você receberá um link para redefinir sua senha.</p>
                <hr class="sent-divider">
                <p class="screen-text">Caso não tenha recebido a mensagem, verifique o e-mail inserido, sua caixa de entrada ou de spam</p>
                <a class="link resend-link" href="{{ route('password.request') }}">Reenviar e-mail</a>
            </div>
        </main>
    </body>
</html>