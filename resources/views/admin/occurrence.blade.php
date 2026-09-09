@extends('layouts.admin', ['pageTitle' => 'Ocorrências', 'active' => 'admin.occurrences'])
@section('admin-content')
<header class="admin-heading detail-heading"><h1>Ocorrências</h1><p>ocorrências/individual</p></header>
<a class="admin-back" href="{{ route('admin.occurrences') }}">‹ voltar</a>
<section class="admin-panel occurrence-detail">
    <x-occurrence-summary />
    <div class="detail-actions"><a class="admin-action" href="{{ route('admin.occurrences.report') }}">Finalizar atendimento</a></div>
    <p class="admin-detail-note">Prévia visual. Nenhuma ocorrência selecionada.</p>
</section>
@endsection
