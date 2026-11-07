@extends('estruturas.administracao', ['pageTitle' => 'Editar agressor', 'active' => 'admin.agressores.index'])

@section('admin-content')
@php
    $imagem1 = $agressor->imagemAgressor1;
    $imagem2 = $agressor->imagemAgressor2;
    $imagem3 = $agressor->imagemAgressor3;
    $imagem4 = $agressor->imagemAgressor4;
    $fotoUrl = $imagem1 ? (\Illuminate\Support\Str::startsWith($imagem1, ['http://', 'https://']) ? $imagem1 : asset('storage/' . ltrim($imagem1, '/'))) : null;
    $fotoUrl2 = $imagem2 ? (\Illuminate\Support\Str::startsWith($imagem2, ['http://', 'https://']) ? $imagem2 : asset('storage/' . ltrim($imagem2, '/'))) : null;
    $fotoUrl3 = $imagem3 ? (\Illuminate\Support\Str::startsWith($imagem3, ['http://', 'https://']) ? $imagem3 : asset('storage/' . ltrim($imagem3, '/'))) : null;
    $fotoUrl4 = $imagem4 ? (\Illuminate\Support\Str::startsWith($imagem4, ['http://', 'https://']) ? $imagem4 : asset('storage/' . ltrim($imagem4, '/'))) : null;
    $valores = [
        'foto2_url' => $fotoUrl2,
        'foto3_url' => $fotoUrl3,
        'foto4_url' => $fotoUrl4,
        'nome' => $agressor->nomeAgressor, 'cpf' => $agressor->cpfAgressor, 'data_nascimento' => $agressor->dataNascimentoAgressor,
        'nome_mae' => $agressor->nomeMaeAgressor, 'nome_pai' => $agressor->nomePaiAgressor, 'email' => $agressor->emailAgressor,
        'telefone' => $agressor->telefoneAgressor, 'cep' => $agressor->cepAgressor, 'rua' => $agressor->logradouroAgressor,
        'numero' => $agressor->numLogradouroAgressor, 'bairro' => $agressor->bairroAgressor, 'cidade' => $agressor->cidadeAgressor,
        'uf' => $agressor->ufAgressor, 'complemento' => $agressor->complementoAgressor, 'foto_url' => $fotoUrl,
    ];
@endphp
<header class="admin-heading">
    <h1>Agressores</h1>
</header>

<a class="admin-back" href="{{ route('admin.agressores.visualizar', $agressor->id) }}">‹ voltar</a>

<form class="admin-panel cadastro-unificado" method="POST" action="{{ route('admin.agressores.atualizar', $agressor->id) }}"
      enctype="multipart/form-data" novalidate data-validar autocomplete="off">
    @csrf
    @method('PUT')

    <h2 class="fase-titulo">Editar cadastro do agressor</h2>
    <p class="fase-intro">A tornozeleira é atribuída no cadastro unificado e não é alterada aqui.</p>

    @include('parciais.campos-pessoa', [
        'prefixo' => null,
        'valores' => $valores,
        'edicao' => true,
        'emailObrigatorio' => false,
        'ufs' => \App\Support\Formatos::UFS,
        'multiplasFotos' => true
    ])

    <div class="fase-acoes">
        <a class="botao-secundario" href="{{ route('admin.agressores.visualizar', $agressor->id) }}">Cancelar</a>
        <button class="victim-primary" type="submit">Salvar alterações</button>
    </div>
</form>
@endsection

@push('scripts')
    <script src="{{ asset('js/validacao.js') }}"></script>
    <script src="{{ asset('js/cep.js') }}"></script>
    <script src="{{ asset('js/foto.js') }}"></script>
@endpush
