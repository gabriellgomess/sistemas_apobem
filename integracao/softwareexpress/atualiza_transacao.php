<?php
header("Access-Control-Allow-Origin: *");
include("variaveis_fixas.php");


$Arquivo_conect = "../connect_seguro.php";

include($Arquivo_conect);

$id_venda = $_POST['id_venda'];
$ordernumberUnder = explode("_", $_POST['order_number']);
$order_number = $ordernumberUnder[0];
$nit = $_POST['nit'];
$esitef_usn = $_POST['esitef_usn'];
$authorizer_id = $_POST['authorizer_id'];
$status = $_POST['status'];
$authorizer_date = $_POST['authorizer_date'];
$amount = $_POST['amount'];
$user_id = $_POST['user_id'];
$cpf = $_POST['cpf'];
$json = $_POST['data'];


$query1 = "UPDATE sys_vendas_transacoes_tef SET order_id = '$order_number', transacao_nit = '$nit', transacao_esitef_usn = '$esitef_usn', transacao_authorizer_id = '$authorizer_id', transacao_status = '$status', transacao_data_confirmacao = '$authorizer_date', transacao_valor = '$amount' WHERE transacao_id = $order_number";
$atualiza_transacao_tef = mysqli_query($con, $query1);

$query2 = "INSERT INTO sys_vendas_transacoes_tef_log (transacao_id, clients_cpf, data, status, esitef_usn, response_json) VALUES ($order_number, '$cpf','$authorizer_date', '$status', '$esitef_usn', '$json')";
$insere_log_transacao_tef = mysqli_query($con, $query2);




if($insere_log_transacao_tef){
    echo "Transação atualizada com sucesso!\n";
}else{
    echo "Erro ao atualizar transação!\n";
    echo "Erro: ".mysqli_error($con) . "\n";
    echo "Atualiza_tef: " . $insere_log_transacao_tef;
}


echo "QUERY 1 " . $query1 . "\n";
echo "QUERY 2 " . $query2;


// echo $order_number;
// echo $dados->order_id;



echo "ID VENDA: ".$id_venda . " \n ";
echo "ORDER_NUMBER " . $order_number . " \n ";
echo "NIT " . $nit . " \n ";
echo "ESITEF_USN " . $esitef_usn . " \n ";
echo "AUTHORIZER_ID " . $authorizer_id . " \n ";
echo "STATUS " . $status . " \n ";
echo "AUTHORIZER_DATE " . $authorizer_date . " \n ";
echo "AMOUNT " . $amount . " \n ";
echo "USER_ID " . $user_id . " \n ";
echo "CPF " . $cpf . " \n ";
echo "JSON " . $json . " \n ";



?>