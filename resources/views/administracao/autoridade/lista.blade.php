@extends('estruturas.administracao',['pageTitle'=>'Autoridades','active'=>'admin.autoridades'])
@section('admin-content')
<header class="topo lista">
    <h1>Autoridades</h1>
</header>
<form class="filtros" method="GET">
    <a class="adicionar" href="{{ route('admin.autoridades.criar') }}">+ Adicionar</a>
    <div class="busca">
        <input name="busca" aria-label="Pesquisar por nome ou CPF" placeholder="Nome ou CPF" value="{{ request('busca') }}">
        <button type="submit" aria-label="Pesquisar">@include('componentes.icone', ['name' => 'search'])</button>
    </div>
    <select name="situacao" aria-label="Status">
        <option value="">Todos os status</option>
        <option value="ativo" @if(request('situacao') === 'ativo') selected @endif>ativo</option>
        <option value="inativo" @if(request('situacao') === 'inativo') selected @endif>inativo</option>
    </select>
    <button class="botao-padrao secundario">Filtrar</button>
</form>
<section class="caixa area-tabela">
    <div class="rolagem-tabela">
        <table class="tabela">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Cargo</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($autoridades ?? []) as $autoridade)
                    <tr>
                        <td>{{ $autoridade->nome }}</td>
                        <td>{{ $autoridade->cpf }}</td>
                        <td>{{ $autoridade->telefone ?? '—' }}</td>
                        <td>{{ $autoridade->email }}</td>
                        <td>{{ $autoridade->cargo ?? '—' }}</td>
                        <td>{{ ucfirst($autoridade->status ?? 'ativo') }}</td>
                        <td class="acoes">
                            <a class="ver" href="{{ route('admin.autoridades.editar',$autoridade->id) }}">Editar</a>
                            <a class="ver excluir" href="#excluir-{{ $autoridade->id }}">Excluir</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="vazio sem-registros">
                                Nenhuma autoridade encontrada.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @isset($autoridades)
    {{ $autoridades->links() }}
    @endisset
</section>
@foreach(($autoridades ?? []) as $autoridade)
    <div class="modal" id="excluir-{{ $autoridade->id }}" role="dialog" aria-modal="true" aria-labelledby="excluir-titulo-{{ $autoridade->id }}">
        <div class="caixa modal-caixa">
            <h2 id="excluir-titulo-{{ $autoridade->id }}">Excluir autoridade?</h2>
            <p>Tem certeza que deseja excluir <strong>{{ $autoridade->nome }}</strong>? Essa ação não pode ser desfeita.</p>
            <div class="modal-acoes">
                <a class="botao-padrao secundario" href="#">Cancelar</a>
                <form method="POST" action="{{ route('admin.autoridades.remover',$autoridade->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="botao-padrao" type="submit">Excluir</button>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection