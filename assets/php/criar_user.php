<?php
include 'conexao.php'; // Garante que $conn está sendo carregado
session_start();

// Verifica se a conexão está funcionando
if (!$conn) {
    die("Erro: Conexão com o banco de dados falhou.");
}
if (!isset($_SESSION["id"]) || $_SESSION["tipo_usuario"] != "admin") {
    header("Location: /../login.html");
    exit();
}
// Processa o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografando a senha
    $tipo = $_POST['tipo_usuario'];
    // Garantir que o tipo de usuário seja válido
    $tipo = ($_POST['tipo_usuario'] == "admin") ? "admin" : "comum";

    // Comando SQL correto
    $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, ?)";

    // Prepara a consulta
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    // Vincula os parâmetros corretamente
    $stmt->bind_param("ssss", $nome, $email, $senha, $tipo);
    
    // Executa e verifica se deu certo
    if ($stmt->execute()) {
        echo "Usuário criado com sucesso!";
    } else {
        echo "Erro ao criar usuário: " . $stmt->error;
    }

    // Fecha a conexão
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Usuário</title>
</head>
<body>
    <h2>Criar Usuário</h2>
    <form method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" required><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Senha:</label>
        <input type="password" name="senha" required><br>

        <label>Tipo de Usuário:</label>
        <select name="tipo_usuario" required>
            <option value="comum">Comum</option>
            <option value="admin">Admin</option>
        </select><br>

        <button type="submit">Criar</button>
    </form>
</body>
</html>