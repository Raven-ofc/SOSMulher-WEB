/* Comportamentos globais do painel: imprimir e menu lateral em telas pequenas. */
document.addEventListener('click', function (event) {
    if (event.target.closest('[data-print]')) {
        window.print();
        return;
    }

    if (event.target.closest('[data-menu-toggle]')) {
        var aberto = document.body.classList.toggle('menu-aberto');
        var botao = document.querySelector('[data-menu-toggle]');
        if (botao) { botao.setAttribute('aria-expanded', aberto ? 'true' : 'false'); }
        return;
    }

    if (document.body.classList.contains('menu-aberto') &&
        (event.target.closest('[data-menu-fechar]') || event.target.closest('.admin-sidebar a'))) {
        document.body.classList.remove('menu-aberto');
        var b = document.querySelector('[data-menu-toggle]');
        if (b) { b.setAttribute('aria-expanded', 'false'); }
    }
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && document.body.classList.contains('menu-aberto')) {
        document.body.classList.remove('menu-aberto');
    }
});

window.addEventListener('resize', function () {
    if (window.innerWidth > 1000) { document.body.classList.remove('menu-aberto'); }
});
