@extends('layouts.admin',['pageTitle'=>'Finalizar atendimento','active'=>'admin.occurrences'])
@section('admin-content')
<header class="admin-heading"><h1>Finalizar atendimento</h1></header><a class="admin-back" href="{{ route('admin.occurrences.show',$case->id) }}">‹ voltar</a>
<section class="admin-panel"><h2>{{ $case->tipoOcorrencia }}</h2><form class="report-section" method="POST" action="{{ route('admin.occurrences.complete',$case->id) }}">@csrf
<div class="report-dates"><label>Início da operação<input type="datetime-local" name="start" value="{{ old('start') }}" required></label><label>Final da operação<input type="datetime-local" name="end" value="{{ old('end') }}" required></label></div>
<label for="report">Relatório do atendimento</label><textarea id="report" name="report" required minlength="20" maxlength="30000">{{ old('report') }}</textarea>
<div class="report-actions"><a class="admin-action secondary" href="{{ route('admin.occurrences.show',$case->id) }}">cancelar</a><button class="admin-action" type="submit">Finalizar atendimento</button></div></form></section>
@endsection
