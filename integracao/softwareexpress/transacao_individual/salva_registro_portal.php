<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include("../get_erros.php");
include("../variaveis_fixas.php");
include("../../../sistema/sistema/connect_seguro.php");

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$venda_id = $_POST['venda_id'];
$cpf = $_POST['cpf'];
$tipo = $_POST['tipo'];
$valor = $_POST['valor'];

if($tipo == "CARTAO"){
    $salva_registro = "INSERT INTO sys_vendas_transacoes_portal (venda_id, cpf, tipo, valor, data) VALUES ('$venda_id', '$cpf', '$tipo', '$valor', NOW())";
    $insere = mysqli_query($con, $salva_registro) or die(mysqli_error($con));
    echo "Registro de pagamento salvo com sucesso";
}else if($tipo == "BOLETO GERADO"){
    $salva_registro = "INSERT INTO sys_vendas_transacoes_portal (venda_id, cpf, tipo, valor, data) VALUES ('$venda_id', '$cpf', '$tipo', '$valor', NOW())";
    $insere = mysqli_query($con, $salva_registro) or die(mysqli_error($con));
    echo "Registro de boleto gerado salvo com sucesso";
}



?>