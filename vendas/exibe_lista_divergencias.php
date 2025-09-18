<?php
while($row = mysql_fetch_array( $result )) {
	
	$result_user = mysql_query("SELECT nivel FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
	or die(mysql_error());
	$row_user = mysql_fetch_array( $result_user );
	if (($row_user["nivel"] == "4") || ($row_user["nivel"] == "8")){
		$link_venda = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_agente&vendas_id=".$row['vendas_id'];
	}else{$link_venda = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id=".$row['vendas_id'];}
	
	echo "<tr class='even'><div align='left'><td width='3%'>";
	echo "<a href='".$link_venda."'><span style='color:#666666; font-size:8pt'>{$numero}</span></a></td>";
	echo "<td width='25%'>";
if ($row["vendas_orgao"] == "Exercito"){
	echo "<a href='".$link_venda."'>{$row['clients_nm']}</a>";
	echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']} | Matr.: {$row['clients_prec_cp']}";
}else{
	if ($row['cliente_nome']){
		echo "<a href='".$link_venda."'>".$row['cliente_nome']."</a>";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['cliente_cpf']}";
	}else{
		echo "<a href='".$link_venda."'>".$row['clients_nm']."</a>";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']} | Matr.: {$row['clients_prec_cp']}";
	}
}
	if ($contagem){
		$link_num = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=440&nome=".$nome."&prec=".$prec."&cpf=".$row['cliente_cpf'].$pag_mes."&consultor_unidade=".$pag_unidade."&vendas_consultor=".$vendas_consultor."&vendas_vendedor=".$vendas_vendedor."&vendas_status=".$pag_status."&vendas_contrato_fisico=".$pag_contrato."&vendas_promotora=".$vendas_promotora."&vendas_banco=".$vendas_banco."&vendas_orgao=".$vendas_orgao."&vendas_tipo_contrato=".$vendas_tipo_contrato."&vendas_seguro_protegido=".$vendas_seguro_protegido."&dp-normal-3=".$pag_data_imp_ini."&dp-normal-4=".$pag_data_imp_fim;
		echo " | <a href='".$link_num."'>Nº de Vendas: <strong>".$row['contagem']."</strong></a>";}
	echo "</span></td><td width='12%'>";
	echo "<a href='".$link_venda."'>{$row['vendas_banco']}</a>";
if ($row['vendas_proposta']){echo "<span style='color:#666666; font-size:8pt'>nº: {$row['vendas_proposta']}</span>";}
	echo "</td><td width='11%'>";
	$vendas_base_contrato = ($row['vendas_base_contrato']>0) ? number_format($row['vendas_base_contrato'], 2, ',', '.') : '0' ;
	echo "<a href='".$link_venda."'>Base: R$ {$vendas_base_contrato}</a>";
	$vendas_receita = ($row['vendas_receita']<>0) ? number_format($row['vendas_receita'], 2, ',', '.') : '0' ;
	if($row['vendas_receita']){echo "Receita: R$ ".$vendas_receita."<br />";}else{echo "<span style='color:#F49333;'><strong>Sem Receita</strong></span><br />";}
	$vendas_receita_bonus = ($row['vendas_receita_bonus']<>0) ? number_format($row['vendas_receita_bonus'], 2, ',', '.') : '0' ;
	if($row['vendas_receita_bonus']){echo "Bônus: R$ ".$vendas_receita_bonus;}else{echo "<span style='color:#F49333;'><strong>Sem Bônus</strong></span>";}
	echo "</span></td><td width='26%'>";
	echo "<a href='".$link_venda."'>{$row['vendas_divergencia']}</a></td><td width='15%'>"; 
$result_status = mysql_query("SELECT * FROM sys_vendas_status WHERE status_id = " . $row['vendas_status'] . ";")
or die(mysql_error());
$row_status = mysql_fetch_array( $result_status );
if ($row["vendas_status"] > 0){echo "<a href='".$link_venda."'><span style='color:#666666; font-size:8pt'>{$row_status['status_nm']}</span></a>";}
else{echo "<a href='".$link_venda."'><span style='color:#666666; font-size:8pt'>Enviada p/ implantação</span></a>";}
	echo "<a href='".$link_venda."'><img src='sistema/imagens/status_{$row['vendas_status']}.png'></a>";
if (($row['vendas_dia_pago']) && ($row['vendas_dia_pago'] != "0000-00-00")){
	$vendas_dia_pago = implode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "-" : "/", $row['vendas_dia_pago'])));
	$pagamento = $vendas_dia_pago." | ".$row['vendas_mes'];}else{if ($row['vendas_status'] == "8"){$pagamento = "Data não informada";}else{$pagamento = "Ainda não paga";}}
	echo "<img src='sistema/imagens/contrato_{$row['vendas_contrato_fisico']}.png'>";
	if ((($row['vendas_tipo_contrato'] == "3")||($row['vendas_tipo_contrato'] == "6"))&&($frame_fisicos == 1)) {echo " <img src='sistema/imagens/contrato_port_{$row['vendas_contrato_fisico2']}.png'>";}
	echo " | <span style='color:#666666; font-size:8pt'>{$pagamento}</span></td><td width='5%'>";
	echo "<div align='center'><a href='".$link_venda."'><span style='color:#666666; font-size:8pt'>{$row['vendas_id']}</span></a>";
	echo "<a title='ACIETAR DIVERGÊNCIA DA VENDA Nº: {$row['vendas_id']}' href='index.php?option=com_k2&view=item&id=64:aceitar-divergencia&Itemid=123&tmpl=component&print=1&vendas_id={$row['vendas_id']}&acao=aceita_divergencia' rel='lyteframe' rev='width: 650px; height: 500px; scroll:no;'><img src='sistema/imagens/aceitar.png'></a></div>";
	echo "</td></div></tr>";
$exibindo = $exibindo + 1;
$numero = $numero + 1;
}
?>