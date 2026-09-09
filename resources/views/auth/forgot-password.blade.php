@extends('layouts.app', ['title' => 'Esqueceu a senha?'])
@section('content')
<main class="screen">
    <div class="card forgot-card">
        <a class="link back-link" href="{{ route('login') }}">‹ voltar</a>
        <x-logo />
        <h1 class="screen-title">Esqueceu a senha?</h1>
        <p class="screen-text">Faça a redefinição da sua senha em duas etapas:</p>
        <form class="auth-form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <x-input type="email" name="email" id="email" label="E-mail institucional cadastrado" placeholder="Seu e-mail institucional" required autocomplete="email" :error="$errors->first('email') ?: null" />
            <div class="form-actions"><x-button type="submit">Enviar</x-button></div>
        </form>
    </div>
</main>
@endsection
