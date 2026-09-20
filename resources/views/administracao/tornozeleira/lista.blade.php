@extends('estruturas.administracao', [
    'pageTitle' => 'Gerenciamento de Tornozeleiras',
    'active' => 'admin.tornozeleiras.index'
])

@section('admin-content')

<header class="admin-heading">
    <h1>Gerenciamento de Tornozeleiras</h1>
</header>

<form
    class="occurrence-filters victims-filters"
    method="GET"
    action="{{ route('admin.tornozeleiras.index') }}"
>

    <a
        class="victim-primary"
        href="{{ route('admin.tornozeleiras.criar') }}"
    >
        + Adicionar
    </a>

    <div class="admin-search">
        <label class="somente-leitor" for="device-search">
            Pesquisar tornozeleiras
        </label>

        <input
            id="device-search"
            name="busca"
            value="{{ request('busca') }}"
            placeholder="pesquisar..."
        >

        <button type="submit" aria-label="Pesquisar">
            @include('parciais.icone', ['name' => 'search'])
        </button>
    </div>

    <label class="somente-leitor" for="device-status">
        Status
    </label>

    <select name="status" id="device-status">
        <option value="">Status</option>

        <option
            value="Disponível"
            @selected(request('status') === 'Disponível')
        >
            Disponível
        </option>

        <option
            value="Ativa"
            @selected(request('status') === 'Ativa')
        >
            Ativa
        </option>

        <option
            value="Inativa"
            @selected(request('status') === 'Inativa')
        >
            Inativa
        </option>

        <option
            value="Manutenção"
            @selected(request('status') === 'Manutenção')
        >
            Manutenção
        </option>
    </select>

</form>

<div class="device-grid">

    @forelse($tornozeleiras as $tornozeleira)

        <section
            class="admin-panel device-card"
            aria-label="Cartão da tornozeleira"
        >

            <header>
                <h2>
                    Tornozeleira —
                    {{ $tornozeleira->numeroSerieTornozeleira }}
                </h2>

                <span class="device-state">
                    {{ $tornozeleira->statusTornozeleira }}

                    <i aria-hidden="true"></i>

                    <span class="somente-leitor">
                        Status da tornozeleira
                    </span>
                </span>
            </header>

            <dl class="victim-info">

                <div>
                    <dt>Agressor:</dt>

                    <dd>
                        @if($tornozeleira->agressor)
                            {{ $tornozeleira->agressor->nomeAgressor }}
                        @else
                            Não atribuída
                        @endif
                    </dd>
                </div>

                <div>
                    <dt>Bateria:</dt>

                    <dd class="device-battery">
                        <span aria-hidden="true"></span>

                        {{ $tornozeleira->bateriaTornozeleira }}%
                    </dd>
                </div>

                <div>
                    <dt>Data de instalação:</dt>

                    <dd>
                        {{ \Carbon\Carbon::parse($tornozeleira->dataInstalacaoTornozeleira)->format('d/m/Y') }}
                    </dd>
                </div>

            </dl>

            <footer>

                <a
                    class="admin-action"
                    href="{{ route('admin.tornozeleiras.visualizar', $tornozeleira->id) }}"
                >
                    Visualizar
                </a>

                <a
                    class="admin-action"
                    href="{{ route('admin.tornozeleiras.editar', $tornozeleira->id) }}"
                >
                    Editar
                </a>

            </footer>

        </section>

    @empty

        <section class="admin-panel">
            <p>Nenhuma tornozeleira encontrada.</p>
        </section>

    @endforelse

</div>

{{ $tornozeleiras->links() }}

@endsection