@extends('estruturas.administracao', ['pageTitle' => 'Ocorrências', 'active' => 'admin.ocorrencias.index'])

@section('admin-content')

<header class="admin-heading">
    <h1>Ocorrências</h1>
</header>

<ul class="legenda-status" aria-label="Legenda de cores por status">
    @foreach(\App\Models\TbOcorrencia::STATUS as $chave => [$rotulo, $classe])
        <li>
            <a class="selo-status selo-{{ $classe }}" href="{{ request()->fullUrlWithQuery(['status' => request('status') === $chave ? null : $chave, 'page' => null]) }}"
               @if(request('status') === $chave) aria-current="true" @endif>
                <i aria-hidden="true"></i>{{ $rotulo }} <strong>{{ $contagem[$chave] ?? 0 }}</strong>
            </a>
        </li>
    @endforeach
</ul>

<form class="occurrence-filters" method="GET">
    <a class="victim-primary" href="{{ route('admin.ocorrencias.criar') }}">+ Registrar ocorrência</a>
    <div class="admin-search">
        <input name="busca" aria-label="Pesquisar ocorrência" placeholder="Tipo, local, bairro ou CEP" value="{{ request('busca') }}">
        <button aria-label="Pesquisar">@include('parciais.icone', ['name' => 'search'])</button>
    </div>
    <select name="status" aria-label="Status">
        <option value="">Todos os status</option>
        @foreach(\App\Models\TbOcorrencia::STATUS as $chave => [$rotulo])
            <option value="{{ $chave }}" @selected(request('status') === $chave)>{{ $rotulo }}</option>
        @endforeach
    </select>
    <select name="gravidade" aria-label="Gravidade">
        <option value="">Toda gravidade</option>
        @foreach(['alta' => 'Alta', 'media' => 'Média', 'baixa' => 'Baixa'] as $v => $r)
            <option value="{{ $v }}" @selected(request('gravidade') === $v)>{{ $r }}</option>
        @endforeach
    </select>
    <div class="admin-date-filter">
        <label>De <input type="date" name="de" value="{{ request('de') }}"></label>
        <label>Até <input type="date" name="ate" value="{{ request('ate') }}"></label>
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
                    <tr class="linha-status linha-{{ $ocorrencia->statusClasse() }}">
                        <td>{{ \Carbon\Carbon::parse($ocorrencia->dataHoraOcorrencia ?? $ocorrencia->dataOcorrencia)->format('d/m/Y H:i') }}</td>
                        <td>{{ $ocorrencia->tipoOcorrencia }}</td>
                        <td>{{ $ocorrencia->localOcorrencia ?? '—' }}</td>
                        <td>{{ $ocorrencia->bairroOcorrencia ?? '—' }}</td>
                        <td>@include('parciais.status-ocorrencia', ['ocorrencia' => $ocorrencia])</td>
                        <td>
                            <a href="{{ route('admin.ocorrencias.visualizar', $ocorrencia->id) }}">Visualizar ↗</a>
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
