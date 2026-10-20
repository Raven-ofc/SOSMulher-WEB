@extends('estruturas.administracao', ['pageTitle' => 'Novo cadastro', 'active' => 'admin.cadastro.index'])

@section('admin-content')
@php
    // Se o servidor devolveu erros, reabre a primeira fase que tem campo inválido.
    $faseInicial = 'vitima';
    foreach (['vitima', 'agressor', 'tornozeleira', 'medida'] as $f) {
        if (collect($errors->keys())->contains(fn ($chave) => str_starts_with($chave, $f . '.'))) {
            $faseInicial = $f;
            break;
        }
    }
    $passos = ['vitima' => 'Vítima', 'agressor' => 'Agressor', 'tornozeleira' => 'Tornozeleira', 'medida' => 'Medida protetiva', 'revisao' => 'Revisão'];
    $erro = fn ($chave) => $errors->first($chave);
    $modo = old('tornozeleira.modo', 'nova');
@endphp

<header class="admin-heading">
    <h1>Novo cadastro</h1>
    <p>Cadastre a vítima, o agressor, a tornozeleira e gere a medida protetiva.</p>
</header>

<ol class="stepper" aria-label="Fases do cadastro">
    @foreach($passos as $chave => $rotulo)
        <li data-passo="{{ $chave }}" class="{{ $loop->first ? 'is-atual' : '' }}">
            <button type="button" @if(!$loop->first) disabled @endif>
                <span class="stepper-numero">{{ $loop->iteration }}</span>
                <span class="stepper-rotulo">{{ $rotulo }}</span>
            </button>
        </li>
    @endforeach
</ol>

<div id="cadastro-alerta" class="alerta-fase" role="alert" hidden></div>

<form id="cadastro-form" class="admin-panel cadastro-unificado" method="POST" action="{{ route('admin.cadastro.salvar') }}"
      enctype="multipart/form-data" novalidate autocomplete="off"
      data-fase-inicial="{{ $faseInicial }}"
      data-url-validar="{{ route('admin.cadastro.validar', ['fase' => ':fase']) }}">
    @csrf

    {{-- ============================ 1. VÍTIMA ============================ --}}
    <section data-fase="vitima" aria-labelledby="titulo-vitima">
        <h2 class="fase-titulo" id="titulo-vitima">1. Dados da vítima</h2>
        @include('parciais.campos-pessoa', ['prefixo' => 'vitima', 'emailObrigatorio' => true, 'ufs' => $ufs])
        <div class="fase-acoes">
            <a class="botao-secundario" href="{{ route('admin.vitimas.index') }}">Cancelar</a>
            <button type="button" class="victim-primary" data-proximo>Próximo: Agressor →</button>
        </div>
    </section>

    {{-- ============================ 2. AGRESSOR ============================ --}}
    <section data-fase="agressor" aria-labelledby="titulo-agressor" hidden>
        <h2 class="fase-titulo" id="titulo-agressor">2. Dados do agressor</h2>
        @include('parciais.campos-pessoa', ['prefixo' => 'agressor', 'emailObrigatorio' => false, 'ufs' => $ufs])
        <div class="fase-acoes">
            <button type="button" class="botao-secundario" data-anterior>← Voltar</button>
            <button type="button" class="victim-primary" data-proximo>Próximo: Tornozeleira →</button>
        </div>
    </section>

    {{-- ============================ 3. TORNOZELEIRA ============================ --}}
    <section data-fase="tornozeleira" aria-labelledby="titulo-tornozeleira" hidden>
        <h2 class="fase-titulo" id="titulo-tornozeleira">3. Tornozeleira do agressor</h2>
        <p class="fase-intro">A tornozeleira informada aqui é atribuída diretamente ao agressor cadastrado na fase anterior.</p>

        <fieldset class="bloco bloco-estreito">
            <legend class="bloco-titulo">Equipamento</legend>

            <div class="grupo-opcoes opcoes-linha" data-rotulo="Origem da tornozeleira">
                <label class="opcao"><input type="radio" name="tornozeleira[modo]" value="nova" @checked($modo === 'nova')> Cadastrar nova</label>
                <label class="opcao"><input type="radio" name="tornozeleira[modo]" value="existente" @checked($modo === 'existente')> Usar uma disponível</label>
            </div>

            <div data-modo-bloco="nova">
                @include('parciais.campo', ['id' => 'tz-serie', 'name' => 'tornozeleira[numeroSerie]', 'label' => 'Número de série', 'value' => old('tornozeleira.numeroSerie'),
                    'obrigatorio' => true, 'erro' => $erro('tornozeleira.numeroSerie'),
                    'attrs' => 'maxlength="100" placeholder="Ex.: TZ011" autocomplete="off"'])
            </div>

            <div data-modo-bloco="existente" hidden>
                <div class="campo {{ $erro('tornozeleira.tornozeleira_id') ? 'is-invalid' : '' }}">
                    <label for="tz-existente">Tornozeleira disponível <span class="obrigatorio" aria-hidden="true">*</span></label>
                    <select id="tz-existente" name="tornozeleira[tornozeleira_id]" data-obrigatorio>
                        <option value="">{{ $tornozeleirasDisponiveis->isEmpty() ? 'Nenhuma tornozeleira disponível' : 'Selecione' }}</option>
                        @foreach($tornozeleirasDisponiveis as $tz)
                            <option value="{{ $tz->id }}" @selected((string) old('tornozeleira.tornozeleira_id') === (string) $tz->id)>
                                {{ $tz->numeroSerieTornozeleira }} — bateria {{ $tz->bateriaTornozeleira }}%
                            </option>
                        @endforeach
                    </select>
                    <p class="campo-erro" role="alert" @if(!$erro('tornozeleira.tornozeleira_id')) hidden @endif>{{ $erro('tornozeleira.tornozeleira_id') }}</p>
                </div>
            </div>

            @include('parciais.campo', ['id' => 'tz-data', 'name' => 'tornozeleira[dataInstalacao]', 'label' => 'Data de instalação', 'type' => 'date',
                'value' => old('tornozeleira.dataInstalacao'), 'obrigatorio' => true, 'erro' => $erro('tornozeleira.dataInstalacao'),
                'attrs' => 'data-tipo="dataPassada" max="' . today()->toDateString() . '"'])
        </fieldset>

        <div class="fase-acoes">
            <button type="button" class="botao-secundario" data-anterior>← Voltar</button>
            <button type="button" class="victim-primary" data-proximo>Próximo: Medida protetiva →</button>
        </div>
    </section>

    {{-- ============================ 4. MEDIDA PROTETIVA ============================ --}}
    <section data-fase="medida" aria-labelledby="titulo-medida" hidden>
        <h2 class="fase-titulo" id="titulo-medida">4. Medida protetiva</h2>

        <div class="grade-pessoa">
            <fieldset class="bloco">
                <legend class="bloco-titulo">Decisão judicial</legend>

                @include('parciais.campo', ['id' => 'md-processo', 'name' => 'medida[numero_processo]', 'label' => 'Nº do processo (CNJ) — opcional', 'value' => old('medida.numero_processo'),
                    'erro' => $erro('medida.numero_processo'), 'ajuda' => 'Formato: 0000000-00.0000.0.00.0000',
                    'attrs' => 'data-tipo="processo" data-mascara="processo" inputmode="numeric" maxlength="25" placeholder="0000000-00.0000.0.00.0000"'])
                @include('parciais.campo', ['id' => 'md-vara', 'name' => 'medida[vara]', 'label' => 'Vara / juízo — opcional', 'value' => old('medida.vara'),
                    'erro' => $erro('medida.vara'), 'attrs' => 'maxlength="100" placeholder="Ex.: 1ª Vara de Violência Doméstica"'])

                <div class="linha-2">
                    @include('parciais.campo', ['id' => 'md-inicio', 'name' => 'medida[data_inicio]', 'label' => 'Início da vigência', 'type' => 'date', 'value' => old('medida.data_inicio'),
                        'obrigatorio' => true, 'erro' => $erro('medida.data_inicio'), 'attrs' => 'data-tipo="dataPassada" max="' . today()->toDateString() . '"'])
                    @include('parciais.campo', ['id' => 'md-fim', 'name' => 'medida[data_fim]', 'label' => 'Fim da vigência', 'type' => 'date', 'value' => old('medida.data_fim'),
                        'erro' => $erro('medida.data_fim')])
                </div>
                <label class="opcao opcao-solta">
                    <input type="checkbox" name="medida[indeterminada]" value="1" @checked(old('medida.indeterminada'))>
                    Vigência por prazo indeterminado (até nova decisão judicial)
                </label>

                <div class="grupo-opcoes opcoes-linha {{ $erro('medida.nivel_risco') ? 'is-invalid' : '' }}" data-rotulo="Nível de risco" data-grupo-obrigatorio>
                    <span class="campo-rotulo">Nível de risco avaliado <span class="obrigatorio" aria-hidden="true">*</span></span>
                    @foreach($riscos as $valor => $rotulo)
                        <label class="opcao opcao-risco risco-{{ $valor }}">
                            <input type="radio" name="medida[nivel_risco]" value="{{ $valor }}" @checked(old('medida.nivel_risco', 'medio') === $valor)> {{ $rotulo }}
                        </label>
                    @endforeach
                    <p class="campo-erro" role="alert" @if(!$erro('medida.nivel_risco')) hidden @endif>{{ $erro('medida.nivel_risco') }}</p>
                </div>
            </fieldset>

            <fieldset class="bloco">
                <legend class="bloco-titulo">Restrições e perímetro</legend>

                <div class="grupo-opcoes opcoes-coluna {{ $erro('medida.tipos') ? 'is-invalid' : '' }}" data-rotulo="Tipos de medida" data-grupo-obrigatorio>
                    <span class="campo-rotulo">Tipos de medida aplicados <span class="obrigatorio" aria-hidden="true">*</span></span>
                    @foreach($tiposMedida as $chave => $rotulo)
                        <label class="opcao">
                            <input type="checkbox" name="medida[tipos][]" value="{{ $chave }}" @checked(in_array($chave, (array) old('medida.tipos', []), true))> {{ $rotulo }}
                        </label>
                    @endforeach
                    <p class="campo-erro" role="alert" @if(!$erro('medida.tipos')) hidden @endif>{{ $erro('medida.tipos') }}</p>
                </div>

                <div class="linha-2">
                    @include('parciais.campo', ['id' => 'md-distancia', 'name' => 'medida[distancia]', 'label' => 'Distância mínima (m)', 'type' => 'number', 'value' => old('medida.distancia'),
                        'obrigatorio' => true, 'erro' => $erro('medida.distancia'), 'ajuda' => 'Entre 10 e 5.000 metros.',
                        'attrs' => 'min="10" max="5000" step="1" data-min="10" inputmode="numeric" placeholder="Ex.: 300"'])
                    @include('parciais.campo', ['id' => 'md-raio', 'name' => 'medida[raio]', 'label' => 'Raio de alerta (m)', 'type' => 'number', 'value' => old('medida.raio'),
                        'obrigatorio' => true, 'erro' => $erro('medida.raio'), 'ajuda' => 'Sugerido: 50% acima da distância mínima.',
                        'attrs' => 'min="10" max="10000" step="1" data-min="10" inputmode="numeric" placeholder="Ex.: 450"'])
                </div>

                <div class="campo {{ $erro('medida.descricao') ? 'is-invalid' : '' }}">
                    <label for="md-descricao">Descrição da medida <span class="obrigatorio" aria-hidden="true">*</span></label>
                    <textarea id="md-descricao" name="medida[descricao]" rows="3" maxlength="255" data-obrigatorio
                              placeholder="Resumo da decisão aplicada…">{{ old('medida.descricao') }}</textarea>
                    <p class="campo-erro" role="alert" @if(!$erro('medida.descricao')) hidden @endif>{{ $erro('medida.descricao') }}</p>
                </div>

                <div class="campo {{ $erro('medida.observacoes') ? 'is-invalid' : '' }}">
                    <label for="md-obs">Observações internas (opcional)</label>
                    <textarea id="md-obs" name="medida[observacoes]" rows="3" maxlength="2000"
                              placeholder="Informações úteis para a equipe de monitoramento…">{{ old('medida.observacoes') }}</textarea>
                    <p class="campo-erro" role="alert" @if(!$erro('medida.observacoes')) hidden @endif>{{ $erro('medida.observacoes') }}</p>
                </div>
            </fieldset>
        </div>

        <div class="fase-acoes">
            <button type="button" class="botao-secundario" data-anterior>← Voltar</button>
            <button type="button" class="victim-primary" data-proximo>Próximo: Revisão →</button>
        </div>
    </section>

    {{-- ============================ 5. REVISÃO ============================ --}}
    <section data-fase="revisao" aria-labelledby="titulo-revisao" hidden>
        <h2 class="fase-titulo" id="titulo-revisao">5. Revise e conclua</h2>
        <p class="fase-intro">Confira os dados. Ao concluir, a vítima, o agressor, a tornozeleira e a medida protetiva são gravados juntos e a vítima recebe a senha de acesso por e-mail.</p>
        <div id="cadastro-resumo" class="resumo-grade"></div>
        <div class="fase-acoes">
            <button type="button" class="botao-secundario" data-anterior>← Voltar</button>
            <button type="submit" class="victim-primary">Concluir cadastro e gerar medida protetiva</button>
        </div>
    </section>
</form>
@endsection

@push('scripts')
    <script src="{{ asset('js/validacao.js') }}"></script>
    <script src="{{ asset('js/cep.js') }}"></script>
    <script src="{{ asset('js/foto.js') }}"></script>
    <script src="{{ asset('js/cadastro.js') }}"></script>
@endpush
