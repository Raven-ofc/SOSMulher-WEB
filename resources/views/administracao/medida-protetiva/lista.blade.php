@extends('estruturas.administracao', ['pageTitle' => 'Medidas Protetivas', 'active' => 'admin.medidas-protetivas'])
@section('admin-content')
<header class="admin-heading">
    <h1>Medidas Protetivas</h1>
</header>
<form class="occurrence-filters victims-filters" method="GET" action="{{ route('admin.medidas-protetivas') }}">
    <a class="victim-primary" href="{{ route('admin.cadastro.index') }}">+ Novo cadastro</a>
    <div class="admin-search">
        <label class="somente-leitor" for="measure-search">Pesquisar medidas protetivas</label>
        <input id="measure-search" name="busca" value="{{ request('busca') }}" placeholder="Vítima, agressor, processo…">
        <button type="submit" aria-label="Pesquisar">@include('parciais.icone', ['name' => 'search'])</button>
    </div>
    <label class="somente-leitor" for="measure-status">Status</label>
    <select name="status" id="measure-status">
        <option value="">Todos os status</option>
        @foreach(\App\Http\Controllers\Admin\MedidaController::STATUS as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
        @endforeach
    </select>
    <label class="somente-leitor" for="measure-risk">Risco</label>
    <select name="risco" id="measure-risk">
        <option value="">Qualquer risco</option>
        @foreach(\App\Models\TbMedida::RISCOS as $v => $r)
            <option value="{{ $v }}" @selected(request('risco') === $v)>{{ $r }}</option>
        @endforeach
    </select>
    <button class="admin-action secondary" type="submit">Filtrar</button>
</form>
<section class="admin-panel occurrence-table-panel victims-table" aria-label="Lista de medidas protetivas">
    <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nº Medida</th>
                    <th>Agressor</th>
                    <th>Tornozeleira</th>
                    <th>Bateria</th>
                    <th>Vítima</th>
                    <th>Perímetro / alerta</th>
                    <th>Risco</th>
                    <th>Status</th>
                    <th>Abrir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medidas as $medida)
                    <tr>
                        <td>{{ $medida->numeroFormatado() }}</td>
                        <td>{{ $medida->agressor?->nomeAgressor ?? '—' }}</td>
                        <td>{{ $medida->agressor?->tornozeleira?->numeroSerieTornozeleira ?? '—' }}</td>
                        <td>{{ $medida->agressor?->tornozeleira ? $medida->agressor->tornozeleira->bateriaTornozeleira . '%' : '—' }}</td>
                        <td>{{ $medida->vitima?->nomeVitima ?? '—' }}</td>
                        <td>{{ $medida->distanciaMedida }} m / {{ $medida->raioAlertaMedida }} m</td>
                        <td><span class="selo-risco risco-{{ $medida->nivelRiscoMedida }}">{{ $medida->riscoRotulo() }}</span></td>
                        <td>{{ $medida->statusMedida }}</td>
                        <td><a class="admin-text-link" href="{{ route('admin.medidas-protetivas.visualizar', $medida->id) }}">Visualizar ↗</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="admin-empty table-empty">
                                @include('parciais.icone', ['name' => 'book'])
                                <h2>Nenhuma medida encontrada</h2>
                                <p>As medidas são geradas no final do cadastro unificado.</p>
                                <a class="admin-text-link" href="{{ route('admin.cadastro.index') }}">Iniciar novo cadastro</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $medidas->links() }}
</section>
@endsection
