<?php
while($row = mysql_fetch_array( $result_imp )) {
	echo "<tr class='even'><div align='left'><td width='3%'>";
	echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'><span style='color:#666666; font-size:8pt'>{$numero}</span></a></td><td width='30%'>";
if ($row["vendas_orgao"] == "Exercito"){
	echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'>{$row['clients_nm']}</a>";
	echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']} | Matr.: {$row['clients_prec_cp']}</span></td><td width='12%'>";
}
else{
	if ($row['cliente_nome']){
		echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'>".$row['cliente_nome']."</a>";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['cliente_cpf']}</span></td><td width='12%'>";}
	else {
		echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'>".$row['clients_nm']."</a>";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']} | Matr.: {$row['clients_prec_cp']}</span></td><td width='12%'>";
	}
}
	echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'>{$row['vendas_orgao']}</a>";
	echo "<span style='color:#666666; font-size:8pt'>{$row['vendas_banco']}</span>";
if ($row['vendas_proposta']){echo "<br /><span style='color:#666666; font-size:8pt'>nº: {$row['vendas_proposta']}</span>";}
	echo "</td><td width='11%'>";
$vendas_valor = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0' ;
	echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'>R$ {$vendas_valor}</a>";
	if ($row["vendas_base"]){$vendas_base = "| B".$row["vendas_base"];} else {$vendas_base = "";}
	$result_tipo = mysql_query("SELECT * FROM sys_vendas_tipos WHERE tipo_id = " . $row['vendas_tipo_contrato'] . ";")
or die(mysql_error());
$row_tipo = mysql_fetch_array( $result_tipo );
	echo "<span style='color:#666666; font-size:8pt'>{$row_tipo['tipo_nome']}{$vendas_base}<br/>"; 
if ($diretoria == 1){
	$vendas_receita = ($row['vendas_receita']>0) ? number_format($row['vendas_receita'], 2, ',', '.') : '0' ;
	if($row['vendas_receita']){echo "Receita: R$ ".$vendas_receita;}else{echo "<span style='color:#F49333;'><strong>Sem Receita</strong></span>";}
}
	echo "</span></td><td width='21%'>";
$result_user = mysql_query("SELECT name FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );	
	echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'>{$row_user['name']}</a>";
		$yr=strval(substr($row["vendas_dia_venda"],0,4));
		$mo=strval(substr($row["vendas_dia_venda"],5,2));
		$da=strval(substr($row["vendas_dia_venda"],8,2));
		$hr=strval(substr($row["vendas_dia_venda"],11,2));
		$mi=strval(substr($row["vendas_dia_venda"],14,2));
		$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));	
	echo "<span style='color:#666666; font-size:8pt'>{$data_venda}</span></td><td width='15%'>"; 
$result_status = mysql_query("SELECT * FROM sys_vendas_status WHERE status_id = " . $row['vendas_status'] . ";")
or die(mysql_error());
$row_status = mysql_fetch_array( $result_status );
if ($row["vendas_status"] > 0){echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'><span style='color:#666666; font-size:8pt'>{$row_status['status_nm']}</span></a>";}
else{echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'><span style='color:#666666; font-size:8pt'>Enviada p/ implantação</span></a>";}
	echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'><img src='sistema/imagens/status_{$row['vendas_status']}.png'></a>";
if (($row['vendas_dia_pago']) && ($row['vendas_dia_pago'] != "0000-00-00")){
	$vendas_dia_pago = implode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "-" : "/", $row['vendas_dia_pago'])));
	$pagamento = $vendas_dia_pago." | ".$row['vendas_mes'];}else{if ($row['vendas_status'] == "8"){$pagamento = "Data não informada";}else{$pagamento = "Ainda não paga";}}
	echo "<img src='sistema/imagens/contrato_{$row['vendas_contrato_fisico']}.png'> | <span style='color:#666666; font-size:8pt'>{$pagamento}</span></td><td width='5%'>"; 
	echo "<a title='editar' href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'><img src='sistema/imagens/edit.png'></a>";
	echo "<img src='sistema/imagens/separa.png'>";
if ($administracao == 1){echo "<a title='EXCLUIR VENDA Nº: {$row['vendas_id']}' href='index.php?option=com_k2&view=item&id=64:excluir-venda&Itemid=123&tmpl=component&print=1&vendas_id={$row['vendas_id']}&clients_cpf={$row['clients_cpf']}&clients_nm={$row['clients_nm']}&acao=exclui_venda' rel='lyteframe' rev='width: 550px; height: 400px; scroll:no;'><img src='sistema/imagens/delete.png'></a>";}
	echo "<a href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'><span style='color:#666666; font-size:8pt'>{$row['vendas_id']}</span></a>";
	echo "</td></div></tr>"; 
$exibindo = $exibindo + 1;
$numero = $numero + 1;
}
?>