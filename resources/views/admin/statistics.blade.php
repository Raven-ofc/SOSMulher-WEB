@extends('layouts.admin', ['pageTitle' => 'Estatísticas', 'active' => 'admin.statistics'])
@section('admin-content')
<header class="admin-heading"><h1>Estatísticas</h1><p class="admin-preview-note">Prévia das telas · dados operacionais ainda não disponíveis</p></header>
<div class="statistics-charts"><x-admin-charts /></div>
@endsection