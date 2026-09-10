@extends('estruturas.administracao', ['pageTitle' => 'Meu perfil', 'active' => 'admin.profile'])
@section('admin-content')
<header class="admin-heading">
    <h1>Meu perfil</h1>
</header>
<div class="profile-grid">
    <section class="admin-panel profile-card">
        <div class="profile-identity">
            @if(auth()->user()->photo_path)
                <img class="profile-avatar" src="{{ route('admin.profile.photo') }}" alt="Foto de perfil" style="object-fit:cover">
            @else
                <div class="profile-avatar" role="img" aria-label="Perfil sem foto">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name,0,1)) }}
                </div>
            @endif
            <a class="profile-edit-link" href="{{ route('admin.profile.edit') }}">editar perfil</a>
        </div>
        <div class="profile-information">
            <h2>{{ auth()->user()->name }}</h2>
            <dl>
                <div>
                    <dt>CPF:</dt>
                    <dd>{{ auth()->user()->cpf ?: '—' }}</dd>
                </div>
                <div>
                    <dt>Matrícula:</dt>
                    <dd>—</dd>
                </div>
                <div>
                    <dt>Telefone:</dt>
                    <dd>{{ auth()->user()->phone ?: '—' }}</dd>
                </div>
                <div>
                    <dt>E-mail:</dt>
                    <dd>{{ auth()->user()->email }}</dd>
                </div>
            </dl>
            <h3>Informações institucionais</h3>
            <dl>
                <div>
                    <dt>Instituição:</dt>
                    <dd>—</dd>
                </div>
                <div>
                    <dt>Cargo:</dt>
                    <dd>—</dd>
                </div>
                <div>
                    <dt>Status:</dt>
                    <dd>Não disponível</dd>
                </div>
            </dl>
        </div>
    </section>
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