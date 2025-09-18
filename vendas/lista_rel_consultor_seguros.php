<?php
while($row = mysql_fetch_array( $result_seguros )) {
	echo "<tr><div align='left'><td width='3%'>";
	echo "<span style='color:#666666; font-size:8pt'>{$numero}</span></td><td width='25%'>";
	echo "<a href='".$endereco_link."'>".$row['clients_nm'].$row['cliente_nome']."<br />";
	echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['cliente_cpf']}</span>";
	echo "</td><td width='12%'>";
	$vendas_valor = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0' ;
	echo "R$ ".$vendas_valor."<br>";
	echo "<span style='color:#666666; font-size:8pt'>{$row['apolice_nome']}</span></td><td width='21%'>";
	echo $row_user['name']."<br>";
	$yr=strval(substr($row["vendas_dia_venda"],0,4));
	$mo=strval(substr($row["vendas_dia_venda"],5,2));
	$da=strval(substr($row["vendas_dia_venda"],8,2));
	$hr=strval(substr($row["vendas_dia_venda"],11,2));
	$mi=strval(substr($row["vendas_dia_venda"],14,2));
	$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));	
	echo "<span style='color:#666666; font-size:8pt'>{$data_venda}</span></td><td width='15%'>";
	echo "<span style='color:#666666; font-size:8pt'>{$row['status_nm']}</span>";
	echo "</td><td width='10%'>"; 
	echo $row['vendas_id']."<br>";
	$vendas_comissao_vendedor = (($row['vendas_valor'] * $row['apolice_cms_vendedor']) / 100);
	$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_comissao_vendedor='$vendas_comissao_vendedor' WHERE vendas_id='".$row['vendas_id']."' ") or die(mysql_error());
	$vendas_comissao_vendedor = ($vendas_comissao_vendedor>0) ? number_format($vendas_comissao_vendedor, 2, ',', '.') : '0' ;
	echo "<strong>R$ ".$vendas_comissao_vendedor."</strong>";
	echo "</td></div></tr>"; 
$exibindo = $exibindo + 1;
$numero = $numero + 1;
}
?>