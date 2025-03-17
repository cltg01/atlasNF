<?php
include "conexao.php"; 

$nome = "Administrador";
$email = "admin@email.com";
$senha = password_hash("admin123", PASSWORD_DEFAULT); 
$tipo_usuario = "admin";

$sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nome, $email, $senha, $tipo_usuario);

if ($stmt->execute()) {
    echo "Usuário admin criado com sucesso!";
} else {
    echo "Erro ao criar usuário: " . $conn->error;
}
?>
