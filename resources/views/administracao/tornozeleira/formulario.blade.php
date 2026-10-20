@extends('estruturas.administracao', ['pageTitle' => 'Editar tornozeleira', 'active' => 'admin.tornozeleiras.index'])

@section('admin-content')
@php
    $erro = fn ($c) => $errors->first($c);
    $statusAtual = old('status', $tornozeleira->statusTornozeleira);
@endphp
<header class="admin-heading">
    <h1>Gerenciamento de Tornozeleiras</h1>
</header>
<a class="admin-back" href="{{ route('admin.tornozeleiras.index') }}">‹ voltar</a>

<form class="admin-panel cadastro-unificado" method="POST" action="{{ route('admin.tornozeleiras.atualizar', $tornozeleira->id) }}" novalidate data-validar autocomplete="off">
    @csrf
    @method('PUT')

    <h2 class="fase-titulo">Editar tornozeleira</h2>
    <p class="fase-intro">Uma tornozeleira nova é cadastrada e atribuída ao agressor no <a class="admin-text-link" href="{{ route('admin.cadastro.index') }}">cadastro unificado</a>.</p>

    <fieldset class="bloco bloco-estreito">
        @include('parciais.campo', ['id' => 'numeroSerie', 'name' => 'numeroSerie', 'label' => 'Número de série', 'value' => old('numeroSerie', $tornozeleira->numeroSerieTornozeleira),
            'obrigatorio' => true, 'erro' => $erro('numeroSerie'), 'attrs' => 'maxlength="100"'])

        <div class="campo {{ $erro('status') ? 'is-invalid' : '' }}">
            <label for="status">Status <span class="obrigatorio" aria-hidden="true">*</span></label>
            <select id="status" name="status" data-obrigatorio>
                @foreach(\App\Http\Controllers\Admin\TornozeleiraController::STATUS as $s)
                    <option value="{{ $s }}" @selected($statusAtual === $s)>{{ $s }}</option>
                @endforeach
            </select>
            <p class="campo-erro" role="alert" @if(!$erro('status')) hidden @endif>{{ $erro('status') }}</p>
        </div>

        @include('parciais.campo', ['id' => 'dataInstalacao', 'name' => 'dataInstalacao', 'label' => 'Data de instalação', 'type' => 'date',
            'value' => old('dataInstalacao', $tornozeleira->dataInstalacaoTornozeleira), 'obrigatorio' => true, 'erro' => $erro('dataInstalacao'),
            'attrs' => 'data-tipo="dataPassada" max="' . today()->toDateString() . '"'])

        @include('parciais.campo', ['id' => 'bateria', 'name' => 'bateria', 'label' => 'Bateria (%)', 'type' => 'number', 'value' => old('bateria', $tornozeleira->bateriaTornozeleira),
            'obrigatorio' => true, 'erro' => $erro('bateria'), 'attrs' => 'min="0" max="100" step="1"'])
    </fieldset>

    <div class="fase-acoes">
        <a class="botao-secundario" href="{{ route('admin.tornozeleiras.visualizar', $tornozeleira->id) }}">Cancelar</a>
        <button class="victim-primary" type="submit">Salvar alterações</button>
    </div>
</form>
@endsection

@push('scripts')
    <script src="{{ asset('js/validacao.js') }}"></script>
@endpush
