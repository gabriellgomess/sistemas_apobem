<?php 
require("../../connect.php");
$response->erro = 0;

if($_GET['transacao_id']){
	$transacao_id = $_GET['transacao_id'];
}else{
	$response->erro = 1;
	$response->mensagem = "transacao_id não enviado. ";
}
if($_GET['venda_id']){
	$venda_id = $_GET['venda_id'];
}else{
	$response->erro = 1;
	$response->mensagem .= "venda_id não enviado. ";
}

if($response->erro == 0)
{
	$sql = "UPDATE sys_vendas_transacoes_tef SET transacao_venda_id = '".$venda_id."' WHERE transacao_id='".$transacao_id."' AND transacao_venda_id = 0";
	if(mysql_query($sql))
	{
		$response->erro = 0;
		$response->mensagem = "Transação ".$transacao_id." vinculada a venda ".$venda_id." com sucesso.";
	}else{
		$response->erro = 1;
		$response->mensagem = "Não foi possível vincular trnsação. Erro: ".mysql_error();
	}
}

echo json_encode($response);
?>