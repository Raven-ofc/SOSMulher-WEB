@extends('estruturas.administracao', ['pageTitle' => 'Cadastrar nova Tornozeleira', 'active' => 'admin.tornozeleiras.index'])
@section('admin-content')
<header class="admin-heading detail-heading">
    <h1>Gerenciamento de Tornozeleiras</h1>
    <p>tornozeleira/adicionarNovaTornozeleira</p>
</header>
<a class="admin-back" href="{{ route('admin.tornozeleiras.index') }}">‹ voltar</a>
<div class="victim-form-intro">
    <h2>Cadastrar nova Tornozeleira</h2>
    <p>Insira as informações para cadastrar a tornozeleira</p>
</div>
<form class="admin-panel victim-form device-form" method="POST" action="{{ route('admin.tornozeleiras.salvar') }}" autocomplete="off">
    @csrf
    <fieldset>
        <legend class="somente-leitor">Informações da tornozeleira</legend>
        <label for="device-serial">Número de Série</label>
        <input id="device-serial" required name="numeroSerie" type="text" placeholder="Número de série da tornozeleira" value="{{ old('numeroSerie') }}">
        <label for="device-installation">Data de instalação</label>
        <input id="device-installation" name="dataInstalacao" type="date" required value="{{old('dataInstalacao')}}">
    </fieldset>
    <div class="device-form-actions">
        <button class="victim-primary" type="submit" aria-describedby="device-create-note">Cadastrar</button>
    </div>
</form>
@endsection