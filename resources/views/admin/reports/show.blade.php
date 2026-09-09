@extends('layouts.admin', ['pageTitle' => 'Relatórios Boletins de Ocorrências', 'active' => 'admin.reports'])
@section('admin-content')
<header class="admin-heading detail-heading"><h1>Relatórios Boletins de Ocorrências</h1><p>relatórios/individual</p></header>
<a class="admin-back" href="{{ route('admin.reports') }}">‹ voltar</a>
<article class="admin-panel occurrence-detail bulletin-detail">
    <section class="occurrence-summary">
        <div class="occurrence-heading"><h2>Ocorrência não selecionada</h2><span>Data/hora: —</span></div>
        <dl>
            <div><dt>Local:</dt><dd>—</dd></div>
            <div><dt>Bairro:</dt><dd>—</dd></div>
            <div><dt>Tornozeleira:</dt><dd>—</dd></div>
            <div><dt>Agressor:</dt><dd>—</dd></div>
            <div><dt>Vítima:</dt><dd>—</dd></div>
            <div class="occurrence-status"><dt>Status:</dt><dd><span class="status-neutral">Não disponível</span></dd></div>
        </dl>
    </section>
    <section class="report-section bulletin-content" aria-labelledby="bulletin-title">
        <h2 id="bulletin-title">Relatório</h2>
        <p class="bulletin-period">Início da operação: — <span>Final da operação: —</span></p>
        <p class="bulletin-empty">Prévia visual. Selecione um relatório para visualizar o atendimento registrado.</p>
    </section>
</article>
@endsection
