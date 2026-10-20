/*
 * Mapa de monitoramento (Leaflet). Os filtros de região, bairro, CEP e status rodam no navegador,
 * sobre os pontos entregues pelo servidor em <script type="application/json" id="dados-mapa">.
 */
(function () {
    'use strict';

    var caixa = document.getElementById('mapa');
    var bruto = document.getElementById('dados-mapa');
    if (!caixa || !bruto) { return; }

    if (typeof L === 'undefined') {
        caixa.textContent = 'Não foi possível carregar o mapa (verifique a conexão com a internet).';
        caixa.classList.add('mapa-indisponivel');
        return;
    }

    var dados = JSON.parse(bruto.textContent);
    var mapa = L.map(caixa, { zoomControl: true }).setView([-23.5505, -46.6333], 11);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; contribuidores do <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapa);

    var camadas = {
        ocorrencias: L.layerGroup().addTo(mapa),
        tornozeleiras: L.layerGroup().addTo(mapa),
        vitimas: L.layerGroup()
    };

    var COR_STATUS = { aberta: '#e5484d', andamento: '#f5a524', realizado: '#2ea36b' };

    var el = {
        regiao: document.getElementById('f-regiao'),
        bairro: document.getElementById('f-bairro'),
        cep: document.getElementById('f-cep'),
        status: document.getElementById('f-status'),
        limpar: document.getElementById('f-limpar'),
        contagem: document.getElementById('mapa-contagem'),
        chk: {
            ocorrencias: document.getElementById('c-ocorrencias'),
            tornozeleiras: document.getElementById('c-tornozeleiras'),
            vitimas: document.getElementById('c-vitimas')
        }
    };

    function linha(pai, rotulo, valor) {
        if (valor === null || valor === undefined || valor === '') { return; }
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
        linhas.forEach(function (l) { linha(d, l[0], l[1]); });
        if (url) {
            var a = document.createElement('a');
            a.href = url;
            a.textContent = 'Abrir detalhes ↗';
            d.appendChild(a);
        }
        return d;
    }

    function digitos(v) { return String(v || '').replace(/\D/g, ''); }

    function passa(p, ehOcorrencia) {
        if (el.regiao.value && p.regiao !== el.regiao.value) { return false; }
        if (el.bairro.value && p.bairro !== el.bairro.value) { return false; }
        var cep = digitos(el.cep.value);
        if (cep && digitos(p.cep).indexOf(cep) !== 0) { return false; }
        if (ehOcorrencia && el.status.value && p.status !== el.status.value) { return false; }
        return true;
    }

    function atualizarBairros() {
        var atual = el.bairro.value;
        var nomes = {};
        ['ocorrencias', 'tornozeleiras', 'vitimas'].forEach(function (k) {
            dados[k].forEach(function (p) {
                if (p.bairro && (!el.regiao.value || p.regiao === el.regiao.value)) { nomes[p.bairro] = true; }
            });
        });
        el.bairro.textContent = '';
        var todos = document.createElement('option');
        todos.value = '';
        todos.textContent = 'Todos os bairros';
        el.bairro.appendChild(todos);
        Object.keys(nomes).sort(function (a, b) { return a.localeCompare(b, 'pt-BR'); }).forEach(function (n) {
            var o = document.createElement('option');
            o.value = n;
            o.textContent = n;
            el.bairro.appendChild(o);
        });
        el.bairro.value = nomes[atual] ? atual : '';
    }

    function desenhar() {
        var limites = [];
        var total = { ocorrencias: 0, tornozeleiras: 0, vitimas: 0 };

        Object.keys(camadas).forEach(function (k) { camadas[k].clearLayers(); });

        if (el.chk.ocorrencias.checked) {
            dados.ocorrencias.filter(function (p) { return passa(p, true); }).forEach(function (p) {
                var cor = COR_STATUS[p.status] || '#7a4dd8';
                var m = L.circleMarker([p.lat, p.lng], {
                    radius: p.gravidade === 'alta' ? 11 : 8, color: '#fff', weight: 2, fillColor: cor, fillOpacity: 0.95
                });
                m.bindPopup(popup(p.titulo, [
                    ['Status', p.statusRotulo], ['Gravidade', p.gravidade], ['Quando', p.data],
                    ['Local', p.local], ['Bairro', p.bairro], ['CEP', p.cep], ['Região', p.regiao]
                ], p.url));
                camadas.ocorrencias.addLayer(m);
                limites.push([p.lat, p.lng]);
                total.ocorrencias++;
            });
        }

        if (el.chk.tornozeleiras.checked) {
            dados.tornozeleiras.filter(function (p) { return passa(p, false); }).forEach(function (p) {
                var m = L.circleMarker([p.lat, p.lng], {
                    radius: 8, color: p.bateria < 20 ? '#e5484d' : '#fff', weight: 3, fillColor: '#7a4dd8', fillOpacity: 0.95
                });
                m.bindPopup(popup(p.titulo, [
                    ['Agressor', p.agressor], ['Status', p.status], ['Bateria', p.bateria + '%'],
                    ['Último sinal', p.data], ['Bairro', p.bairro], ['Região', p.regiao]
                ], p.url));
                camadas.tornozeleiras.addLayer(m);
                limites.push([p.lat, p.lng]);
                total.tornozeleiras++;
            });
        }

        if (el.chk.vitimas.checked) {
            dados.vitimas.filter(function (p) { return passa(p, false); }).forEach(function (p) {
                var m = L.circleMarker([p.lat, p.lng], { radius: 7, color: '#fff', weight: 2, fillColor: '#2f8fe0', fillOpacity: 0.95 });
                m.bindPopup(popup(p.titulo, [['Última localização', p.data], ['Bairro', p.bairro], ['Região', p.regiao]], p.url));
                camadas.vitimas.addLayer(m);
                limites.push([p.lat, p.lng]);
                total.vitimas++;
            });
        }

        el.contagem.textContent = total.ocorrencias + ' ocorrência(s), ' + total.tornozeleiras + ' tornozeleira(s)' +
            (el.chk.vitimas.checked ? ', ' + total.vitimas + ' vítima(s)' : '') + ' no mapa';

        if (limites.length) {
            mapa.fitBounds(limites, { padding: [40, 40], maxZoom: 15 });
        }
    }

    el.regiao.addEventListener('change', function () { atualizarBairros(); desenhar(); });
    el.bairro.addEventListener('change', desenhar);
    el.status.addEventListener('change', desenhar);
    el.cep.addEventListener('input', desenhar);
    Object.keys(el.chk).forEach(function (k) { el.chk[k].addEventListener('change', desenhar); });
    el.limpar.addEventListener('click', function () {
        el.regiao.value = '';
        el.cep.value = '';
        el.status.value = '';
        atualizarBairros();
        desenhar();
    });

    window.addEventListener('resize', function () { mapa.invalidateSize(); });

    atualizarBairros();
    desenhar();
})();
