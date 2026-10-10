function pesquisarCep(cep) {
    var cepLimpo = cep.replace(/\D/g, '');

    if (cepLimpo != "") {
        var validacep = /^[0-9]{8}$/;

        if (validacep.test(cepLimpo)) {
            var campoRua = document.getElementById('rua');
            var campoBairro = document.getElementById('bairro');
            var campoCidade = document.getElementById('cidade');
            var campoUf = document.getElementById('uf');

            if (campoRua) campoRua.value = "...";
            if (campoBairro) campoBairro.value = "...";
            if (campoCidade) campoCidade.value = "...";
            if (campoUf) campoUf.value = "...";

            fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`)
                .then(response => response.json())
                .then(dados => {
                    if (!("erro" in dados)) {
                        if (campoRua) campoRua.value = dados.logradouro;
                        if (campoBairro) campoBairro.value = dados.bairro;
                        if (campoCidade) campoCidade.value = dados.localidade;
                        if (campoUf) campoUf.value = dados.uf;
                        var campoNumero = document.getElementById('numero');
                        if (campoNumero) campoNumero.focus();
                    } else {
                        alert("CEP não encontrado.");
                        limparCamposCep();
                    }
                })
                .catch(error => {
                    alert("Erro ao buscar o CEP.");
                    limparCamposCep();
                });
        } else {
            alert("Formato de CEP inválido.");
            limparCamposCep();
        }
    }
}

function limparCamposCep() {
    var campoRua = document.getElementById('rua');
    var campoBairro = document.getElementById('bairro');
    var campoCidade = document.getElementById('cidade');
    var campoUf = document.getElementById('uf');

    if (campoRua) campoRua.value = "";
    if (campoBairro) campoBairro.value = "";
    if (campoCidade) campoCidade.value = "";
    if (campoUf) campoUf.value = "";
}

document.addEventListener('DOMContentLoaded', function () {
    var campoCep = document.getElementById('cep');
    if (campoCep) {
        campoCep.addEventListener('blur', function () {
            pesquisarCep(this.value);
        });
    }
});