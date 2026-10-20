@extends('estruturas.administracao', ['pageTitle' => 'Editar agressor', 'active' => 'admin.agressores.index'])

@section('admin-content')
@php
    $imagem = $agressor->imagemAgressor;
    $fotoUrl = $imagem ? (\Illuminate\Support\Str::startsWith($imagem, ['http://', 'https://']) ? $imagem : asset('storage/' . ltrim($imagem, '/'))) : null;
    $valores = [
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

    @include('parciais.campos-pessoa', ['prefixo' => null, 'valores' => $valores, 'edicao' => true, 'emailObrigatorio' => false, 'ufs' => \App\Support\Formatos::UFS])

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
