/*
 * Cadastro unificado em fases: vítima > agressor > tornozeleira > medida protetiva > revisão.
 * - "Próximo" só avança com a fase 100% válida (navegador + servidor).
 * - Em caso de erro: campo vermelho, indicador, foco no primeiro campo errado e NENHUM dado é apagado.
 */
(function () {
    'use strict';

    var form = document.getElementById('cadastro-form');
    if (!form) { return; }

    var V = window.Validacao;
    var FASES = ['vitima', 'agressor', 'tornozeleira', 'medida', 'revisao'];
    var secoes = {};
    FASES.forEach(function (f) { secoes[f] = form.querySelector('[data-fase="' + f + '"]'); });
    var passos = document.querySelectorAll('[data-passo]');
    var alerta = document.getElementById('cadastro-alerta');
    var urlValidar = form.getAttribute('data-url-validar');

    var atual = 0;
    var maxAlcancado = 0;
    var ocupado = false;

    /* ------------------------------ navegação ------------------------------ */
    function mostrarAlerta(texto) {
        if (!alerta) { return; }
        alerta.textContent = texto || '';
        alerta.hidden = !texto;
    }

    function ir(indice, focarTitulo) {
        atual = Math.max(0, Math.min(FASES.length - 1, indice));
        maxAlcancado = Math.max(maxAlcancado, atual);

        FASES.forEach(function (f, i) { secoes[f].hidden = i !== atual; });

        passos.forEach(function (li, i) {
            li.classList.toggle('is-atual', i === atual);
            li.classList.toggle('is-concluido', i < atual);
            var b = li.querySelector('button');
            if (b) {
                b.disabled = i > maxAlcancado;
                if (i === atual) { b.setAttribute('aria-current', 'step'); } else { b.removeAttribute('aria-current'); }
            }
        });

        if (FASES[atual] === 'revisao') { montarResumo(); }
        mostrarAlerta('');

        if (focarTitulo !== false) {
            var titulo = secoes[FASES[atual]].querySelector('.fase-titulo');
            if (titulo) {
                titulo.setAttribute('tabindex', '-1');
                titulo.focus({ preventScroll: true });
                form.scrollIntoView({ block: 'start', behavior: 'smooth' });
            }
        }
    }

    function validarFase(indice) {
        var fase = FASES[indice];
        var secao = secoes[fase];
        V.limparTudo(secao);

        var invalidos = V.validarEscopo(secao);
        if (invalidos.length) {
            mostrarAlerta('Corrija os campos destacados em vermelho para continuar.');
            V.focar(invalidos[0]);
            return Promise.resolve(false);
        }

        var dados = new FormData(form);
        // Evita reenviar fotos grandes em fases que não as usam.
        if (fase !== 'vitima') { dados.delete('vitima[foto]'); }
        if (fase !== 'agressor') { dados.delete('agressor[foto]'); }

        return fetch(urlValidar.replace(':fase', fase), {
            method: 'POST',
            body: dados,
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        }).then(function (resposta) {
            if (resposta.ok) { return true; }
            if (resposta.status === 422) {
                return resposta.json().then(function (corpo) {
                    var marcados = V.aplicarErros(form, corpo.errors);
                    mostrarAlerta(corpo.message || 'Corrija os campos destacados em vermelho para continuar.');
                    V.focar(marcados[0]);
                    return false;
                });
            }
            mostrarAlerta('Não foi possível validar agora (erro ' + resposta.status + '). Recarregue a página se persistir; seus dados continuam preenchidos.');
            return false;
        }).catch(function () {
            mostrarAlerta('Sem conexão com o servidor. Seus dados continuam preenchidos; tente novamente.');
            return false;
        });
    }

    form.addEventListener('click', function (e) {
        var proximo = e.target.closest('[data-proximo]');
        var anterior = e.target.closest('[data-anterior]');
        var editar = e.target.closest('[data-ir]');

        if (proximo && !ocupado) {
            ocupado = true;
            proximo.disabled = true;
            validarFase(atual).then(function (ok) {
                ocupado = false;
                proximo.disabled = false;
                if (ok) { ir(atual + 1); }
            });
        } else if (anterior) {
            ir(atual - 1);
        } else if (editar) {
            ir(FASES.indexOf(editar.getAttribute('data-ir')));
        }
    });

    passos.forEach(function (li, i) {
        var b = li.querySelector('button');
        if (b) { b.addEventListener('click', function () { if (i <= maxAlcancado) { ir(i); } }); }
    });

    /* Enter nos campos não envia o formulário: ele avança a fase. */
    form.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && e.target.matches('input:not([type="submit"]):not([type="button"]), select')) {
            e.preventDefault();
            var proximo = secoes[FASES[atual]].querySelector('[data-proximo]');
            if (proximo) { proximo.click(); }
        }
    });

    /* Envio final: revalida todas as fases antes de enviar. */
    form.addEventListener('submit', function (e) {
        for (var i = 0; i < FASES.length - 1; i++) {
            var invalidos = V.validarEscopo(secoes[FASES[i]]);
            if (invalidos.length) {
                e.preventDefault();
                ir(i, false);
                mostrarAlerta('Há campos inválidos nesta fase. Corrija-os para concluir o cadastro.');
                V.focar(invalidos[0]);
                return;
            }
        }
        var botao = form.querySelector('[type="submit"]');
        if (botao) { botao.disabled = true; botao.textContent = 'Salvando…'; }
    });

    /* ------------------------------ revisão ------------------------------ */
    function textoDoRotulo(el) {
        var l = el.closest('.campo') && el.closest('.campo').querySelector('label');
        return l ? l.textContent.replace('*', '').trim() : '';
    }

    function ocultoNaSecao(el, secao) {
        for (var n = el; n && n !== secao; n = n.parentElement) {
            if (n.hidden) { return true; }
        }
        return false;
    }

    function resumir(secao) {
        var linhas = [];
        secao.querySelectorAll('.campo, .grupo-opcoes[data-rotulo]').forEach(function (bloco) {
            if (ocultoNaSecao(bloco, secao)) { return; }
            if (bloco.classList.contains('grupo-opcoes')) {
                var marcados = Array.prototype.map.call(bloco.querySelectorAll('input:checked'), function (i) {
                    var l = i.closest('label');
                    return l ? l.textContent.trim() : i.value;
                });
                if (marcados.length) { linhas.push([bloco.getAttribute('data-rotulo'), marcados.join('; ')]); }
                return;
            }
            var campo = bloco.querySelector('input:not([type="hidden"]):not([type="file"]), select, textarea');
            if (!campo || campo.type === 'checkbox' || campo.type === 'radio' || campo.closest('.grupo-opcoes')) { return; }
            var valor = campo.tagName === 'SELECT' ? (campo.options[campo.selectedIndex] || {}).text : campo.value;
            if (valor && valor.trim() !== '') { linhas.push([textoDoRotulo(campo), valor.trim()]); }
        });
        return linhas;
    }

    function montarResumo() {
        var alvo = document.getElementById('cadastro-resumo');
        alvo.textContent = '';
        var titulos = { vitima: 'Vítima', agressor: 'Agressor', tornozeleira: 'Tornozeleira', medida: 'Medida protetiva' };

        ['vitima', 'agressor', 'tornozeleira', 'medida'].forEach(function (fase) {
            var bloco = document.createElement('section');
            bloco.className = 'resumo-bloco';

            var cab = document.createElement('div');
            cab.className = 'resumo-cabecalho';
            var h = document.createElement('h3');
            h.textContent = titulos[fase];
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'link-botao';
            btn.setAttribute('data-ir', fase);
            btn.textContent = 'Editar';
            cab.appendChild(h);
            cab.appendChild(btn);
            bloco.appendChild(cab);

            var dl = document.createElement('dl');
            dl.className = 'resumo-lista';
            resumir(secoes[fase]).forEach(function (par) {
                var d = document.createElement('div');
                var dt = document.createElement('dt');
                dt.textContent = par[0];
                var dd = document.createElement('dd');
                dd.textContent = par[1];
                d.appendChild(dt);
                d.appendChild(dd);
                dl.appendChild(d);
            });
            bloco.appendChild(dl);
            alvo.appendChild(bloco);
        });
    }

    /* ----------------------- comportamentos de cada fase ----------------------- */
    // Tornozeleira: nova x já cadastrada.
    function alternarModo() {
        var marcado = form.querySelector('input[name="tornozeleira[modo]"]:checked');
        var modo = marcado ? marcado.value : 'nova';
        form.querySelectorAll('[data-modo-bloco]').forEach(function (b) {
            b.hidden = b.getAttribute('data-modo-bloco') !== modo;
        });
    }
    form.querySelectorAll('input[name="tornozeleira[modo]"]').forEach(function (r) { r.addEventListener('change', alternarModo); });
    alternarModo();

    // Medida: vigência indeterminada.
    var indeterminada = form.querySelector('[name="medida[indeterminada]"]');
    var dataFim = form.querySelector('[name="medida[data_fim]"]');
    function alternarVigencia() {
        if (!indeterminada || !dataFim) { return; }
        dataFim.disabled = indeterminada.checked;
        if (indeterminada.checked) { dataFim.value = ''; V.limpar(dataFim); }
    }
    if (indeterminada) { indeterminada.addEventListener('change', alternarVigencia); alternarVigencia(); }

    // Medida: sugestão do raio de alerta (50% acima da distância mínima) enquanto o usuário não editar o raio.
    var distancia = form.querySelector('[name="medida[distancia]"]');
    var raio = form.querySelector('[name="medida[raio]"]');
    if (distancia && raio) {
        raio.addEventListener('input', function () { raio.setAttribute('data-tocado', '1'); });
        distancia.addEventListener('input', function () {
            var d = parseInt(distancia.value, 10);
            if (d > 0 && !raio.getAttribute('data-tocado')) { raio.value = Math.ceil((d * 1.5) / 50) * 50; }
        });
    }

    // Medida: descrição sugerida a partir dos tipos escolhidos (até o usuário escrever a própria).
    var descricao = form.querySelector('[name="medida[descricao]"]');
    var ultimaSugestao = descricao ? descricao.value : '';
    form.querySelectorAll('input[name="medida[tipos][]"]').forEach(function (cb) {
        cb.addEventListener('change', function () {
            if (!descricao || (descricao.value.trim() !== '' && descricao.value !== ultimaSugestao)) { return; }
            var nomes = Array.prototype.map.call(form.querySelectorAll('input[name="medida[tipos][]"]:checked'), function (i) {
                return i.closest('label').textContent.trim();
            });
            ultimaSugestao = nomes.length ? ('Medida protetiva de urgência: ' + nomes.join('; ') + '.').slice(0, 255) : '';
            descricao.value = ultimaSugestao;
        });
    });

    // Datas padrão (hoje).
    var hoje = new Date();
    var iso = hoje.getFullYear() + '-' + String(hoje.getMonth() + 1).padStart(2, '0') + '-' + String(hoje.getDate()).padStart(2, '0');
    ['medida[data_inicio]', 'tornozeleira[dataInstalacao]'].forEach(function (nome) {
        var el = form.querySelector('[name="' + nome + '"]');
        if (el && !el.value) { el.value = iso; }
    });

    /* ------------------------------ início ------------------------------ */
    var inicial = FASES.indexOf(form.getAttribute('data-fase-inicial') || 'vitima');
    if (inicial > 0) { maxAlcancado = FASES.length - 1; }
    ir(inicial < 0 ? 0 : inicial, false);

    var primeiroErro = form.querySelector('.is-invalid');
    if (primeiroErro) {
        mostrarAlerta('Alguns campos precisam de correção. Os dados já preenchidos foram mantidos.');
        V.focar(primeiroErro);
    }
})();
