{{--
    Campos idênticos para VÍTIMA e AGRESSOR (dados pessoais + endereço com busca de CEP + contato).
    Parâmetros:
      prefixo   'vitima' | 'agressor' (nomes viram vitima[nome]) ou null (formulários de edição: nome puro)
      valores   array com os valores atuais (edição) — chaves: nome, cpf, data_nascimento, nome_mae, nome_pai, telefone,
                email, cep, rua, numero, bairro, cidade, uf, complemento, lat, lng, foto_url
      edicao    true => CPF e data de nascimento ficam bloqueados
      emailObrigatorio  true para vítima (é o login dela)
      ufs       lista de UFs
--}}
@php
    $prefixo = $prefixo ?? null;
    $valores = $valores ?? [];
    $edicao = $edicao ?? false;
    $emailObrigatorio = $emailObrigatorio ?? false;
    $n = fn ($c) => $prefixo ? "{$prefixo}[{$c}]" : $c;
    $k = fn ($c) => $prefixo ? "{$prefixo}.{$c}" : $c;
    $v = fn ($c) => old($k($c), $valores[$c] ?? null);
    $i = fn ($c) => ($prefixo ? $prefixo . '-' : '') . str_replace('_', '-', $c);
    $e = fn ($c) => $errors->first($k($c));
    $bloqueado = $edicao ? 'readonly tabindex="-1"' : '';
@endphp

<div class="grade-pessoa">
    <fieldset class="bloco">
        <legend class="bloco-titulo">Dados pessoais</legend>

        @include('parciais.foto-upload', ['id' => $i('foto'), 'name' => $n('foto'), 'atual' => $valores['foto_url'] ?? null, 'erro' => $e('foto')])

        @include('parciais.campo', ['id' => $i('nome'), 'name' => $n('nome'), 'label' => 'Nome completo', 'value' => $v('nome'), 'obrigatorio' => true,
            'erro' => $e('nome'), 'attrs' => 'maxlength="100" data-tipo="nomecompleto" placeholder="Nome e sobrenome" autocomplete="off"'])

        <div class="linha-2">
            @include('parciais.campo', ['id' => $i('cpf'), 'name' => $n('cpf'), 'label' => 'CPF', 'value' => $v('cpf'), 'obrigatorio' => true, 'erro' => $e('cpf'),
                'ajuda' => $edicao ? 'O CPF não pode ser alterado após o cadastro.' : null,
                'attrs' => 'inputmode="numeric" maxlength="14" data-tipo="cpf" data-mascara="cpf" placeholder="000.000.000-00" autocomplete="off" ' . $bloqueado])
            @include('parciais.campo', ['id' => $i('data_nascimento'), 'name' => $n('data_nascimento'), 'label' => 'Data de nascimento', 'type' => 'date',
                'value' => $v('data_nascimento'), 'obrigatorio' => true, 'erro' => $e('data_nascimento'),
                'ajuda' => $edicao ? 'A data de nascimento não pode ser alterada.' : null,
                'attrs' => 'data-tipo="dataPassada" max="' . today()->toDateString() . '" ' . $bloqueado])
        </div>

        <h3 class="bloco-subtitulo">Filiação</h3>
        @include('parciais.campo', ['id' => $i('nome_mae'), 'name' => $n('nome_mae'), 'label' => 'Nome da mãe', 'value' => $v('nome_mae'), 'obrigatorio' => true,
            'erro' => $e('nome_mae'), 'attrs' => 'maxlength="100" autocomplete="off"'])
        @include('parciais.campo', ['id' => $i('nome_pai'), 'name' => $n('nome_pai'), 'label' => 'Nome do pai (opcional)', 'value' => $v('nome_pai'),
            'erro' => $e('nome_pai'), 'attrs' => 'maxlength="100" autocomplete="off"'])
    </fieldset>

    <fieldset class="bloco" data-cep-group>
        <legend class="bloco-titulo">Endereço residencial</legend>

        <div class="linha-cep">
            @include('parciais.campo', ['id' => $i('cep'), 'name' => $n('cep'), 'label' => 'CEP', 'value' => $v('cep'), 'obrigatorio' => true, 'erro' => $e('cep'),
                'ajuda' => 'O endereço é preenchido automaticamente.',
                'attrs' => 'data-f="cep" data-tipo="cep" data-mascara="cep" inputmode="numeric" maxlength="9" placeholder="00000-000" autocomplete="off"'])
            <div class="campo cep-estado" aria-live="polite"><span class="cep-spinner" aria-hidden="true"></span><span class="cep-texto">Buscando CEP…</span></div>
        </div>

        @include('parciais.campo', ['id' => $i('rua'), 'name' => $n('rua'), 'label' => 'Logradouro (rua/avenida)', 'value' => $v('rua'), 'obrigatorio' => true,
            'erro' => $e('rua'), 'attrs' => 'data-f="rua" maxlength="150" placeholder="Rua, avenida, viela…" autocomplete="off"'])

        <div class="linha-num">
            @include('parciais.campo', ['id' => $i('numero'), 'name' => $n('numero'), 'label' => 'Número', 'value' => $v('numero'), 'obrigatorio' => true,
                'erro' => $e('numero'), 'attrs' => 'data-f="numero" maxlength="20" placeholder="Nº ou S/N" autocomplete="off"'])
            @include('parciais.campo', ['id' => $i('bairro'), 'name' => $n('bairro'), 'label' => 'Bairro', 'value' => $v('bairro'), 'obrigatorio' => true,
                'erro' => $e('bairro'), 'attrs' => 'data-f="bairro" maxlength="100" autocomplete="off"'])
        </div>

        <div class="linha-cidade">
            @include('parciais.campo', ['id' => $i('cidade'), 'name' => $n('cidade'), 'label' => 'Cidade', 'value' => $v('cidade'), 'obrigatorio' => true,
                'erro' => $e('cidade'), 'attrs' => 'data-f="cidade" maxlength="100" autocomplete="off"'])
            <div class="campo {{ $e('uf') ? 'is-invalid' : '' }}">
                <label for="{{ $i('uf') }}">UF <span class="obrigatorio" aria-hidden="true">*</span></label>
                <select id="{{ $i('uf') }}" name="{{ $n('uf') }}" data-f="uf" data-obrigatorio @if($e('uf')) aria-invalid="true" @endif>
                    <option value="">UF</option>
                    @foreach($ufs as $uf)
                        <option value="{{ $uf }}" @selected($v('uf') === $uf)>{{ $uf }}</option>
                    @endforeach
                </select>
                <p class="campo-erro" role="alert" @if(!$e('uf')) hidden @endif>{{ $e('uf') }}</p>
            </div>
        </div>

        @include('parciais.campo', ['id' => $i('complemento'), 'name' => $n('complemento'), 'label' => 'Complemento (opcional)', 'value' => $v('complemento'),
            'erro' => $e('complemento'), 'attrs' => 'maxlength="100" placeholder="Apto, bloco, casa 2…" autocomplete="off"'])

        <input type="hidden" name="{{ $n('latitude') }}" data-f="lat" value="{{ old($k('latitude'), $valores['lat'] ?? '') }}">
        <input type="hidden" name="{{ $n('longitude') }}" data-f="lng" value="{{ old($k('longitude'), $valores['lng'] ?? '') }}">

        <h3 class="bloco-subtitulo">Contato</h3>
        <div class="linha-2">
            @include('parciais.campo', ['id' => $i('telefone'), 'name' => $n('telefone'), 'label' => 'Telefone', 'type' => 'tel', 'value' => $v('telefone'),
                'obrigatorio' => true, 'erro' => $e('telefone'),
                'attrs' => 'data-tipo="telefone" data-mascara="telefone" inputmode="tel" maxlength="15" placeholder="(00) 00000-0000"'])
            @include('parciais.campo', ['id' => $i('email'), 'name' => $n('email'), 'label' => $emailObrigatorio ? 'E-mail' : 'E-mail (opcional)', 'type' => 'email',
                'value' => $v('email'), 'obrigatorio' => $emailObrigatorio, 'erro' => $e('email'),
                'ajuda' => $emailObrigatorio ? 'A senha de acesso é enviada para este e-mail.' : null,
                'attrs' => 'data-tipo="email" maxlength="100" placeholder="email@exemplo.com" autocomplete="off"'])
        </div>
    </fieldset>
</div>
