@extends('layouts.app', ['title' => 'Redefina sua senha'])
@section('content')
<main class="screen">
    <div class="card reset-card">
        <a class="link back-link" href="{{ route('login') }}">‹ voltar</a>
        <x-logo />
        <h1 class="screen-title">Redefina sua senha</h1>
        <p class="screen-subtitle">Informe sua nova senha</p>
        <form class="auth-form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">
            <input type="hidden" name="token" value="{{ request()->query('token', '') }}">
            <x-input type="password" name="password" id="password" label="Nova senha" placeholder="Sua nova senha" required autocomplete="new-password" :error="$errors->first('password') ?: null" />
            <x-input type="password" name="password_confirmation" id="password_confirmation" label="Confirme nova senha" placeholder="Sua nova senha novamente" required autocomplete="new-password" :error="$errors->first('password_confirmation') ?: null" />
            <div class="form-actions"><x-button type="submit">Salvar</x-button></div>
        </form>
    </div>
</main>
@endsection
