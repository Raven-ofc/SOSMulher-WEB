@extends('estruturas.principal', ['title' => 'Solicitação recebida'])
@section('content')
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
@endsection
