/* Gráficos da página de Estatísticas (Chart.js). Dados em <script type="application/json" id="dados-graficos">. */
(function () {
    'use strict';

    var bruto = document.getElementById('dados-graficos');
    if (!bruto) { return; }

    var dados = JSON.parse(bruto.textContent);
    var PALETA = ['#8e5be0', '#ff6a59', '#ffc64f', '#49b5ee', '#35b76d', '#f48fb1', '#80cbc4', '#a1887f'];
    var CORES = {
        status: ['#e5484d', '#f5a524', '#2ea36b'],
        gravidade: ['#e5484d', '#f5a524', '#2ea36b'],
        risco: ['#2ea36b', '#f5a524', '#e5484d'],
        bateria: ['#e5484d', '#f5a524', '#49b5ee', '#2ea36b']
    };

    function indisponivel(canvas) {
        var p = document.createElement('p');
        p.className = 'grafico-vazio';
        p.textContent = 'Não foi possível carregar o gráfico (verifique a conexão com a internet).';
        canvas.replaceWith(p);
    }

    function vazio(canvas) {
        var p = document.createElement('p');
        p.className = 'grafico-vazio';
        p.textContent = 'Sem dados para exibir.';
        canvas.replaceWith(p);
    }

    document.querySelectorAll('canvas[data-grafico]').forEach(function (canvas) {
        var chave = canvas.getAttribute('data-grafico');
        var tipo = canvas.getAttribute('data-tipo') || 'bar';
        var serie = dados[chave];

        if (typeof Chart === 'undefined') { indisponivel(canvas); return; }
        if (!serie || !serie.values || !serie.values.some(function (v) { return v > 0; })) { vazio(canvas); return; }

        var cores = CORES[chave] || PALETA;
        var colorir = function (n) { return serie.labels.map(function (_, i) { return cores[i % cores.length]; }).slice(0, n || serie.labels.length); };

        var circular = tipo === 'doughnut';
        var horizontal = tipo === 'barh';
        var linha = tipo === 'line';

        var conjunto = {
            label: canvas.getAttribute('data-rotulo') || 'Ocorrências',
            data: serie.values,
            backgroundColor: circular ? colorir() : (linha ? 'rgba(142,91,224,.15)' : (CORES[chave] ? colorir() : '#8e5be0')),
            borderColor: linha ? '#8e5be0' : (circular ? '#fff' : undefined),
            borderWidth: circular ? 2 : (linha ? 2 : 0),
            borderRadius: circular || linha ? 0 : 6,
            fill: linha,
            tension: 0.3,
            pointRadius: linha ? 2 : undefined
        };

        new Chart(canvas, {
            type: circular ? 'doughnut' : (linha ? 'line' : 'bar'),
            data: { labels: serie.labels, datasets: [conjunto] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: horizontal ? 'y' : 'x',
                cutout: circular ? '62%' : undefined,
                plugins: {
                    legend: { display: circular, position: 'bottom', labels: { boxWidth: 12, font: { family: 'Poppins' } } },
                    tooltip: { padding: 10 }
                },
                scales: circular ? {} : {
                    x: { grid: { display: horizontal }, ticks: { maxRotation: 0, autoSkip: true, font: { family: 'Poppins', size: 11 } }, beginAtZero: true },
                    y: { beginAtZero: true, ticks: { precision: 0, font: { family: 'Poppins', size: 11 } } }
                }
            }
        });
    });
})();
