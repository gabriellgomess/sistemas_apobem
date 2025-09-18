<?php
while($row = mysql_fetch_array( $result )) {
	
	$link_venda = "index.php?option=com_k2&view=item&layout=item&id=356&Itemid=398&acao=edita_venda_agente&vendas_id=".$row['vendas_id'];
	
	echo "<tr class='even'><div align='left'><td width='3%'>";
	echo "<a href='".$link_venda."'><span style='color:#666666; font-size:8pt'>{$numero}</span></a></td><td width='30%'>";
if ($row["vendas_orgao"] == "Exercito"){
	echo "<a href='".$link_venda."'>{$row['clients_nm']}</a>";
	echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']} | Matr.: {$row['clients_prec_cp']}</span></td><td width='12%'>";
}
else{
	if ($row['cliente_nome']){
		echo "<a href='".$link_venda."'>".$row['cliente_nome']."</a>";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['cliente_cpf']}</span></td><td width='12%'>";}
	else {
		echo "<a href='".$link_venda."'>".$row['clients_nm']."</a>";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']} | Matr.: {$row['clients_prec_cp']}</span></td><td width='12%'>";
	}
}
	echo "<a href='".$link_venda."'>{$row['vendas_orgao']}</a>";
	echo "<span style='color:#666666; font-size:8pt'>{$row['vendas_banco']}</span>";
if ($row['vendas_proposta']){echo "<br /><span style='color:#666666; font-size:8pt'>nº: {$row['vendas_proposta']}</span>";}
	echo "</td><td width='11%'>";
$vendas_valor = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0' ;
	echo "<a href='".$link_venda."'>R$ {$vendas_valor}</a>";
	if ($row["vendas_base"]){$vendas_base = "| B".$row["vendas_base"];} else {$vendas_base = "";}
$result_tipo = mysql_query("SELECT * FROM sys_vendas_tipos WHERE tipo_id = " . $row['vendas_tipo_contrato'] . ";")
or die(mysql_error());
$row_tipo = mysql_fetch_array( $result_tipo );
	echo "<span style='color:#666666; font-size:8pt'>{$row_tipo['tipo_nome']}{$vendas_base}"; 
if (($diretoria == 1)||($gerente_unidade)){
	$vendas_receita = ($row['vendas_receita']<>0) ? number_format($row['vendas_receita'], 2, ',', '.') : '0' ;
	if ($row['vendas_receita'] < 0){echo "<span style='color:red; font-weight: bold;'>";}
	elseif($row['vendas_receita'] == 0){echo "<span style='color:#F49333; font-weight: bold;'>";}
	else{echo "<span style='color:blue; font-weight: bold;'>";}
	echo "<br/>Receita: R$ ".$vendas_receita;
	echo "</span>";
}

	echo "</span></td><td width='21%'>";
$result_user = mysql_query("SELECT name FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );	
	echo "<a href='".$link_venda."'>{$row_user['name']}</a>";
		$yr=strval(substr($row["vendas_dia_venda"],0,4));
		$mo=strval(substr($row["vendas_dia_venda"],5,2));
		$da=strval(substr($row["vendas_dia_venda"],8,2));
		$hr=strval(substr($row["vendas_dia_venda"],11,2));
		$mi=strval(substr($row["vendas_dia_venda"],14,2));
		$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));	
	echo "<span style='color:#666666; font-size:8pt'>{$data_venda}<br />";
	
	if (($row['vendas_dia_pago']) && ($row['vendas_dia_pago'] != "0000-00-00")){
		$vendas_dia_pago = implode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "-" : "/", $row['vendas_dia_pago'])));
		$pagamento = $vendas_dia_pago." | ".$row['vendas_mes'];
	}else{if ($row['vendas_status'] == "8"){$pagamento = "Data não informada";}else{$pagamento = "Ainda não paga";}}
	echo "{$pagamento}</span></td>";
	
	echo "<td width='15%'>";
	
$result_status = mysql_query("SELECT * FROM sys_vendas_status WHERE status_id = " . $row['vendas_status'] . ";")
or die(mysql_error());
$row_status = mysql_fetch_array( $result_status );
if ($row["vendas_status"] > 0){echo "<a href='".$link_venda."'><span style='color:#666666; font-size:8pt'>{$row_status['status_nm']}</span></a>";}
else{echo "<a href='".$link_venda."'><span style='color:#666666; font-size:8pt'>Enviada p/ implantação</span></a>";}
	echo "<a href='".$link_venda."'><img src='sistema/imagens/status_{$row['vendas_status']}.png'></a>";
	
	if ($row['vendas_produto'] == 1){
		$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos ORDER BY contrato_etapa;")
		or die(mysql_error());
		$largura_fisicos = 50 / (mysql_num_rows( $result_fisicos ) - 1); 
		echo "<div id='container-sbar'>";
		while($row_fisicos = mysql_fetch_array( $result_fisicos )) {
			if ($row_fisicos["contrato_id"] == $row['vendas_contrato_fisico']){echo "<div class='sbar sbar-active' style='background-color: ".$row_fisicos['contrato_cor']."1);' title='Status Físico ".$row_fisicos['contrato_nome']."'>".$row_fisicos['contrato_nome']."</div>";}
			else {echo "<div class='sbar' style='width: ".$largura_fisicos."%;'><div class='sbar-inside'></div></div>";}
		}
		echo "</div>";
	}
	
if (($row['vendas_dia_pago']) && ($row['vendas_dia_pago'] != "0000-00-00")){
	$vendas_dia_pago = implode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "-" : "/", $row['vendas_dia_pago'])));
	$pagamento = $vendas_dia_pago." | ".$row['vendas_mes'];}else{if ($row['vendas_status'] == "8"){$pagamento = "Data não informada";}else{$pagamento = "Ainda não paga";}}

	echo "</td>"; 
	echo "<td width='5%'>"; 
	echo "<a title='editar' href='".$link_venda."'><img src='sistema/imagens/edit.png'></a>";
	echo "<img src='sistema/imagens/separa.png'>";
if ($exclusao_vendas == 1){echo "<a title='EXCLUIR VENDA Nº: {$row['vendas_id']}' href='index.php?option=com_k2&view=item&id=64:excluir-venda&Itemid=123&tmpl=component&print=1&vendas_id={$row['vendas_id']}&clients_cpf={$row['clients_cpf']}&clients_nm={$row['clients_nm']}&acao=exclui_venda' rel='lyteframe' rev='width: 550px; height: 400px; scroll:no;'><img src='sistema/imagens/delete.png'></a>";}
	echo "<a href='".$link_venda."'><span style='color:#666666; font-size:8pt'>{$row['vendas_id']}</span></a>";
	echo "</td></div></tr>"; 
$exibindo = $exibindo + 1;
$numero = $numero + 1;
}
?>