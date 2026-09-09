@extends('layouts.admin', ['pageTitle' => 'Cadastrar nova Tornozeleira', 'active' => 'admin.devices'])
@section('admin-content')
<header class="admin-heading detail-heading"><h1>Gerenciamento de Tornozeleiras</h1><p>tornozeleira/adicionarNovaTornozeleira</p></header>
<a class="admin-back" href="{{ route('admin.devices') }}">‹ voltar</a>
<div class="victim-form-intro"><h2>Cadastrar nova Tornozeleira</h2><p>Insira as informações para cadastrar a tornozeleira</p></div>
<form class="admin-panel victim-form device-form" onsubmit="event.preventDefault()" autocomplete="off">
    <fieldset><legend class="sr-only">Informações da tornozeleira</legend>
        <label for="device-serial">Número de Série</label><input id="device-serial" name="serial_number" type="text" placeholder="Número de série da tornozeleira">
        <label for="device-aggressor">Agressor</label><input id="device-aggressor" name="aggressor_cpf" type="text" inputmode="numeric" maxlength="14" placeholder="CPF do agressor">
        <label for="device-installation">Data de instalação</label><input id="device-installation" name="installation_date" type="date">
    </fieldset>
    <div class="device-form-actions"><p id="device-create-note">Prévia visual. O cadastro ainda não está disponível.</p><button class="victim-primary" type="button" disabled aria-describedby="device-create-note">Cadastrar</button></div>
</form>
@endsection
