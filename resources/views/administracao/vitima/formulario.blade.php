@extends('estruturas.administracao',['pageTitle'=>'Usuárias/Vítimas','active'=>'admin.victims'])
@section('admin-content')
<header class="admin-heading">
    <h1>Usuárias/Vítimas</h1>
</header>
<a class="admin-back" href="{{ route('admin.victims') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ $vitima ? route('admin.victims.update',$vitima->id) : route('admin.victims.store') }}">
    @csrf
    @if($vitima)
        @method('PATCH')
    @endif
    <fieldset>
        <legend class="somente-leitor">Informações pessoais</legend>
        <h2>{{ $vitima ? 'Editar cadastro' : 'Informações pessoais' }}</h2>
        <label for="name">Nome</label>
        <input id="name" name="name" required maxlength="100" value="{{ old('name',$vitima?->nomeVitima) }}">
        <label for="cpf">CPF</label>
        <input id="cpf" name="cpf" required inputmode="numeric" maxlength="14" value="{{ old('cpf',$vitima?->cpfVitima) }}">
        <label for="birth_date">Data de nascimento</label>
        <input id="birth_date" name="birth_date" type="date" required max="{{ today()->toDateString() }}" value="{{ old('birth_date',$vitima?->dataNascimentoVitima) }}">
    </fieldset>
    <fieldset>
        <legend class="somente-leitor">Contato</legend>
        <h2>Informações de contato</h2>
        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" required maxlength="100" value="{{ old('email',$vitima?->emailVitima) }}">
        <label for="phone">Telefone</label>
        <input id="phone" name="phone" type="tel" required value="{{ old('phone',$vitima?->telefones()->first()?->numeroTelefoneVitima) }}">
        <div class="victim-form-actions">
            <button class="victim-primary" type="submit">{{ $vitima ? 'Salvar' : 'Cadastrar' }}</button>
        </div>
    </fieldset>
</form>
@endsection
