@extends('estruturas.administracao', [
    'pageTitle' => $estatisticas ? 'Estatísticas' : 'Dashboard',
    'active' => $estatisticas ? 'admin.estatisticas' : 'admin.painel'
])

@section('admin-content')

<header class="admin-heading">

    <h1>
        {{ $estatisticas ? 'Estatísticas' : 'Dashboard' }}
    </h1>

</header>


{{-- ========================================================= --}}
{{-- MÉTRICAS --}}
{{-- ========================================================= --}}

<div class="admin-stats">

    @foreach($metricas as $label => $value)

        <div class="admin-stat">

            <div>

                <strong>
                    {{ $value }}
                </strong>

                <span>
                    {{ $label }}
                </span>

            </div>

        </div>

    @endforeach

</div>


{{-- ========================================================= --}}
{{-- GRÁFICOS --}}
{{-- ========================================================= --}}

<div class="statistics-charts">

    <div class="dashboard-charts">


        {{-- ================================================= --}}
        {{-- GRÁFICO DE ROSCA --}}
        {{-- ================================================= --}}

        <section class="admin-panel">

            <h2>
                Ocorrências por tipo
            </h2>

            <div class="weekly-chart">

                @if($total > 0)

                    <div
                        class="data-donut"
                        style="
                            background: conic-gradient(
                                {{ implode(',', $paradas) }}
                            );
                        "
                        role="img"
                        aria-label="Distribuição das {{ $total }} ocorrências por tipo"
                    >

                        <span>
                            {{ $total }}
                        </span>

                    </div>

                @else

                    <div class="empty-donut">

                        <span>
                            Sem dados
                        </span>

                    </div>

                @endif


                {{-- ========================================= --}}
                {{-- LEGENDA --}}
                {{-- ========================================= --}}

                <ul
                    class="chart-legend"
                    style="
                        list-style: none;
                        margin: 12px 0 0;
                        padding: 0;
                    "
                >

                    @php

                        /*
                         * MESMAS CORES DO GRÁFICO
                         */
                        $coresLegenda = [
                            '#ff6a59',
                            '#ffc64f',
                            '#49b5ee',
                            '#35b76d',
                            '#a174ee',
                        ];

                    @endphp


                    @forelse($porTipo as $index => $row)

                        <li
                            style="
                                display: flex;
                                align-items: center;
                                gap: 8px;
                                margin: 4px 0;
                                padding: 0;
                            "
                        >

                            {{-- ================================= --}}
                            {{-- BOLINHA COLORIDA --}}
                            {{-- ================================= --}}

                            <span
                                style="
                                    display: inline-block !important;
                                    width: 10px !important;
                                    height: 10px !important;
                                    min-width: 10px !important;
                                    min-height: 10px !important;
                                    max-width: 10px !important;
                                    max-height: 10px !important;
                                    flex: 0 0 10px !important;
                                    border-radius: 50% !important;
                                    background-color: {{ $coresLegenda[$index % count($coresLegenda)] }} !important;
                                "
                            ></span>


                            {{-- ================================= --}}
                            {{-- NOME --}}
                            {{-- ================================= --}}

                            <span
                                style="
                                    flex: 1;
                                    margin: 0;
                                    padding: 0;
                                "
                            >
                                {{ $row->tipoOcorrencia }}
                            </span>


                            {{-- ================================= --}}
                            {{-- QUANTIDADE --}}
                            {{-- ================================= --}}

                            <strong>
                                {{ $row->total }}
                            </strong>

                        </li>

                    @empty

                        <li>
                            Nenhuma ocorrência registrada.
                        </li>

                    @endforelse

                </ul>

            </div>

        </section>


        {{-- ================================================= --}}
        {{-- ÚLTIMO SEMESTRE --}}
        {{-- ================================================= --}}

        <section class="admin-panel">

            <h2>
                Último semestre
            </h2>

            <div class="data-bars">

                @foreach($mensal as $month)

                    <div>

                        <strong>
                            {{ $month['total'] }}
                        </strong>

                        <span
                            style="
                                height: {{
                                    ($month['total'] /
                                    max(1, $mensal->max('total')))
                                    * 150
                                }}px;
                            "
                        ></span>

                        <small>
                            {{ $month['rotulo'] }}
                        </small>

                    </div>

                @endforeach

            </div>

        </section>

    </div>

</div>


{{-- ========================================================= --}}
{{-- OCORRÊNCIAS RECENTES --}}
{{-- ========================================================= --}}

@unless($estatisticas)

    <section class="admin-panel backend-recent">

        <h2>
            Ocorrências recentes
        </h2>

        @forelse($recentes as $case)

            <p>

                <a href="{{ route(
                    'admin.ocorrencias.visualizar',
                    $case->id
                ) }}">

                    {{ $case->tipoOcorrencia }}

                </a>

                ·

                {{ $case->dataOcorrencia }}

                ·

                {{ $case->statusAtendimento }}

            </p>

        @empty

            <p>
                Nenhuma ocorrência registrada.
            </p>

        @endforelse

    </section>

@endunless

@endsection