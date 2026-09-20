@extends('estruturas.administracao',['pageTitle'=>'Editar perfil','active'=>'admin.perfil.editar'])
@section('admin-content')
<header class="admin-heading">
    <h1>Meu perfil</h1>
</header>
<div class="profile-grid">
    <form class="admin-panel profile-card profile-edit-card" method="POST" action="{{ route('admin.perfil.atualizar') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="profile-identity">
            @if(auth()->user()->imagemAutoridade)
                <img class="profile-avatar" src="{{ route('admin.profile.photo') }}" alt="Foto de perfil" style="object-fit:cover">
            @else
                <div class="profile-avatar" role="img" aria-label="Perfil sem foto">
                    {{ mb_strtoupper(mb_substr(auth()->user()->nomeAutoridade,0,1)) }}
                </div>
            @endif
            <label for="photo">Alterar foto</label>
            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" style="max-width:130px">
            <small>Até 2 MB.</small>
        </div>
        <div class="profile-fields">
            <label for="name">Nome completo</label>
            <input id="name" name="name" required maxlength="100" value="{{ old('name',auth()->user()->nomeAutoridade) }}">
            <label for="cpf">CPF</label>
            <input id="cpf" name="cpf" inputmode="numeric" maxlength="14" value="{{ old('cpf',auth()->user()->cpfAutoridade) }}">
            <label for="phone">Telefone</label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone',$telefone->numTelefoneAutoridade ?? null) }}">
        </div>
        <div class="profile-edit-actions">
            <div>
                <a class="admin-action secondary" href="{{ route('admin.perfil') }}">cancelar</a>
                <button class="admin-action">salvar</button>
            </div>
        </div>
    </form>
    <aside class="profile-stats" aria-label="Resumo pessoal">
        <div class="admin-panel profile-stat">
            <span class="profile-stat-icon">@include('parciais.icone', ['name' => 'headset'])</span>
            <div>
                <strong>{{ $atendimentos }}</strong>
                <p>Ocorrências atendidas</p>
            </div>
        </div>
    </aside>
</div>
@endsection
