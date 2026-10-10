@extends('estruturas.administracao', ['pageTitle' => 'Editar perfil', 'active' => 'admin.perfil.editar'])

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
                <img class="profile-avatar" id="avatar-preview" 
                     src="{{ route('admin.perfil.foto') }}" alt="Foto de perfil" style="object-fit:cover">
            @else
                <div class="profile-avatar" id="avatar-preview" role="img" aria-label="Perfil sem foto">
                    {{ mb_strtoupper(mb_substr(auth()->user()->nomeAutoridade, 0, 1)) }}
                </div>
            @endif

            <label for="photo" class="btn-upload">Escolher arquivo</label>
            <input id="photo" name="foto" type="file" accept="image/jpeg,image/png,image/webp" hidden>
            <small>Até 2 MB.</small>
        </div>

        <div class="profile-fields">
            <label for="name">Nome completo</label>
            <input id="name" name="nome" required maxlength="100" value="{{ old('nome', auth()->user()->nomeAutoridade) }}">

            <label for="cpf">CPF</label>
            <input id="cpf" name="cpf" type="text" inputmode="numeric" readonly value="{{ auth()->user()->cpfAutoridade }}">

            <label for="phone">Telefone</label>
            <input id="phone" name="telefone" type="tel" inputmode="numeric" maxlength="15" value="{{ old('telefone', optional($telefone)->numTelefoneAutoridade) }}">
        </div>

        <div class="profile-edit-actions">
            <div>
                <a class="admin-action secondary" href="{{ route('admin.perfil') }}">cancelar</a>
                <button class="admin-action" type="submit">salvar</button>
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

<style>
.btn-upload {
    display: inline-block;
    margin-top: 8px;
    padding: 6px 14px;
    background: #bfaeff;
    color: white;
    font-size: 0.875rem;
    border-radius: 20px;
    cursor: pointer;
    transition: background 0.2s;
    text-align: center;
}

.btn-upload:hover {
    background: #ae97f6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // === Preview da foto ===
    const photoInput = document.getElementById('photo');
    const preview = document.getElementById('avatar-preview');

    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(ev) {
            if (preview.tagName === 'IMG') {
                preview.src = ev.target.result;
            } else {
                const img = document.createElement('img');
                img.className = 'profile-avatar';
                img.style.objectFit = 'cover';
                img.src = ev.target.result;
                preview.replaceWith(img);
            }
        };
        reader.readAsDataURL(file);
    });

    // === Máscara do telefone ===
    const phone = document.getElementById('phone');

    function formatPhone(value) {
        const digits = value.replace(/\D/g, '').slice(0, 11);

        if (digits.length <= 2) return digits ? '(' + digits : '';
        if (digits.length <= 6) return '(' + digits.slice(0, 2) + ') ' + digits.slice(2);
        if (digits.length <= 10) {
            return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 6) + '-' + digits.slice(6);
        }
        return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 7) + '-' + digits.slice(7);
    }

    if (phone) {
        phone.value = formatPhone(phone.value);

        phone.addEventListener('input', function () {
            phone.value = formatPhone(phone.value);
        });
    }
});
</script>
@endsection