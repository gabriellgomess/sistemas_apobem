<?php
include("../../connect.php");

// Define o arquivo de log no mesmo diretório
$log_file = "/var/www/html/sistema/sistema/vendas/blocos_seguros/log_transacoes.txt";

// Função para gravar log
function gravaLog($mensagem)
{
	global $log_file;
	$data = date('Y-m-d H:i:s');
	file_put_contents($log_file, "[$data] " . $mensagem . "\n", FILE_APPEND);
}

// Debug dos valores recebidos
gravaLog("Debug atualiza_transacao.php - POST recebido: " . print_r($_POST, true));
gravaLog("transaction_id: " . $_POST['transaction_id']);
gravaLog("transaction_nit: " . $_POST['transaction_nit']);
gravaLog("transacao_status: " . $_POST['transacao_status']);
gravaLog("transacao_ids: " . $_POST['transacao_ids']);

$transaction_id = $_POST['transaction_id'];
$transaction_nit = $_POST['transaction_nit'];
$transacao_status = $_POST['transacao_status'];
$transacao_ids = $_POST['transacao_ids'];
$transacao_data = date('Y-m-d');

if ($transacao_status == "CON") {
	gravaLog("Entrando no bloco de confirmação da transação");
	$array_ids = explode(',', $transacao_ids);
	foreach ($array_ids as $transacao_id) {
		gravaLog("Processando transacao_id: " . $transacao_id);

		$sql_select = "SELECT vendas_valor FROM sys_vendas_transacoes_seg 
						INNER JOIN sys_vendas_seguros ON transacao_id_venda = vendas_id 
						WHERE transacao_id = '" . $transacao_id . "'";
		gravaLog("SQL Select: " . $sql_select);

		$result = mysql_query($sql_select) or die(mysql_error());
		$row = mysql_fetch_array($result);

		gravaLog("Valor encontrado: " . $row['vendas_valor']);

		$sql_update = "UPDATE sys_vendas_transacoes_seg 
			SET transacao_recebido = '1', 
			transacao_motivo = 0, 
			transacao_valor = '" . $row['vendas_valor'] . "', 
			transacao_data = '" . $transacao_data . "' 
			WHERE transacao_id = '" . $transacao_id . "'";
		gravaLog("SQL Update: " . $sql_update);

		$query = mysql_query($sql_update) or die(mysql_error());
		gravaLog("Update executado com sucesso");
	}
}

$sql_update_tef = "UPDATE sys_vendas_transacoes_tef
	SET transacao_nit = '" . $transaction_nit . "',
	transacao_status = '" . $transacao_status . "' 
	WHERE transacao_id = '" . $transaction_id . "'";
gravaLog("SQL Update TEF: " . $sql_update_tef);

$query = mysql_query($sql_update_tef) or die(mysql_error());
gravaLog("Transação TEF atualizada com sucesso");

echo "<br>Transação Atualizada com Sucesso<br>";
