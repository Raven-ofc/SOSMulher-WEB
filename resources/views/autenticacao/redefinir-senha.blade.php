@extends('estruturas.principal', ['title' => 'Redefina sua senha'])
@section('content')
<main class="screen">
    <div class="card reset-card">
        <a class="link back-link" href="{{ route('login') }}">‹ voltar</a>
        <img class="logo" src="{{ asset('img/logo.svg') }}" alt="Símbolo de proteção à mulher">
        <h1 class="screen-title">Redefina sua senha</h1>
        <p class="screen-subtitle">Informe sua nova senha</p>
        <form class="auth-form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">
            <input type="hidden" name="token" value="{{ request()->query('token', '') }}">
            <div class="form-field">
                <label class="form-label" for="password">Nova senha</label>
                <input class="form-input @error('password') has-error @enderror" type="password" id="password" name="password" placeholder="Sua nova senha" autocomplete="new-password" required
        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                @error('password')
                <span class="form-error" id="password-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-field">
            <label class="form-label" for="password_confirmation">Confirme nova senha</label>
            <input class="form-input @error('password_confirmation') has-error @enderror" type="password" id="password_confirmation" name="password_confirmation" placeholder="Sua nova senha novamente" autocomplete="new-password" required
        @error('password_confirmation') aria-invalid="true" aria-describedby="password_confirmation-error" @enderror>
            @error('password_confirmation')
            <span class="form-error" id="password_confirmation-error">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-actions">
        <button class="btn btn-primary btn-md" type="submit">Salvar</button>
    </div>
</form>
</div>
</main>
@endsection
