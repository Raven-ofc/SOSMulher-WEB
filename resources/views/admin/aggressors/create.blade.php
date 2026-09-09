@extends('layouts.admin', ['pageTitle' => 'Adicionar agressor', 'active' => 'admin.aggressors'])
@section('admin-content')
<header class="admin-heading detail-heading"><h1>Agressores</h1><p>Agressores/adicionarNovoAgressor</p></header>
<a class="admin-back" href="{{ route('admin.aggressors') }}">‹ voltar</a>
<div class="victim-form-intro"><h2>Insira as informações</h2><p>Informe os dados referentes ao indiciado.</p></div>
<form class="admin-panel victim-form aggressor-form" onsubmit="event.preventDefault()" autocomplete="off">
    <fieldset><legend class="sr-only">Informações Pessoais</legend><h2 aria-hidden="true">Informações Pessoais</h2>
        <label for="aggressor-name">Nome</label><input id="aggressor-name" name="name" type="text" placeholder="Nome completo do agressor">
        <label for="aggressor-cpf">CPF</label><input id="aggressor-cpf" name="cpf" type="text" inputmode="numeric" maxlength="14" placeholder="000.000.000-00">
        <label for="aggressor-birth">Data de nascimento</label><input id="aggressor-birth" name="birth_date" type="date" max="{{ now()->toDateString() }}">
        <label for="aggressor-device">Número tornozeleira</label><input id="aggressor-device" class="aggressor-device" name="device_number" type="text" placeholder="Número de série">
    </fieldset>
    <fieldset><legend class="sr-only">Informações de Contato</legend><h2 aria-hidden="true">Informações de Contato</h2>
        <label for="aggressor-phone">Número de telefone</label><input id="aggressor-phone" name="phone" type="tel" placeholder="(00) 00000-0000">
        <div class="victim-form-actions"><p id="aggressor-create-note">Prévia visual. O cadastro ainda não está disponível.</p><button class="victim-primary" type="button" disabled aria-describedby="aggressor-create-note">Cadastrar</button></div>
    </fieldset>
</form>
@endsection
