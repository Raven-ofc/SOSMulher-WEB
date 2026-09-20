@extends('estruturas.administracao',['pageTitle'=>'Agressores','active'=>'admin.agressores.index'])
@section('admin-content')
<header class="admin-heading">
    <h1>Agressores</h1>
</header>
<a class="admin-back" href="{{ route('admin.agressores.index') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ $agressor ? route('admin.agressores.atualizar',$agressor->id) : route('admin.agressores.salvar') }}">
    @csrf
    @if($agressor)
        @method('PATCH')
    @endif
    <fieldset>
        <legend class="somente-leitor">Informações pessoais</legend>
        <h2>{{ $agressor ? 'Editar cadastro' : 'Informações pessoais' }}</h2>
        <label for="nome">Nome</label>
        <input id="nome" name="nome" required maxlength="100" value="{{ old('nome',$agressor?->nomeAgressor) }}">
        <label for="cpf">CPF</label>
        <input id="cpf" name="cpf" required inputmode="numeric" maxlength="14" value="{{ old('cpf',$agressor?->cpfAgressor) }}">
        <label for="data_nascimento">Data de nascimento</label>
        <input id="data_nascimento" name="data_nascimento" type="date" required max="{{ today()->toDateString() }}" value="{{ old('data_nascimento',$agressor?->dataNascimentoAgressor) }}">
    </fieldset>
    <fieldset>
        <legend class="somente-leitor">Contato</legend>
        <h2>Informações de contato</h2>
        <label for="telefone">Telefone</label>
        <input id="telefone" name="telefone" type="tel" required value="{{ old('telefone',$agressor?->telefoneAgressor) }}">
        <div class="victim-form-actions">
            <button class="victim-primary" type="submit">{{ $agressor ? 'Salvar' : 'Cadastrar' }}</button>
        </div>
    </fieldset>
</form>
@endsection