<?php
// Configurações de conexão
$db_ip = "10.100.0.22";
$db_username = "root";
$db_password = "Theredpil2001";
$db_name = "sistema";

// Tentativa de conexão com o banco de dados usando PDO
try {
    $conn = new PDO("mysql:host=$db_ip;dbname=$db_name;charset=utf8", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conectado com sucesso";
} catch(PDOException $e) {
    echo "Falha na conexão: " . $e->getMessage();
}



?>
