@extends('layouts.app', ['title' => 'Acesso ao sistema'])
@section('content')
<main class="screen">
    <div class="card login-card">
        <a class="link back-link" href="{{ route('home') }}">‹ voltar</a>
        <x-logo />
        <h1 class="screen-title">Acesso ao sistema</h1>
        <p class="screen-subtitle">Entre com suas credenciais para acessar o painel de monitoramento.</p>
        @if(session('status'))<p role="status">{{ session('status') }}</p>@endif
        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf
            <x-input type="email" name="email" id="email" label="E-mail institucional" placeholder="Seu e-mail institucional" required autocomplete="email" :error="$errors->first('email') ?: null" />
            <x-input type="password" name="password" id="password" label="Senha" placeholder="Sua senha" required autocomplete="current-password" :error="$errors->first('password') ?: null" />
            <div class="form-actions"><x-button type="submit">Entrar</x-button></div>
        </form>
        <p class="screen-footer"><a class="link" href="{{ route('password.request') }}">Esqueceu a senha? entre em contato!</a></p>
    </div>
</main>
@endsection
