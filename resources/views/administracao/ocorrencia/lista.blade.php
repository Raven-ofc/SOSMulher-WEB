@extends('estruturas.administracao',['pageTitle'=>'Ocorrências','active'=>'admin.occurrences'])
@section('admin-content')
<header class="admin-heading">
    <h1>Ocorrências</h1>
</header>
<form class="occurrence-filters" method="GET">
    <a class="victim-primary" href="{{ route('admin.occurrences.create') }}">+ Registrar ocorrência</a>
    <div class="admin-search">
        <input name="search" aria-label="Pesquisar ocorrência" placeholder="Pesquisar..." value="{{ request('search') }}">
        <button aria-label="Pesquisar">@include('parciais.icone', ['name' => 'search'])</button>
    </div>
    <div class="admin-date-filter">
        <label>
            De
            <input type="date" name="from" value="{{ request('from') }}">
        </label>
        <label>
            Até
            <input type="date" name="to" value="{{ request('to') }}">
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
                @forelse($records as $record)
                    <tr>
                        <td>{{ $record->dataHoraOcorrencia ?? $record->dataOcorrencia }}</td>
                        <td>{{ $record->tipoOcorrencia }}</td>
                        <td>{{ $record->localOcorrencia ?? '—' }}</td>
                        <td>{{ $record->bairroOcorrencia ?? '—' }}</td>
                        <td>{{ $record->statusAtendimento }}</td>
                        <td>
                            <a href="{{ route('admin.occurrences.show',$record->id) }}">Visualizar ↗</a>
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
    {{ $records->links() }}
</section>
@endsection
