/*
 * Validação visual de formulários (usada em todos os cadastros).
 *  - campo inválido fica vermelho, ganha mensagem + indicador "!" e recebe o foco;
 *  - nenhum outro campo é apagado;
 *  - máscaras: data-mascara="cpf|telefone|cep|processo".
 * Marcação esperada: <div class="campo"> <label/> <input data-obrigatorio data-tipo="cpf"> <p class="campo-erro"> </div>
 */
(function () {
    'use strict';

    var V = {};

    function recipiente(el) {
        return el.closest('.campo, .grupo-opcoes') || el.parentElement;
    }

    V.limpar = function (el) {
        var c = recipiente(el);
        c.classList.remove('is-invalid');
        el.removeAttribute('aria-invalid');
        var m = c.querySelector(':scope > .campo-erro');
        if (m) { m.hidden = true; m.textContent = ''; }
    };

    V.marcar = function (el, mensagem) {
        var c = recipiente(el);
        c.classList.add('is-invalid');
        el.setAttribute('aria-invalid', 'true');
        var m = c.querySelector(':scope > .campo-erro');
        if (!m) {
            m = document.createElement('p');
            m.className = 'campo-erro';
            m.setAttribute('role', 'alert');
            c.appendChild(m);
        }
        m.textContent = mensagem;
        m.hidden = false;
    };

    V.limparTudo = function (escopo) {
        (escopo || document).querySelectorAll('.is-invalid').forEach(function (c) {
            c.classList.remove('is-invalid');
            var m = c.querySelector(':scope > .campo-erro');
            if (m) { m.hidden = true; m.textContent = ''; }
            c.querySelectorAll('[aria-invalid]').forEach(function (i) { i.removeAttribute('aria-invalid'); });
        });
    };

    V.focar = function (el) {
        if (!el) { return; }
        var alvo = el;
        if (el.matches && !el.matches('input, select, textarea, button')) {
            alvo = el.querySelector('input, select, textarea') || el;
        }
        if (alvo.scrollIntoView) { alvo.scrollIntoView({ block: 'center', behavior: 'smooth' }); }
        try { alvo.focus({ preventScroll: true }); } catch (e) { alvo.focus(); }
    };

    /* "vitima.nome" -> campo name="vitima[nome]"; "medida.tipos.0" -> name="medida[tipos][]" */
    V.campoPorChave = function (form, chave) {
        var partes = chave.split('.');
        var nome = partes[0] + partes.slice(1).map(function (p) { return '[' + p + ']'; }).join('');
        var tentativas = [nome, nome.replace(/\[\d+\]$/, '[]'), nome + '[]'];
        for (var i = 0; i < tentativas.length; i++) {
            var achado = form.querySelector('[name="' + tentativas[i] + '"]');
            if (achado) {
                return achado.type === 'radio' || achado.type === 'checkbox' ? (recipiente(achado) || achado) : achado;
            }
        }
        return null;
    };

    /* Aplica erros do servidor ({chave: [mensagens]}). Retorna os elementos marcados, na ordem do documento. */
    V.aplicarErros = function (form, erros) {
        var marcados = [];
        Object.keys(erros || {}).forEach(function (chave) {
            var el = V.campoPorChave(form, chave);
            if (el) {
                var msg = Array.isArray(erros[chave]) ? erros[chave][0] : erros[chave];
                if (el.classList && el.classList.contains('grupo-opcoes')) {
                    var c = el;
                    c.classList.add('is-invalid');
                    var m = c.querySelector(':scope > .campo-erro');
                    if (!m) { m = document.createElement('p'); m.className = 'campo-erro'; m.setAttribute('role', 'alert'); c.appendChild(m); }
                    m.textContent = msg; m.hidden = false;
                } else {
                    V.marcar(el, msg);
                }
                if (marcados.indexOf(el) === -1) { marcados.push(el); }
            }
        });
        marcados.sort(function (a, b) {
            return (a.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING) ? -1 : 1;
        });
        return marcados;
    };

    /* ----------------------- validadores do navegador ----------------------- */
    function cpfValido(valor) {
        var d = String(valor).replace(/\D/g, '');
        if (d.length !== 11 || /^(\d)\1{10}$/.test(d)) { return false; }
        for (var t = 9; t < 11; t++) {
            var soma = 0;
            for (var i = 0; i < t; i++) { soma += parseInt(d[i], 10) * (t + 1 - i); }
            var dig = 11 - (soma % 11);
            if (dig >= 10) { dig = 0; }
            if (dig !== parseInt(d[t], 10)) { return false; }
        }
        return true;
    }

    var VALIDADORES = {
        cpf: [cpfValido, 'Informe um CPF válido.'],
        email: [function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v); }, 'Informe um e-mail válido.'],
        telefone: [function (v) { var n = v.replace(/\D/g, '').length; return n === 10 || n === 11; }, 'Informe um telefone válido, com DDD.'],
        cep: [function (v) { return v.replace(/\D/g, '').length === 8; }, 'Informe um CEP válido (00000-000).'],
        nomecompleto: [function (v) { return /^\S+(\s+\S+)+$/.test(v.trim()); }, 'Informe o nome e o sobrenome.'],
        dataPassada: [function (v) { return v <= new Date().toISOString().slice(0, 10); }, 'A data não pode ser futura.'],
        processo: [function (v) { return /^\d{7}-\d{2}\.\d{4}\.\d\.\d{2}\.\d{4}$/.test(v); }, 'Use o formato do CNJ: 0000000-00.0000.0.00.0000.']
    };

    /* Oculto = tem um ancestral [hidden] DENTRO do escopo validado (a própria fase pode estar escondida). */
    function visivel(el, escopo) {
        if (el.disabled) { return false; }
        for (var n = el; n && n !== escopo; n = n.parentElement) {
            if (n.hidden) { return false; }
        }
        return true;
    }

    /* Valida todos os campos de um escopo. Retorna a lista de elementos inválidos (ordem do documento). */
    V.validarEscopo = function (escopo) {
        var invalidos = [];

        escopo.querySelectorAll('[data-obrigatorio], [data-tipo]').forEach(function (el) {
            if (!visivel(el, escopo) || el.type === 'file') { return; }
            var valor = (el.value || '').trim();
            if (el.hasAttribute('data-obrigatorio') && valor === '') {
                V.marcar(el, 'Preencha este campo.');
                invalidos.push(el);
                return;
            }
            var tipo = el.getAttribute('data-tipo');
            if (valor !== '' && tipo && VALIDADORES[tipo] && !VALIDADORES[tipo][0](valor)) {
                V.marcar(el, VALIDADORES[tipo][1]);
                invalidos.push(el);
                return;
            }
            var min = el.getAttribute('data-min');
            if (valor !== '' && min !== null && parseFloat(valor) < parseFloat(min)) {
                V.marcar(el, 'O valor deve ser no mínimo ' + min + '.');
                invalidos.push(el);
            }
        });

        escopo.querySelectorAll('[data-grupo-obrigatorio]').forEach(function (grupo) {
            if (!visivel(grupo, escopo)) { return; }
            if (!grupo.querySelector('input:checked')) {
                var m = grupo.querySelector(':scope > .campo-erro');
                grupo.classList.add('is-invalid');
                if (!m) { m = document.createElement('p'); m.className = 'campo-erro'; m.setAttribute('role', 'alert'); grupo.appendChild(m); }
                m.textContent = 'Selecione ao menos uma opção.'; m.hidden = false;
                invalidos.push(grupo);
            }
        });

        escopo.querySelectorAll('input[type="file"][data-imagem]').forEach(function (inp) {
            var f = inp.files && inp.files[0];
            if (f && (!/^image\//.test(f.type) || f.size > 5 * 1024 * 1024)) {
                V.marcar(inp, 'Envie uma imagem de até 5 MB.');
                invalidos.push(inp);
            }
        });

        invalidos.sort(function (a, b) {
            return (a.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING) ? -1 : 1;
        });
        return invalidos;
    };

    /* ----------------------------- máscaras ----------------------------- */
    var MASCARAS = {
        cpf: function (d) {
            d = d.slice(0, 11);
            return d.replace(/^(\d{3})(\d)/, '$1.$2').replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3').replace(/\.(\d{3})(\d)/, '.$1-$2');
        },
        telefone: function (d) {
            d = d.slice(0, 11);
            if (d.length > 10) { return d.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3'); }
            if (d.length > 6) { return d.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3'); }
            if (d.length > 2) { return d.replace(/^(\d{2})(\d*)/, '($1) $2'); }
            return d;
        },
        cep: function (d) {
            d = d.slice(0, 8);
            return d.length > 5 ? d.slice(0, 5) + '-' + d.slice(5) : d;
        },
        processo: function (d) {
            d = d.slice(0, 20);
            var m = d.match(/^(\d{0,7})(\d{0,2})(\d{0,4})(\d{0,1})(\d{0,2})(\d{0,4})$/);
            if (!m) { return d; }
            var s = m[1];
            if (m[2]) { s += '-' + m[2]; }
            if (m[3]) { s += '.' + m[3]; }
            if (m[4]) { s += '.' + m[4]; }
            if (m[5]) { s += '.' + m[5]; }
            if (m[6]) { s += '.' + m[6]; }
            return s;
        }
    };

    document.addEventListener('input', function (e) {
        var el = e.target;
        var tipo = el.getAttribute && el.getAttribute('data-mascara');
        if (tipo && MASCARAS[tipo]) {
            el.value = MASCARAS[tipo](el.value.replace(/\D/g, ''));
        }
        if (el.getAttribute && el.getAttribute('aria-invalid') === 'true') { V.limpar(el); }
    });

    document.addEventListener('change', function (e) {
        var el = e.target;
        var grupo = el.closest && el.closest('.grupo-opcoes.is-invalid');
        if (grupo) {
            grupo.classList.remove('is-invalid');
            var m = grupo.querySelector(':scope > .campo-erro');
            if (m) { m.hidden = true; }
        }
        if (el.getAttribute && el.getAttribute('aria-invalid') === 'true') { V.limpar(el); }
    });

    /* Formulários simples (edição): <form data-validar novalidate> */
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form.matches || !form.matches('form[data-validar]')) { return; }
        var invalidos = V.validarEscopo(form);
        if (invalidos.length) {
            e.preventDefault();
            V.focar(invalidos[0]);
        }
    });

    /* Erros vindos do servidor (Blade já renderiza .is-invalid): foca o primeiro. */
    document.addEventListener('DOMContentLoaded', function () {
        var primeiro = document.querySelector('.campo.is-invalid input, .campo.is-invalid select, .campo.is-invalid textarea, .grupo-opcoes.is-invalid');
        if (primeiro && !document.querySelector('[data-fase]')) { V.focar(primeiro); }
    });

    window.Validacao = V;
})();
