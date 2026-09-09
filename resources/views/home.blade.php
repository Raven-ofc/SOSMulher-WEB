@extends('layouts.app')
@section('content')
<main class="screen">
    <div class="card welcome-card">
        <x-logo />
        <h1 class="screen-title">Sistema de Monitoramento de Medidas Protetivas</h1>
        <p class="screen-subtitle">Tecnologia para apoiar o acompanhamento e a proteção de vítimas.</p>
        <div class="welcome-actions">
            <a class="btn btn-primary" href="{{ route('login') }}">Acessar sistema</a>
            <p class="screen-footer">Acesso restrito às autoridades competentes</p>
        </div>
    </div>
</main>
@endsection

