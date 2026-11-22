
/*
 * Mapa de monitoramento (Leaflet).
 * Exibe ocorrências e tornozeleiras inicialmente.
 * A localização da vítima é carregada somente ao clicar em uma tornozeleira.
 */
(function () {
    'use strict';

    var caixa = document.getElementById('mapa');
    var bruto = document.getElementById('dados-mapa');

    if (!caixa || !bruto) {
        return;
    }

    if (typeof L === 'undefined') {
        caixa.textContent = 'Não foi possível carregar o mapa (verifique a conexão com a internet).';
        caixa.classList.add('mapa-indisponivel');
        return;
    }

    var dados = JSON.parse(bruto.textContent);

    var mapa = L.map(caixa, {
        zoomControl: true
    }).setView([-23.5505, -46.6333], 11);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; contribuidores do <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapa);

    // Camadas exibidas inicialmente.
    var camadas = {
        ocorrencias: L.layerGroup().addTo(mapa),
        tornozeleiras: L.layerGroup().addTo(mapa)
    };

    // Camada exclusiva para a vítima selecionada.
    var camadaVitimaSelecionada = L.layerGroup().addTo(mapa);

    // Identifica a requisição mais recente, evitando respostas fora de ordem.
    var requisicaoVitima = 0;

    var COR_STATUS = {
        aberta: '#e5484d',
        andamento: '#f5a524',
        realizado: '#2ea36b'
    };

    var el = {
        regiao: document.getElementById('f-regiao'),
        bairro: document.getElementById('f-bairro'),
        cep: document.getElementById('f-cep'),
        status: document.getElementById('f-status'),
        limpar: document.getElementById('f-limpar'),
        contagem: document.getElementById('mapa-contagem'),
        chk: {
            ocorrencias: document.getElementById('c-ocorrencias'),
            tornozeleiras: document.getElementById('c-tornozeleiras')
        }
    };

    function linha(pai, rotulo, valor) {
        if (valor === null || valor === undefined || valor === '') {
            return;
        }

        var p = document.createElement('p');
        var b = document.createElement('strong');

        b.textContent = rotulo + ': ';

        p.appendChild(b);
        p.appendChild(document.createTextNode(valor));

        pai.appendChild(p);
    }

    function popup(titulo, linhas, url) {
        var d = document.createElement('div');
        d.className = 'mapa-popup';

        var h = document.createElement('h4');
        h.textContent = titulo;

        d.appendChild(h);

        linhas.forEach(function (l) {
            linha(d, l[0], l[1]);
        });

        if (url) {
            var a = document.createElement('a');

            a.href = url;
            a.textContent = 'Abrir detalhes ↗';

            d.appendChild(a);
        }

        return d;
    }
    var marcadoresOcorrencia = {};

    function criarMarcadorOcorrencia(p) {
        var m = L.circleMarker([p.lat, p.lng], {
            radius: p.gravidade === 'alta' ? 11 : 8,
            color: '#fff',
            weight: 2,
            fillColor: COR_STATUS[p.status] || '#7a4dd8',
            fillOpacity: 0.95
        });

        m.bindPopup(popup(p.titulo, [
            ['Status', p.statusRotulo],
            ['Gravidade', p.gravidade],
            ['Quando', p.data],
            ['Local', p.local],
            ['Bairro', p.bairro],
            ['CEP', p.cep],
            ['Região', p.regiao]
        ], p.url));

        return m;
    }

    function digitos(v) {
        return String(v || '').replace(/\D/g, '');
    }

    function passa(p, ehOcorrencia) {
        if (el.regiao.value && p.regiao !== el.regiao.value) {
            return false;
        }

        if (el.bairro.value && p.bairro !== el.bairro.value) {
            return false;
        }

        var cep = digitos(el.cep.value);

        if (cep && digitos(p.cep).indexOf(cep) !== 0) {
            return false;
        }

        if (ehOcorrencia && el.status.value && p.status !== el.status.value) {
            return false;
        }

        return true;
    }

    function atualizarBairros() {
        var atual = el.bairro.value;
        var nomes = {};

        ['ocorrencias', 'tornozeleiras'].forEach(function (k) {
            dados[k].forEach(function (p) {
                if (p.bairro && (!el.regiao.value || p.regiao === el.regiao.value)) {
                    nomes[p.bairro] = true;
                }
            });
        });

        el.bairro.textContent = '';

        var todos = document.createElement('option');
        todos.value = '';
        todos.textContent = 'Todos os bairros';

        el.bairro.appendChild(todos);

        Object.keys(nomes)
            .sort(function (a, b) {
                return a.localeCompare(b, 'pt-BR');
            })
            .forEach(function (n) {
                var o = document.createElement('option');

                o.value = n;
                o.textContent = n;

                el.bairro.appendChild(o);
            });

        el.bairro.value = nomes[atual] ? atual : '';
    }

    /*
     * Busca a localização da vítima relacionada à ocorrência
     * mais recente do agressor associado à tornozeleira.
     */
    function carregarVitima(tornozeleira) {
        // Invalida qualquer requisição anterior.
        requisicaoVitima++;

        var minhaRequisicao = requisicaoVitima;

        // Remove o marcador da vítima anteriormente selecionada.
        camadaVitimaSelecionada.clearLayers();

        if (!tornozeleira.urlVitimas) {
            console.error('URL para buscar a vítima não foi informada.');
            return;
        }

        fetch(tornozeleira.urlVitimas, {
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(function (resposta) {
                if (!resposta.ok) {
                    throw new Error('Não foi possível consultar a localização da vítima.');
                }

                return resposta.json();
            })
            .then(function (vitimas) {
                // Ignora respostas de cliques anteriores.
                if (minhaRequisicao !== requisicaoVitima) {
                    return;
                }

                if (!Array.isArray(vitimas) || vitimas.length === 0) {
                    console.info('Nenhuma localização disponível para a vítima desta ocorrência.');
                    return;
                }

                var vitima = vitimas[0];

                if (
                    vitima.lat === null || vitima.lat === undefined ||
                    vitima.lng === null || vitima.lng === undefined
                ) {
                    return;
                }

                // Marcador azul da vítima selecionada.
                var marcador = L.circleMarker(
                    [vitima.lat, vitima.lng],
                    {
                        radius: 9,
                        color: '#fff',
                        weight: 2,
                        fillColor: '#2f8fe0',
                        fillOpacity: 0.95
                    }
                );

                marcador.bindPopup(popup(vitima.titulo, [
                    ['Última localização', vitima.data],
                    ['Bairro', vitima.bairro],
                    ['CEP', vitima.cep],
                    ['Região', vitima.regiao]
                ]));

                camadaVitimaSelecionada.addLayer(marcador);

            })
            .catch(function (erro) {
                if (minhaRequisicao === requisicaoVitima) {
                    console.error(erro);
                }
            });
    }

    function desenhar(opcoes) {
        var ajustar = !opcoes || opcoes.ajustar !== false
        var limites = [];

        var total = {
            ocorrencias: 0,
            tornozeleiras: 0
        };
        marcadoresOcorrencia = {};
        Object.keys(camadas).forEach(function (k) { camadas[k].clearLayers(); });

        if (el.chk.ocorrencias.checked) {
            dados.ocorrencias
                .filter(function (p) { return passa(p, true); })
                .forEach(function (p) {
                    var m = criarMarcadorOcorrencia(p);
                    marcadoresOcorrencia[p.id] = m;
                    camadas.ocorrencias.addLayer(m);
                    limites.push([p.lat, p.lng]);
                    total.ocorrencias++;
                });
        }

        Object.keys(camadas).forEach(function (k) {
            camadas[k].clearLayers();
        });

        // Desenha ocorrências.
        if (el.chk.ocorrencias.checked) {
            dados.ocorrencias
                .filter(function (p) {
                    return passa(p, true);
                })
                .forEach(function (p) {
                    var cor = COR_STATUS[p.status] || '#7a4dd8';

                    var m = L.circleMarker([p.lat, p.lng], {
                        radius: p.gravidade === 'alta' ? 11 : 8,
                        color: '#fff',
                        weight: 2,
                        fillColor: cor,
                        fillOpacity: 0.95
                    });

                    m.bindPopup(popup(p.titulo, [
                        ['Status', p.statusRotulo],
                        ['Gravidade', p.gravidade],
                        ['Quando', p.data],
                        ['Local', p.local],
                        ['Bairro', p.bairro],
                        ['CEP', p.cep],
                        ['Região', p.regiao]
                    ], p.url));

                    camadas.ocorrencias.addLayer(m);

                    limites.push([p.lat, p.lng]);
                    total.ocorrencias++;
                });
        }

        // Desenha tornozeleiras.
        if (el.chk.tornozeleiras.checked) {
            dados.tornozeleiras
                .filter(function (p) {
                    return passa(p, false);
                })
                .forEach(function (p) {
                    var m = L.circleMarker([p.lat, p.lng], {
                        radius: 8,
                        color: p.bateria < 20 ? '#e5484d' : '#fff',
                        weight: 3,
                        fillColor: '#7a4dd8',
                        fillOpacity: 0.95
                    });

                    m.bindPopup(popup(p.titulo, [
                        ['Agressor', p.agressor],
                        ['Status', p.status],
                        ['Bateria', p.bateria + '%'],
                        ['Último sinal', p.data],
                        ['Bairro', p.bairro],
                        ['Região', p.regiao]
                    ], p.url));

                    // Ao clicar na tornozeleira, busca a vítima correspondente.
                    m.on('click', function () {
                        carregarVitima(p);
                    });

                    camadas.tornozeleiras.addLayer(m);

                    limites.push([p.lat, p.lng]);
                    total.tornozeleiras++;
                });
        }

        el.contagem.textContent =
            total.ocorrencias + ' ocorrência(s), ' +
            total.tornozeleiras + ' tornozeleira(s) no mapa';

        if (limites.length) {
            mapa.fitBounds(limites, {
                padding: [40, 40],
                maxZoom: 15
            });
        }

        if (ajustar && limites.length) {
            mapa.fitBounds(limites, { padding: [40, 40], maxZoom: 15 });
        }
    }

    el.regiao.addEventListener('change', function () {
        atualizarBairros();
        desenhar();
    });

    el.bairro.addEventListener('change', desenhar);
    el.status.addEventListener('change', desenhar);
    el.cep.addEventListener('input', desenhar);

    Object.keys(el.chk).forEach(function (k) {
        el.chk[k].addEventListener('change', desenhar);
    });

    function limparFiltros() {
        el.regiao.value = '';
        el.cep.value = '';
        el.status.value = '';
        atualizarBairros();
        desenhar();
    }

    el.limpar.addEventListener('click', limparFiltros);

    el.limpar.addEventListener('click', function () {
        el.regiao.value = '';
        el.cep.value = '';
        el.status.value = '';

        atualizarBairros();
        desenhar();
    });

    window.addEventListener('resize', function () {
        mapa.invalidateSize();
    });

    /* ---------- Novas ocorrências ---------- */
    var URL_NOVAS = caixa.getAttribute('data-url-novas');
    var ultimoId = parseInt(caixa.getAttribute('data-ultimo-id'), 10) || 0;
    var INTERVALO_MS = 10000;
    var consultando = false;
    var areaAviso = document.getElementById('toast-ocorrencias');

    function atualizarResumo(resumo) {
        if (!resumo) { return; }

        Array.prototype.forEach.call(document.querySelectorAll('[data-resumo]'), function (n) {
            var v = resumo[n.getAttribute('data-resumo')];
            if (v !== undefined) { n.textContent = v; }
        });
    }

    function focarOcorrencia(p) {
        // se um filtro estiver escondendo o ponto, limpa os filtros
        if (!el.chk.ocorrencias.checked || !passa(p, true)) {
            el.chk.ocorrencias.checked = true;
            limparFiltros();
        }

        var m = marcadoresOcorrencia[p.id];
        if (m) {
            mapa.setView([p.lat, p.lng], 16);
            m.openPopup();
        }
    }

    function avisar(p) {
        if (!areaAviso) { return; }

        var t = document.createElement('div');
        t.className = 'toast-ocorrencia';
        t.setAttribute('role', 'alert');

        // textContent (e não innerHTML): local e descrição vêm de dados externos
        var titulo = document.createElement('strong');
        titulo.textContent = 'Nova ocorrência: ' + p.titulo;

        var detalhe = document.createElement('span');
        detalhe.textContent = (p.local || 'Local não identificado') + ' · ' + p.data;

        var acoes = document.createElement('div');
        acoes.className = 'toast-acoes';

        var ver = document.createElement('button');
        ver.type = 'button';
        ver.textContent = 'Ver no mapa';
        ver.addEventListener('click', function () {
            focarOcorrencia(p);
            t.remove();
        });

        var fechar = document.createElement('button');
        fechar.type = 'button';
        fechar.className = 'botao-secundario';
        fechar.textContent = 'Fechar';
        fechar.addEventListener('click', function () { t.remove(); });

        acoes.appendChild(ver);
        acoes.appendChild(fechar);
        t.appendChild(titulo);
        t.appendChild(detalhe);
        t.appendChild(acoes);
        areaAviso.appendChild(t);
    }

    function consultarNovas() {
        if (consultando || !URL_NOVAS) { return; }
        consultando = true;

        fetch(URL_NOVAS + '?depois_de=' + ultimoId, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
            .then(function (r) {
                if (!r.ok) { throw new Error('Falha ao consultar novas ocorrências.'); }
                return r.json();
            })
            .then(function (res) {
                ultimoId = res.ultimoId;
                atualizarResumo(res.resumo);

                var novas = (res.ocorrencias || []).filter(function (p) {
                    return !dados.ocorrencias.some(function (o) { return o.id === p.id; });
                });

                if (!novas.length) { return; }

                novas.forEach(function (p) { dados.ocorrencias.unshift(p); });
                desenhar({ ajustar: false });
                novas.forEach(avisar);

                // abre automaticamente o balão da mais recente
                var ultima = novas[novas.length - 1];
                if (marcadoresOcorrencia[ultima.id]) {
                    marcadoresOcorrencia[ultima.id].openPopup();
                }
            })
            .catch(function (erro) { console.error(erro); })
            .then(function () { consultando = false; });
    }

    setInterval(consultarNovas, INTERVALO_MS);

    atualizarBairros();
    desenhar();

})();
