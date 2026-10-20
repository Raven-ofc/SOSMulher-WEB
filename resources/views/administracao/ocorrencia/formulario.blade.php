@extends('estruturas.administracao', ['pageTitle' => 'Registrar ocorrência', 'active' => 'admin.ocorrencias.index'])

@section('admin-content')
@php $erro = fn ($c) => $errors->first($c); @endphp

<header class="admin-heading">
    <h1>Registrar ocorrência</h1>
</header>
<a class="admin-back" href="{{ route('admin.ocorrencias.index') }}">‹ voltar</a>

<form class="admin-panel cadastro-unificado" method="POST" action="{{ route('admin.ocorrencias.salvar') }}" novalidate data-validar autocomplete="off">
    @csrf

    <div class="grade-pessoa">
        <fieldset class="bloco">
            <legend class="bloco-titulo">Pessoas e ocorrência</legend>

            <div class="campo {{ $erro('vitima_id') ? 'is-invalid' : '' }}">
                <label for="vitima_id">Vítima <span class="obrigatorio" aria-hidden="true">*</span></label>
                <select id="vitima_id" name="vitima_id" data-obrigatorio>
                    <option value="">Selecione</option>
                    @foreach($vitimas as $v)
                        <option value="{{ $v->id }}" @selected(old('vitima_id') == $v->id)>{{ $v->nomeVitima }} — {{ $v->cpfVitima }}</option>
                    @endforeach
                </select>
                <p class="campo-erro" role="alert" @if(!$erro('vitima_id')) hidden @endif>{{ $erro('vitima_id') }}</p>
            </div>

            <div class="campo {{ $erro('agressor_id') ? 'is-invalid' : '' }}">
                <label for="agressor_id">Agressor <span class="obrigatorio" aria-hidden="true">*</span></label>
                <select id="agressor_id" name="agressor_id" data-obrigatorio>
                    <option value="">Selecione</option>
                    @foreach($agressores as $a)
                        <option value="{{ $a->id }}" @selected(old('agressor_id') == $a->id)>{{ $a->nomeAgressor }} — {{ $a->cpfAgressor }}</option>
                    @endforeach
                </select>
                <p class="campo-erro" role="alert" @if(!$erro('agressor_id')) hidden @endif>{{ $erro('agressor_id') }}</p>
            </div>

            <div class="campo {{ $erro('tipo') ? 'is-invalid' : '' }}">
                <label for="tipo">Tipo de ocorrência <span class="obrigatorio" aria-hidden="true">*</span></label>
                <select id="tipo" name="tipo" data-obrigatorio>
                    <option value="">Selecione</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo }}" @selected(old('tipo') === $tipo)>{{ $tipo }}</option>
                    @endforeach
                </select>
                <p class="campo-erro" role="alert" @if(!$erro('tipo')) hidden @endif>{{ $erro('tipo') }}</p>
            </div>

            <div class="campo {{ $erro('gravidade') ? 'is-invalid' : '' }}">
                <label for="gravidade">Gravidade <span class="obrigatorio" aria-hidden="true">*</span></label>
                <select id="gravidade" name="gravidade" data-obrigatorio>
                    <option value="">Selecione</option>
                    @foreach(['baixa' => 'Baixa', 'media' => 'Média', 'alta' => 'Alta'] as $valor => $rotulo)
                        <option value="{{ $valor }}" @selected(old('gravidade') === $valor)>{{ $rotulo }}</option>
                    @endforeach
                </select>
                <p class="campo-erro" role="alert" @if(!$erro('gravidade')) hidden @endif>{{ $erro('gravidade') }}</p>
            </div>
        </fieldset>

        <fieldset class="bloco" data-cep-group>
            <legend class="bloco-titulo">Data e local</legend>

            @include('parciais.campo', ['id' => 'data_hora', 'name' => 'data_hora', 'label' => 'Data e hora', 'type' => 'datetime-local', 'value' => old('data_hora'),
                'obrigatorio' => true, 'erro' => $erro('data_hora'), 'attrs' => 'max="' . now()->format('Y-m-d\TH:i') . '"'])

            <div class="linha-cep">
                @include('parciais.campo', ['id' => 'cep', 'name' => 'cep', 'label' => 'CEP', 'value' => old('cep'), 'obrigatorio' => true, 'erro' => $erro('cep'),
                    'ajuda' => 'O endereço é preenchido automaticamente.',
                    'attrs' => 'data-f="cep" data-tipo="cep" data-mascara="cep" inputmode="numeric" maxlength="9" placeholder="00000-000"'])
                <div class="campo cep-estado" aria-live="polite"><span class="cep-spinner" aria-hidden="true"></span><span class="cep-texto">Buscando CEP…</span></div>
            </div>

            @include('parciais.campo', ['id' => 'rua', 'name' => 'rua', 'label' => 'Rua / logradouro', 'value' => old('rua'), 'obrigatorio' => true,
                'erro' => $erro('rua'), 'attrs' => 'data-f="rua" maxlength="200"'])

            <div class="linha-num">
                @include('parciais.campo', ['id' => 'numero', 'name' => 'numero', 'label' => 'Número', 'value' => old('numero'), 'obrigatorio' => true,
                    'erro' => $erro('numero'), 'attrs' => 'data-f="numero" maxlength="50"'])
                @include('parciais.campo', ['id' => 'bairro', 'name' => 'bairro', 'label' => 'Bairro', 'value' => old('bairro'), 'obrigatorio' => true,
                    'erro' => $erro('bairro'), 'attrs' => 'data-f="bairro" maxlength="100"'])
            </div>

            @include('parciais.campo', ['id' => 'cidade', 'name' => 'cidade', 'label' => 'Cidade', 'value' => old('cidade'), 'erro' => $erro('cidade'),
                'attrs' => 'data-f="cidade" maxlength="100"'])
            <input type="hidden" id="uf" name="uf" data-f="uf" value="{{ old('uf') }}">
            <input type="hidden" name="latitude" data-f="lat" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" data-f="lng" value="{{ old('longitude') }}">

            <div class="campo {{ $erro('descricao') ? 'is-invalid' : '' }}">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4" maxlength="10000">{{ old('descricao') }}</textarea>
                <p class="campo-erro" role="alert" @if(!$erro('descricao')) hidden @endif>{{ $erro('descricao') }}</p>
            </div>
        </fieldset>
    </div>

    <div class="fase-acoes">
        <a class="botao-secundario" href="{{ route('admin.ocorrencias.index') }}">Cancelar</a>
        <button class="victim-primary" type="submit">Registrar</button>
    </div>
</form>
@endsection

@push('scripts')
    <script src="{{ asset('js/validacao.js') }}"></script>
    <script src="{{ asset('js/cep.js') }}"></script>
@endpush
