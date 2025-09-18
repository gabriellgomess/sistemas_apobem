<?php
// https://www.grupofortune.com.br/integracao/softwareexpress/atualizacao/portal/api-consulta-historico.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// Configurações do banco de dados
$path_includes = "/var/www/html/sistema/sistema/";
$arquivo_conect = $path_includes."connect_seguro.php";
include($arquivo_conect);

// Verifica se a conexão foi bem sucedida
if ($con->connect_error) {
    die("Falha na conexão com o banco de dados: " . $con->connect_error);
}

// Consulta a tabela "sys_historico_script_atualizacao"
$sql = "SELECT * FROM sys_historico_script_atualizacao";
$result = $con->query($sql);

// Cria um array para armazenar os resultados
$rows = array();

// Obtém os resultados da consulta e adiciona ao array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

// Retorna os resultados em formato JSON
header('Content-Type: application/json');
echo json_encode($rows);

// Fecha a conexão com o banco de dados
$con->close();
?>


