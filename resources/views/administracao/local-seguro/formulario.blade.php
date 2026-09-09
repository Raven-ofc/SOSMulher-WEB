@extends('estruturas.administracao',['pageTitle'=>'Solicitar local seguro','active'=>'admin.victims'])
@section('admin-content')
<header class="admin-heading">
    <h1>Solicitar local seguro</h1>
</header>
<a class="admin-back" href="{{ route('admin.victims.requests') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ route('admin.places.store') }}">
    @csrf
    <fieldset>
        <legend class="somente-leitor">Local</legend>
        <label for="victim_id">Vítima</label>
        <select id="victim_id" name="victim_id" required>
            <option value="">Selecione</option>
            @foreach($victims as $v)
                <option value="{{ $v->id }}" @selected(old('victim_id')==$v->id)>
                    {{ $v->nomeVitima }}
                    —
                    {{ $v->cpfVitima }}
                </option>
            @endforeach
        </select>
                <label for="label">Nome do local</label>
        <input id="label" name="label" required value="{{ old('label') }}">
        <label for="street">Logradouro</label>
        <input id="street" name="street" required value="{{ old('street') }}">
        <label for="number">Número</label>
        <input id="number" name="number" required value="{{ old('number') }}">
        <label for="district">Bairro</label>
        <input id="district" name="district" required value="{{ old('district') }}">
        <label for="city">Cidade</label>
        <input id="city" name="city" required value="{{ old('city') }}">
    </fieldset>
    <fieldset>
        <legend class="somente-leitor">Endereço e coordenadas</legend>
                <label for="state">UF</label>
        <input id="state" name="state" required value="{{ old('state') }}">
        <label for="postal_code">CEP</label>
        <input id="postal_code" name="postal_code" required value="{{ old('postal_code') }}">
        <label for="complement">Complemento</label>
        <input id="complement" name="complement" value="{{ old('complement') }}">
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
