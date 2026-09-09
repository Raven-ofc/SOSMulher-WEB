@extends('layouts.admin', ['pageTitle' => 'Relatório de atendimento', 'active' => 'admin.occurrences'])
@section('admin-content')
<header class="admin-heading detail-heading"><h1>Ocorrências</h1><p>ocorrências/individual</p></header>
<a class="admin-back" href="{{ route('admin.occurrences.preview') }}">‹ voltar</a>
<section class="admin-panel occurrence-detail">
    <x-occurrence-summary />
    <section class="report-section" aria-labelledby="report-title">
        <h2 id="report-title">Relatório</h2>
        <p class="report-intro">Para finalizar a ocorrência, faça o relatório sobre o serviço de proteção prestado à vítima e a abordagem ao acusado.</p>
        <p class="report-help">Ao realizar a ocorrência, você se compromete com um relatório transparente sobre o ocorrido, sem omissões.</p>
        <form onsubmit="event.preventDefault()">
            <div class="report-dates">
                <fieldset><legend>Início da operação</legend><div><label class="sr-only" for="start-date">Data de início</label><input id="start-date" type="date"><label class="sr-only" for="start-time">Hora de início</label><input id="start-time" type="time"></div></fieldset>
                <fieldset><legend>Final da operação</legend><div><label class="sr-only" for="end-date">Data de término</label><input id="end-date" type="date"><label class="sr-only" for="end-time">Hora de término</label><input id="end-time" type="time"></div></fieldset>
            </div>
            <label class="sr-only" for="report-body">Relatório do atendimento</label><textarea id="report-body" placeholder="Digite o relatório..." aria-describedby="report-unavailable"></textarea>
            <p class="report-help" id="report-unavailable">Prévia visual. O envio estará disponível quando houver uma ocorrência selecionada.</p>
            <div class="report-actions"><a class="admin-action secondary" href="{{ route('admin.occurrences.preview') }}">cancelar</a><button class="admin-action" type="button" disabled aria-describedby="report-unavailable">Finalizar atendimento</button></div>
        </form>
    </section>
</section>
@endsection
