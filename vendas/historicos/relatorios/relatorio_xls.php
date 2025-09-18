<meta charset="utf-8">
<style>
table {
  border-collapse: collapse;
  width: 100%;
}

table, th, td {
  border: 1px solid black;
}

th, td {
  text-align: left;
  padding: 2px;
}

tr:nth-child(even) {background-color: #f2f2f2;}
</style>
<?php 
require("../../connect.php");

if($_GET["data_inicio"] && $_GET["data_fim"])
{
	$data_intervalo = " AND DATE(data) BETWEEN DATE('".$_GET["data_inicio"]."') AND DATE('".$_GET["data_fim"]."') ";
	$filtros = "";
	$filtros .= $data_intervalo;

	$relatorio = consultaRelatorio($filtros);
}else{
	echo "As datas de início e fim são obrigatórias!";
}

date_default_timezone_set('America/Sao_Paulo');
$datetime = date("Y-m-d H:i:s");
$file = "relatorio_".$datetime.".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");

echo "<table>";
echo "<th>"."log_id"."</td>";
echo "<th>"."transacao_id"."</td>";
echo "<th>"."transacao_venda_id"."</td>";
echo "<th>"."transacao_username"."</td>";
echo "<th>"."clients_cpf"."</td>";
echo "<th>"."transacao_cartao_num"."</td>";
echo "<th>"."transacao_valor"."</td>";
echo "<th>"."data"."</td>";
echo "<th>"."erro_cod"."</td>";
echo "<th>"."status"."</td>";
echo "<th>"."authorization_number"."</td>";
echo "<th>"."esitef_usn"."</td>";
echo "<th>"."host_usn"."</td>";
echo "<th>"."tid"."</td>";

//echo "<th>"."response_json"."</td>";

//echo "<th>"."user_id"."</td>";
//echo "<th>"."transacao_nit"."</td>";
//echo "<th>"."transacao_agendamento_sid"."</td>";
//echo "<th>"."transacao_agendamento_seid"."</td>";
//echo "<th>"."transacao_merchant_usn"."</td>";
//echo "<th>"."transacao_authorizer_id"."</td>";
//echo "<th>"."transacao_cliente_cpf"."</td>";
//echo "<th>"."transacao_username"."</td>";
//echo "<th>"."transacao_user_id"."</td>";
//echo "<th>"."transacao_token"."</td>";
//echo "<th>"."transacao_status"."</td>";
//echo "<th>"."transacao_authorizer_message"."</td>";
//echo "<th>"."transacao_data"."</td>";
//echo "<th>"."transacao_data_confirmacao"."</td>";
//echo "<th>"."transacao_dia_debito"."</td>";
//echo "<th>"."transacao_tipo_plano"."</td>";
//echo "<th>"."transacao_cartao_adm"."</td>";
//echo "<th>"."transacao_cartao_band"."</td>";
//echo "<th>"."transacao_cartao_cvv"."</td>";
//echo "<th>"."transacao_cartao_validade_mes"."</td>";
//echo "<th>"."transacao_cartao_validade_ano"."</td>";

foreach($relatorio as $row)
{
	echo "<tr>";
	echo "<td>".$row->log_id."</td>";
	echo "<td>".$row->transacao_id."</td>";
	if($row->transacao_venda_id)
	{
		echo "<td>".$row->transacao_venda_id."</td>";
	}else{
		echo "<td>";

			$vendas = encontraVendasPossiveis($row->clients_cpf, $row->transacao_valor, $row->data);
			if($vendas){				
				foreach ($vendas as $venda)
				{
				 	echo "<br>".$venda->vendas_id;
				}
			}else{
				echo "Não existe nenhuma venda para este CPF, com este valor, nesta data.";
			}
		echo "</td>";
	}

	echo "<td>".$row->transacao_username."</td>";
	echo "<td>".$row->clients_cpf."</td>";
	echo "<td>".$row->transacao_cartao_num."</td>";
	echo "<td>".$row->transacao_valor."</td>";
	echo "<td>"._datetimeDB_to_datetimeBR($row->data)."</td>";
	echo "<td>".$row->erro_cod."</td>";
	echo "<td>".$row->status."</td>";
//### JSON ###
	$jsodec = json_decode($row->response_json, true);	

	echo "<td>".$jsodec['pre_authorization']['authorization_number']."</td>";	
	echo "<td>".$jsodec['pre_authorization']['esitef_usn']."</td>";
	echo "<td>".$jsodec['pre_authorization']['host_usn']."</td>";
	echo "<td>".$jsodec['pre_authorization']['tid']."</td>";
	
	//echo "<td>".jsonToDebug($row->response_json)."</td>";
//### JSON ###

	// echo "<td>".$row->user_id."</td>";
	// echo "<td>".$row->transacao_nit."</td>";
	// echo "<td>".$row->transacao_agendamento_sid."</td>";
	// echo "<td>".$row->transacao_agendamento_seid."</td>";
	// echo "<td>".$row->transacao_merchant_usn."</td>";
	// echo "<td>".$row->transacao_authorizer_id."</td>";
	// echo "<td>".$row->transacao_cliente_cpf."</td>";
	// echo "<td>".$row->transacao_user_id."</td>";
	// echo "<td>".$row->transacao_token."</td>";
	// echo "<td>".$row->transacao_status."</td>";
	// echo "<td>".$row->transacao_authorizer_message."</td>";
	// echo "<td>".$row->transacao_data."</td>";
	// echo "<td>".$row->transacao_data_confirmacao."</td>";
	// echo "<td>".$row->transacao_dia_debito."</td>";
	// echo "<td>".$row->transacao_tipo_plano."</td>";
	// echo "<td>".$row->transacao_cartao_adm."</td>";
	// echo "<td>".$row->transacao_cartao_band."</td>";
	// echo "<td>".$row->transacao_cartao_cvv."</td>";
	// echo "<td>".$row->transacao_cartao_validade_mes."</td>";
	// echo "<td>".$row->transacao_cartao_validade_ano."</td>";
	echo "</tr>";
}
echo "</table>";

?>

<?php
// foreach ($transacoes as $transacao):
// 	echo $transacao->transacao_id."<br>";
// 	echo $transacao->transacao_nit."<br>";
// 	echo $transacao->transacao_agendamento_sid."<br>";
// 	echo $transacao->transacao_agendamento_seid."<br>";
// 	echo $transacao->transacao_merchant_usn."<br>";
// 	echo $transacao->transacao_authorizer_id."<br>";
// 	echo $transacao->transacao_venda_id."<br>";
// 	echo $transacao->transacao_cliente_cpf."<br>";
// 	echo $transacao->transacao_username."<br>";
// 	echo $transacao->transacao_user_id."<br>";
// 	echo $transacao->transacao_token."<br>";
// 	echo $transacao->transacao_status."<br>";
// 	echo $transacao->transacao_authorizer_message."<br>";
// 	echo $transacao->transacao_data."<br>";
// 	echo $transacao->transacao_data_confirmacao."<br>";
// 	echo $transacao->transacao_dia_debito."<br>";
// 	echo $transacao->transacao_valor."<br>";
// 	echo $transacao->transacao_tipo_plano."<br>";
// 	echo $transacao->transacao_cartao_adm."<br>";
// 	echo $transacao->transacao_cartao_band."<br>";
// 	echo $transacao->transacao_cartao_cvv."<br>";
// 	echo $transacao->transacao_cartao_num."<br>";
// 	echo $transacao->transacao_cartao_validade_mes."<br>";
// 	echo $transacao->transacao_cartao_validade_ano."<br>";
// 	echo "<br>";
// endforeach;
?>

<?php
function consultaRelatorio($filtros)
{
	$sql="SELECT * FROM sys_vendas_transacoes_tef_log 
		  INNER JOIN sys_vendas_transacoes_tef ON sys_vendas_transacoes_tef_log.transacao_id = sys_vendas_transacoes_tef.transacao_id
		  WHERE 1=1 ".$filtros."
		  ORDER BY log_id;";
		  //echo $sql;
	$result=mysql_query($sql);
	while ($row = mysql_fetch_object($result))
	{
		$obj[]=$row;
	}
	return $obj;
}

function encontraVendasPossiveis($clients_cpf, $transacao_valor, $data)
{
	$sql="SELECT * FROM sys_vendas_seguros
		  WHERE cliente_cpf = '".$clients_cpf. "' AND vendas_valor = '".$transacao_valor."' AND DATE(vendas_dia_venda) = DATE('".$data."');";
	$result=mysql_query($sql);
	while ($row = mysql_fetch_object($result))
	{
		$obj[]=$row;
	}
	return $obj;
}

function consultaTransacoesLogs()
{
	$sql="SELECT * FROM sys_vendas_transacoes_tef_log LIMIT 0,10;";
	$result=mysql_query($sql);
	while ($row = mysql_fetch_object($result))
	{
		$obj[]=$row;
	}
	return $obj;
}

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

function jsonToDebug($jsonText = '')
{
    $arr = json_decode($jsonText, true);
    $html = "";
    if ($arr && is_array($arr)) {
        $html .= _arrayToHtmlTableRecursive($arr);
    }
    return $html;
}

function _arrayToHtmlTableRecursive($arr) {
    $str = "<table class='json_table'><tbody>";
    foreach ($arr as $key => $val) {
        $str .= "<tr>";
        $str .= "<td><strong>$key</strong></td>";
        $str .= "<td>";
        if (is_array($val)) {
            if (!empty($val)) {
                $str .= _arrayToHtmlTableRecursive($val);
            }
        } else {
            $str .= "$val";
        }
        $str .= "</td></tr>";
    }
    $str .= "</tbody></table>";

    return $str;
}

function _datetimeDB_to_datetimeBR($datetimeDb) {
	$datetimeDb = explode(" ", $datetimeDb);
	return implode("/",array_reverse(explode("/", str_replace("-","/",$datetimeDb[0]))))." ".$datetimeDb[1];
}
?>

























