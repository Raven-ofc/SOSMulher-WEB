@extends('estruturas.administracao',['pageTitle'=>'Usuárias/Vítimas','active'=>'admin.vitimas.index'])
@section('admin-content')
<header class="admin-heading">
    <h1>Usuárias/Vítimas</h1>
</header>
<a class="admin-back" href="{{ route('admin.vitimas.index') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ $vitima ? route('admin.vitimas.atualizar',$vitima->id) : route('admin.vitimas.salvar') }}">
    @csrf
    @if($vitima)
        @method('PATCH')
    @endif
    <fieldset>
        <legend class="somente-leitor">Informações pessoais</legend>
        <h2>{{ $vitima ? 'Editar cadastro' : 'Informações pessoais' }}</h2>
        <label for="nome">Nome</label>
        <input id="nome" name="nome" required maxlength="100" value="{{ old('nome',$vitima?->nomeVitima) }}">
        <label for="cpf">CPF</label>
        <input id="cpf" name="cpf" required inputmode="numeric" maxlength="14" value="{{ old('cpf',$vitima?->cpfVitima) }}">
        <label for="data_nascimento">Data de nascimento</label>
        <input id="data_nascimento" name="data_nascimento" type="date" required max="{{ today()->toDateString() }}" value="{{ old('data_nascimento',$vitima?->dataNascimentoVitima) }}">
    </fieldset>
    <fieldset>
        <legend class="somente-leitor">Contato</legend>
        <h2>Informações de contato</h2>
        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" required maxlength="100" value="{{ old('email',$vitima?->emailVitima) }}">
        <label for="telefone">Telefone</label>
        <input id="telefone" name="telefone" type="tel" required value="{{ old('telefone',$vitima?->telefones()->first()?->numeroTelefoneVitima) }}">
        <div class="victim-form-actions">
            <button class="victim-primary" type="submit">{{ $vitima ? 'Salvar' : 'Cadastrar' }}</button>
        </div>
    </fieldset>
</form>
@endsection