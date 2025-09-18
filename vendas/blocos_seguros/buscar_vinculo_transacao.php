<?php
include("../../connect.php");

$vendas_id = $_GET['vendas_id'];
$cliente_cpf = $_GET['cliente_cpf'];
$vendas_valor = $_GET['vendas_valor'];

if($vendas_id && $cliente_cpf && $vendas_valor)
{
   $result = mysql_query("SELECT transacao_id
   FROM sys_vendas_transacoes_tef
   WHERE ( transacao_venda_id = '" . $vendas_id . "' OR transacao_cliente_cpf = '".$cliente_cpf."' ) AND transacao_valor = '".$vendas_valor."' AND transacao_status = 'VER'  ORDER BY transacao_id DESC LIMIT 0,1;")
   or die(mysqli_error());

   if(mysql_num_rows($result))
   {
	   $row = mysql_fetch_array($result);	   

	   $result_ina = mysql_query("SELECT transacao_id
	   FROM sys_vendas_transacoes_tef
	   WHERE ( transacao_venda_id = '" . $vendas_id . "' OR transacao_cliente_cpf = '".$cliente_cpf."' ) AND transacao_status = 'INA'  ORDER BY transacao_id DESC LIMIT 0,1;")
	   or die(mysqli_error());
	   $row_ina = mysql_fetch_array($result_ina);

		$resposta->erro = 0;
		
		if ($row_ina['transacao_id']){
		   $resposta->transacao_id = 0;
		   $resposta->mensagem = "Vinculo INATIVO encontrado.";
		}else{
		   $resposta->transacao_id = $row['transacao_id'];
		   $resposta->mensagem = "Vinculo ATIVO encontrado.";
		}
		
	   $resposta = json_encode($resposta);

	   echo $resposta;
   }else{
   	   $resposta->erro = 0;
	   $resposta->transacao_id = 0;
	   $resposta->mensagem = "Nao foi localizada transacao sem vinculo e com status VER para esta venda.";

	   $resposta = json_encode($resposta);

	   echo $resposta;
   }  

   
}else{
	$resposta->erro = 1;
	$resposta->transacao_id = 0;
	$resposta->mensagem = "Erro: Necessario o envio das variaveis vendas_id, cliente_cpf e vendas_valor.";

	$resposta = json_encode($resposta);

	echo $resposta;
}
?>
