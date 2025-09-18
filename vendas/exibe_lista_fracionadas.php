<tr><td colspan="7">
<div align="center"><h3 class="mypets2">Vendas com Fracionado Pendente:</h3></div>
<div class="thepet2">
<div class="scroller_vendas">
<table class="listaValores2" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
<?php
while($row_fracionadas = mysql_fetch_array( $result_fracionadas )) {

$result_user = mysql_query("SELECT name, nivel FROM jos_users WHERE id = " . $row_fracionadas['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );	

if ($row_user['nivel'] == "4"){
$url_linha = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_agente&vendas_id={$row_fracionadas['vendas_id']}";
}else{$url_linha = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id={$row_fracionadas['vendas_id']}";}

	if (($row_fracionadas['age'] > 70)&&($row_fracionadas['age'] < 81)){$cor_linha = "amarelo";}
	elseif ($row_fracionadas['age'] > 80){$cor_linha = "vermelho";}
	else{$cor_linha = "verde";}

	echo "<tr class='".$cor_linha."'><div align='left'><td width='3%'>";
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>&nbsp;</span></a></td><td width='30%'>";
if ($row_fracionadas["vendas_orgao"] == "Exercito"){
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_fracionadas['clients_nm']}</span></a></td><td width='12%'>";
}
else{
	if ($row_fracionadas['cliente_nome']){
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>".$row_fracionadas['cliente_nome']."</span></a></td><td width='12%'>";}
	else {
		echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>".$row_fracionadas['clients_nm']."</span></a></td><td width='12%'>";
	}
}
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_fracionadas['vendas_banco']}</span></a></td><td width='11%'>";
$vendas_receita_fr = ($row_fracionadas['vendas_receita_fr']>0) ? number_format($row_fracionadas['vendas_receita_fr'], 2, ',', '.') : '0' ;
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>R$ {$vendas_receita_fr}</span></a>";
	echo "</td><td width='21%'>";	
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_fracionadas_user['name']}</span></a>";
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_user['name']}</span></a>";
	echo "</td><td width='15%'>";
$vendas_dia_pago = implode(preg_match("~\/~", $row_fracionadas['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row_fracionadas['vendas_dia_pago']) == 0 ? "-" : "/", $row_fracionadas['vendas_dia_pago'])));
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'><strong>{$vendas_dia_pago}</strong> (há {$row_fracionadas['age']} dias)</span></a></td><td width='5%'>"; 
	echo "<a href='".$url_linha."'><span style='color:#666666; font-size:8pt'>{$row_fracionadas['vendas_proposta']}</span></a>";
	echo "</td></div></tr>"; 
}
?>
</table>
</div>
</div>
</td></tr>