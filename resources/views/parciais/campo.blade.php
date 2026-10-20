{{--
    Campo de formulário padronizado (rótulo + input + ajuda + mensagem de erro).
    Parâmetros: id, name, label, value, type (text), obrigatorio (false), erro (''), attrs ('' - atributos extras já escapados),
                ajuda (null), classe ('')
    O erro do servidor chega em $erro; no navegador, validacao.js usa .campo-erro do mesmo bloco.
--}}
@php($erro = $erro ?? '')
<div class="campo {{ $classe ?? '' }} {{ $erro ? 'is-invalid' : '' }}">
    <label for="{{ $id }}">{{ $label }}@if(!empty($obrigatorio)) <span class="obrigatorio" aria-hidden="true">*</span>@endif</label>
    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type ?? 'text' }}"
        value="{{ $value ?? '' }}"
        @if(!empty($obrigatorio)) data-obrigatorio aria-required="true" @endif
        @if($erro) aria-invalid="true" @endif
        {!! $attrs ?? '' !!}
    >
    @if(!empty($ajuda))
        <p class="campo-ajuda">{{ $ajuda }}</p>
    @endif
    <p class="campo-erro" role="alert" @if(!$erro) hidden @endif>{{ $erro }}</p>
</div>
