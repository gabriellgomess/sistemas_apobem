<?php while($row = mysql_fetch_array( $result )): ?>
<?php
$consultor_situacao = $row["situacao"];
$consultor_empresa = $row["empresa"];
$vendas_unidade = $row["unidade"];
$vendas_equipe = $row["equipe_id"];
$cont_vendas = 0;
$row_regra_cms['regras_cms_id'] = 0;
$row_regra_cms['regras_cms_nome'] = "";

$result_sextou = mysql_query("SELECT SUM(adiantamento_valor) AS total_sextou FROM sys_adiantamentos 
WHERE adiantamento_consultor = '" . $row['vendas_consultor'] . "' 
AND adiantamento_status = 4".
$select_mes_sextou.";")
or die(mysql_error());
$row_sextou = mysql_fetch_array( $result_sextou );

// Consulta a base de produção do mês corrente, do vendedor:
$result_base_consultor = mysql_query("SELECT SUM(vendas_fortcoins) AS total_fortcoins FROM sys_vendas 
WHERE vendas_consultor = '" . $row['vendas_consultor'] . "' 
AND (vendas_status = 8 OR vendas_status = 9) ".
$select_mes.";")
or die(mysql_error());
$row_base_consultor = mysql_fetch_array( $result_base_consultor );

if (($row["data_contrato_90"])&&($row["data_contrato_90"] != "0000-00-00")){
	$result_regra_cms = mysql_query("SELECT * FROM sys_regras_cms 
	WHERE regras_cms_base_ini <= ".$row_base_consultor['total_fortcoins']." 
	AND regras_cms_base_fim >= '".$row_base_consultor['total_fortcoins']."' 
	AND (consultor_situacao LIKE '%,".$consultor_situacao.",%' OR consultor_situacao LIKE '0') 
	AND (consultor_empresa = '".$consultor_empresa."' OR consultor_empresa = '0') 
	AND (consultor_unidade LIKE '%,".$vendas_unidade.",%' OR consultor_unidade LIKE '0') 
	AND (consultor_equipe = '".$vendas_equipe."' OR consultor_equipe = '0') 
	ORDER BY regras_cms_ordem ASC 
	LIMIT 0, 1;")
	or die(mysql_error());
	$row_regra_cms = mysql_fetch_array( $result_regra_cms );
}

if ($row_regra_cms['regras_cms_id']){
	$result_comissao_vendedor = mysql_query("SELECT SUM(vendas_fortcoins) AS total_fortcoins FROM sys_vendas 
	WHERE vendas_consultor = '".$row['vendas_consultor']."' 
	AND (vendas_contrato_fisico = '1' OR vendas_contrato_fisico = '2' OR vendas_contrato_fisico = '101' OR vendas_produto = '2' OR vendas_produto = '3') 
	AND (vendas_status = 8 OR vendas_status = 9) ".$select_mes.";") 
	or die(mysql_error());
	$row_comissao_vendedor = mysql_fetch_array( $result_comissao_vendedor );

	$result_comissao_pendente = mysql_query("SELECT SUM(vendas_fortcoins) AS total_fortcoins FROM sys_vendas 
	WHERE vendas_consultor = '".$row['vendas_consultor']."' 
	AND (vendas_contrato_fisico = '1' OR vendas_contrato_fisico = '2' OR vendas_contrato_fisico = '101' OR vendas_produto = '2' OR vendas_produto = '3') 
	AND vendas_pago_agente = '1' 
	AND (vendas_status = 8 OR vendas_status = 9) ".$select_mes.";") 
	or die(mysql_error());
	$row_comissao_pendente = mysql_fetch_array( $result_comissao_pendente );
	
	if ($_GET["processar"]) {
		$query = mysql_query("UPDATE sys_vendas SET vendas_pago_agente='2' 
		WHERE vendas_consultor = '".$row['vendas_consultor']."' 
		AND (vendas_contrato_fisico = '1' OR vendas_contrato_fisico = '2' OR vendas_contrato_fisico = '101' OR vendas_produto = '2' OR vendas_produto = '3') 
		AND vendas_pago_agente = '1' 
		AND (vendas_status = 8 OR vendas_status = 9) ".$select_mes.";") or die(mysql_error());
	}
}else{
	$row_comissao_vendedor['total_fortcoins'] = 0;
	$row_comissao_pendente['total_fortcoins'] = 0;
}
?>

	<tr>
	<!-- NOME -->
		<td><?php echo $row['name']; ?></td>
	<!-- UNIDADE -->
		<td><?php echo $row['unidade']; ?></td>
	<!-- EQUIPE -->
		<td><?php echo $row['equipe_nome']; ?></td>
	<!-- SITUACAO -->
		<td><?php echo $row['situacao_nome']; ?></td>
	<!-- DATA ADMISSAO -->
		<?php $data_admissao = implode(preg_match("~\/~", $row['data_admissao']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['data_admissao']) == 0 ? "-" : "/", $row['data_admissao']))); ?>
		<td><?php echo $data_admissao; ?></td>
	<!-- FC GATILHO -->
		<?php $total_fortcoins_gatilho = ($row['total_fortcoins']>0) ? number_format($row['total_fortcoins'], 2, ',', '.') : '0' ; ?>
		<td><?php echo $total_fortcoins_gatilho; ?></td>
	<!-- REGRA APLICADA -->
		<td><?php echo $row_regra_cms['regras_cms_nome']; ?></td>
		<?php $total_fortcoins_rs = ($row_comissao_vendedor['total_fortcoins']>0) ? number_format($row_comissao_vendedor['total_fortcoins'], 2, ',', '.') : '0' ; ?>
	<!-- FORTCOINS -->
		<td><?php echo $total_fortcoins_rs; ?></td>
	<!-- CMS % -->
		<td><?php echo $row_regra_cms['regras_cms_cms_1']; ?></td>
	<!-- COMISSAO -->
		<?php 
			$total_comissao_vendedor = ($row_comissao_vendedor['total_fortcoins'] * $row_regra_cms['regras_cms_cms_1']) / 100;
			$total_comissao_vendedor_rs = ($total_comissao_vendedor>0) ? number_format($total_comissao_vendedor, 2, ',', '.') : '0' ;
		?>
		<td><?php echo $total_comissao_vendedor_rs; ?></td>
	<!-- FC PENDENTE -->
		<?php $total_fortcoins_pendente = ($row_comissao_pendente['total_fortcoins']>0) ? number_format($row_comissao_pendente['total_fortcoins'], 2, ',', '.') : '0' ; ?>
		<td><?php echo $total_fortcoins_pendente; ?></td>
	<!-- COMISSAO PENDENTE -->
		<?php 
			$total_comissao_pendente = ($row_comissao_pendente['total_fortcoins'] * $row_regra_cms['regras_cms_cms_1']) / 100;
			$total_comissao_pendente_rs = ($total_comissao_pendente>0) ? number_format($total_comissao_pendente, 2, ',', '.') : '0' ;
		?>
		<td><?php echo $total_comissao_pendente_rs; ?></td>
	<!-- SEXTOU -->
		<?php $total_sextou = ($row_sextou["total_sextou"]>0) ? number_format($row_sextou["total_sextou"], 2, ',', '.') : '0' ; ?>
		<td><?php echo $total_sextou; ?></td>
	<!-- CMS FINAL -->
		<?php
			$cms_final = $total_comissao_pendente - $row_sextou["total_sextou"];
			$cms_final = ($cms_final>0) ? number_format($cms_final, 2, ',', '.') : '0' ;
		?>
		<td><?php echo $cms_final; ?></td>
	<!-- RECEITA -->
		<?php $total_receita_vendedor = ($row["total_receita_vendedor"]>0) ? number_format($row["total_receita_vendedor"], 2, ',', '.') : '0' ; ?>
		<td><?php echo $total_receita_vendedor; ?></td>
	</tr>
	<?php 
	$exibindo = $exibindo + 1;
	$numero = $numero + 1;
	?>
<?php endwhile; ?>