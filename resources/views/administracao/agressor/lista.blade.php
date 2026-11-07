@extends('estruturas.administracao',['pageTitle'=>'Agressores','active'=>'admin.agressores.index'])
@section('admin-content')
<header class="admin-heading victims-heading">
    <h1>Agressores</h1>
</header>
<form class="occurrence-filters" method="GET">
    <div class="admin-search">
        <input name="busca" aria-label="Pesquisar por nome ou CPF" placeholder="Nome ou CPF" value="{{ request('busca') }}">
        <button type="submit" aria-label="Pesquisar">@include('parciais.icone', ['name' => 'search'])</button>
    </div>
    <select name="status" aria-label="Status">
        <option value="">Todos os status</option>
        @foreach(\App\Http\Controllers\Admin\AgressorController::STATUS as $status)
            <option value="{{ $status }}" @selected(request('status')===$status)>{{ $status }}</option>
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
                    <th>Bairro</th>
                    <th>Tornozeleira</th>
                    <th>Status</th>
                    <th>Abrir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agressores as $agressor)
                    <tr>
                        <td>{{ $agressor->nomeAgressor }}</td>
                        <td>{{ $agressor->cpfAgressor }}</td>
                        <td>{{ $agressor->bairroAgressor ?? '—' }}</td>
                        <td>{{ $agressor->tornozeleira?->numeroSerieTornozeleira ?? '—' }}</td>
                        <td>{{ $agressor->statusAgressor }}</td>
                        <td>
                            <a href="{{ route('admin.agressores.visualizar',$agressor->id) }}" class="btn-visualizar" title="Visualizar ocorrência" aria-label="Visualizar ocorrência">
                                <i data-lucide="external-link"></i>
                            </a>
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
</section>

<script src="https://unpkg.com/lucide@latest"></script>

@stack('scripts')

<script>
    lucide.createIcons();
</script>
@endsection
