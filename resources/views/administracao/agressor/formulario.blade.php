@extends('estruturas.administracao',['pageTitle'=>'Agressores','active'=>'admin.aggressors'])
@section('admin-content')
<header class="admin-heading">
    <h1>Agressores</h1>
</header>
<a class="admin-back" href="{{ route('admin.aggressors') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ $agressor ? route('admin.aggressors.update',$agressor->id) : route('admin.aggressors.store') }}">
    @csrf
    @if($agressor)
        @method('PATCH')
    @endif
    <fieldset>
        <legend class="somente-leitor">Informações pessoais</legend>
        <h2>{{ $agressor ? 'Editar cadastro' : 'Informações pessoais' }}</h2>
        <label for="name">Nome</label>
        <input id="name" name="name" required maxlength="100" value="{{ old('name',$agressor?->nomeAgressor) }}">
        <label for="cpf">CPF</label>
        <input id="cpf" name="cpf" required inputmode="numeric" maxlength="14" value="{{ old('cpf',$agressor?->cpfAgressor) }}">
        <label for="birth_date">Data de nascimento</label>
        <input id="birth_date" name="birth_date" type="date" required max="{{ today()->toDateString() }}" value="{{ old('birth_date',$agressor?->dataNascimentoAgressor) }}">
    </fieldset>
    <fieldset>
        <legend class="somente-leitor">Contato</legend>
        <h2>Informações de contato</h2>
        <label for="phone">Telefone</label>
        <input id="phone" name="phone" type="tel" required value="{{ old('phone',$agressor?->telefoneAgressor) }}">
        <div class="victim-form-actions">
            <button class="victim-primary" type="submit">{{ $agressor ? 'Salvar' : 'Cadastrar' }}</button>
        </div>
    </fieldset>
</form>
@endsection
