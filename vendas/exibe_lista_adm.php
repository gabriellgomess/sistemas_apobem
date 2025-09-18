<?php
while ($row = mysql_fetch_array($result)) {
	$link_venda = "index.php?option=com_k2&view=item&layout=item&id=341&Itemid=398&acao=edita_venda&vendas_id=" . $row['vendas_id'];

	echo "<tr class='even'><div align='left'><td width='3%'>";
	echo "<a href='" . $link_venda . "'><span style='color:#666666; font-size:8pt'>{$numero}</span></a>";
	if ($frame_fisicos == 1) {
		echo "<input type='checkbox' name='massa[]' value='" . $row['vendas_id'] . "'/>";
	}
	echo "</td>";
	echo "<td width='30%'>";

	if ($row['cliente_nome']) {
		echo "<a href='" . $link_venda . "'>" . $row['cliente_nome'] . "</a>";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['cliente_cpf']}";
	} else {
		echo "<a href='" . $link_venda . "'>" . $row['clients_nm'] . "</a>";
		echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']} | Matr.: {$row['clients_prec_cp']}";
	}

	if ($contagem) {
		$link_num = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=440&nome=" . $nome . "&prec=" . $prec . "&cpf=" . $row['cliente_cpf'] . $pag_mes . "&consultor_unidade=" . $pag_unidade . "&vendas_consultor=" . $vendas_consultor . "&vendas_vendedor=" . $vendas_vendedor . "&vendas_status=" . $pag_status . "&vendas_contrato_fisico=" . $pag_contrato . "&vendas_promotora=" . $vendas_promotora . "&vendas_banco=" . $vendas_banco . "&vendas_orgao=" . $vendas_orgao . "&vendas_tipo_contrato=" . $vendas_tipo_contrato . "&vendas_seguro_protegido=" . $vendas_seguro_protegido . "&dp-normal-3=" . $pag_data_imp_ini . "&dp-normal-4=" . $pag_data_imp_fim;
		echo " | <a href='" . $link_num . "'>Nº de Vendas: <strong>" . $row['contagem'] . "</strong></a>";
	}
	echo "</span></td><td width='12%'>";
	echo "<a href='" . $link_venda . "'>{$row['vendas_orgao']} | {$row['vendas_banco']}</a>";
	if ($row['vendas_proposta']) {
		echo "<span style='color:#666666; font-size:8pt'>Proposta nº: {$row['vendas_proposta']}</span>";
	}
	if ($row['vendas_portabilidade']) {
		echo "<br /><span style='color:#666666; font-size:8pt'>Portabilidade nº: {$row['vendas_portabilidade']}</span>";
	}
	if ($row['vendas_produto']) {
		echo "<br /><span style='color:#666666; font-size:8pt'>Produto: " . getProdutoNomeById($row['vendas_produto']) . "</span>";
	}
	echo "</td><td width='11%'>";
	$vendas_valor = ($row['vendas_valor'] > 0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0';
	echo "<a href='" . $link_venda . "'>R$ {$vendas_valor}</a>";
	if ($row["vendas_base"]) {
		$vendas_base = "| B" . $row["vendas_base"];
	} else {
		$vendas_base = "";
	}
	$result_tipo = mysql_query("SELECT * FROM sys_vendas_tipos WHERE tipo_id = " . $row['vendas_tipo_contrato'] . ";")
		or die(mysql_error());
	$row_tipo = mysql_fetch_array($result_tipo);
	echo "<span style='color:#666666; font-size:8pt'>{$row_tipo['tipo_nome']}{$vendas_base}";
	
	if (($super_user || $diretoria || $sup_operacional || $supervisor_equipe_vendas || $operacional_fisico) && !$consultor_mei) {
		$vendas_fortcoins = ($row['vendas_fortcoins'] > 0) ? "BS¢ " . number_format($row['vendas_fortcoins'], 2, ',', '.') : 'Aguarda cálculo.';
		echo "<br>" . $vendas_fortcoins;
	}
	
	if (($diretoria == 1) || ($user_id == 165) || ($user_id == 2415) || ($user_id == 3510)) {
		$vendas_receita = ($row['vendas_receita'] <> 0) ? number_format($row['vendas_receita'], 2, ',', '.') : '0';
		if ($row['vendas_receita']) {
			echo "<br/>Receita: R$ " . $vendas_receita;
		} else {
			echo "<br/><span style='color:#F49333;'><strong>Sem Receita</strong></span>";
		}
	}


	echo "</span></td><td width='21%'>";
	$result_user = mysql_query("SELECT name FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
		or die(mysql_error());
	$row_user = mysql_fetch_array($result_user);
	echo "<a href='" . $link_venda . "'>{$row_user['name']}</a>";
	$yr = strval(substr($row["vendas_dia_venda"], 0, 4));
	$mo = strval(substr($row["vendas_dia_venda"], 5, 2));
	$da = strval(substr($row["vendas_dia_venda"], 8, 2));
	$hr = strval(substr($row["vendas_dia_venda"], 11, 2));
	$mi = strval(substr($row["vendas_dia_venda"], 14, 2));
	$data_venda = date("d/m/Y H:i:s", mktime($hr, $mi, 0, $mo, $da, $yr));
	echo "<span style='color:#666666; font-size:8pt'>{$data_venda}<br />";
	if (($row['vendas_dia_pago']) && ($row['vendas_dia_pago'] != "0000-00-00")) {
		$vendas_dia_pago = implode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "-" : "/", $row['vendas_dia_pago'])));
		$pagamento = $vendas_dia_pago . " | " . $row['vendas_mes'];
	} else {
		if ($row['vendas_status'] == "8") {
			$pagamento = "Data não informada";
		} else {
			$pagamento = "Ainda não paga";
		}
	}
	echo "{$pagamento}</span>";
	if ($row['vendas_user'] == "atualizador.ws") {
		$yr = strval(substr($row["vendas_integracao_data"], 0, 4));
		$mo = strval(substr($row["vendas_integracao_data"], 5, 2));
		$da = strval(substr($row["vendas_integracao_data"], 8, 2));
		$hr = strval(substr($row["vendas_integracao_data"], 11, 2));
		$mi = strval(substr($row["vendas_integracao_data"], 14, 2));
		$vendas_integracao_data = date("d/m/Y H:i:s", mktime($hr, $mi, 0, $mo, $da, $yr));
		if ($row['vendas_integracao'] == 2) {
			$msg_robo = "Atualizado pelo Robô em " . $vendas_integracao_data;
		}
		if ($row['vendas_integracao'] == 3) {
			$msg_robo = "Verificado pelo Robô, sem alterações em " . $vendas_integracao_data;
		}
		echo "<a href='" . $link_venda . "' style='float: right; height: 36px; margin-top: -36px; display: unset;' title='{$msg_robo}'><img style='float: right; height: 36px;' src='sistema/imagens/robo_{$row['vendas_integracao']}.png'></a>";
	}
	echo "</td>";

	echo "<td width='15%'>";
	$result_status = mysql_query("SELECT * FROM sys_vendas_status WHERE status_id = " . $row['vendas_status'] . ";")
		or die(mysql_error());
	$row_status = mysql_fetch_array($result_status);
	if ($exibe_data_alteracao_status) {
		$data_alteracao_status = $row['registro_data'];
	}
	if ($row["vendas_status"] > 0) {
		echo "<a href='" . $link_venda . "'><span style='color:#666666; font-size:8pt'>{$row_status['status_nm']}  {$data_alteracao_status}</span></a>";
	} else {
		echo "<a href='" . $link_venda . "'><span style='color:#666666; font-size:8pt'>Enviada p/ implantação</span></a>";
	}
	echo "<a href='" . $link_venda . "'><img src='sistema/imagens/status_{$row['vendas_status']}.png'></a>";

	if ($row['vendas_produto'] == 1) {
		$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos ORDER BY contrato_etapa;")
			or die(mysql_error());
		$largura_fisicos = 50 / (mysql_num_rows($result_fisicos) - 1);
		echo "<div id='container-sbar'>";
		while ($row_fisicos = mysql_fetch_array($result_fisicos)) {
			if ($row_fisicos["contrato_id"] == $row['vendas_contrato_fisico']) {
				echo "<div class='sbar sbar-active' style='background-color: " . $row_fisicos['contrato_cor'] . "1);' title='Status Físico " . $row_fisicos['contrato_nome'] . "'>" . $row_fisicos['contrato_nome'] . "</div>";
			} else {
				echo "<div class='sbar' style='width: " . $largura_fisicos . "%;'><div class='sbar-inside'></div></div>";
			}
		}
		echo "</div>";
	}

	echo "</td>";
	echo "<td width='5%'>";
	echo "<a title='editar' href='index.php?option=com_k2&view=item&layout=item&id=341&Itemid=398&acao=edita_venda&vendas_id={$row['vendas_id']}'><img src='sistema/imagens/edit.png'></a>";
	echo "<img src='sistema/imagens/separa.png'>";
	if ($exclusao_vendas == 1) {
		echo "<a title='EXCLUIR VENDA Nº: {$row['vendas_id']}' href='index.php?option=com_k2&view=item&id=64:excluir-venda&Itemid=123&tmpl=component&print=1&vendas_id={$row['vendas_id']}&clients_cpf={$row['clients_cpf']}&clients_nm={$row['clients_nm']}&acao=exclui_venda' rel='lyteframe' rev='width: 550px; height: 400px; scroll:no;'><img src='sistema/imagens/delete.png'></a>";
	}
	echo "<a href='" . $link_venda . "'><span style='color:#666666; font-size:8pt'>{$row['vendas_id']}</span></a>";
	echo "</td></div></tr>";
	$exibindo = $exibindo + 1;
	$numero = $numero + 1;
}
?>



<?php
function getProdutoNomeById($produto_id)
{
	$result = mysql_query("SELECT produto_nome FROM sys_vendas_produtos WHERE produto_id = " . $produto_id . ";")
		or die(mysql_error());
	$row = mysql_fetch_array($result);
	return $row['produto_nome'];
}
?>