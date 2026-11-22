(function () {
    'use strict';

    const elemento = document.getElementById('mapa-tornozeleira');

    if (!elemento) {
        return;
    }

    if (typeof L === 'undefined') {
        elemento.textContent = 'Não foi possível carregar o mapa.';
        return;
    }

    const latitude = Number(elemento.dataset.latitude);
    const longitude = Number(elemento.dataset.longitude);

    if (
        !Number.isFinite(latitude) ||
        !Number.isFinite(longitude) ||
        latitude < -90 ||
        latitude > 90 ||
        longitude < -180 ||
        longitude > 180
    ) {
        elemento.textContent = 'As coordenadas da tornozeleira são inválidas.';
        return;
    }

    // Cria o mapa centralizado na tornozeleira selecionada.
    const mapa = L.map(elemento, {
        zoomControl: true
    }).setView([latitude, longitude], 16);

    // Camada do OpenStreetMap.
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mapa);

    // Único marcador exibido neste mapa.
    const marcador = L.circleMarker(
        [latitude, longitude],
        {
            radius: 10,
            color: '#ffffff',
            weight: 3,
            fillColor: '#7a4dd8',
            fillOpacity: 1
        }
    ).addTo(mapa);

    marcador.bindPopup('Tornozeleira monitorada').openPopup();

    // Corrige o dimensionamento caso o mapa seja carregado
    // antes de o navegador terminar de organizar o layout.
    setTimeout(function () {
        mapa.invalidateSize();
    }, 200);

})();