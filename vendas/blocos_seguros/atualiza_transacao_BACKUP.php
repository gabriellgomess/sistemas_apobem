<?php
include("../../connect.php");

$transaction_id = $_POST['transaction_id'];
$transaction_nit = $_POST['transaction_nit'];
$transacao_status = $_POST['transacao_status'];
$transacao_ids = $_POST['transacao_ids'];
$transacao_data = date('Y-m-d');

if($transacao_status == "CON"){	
	$array_ids = explode(',', $transacao_ids);
	foreach($array_ids as $transacao_id) {
		$result = mysql_query("SELECT vendas_valor FROM sys_vendas_transacoes_seg 
								INNER JOIN sys_vendas_seguros ON transacao_id_venda = vendas_id 
								WHERE transacao_id = '" . $transacao_id . "';") 
		or die(mysql_error());  
		$row = mysql_fetch_array( $result );
		
		$query = mysql_query("UPDATE sys_vendas_transacoes_seg 
		SET transacao_recebido = '1', 
		transacao_motivo = 0, 
		transacao_valor = '".$row['vendas_valor']."', 
		transacao_data = '".$transacao_data."' 
		WHERE transacao_id = '".$transacao_id."';") or die(mysql_error());
	}
}

$query = mysql_query("UPDATE sys_vendas_transacoes_tef
SET transacao_nit = '".$transaction_nit."',
transacao_status = '".$transacao_status."' 
WHERE transacao_id = '".$transaction_id."';") or die(mysql_error());
echo "<br>Transação Atualizada com Sucesso<br>";
?>
