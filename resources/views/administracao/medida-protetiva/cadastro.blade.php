@extends('estruturas.administracao', ['pageTitle' => 'Cadastrar nova Medida Protetiva', 'active' => 'admin.measures'])
@section('admin-content')
<header class="admin-heading detail-heading">
    <h1>Medidas Protetivas</h1>
    <p>medidaProtetiva/adicionarNovaMedidaProtetiva</p>
</header>
<a class="admin-back" href="{{ route('admin.measures') }}">‹ voltar</a>
<div class="victim-form-intro">
    <h2>Cadastrar nova Medida Protetiva</h2>
    <p>Insira as informações para cadastrar a medida. Confira se a vítima e o agressor já foram cadastrados.</p>
</div>
<form class="admin-panel victim-form device-form measure-form" onsubmit="event.preventDefault()" autocomplete="off">
    <fieldset>
        <legend class="somente-leitor">Informações da medida protetiva</legend>
        <label for="measure-victim">Vítima</label>
        <input id="measure-victim" name="victim" type="text" placeholder="Nome da vítima cadastrada">
        <label for="measure-aggressor">Agressor</label>
        <input id="measure-aggressor" name="aggressor" type="text" placeholder="Nome do agressor cadastrado">
        <label for="measure-radius">Raio do perímetro</label>
        <input id="measure-radius" name="radius" type="number" min="1" step="1" placeholder="Ex.: 100 metros" aria-describedby="measure-radius-help">
        <p class="measure-radius-help" id="measure-radius-help">O raio de alerta externo é calculado com base no perímetro informado.</p>
    </fieldset>
    <div class="device-form-actions">
        <p id="measure-create-note">Prévia visual. O cadastro e o cálculo ainda não estão disponíveis.</p>
        <button class="victim-primary" type="button" disabled aria-describedby="measure-create-note">Cadastrar Medida</button>
    </div>
</form>
@endsection
