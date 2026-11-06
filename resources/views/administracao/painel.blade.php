@extends('estruturas.administracao', [
    'pageTitle' => $estatisticas ? 'Estatísticas' : 'Dashboard',
    'active' => $estatisticas ? 'admin.estatisticas' : 'admin.painel'
])

@section('admin-content')
@php
    $coresDonut = ['#ff6a59', '#ffc64f', '#49b5ee', '#35b76d', '#a174ee', '#f48fb1', '#80cbc4'];
    $classesMetricas = ['Abertas' => 'metrica-aberta', 'Em andamento' => 'metrica-andamento', 'Encerradas' => 'metrica-encerrada'];
@endphp

<header class="admin-heading">
    <h1>{{ $estatisticas ? 'Estatísticas' : 'Dashboard' }}</h1>
    @if($estatisticas)
        <p>Visão geral das ocorrências, medidas protetivas e equipamentos.</p>
    @endif
</header>

{{-- ============================ Indicadores ============================ --}}
<div class="admin-stats">
    @foreach($metricas as $label => $value)
        <div class="admin-stat {{ $classesMetricas[$label] ?? '' }}">
            <div>
                <strong>{{ $value }}</strong>
                <span>{{ $label }}</span>
            </div>
        </div>
    @endforeach
</div>

@if($estatisticas)
    <div class="admin-stats kpis-extra">
        @foreach($kpis as $label => $value)
            <div class="admin-stat">
                <div>
                    <strong>{{ $value }}</strong>
                    <span>{{ $label }}</span>
                </div>
            </div>
        @endforeach
        <div class="admin-stat">
            <div>
                <strong>{{ $reincidentes }}</strong>
                <span>Agressores reincidentes</span>
            </div>
        </div>
    </div>

    {{-- ============================ Gráficos ============================ --}}
    <div class="graficos-grade">
        <section class="grupo-graficos">
            <div class="grupo-graficos-cabecalho">
                <h2>Visão geral das ocorrências</h2>
                <p>Acompanhe a evolução e a distribuição das ocorrências.</p>
            </div>

            <div class="grupo-graficos-grade">

                <section class="admin-panel grafico-painel grafico-destaque">
                    <h3>Evolução diária</h3>
                    <p class="grafico-descricao">Ocorrências registradas nos últimos 30 dias.</p>
                    <div class="grafico-caixa grafico-largo">
                        <canvas data-grafico="diario" data-tipo="line"
                            role="img" aria-label="Ocorrências por dia nos últimos 30 dias"></canvas>
                    </div>
                </section>

                <section class="admin-panel grafico-painel">
                    <h3>Ocorrências por tipo</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="tipo" data-tipo="doughnut"
                            role="img" aria-label="Ocorrências por tipo"></canvas>
                    </div>
                </section>

                <section class="admin-panel grafico-painel">
                    <h3>Ocorrências por status</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="status" data-tipo="doughnut"
                            role="img" aria-label="Ocorrências por status"></canvas>
                    </div>
                </section>

            </div>
        </section>

        <section class="grupo-graficos">
            <div class="grupo-graficos-cabecalho">
                <h2>Análise das ocorrências</h2>
                <p>Visualize a gravidade e o comportamento das ocorrências ao longo dos meses.</p>
            </div>

            <div class="grupo-graficos-grade">

                <section class="admin-panel grafico-painel">
                    <h3>Ocorrências por gravidade</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="gravidade" data-tipo="bar"
                            role="img" aria-label="Ocorrências por gravidade"></canvas>
                    </div>
                </section>

                <section class="admin-panel grafico-painel">
                    <h3>Últimos 12 meses</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="mensal" data-tipo="bar"
                            role="img" aria-label="Ocorrências por mês"></canvas>
                    </div>
                </section>

            </div>
        </section>


        {{-- ================= DISTRIBUIÇÃO GEOGRÁFICA ================= --}}
        <section class="grupo-graficos">
            <div class="grupo-graficos-cabecalho">
                <h2>Distribuição geográfica</h2>
                <p>Identifique as regiões com maior concentração de ocorrências.</p>
            </div>

            <div class="grupo-graficos-grade">

                <section class="admin-panel grafico-painel">
                    <h3>Bairros com mais ocorrências</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="bairro" data-tipo="barh"
                            role="img" aria-label="Ocorrências por bairro"></canvas>
                    </div>
                </section>

                <section class="admin-panel grafico-painel">
                    <h3>Ocorrências por região</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="regiao" data-tipo="bar"
                            role="img" aria-label="Ocorrências por região"></canvas>
                    </div>
                </section>

            </div>
        </section>

        <section class="grupo-graficos">
            <div class="grupo-graficos-cabecalho">
                <h2>Distribuição por período</h2>
                <p>Identifique os dias e horários com maior incidência de ocorrências.</p>
            </div>

            <div class="grupo-graficos-grade">

                <section class="admin-panel grafico-painel">
                    <h3>Ocorrências por horário</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="faixa" data-tipo="bar"
                            role="img" aria-label="Ocorrências por faixa horária"></canvas>
                    </div>
                </section>

                <section class="admin-panel grafico-painel">
                    <h3>Ocorrências por dia da semana</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="diaSemana" data-tipo="bar"
                            role="img" aria-label="Ocorrências por dia da semana"></canvas>
                    </div>
                </section>

            </div>
        </section>


        {{-- ================= MEDIDAS PROTETIVAS ================= --}}
        <section class="grupo-graficos">
            <div class="grupo-graficos-cabecalho">
                <h2>Medidas protetivas e monitoramento</h2>
                <p>Acompanhe a situação das medidas e dos dispositivos de monitoramento.</p>
            </div>

            <div class="grupo-graficos-grade">

                <section class="admin-panel grafico-painel">
                    <h3>Medidas protetivas por status</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="medidas" data-tipo="doughnut"
                            data-rotulo="Medidas" role="img"
                            aria-label="Medidas por status"></canvas>
                    </div>
                </section>

                <section class="admin-panel grafico-painel">
                    <h3>Nível de risco das medidas</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="risco" data-tipo="bar"
                            data-rotulo="Medidas" role="img"
                            aria-label="Medidas por nível de risco"></canvas>
                    </div>
                </section>

                <section class="admin-panel grafico-painel">
                    <h3>Tornozeleiras por status</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="tornozeleiras" data-tipo="doughnut"
                            data-rotulo="Tornozeleiras" role="img"
                            aria-label="Tornozeleiras por status"></canvas>
                    </div>
                </section>

                <section class="admin-panel grafico-painel">
                    <h3>Nível de bateria das tornozeleiras</h3>
                    <div class="grafico-caixa">
                        <canvas data-grafico="bateria" data-tipo="bar"
                            data-rotulo="Tornozeleiras" role="img"
                            aria-label="Faixas de bateria"></canvas>
                    </div>
                </section>

            </div>
        </section>

    </div>

    <script type="application/json" id="dados-graficos">{!! json_encode($graficos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}</script>
@else
    {{-- ============================ Home ============================ --}}
    <div class="statistics-charts">
        <div class="dashboard-charts">
            <section class="admin-panel">
                <h2>Ocorrências por tipo</h2>
                <div class="weekly-chart">
                    @if($total > 0)
                        <div class="data-donut" style="background: conic-gradient({{ implode(',', $paradas) }});" role="img" aria-label="Distribuição das {{ $total }} ocorrências por tipo">
                            <span>{{ $total }}</span>
                        </div>
                    @else
                        <div class="empty-donut"><span>Sem dados</span></div>
                    @endif

                    <ul class="chart-legend">
                        @forelse($porTipo as $tipo => $quantidade)
                            <li>
                                <span class="legenda-bolinha" style="background-color: {{ $coresDonut[$loop->index % count($coresDonut)] }}"></span>
                                <span class="legenda-nome">{{ $tipo }}</span>
                                <strong>{{ $quantidade }}</strong>
                            </li>
                        @empty
                            <li>Nenhuma ocorrência registrada.</li>
                        @endforelse
                    </ul>
                </div>
            </section>

            <section class="admin-panel">
                <h2>Último semestre</h2>
                <div class="data-bars">
                    @foreach($mensal as $month)
                        <div>
                            <strong>{{ $month['total'] }}</strong>
                            <span style="height: {{ ($month['total'] / max(1, $mensal->max('total'))) * 150 }}px;"></span>
                            <small>{{ $month['rotulo'] }}</small>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <section class="admin-panel backend-recent">
        <h2>Ocorrências recentes</h2>
        <ul class="lista-recentes">
            @forelse($recentes as $case)
                <li class="item-status item-{{ $case->statusClasse() }}">
                    <a href="{{ route('admin.ocorrencias.visualizar', $case->id) }}">{{ $case->tipoOcorrencia }}</a>
                    <span class="recente-data">{{ \Carbon\Carbon::parse($case->dataHoraOcorrencia)->format('d/m/Y H:i') }}</span>
                    @include('parciais.status-ocorrencia', ['ocorrencia' => $case])
                </li>
            @empty
                <li>Nenhuma ocorrência registrada.</li>
            @endforelse
        </ul>
    </section>
@endif
@endsection

@if($estatisticas)
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
        <script src="{{ asset('js/estatisticas.js') }}"></script>
    @endpush
@endif
