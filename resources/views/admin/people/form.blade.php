@extends('layouts.admin',['pageTitle'=>$c['label'],'active'=>$c['base']])
@section('admin-content')
<header class="admin-heading"><h1>{{ $c['label'] }}</h1></header>
<a class="admin-back" href="{{ route($c['base']) }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ $record ? route($c['base'].'.update',$record->id) : route($c['base'].'.store') }}">
@csrf @if($record) @method('PATCH') @endif
<fieldset><legend class="sr-only">Informações pessoais</legend><h2>{{ $record ? 'Editar cadastro' : 'Informações pessoais' }}</h2>
<label for="name">Nome</label><input id="name" name="name" required maxlength="100" value="{{ old('name',$record?->{'nome'.$c['suffix']}) }}">
<label for="cpf">CPF</label><input id="cpf" name="cpf" required inputmode="numeric" maxlength="14" value="{{ old('cpf',$record?->{'cpf'.$c['suffix']}) }}">
<label for="birth_date">Data de nascimento</label><input id="birth_date" name="birth_date" type="date" required max="{{ today()->toDateString() }}" value="{{ old('birth_date',$record?->{'dataNascimento'.$c['suffix']}) }}">
</fieldset><fieldset><legend class="sr-only">Contato</legend><h2>Informações de contato</h2>
@if($c['victim'])<label for="email">E-mail</label><input id="email" name="email" type="email" required maxlength="100" value="{{ old('email',$record?->emailVitima) }}">@endif
<label for="phone">Telefone</label><input id="phone" name="phone" type="tel" required value="{{ old('phone',$c['victim'] ? $record?->telefones()->first()?->numeroTelefoneVitima : $record?->telefoneAgressor) }}">
<div class="victim-form-actions"><button class="victim-primary" type="submit">{{ $record ? 'Salvar' : 'Cadastrar' }}</button></div>
</fieldset></form>
@endsection
