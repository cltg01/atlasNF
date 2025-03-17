<?php
include "conexao.php"; 


// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Captura os dados do formulário
$filial = $_POST['filial'] ?? '';
$codigo_cliente = $_POST['codigo_cliente'] ?? '';
$cnpj_cpf_cliente = $_POST['cnpj_cpf_cliente'] ?? '';
$razao_social = $_POST['razao_social'] ?? '';
$transportadora = $_POST['transportadora'] ?? '';
$cnpj_transportadora = $_POST['cnpj_transportadora'] ?? '';
$numero_nota = $_POST['numero_nota'] ?? '';
$dataHora = $_POST['dataHora'] ?? date('Y-m-d H:i:s');
$boleto_entregue = $_POST['boleto_entregue'] ?? '';

// Diretório de upload
$diretorio_upload = "uploads/";
if (!is_dir($diretorio_upload)) {
    mkdir($diretorio_upload, 0777, true);
}

// Upload da Foto da Nota Fiscal
$fotoNota = "";
if (!empty($_FILES['fotoNota']['name'])) {
    $extensaoNota = pathinfo($_FILES['fotoNota']['name'], PATHINFO_EXTENSION);
    $fotoNota = $diretorio_upload . "nota_" . date('Ymd_His') . "." . $extensaoNota;
    move_uploaded_file($_FILES['fotoNota']['tmp_name'], $fotoNota);
}
// Verifica se campos obrigatórios estão vazios
if (empty($filial) || empty($codigo_cliente) || empty($cnpj_cpf_cliente) || empty($razao_social) || empty($transportadora) || empty($numero_nota)) {
    die("Erro: Todos os campos obrigatórios devem ser preenchidos.");
}

// Insere os dados no banco de dados
$sql = "INSERT INTO entregas (filial, codigo_cliente, razao_social, transportadora, cnpj_transportadora, cnpj_cpf_cliente, numero_nota, foto_nota, data_hora, boleto_entregue)
        VALUES ('$filial', '$codigo_cliente', '$razao_social', '$transportadora', '$cnpj_transportadora', '$cnpj_cpf_cliente', '$numero_nota', '$fotoNota', '$dataHora', '$boleto_entregue')";

if ($conn->query($sql) === TRUE) {
    // Redireciona para evitar reenvio do formulário
    header("Location: sucesso.php");
    exit();
} else {
    echo "Erro ao salvar: " . $conn->error;
}

$conn->close();
?>
