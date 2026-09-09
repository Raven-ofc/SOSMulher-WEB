// Impressão das páginas que oferecem salvar como PDF.
document.addEventListener('click', function (event) {
    if (event.target.closest('[data-print]')) {
        window.print();
    }
});
