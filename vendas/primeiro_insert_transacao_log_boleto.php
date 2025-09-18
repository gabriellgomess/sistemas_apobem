<?php 

header("Access-Control-Allow-Origin: *");
include("../connect.php");

$cpfCnpj = $_POST["cpfCnpj"];
$vendas_id = $_POST["vendas_id"];
$userid = $_POST["userid"];
$jsonReponse = $_POST["jsonReponse"];
$parcelas_a_pagar = $_POST["parcelas_a_pagar"];


// A TRANSAÇÃO É SOMENTE UMA, QUE APOS CRIADA, IRA SER "ATUALIZADA" COM OS PARAMETROS DA API ASAAS, JA OS LOGS, PODEM TER ATÉ 3 LOGS EM UMA UNICA TRANSAÇÃO
$queryLogTransacao = "INSERT INTO sys_vendas_transacoes_boleto (cliente_cpf, id_boleto, dateCreated, customer, paymentLink, dueDate, value, netValue, billingType, status, description, invoiceUrl, bankSlipUrl, invoiceNumber, vendas_id, parcelas_correspondentes) 
VALUES ('". $cpfCnpj  ."', '', NOW(), '', '', '', '', '', '', '', '', '', '', '', ". $vendas_id .", '".$parcelas_a_pagar."' );";

$result_transaction = mysql_query($queryLogTransacao);
$transacao_id = mysql_insert_id($con);

//AO CONTRARIO DA TRANSAÇÃO OS LOGS, TERAM MAIS DE UM, TODOS REFERENTE A APENAS UMA TRANSAÇAO, MAS CADA UM COM RETORNO DA API DO ASAAX DIFERENTE
$queryLog = "INSERT INTO sys_vendas_transacoes_boleto_log (transacao_id, vendas_id, user_id, cliente_cpf, data, erro_cod, status, response_json) 
VALUES ( " . $transacao_id ." , " . $vendas_id .",". $userid. ", '". $cpfCnpj ."', NOW(), '', '', '" . $jsonReponse . "' );";

$parcelas = explode(",", $parcelas_a_pagar);

foreach ($parcelas as $parcela) {
    $queryParcela = "UPDATE sys_vendas_transacoes_seg SET id_boleto = " . $transacao_id . " WHERE transacao_id = '" . $parcela . "'";
    $result_parcela = mysql_query($queryParcela);
}

// echo "querytransacao -" . $queryLogTransacao . "<br>";
// echo "querytransacaoLog -" . $queryLog . "<br>";

$result_transaction_log = mysql_query($queryLog);
$log_id = mysql_insert_id($con);
echo $transacao_id;


?>
