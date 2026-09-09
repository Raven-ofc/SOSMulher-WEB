@extends('layouts.admin', ['pageTitle' => 'Adicionar vítima', 'active' => 'admin.victims'])
@section('admin-content')
<header class="admin-heading detail-heading"><h1>Usuárias/Vítimas</h1><p>usuárias/adicionarNovaUsuária</p></header>
<a class="admin-back" href="{{ route('admin.victims') }}">‹ voltar</a>
<div class="victim-form-intro"><h2>Insira as informações</h2><p>Informe os dados referentes à vítima.</p></div>
<form class="admin-panel victim-form" onsubmit="event.preventDefault()" autocomplete="off">
    <fieldset><legend class="sr-only">Informações Pessoais</legend><h2 aria-hidden="true">Informações Pessoais</h2>
        <label for="victim-name">Nome</label><input id="victim-name" name="name" type="text" placeholder="Nome completo da vítima">
        <label for="victim-cpf">CPF</label><input id="victim-cpf" name="cpf" type="text" inputmode="numeric" maxlength="14" placeholder="000.000.000-00">
        <label for="victim-birth">Data de nascimento</label><input id="victim-birth" name="birth_date" type="date" max="{{ now()->toDateString() }}">
    </fieldset>
    <fieldset><legend class="sr-only">Informações de Contato</legend><h2 aria-hidden="true">Informações de Contato</h2>
        <label for="victim-email">Email</label><input id="victim-email" name="email" type="email" placeholder="E-mail da vítima">
        <label for="victim-phone">Número de telefone</label><input id="victim-phone" name="phone" type="tel" placeholder="(00) 00000-0000">
        <div class="victim-form-actions"><p id="victim-create-note">Prévia visual. O cadastro ainda não está disponível.</p><button class="victim-primary" type="button" disabled aria-describedby="victim-create-note">Cadastrar</button></div>
    </fieldset>
</form>
@endsection
