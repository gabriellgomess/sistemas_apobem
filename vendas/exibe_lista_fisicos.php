<tr><td colspan="7">
<div align="center"><h3 class="mypets2">Vendas Aprovadas, com Físico Pendente:</h3></div>
<div class="thepet2">
<div class="scroller_vendas">
<table class="listaValores2" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
<?php
while($row_fisicos = mysql_fetch_array( $result_fisicos )) {

$result_user = mysql_query("SELECT name, nivel FROM jos_users WHERE id = " . $row_fisicos['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );	

if ($row_user['nivel'] == "4"){
$url_linha = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_agente&vendas_id={$row_fisicos['vendas_id']}";
}else{$url_linha = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row_fisicos['vendas_id']}";}

	if (($row_fisicos['age'] > 11)&&($row_fisicos['age'] <= 15)){$cor_linha = "amarelo";}
	elseif ($row_fisicos['age'] > 15){$cor_linha = "vermelho";}
	else{$cor_linha = "verde";}

	echo "<tr class='".$cor_linha."'><div align='left'><td width='3%'>";
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>&nbsp;</span></a></td><td width='30%'>";
if ($row_fisicos["vendas_orgao"] == "Exercito"){
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_fisicos['clients_nm']}</span></a></td><td width='12%'>";
}
else{
	if ($row_fisicos['cliente_nome']){
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>".$row_fisicos['cliente_nome']."</span></a></td><td width='12%'>";}
	else {
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>".$row_fisicos['clients_nm']."</span></a></td><td width='12%'>";
	}
}
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_fisicos['vendas_orgao']}</span></a></td><td width='11%'>";
$vendas_valor = ($row_fisicos['vendas_valor']>0) ? number_format($row_fisicos['vendas_valor'], 2, ',', '.') : '0' ;
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>R$ {$vendas_valor}</span></a>";
	echo "</td><td width='21%'>";	
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_fisicos_user['name']}</span></a>";
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_user['name']}</span></a>";
	echo "</td><td width='15%'>"; 
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'><strong>Aprovada há {$row_fisicos['age']} dias</strong></span></a></td><td width='5%'>"; 
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_fisicos['vendas_id']}</span></a>";
	echo "</td></div></tr>"; 
}
?>
</table>
</div>
</div>
</td></tr>