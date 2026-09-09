@extends('estruturas.administracao',['pageTitle'=>'Agressores','active'=>'admin.aggressors'])
@section('admin-content')
<header class="admin-heading victims-heading">
    <h1>Agressores</h1>
</header>
<form class="occurrence-filters" method="GET">
    <a class="victim-primary" href="{{ route('admin.aggressors.create') }}">+ Adicionar</a>
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
                    <th>Status</th>
                    <th>Abrir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agressores as $agressor)
                    <tr>
                        <td>{{ $agressor->nomeAgressor }}</td>
                        <td>{{ $agressor->cpfAgressor }}</td>
                        <td>{{ ($agressor->telefoneAgressor ?? '—') }}</td>
                        <td>{{ ucfirst($agressor->statusAgressor) }}</td>
                        <td>
                            <a class="admin-text-link" href="{{ route('admin.aggressors.show',$agressor->id) }}">Ver cadastro</a>
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
    {{ $agressores->links() }}
</section>
@endsection
