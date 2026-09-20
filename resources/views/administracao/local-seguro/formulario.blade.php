@extends('estruturas.administracao',['pageTitle'=>'Solicitar local seguro','active'=>'admin.vitimas.solicitacoes'])
@section('admin-content')
<header class="admin-heading">
    <h1>Solicitar local seguro</h1>
</header>
<a class="admin-back" href="{{ route('admin.vitimas.solicitacoes') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ route('admin.locais-seguros.salvar') }}">
    @csrf
    <fieldset>
        <legend class="somente-leitor">Local</legend>
        <label for="vitima_id">Vítima</label>
        <select id="vitima_id" name="vitima_id" required>
            <option value="">Selecione</option>
            @foreach($vitimas as $v)
                <option value="{{ $v->id }}" @selected(old('vitima_id')==$v->id)>
                    {{ $v->nomeVitima }}
                    —
                    {{ $v->cpfVitima }}
                </option>
            @endforeach
        </select>
                <label for="rotulo">Nome do local</label>
        <input id="rotulo" name="rotulo" required value="{{ old('rotulo') }}">
        <label for="logradouro">Logradouro</label>
        <input id="logradouro" name="logradouro" required value="{{ old('logradouro') }}">
        <label for="numero">Número</label>
        <input id="numero" name="numero" required value="{{ old('numero') }}">
        <label for="bairro">Bairro</label>
        <input id="bairro" name="bairro" required value="{{ old('bairro') }}">
        <label for="cidade">Cidade</label>
        <input id="cidade" name="cidade" required value="{{ old('cidade') }}">
    </fieldset>
    <fieldset>
        <legend class="somente-leitor">Endereço e coordenadas</legend>
                <label for="uf">UF</label>
        <input id="uf" name="uf" required value="{{ old('uf') }}">
        <label for="cep">CEP</label>
        <input id="cep" name="cep" required value="{{ old('cep') }}">
        <label for="complemento">Complemento</label>
        <input id="complemento" name="complemento" value="{{ old('complemento') }}">
        <label for="latitude">Latitude</label>
        <input type="number" step="any" min="-90" max="90" id="latitude" name="latitude" required value="{{ old('latitude') }}">
        <label for="longitude">Longitude</label>
        <input type="number" step="any" min="-180" max="180" id="longitude" name="longitude" required value="{{ old('longitude') }}">
        <div class="victim-form-actions">
            <button class="victim-primary">Enviar para análise</button>
        </div>
    </fieldset>
</form>
@endsection