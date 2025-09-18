<?php
while($row = mysql_fetch_array( $result )) {
	echo "<tr class='even'><div align='left'><td width='3%'>";
	echo "<span style='color:#666666; font-size:8pt'>{$numero}</span></td><td width='25%'>";
if ($row["vendas_orgao"] == "Exercito"){
	echo $row['clients_nm']."<br />";
	echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']}</span></td><td width='12%'>";
}
else{
	if ($row['cliente_nome']){
		echo $row['cliente_nome']."<br />";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['cliente_cpf']}</span></td><td width='12%'>";}
	else {
		echo $row['clients_nm']."<br />";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']}</span></td><td width='12%'>";
	}
}
$result_apolice = mysql_query("SELECT apolice_valor FROM sys_vendas_apolices WHERE apolice_id = " . $row['vendas_apolice'] . ";")
or die(mysql_error());
$row_apolice = mysql_fetch_array( $result_apolice );
$apolice_valor = ($row_apolice['apolice_valor']>0) ? number_format($row_apolice['apolice_valor'], 2, ',', '.') : '0' ;
	echo "R$ {$apolice_valor}</a><br />";
	echo "<span style='color:#666666; font-size:8pt'>Vencimento dia {$row['vendas_dia_desconto']}</span></td><td width='21%'>";
$result_user = mysql_query("SELECT name FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );	
	echo "{$row_user['name']} ({$row['equipe_nome']})</a><br />";
		$yr=strval(substr($row["vendas_dia_venda"],0,4));
		$mo=strval(substr($row["vendas_dia_venda"],5,2));
		$da=strval(substr($row["vendas_dia_venda"],8,2));
		$hr=strval(substr($row["vendas_dia_venda"],11,2));
		$mi=strval(substr($row["vendas_dia_venda"],14,2));
		$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));	
	echo "<span style='color:#666666; font-size:8pt'>{$data_venda}</span></td><td width='15%'>"; 
$result_status = mysql_query("SELECT status_nm FROM sys_vendas_status_seg WHERE status_id = " . $row['vendas_status'] . ";")
or die(mysql_error());
$row_status = mysql_fetch_array( $result_status );
if ($row["vendas_status"] > 0){echo "<span style='color:#666666; font-size:8pt'>{$row_status['status_nm']}</span></a>";}
else{echo "<span style='color:#666666; font-size:8pt'>Enviada p/ implantação</span></a>";}
	echo "<img src='sistema/imagens/status_{$row['vendas_status']}.png'></a></td><td width='15%'>"; 
	echo "<a title='editar' href='index.php?option=com_k2&view=item&layout=item&id=101&Itemid=477&acao=edita_venda_seguro&bloquear=bloquear&vendas_id={$row['vendas_id']}'><button class='button_right' name='bloquear' type='button' value='bloquear'>Auditar!</button></a>";
	echo "<strong>{$row['vendas_id']}</strong></a>";
	echo "</td></div></tr>"; 
$exibindo = $exibindo + 1;
$numero = $numero + 1;
}
?>