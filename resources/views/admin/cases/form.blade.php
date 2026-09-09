@extends('layouts.admin',['pageTitle'=>'Registrar ocorrência','active'=>'admin.occurrences'])
@section('admin-content')
<header class="admin-heading"><h1>Registrar ocorrência</h1></header><a class="admin-back" href="{{ route('admin.occurrences') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ route('admin.occurrences.store') }}">@csrf
<fieldset><legend class="sr-only">Pessoas e ocorrência</legend>
<label for="victim_id">Vítima</label><select id="victim_id" name="victim_id" required><option value="">Selecione</option>@foreach($victims as $v)<option value="{{ $v->id }}" @selected(old('victim_id')==$v->id)>{{ $v->nomeVitima }} — {{ $v->cpfVitima }}</option>@endforeach</select>
<label for="aggressor_id">Agressor</label><select id="aggressor_id" name="aggressor_id" required><option value="">Selecione</option>@foreach($aggressors as $a)<option value="{{ $a->id }}" @selected(old('aggressor_id')==$a->id)>{{ $a->nomeAgressor }} — {{ $a->cpfAgressor }}</option>@endforeach</select>
<label for="type">Tipo de ocorrência</label><input id="type" name="type" required maxlength="100" value="{{ old('type') }}">
<label for="severity">Gravidade</label><select id="severity" name="severity" required><option value="">Selecione</option>@foreach(['baixa','media','alta'] as $severity)<option @selected(old('severity')===$severity)>{{ $severity }}</option>@endforeach</select>
</fieldset><fieldset><legend class="sr-only">Data e local</legend>
<label for="occurred_at">Data e hora</label><input id="occurred_at" name="occurred_at" type="datetime-local" required value="{{ old('occurred_at') }}">
<label for="location">Local</label><input id="location" name="location" required maxlength="255" value="{{ old('location') }}">
<label for="district">Bairro</label><input id="district" name="district" required maxlength="100" value="{{ old('district') }}">
<label for="description">Descrição</label><textarea id="description" name="description" maxlength="10000">{{ old('description') }}</textarea>
<div class="victim-form-actions"><button class="victim-primary">Registrar</button></div></fieldset></form>
@endsection
