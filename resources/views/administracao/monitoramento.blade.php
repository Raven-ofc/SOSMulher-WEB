@extends('estruturas.administracao', ['pageTitle' => 'Monitoramento', 'active' => 'admin.monitoramento'])
@section('admin-content')
<div class="monitoring-grid">
    <section class="admin-panel monitoring-map-panel">
        <h1>Mapa de monitoramento</h1>

        <form class="mapa-filtros" onsubmit="return false" aria-label="Filtros do mapa">
            <div class="campo">
                <label for="f-regiao">Região</label>
                <select id="f-regiao">
                    <option value="">Todas as regiões</option>
                    @foreach($regioes as $regiao)
                        <option value="{{ $regiao }}">{{ $regiao }}</option>
                    @endforeach
                </select>
            </div>
            <div class="campo">
                <label for="f-bairro">Bairro</label>
                <select id="f-bairro"><option value="">Todos os bairros</option></select>
            </div>
            <div class="campo">
                <label for="f-cep">CEP</label>
                <input id="f-cep" type="text" inputmode="numeric" maxlength="9" placeholder="Ex.: 08410 ou 08410-000" data-mascara="cep" autocomplete="off">
            </div>
            <div class="campo">
                <label for="f-status">Status da ocorrência</label>
                <select id="f-status">
                    <option value="">Todos</option>
                    @foreach($statusOcorrencia as $chave => [$rotulo])
                        <option value="{{ $chave }}">{{ $rotulo }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" id="f-limpar" class="botao-secundario">Limpar filtros</button>
        </form>

        <div class="mapa-camadas" role="group" aria-label="Camadas do mapa">
            <label class="opcao"><input type="checkbox" id="c-ocorrencias" checked> <i class="ponto ponto-ocorrencia"></i> Ocorrências</label>
            <label class="opcao"><input type="checkbox" id="c-tornozeleiras" checked> <i class="ponto ponto-tornozeleira"></i> Tornozeleiras</label>
            <label class="opcao"><input type="checkbox" id="c-vitimas"> <i class="ponto ponto-vitima"></i> Vítimas (última localização)</label>
        </div>

        <ul class="legenda-status legenda-mapa" aria-label="Cores das ocorrências">
            @foreach($statusOcorrencia as $chave => [$rotulo, $classe])
                <li><span class="selo-status selo-{{ $classe }}"><i aria-hidden="true"></i>{{ $rotulo }}</span></li>
            @endforeach
        </ul>

        <div id="mapa" class="monitoring-map" role="application" aria-label="Mapa com ocorrências e tornozeleiras"></div>
        <p id="mapa-contagem" class="mapa-contagem" aria-live="polite"></p>
        <script type="application/json" id="dados-mapa">{!! json_encode($pontos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}</script>
    </section>

    <aside class="monitoring-side" aria-label="Resumo do monitoramento">
        <div class="admin-stats">
            <div class="admin-stat">
                <span class="stat-icon blue">@include('parciais.icone', ['name' => 'monitor'])</span>
                <div><strong>{{ $resumo['aparelhosAtivos'] }}</strong><span>Aparelhos ativos</span></div>
            </div>
            <div class="admin-stat">
                <span class="stat-icon violet">@include('parciais.icone', ['name' => 'chip'])</span>
                <div><strong>{{ $resumo['tornozeleirasOnline'] }}</strong><span>Com sinal nas últimas 24h</span></div>
            </div>
            <div class="admin-stat">
                <span class="stat-icon red">@include('parciais.icone', ['name' => 'warning'])</span>
                <div><strong>{{ $resumo['ocorrenciasHoje'] }}</strong><span>Ocorrências hoje</span></div>
            </div>
            <div class="admin-stat metrica-aberta">
                <span class="stat-icon red">@include('parciais.icone', ['name' => 'clip'])</span>
                <div><strong>{{ $resumo['ocorrenciasAbertas'] }}</strong><span>Ocorrências abertas</span></div>
            </div>
        </div>
        <section class="admin-panel admin-alerts">
            <h2>Alertas recentes</h2>
            @forelse($alertas as $alerta)
                <p class="alerta-item">
                    <strong>{{ $alerta->tipoAlerta }}</strong>
                    <span>{{ $alerta->descricaoAlerta }}</span>
                    <small>{{ \Carbon\Carbon::parse($alerta->dataHoraAlerta)->format('d/m/Y H:i') }} · {{ $alerta->statusAlerta }}</small>
                </p>
            @empty
                <div class="admin-empty alert-empty">
                    @include('parciais.icone', ['name' => 'warning'])
                    <p>Nenhum alerta registrado.</p>
                </div>
            @endforelse
        </section>
    </aside>
</div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script src="{{ asset('js/validacao.js') }}"></script>
    <script src="{{ asset('js/mapa.js') }}"></script>
@endpush
