<?php
include 'conexao.php'; // Conexão com o banco de dados   

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id"]) || $_SESSION["tipo_usuario"] != "admin") {
    header("Location: ../login.html");
    exit();
}


// Inicializa variáveis
$result = null;
$transportadora_selecionada = $_POST['transportadora'] ?? '';
$numero_nota = $_POST['numero_nota'] ?? '';

// Se o botão "Filtrar" for pressionado, executa a busca
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filtrar'])) {
    $data_inicial = !empty($_POST['data_inicial']) ? $_POST['data_inicial'] . " 00:00:00" : null;
    $data_final = !empty($_POST['data_final']) ? $_POST['data_final'] . " 23:59:59" : null;
    $transportadora = $_POST['transportadora'] ?? '';
    $numero_nota = $_POST['numero_nota'] ?? '';

    // Monta a query dinâmica
    $sql = "SELECT transportadora, cnpj_transportadora, filial, numero_nota, codigo_cliente, boleto_entregue, foto_nota, data_hora 
            FROM entregas WHERE 1=1";

    $params = [];
    $types = "";

    if ($data_inicial && $data_final) {
        $sql .= " AND data_hora BETWEEN ? AND ?";
        $params[] = $data_inicial;
        $params[] = $data_final;
        $types .= "ss";
    }

    if (!empty($transportadora)) {
        $sql .= " AND transportadora = ?";
        $params[] = $transportadora;
        $types .= "s";
    }
    $numero_nota = $_POST['numero_nota'] ?? '';

    if (!empty($numero_nota)) {
        $sql .= " AND numero_nota = ?";
        $params[] = $numero_nota;
        $types .= "s";
    }

    $sql .= " ORDER BY data_hora DESC";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Salva os dados na sessão e redireciona
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION['resultados'] = $result->fetch_all(MYSQLI_ASSOC);
    $_SESSION['transportadora_selecionada'] = $transportadora;
    $_SESSION['numero_nota_selecionado'] = $numero_nota;
    header("Location: relatorios.php");
    exit();
}

// Se o botão "Limpar" for pressionado, remove os resultados e recarrega a página
if (isset($_POST['limpar'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    unset($_SESSION['resultados']);
    unset($_SESSION['transportadora_selecionada']);
    unset($_SESSION['numero_nota_selecionado']);
    header("Location: relatorios.php");
    exit();
}

// Recupera os dados da sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dados = $_SESSION['resultados'] ?? [];
$transportadora_selecionada = $_SESSION['transportadora_selecionada'] ?? '';
$numero_nota_selecionado = $_SESSION['numero_nota_selecionado'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Relatório de Entregas">
    <title>Relatório de Entregas</title>
    <link rel="stylesheet" href="../css/style-relatorios.css">
    <link rel="icon" type="image/png" href="assets/imagens/icon-gtrack.png">
</head>

<body>
    <div class="container">
        <div class="logo">
            <a href="../../index.html"> <img src="" alt="Logo_atlasNF" width="300px"
                    height="90"></a>
        </div>

        <h2>Relatório de Entregas</h2>
        <div id="formulario">
            <!-- Formulário para Filtrar Data e Transportadora -->
            <form method="POST" id="form-inputs">
                <div id="head-filtro">
                    <div class="filtro-item">
                        <label for="data_inicial">Data Inicial:</label> <br>
                        <input type="date" name="data_inicial" id="data_inicial">
                    </div>
                    <div class="filtro-item">
                        <label for="data_final">Data Final:</label><br>
                        <input type="date" name="data_final" id="data_final">
                    </div>
                    <div class="filtro-item">
                        <label for="transportadora">Transportadora:</label>
                        <select name="transportadora" id="transportadora">
                            <option value="">Selecione</option>
                            <?php
                            $sql_transportadoras = "SELECT DISTINCT transportadora FROM entregas ORDER BY transportadora ASC";
                            $result_transportadoras = $conn->query($sql_transportadoras);
                            while ($row = $result_transportadoras->fetch_assoc()) {
                                $selected = ($row['transportadora'] == $transportadora_selecionada) ? 'selected' : '';
                                echo "<option value='{$row['transportadora']}' $selected>{$row['transportadora']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="filtro-item">
                        <label for="numero_nota">Número da Nota:</label>
                        <input type="text" name="numero_nota" id="numero_nota">
                    </div>

                    <div style="text-align: center; margin-top: 15px; box-shadow: none;">
                        <button type="submit" name="filtrar">Filtrar</button>
                        <button type="submit" name="limpar">Limpar</button>
                      

                    </div>
                </div>
            </form>

            <?php if (!empty($dados)): ?>
                <form action="exportar.php" method="POST">

                    <button type="submit" name="exportar" style="margin-top:20px;">Exportar</button>
                </form>
            <?php endif; ?>
            
            <?php if ($_SESSION["tipo_usuario"] == "admin"): ?>
    <div style="text-align: center; margin-top: 15px;">
        <a href="criar_user.php">
            <button type="button">Novo Usuário</button>
        </a>
    </div>
<?php endif; ?>

        </div>
        <table >
            <thead>
                <tr >
                    <th>Transportadora</th>
                    <th>CNPJ da Transportadora</th>
                    <th>Filial</th>
                    <th>Nota Fiscal</th>
                    <th>Código do Cliente</th>
                    <th>Boleto Entregue</th>
                    <th>Foto Nota</th>
                    <th >Data e Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($dados)) {
                    foreach ($dados as $row) {
                        echo "<tr id='scroll'>"
                            . "<td>" . htmlspecialchars($row['transportadora']) . "</td>"
                            . "<td>" . htmlspecialchars($row['cnpj_transportadora']) . "</td>"
                            . "<td>" . htmlspecialchars($row['filial']) . "</td>"
                            . "<td>" . htmlspecialchars($row['numero_nota']) . "</td>"
                            . "<td>" . htmlspecialchars($row['codigo_cliente']) . "</td>"
                            . "<td>" . htmlspecialchars($row['boleto_entregue']) . "</td>"
                            . "<td><a href='http://localhost/atlasNF/assets/php/" . htmlspecialchars($row['foto_nota']) . "' target='_blank'>Ver Nota</a></td>"
                            . "<td style='border-right: 1px solid rgb(219, 218, 218);'>" . htmlspecialchars($row['data_hora']) . "</td>"
                            . "</tr>";
                    }
                } else {
                    echo "<tr><td id='nao-encontrado' colspan='8'><span id='color'>Nenhum dado encontrado</span></td></tr>";
                }
                
                ?>
                 
            </tbody>
            
        </table>
        <button class="sair"> <a href="assets/php/logout.php">Sair</a></button>
    </div>
</body>

</html>