@extends('estruturas.administracao',['pageTitle'=>'Usuárias/Vítimas','active'=>'admin.vitimas.index'])
@section('admin-content')
<header class="admin-heading">
    <h1>Usuárias/Vítimas</h1>
</header>
<a class="admin-back" href="{{ route('admin.vitimas.index') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ $vitima ? route('admin.vitimas.atualizar',$vitima->id) : route('admin.vitimas.salvar') }}">
    @csrf
    @if($vitima)
    @method('PATCH')
    @endif
    <fieldset>
        <legend class="somente-leitor">Informações pessoais</legend>
        <h2>{{ $vitima ? 'Editar cadastro' : 'Informações pessoais' }}</h2>
        <label for="nome">Nome</label>
        <input id="nome" name="nome" placeholder="Nome..." required maxlength="100" value="{{ old('nome',$vitima?->nomeVitima) }}">
        <label for="cpf">CPF</label>
        <input id="cpf" name="cpf" required inputmode="numeric" placeholder="000.000.000-00" maxlength="14" value="{{ old('cpf',$vitima?->cpfVitima) }}">
        <label for="data_nascimento">Data de nascimento</label>
        <input id="data_nascimento" name="data_nascimento" type="date" required max="{{ today()->toDateString() }}" value="{{ old('data_nascimento',$vitima?->dataNascimentoVitima) }}">
        <h2>Endereço da vítima</h2>

        <label for="cep">CEP</label>
        <input
            id="cep"
            name="cep"
            type="text"
            required
            maxlength="9"
            placeholder="00000-000"
            value="{{ old('cep', $vitima?->endereco?->cepVitima) }}">

        <label for="logradouro">Rua</label>
        <input
            id="logradouro"
            name="logradouro"
            type="text"
            placeholder="Rua..."
            required
            maxlength="255"
            value="{{ old('logradouro', $vitima?->endereco?->logradouroVitima) }}">

        <label for="numero">Número</label>
        <input
            id="numero"
            name="numero"
            type="text"
            placeholder="Numero..."
            required
            maxlength="100"
            value="{{ old('numero', $vitima?->endereco?->numLogradouroVitima) }}">

        <label for="complemento">Complemento</label>
        <input
            id="complemento"
            name="complemento"
            type="text"
            placeholder="complemento..."
            maxlength="100"
            value="{{ old('complemento', $vitima?->endereco?->complementoVitima) }}">

    </fieldset>
    <fieldset>
        <legend class="somente-leitor">Contato</legend>
        <h2>Informações de contato</h2>
        <label for="email">E-mail</label>
        <input id="email" name="email" placeholder="123@abc.com..." type="email" required maxlength="100" value="{{ old('email',$vitima?->emailVitima) }}">
        <label for="telefone">Telefone</label>
        <input id="telefone" name="telefone" type="tel" placeholder="(00) 00000-0000" required value="{{ old('telefone',$vitima?->telefones()->first()?->numeroTelefoneVitima) }}">
        <legend class="somente-leitor">Endereço</legend>

        <h2>Endereço da vítima</h2>


        <label for="bairro">Bairro</label>
        <input
            id="bairro"
            name="bairro"
            type="text"
            required
            placeholder="Bairro..."
            maxlength="100"
            value="{{ old('bairro', $vitima?->endereco?->bairroVitima) }}">

        <label for="cidade">Cidade</label>
        <input
            id="cidade"
            name="cidade"
            type="text"
            placeholder="Cidade..."
            required
            maxlength="100"
            value="{{ old('cidade', $vitima?->endereco?->cidadeVitima) }}">

        <label for="uf">UF</label>

        <select id="uf" name="uf" required>
            <option value="">Selecione o estado</option>

            <option value="AC" {{ old('uf', $vitima?->endereco?->ufVitima) == 'AC' ? 'selected' : '' }}>Acre (AC)</option>
            <option value="AL" {{ old('uf', $vitima?->endereco?->ufVitima) == 'AL' ? 'selected' : '' }}>Alagoas (AL)</option>
            <option value="AP" {{ old('uf', $vitima?->endereco?->ufVitima) == 'AP' ? 'selected' : '' }}>Amapá (AP)</option>
            <option value="AM" {{ old('uf', $vitima?->endereco?->ufVitima) == 'AM' ? 'selected' : '' }}>Amazonas (AM)</option>
            <option value="BA" {{ old('uf', $vitima?->endereco?->ufVitima) == 'BA' ? 'selected' : '' }}>Bahia (BA)</option>
            <option value="CE" {{ old('uf', $vitima?->endereco?->ufVitima) == 'CE' ? 'selected' : '' }}>Ceará (CE)</option>
            <option value="DF" {{ old('uf', $vitima?->endereco?->ufVitima) == 'DF' ? 'selected' : '' }}>Distrito Federal (DF)</option>
            <option value="ES" {{ old('uf', $vitima?->endereco?->ufVitima) == 'ES' ? 'selected' : '' }}>Espírito Santo (ES)</option>
            <option value="GO" {{ old('uf', $vitima?->endereco?->ufVitima) == 'GO' ? 'selected' : '' }}>Goiás (GO)</option>
            <option value="MA" {{ old('uf', $vitima?->endereco?->ufVitima) == 'MA' ? 'selected' : '' }}>Maranhão (MA)</option>
            <option value="MT" {{ old('uf', $vitima?->endereco?->ufVitima) == 'MT' ? 'selected' : '' }}>Mato Grosso (MT)</option>
            <option value="MS" {{ old('uf', $vitima?->endereco?->ufVitima) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul (MS)</option>
            <option value="MG" {{ old('uf', $vitima?->endereco?->ufVitima) == 'MG' ? 'selected' : '' }}>Minas Gerais (MG)</option>
            <option value="PA" {{ old('uf', $vitima?->endereco?->ufVitima) == 'PA' ? 'selected' : '' }}>Pará (PA)</option>
            <option value="PB" {{ old('uf', $vitima?->endereco?->ufVitima) == 'PB' ? 'selected' : '' }}>Paraíba (PB)</option>
            <option value="PR" {{ old('uf', $vitima?->endereco?->ufVitima) == 'PR' ? 'selected' : '' }}>Paraná (PR)</option>
            <option value="PE" {{ old('uf', $vitima?->endereco?->ufVitima) == 'PE' ? 'selected' : '' }}>Pernambuco (PE)</option>
            <option value="PI" {{ old('uf', $vitima?->endereco?->ufVitima) == 'PI' ? 'selected' : '' }}>Piauí (PI)</option>
            <option value="RJ" {{ old('uf', $vitima?->endereco?->ufVitima) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro (RJ)</option>
            <option value="RN" {{ old('uf', $vitima?->endereco?->ufVitima) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte (RN)</option>
            <option value="RS" {{ old('uf', $vitima?->endereco?->ufVitima) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul (RS)</option>
            <option value="RO" {{ old('uf', $vitima?->endereco?->ufVitima) == 'RO' ? 'selected' : '' }}>Rondônia (RO)</option>
            <option value="RR" {{ old('uf', $vitima?->endereco?->ufVitima) == 'RR' ? 'selected' : '' }}>Roraima (RR)</option>
            <option value="SC" {{ old('uf', $vitima?->endereco?->ufVitima) == 'SC' ? 'selected' : '' }}>Santa Catarina (SC)</option>
            <option value="SP" {{ old('uf', $vitima?->endereco?->ufVitima) == 'SP' ? 'selected' : '' }}>São Paulo (SP)</option>
            <option value="SE" {{ old('uf', $vitima?->endereco?->ufVitima) == 'SE' ? 'selected' : '' }}>Sergipe (SE)</option>
            <option value="TO" {{ old('uf', $vitima?->endereco?->ufVitima) == 'TO' ? 'selected' : '' }}>Tocantins (TO)</option>

        </select>

        <label for="latitude">Latitude</label>
        <input
            id="latitude"
            name="latitude"
            type="number"
            step="any"
            placeholder="00.000000"
            required
            value="{{ old('latitude', $vitima?->endereco?->latitudeVitima) }}">

        <label for="longitude">Longitude</label>
        <input
            id="longitude"
            name="longitude"
            type="number"
            step="any"
            placeholder="00.000000"
            required
            value="{{ old('longitude', $vitima?->endereco?->longitudeVitima) }}">
        <div class="victim-form-actions">
            <button class="victim-primary" type="submit">{{ $vitima ? 'Salvar' : 'Cadastrar' }}</button>
        </div>
    </fieldset>
</form>
@endsection