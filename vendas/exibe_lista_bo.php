<tr><td colspan="7">
<div align="center"><h3 class="mypets2">Vendas Aguardando BackOffice:</h3></div>
<div class="thepet2">
<div class="scroller_vendas">
<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
<?php
while($row = mysql_fetch_array( $result )) {
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
	echo "<a title='editar' href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&bloquear=bloquear&vendas_id={$row['vendas_id']}'><button class='button_right' name='bloquear' type='button' value='bloquear'>CONFERIR!</button></a>";
	echo "<strong>{$row['vendas_id']}</strong></a>";
	echo "</td></div></tr>"; 
$total_bo = $total_bo + 1;
}
?>
</table>
</div>
</div>
</td></tr>
<tr><td colspan="7"><div align="center"><h3 class="mypets2">Vendas em Coleta de Documentos:</h3></div></td></tr>