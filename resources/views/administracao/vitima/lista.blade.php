@extends('estruturas.administracao',['pageTitle'=>'Usuárias/Vítimas','active'=>'admin.victims'])
@section('admin-content')
<header class="admin-heading victims-heading">
    <h1>Usuárias/Vítimas</h1>
    <a class="safe-requests-link" href="{{ route('admin.victims.requests') }}">Pedidos de locais seguros</a>
</header>
<form class="occurrence-filters" method="GET">
    <a class="victim-primary" href="{{ route('admin.victims.create') }}">+ Adicionar</a>
    <div class="admin-search">
        <input name="search" aria-label="Pesquisar por nome ou CPF" placeholder="Nome ou CPF" value="{{ request('search') }}">
        <button type="submit" aria-label="Pesquisar">@include('parciais.icone', ['name' => 'search'])</button>
    </div>
    <select name="status" aria-label="Status">
        <option value="">Todos os status</option>
        @foreach(['ativo','inativo'] as $status)
            <option @selected(request('status')===$status)>{{ $status }}</option>
        @endforeach
    </select>
    <button class="admin-action secondary">Filtrar</button>
</form>
<section class="admin-panel occurrence-table-panel">
    <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th>Abrir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vitimas as $vitima)
                    <tr>
                        <td>{{ $vitima->nomeVitima }}</td>
                        <td>{{ $vitima->cpfVitima }}</td>
                        <td>{{ ($vitima->telefones->first()?->numeroTelefoneVitima ?? '—') }}</td>
                        <td>{{ $vitima->emailVitima }}</td>
                        <td>{{ ucfirst($vitima->statusVitima) }}</td>
                        <td>
                            <a class="admin-text-link" href="{{ route('admin.victims.show',$vitima->id) }}">Ver cadastro</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="admin-empty table-empty">
                                Nenhum cadastro encontrado.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $vitimas->links() }}
</section>
@endsection
