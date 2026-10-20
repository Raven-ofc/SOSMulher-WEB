/*
 * Busca de endereço por CEP (ViaCEP) + coordenadas (OpenStreetMap/Nominatim, quando disponível).
 * Uso: <div data-cep-group> com campos data-f="cep|rua|numero|bairro|cidade|uf|lat|lng".
 * Funciona em quantos grupos existirem na página (ex.: vítima e agressor no mesmo cadastro).
 * Nunca apaga o que o usuário já digitou: se o CEP falhar, só marca o erro.
 */
(function () {
    'use strict';

    var temporizadores = new WeakMap();

    function campo(grupo, f) {
        return grupo.querySelector('[data-f="' + f + '"]');
    }

    function definir(grupo, f, valor) {
        var el = campo(grupo, f);
        if (!el || valor === undefined || valor === null) { return; }
        el.value = valor;
        if (el.getAttribute('aria-invalid') === 'true' && window.Validacao) { window.Validacao.limpar(el); }
    }

    function erro(grupo, mensagem) {
        var cep = campo(grupo, 'cep');
        if (cep && window.Validacao) { window.Validacao.marcar(cep, mensagem); }
    }

    function geocodificar(grupo) {
        var rua = (campo(grupo, 'rua') || {}).value || '';
        var cidade = (campo(grupo, 'cidade') || {}).value || '';
        if (!rua || !cidade) { return; }

        var numero = (campo(grupo, 'numero') || {}).value || '';
        var partes = [rua, numero, (campo(grupo, 'bairro') || {}).value, cidade, (campo(grupo, 'uf') || {}).value, 'Brasil'];
        var consulta = partes.filter(function (p) { return p && String(p).trim() !== ''; }).join(', ');

        fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=br&q=' + encodeURIComponent(consulta), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (r) { return r.ok ? r.json() : []; })
            .then(function (res) {
                if (res && res[0]) {
                    definir(grupo, 'lat', parseFloat(res[0].lat).toFixed(7));
                    definir(grupo, 'lng', parseFloat(res[0].lon).toFixed(7));
                }
            })
            .catch(function () { /* coordenadas são opcionais */ });
    }

    function agendarGeocode(grupo) {
        clearTimeout(temporizadores.get(grupo));
        temporizadores.set(grupo, setTimeout(function () { geocodificar(grupo); }, 600));
    }

    function pesquisar(grupo) {
        var cepEl = campo(grupo, 'cep');
        if (!cepEl) { return; }
        var limpo = cepEl.value.replace(/\D/g, '');
        if (limpo.length !== 8) { return; }

        grupo.classList.add('cep-carregando');

        fetch('https://viacep.com.br/ws/' + limpo + '/json/')
            .then(function (r) { return r.json(); })
            .then(function (d) {
                grupo.classList.remove('cep-carregando');
                if (d.erro) {
                    erro(grupo, 'CEP não encontrado. Confira os números ou preencha o endereço manualmente.');
                    return;
                }
                definir(grupo, 'rua', d.logradouro);
                definir(grupo, 'bairro', d.bairro);
                definir(grupo, 'cidade', d.localidade);
                definir(grupo, 'uf', d.uf);
                definir(grupo, 'lat', '');
                definir(grupo, 'lng', '');
                var numero = campo(grupo, 'numero');
                if (numero) { numero.focus(); }
                agendarGeocode(grupo);
            })
            .catch(function () {
                grupo.classList.remove('cep-carregando');
                erro(grupo, 'Não foi possível consultar o CEP agora. Preencha o endereço manualmente.');
            });
    }

    function iniciar() {
        document.querySelectorAll('[data-cep-group]').forEach(function (grupo) {
            var cep = campo(grupo, 'cep');
            if (cep) {
                cep.addEventListener('input', function () {
                    if (cep.value.replace(/\D/g, '').length === 8) { pesquisar(grupo); }
                });
                cep.addEventListener('blur', function () { pesquisar(grupo); });
            }
            var numero = campo(grupo, 'numero');
            if (numero) { numero.addEventListener('blur', function () { agendarGeocode(grupo); }); }
        });
    }

    document.addEventListener('DOMContentLoaded', iniciar);
})();
