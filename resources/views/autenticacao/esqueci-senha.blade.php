<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Esqueceu a senha?</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="{{ asset('js/principal.js') }}" defer></script>
    </head>
    <body class="app-body">
        <main class="screen">
            <div class="card forgot-card">
                <a class="link back-link" href="{{ route('login') }}">‹ voltar</a>
                <img class="logo" src="{{ asset('img/logo.svg') }}" alt="Símbolo de proteção à mulher">
                <h1 class="screen-title">Esqueceu a senha?</h1>
                <p class="screen-text">Faça a redefinição da sua senha em duas etapas:</p>
                <form class="auth-form" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-field">
                        <label class="form-label" for="email">E-mail institucional cadastrado</label>
                        <input class="form-input @error('email') has-error @enderror" type="email" id="email" name="email" placeholder="Seu e-mail institucional" autocomplete="email" required value="{{ old('email') }}"
                            @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email')
                        <span class="form-error" id="email-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary btn-md" type="submit">Enviar</button>
                    </div>
                </form>
            </div>
        </main>
    </body>
</html>