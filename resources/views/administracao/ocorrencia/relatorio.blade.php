@extends('estruturas.administracao',['pageTitle'=>'Finalizar atendimento','active'=>'admin.ocorrencias.index'])
@section('admin-content')
<header class="admin-heading">
    <h1>Finalizar atendimento</h1>
</header>
<a class="admin-back" href="{{ route('admin.ocorrencias.visualizar',$ocorrencia->id) }}">‹ voltar</a>
<section class="admin-panel">
    <h2>{{ $ocorrencia->tipoOcorrencia }}</h2>
    <form class="report-section" method="POST" action="{{ route('admin.ocorrencias.finalizar',$ocorrencia->id) }}">
        @csrf
        <div class="report-dates">
            <label>
                Início da operação
                <input type="datetime-local" name="inicio" value="{{ old('inicio') }}" required>
            </label>
            <label>
                Final da operação
                <input type="datetime-local" name="fim" value="{{ old('fim') }}" required>
            </label>
        </div>
        <label for="relato">Relatório do atendimento</label>
        <textarea id="relato" name="relato" required minlength="20" maxlength="30000">{{ old('relato') }}</textarea>
        <div class="report-actions">
            <a class="admin-action secondary" href="{{ route('admin.ocorrencias.visualizar',$ocorrencia->id) }}">cancelar</a>
            <button class="admin-action" type="submit">Finalizar atendimento</button>
        </div>
    </form>
</section>
@endsection