@extends('estruturas.administracao',['pageTitle'=>$statistics?'Estatísticas':'Dashboard','active'=>$statistics?'admin.statistics':'dashboard'])
@section('admin-content')
<header class="admin-heading">
    <h1>{{ $statistics?'Estatísticas':'Dashboard' }}</h1>
</header>
<div class="admin-stats">
    @foreach($metrics as $label=>$value)
        <div class="admin-stat">
            <div>
                <strong>{{ $value }}</strong>
                <span>{{ $label }}</span>
            </div>
        </div>
    @endforeach
</div>
<div class="statistics-charts">
    <div class="dashboard-charts">
        <section class="admin-panel">
            <h2>Gráfico - Análise Semanal</h2>
            <div class="weekly-chart">
                @if($total)
                    <div class="data-donut" style="background:conic-gradient({{ implode(',',$stops) }})" role="img" aria-label="{{ $total }} ocorrências na última semana">
                        <span>{{ $total }}</span>
                    </div>
                @else
                    <div class="empty-donut">
                        <span>Sem dados</span>
                    </div>
                @endif
                <ul class="chart-legend">
                    @forelse($weekly as $row)
                        <li>
                            {{ $row->tipoOcorrencia }}
                            :
                            {{ $row->total }}
                        </li>
                    @empty
                        <li>
                            Nenhuma ocorrência nos últimos sete dias.
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>
        <section class="admin-panel">
            <h2>Último semestre</h2>
            <div class="data-bars">
                @foreach($months as $month)
                    <div>
                        <strong>{{ $month['total'] }}</strong>
                        <span style="height:{{ $month['total']/max(1,$months->max('total'))*150 }}px">
                        </span>
                        <small>{{ $month['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>
@unless($statistics)
    <section class="admin-panel backend-recent">
        <h2>Ocorrências recentes</h2>
        @forelse($recent as $case)
            <p>
                <a href="{{ route('admin.occurrences.show',$case->id) }}">{{ $case->tipoOcorrencia }}</a>
                ·
                {{ $case->dataOcorrencia }}
                ·
                {{ $case->statusAtendimento }}
            </p>
        @empty
            <p>Nenhuma ocorrência registrada.</p>
        @endforelse
    </section>
@endunless
@endsection
