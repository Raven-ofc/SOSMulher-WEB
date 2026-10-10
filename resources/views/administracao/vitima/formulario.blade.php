@extends('estruturas.administracao',['pageTitle'=>'Usuárias/Vítimas','active'=>'admin.vitimas.index'])
@section('admin-content')
<header class="admin-heading">
    <h1>Vítimas</h1>
</header>

<a class="admin-back" href="{{ route('admin.vitimas.index') }}">‹ voltar</a>

<form class="admin-panel victim-form-full" method="POST" action="{{ $vitima ? route('admin.vitimas.atualizar',$vitima->id) : route('admin.vitimas.salvar') }}" enctype="multipart/form-data">
    @csrf
    @if($vitima)
        @method('PATCH')
    @endif

    <h2>{{ $vitima ? 'Editar ficha da vítima' : 'Nova Ficha de Vítima' }}</h2>

    <div class="victim-form-grid">

        <fieldset>
            <legend class="somente-leitor">Informações pessoais</legend>

            <h3 class="victim-form-section-title">Dados Pessoais</h3>

            <label for="foto">Foto de Identificação (Opcional)</label>
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 18px;">
                <div style="width: 70px; height: 70px; border-radius: 50%; background: #eee; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 2px solid #bfaeff;">
                    <img id="preview-foto" src="{{ $vitima?->imagemVitima ? asset($vitima->imagemVitima) : '' }}" style="{{ $vitima?->imagemVitima ? '' : 'display:none;' }} width: 100%; height: 100%; object-fit: cover;">
                    <span id="preview-placeholder" style="{{ $vitima?->imagemVitima ? 'display:none;' : '' }} font-size: 24px; color: #aaa;">📷</span>
                </div>
                <input type="file" id="foto" name="foto" accept="image/*" onchange="mostrarPreviewFoto(this)" style="margin-bottom: 0;">
            </div>

            <label for="nome">Nome Completo</label>
            <input id="nome" name="nome" placeholder="Nome completo da vítima..." required maxlength="100" value="{{ old('nome', $vitima?->nomeVitima) }}">

            <div class="victim-form-row-2">
                <div>
                    <label for="cpf">CPF</label>
                    <input
                        id="cpf"
                        name="cpf"
                        required
                        inputmode="numeric"
                        placeholder="000.000.000-00"
                        maxlength="14"
                        value="{{ old('cpf', $vitima?->cpfVitima) }}"
                        @if($vitima) readonly class="campo-bloqueado" @endif
                    >
                    @if($vitima)
                        <p class="campo-ajuda">O CPF não pode ser alterado após o cadastro.</p>
                    @endif
                </div>
                <div>
                    <label for="data_nascimento">Data de nascimento</label>
                    <input
                        id="data_nascimento"
                        name="data_nascimento"
                        type="date"
                        required
                        max="{{ today()->toDateString() }}"
                        value="{{ old('data_nascimento', $vitima?->dataNascimentoVitima) }}"
                        @if($vitima) readonly class="campo-bloqueado" @endif
                    >
                    @if($vitima)
                        <p class="campo-ajuda">A data de nascimento não pode ser alterada após o cadastro.</p>
                    @endif
                </div>
            </div>

            <h3 class="victim-form-section-title mt">Filiação</h3>

            <label for="nome_mae">Nome da Mãe</label>
            <input id="nome_mae" name="nome_mae" placeholder="Nome da mãe..." maxlength="100" value="{{ old('nome_mae', $vitima?->nomeMaeVitima) }}">

            <label for="nome_pai">Nome do Pai (Opcional)</label>
            <input id="nome_pai" name="nome_pai" placeholder="Nome do pai..." maxlength="100" value="{{ old('nome_pai', $vitima?->nomePaiVitima) }}">
        </fieldset>

        <fieldset>
            <legend class="somente-leitor">Endereço e Contato</legend>

            <h3 class="victim-form-section-title">Endereço Residencial</h3>

            <div class="victim-form-row-cep">
                <div>
                    <label for="cep">CEP</label>
                    <input
                        id="cep"
                        name="cep"
                        placeholder="00000-000"
                        required
                        maxlength="9"
                        value="{{ old('cep', $vitima?->endereco?->cepVitima) }}"
                        onblur="pesquisarCep(this.value);"
                    >
                </div>
                <div>
                    <label for="cidade">Cidade / UF</label>
                    <div class="victim-form-cidade-uf">
                        <input
                            id="cidade"
                            name="cidade"
                            placeholder="Cidade"
                            required
                            readonly
                            class="victim-form-readonly"
                            value="{{ old('cidade', $vitima?->endereco?->cidadeVitima) }}"
                        >
                        <input
                            id="uf"
                            name="uf"
                            placeholder="UF"
                            required
                            readonly
                            class="victim-form-readonly"
                            value="{{ old('uf', $vitima?->endereco?->ufVitima) }}"
                        >
                    </div>
                </div>
            </div>

            <label for="rua">Logradouro (Rua/Avenida)</label>
            <input id="rua" name="rua" placeholder="Rua, Avenida, Viela..." required maxlength="150" value="{{ old('rua', $vitima?->endereco?->logradouroVitima) }}">

            <div class="victim-form-row-num">
                <div>
                    <label for="numero">Número</label>
                    <input id="numero" name="numero" placeholder="Nº" required maxlength="20" value="{{ old('numero', $vitima?->endereco?->numLogradouroVitima) }}">
                </div>
                <div>
                    <label for="bairro">Bairro</label>
                    <input id="bairro" name="bairro" placeholder="Bairro..." required maxlength="100" value="{{ old('bairro', $vitima?->endereco?->bairroVitima) }}">
                </div>
            </div>

            <label for="complemento">Complemento (Opcional)</label>
            <input id="complemento" name="complemento" placeholder="Apto, Bloco, Casa 2..." maxlength="100" value="{{ old('complemento', $vitima?->endereco?->complementoVitima) }}">

            <h3 class="victim-form-section-title mt">Contato</h3>

            <div class="victim-form-row-2">
                <div>
                    <label for="telefone">Telefone</label>
                    <input id="telefone" name="telefone" type="tel" placeholder="(00) 00000-0000" required value="{{ old('telefone', $vitima?->telefones()->first()?->numeroTelefoneVitima) }}">
                </div>
                <div>
                    <label for="email">E-mail</label>
                    <input id="email" name="email" placeholder="email@exemplo.com" type="email" required maxlength="100" value="{{ old('email', $vitima?->emailVitima) }}">
                </div>
            </div>
        </fieldset>
    </div>

    <div class="victim-form-actions-bar">
        <button class="victim-primary" type="submit">
            {{ $vitima ? 'Salvar Alterações' : 'Cadastrar Vítima' }}
        </button>
    </div>
</form>

<script>
    function mostrarPreviewFoto(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-foto').src = e.target.result;
                document.getElementById('preview-foto').style.display = 'block';
                document.getElementById('preview-placeholder').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<script src="{{ asset('js/cep.js') }}"></script>
@endsection