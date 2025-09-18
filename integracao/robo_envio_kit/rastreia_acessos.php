<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include 'connect.php';

// Defina o fuso horário correto
date_default_timezone_set('America/Sao_Paulo');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Erro ao decodificar JSON: ' . json_last_error_msg());
    }

    $dataHora = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['dataHora'])));

    // insert into sys_logs_acessos_kit
    $sql = "INSERT INTO sys_logs_acessos_kit (vendas_id, navegador, sistema, ip, cidade, pais, estado, dataHora) VALUES (:vendas_id, :navegador, :sistema, :ip, :cidade, :pais, :estado, :dataHora)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':vendas_id', $data['vendas_id']);
    $stmt->bindParam(':navegador', $data['navegador']);
    $stmt->bindParam(':sistema', $data['sistema']);
    $stmt->bindParam(':ip', $data['ip']);
    $stmt->bindParam(':cidade', $data['cidade']);
    $stmt->bindParam(':pais', $data['pais']);
    $stmt->bindParam(':estado', $data['estado']);
    $stmt->bindParam(':dataHora', $dataHora);

    if ($stmt->execute()) {
        echo json_encode(array('message' => 'Acesso registrado com sucesso!'));
    } else {
        throw new Exception('Erro ao registrar acesso!');
    }
} catch (Exception $e) {
    echo json_encode(array('message' => 'Erro: ' . $e->getMessage()));
}

?>
