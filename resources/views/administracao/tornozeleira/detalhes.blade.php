@extends('estruturas.administracao', [
    'pageTitle' => 'Detalhes da Tornozeleira',
    'active' => 'admin.tornozeleiras.index'
])

@section('admin-content')

<header class="admin-heading detail-heading">
    <h1>Gerenciamento de Tornozeleiras</h1>
    <p>tornozeleira/visualizar</p>
</header>

<a
    class="admin-back"
    href="{{ route('admin.tornozeleiras.index') }}"
>
    ‹ voltar
</a>

<div class="device-grid">

    <section
        class="admin-panel device-card"
        aria-label="Detalhes da tornozeleira"
    >

        <header>
            <h2>
                Tornozeleira —
                {{ $tornozeleira->numeroSerieTornozeleira }}
            </h2>

            <span class="device-state">
                {{ $tornozeleira->statusTornozeleira }}

                <i aria-hidden="true"></i>
            </span>
        </header>

        <dl class="victim-info">

            <div>
                <dt>Número de série:</dt>
                <dd>
                    {{ $tornozeleira->numeroSerieTornozeleira }}
                </dd>
            </div>

            <div>
                <dt>Status:</dt>
                <dd>
                    {{ $tornozeleira->statusTornozeleira }}
                </dd>
            </div>

            <div>
                <dt>Bateria:</dt>
                <dd class="device-battery">
                    {{ $tornozeleira->bateriaTornozeleira }}%
                </dd>
            </div>

            <div>
                <dt>Data de instalação:</dt>
                <dd>
                    {{ \Carbon\Carbon::parse(
                        $tornozeleira->dataInstalacaoTornozeleira
                    )->format('d/m/Y') }}
                </dd>
            </div>

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

        </dl>

        <footer>

            <a
                class="admin-action"
                href="{{ route('admin.tornozeleiras.editar', $tornozeleira->id) }}"
            >
                Editar
            </a>

        </footer>

    </section>

</div>

@endsection