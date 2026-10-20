@extends('estruturas.administracao', ['pageTitle' => 'Editar vítima', 'active' => 'admin.vitimas.index'])

@section('admin-content')
@php
    $endereco = $vitima->endereco;
    $imagem = $vitima->imagemVitima;
    $fotoUrl = $imagem ? (\Illuminate\Support\Str::startsWith($imagem, ['http://', 'https://']) ? $imagem : asset('storage/' . ltrim($imagem, '/'))) : null;
    $valores = [
        'nome' => $vitima->nomeVitima, 'cpf' => $vitima->cpfVitima, 'data_nascimento' => $vitima->dataNascimentoVitima,
        'nome_mae' => $vitima->nomeMaeVitima, 'nome_pai' => $vitima->nomePaiVitima, 'email' => $vitima->emailVitima,
        'telefone' => $vitima->telefones->first()?->numeroTelefoneVitima,
        'cep' => $endereco?->cepVitima, 'rua' => $endereco?->logradouroVitima, 'numero' => $endereco?->numLogradouroVitima,
        'bairro' => $endereco?->bairroVitima, 'cidade' => $endereco?->cidadeVitima, 'uf' => $endereco?->ufVitima,
        'complemento' => $endereco?->complementoVitima, 'foto_url' => $fotoUrl,
    ];
@endphp
<header class="admin-heading">
    <h1>Vítimas</h1>
</header>

<a class="admin-back" href="{{ route('admin.vitimas.visualizar', $vitima->id) }}">‹ voltar</a>

<form class="admin-panel cadastro-unificado" method="POST" action="{{ route('admin.vitimas.atualizar', $vitima->id) }}"
      enctype="multipart/form-data" novalidate data-validar autocomplete="off">
    @csrf
    @method('PUT')

    <h2 class="fase-titulo">Editar ficha da vítima</h2>

    @include('parciais.campos-pessoa', ['prefixo' => null, 'valores' => $valores, 'edicao' => true, 'emailObrigatorio' => true, 'ufs' => \App\Support\Formatos::UFS])

    <div class="fase-acoes">
        <a class="botao-secundario" href="{{ route('admin.vitimas.visualizar', $vitima->id) }}">Cancelar</a>
        <button class="victim-primary" type="submit">Salvar alterações</button>
    </div>
</form>
@endsection

@push('scripts')
    <script src="{{ asset('js/validacao.js') }}"></script>
    <script src="{{ asset('js/cep.js') }}"></script>
    <script src="{{ asset('js/foto.js') }}"></script>
@endpush
