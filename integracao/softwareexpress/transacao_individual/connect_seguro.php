<?php
// Configurações do banco de dados
$db_host = "10.100.0.22";
$db_user = "root";
$db_pass = "Theredpil2001";
$db_name = "sistema";

// Obtém o IP do cliente
$ip = $_SERVER["REMOTE_ADDR"];

// Conexão usando MySQLi
$con = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica se a conexão falhou
if ($con->connect_error) {
    die("Erro na conexão: " . $con->connect_error);
}

// Configura charset para evitar problemas com acentuação
$con->set_charset("utf8");

// Exemplo de fechamento da conexão (caso necessário em outro ponto do código)
// $con->close();
?>
