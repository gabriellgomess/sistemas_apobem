<?php
while($row = mysql_fetch_array( $result )) {
$endereco_link = "#";
	echo "<tr style='border: 2px solid #333;'><div align='left'><td width='3%' class='borda'>";
	echo "<span style='color:#666666; font-size:8pt'>{$numero}</span></td><td width='25%' class='borda'>";
if ($row["vendas_orgao"] == "Exercito"){
	$nome = $row['clients_nm'];
	$cpf = $row['clients_cpf'];
}
else{
	if ($row['cliente_nome']){
		$nome = $row['cliente_nome'];
		$cpf = $row['cliente_cpf'];
	}else{
		$nome = $row['clients_nm'];
		$cpf = $row['clients_cpf'];
	}
}
	echo $nome."<br />";
	echo "<span style='color:#666666; font-size:8pt'>CPF: {$cpf}</span></td><td width='12%' class='borda'>";
$result_apolice = mysql_query("SELECT apolice_valor FROM sys_vendas_apolices WHERE apolice_id = " . $row['vendas_apolice'] . ";")
or die(mysql_error());
$row_apolice = mysql_fetch_array( $result_apolice );
$apolice_valor = ($row_apolice['apolice_valor']>0) ? number_format($row_apolice['apolice_valor'], 2, ',', '.') : '0' ;
	echo "R$ {$apolice_valor}<br />";
	echo "<span style='color:#666666; font-size:8pt'>Vencimento dia {$row['vendas_dia_desconto']}</span></td><td width='21%' class='borda'>";
$result_user = mysql_query("SELECT name FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );	
	echo $row_user['name']."<br />";
		$yr=strval(substr($row["vendas_dia_venda"],0,4));
		$mo=strval(substr($row["vendas_dia_venda"],5,2));
		$da=strval(substr($row["vendas_dia_venda"],8,2));
		$hr=strval(substr($row["vendas_dia_venda"],11,2));
		$mi=strval(substr($row["vendas_dia_venda"],14,2));
		$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));	
	echo "<span style='color:#666666; font-size:8pt'>{$data_venda}</span></td><td width='15%' class='borda'>"; 
$result_status = mysql_query("SELECT status_nm FROM sys_vendas_status_seg WHERE status_id = " . $row['vendas_status'] . ";")
or die(mysql_error());
$row_status = mysql_fetch_array( $result_status );
	echo "<span style='color:#666666; font-size:8pt'>{$row_status['status_nm']}</span><br />";
	echo "<img src='sistema/imagens/status_seg_{$row['vendas_status']}.png'></td>"; 
	echo "<td width='6%' class='borda'><div align='right'><strong>{$row['vendas_id']}</strong></div></td>";
	echo "</div></tr>"; 
$exibindo = $exibindo + 1;
$numero = $numero + 1;
}
?>