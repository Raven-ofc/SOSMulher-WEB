@extends('layouts.admin', ['pageTitle' => 'Solicitações de Locais seguros', 'active' => 'admin.victims'])
@section('admin-content')
<header class="admin-heading detail-heading"><h1>Solicitações de Locais seguros</h1><p>usuárias/solicitaçõeslocaisseguros</p></header>
<a class="admin-back" href="{{ route('admin.victims') }}">‹ voltar</a>
<div class="safe-request-grid">
    <section class="admin-panel safe-request-card" aria-label="Prévia de uma solicitação">
        <h2>Solicitação não selecionada</h2>
        <dl class="victim-info"><div><dt>CPF:</dt><dd>—</dd></div><div><dt>Local:</dt><dd>—</dd></div><div><dt>Endereço:</dt><dd>—</dd></div></dl>
        <p id="safe-request-note">Solicitações ainda não disponíveis.</p>
        <div class="safe-request-actions"><button class="admin-action secondary" type="button" disabled aria-describedby="safe-request-note">Reprovar</button><button class="admin-action" type="button" disabled aria-describedby="safe-request-note">Aprovar</button></div>
    </section>
</div>
@endsection
