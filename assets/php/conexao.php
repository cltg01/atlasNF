<?php
$servername = "localhost";
$username = "teste";
$password = "cleito";
$database = "controle_entregas";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
