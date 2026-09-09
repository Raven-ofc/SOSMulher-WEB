@extends('estruturas.administracao',['pageTitle'=>'Editar perfil','active'=>'admin.profile'])
@section('admin-content')
<header class="admin-heading">
    <h1>Meu perfil</h1>
</header>
<div class="profile-grid">
    <form class="admin-panel profile-card profile-edit-card" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="profile-identity">
            @include('parciais.foto-perfil')
            <label for="photo">Alterar foto</label>
            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" style="max-width:130px">
            <small>Até 2 MB.</small>
        </div>
        <div class="profile-fields">
            <label for="name">Nome completo</label>
            <input id="name" name="name" required maxlength="100" value="{{ old('name',auth()->user()->name) }}">
            <label for="cpf">CPF</label>
            <input id="cpf" name="cpf" inputmode="numeric" maxlength="14" value="{{ old('cpf',auth()->user()->cpf) }}">
            <label for="phone">Telefone</label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone',auth()->user()->phone) }}">
        </div>
        <div class="profile-edit-actions">
            <div>
                <a class="admin-action secondary" href="{{ route('admin.profile') }}">cancelar</a>
                <button class="admin-action">salvar</button>
            </div>
        </div>
    </form>
    @include('parciais.resumo-perfil')
</div>
@endsection
