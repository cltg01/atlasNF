<?php
$servername = "localhost";
$username = "teste";
$password = "teste";
$database = "controle_entregas_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
