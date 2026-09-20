@extends('estruturas.administracao',['pageTitle'=>'Autoridades','active'=>'admin.autoridades'])
@section('admin-content')
@php
    // Sem back, $autoridade não existe: vira null (tela de cadastro).
    // Com back, ela chega preenchida (tela de edição).
    $autoridade = $autoridade ?? null;
    $editando = $autoridade !== null;
    $acao = $editando
        ? route('admin.autoridades.atualizar', $autoridade->id)
        : route('admin.autoridades.salvar');
@endphp
<header class="topo">
    <h1>Autoridades</h1>
</header>
<a class="voltar" href="{{ route('admin.autoridades') }}">‹ voltar</a>
<form class="caixa formulario" method="POST" action="{{ $acao }}">
    @csrf
    @if($editando)
        @method('PATCH')
    @endif
    <fieldset>
        <legend class="leitura-tela">Informações pessoais</legend>
        <h2>{{ $editando ? 'Editar cadastro' : 'Informações pessoais' }}</h2>
        <label for="nome">Nome</label>
        <input id="nome" name="nome" required maxlength="100" value="{{ old('nome', $editando ? $autoridade->nome : '') }}">
        <label for="cpf">CPF</label>
        <input id="cpf" name="cpf" required inputmode="numeric" maxlength="14" value="{{ old('cpf', $editando ? $autoridade->cpf : '') }}">
        <label for="data_nascimento">Data de nascimento</label>
        <input id="data_nascimento" name="data_nascimento" type="date" required max="{{ today()->toDateString() }}" value="{{ old('data_nascimento', $editando ? $autoridade->dataNascimento : '') }}">
    </fieldset>
    <fieldset>
        <legend class="leitura-tela">Contato e acesso</legend>
        <h2>Informações de contato</h2>
        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" required maxlength="100" value="{{ old('email', $editando ? $autoridade->email : '') }}">
        <label for="telefone">Telefone</label>
        <input id="telefone" name="telefone" type="tel" required value="{{ old('telefone', $editando ? $autoridade->telefone : '') }}">
        <label for="cargo">Cargo</label>
        <input id="cargo" name="cargo" maxlength="100" value="{{ old('cargo', $editando ? $autoridade->cargo : '') }}">
        <div class="envio-dados">
            <button class="adicionar" type="submit">{{ $editando ? 'Salvar' : 'Cadastrar' }}</button>
        </div>
    </fieldset>
</form>
@endsection