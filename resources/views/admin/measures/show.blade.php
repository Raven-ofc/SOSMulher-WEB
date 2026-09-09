@extends('layouts.admin', ['pageTitle' => 'Medidas Protetivas', 'active' => 'admin.measures'])
@section('admin-content')
<header class="admin-heading detail-heading"><h1>Medidas Protetivas</h1><p>medidaProtetiva/individual</p></header>
<a class="admin-back" href="{{ route('admin.measures') }}">‹ voltar</a>
<section class="admin-panel measure-detail">
    <div class="measure-summary">
        <section><h2>Medida —</h2>
            <dl class="victim-info"><div><dt>Raio do perímetro:</dt><dd>—</dd></div></dl>
            <h3>Tornozeleira</h3>
            <dl class="victim-info">
                <div><dt>Número Série:</dt><dd>—</dd></div>
                <div><dt>Status:</dt><dd>Não disponível</dd></div>
                <div><dt>Bateria:</dt><dd class="device-battery"><span aria-hidden="true"></span>—</dd></div>
                <div><dt>Local:</dt><dd>—</dd></div>
            </dl>
        </section>
        <div class="measure-people">
            <section><h3>Informações da Vítima</h3><dl class="victim-info"><div><dt>Nome:</dt><dd>—</dd></div><div><dt>CPF:</dt><dd>—</dd></div></dl></section>
            <section><h3>Informações do Agressor</h3><dl class="victim-info"><div><dt>Nome:</dt><dd>—</dd></div><div><dt>CPF:</dt><dd>—</dd></div></dl></section>
        </div>
        <span class="status-neutral measure-status">Não disponível</span>
    </div>
    <section class="measure-occurrences"><h2>Ocorrências:</h2><div class="measure-occurrences-empty">Ocorrências ainda não disponíveis.</div></section>
</section>
<div class="victim-detail-footer"><p id="measure-preview-note">Prévia visual. Nenhuma medida selecionada.</p><button type="button" disabled aria-describedby="measure-preview-note">download PDF <span aria-hidden="true">⇩</span></button></div>
@endsection
