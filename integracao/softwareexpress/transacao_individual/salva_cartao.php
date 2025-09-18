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

$cpf = $_POST['cpf'];
$venda_id = $_POST['venda_id'];
$numero_cartao = $_POST['card_num'];
$validade_mes = $_POST['card_validade_mes'];
$validade_ano = $_POST['card_validade_ano'];
$nome_cartao = $_POST['card_adm'];
$cartao_adm = $_POST['card_adm'];
$cvv = $_POST['cvv'];
$salva_cartao_principal = $_POST['save_card_future'];
$salva_cartao_secundario = $_POST['save_card_secondary'];

if($salva_cartao_principal == "1"){
    $salva_cartao = "UPDATE sys_vendas_seguros SET vendas_cartao_num = '$numero_cartao', vendas_cartao_validade_mes = '$validade_mes', vendas_cartao_validade_ano = '$validade_ano', vendas_cartao_cvv = '$cvv' WHERE cliente_cpf = '$cpf' AND vendas_id = '$venda_id'";
    $insere = mysqli_query($con, $salva_cartao) or die(mysqli_error($con));
    echo $salva_cartao;
}else{
    if($salva_cartao_secundario == "1"){
        $salva_cartao_secundario = "INSERT INTO sys_vendas_transacoes_tef_cartoes_secundarios (cpf, cartao_numero, cartao_validade_mes, cartao_validade_ano, cartao_adm, cartao_cvv) VALUES ('$cpf', '$numero_cartao', '$validade_mes', '$validade_ano', '$cartao_adm', '$cvv')";
        $insere = mysqli_query($con, $salva_cartao_secundario) or die(mysqli_error($con));

        echo $salva_cartao_secundario;
    }else{
        echo "não salva nenhum cartao";
    }
}



?>