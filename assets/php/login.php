<?php
session_start();
include "conexao.php"; // Certifique-se de que a conexão está funcionando corretamente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Verifica se o e-mail existe
    $sql = "SELECT id, nome, senha, tipo_usuario FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $usuario["senha"])) {
            $_SESSION["id"] = $usuario["id"];
            $_SESSION["nome"] = $usuario["nome"];
            $_SESSION["tipo_usuario"] = $usuario["tipo_usuario"];

            echo "✅ Login bem-sucedido! Redirecionando...";

            if ($usuario["tipo_usuario"] == "admin") {
                header("Location: relatorios.php");
                exit();
            } else {
                header("Location: ../../index.html");
                exit();
            }
            
        } else {
            echo "❌ Senha incorreta!";
        }
    } else {
        echo "❌ Usuário não encontrado!";
    }
} else {
    echo "Método de requisição inválido.";
}
?>
