@extends('estruturas.administracao',['pageTitle'=>'Ocorrências','active'=>'admin.occurrences'])
@section('admin-content')
<header class="admin-heading">
    <h1>Ocorrências</h1>
</header>
<form class="occurrence-filters" method="GET">
    <a class="victim-primary" href="{{ route('admin.ocorrencias.criar') }}">+ Registrar ocorrência</a>
    <div class="admin-search">
        <input name="busca" aria-label="Pesquisar ocorrência" placeholder="Pesquisar..." value="{{ request('busca') }}">
        <button aria-label="Pesquisar">@include('parciais.icone', ['name' => 'search'])</button>
    </div>
    <div class="admin-date-filter">
        <label>
            De
            <input type="date" name="de" value="{{ request('de') }}">
        </label>
        <label>
            Até
            <input type="date" name="ate" value="{{ request('ate') }}">
        </label>
    </div>
    <button class="admin-action secondary">Filtrar</button>
</form>
<section class="admin-panel occurrence-table-panel">
    <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Ocorrência</th>
                    <th>Local</th>
                    <th>Bairro</th>
                    <th>Status</th>
                    <th>Abrir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ocorrencias as $ocorrencia)
                    <tr>
                        <td>{{ $ocorrencia->dataHoraOcorrencia ?? $ocorrencia->dataOcorrencia }}</td>
                        <td>{{ $ocorrencia->tipoOcorrencia }}</td>
                        <td>{{ $ocorrencia->localOcorrencia ?? '—' }}</td>
                        <td>{{ $ocorrencia->bairroOcorrencia ?? '—' }}</td>
                        <td>{{ $ocorrencia->statusAtendimento }}</td>
                        <td>
                            <a href="{{ route('admin.ocorrencias.visualizar', $ocorrencia->id) }}">
                                Visualizar ↗
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="admin-empty table-empty">
                                Nenhum registro encontrado.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $ocorrencias->links() }}
</section>
@endsection