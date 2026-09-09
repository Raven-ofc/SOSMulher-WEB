@extends('estruturas.principal', ['title' => 'Acesso ao sistema'])
@section('content')
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
@endsection
