@extends('estruturas.principal', ['title' => 'Esqueceu a senha?'])
@section('content')
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
@endsection
