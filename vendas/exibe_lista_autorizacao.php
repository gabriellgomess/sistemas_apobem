<tr><td colspan="7">
<div align="center"><h3 class="mypets2">Vendas Aguardando Autorização:</h3></div>
<div class="thepet2">
<div class="scroller_vendas">
<table class="listaValores2" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
<?php
while($row_autorizacao = mysql_fetch_array( $result_autorizacao )) {

$result_user = mysql_query("SELECT name, nivel FROM jos_users WHERE id = " . $row_autorizacao['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );	

if ($row_user['nivel'] == "4"){
$url_linha = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_agente&vendas_id={$row_autorizacao['vendas_id']}";
}else{$url_linha = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row_autorizacao['vendas_id']}";}

	if (($row_autorizacao['age'] > 70)&&($row_autorizacao['age'] < 81)){$cor_linha = "amarelo";}
	elseif ($row_autorizacao['age'] > 80){$cor_linha = "vermelho";}
	else{$cor_linha = "amarelo";}

	echo "<tr class='".$cor_linha."'><div align='left'><td width='3%'>";
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>&nbsp;</span></a></td><td width='30%'>";
if ($row_autorizacao["vendas_orgao"] == "Exercito"){
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_autorizacao['clients_nm']}</span></a></td><td width='12%'>";
}
else{
	if ($row_autorizacao['cliente_nome']){
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>".$row_autorizacao['cliente_nome']."</span></a></td><td width='12%'>";}
	else {
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>".$row_autorizacao['clients_nm']."</span></a></td><td width='12%'>";
	}
}
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_autorizacao['vendas_banco']}</span></a></td><td width='11%'>";
$vendas_valor = ($row_autorizacao['vendas_valor']>0) ? number_format($row_autorizacao['vendas_valor'], 2, ',', '.') : '0' ;
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>R$ {$vendas_valor}</span></a>";
	echo "</td><td width='21%'>";	
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_user['name']}</span></a>";
	echo "</td><td width='15%'>";
	$yr=strval(substr($row_autorizacao["vendas_dia_venda"],0,4));
	$mo=strval(substr($row_autorizacao["vendas_dia_venda"],5,2));
	$da=strval(substr($row_autorizacao["vendas_dia_venda"],8,2));
	$hr=strval(substr($row_autorizacao["vendas_dia_venda"],11,2));
	$mi=strval(substr($row_autorizacao["vendas_dia_venda"],14,2));
	$vendas_dia_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'><strong>{$vendas_dia_venda}</strong></span></a></td><td width='5%'>"; 
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_autorizacao['vendas_id']}</span></a>";
	echo "</td></div></tr>"; 
}
?>
</table>
</div>
</div>
</td></tr>