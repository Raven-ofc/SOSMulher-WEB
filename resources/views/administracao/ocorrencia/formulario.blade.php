@extends('estruturas.administracao',['pageTitle'=>'Registrar ocorrência','active'=>'admin.ocorrencias.criar'])
@section('admin-content')
<header class="admin-heading">
    <h1>Registrar ocorrência</h1>
</header>
<a class="admin-back" href="{{ route('admin.ocorrencias.index') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ route('admin.ocorrencias.salvar') }}">
    @csrf
    <fieldset>
        <legend class="somente-leitor">Pessoas e ocorrência</legend>
        <label for="vitima_id">Vítima</label>
        <select id="vitima_id" name="vitima_id" required>
            <option value="">Selecione</option>
            @foreach($vitimas as $v)
                <option value="{{ $v->id }}" @selected(old('vitima_id')==$v->id)>
                    {{ $v->nomeVitima }}
                    —
                    {{ $v->cpfVitima }}
                </option>
            @endforeach
        </select>
        <label for="agressor_id">Agressor</label>
        <select id="agressor_id" name="agressor_id" required>
            <option value="">Selecione</option>
            @foreach($agressores as $a)
                <option value="{{ $a->id }}" @selected(old('agressor_id')==$a->id)>
                    {{ $a->nomeAgressor }}
                    —
                    {{ $a->cpfAgressor }}
                </option>
            @endforeach
        </select>
        <label for="tipo">Tipo de ocorrência</label>
        <input id="tipo" name="tipo" required maxlength="100" value="{{ old('tipo') }}">
        <label for="gravidade">Gravidade</label>
        <select id="gravidade" name="gravidade" required>
            <option value="">Selecione</option>
            @foreach(['baixa','media','alta'] as $gravidade)
                <option @selected(old('gravidade')===$gravidade)>{{ $gravidade }}</option>
            @endforeach
        </select>
    </fieldset>
    <fieldset>
        <legend class="somente-leitor">Data e local</legend>
        <label for="data_hora">Data e hora</label>
        <input id="data_hora" name="data_hora" type="datetime-local" required value="{{ old('data_hora') }}">
        <label for="local">Local</label>
        <input id="local" name="local" required maxlength="255" value="{{ old('local') }}">
        <label for="bairro">Bairro</label>
        <input id="bairro" name="bairro" required maxlength="100" value="{{ old('bairro') }}">
        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" maxlength="10000">{{ old('descricao') }}</textarea>
        <div class="victim-form-actions">
            <button class="victim-primary">Registrar</button>
        </div>
    </fieldset>
</form>
@endsection