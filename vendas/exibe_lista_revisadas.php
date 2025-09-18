<tr><td colspan="7">
<div align="center"><h3 class="mypets2">Vendas Revisadas:</h3></div>
<div class="thepet2">
<div class="scroller_vendas">
<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
<?php
while($row_pendentes = mysql_fetch_array( $result_pendentes )) {

$result_user = mysql_query("SELECT name, nivel FROM jos_users WHERE id = " . $row_pendentes['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );	
$result_tipo = mysql_query("SELECT tipo_nome FROM sys_vendas_tipos WHERE tipo_id = " . $row_pendentes['vendas_tipo_contrato'] . ";")
or die(mysql_error());
$row_tipo = mysql_fetch_array( $result_tipo );

if ($row_user['nivel'] == "4"){
$url_linha = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_agente&vendas_id={$row_pendentes['vendas_id']}";
}else{$url_linha = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row_pendentes['vendas_id']}";}

	echo "<tr class='even'><div align='left'><td width='3%'>";
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>&nbsp;</span></a></td><td width='30%'>";
if ($row_pendentes["vendas_orgao"] == "Exercito"){
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_pendentes['clients_nm']}</span></a></td><td width='12%'>";
}
else{
	if ($row_pendentes['cliente_nome']){
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>".$row_pendentes['cliente_nome']."</span></a></td><td width='12%'>";}
	else {
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>".$row_pendentes['clients_nm']."</span></a></td><td width='12%'>";
	}
}
	if ($row_pendentes['vendas_produto'] == 1) {
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>Físico</span></a></td><td width='11%'>";
	}
	if ($row_pendentes['vendas_produto'] == 2) {
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>Fonado</span></a></td><td width='11%'>";
	}
	if ($row_pendentes['vendas_produto'] == 3) {
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>Digital</span></a></td><td width='11%'>";
	}

	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_pendentes['vendas_orgao']} ({$row_tipo['tipo_nome']})</span></a></td><td width='11%'>";
$vendas_valor = ($row_pendentes['vendas_valor']>0) ? number_format($row_pendentes['vendas_valor'], 2, ',', '.') : '0' ;
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>R$ {$vendas_valor}</span></a>";
	echo "</td><td width='21%'>";	
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_pendentes_user['name']}</span></a>";
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_user['name']}</a>";
	echo "</td><td width='15%'>"; 
$result_status = mysql_query("SELECT * FROM sys_vendas_status WHERE status_id = " . $row_pendentes['vendas_status'] . ";")
or die(mysql_error());
$row_status = mysql_fetch_array( $result_status );
if ($row_pendentes["vendas_status"] > 0){echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_status['status_nm']}</span></a></td><td width='5%'>";}
else{echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>Enviada p/ implantação</span></a></td><td width='5%'>";}
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_pendentes['vendas_id']}</span></a>";
	echo "</td></div></tr>"; 
}
?>
</table>
</div>
</div>
</td></tr>