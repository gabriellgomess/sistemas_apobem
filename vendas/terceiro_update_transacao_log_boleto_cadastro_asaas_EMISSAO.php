<?php 

header("Access-Control-Allow-Origin: *");
include("../connect.php");

$dueDate = $_POST["dueDate"];
$cpfCnpj = $_POST["cpfCnpj"];
$vendas_id = $_POST["vendas_id"];
$userid = $_POST["userid"];
$jsonReponse = $_POST["jsonReponse"];
$idTransacao = $_POST["idTransacao"];
$idAsaas =  $_POST["idAsaas"];

$id_boleto =  $_POST["id_boleto"];
$invoiceUrl =  $_POST["invoiceUrl"];
$bankSlipUrl =  $_POST["bankSlipUrl"];
$invoiceNumber =  $_POST["invoiceNumber"];
$username = $_POST["username"];
$value = $_POST["value"];
$status = "Emitido o boleto";



     // ATUALIZADA A TRANSAÇÃO COM O ID DO USUARIO NO ASSAS, QUE NAO EXISTIA AO CRIAR A TRANSAÇÃO
    $queryLogTransacao = "UPDATE sys_vendas_transacoes_boleto SET id_boleto = '". $id_boleto ."', customer = '". $idAsaas . "', status = '" . $status ."',  dueDate ='" . $dueDate . "' , username = '". $username . "' , invoiceUrl = '".$invoiceUrl . "', bankSlipUrl = '". $bankSlipUrl . "', invoiceNumber = '".$invoiceNumber . "',
    value = ". $value. " WHERE transacao_id = " . $idTransacao . ";";

    $result_transaction = mysql_query($queryLogTransacao);
    

    //AO CONTRARIO DA TRANSAÇÃO OS LOGS, TERAM MAIS DE UM, TODOS REFERENTE A APENAS UMA TRANSAÇAO, MAS CADA UM COM RETORNO DA API DO ASAAX DIFERENTE
    $queryLog = "INSERT INTO sys_vendas_transacoes_boleto_log (transacao_id, vendas_id, user_id, cliente_cpf, data, erro_cod, status, response_json) 
    VALUES ( " . $idTransacao ." , " . $vendas_id .",". $userid. ", '". $cpfCnpj ."', NOW(), '', '', '" . $jsonReponse . "' );";

    echo "querytransacao -" . $queryLogTransacao . "<br>";
    echo "querytransacaoLog -" . $queryLog . "<br>"; 

    $result_transaction_log = mysql_query($queryLog);
    $log_id = mysql_insert_id($con);

    echo $transacao_id;


?>