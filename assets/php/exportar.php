<?php
session_start();

if (!isset($_SESSION['resultados']) || empty($_SESSION['resultados'])) {
    die("Nenhum dado para exportar.");
}

$dados = $_SESSION['resultados'];

// Nome do arquivo Excel
$arquivo = "relatorio_entregas_" . date("Y-m-d") . ".xls";

// Configuração dos headers para download do Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$arquivo\"");
header("Pragma: no-cache");
header("Expires: 0");

// Saída do cabeçalho da tabela
echo "<table border='1'>";
echo "<tr>
        <th>Transportadora</th>
        <th>CNPJ da Transportadora</th>
        <th>Filial</th>
        <th>Nota Fiscal</th>
        <th>Código do Cliente</th>
        <th>Boleto Entregue</th>
        <th>Data e Hora</th>
      </tr>";

// Loop para inserir os dados
foreach ($dados as $row) {
    echo "<tr>
            <td>" . htmlspecialchars($row['transportadora']) . "</td>
            <td>" . htmlspecialchars($row['cnpj_transportadora']) . "</td>
            <td>" . htmlspecialchars($row['filial']) . "</td>
            <td>" . htmlspecialchars($row['numero_nota']) . "</td>
            <td>" . htmlspecialchars($row['codigo_cliente']) . "</td>
            <td>" . htmlspecialchars($row['boleto_entregue']) . "</td>
            <td>" . htmlspecialchars($row['data_hora']) . "</td>
          </tr>";
}

echo "</table>";
exit();
?>
