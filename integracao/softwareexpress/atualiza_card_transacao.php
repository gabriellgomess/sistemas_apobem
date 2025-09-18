<?php

header("Access-Control-Allow-Origin: *");
include("variaveis_fixas.php");


$nit = $_POST['nit'];
$esitef_usn = $_POST['esitef_usn'];
$authorizer_id = $_POST['authorizer_id'];
$status = $_POST['status'];
$authorizer_date = $_POST['authorizer_date'];
$amount = $_POST['valor'];
$cpf = $_POST['cpf'];
$authorizer_message = $_POST['authorizer_message'];
$order_id = $_POST['order_id'];
$json = $_POST['json'];

$path_includes = "../var/www/html/sistema/sistema/";
$path_includes = "../../sistema/sistema/";
$Arquivo_conect = "connect_seguro.php";

include($path_includes.$Arquivo_conect);

$authorizer_date = $authorizer_date;
$date = explode("T", $authorizer_date);
$time = $date[1];
$date = $date[0];
$date = explode("/", $date);
$date = $date[2]."-".$date[1]."-".$date[0];
$authorizer_date = $date." ".$time.":00";

// echo "NIT: ".$nit . " \n ";
// echo "ESITEF_USN: ".$esitef_usn . " \n ";
// echo "AUTHORIZER_ID: ".$authorizer_id . " \n ";
// echo "STATUS: ".$status . " \n ";
// echo "AUTHORIZER_DATE: ".$authorizer_date . " \n ";
// echo "AMOUNT: ".$amount . " \n ";
// echo "CPF: ".$cpf . " \n ";
// echo "AUTHORIZER_MESSAGE: ".$authorizer_message . " \n ";
// echo "ORDER_ID: ".$order_id . " \n ";
// echo "JSON: ".json_encode($json)  . " \n ";


$insere_transaction_query ="INSERT INTO sys_vendas_transacoes_tef (
	transacao_nit,
	transacao_cliente_cpf,
	transacao_username,
	transacao_user_id,
	transacao_token,
	transacao_valor,
	transacao_venda_id,
	transacao_data,
	transacao_dia_debito,
	transacao_tipo_plano,
	transacao_cartao_adm,
	transacao_cartao_band,
	transacao_esitef_usn,
	transacao_authorizer_message,
	transacao_status)
	VALUES (
	'".$nit."',
	'".$cpf."',
	'integrador.automatico',
	'42',
	'EsearR31234fpssa0vfc9o',
	'".$amount."',
	'".$order_id."',
	'".$authorizer_date."',
	'".substr($authorizer_date, 0, 2)."',
	'".$row_venda['apolice_nome']."',
	'".$authorizer_id."',
	'".$authorizer_id."',
	'".$esitef_usn."',
	'".$authorizer_message."',
	'".$status."'
	);";
//echo "insere_transaction_query: <br>".$insere_transaction_query;
$result_transaction = mysqli_query($con, $insere_transaction_query);
$transaction_id = mysqli_insert_id($con);

$busca_dados = "SELECT transacao_id FROM sys_vendas_transacoes_tef WHERE transacao_nit = $nit";
$result_dados = mysqli_query($con, $busca_dados);
$row_dados = mysqli_fetch_array( $result_dados );

$insere_log_query ="INSERT INTO sys_vendas_transacoes_tef_log (
	transacao_id,
	user_id,
	clients_cpf,
	data,
	erro_cod,
	status,
	esitef_usn,
	response_json)
	VALUES (
	'".$row_dados['transacao_id']."',
	'42',
	'".$cpf."',
	'".$authorizer_date."',
	'".$authorizer_code."',
	'".$status."',
	'".$esitef_usn."',
	'".json_encode($transacao)."'
	);";
//echo "<br>insere_log_query: <br>".$insere_log_query;
$result_transaction = mysqli_query($con, $insere_log_query);
$transaction_id = mysqli_insert_id($con)

?>
	