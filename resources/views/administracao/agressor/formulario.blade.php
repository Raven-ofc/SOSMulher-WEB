@extends('estruturas.administracao',['pageTitle'=>'Agressores','active'=>'admin.agressores.index'])
@section('admin-content')
<header class="admin-heading">
    <h1>Agressores</h1>
</header>
<a class="admin-back" href="{{ route('admin.agressores.index') }}">‹ voltar</a>
<form class="admin-panel victim-form" method="POST" action="{{ $agressor ? route('admin.agressores.atualizar',$agressor->id) : route('admin.agressores.salvar') }}">
    @csrf
    @if($agressor)
    @method('PATCH')
    @endif
    <fieldset>
        <legend class="somente-leitor">Informações pessoais</legend>
        <h2>{{ $agressor ? 'Editar cadastro' : 'Informações pessoais' }}</h2>
        <label for="nome">Nome</label>
        <input id="nome" name="nome" required maxlength="100" placeholder="nome..." value="{{ old('nome',$agressor?->nomeAgressor) }}">
        <label for="cpf">CPF</label>
        <input id="cpf" name="cpf" required inputmode="numeric" placeholder="cpf..." maxlength="14" value="{{ old('cpf',$agressor?->cpfAgressor) }}">
        <label for="data_nascimento">Data de nascimento</label>
        <input id="data_nascimento" name="data_nascimento" type="date" required max="{{ today()->toDateString() }}" value="{{ old('data_nascimento',$agressor?->dataNascimentoAgressor) }}">

        <label for="tornozeleira">Tornozeleira</label>
        <select id="tornozeleira" name="idTornozeleira" required>
            <option value="">Selecione uma tornozeleira</option>

            @foreach($tornozeleiras as $tornozeleira)
            <option
                value="{{ $tornozeleira->id }}"
                {{ old('idTornozeleira', $agressor?->idTornozeleira) == $tornozeleira->id ? 'selected' : '' }}>
                {{ $tornozeleira->numeroSerieTornozeleira }}
            </option>
            @endforeach
        </select>
    </fieldset>
    <fieldset>
        <h2>{{ $agressor ? 'Editar cadastro' : 'Endereço' }}</h2>
        <label for="logradouro">Rua</label>
        <input id="logradouro" name="logradouro" placeholder="rua..." value="{{ old('logradouro',$agressor?->logradouroAgressor) }}">
        <label for="numLogradouro">Número da Rua</label>
        <input id="numLogradouro" name="numLogradouro" placeholder="Numero da rua..." value="{{ old('numeroLogradouro',$agressor?->numLogradouroAgressor) }}">
        <label for="bairroAgressor">Bairro</label>
        <input id="bairroAgressor" name="bairroAgressor" placeholder="Numero da rua..." value="{{ old('bairro',$agressor?->bairroAgressor) }}">
        <label for="cidadeAgressor">Cidade</label>
        <input id="cidadeAgressor" name="cidadeAgressor" placeholder="Numero da rua..." value="{{ old('cidade',$agressor?->cidadeAgressor) }}">
        <label for="ufAgressor">UF</label>
        <select id="ufAgressor" name="ufAgressor" required>
            <option value="">Selecione o estado</option>

            <option value="AC" {{ old('ufAgressor', $agressor?->ufAgressor) == 'AC' ? 'selected' : '' }}>Acre (AC)</option>
            <option value="AL" {{ old('ufAgressor', $agressor?->ufAgressor) == 'AL' ? 'selected' : '' }}>Alagoas (AL)</option>
            <option value="AP" {{ old('ufAgressor', $agressor?->ufAgressor) == 'AP' ? 'selected' : '' }}>Amapá (AP)</option>
            <option value="AM" {{ old('ufAgressor', $agressor?->ufAgressor) == 'AM' ? 'selected' : '' }}>Amazonas (AM)</option>
            <option value="BA" {{ old('ufAgressor', $agressor?->ufAgressor) == 'BA' ? 'selected' : '' }}>Bahia (BA)</option>
            <option value="CE" {{ old('ufAgressor', $agressor?->ufAgressor) == 'CE' ? 'selected' : '' }}>Ceará (CE)</option>
            <option value="DF" {{ old('ufAgressor', $agressor?->ufAgressor) == 'DF' ? 'selected' : '' }}>Distrito Federal (DF)</option>
            <option value="ES" {{ old('ufAgressor', $agressor?->ufAgressor) == 'ES' ? 'selected' : '' }}>Espírito Santo (ES)</option>
            <option value="GO" {{ old('ufAgressor', $agressor?->ufAgressor) == 'GO' ? 'selected' : '' }}>Goiás (GO)</option>
            <option value="MA" {{ old('ufAgressor', $agressor?->ufAgressor) == 'MA' ? 'selected' : '' }}>Maranhão (MA)</option>
            <option value="MT" {{ old('ufAgressor', $agressor?->ufAgressor) == 'MT' ? 'selected' : '' }}>Mato Grosso (MT)</option>
            <option value="MS" {{ old('ufAgressor', $agressor?->ufAgressor) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul (MS)</option>
            <option value="MG" {{ old('ufAgressor', $agressor?->ufAgressor) == 'MG' ? 'selected' : '' }}>Minas Gerais (MG)</option>
            <option value="PA" {{ old('ufAgressor', $agressor?->ufAgressor) == 'PA' ? 'selected' : '' }}>Pará (PA)</option>
            <option value="PB" {{ old('ufAgressor', $agressor?->ufAgressor) == 'PB' ? 'selected' : '' }}>Paraíba (PB)</option>
            <option value="PR" {{ old('ufAgressor', $agressor?->ufAgressor) == 'PR' ? 'selected' : '' }}>Paraná (PR)</option>
            <option value="PE" {{ old('ufAgressor', $agressor?->ufAgressor) == 'PE' ? 'selected' : '' }}>Pernambuco (PE)</option>
            <option value="PI" {{ old('ufAgressor', $agressor?->ufAgressor) == 'PI' ? 'selected' : '' }}>Piauí (PI)</option>
            <option value="RJ" {{ old('ufAgressor', $agressor?->ufAgressor) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro (RJ)</option>
            <option value="RN" {{ old('ufAgressor', $agressor?->ufAgressor) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte (RN)</option>
            <option value="RS" {{ old('ufAgressor', $agressor?->ufAgressor) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul (RS)</option>
            <option value="RO" {{ old('ufAgressor', $agressor?->ufAgressor) == 'RO' ? 'selected' : '' }}>Rondônia (RO)</option>
            <option value="RR" {{ old('ufAgressor', $agressor?->ufAgressor) == 'RR' ? 'selected' : '' }}>Roraima (RR)</option>
            <option value="SC" {{ old('ufAgressor', $agressor?->ufAgressor) == 'SC' ? 'selected' : '' }}>Santa Catarina (SC)</option>
            <option value="SP" {{ old('ufAgressor', $agressor?->ufAgressor) == 'SP' ? 'selected' : '' }}>São Paulo (SP)</option>
            <option value="SE" {{ old('ufAgressor', $agressor?->ufAgressor) == 'SE' ? 'selected' : '' }}>Sergipe (SE)</option>
            <option value="TO" {{ old('ufAgressor', $agressor?->ufAgressor) == 'TO' ? 'selected' : '' }}>Tocantins (TO)</option>
        </select>
        <label for="complementoAgressor">Complemento</label>
        <input id="complementoAgressor" name="complementoAgressor" placeholder="Numero da rua..." value="{{ old('complemento',$agressor?->complementoAgressor) }}">
        <div class="victim-form-actions">
            <button class="victim-primary" type="submit">{{ $agressor ? 'Salvar' : 'Cadastrar' }}</button>
        </div>
    </fieldset>
</form>
@endsection

'logradouroAgressor' => 'Rua Feliciano de Mendonça',
'bairroAgressor' => 'Guaianases',
'cidadeAgressor' => 'São Paulo',
'ufAgressor' => 'SP',
'complementoAgressor' => 'Casa 2',