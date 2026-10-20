/* Pré-visualização circular da foto + nome do arquivo + botão de remover. */
(function () {
    'use strict';

    function iniciar(input) {
        var raiz = input.closest('.foto-upload');
        var img = raiz.querySelector('.foto-previa');
        var icone = raiz.querySelector('.foto-placeholder');
        var nome = raiz.querySelector('.foto-nome');
        var remover = raiz.querySelector('.botao-foto-remover');
        var original = img.getAttribute('data-original') || '';

        function mostrar(src, texto) {
            if (src) {
                img.src = src;
                img.hidden = false;
                icone.hidden = true;
            } else {
                img.removeAttribute('src');
                img.hidden = true;
                icone.hidden = false;
            }
            nome.textContent = texto;
        }

        input.addEventListener('change', function () {
            var arquivo = input.files && input.files[0];
            if (!arquivo) { mostrar(original, original ? 'Foto atual mantida' : 'Nenhum arquivo selecionado'); remover.hidden = true; return; }
            if (!/^image\//.test(arquivo.type) || arquivo.size > 5 * 1024 * 1024) {
                input.value = '';
                if (window.Validacao) { window.Validacao.marcar(input, 'Envie uma imagem (JPG, PNG, WEBP) de até 5 MB.'); }
                return;
            }
            var leitor = new FileReader();
            leitor.onload = function (e) { mostrar(e.target.result, arquivo.name); };
            leitor.readAsDataURL(arquivo);
            remover.hidden = false;
        });

        remover.addEventListener('click', function () {
            input.value = '';
            mostrar(original, original ? 'Foto atual mantida' : 'Nenhum arquivo selecionado');
            remover.hidden = true;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.foto-upload input[type="file"]').forEach(iniciar);
    });
})();
