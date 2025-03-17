document.addEventListener("DOMContentLoaded", function () {
    if (sessionStorage.getItem("form_recarregado")) {
        document.getElementById("formEntrega").reset(); // Reseta o formulário
        sessionStorage.removeItem("form_recarregado");
    }
});
// Impede que os campos permaneçam preenchidos ao voltar no navegador
window.addEventListener("pageshow", function (event) {
    if (event.persisted || window.performance && window.performance.navigation.type === 2) {
        document.getElementById("formEntrega").reset();
        document.querySelectorAll("#formEntrega input[disabled]").forEach(input => {
            input.removeAttribute("disabled");
            input.value = "";
            input.setAttribute("disabled", "true");
        });
    }
});
// Força o refresh ao sair e voltar para a página
window.addEventListener("beforeunload", function () {
    sessionStorage.setItem("form_recarregado", "true");
});
document.getElementById('formEntrega').addEventListener('submit', function(event) {
    let dataHoraLocal = new Date();
    dataHoraLocal.setHours(dataHoraLocal.getHours() - 3); // Ajusta UTC-3
    document.getElementById('dataHora').value = dataHoraLocal.toISOString().slice(0, 19).replace('T', ' ');

    // Seleciona os campos a serem validados
    let filial = document.getElementById('filial').value.trim();
    let razaoSocial = document.getElementById('razao_social').value.trim();
    let codigoCliente = document.getElementById('codigo_cliente').value.trim();

    // Verifica se algum campo está vazio
    if (filial === '' || razaoSocial === '' || codigoCliente === '') {
        alert("Os campos Filial, Razão Social e Código do Cliente são obrigatórios!");
        event.preventDefault(); // Impede o envio do formulário
    }
});

const transportadoras = {};
// Função para carregar o arquivo TXT
async function carregarTransportadoras() {
    try {
        const response = await fetch('transportadoras.txt');
        const data = await response.text();
        
        const linhas = data.split('\n');
        const select = document.getElementById('transportadora');

        linhas.forEach(linha => {
            const [nome, cnpj] = linha.split(';');
            if (nome && cnpj) {
                transportadoras[nome.trim()] = cnpj.trim();

                const option = document.createElement('option');
                option.value = nome.trim();
                option.textContent = nome.trim();
                select.appendChild(option);
            }
        });
    } catch (error) {
        console.error('Erro ao carregar transportadoras:', error);
    }
}

// Preencher o campo CNPJ ao selecionar
function preencherCNPJ() {
    const select = document.getElementById('transportadora');
    const cnpjInput = document.getElementById('cnpj_transportadora');
    cnpjInput.value = transportadoras[select.value] || '';
}

// Carregar as transportadoras ao abrir a página
carregarTransportadoras();
let clientes = [];

function carregarArquivo() {
    fetch('base_atlas.txt') // Substitua 'dados.txt' pelo caminho correto do seu arquivo
        .then(response => response.text())
        .then(data => {
            clientes = data.trim().split('\n').map(linha => {
                const [codigo_cliente, razao_social, filial, cnpj, cpf] = linha.split(';');
                return { codigo_cliente, razao_social, filial, cnpj: cnpj.trim(), cpf: cpf ? cpf.trim() : '' };
            });
        })
        .catch(error => console.error('Erro ao carregar o arquivo:', error));
}

document.getElementById('cnpjCpf').addEventListener('input', preencherCampos);

function preencherCampos() {
    const input = document.getElementById('cnpjCpf').value.trim().replace(/\D/g, ''); // Remove caracteres não numéricos
    
    // Se o campo estiver vazio, limpa os dados e para a execução
    if (input === '') {
        limparCampos();
        return;
    }

    // Só busca os dados se o input tiver exatamente 11 (CPF) ou 14 (CNPJ) dígitos
    if (input.length === 11 || input.length === 14) {
        const cliente = clientes.find(c => c.cnpj === input || c.cpf === input);
        if (cliente) {
            document.getElementById('filial').value = cliente.filial;
            document.getElementById('codigo_cliente').value = cliente.codigo_cliente;
            document.getElementById('razao_social').value = cliente.razao_social;
        } else {
            limparCampos(); // Se não encontrar o cliente, limpa os campos
        }
    } else {
        limparCampos(); // Se os dígitos forem diferentes de 11 ou 14, limpa os campos
    }
}

function limparCampos() {
    document.getElementById('filial').value = '';
    document.getElementById('codigo_cliente').value = '';
    document.getElementById('razao_social').value = '';
}

carregarArquivo();
