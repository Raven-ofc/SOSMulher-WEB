@extends('layouts.admin',['pageTitle'=>'Solicitar local seguro','active'=>'admin.victims'])
@section('admin-content')
<header class="admin-heading"><h1>Solicitar local seguro</h1></header><a class="admin-back" href="{{ route('admin.victims.requests') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ route('admin.places.store') }}">@csrf
<fieldset><legend class="sr-only">Local</legend><label for="victim_id">Vítima</label><select id="victim_id" name="victim_id" required><option value="">Selecione</option>@foreach($victims as $v)<option value="{{ $v->id }}" @selected(old('victim_id')==$v->id)>{{ $v->nomeVitima }} — {{ $v->cpfVitima }}</option>@endforeach</select>
@foreach(['label'=>'Nome do local','street'=>'Logradouro','number'=>'Número','district'=>'Bairro','city'=>'Cidade'] as $key=>$label)<label for="{{ $key }}">{{ $label }}</label><input id="{{ $key }}" name="{{ $key }}" required value="{{ old($key) }}">@endforeach
</fieldset><fieldset><legend class="sr-only">Endereço e coordenadas</legend>
@foreach(['state'=>'UF','postal_code'=>'CEP','complement'=>'Complemento'] as $key=>$label)<label for="{{ $key }}">{{ $label }}</label><input id="{{ $key }}" name="{{ $key }}" @required($key!=='complement') value="{{ old($key) }}">@endforeach
<label for="latitude">Latitude</label><input type="number" step="any" min="-90" max="90" id="latitude" name="latitude" required value="{{ old('latitude') }}">
<label for="longitude">Longitude</label><input type="number" step="any" min="-180" max="180" id="longitude" name="longitude" required value="{{ old('longitude') }}">
<div class="victim-form-actions"><button class="victim-primary">Enviar para análise</button></div></fieldset></form>
@endsection
