<?php
while($row = mysql_fetch_array( $result )) {

	$vendas_consultor=$row["vendas_consultor"];
	$select_consultor= " AND vendas_consultor = " . $vendas_consultor . "";
	$vendas_unidade = $row["unidade"];
	$vendas_equipe = $row["equipe_id"];
	$consultor_situacao = $row["situacao"];
	$consultor_nivel = $row["nivel"];
	$consultor_empresa = $row["empresa"];
	if ($row['nivel'] == 3){$nivel = "consultor";}
	if ($row['nivel'] == 2){$nivel = "cordenador";}

	// Consulta a base de produção do mês corrente, do vendedor:
	$result_base_consultor = mysql_query("SELECT SUM(vendas_base_prod) AS total_base FROM sys_vendas 
	WHERE vendas_consultor = '" . $vendas_consultor . "' 
	AND (vendas_status = 8 OR vendas_status = 9)".$select_mes.";")
	or die(mysql_error());
	$row_base_consultor = mysql_fetch_array( $result_base_consultor );

	$result_regra_cms = mysql_query("SELECT * FROM sys_regras_cms 
	WHERE regras_cms_base_ini <= ".$row_base_consultor['total_base']." 
	AND regras_cms_base_fim >= '".$row_base_consultor['total_base']."' 
	AND (consultor_situacao LIKE '%,".$consultor_situacao.",%' OR consultor_situacao LIKE '0') 
	AND (consultor_empresa = '".$consultor_empresa."' OR consultor_empresa = '0') 
	AND (consultor_unidade LIKE '%,".$vendas_unidade.",%' OR consultor_unidade LIKE '0') 
	AND (consultor_equipe = '".$vendas_equipe."' OR consultor_equipe = '0') 
	ORDER BY regras_cms_ordem ASC 
	LIMIT 0, 1;")
	or die(mysql_error());
	$row_regra_cms = mysql_fetch_array( $result_regra_cms );

	$result_comissao_minima = mysql_query("SELECT meta_id, meta_nome, meta_valor1, meta_valor2, meta_nivel
		FROM sys_metas WHERE meta_nivel = '" . $row_user['nivel'] . "';") 
	or die(mysql_error());
	$row_comissao_minima = mysql_fetch_array( $result_comissao_minima );
	if( isset($row_comissao_minima['meta_valor1']) )
	{
		$comissao_minima = $row_comissao_minima['meta_valor1'];
	}else{
		$comissao_minima = 0;
	}

	echo "<tr><div align='left'><td style='border-bottom:2px solid #333;' width='3%'>";
	echo "<span style='color:#666666; font-size:8pt'>{$numero}</span></td><td style='border-bottom:2px solid #333;' width='30%'>";
if ($row["vendas_orgao"] == "Exercito"){
	echo $row['clients_nm']." </br>";
	echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']} | Matr.: {$row['clients_prec_cp']}</span></td><td style='border-bottom:2px solid #333;' width='12%'>";
}
else{
	if ($row['cliente_nome']){
		echo $row['cliente_nome']." </br>";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['cliente_cpf']}</span></td><td style='border-bottom:2px solid #333;' width='12%'>";}
	else {
		echo $row['clients_nm']." </br>";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']} | Matr.: {$row['clients_prec_cp']}</span></td><td style='border-bottom:2px solid #333;' width='12%'>";
	}
}
	echo $row['vendas_orgao']."<br/>";
	echo "<span style='color:#666666; font-size:8pt'>{$row['vendas_banco']}</span></td><td style='border-bottom:2px solid #333;' width='12%'>"; 
$vendas_valor = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0' ;
	echo "R$ {$vendas_valor} </br>";
	if ($row["vendas_base"]){$vendas_base = "| B".$row["vendas_base"];} else {$vendas_base = "";}
	if ($row["vendas_tipo_contrato"] == "Refinanciamento"){$vendas_tipo_contrato = "Refin";} else {$vendas_tipo_contrato = $row['vendas_tipo_contrato'];}
$vendas_base_prod = ($row['vendas_base_prod']>0) ? number_format($row['vendas_base_prod'], 2, ',', '.') : '0' ;
	echo "<span style='color:#666666; font-size:8pt'>{$vendas_tipo_contrato}{$vendas_base}<br/>R$ {$vendas_base_prod}</span></td><td style='border-bottom:2px solid #333;' width='19%'>"; 
$result_user = mysql_query("SELECT name FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );	
	echo $row_user['name']." </br>";
		$yr=strval(substr($row["vendas_dia_venda"],0,4));
		$mo=strval(substr($row["vendas_dia_venda"],5,2));
		$da=strval(substr($row["vendas_dia_venda"],8,2));
		$hr=strval(substr($row["vendas_dia_venda"],11,2));
		$mi=strval(substr($row["vendas_dia_venda"],14,2));
		$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));	
	echo "<span style='color:#666666; font-size:8pt'>{$data_venda}</span></td><td style='border-bottom:2px solid #333;' width='14%'>"; 
$result_status = mysql_query("SELECT * FROM sys_vendas_status WHERE status_id = " . $row['vendas_status'] . ";")
or die(mysql_error());
$row_status = mysql_fetch_array( $result_status );
if ($row["vendas_status"] > 0){echo "<span style='color:#666666; font-size:8pt'>{$row_status['status_nm']}</span><br/>";}
else{echo "<span style='color:#666666; font-size:8pt'>Enviada p/ implantação</span>";}
if (($row['vendas_dia_pago']) && ($row['vendas_dia_pago'] != "0000-00-00")){
	$vendas_dia_pago = implode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "-" : "/", $row['vendas_dia_pago'])));
	$pagamento = $vendas_dia_pago."<br/>".$row['vendas_mes'];}else{if ($row['vendas_status'] == "8"){$pagamento = "Data não informada";}else{$pagamento = "Ainda não paga";}}
	echo "<span style='color:#666666; font-size:8pt'>{$pagamento}</span></td><td style='border-bottom:2px solid #333;' width='9%'>"; 

// ### CALCULO AUTOMATICO DE BASE DE PRODUÇÃO! ###
$vendas_tabela = $row['vendas_tabela'];
$result_tabela = mysql_query("SELECT tabela_id, 
tabela_base, 
tabela_coeficiente_base2, 
tabela_base_contrato, 
tabela_perc_base_prod, 
tabela_cms_consultor_interno, 
tabela_juros, 
tabela_juros_liquido, 
tabela_juros_fr, 
tabela_cms_pmt, 
tabela_cms_saldo, 
tabela_bonus, 
tabela_tipo FROM sys_vendas_tabelas WHERE tabela_id = '".$vendas_tabela."';") 
or die(mysql_error());
$row_tabela = mysql_fetch_array( $result_tabela );

$vendas_base_prod = $row['vendas_base_prod'];
$vendas_tipo_contrato = $row["vendas_tipo_contrato"];
$vendas_liquido = $row["vendas_liquido"];
$vendas_valor = $row['vendas_valor'];
$vendas_id = $row['vendas_id'];

$_GET["vendas_portabilidade_saldo"] = $row['vendas_portabilidade_saldo'];
$vendas_valor_parcela = $row['vendas_valor_parcela'];
$vendas_juros = $row_tabela['tabela_juros'];
$vendas_juros_liquido = $row_tabela['tabela_juros_liquido'];
$vendas_juros_fr = $row_tabela['tabela_juros_fr'];
$vendas_pmt = $row_tabela['tabela_cms_pmt'];
$vendas_cms_saldo = $row_tabela['tabela_cms_saldo'];
$vendas_bonus = $row_tabela['tabela_bonus'];
if ($row_tabela['tabela_coeficiente_base2']){$coeficiente_base = $row_tabela['tabela_coeficiente_base2'];}else{$coeficiente_base = 5.0;}
include("sistema/vendas/calcula_base_rel_consultor.php");
	
if ($row["vendas_base"] == "2"){$aux_comissao_vendedor = $row_regra_cms["regras_cms_cms_2"];}else{$aux_comissao_vendedor = $row_regra_cms["regras_cms_cms_1"];}
$vendas_comissao_vendedor = ($vendas_base_prod * $aux_comissao_vendedor) / 100;

$vendas_receita_bruta = $cms_total;
$vendas_receita = $vendas_receita_bruta + $row['vendas_receita_plastico'] + $row['vendas_receita_ativacao'] - $row['vendas_taxa'] - $row['vendas_impostos'] - $row['vendas_impostos_plastico_ativacao'];
$vendas_receita_fr = $cms_dif;
$vendas_receita_ant_dif = $cms_ant_dif;

$query = mysql_query("UPDATE sys_vendas SET 
vendas_regra_cms='".$row_regra_cms['regras_cms_id']."', 
vendas_base_prod='".$vendas_base_prod."', 
vendas_base_contrato='".$vendas_base_contrato."', 
vendas_cms_perc_af='".$vendas_cms_perc_af."', 
vendas_base='".$vendas_base."', 
vendas_comissao_fortune='".$vendas_comissao_fortune."', 
vendas_receita_bruta='".$vendas_receita_bruta."', 
vendas_receita_fr='".$vendas_receita_fr."', 
vendas_receita_ant_dif='".$vendas_receita_ant_dif."', 
vendas_receita_bonus='".$vendas_receita_bonus."', 
vendas_receita='".$vendas_receita."', 
vendas_comissao_vendedor='".$vendas_comissao_vendedor."' 
WHERE vendas_id='".$row['vendas_id']."';") or die(mysql_error());
	
$vendas_comissao_vendedor = ($vendas_comissao_vendedor>0) ? number_format($vendas_comissao_vendedor, 2, ',', '.') : '0' ;
	echo "<span style='color:#666666; font-size:8pt'>{$row['vendas_id']}<br/>R$ {$vendas_comissao_vendedor}</span>";
	echo "</td></div></tr>"; 
$exibindo = $exibindo + 1;
$numero = $numero + 1;
}
?>