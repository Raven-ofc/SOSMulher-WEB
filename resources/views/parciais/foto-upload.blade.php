{{-- Foto de identificação: círculo perfeito com ícone de câmera e botão estilizado. Parâmetros: id, name, atual (url|null), erro --}}
@php($erro = $erro ?? '')
<div class="campo foto-campo {{ $erro ? 'is-invalid' : '' }}">
    <span class="campo-rotulo">Foto de identificação <small>(opcional)</small></span>
    <div class="foto-upload">
        <div class="foto-circulo">
            <img class="foto-previa" alt="Pré-visualização da foto" data-original="{{ $atual ?? '' }}"
                 @if(!empty($atual)) src="{{ $atual }}" @else hidden @endif>
            <svg class="foto-placeholder" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" @if(!empty($atual)) hidden @endif>
                <path d="M4 8h3l1.6-2.4h6.8L17 8h3a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1Z"/>
                <circle cx="12" cy="13" r="3.6"/>
            </svg>
        </div>
        <div class="foto-acoes">
            <input class="foto-input" type="file" id="{{ $id }}" name="{{ $name }}" accept="image/*" data-imagem
                   @if($erro) aria-invalid="true" @endif>
            <label class="botao-foto" for="{{ $id }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 16V4m0 0L7.5 8.5M12 4l4.5 4.5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>
                </svg>
                {{ !empty($atual) ? 'Trocar foto' : 'Carregar foto' }}
            </label>
            <button type="button" class="botao-foto-remover" hidden>Remover</button>
            <span class="foto-nome">{{ !empty($atual) ? 'Foto atual mantida' : 'Nenhum arquivo selecionado' }}</span>
            <p class="campo-ajuda">JPG, PNG ou WEBP, até 5 MB.</p>
        </div>
    </div>
    <p class="campo-erro" role="alert" @if(!$erro) hidden @endif>{{ $erro }}</p>
</div>
