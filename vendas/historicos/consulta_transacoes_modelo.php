<?php 
echo "iniciando...<br>";
$transacoes = consultaTransacoes();

foreach ($transacoes as $transacao): ?>
<?php 
	echo $transacao->transacao_id."<br>";
	echo $transacao->transacao_nit."<br>";
	echo $transacao->transacao_agendamento_sid."<br>";
	echo $transacao->transacao_agendamento_seid."<br>";
	echo $transacao->transacao_merchant_usn."<br>";
	echo $transacao->transacao_authorizer_id."<br>";
	echo $transacao->transacao_venda_id."<br>";
	echo $transacao->transacao_cliente_cpf."<br>";
	echo $transacao->transacao_username."<br>";
	echo $transacao->transacao_user_id."<br>";
	echo $transacao->transacao_token."<br>";
	echo $transacao->transacao_status."<br>";
	echo $transacao->transacao_authorizer_message."<br>";
	echo $transacao->transacao_data."<br>";
	echo $transacao->transacao_data_confirmacao."<br>";
	echo $transacao->transacao_dia_debito."<br>";
	echo $transacao->transacao_valor."<br>";
	echo $transacao->transacao_tipo_plano."<br>";
	echo $transacao->transacao_cartao_adm."<br>";
	echo $transacao->transacao_cartao_band."<br>";
	echo $transacao->transacao_cartao_cvv."<br>";
	echo $transacao->transacao_cartao_num."<br>";
	echo $transacao->transacao_cartao_validade_mes."<br>";
	echo $transacao->transacao_cartao_validade_ano."<br>";
	echo "<br>";
?>
<?php endforeach; ?>

<?php
function consultaTransacoes()
{
	$sql="SELECT * FROM sys_vendas_transacoes_tef WHERE transacao_venda_id != '0' LIMIT 0,10;";
	$result=mysql_query($sql);
	while ($row = mysql_fetch_object($result))
	{
		$obj[]=$row;
	}
	return $obj;
}
?>

























