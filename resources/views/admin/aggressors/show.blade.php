@extends('layouts.admin', ['pageTitle' => 'Agressores', 'active' => 'admin.aggressors'])
@section('admin-content')
<header class="admin-heading detail-heading"><h1>Agressores</h1><p>Agressores/individual</p></header>
<a class="admin-back" href="{{ route('admin.aggressors') }}">‹ voltar</a>
<section class="admin-panel victim-detail aggressor-detail">
    <div class="victim-profile">
        <div class="victim-profile-heading"><h2>Agressor não selecionado</h2><span class="status-neutral">Não disponível</span></div>
        <dl class="victim-info">
            <div><dt>CPF:</dt><dd>—</dd></div>
            <div><dt>Nascimento:</dt><dd>—</dd></div>
            <div><dt>Telefone:</dt><dd>—</dd></div>
            <div><dt>Residência:</dt><dd>—</dd></div>
            <div><dt>Tornozeleira:</dt><dd>—</dd></div>
            <div><dt>Medida Protetiva:</dt><dd>—</dd></div>
            <div><dt>Vítima:</dt><dd>—</dd></div>
        </dl>
        <div class="victim-profile-actions"><button class="admin-action secondary" type="button" disabled aria-describedby="aggressor-preview-note">editar info</button><button class="admin-action" type="button" disabled aria-describedby="aggressor-preview-note">Inativar</button></div>
    </div>
    <div class="victim-related"><section><h2>Ocorrências:</h2><div class="victim-related-empty">Ocorrências ainda não disponíveis.</div></section></div>
</section>
<div class="victim-detail-footer"><p id="aggressor-preview-note">Prévia visual. Selecione um agressor para editar ou exportar seus dados.</p><button type="button" disabled aria-describedby="aggressor-preview-note">download PDF <span aria-hidden="true">⇩</span></button></div>
@endsection
