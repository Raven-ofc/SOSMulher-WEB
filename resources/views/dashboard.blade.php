@extends('layouts.admin', ['pageTitle' => 'Dashboard', 'active' => 'dashboard'])
@section('admin-content')
<header class="admin-heading"><h1>Dashboard</h1><p class="admin-preview-note">Prévia das telas · dados operacionais ainda não disponíveis</p></header>
<div class="dashboard-grid">
    <x-admin-alerts />
    <x-admin-charts />
    <x-admin-stats />
</div>
@endsection
