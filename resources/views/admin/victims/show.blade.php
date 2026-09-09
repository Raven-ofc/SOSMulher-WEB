@extends('layouts.admin', ['pageTitle' => 'Usuárias/Vítimas', 'active' => 'admin.victims'])
@section('admin-content')
<header class="admin-heading detail-heading"><h1>Usuárias/Vítimas</h1><p>usuárias/individual</p></header>
<a class="admin-back" href="{{ route('admin.victims') }}">‹ voltar</a>
<section class="admin-panel victim-detail">
    <div class="victim-profile">
        <div class="victim-profile-heading"><h2>Vítima não selecionada</h2><span class="status-neutral">Não disponível</span></div>
        <dl class="victim-info">
            <div><dt>CPF:</dt><dd>—</dd></div>
            <div><dt>Nascimento:</dt><dd>—</dd></div>
            <div><dt>Telefone:</dt><dd>—</dd></div>
            <div><dt>Email:</dt><dd>—</dd></div>
            <div><dt>Medida Protetiva:</dt><dd>—</dd></div>
            <div><dt>Agressor:</dt><dd>—</dd></div>
        </dl>
        <div class="victim-profile-actions"><button class="admin-action secondary" type="button" disabled aria-describedby="victim-preview-note">editar info</button><button class="admin-action" type="button" disabled aria-describedby="victim-preview-note">Inativar</button></div>
    </div>
    <div class="victim-related">
        <section><h2>Ocorrências:</h2><div class="victim-related-empty">Ocorrências ainda não disponíveis.</div></section>
        <section><h2>Locais de segurança</h2><div class="victim-related-empty">Nenhum local disponível para exibição.</div><div class="victim-place-preview"><span>Local: —<br>Endereço: —</span><button type="button" disabled aria-describedby="victim-preview-note">excluir</button></div></section>
    </div>
</section>
<div class="victim-detail-footer"><p id="victim-preview-note">Prévia visual. Selecione uma vítima para editar ou exportar seus dados.</p><button type="button" disabled aria-describedby="victim-preview-note">download PDF <span aria-hidden="true">⇩</span></button></div>
@endsection
