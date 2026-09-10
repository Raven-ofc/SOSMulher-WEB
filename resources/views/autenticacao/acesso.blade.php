<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acesso ao sistema</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="{{ asset('js/principal.js') }}" defer></script>
    </head>
    <body class="app-body">
        <main class="screen">
            <div class="card login-card">
                <a class="link back-link" href="{{ route('home') }}">‹ voltar</a>
                <img class="logo" src="{{ asset('img/logo.svg') }}" alt="Símbolo de proteção à mulher">
                <h1 class="screen-title">Acesso ao sistema</h1>
                <p class="screen-subtitle">Entre com suas credenciais para acessar o painel de monitoramento.</p>
                @if(session('status'))
                    <p role="status">{{ session('status') }}</p>
                @endif
                <form class="auth-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-field">
                        <label class="form-label" for="email">E-mail institucional</label>
                        <input class="form-input @error('email') has-error @enderror" type="email" id="email" name="email" placeholder="Seu e-mail institucional" autocomplete="email" required value="{{ old('email') }}"
                            @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email')
                        <span class="form-error" id="email-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label" for="password">Senha</label>
                        <input class="form-input @error('password') has-error @enderror" type="password" id="password" name="password" placeholder="Sua senha" autocomplete="current-password" required
                            @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        @error('password')
                        <span class="form-error" id="password-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary btn-md" type="submit">Entrar</button>
                    </div>
                </form>
                <p class="screen-footer">
                    <a class="link" href="{{ route('password.request') }}">Esqueceu a senha? entre em contato!</a>
                </p>
            </div>
        </main>
    </body>
</html>