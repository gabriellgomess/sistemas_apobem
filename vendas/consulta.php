<?php if (!$_GET["carregado"]) : ?>
	<div align="center"><img src="sistema/imagens/loading.gif"></div>
	<meta http-equiv="Refresh" content="0; url=<?php echo $_SERVER['REQUEST_URI']; ?>&carregado=1">
<?php else : ?>
	<script type="text/javascript" src="sistema/vendas/js/datepicker.js"></script>
	<link href="sistema/vendas/css/datepicker.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		table.split-date-wrap {
			width: auto;
			margin-bottom: 0;
		}

		table.split-date-wrap td {
			padding: 0 0.2em 0.4em 0;
			border-bottom: 0 none;
		}

		table.split-date-wrap td input {
			margin-right: 0.3em;
		}

		table.split-date-wrap td label {
			font-size: 10px;
			font-weight: normal;
			display: block;
		}
	</style>
	<script type="text/javascript">
		$(document).on("change", "#select_all", function() {
			if ($(this).prop('checked')) {
				$("input[name='massa[]']").each(function() {
					$(this).prop('checked', true);
				});
			} else {
				$("input[name='massa[]']").each(function() {
					$(this).prop('checked', false);
				});
			}
		});
	</script>
	<script type="text/javascript">
		//Initialize first demo:
		ddaccordion.init({
			headerclass: "mypets2", //Shared CSS class name of headers group
			contentclass: "thepet2", //Shared CSS class name of contents group
			revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
			mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
			collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
			defaultexpanded: [1, 2], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
			onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
			animatedefault: false, //Should contents open by default be animated into view?
			scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
			persiststate: false, //persist state of opened contents within browser session?
			toggleclass: ["", "openpet2"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
			togglehtml: ["none", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
			animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
			oninit: function(expandedindices) { //custom code to run when headers have initalized
				//do nothing
			},
			onopenclose: function(header, index, state, isuseractivated) { //custom code to run whenever a header is opened or closed
				//do nothing
			}
		})
	</script>

	<?php
	//if ($user_id == 42){$user_id = 346;}

	include("sistema/utf8.php");
	$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";")
		or die(mysql_error());
	while ($row_grupo_user = mysql_fetch_array($result_grupo_user)) {
		if ($row_grupo_user['id'] == '43') {
			$supervisor_treinamento = 1;
			$administracao = 0;
		}
		if ($row_grupo_user['id'] == '56') {
			$seguros_consignado = 1;
			$administracao = 1;
		}
		if ($row_grupo_user['id'] == '58') {
			$coordenador_plataformas = 1;
			$administracao = 0;
		}
		if ($row_grupo_user['id'] == '59') {
			$consultor_pos_venda = 1;
			$administracao = 0;
		}
		if ($row_grupo_user['id'] == '61') {
			$operacional_equipes = 1;
			$administracao = 1;
		}
		if ($row_grupo_user['id'] == '37') {
			$supervisor_equipe_vendas = 1;
			$administracao = 0;
		}
		if ($row_grupo_user['id'] == '62') {
			$operacional_seguros_consignado = 1;
			$administracao = 1;
		}
		if ($row_grupo_user['id'] == '64') {
			$retencao_safra = 1;
			$administracao = 0;
		}
		if ($row_grupo_user['id'] == '76') {
			$gerente_plataformas = 1;
			$administracao = 0;
		}
		if ($row_grupo_user['id'] == '77') {
			$diretoria_empresa = 1;
			$administracao = 1;
			$diretoria = 1;
		}
		if ($row_grupo_user['id'] == '31') {
			$grupo_credito = 1;
		}
		if ($row_grupo_user['id'] == '32') {
			$grupo_seguros = 1;
		}
		if ($row_grupo_user['id'] == '40') {
			$operacional_fonado = 1;
			$administracao = 1;
		}
		if ($row_grupo_user['id'] == '41') {
			$operacional_fisico = 1;
			$administracao = 1;
		}
	}

	$query = mysql_query("UPDATE jos_users SET atendimento='0', atendimento_fim='" . date('Y-m-d H:i:s') . "' WHERE id='$userid' AND atendimento='1';") or die(mysql_error());

	$cpf = $_GET["cpf"];
	$clients_cat = $_GET["clients_cat"];
	if ($_GET["p"]) {
		$pagina = $_GET["p"];
	} else {
		$pagina = "1";
	}

	if (!$_GET["massa"]) {
		$url_consulta_clientes = $_SERVER['REQUEST_URI'];
		$query = mysql_query("UPDATE jos_users SET url_consulta_clientes='$url_consulta_clientes' WHERE id='$user_id';") or die(mysql_error());
	}

	if ($_GET["filtro_data1"]) {
		$filtro_data1 = $_GET["filtro_data1"];
	} else {
		$filtro_data1 = "1";
	}
	if ($_GET["filtro_data2"]) {
		$filtro_data2 = $_GET["filtro_data2"];
	} else {
		$filtro_data2 = "2";
	}
	if ($filtro_data1 == "1") {
		$normal_3_4 = "vendas_dia_imp";
		$normal_3_4_hr_ini = "'";
		$normal_3_4_hr_fim = "'";
	}
	if ($filtro_data1 == "2") {
		$normal_3_4 = "vendas_dia_pago";
		$normal_3_4_hr_ini = "'";
		$normal_3_4_hr_fim = "'";
	}
	if ($filtro_data1 == "3") {
		$normal_3_4 = "vendas_dia_venda";
		$normal_3_4_hr_ini = " 00:00:00'";
		$normal_3_4_hr_fim = " 23:59:59'";
	}
	if ($filtro_data1 == "4") {
		$normal_3_4 = "vendas_envio_data";
		$normal_3_4_hr_ini = "'";
		$normal_3_4_hr_fim = "'";
	}
	if ($filtro_data1 == "5") {
		$normal_3_4 = "vendas_import_data";
		$normal_3_4_hr_ini = " 00:00:00'";
		$normal_3_4_hr_fim = " 23:59:59'";
	}
	if ($filtro_data1 == "6") {
		$normal_3_4 = "vendas_alteracao";
		$normal_3_4_hr_ini = " 00:00:00'";
		$normal_3_4_hr_fim = " 23:59:59'";
	}
	if ($filtro_data1 == "7") {
		$normal_3_4 = "sys_vendas_compras.compra_venc";
		$normal_3_4_hr_ini = "'";
		$normal_3_4_hr_fim = "'";
		$join_banco_compra = " INNER JOIN sys_vendas_compras ON sys_vendas.vendas_id = sys_vendas_compras.vendas_id";
	}

	if ($filtro_data2 == "1") {
		$normal_5_6 = "vendas_dia_imp";
		$normal_5_6_hr_ini = "'";
		$normal_5_6_hr_fim = "'";
	}
	if ($filtro_data2 == "2") {
		$normal_5_6 = "vendas_dia_pago";
		$normal_5_6_hr_ini = "'";
		$normal_5_6_hr_fim = "'";
	}
	if ($filtro_data2 == "3") {
		$normal_5_6 = "vendas_dia_venda";
		$normal_5_6_hr_ini = " 00:00:00'";
		$normal_5_6_hr_fim = " 23:59:59'";
	}
	if ($filtro_data2 == "4") {
		$normal_5_6 = "vendas_envio_data";
		$normal_5_6_hr_ini = "'";
		$normal_5_6_hr_fim = "'";
	}
	if ($filtro_data2 == "5") {
		$normal_5_6 = "vendas_import_data";
		$normal_5_6_hr_ini = " 00:00:00'";
		$normal_5_6_hr_fim = " 23:59:59'";
	}
	if ($filtro_data2 == "6") {
		$normal_5_6 = "vendas_alteracao";
		$normal_5_6_hr_ini = " 00:00:00'";
		$normal_5_6_hr_fim = " 23:59:59'";
	}

	if ($_GET["dp-normal-5"]) {
		$pag_data_ini = $_GET["dp-normal-5"];
		$data_ini = implode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "-" : "/", $_GET["dp-normal-5"])));
		$select_data_ini = " AND " . $normal_5_6 . " >= '" . $data_ini . $normal_5_6_hr_ini;
	} else {
		$select_data_ini = "";
	}

	if ($_GET["dp-normal-6"]) {
		$pag_data_fim = $_GET["dp-normal-6"];
		$data_fim = implode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "-" : "/", $_GET["dp-normal-6"])));
		$select_data_fim = " AND " . $normal_5_6 . " <= '" . $data_fim . $normal_5_6_hr_fim;
	} else {
		$select_data_fim = "";
	}

	if ($_GET["dp-normal-3"]) {
		$pag_data_imp_ini = $_GET["dp-normal-3"];
		$data_imp_ini = implode(preg_match("~\/~", $_GET["dp-normal-3"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-3"]) == 0 ? "-" : "/", $_GET["dp-normal-3"])));
		$select_data_imp_ini = " AND " . $normal_3_4 . " >= '" . $data_imp_ini . $normal_3_4_hr_ini;
	} else {
		$select_data_imp_ini = "";
	}

	if ($_GET["dp-normal-4"]) {
		$pag_data_imp_fim = $_GET["dp-normal-4"];
		$data_imp_fim = implode(preg_match("~\/~", $_GET["dp-normal-4"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-4"]) == 0 ? "-" : "/", $_GET["dp-normal-4"])));
		$select_data_imp_fim = " AND " . $normal_3_4 . " <= '" . $data_imp_fim . $normal_3_4_hr_fim;
	} else {
		$select_data_imp_fim = "";
	}

	$vendas_consultor = $_GET["vendas_consultor"];

	$vendas_promotora = $_GET["vendas_promotora"];
	if ($_GET["vendas_promotora"]) {
		$select_promotora = " AND vendas_promotora like '%" . $vendas_promotora . "%'";
	} else {
		$select_promotora = "";
	}

	$vendas_id = $_GET["vendas_id"];
	if ($_GET["vendas_id"]) {
		$select_id = " AND sys_vendas.vendas_id = '" . $vendas_id . "'";
	} else {
		$select_id = "";
	}

	$vendas_proposta = $_GET["vendas_proposta"];
	if ($_GET["vendas_proposta"]) {
		$select_proposta = " AND vendas_proposta = '" . $vendas_proposta . "'";
	} else {
		$select_proposta = "";
	}

	$vendas_tabela = $_GET["vendas_tabela"];
	if ($_GET["vendas_tabela"]) {
		$select_tabela = " AND vendas_tabela = '" . $vendas_tabela . "'";
	} else {
		$select_tabela = "";
	}

	$vendas_portabilidade = $_GET["vendas_portabilidade"];
	if ($_GET["vendas_portabilidade"]) {
		$select_portabilidade = " AND vendas_portabilidade = '" . $vendas_portabilidade . "'";
	} else {
		$select_portabilidade = "";
	}

	$vendas_vendedor = $_GET["vendas_vendedor"];
	if ($_GET["vendas_vendedor"]) {
		$select_vendedor = " AND vendas_vendedor = '" . $vendas_vendedor . "'";
	} else {
		$select_vendedor = "";
	}

	$nome = $_GET["nome"];
	if ($_GET["nome"]) {
		$select_nome = " AND (clients_nm like '%" . $nome . "%' OR cliente_nome like '%" . $nome . "%')";
	} else {
		$select_nome = "";
	}

	$prec = $_GET["prec"];
	if ($_GET["prec"]) {
		$select_prec = " AND sys_clients.clients_prec_cp like '%" . $prec . "%'";
	} else {
		$select_prec = "";
	}

	$vendas_turno = $_GET["vendas_turno"];
	if ($_GET["vendas_turno"]) {
		$select_turno = " AND vendas_turno = '" . $vendas_turno . "'";
	} else {
		$select_turno = "";
	}

	$vendas_envio = $_GET["vendas_envio"];
	if ($_GET["vendas_envio"]) {
		$select_envio = " AND vendas_envio = '" . $vendas_envio . "'";
	} else {
		$select_envio = "";
	}

	$vendas_produto = $_GET["vendas_produto"];
	if ($_GET["vendas_produto"]) {
		$select_produto = " AND vendas_produto = '" . $vendas_produto . "'";
	} else {
		$select_produto = "";
	}

	$vendas_intencionada = $_GET["vendas_intencionada"];
	if ($_GET["vendas_intencionada"]) {
		$select_intencionada = " AND vendas_intencionada = '" . $vendas_intencionada . "'";
	} else {
		$select_intencionada = "";
	}

	$vendas_pos_venda = $_GET["vendas_pos_venda"];
	if ($_GET["vendas_pos_venda"]) {
		$select_pos_venda = " AND vendas_pos_venda = '" . $vendas_pos_venda . "'";
	} else {
		$select_pos_venda = "";
	}

	if ($_GET["vendas_tipo_tabela"]) {
		$vendas_tipo_tabela = $_GET["vendas_tipo_tabela"];
		$select_tipo_tabela = " AND tabela_tipo = '" . $vendas_tipo_tabela . "'";
		$join_tabela = " INNER JOIN sys_vendas_tabelas ON sys_vendas.vendas_tabela = sys_vendas_tabelas.tabela_id";
	} else {
		$select_tipo_tabela = "";
		$join_tabela = "";
	}

	if ($_GET["vendas_banco_compra"]) {
		$vendas_banco_compra = $_GET["vendas_banco_compra"];
		$select_banco_compra = " AND compra_banco = '" . $vendas_banco_compra . "'";
		$join_banco_compra = " INNER JOIN sys_vendas_compras ON sys_vendas.vendas_id = sys_vendas_compras.vendas_id";
	}

	$vendas_envio_objeto = $_GET["vendas_envio_objeto"];
	if ($_GET["vendas_envio_objeto"] == "vazio") {
		$select_envio_objeto = " AND (vendas_envio_objeto IS NULL OR vendas_envio_objeto='')";
	} elseif ($_GET["vendas_envio_objeto"] == "completo") {
		$select_envio_objeto = " AND (vendas_envio_objeto IS NOT NULL OR vendas_envio_objeto != '')";
	} elseif ($_GET["vendas_envio_objeto"]) {
		$select_envio_objeto = " AND vendas_envio_objeto like '%" . $vendas_envio_objeto . "%'";
	} else {
		$select_envio_objeto = "";
	}


	if ($_GET["Itemid"] == "499") {
		$select_produto = $select_produto . " AND (vendas_produto = '2')";
	} else {
		$select_produto = $select_produto . " AND vendas_produto != '2'";
	}

	$vendas_seguro_protegido = $_GET["vendas_seguro_protegido"];
	if ($_GET["vendas_seguro_protegido"]) {
		$select_protegido = " AND vendas_seguro_protegido = '" . $vendas_seguro_protegido . "'";
	} else {
		$select_protegido = "";
	}

	$vendas_estoque = $_GET["vendas_estoque"];
	if ($_GET["vendas_estoque"]) {
		$select_estoque = " AND vendas_estoque = 1";
	} else {
		$select_estoque = " AND vendas_estoque = 0";
	}

	//Condição para a multipla seleção do campo 'Contrato Físico'.
	if ($_GET["vendas_contrato_fisico"]) {
		$vendas_contrato_fisico = $_GET["vendas_contrato_fisico"];
		for ($i = 0; $i < count($vendas_contrato_fisico); $i++) {
			if ($vendas_contrato_fisico[$i] != "") {
				if ($i == 0) {
					$select_contrato = " AND (vendas_contrato_fisico = '" . $vendas_contrato_fisico[$i] . "'";
				} else {
					$select_contrato = $select_contrato . " OR vendas_contrato_fisico = '" . $vendas_contrato_fisico[$i] . "'";
				}
				$pag_contrato = $pag_contrato . "&vendas_contrato_fisico[]=" . $vendas_contrato_fisico[$i];
			}
			$aux_stat = $i;
		}
		if ($vendas_contrato_fisico[$aux_stat] != "") {
			$select_contrato = $select_contrato . ")";
		}
	}

	//Condição para a multipla seleção do campo 'Mês válido'.
	if ($_GET["vendas_mes"]) {
		$vendas_mes = $_GET["vendas_mes"];
		for ($i = 0; $i < count($vendas_mes); $i++) {
			if ($vendas_mes[$i] != "") {
				if ($i == 0) {
					$select_mes = " AND (vendas_mes = '" . $vendas_mes[$i] . "'";
				} else {
					$select_mes = $select_mes . " OR vendas_mes = '" . $vendas_mes[$i] . "'";
				}
				$pag_mes = $pag_mes . "&vendas_mes[]=" . $vendas_mes[$i];
			}
			$aux_stat = $i;
		}
		if ($vendas_mes[$aux_stat] != "") {
			$select_mes = $select_mes . ")";
		}
	}

	if ($_GET["bulk"] == "bulk") {
		if ($_GET["massa"]) {
			$massa = $_GET["massa"];
			for ($i = 0; $i < count($massa); $i++) {
				if ($massa[$i] != "") {
					if ($i == 0) {
						$ids_massa = $massa[$i];
					} else {
						$ids_massa = $ids_massa . ", " . $massa[$i];
					}
					$select_massa = " sys_vendas.vendas_id = '" . $massa[$i] . "'";

					if ($_GET["processar"] == "processar") {
						$update_fisico = "";
						if ($_GET["vendas_contrato_fisico_lote"]) {
							$update_fisico = "vendas_contrato_fisico='" . $_GET['vendas_contrato_fisico_lote'] . "'";
						}
						if ($_GET["vendas_contrato_fisico2_lote"]) {
							if ($update_fisico) {
								$update_fisico = $update_fisico . ", ";
							}
							$update_fisico = $update_fisico . "vendas_contrato_fisico2='" . $_GET['vendas_contrato_fisico2_lote'] . "'";
						}
						echo "UPDATE sys_vendas SET " . $update_fisico . " WHERE " . $select_massa . ";";
						$query = mysql_query("UPDATE sys_vendas SET " . $update_fisico . " WHERE " . $select_massa . ";") or die(mysql_error());
						$vendas_alteracao = date('Y-m-d H:i:s');
						$registro_contrato_fisico = $_GET['vendas_contrato_fisico_lote'];
						$sql = "INSERT INTO `sistema`.`sys_vendas_registros` (`registro_id`, 
					`vendas_id`, 
					`registro_usuario`, 
					`registro_obs`, 
					`registro_data`, 
					`registro_contrato_fisico`) 
					VALUES (NULL, 
					'$massa[$i]',
					'$username',
					'Atualizacao de Fisico.',
					'$vendas_alteracao',
					'$registro_contrato_fisico');";
						if (mysql_query($sql, $con)) {
							echo "Histórico Registrado com Sucesso. </br>";
						} else {
							die('Error: ' . mysql_error());
						}
					}

					$pag_massa = $pag_massa . "&massa[]=" . $massa[$i];
				}
				$input_massa = $input_massa . "<input name='massa[]' type='hidden' value='" . $massa[$i] . "' />";
				$aux_stat = $i;
			}
		}
	}

	if ($_GET["processar"] == "processar") {
		echo "<meta http-equiv='Refresh' content='0; url=index.php?option=com_k2&view=item&layout=item&id=64&Itemid=" . $_GET["Itemid"] . "'>";
	}

	if ($_GET["ordemi"]) {
		$ordem = $_GET["ordemi"];
	} else {
		$ordem = "sys_vendas.vendas_id";
	}
	if ($_GET["ordenacao"]) {
		$ordenacao = $_GET["ordenacao"];
	} else {
		$ordenacao = "DESC";
	}
	if ($_GET["ordenacao"] == "ASC") {
		$link_ordem = "DESC";
		$img_ordem = "<img src='sistema/imagens/asc.png'>";
	} else {
		$link_ordem = "ASC";
		$img_ordem = "<img src='sistema/imagens/desc.png'>";
	}

	if ($_GET["nobjeto_retorno"]) {
		switch ($_GET["nobjeto_retorno"]) {
			case '1':
				$select_nobjeto_retorno = " AND ( vendas_retorno_objeto IS NOT NULL AND vendas_retorno_objeto != '' )";
				break;
			case '2':
			default:
				$select_nobjeto_retorno = " AND ( vendas_retorno_objeto IS NULL OR vendas_retorno_objeto = '' )";
				break;
		}
	}

	if ($_GET["contar"]) {
		$contagem = ", COUNT(sys_vendas.clients_cpf) AS contagem";
		$agrupamento = " GROUP BY sys_vendas.clients_cpf ";
		if ($_GET["ordemi"]) {
			$ordem = $_GET["ordemi"];
		} else {
			$ordem = "contagem";
		}
	} else {
		$agrupamento = "";
	}

	if (($supervisor_agentes == 1) && (!$supervisor_equipe_vendas) && (!$operacional_equipes)) {
		header("location: http://acionamento.grupofortune.com.br/sistema/index.php?option=com_k2&view=item&layout=item&id=64&Itemid=473");
	}

	if ($juridico) {
		$select_jud = " AND vendas_jud = '2'";
		if ($diretoria_juridico) {
			$diretoria = 1;
			$administracao = 1;
		}
	} else {
		$vendas_jud = $_GET["vendas_jud"];
		if ($_GET["vendas_jud"]) {
			$select_jud = " AND vendas_jud = '" . $vendas_jud . "'";
		} else {
			$select_jud = "";
		}
	}

	if ($_GET["vendas_status"]) {
		$vendas_status = $_GET["vendas_status"];
		for ($i = 0; $i < count($vendas_status); $i++) {
			if ($vendas_status[$i] != "") {
				if ($i == 0) {
					$select_status = " AND (vendas_status = '" . $vendas_status[$i] . "'";
				} else {
					$select_status = $select_status . " OR vendas_status = '" . $vendas_status[$i] . "'";
				}
			}
			$aux_stat = $i;
		}
		if ($vendas_status[$aux_stat] != "") {
			$select_status = $select_status . ")";
		}
		for ($i = 0; $i < count($vendas_status); $i++) {
			if ($vendas_status[$i] != "") {
				$pag_status = $pag_status . "&vendas_status[]=" . $vendas_status[$i];
			}
		}
	} else {
		if ((($administracao == 1) || ($diretoria == 1) || ($financeiro == 1)) && ($_GET["Itemid"] != "499")) {
			$select_status = " AND (vendas_status <= '12' OR vendas_status >= '15')";
		}

		$select_status = $select_status . " AND (vendas_status != '100' OR vendas_consultor = '" . $user_id . "')";
	}

	if ($_GET["vendas_tipo_contrato"]) {
		$vendas_tipo_contrato = $_GET["vendas_tipo_contrato"];
		for ($i = 0; $i < count($vendas_tipo_contrato); $i++) {
			if ($vendas_tipo_contrato[$i] != "") {
				if ($i == 0) {
					$select_tipo = " AND (vendas_tipo_contrato = '" . $vendas_tipo_contrato[$i] . "'";
				} else {
					$select_tipo = $select_tipo . " OR vendas_tipo_contrato = '" . $vendas_tipo_contrato[$i] . "'";
				}
			}
			$aux_stat = $i;
		}
		if ($vendas_tipo_contrato[$aux_stat] != "") {
			$select_tipo = $select_tipo . ")";
		}
		for ($i = 0; $i < count($vendas_tipo_contrato); $i++) {
			if ($vendas_tipo_contrato[$i] != "") {
				$pag_tipo = $pag_tipo . "&vendas_tipo_contrato[]=" . $vendas_tipo_contrato[$i];
			}
		}
	}

	if ($_GET["vendas_orgao"]) {
		$vendas_orgao = $_GET["vendas_orgao"];
		for ($i = 0; $i < count($vendas_orgao); $i++) {
			if ($vendas_orgao[$i] != "") {
				if ($i == 0) {
					$select_orgao = " AND (vendas_orgao like '" . $vendas_orgao[$i] . "'";
				} else {
					$select_orgao = $select_orgao . " OR vendas_orgao like '" . $vendas_orgao[$i] . "'";
				}
			}
			$aux_stat = $i;
		}
		if ($vendas_orgao[$aux_stat] != "") {
			$select_orgao = $select_orgao . ")";
		}
		for ($i = 0; $i < count($vendas_orgao); $i++) {
			if ($vendas_orgao[$i] != "") {
				$pag_orgao = $pag_orgao . "&vendas_orgao[]=" . $vendas_orgao[$i];
			}
		}
	}

	$result_user = mysql_query("SELECT nivel, unidade, equipe_id FROM jos_users WHERE id = '" . $user_id . "';")
		or die(mysql_error());
	$array_user_id = mysql_fetch_array($result_user);
	$user_nivel = $array_user_id["nivel"];
	$user_unidade = $array_user_id["unidade"];
	$user_equipe = $array_user_id["equipe_id"];

	$join_unidade = " INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id";

	if ($_GET["cliente_carteira"]) {
		$cliente_carteira = $_GET["cliente_carteira"];
		for ($i = 0; $i < count($cliente_carteira); $i++) {
			if ($cliente_carteira[$i] != "") {
				if ($i == 0) {
					$select_carteira = " AND (sys_inss_clientes.cliente_campanha = '" . $cliente_carteira[$i] . "'";
				} else {
					$select_carteira = $select_carteira . " OR sys_inss_clientes.cliente_campanha = '" . $cliente_carteira[$i] . "'";
				}
			}
			$aux_stat = $i;
		}
		if ($cliente_carteira[$aux_stat] != "") {
			$select_carteira = $select_carteira . ")";
		}
		for ($i = 0; $i < count($cliente_carteira); $i++) {
			if ($cliente_carteira[$i] != "") {
				$pag_carteira = $pag_carteira . "&cliente_carteira[]=" . $cliente_carteira[$i];
			}
		}
	} else {
		$select_carteira = "";
	}


	if ($administracao == 1) {
		$vendas_consultor = $_GET["vendas_consultor"];
		if ($_GET["vendas_consultor"]) {
			$select_consultor = " AND vendas_consultor = " . $vendas_consultor . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		} else {
			$select_consultor = " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		}
	} elseif ((($user_nivel == "5") && (!$supervisor_equipe_vendas)) || ($user_nivel == "6")) {
		if ($_GET["vendas_consultor"]) {
			$vendas_consultor = $_GET["vendas_consultor"];
			$select_consultor = " AND vendas_consultor = '" . $vendas_consultor . "' AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		} else {
			$select_consultor = " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		}
		$select_unidade = " AND jos_users.unidade = '" . $user_unidade . "'";
		//if ($supervisor_equipe_vendas == 1) {$select_equipe = " AND jos_users.equipe_id = '". $user_equipe ."' AND jos_users.equipe_id > 0"; $select_unidade="";}
	} elseif ($consultor_pos_venda) {
		$select_origem = "";
		$select_unidade = "";
		$select_equipe = "";
		$count_equipes = 0;
		$result_eq_posvenda = mysql_query("SELECT equipe_id FROM sys_equipes WHERE equipe_pos_venda = " . $user_id . ";")
			or die(mysql_error());
		while ($row_eq_posvenda = mysql_fetch_array($result_eq_posvenda)) {
			if ($row_eq_posvenda['equipe_id'] != "") {
				if ($count_equipes == 0) {
					$select_equipe = " AND (vendas_equipe = '" . $row_eq_posvenda['equipe_id'] . "'";
					$select_equipe_plataforma = " AND (equipe_id = '" . $row_eq_posvenda['equipe_id'] . "'";
				} else {
					$select_equipe = $select_equipe . " OR vendas_equipe = '" . $row_eq_posvenda['equipe_id'] . "'";
					$select_equipe_plataforma = $select_equipe_plataforma . " OR equipe_id = '" . $row_eq_posvenda['equipe_id'] . "'";
				}
				$count_equipes++;
			}
		}
		if ($count_equipes) {
			$select_equipe = $select_equipe . " OR vendas_consultor = '" . $user_id . "') AND (equipe_id > 0 OR vendas_consultor = '" . $user_id . "')";
			$select_equipe_plataforma = $select_equipe_plataforma . ") AND (equipe_id > 0 OR vendas_consultor = '" . $user_id . "')";
			$vendas_consultor = $_GET["vendas_consultor"];
			if ($_GET["vendas_consultor"]) {
				$select_consultor = " AND vendas_consultor = " . $vendas_consultor;
			}
		} else {
			$select_consultor = " AND vendas_consultor = " . $user_id . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		}
		$select_status = $select_status . " AND (((vendas_status = '8' OR vendas_status = '25') AND vendas_tipo_contrato != '4') OR (vendas_status = '35' AND vendas_tipo_contrato = '4')) AND vendas_pos_venda != 4";
	} elseif ($supervisor_equipe_vendas || $coordenador_plataformas || $gerente_plataformas || $seguros_consignado) {
		$select_origem = "";
		$select_unidade = "";
		$select_equipe = "";
		$select_consultor = "";
		$select_equipe_supervisor = "";
		$count_equipes = 0;
		if ($coordenador_plataformas) {
			$select_coordenador_plataforma = " OR equipe_coordenador = '" . $user_id . "'";
		}
		if ($gerente_plataformas) {
			$select_coordenador_plataforma = " OR equipe_tipo = '2'";
		}
		$result_eq_supervisor = mysql_query("SELECT equipe_id FROM sys_equipes WHERE equipe_supervisor = '" . $user_id . "'" . $select_coordenador_plataforma . ";")
			or die(mysql_error());
		while ($row_eq_supervisor = mysql_fetch_array($result_eq_supervisor)) {
			if ($row_eq_supervisor['equipe_id'] != "") {
				if ($count_equipes == 0) {
					$select_equipe = " AND (jos_users.equipe_id = '" . $row_eq_supervisor['equipe_id'] . "'";
				} else {
					$select_equipe = $select_equipe . " OR jos_users.equipe_id = '" . $row_eq_supervisor['equipe_id'] . "'";
				}
				$count_equipes++;
			}
		}
		if ($count_equipes) {
			$select_equipe_supervisor = $select_equipe . " OR jos_users.id = " . $user_id . ") AND (jos_users.equipe_id > 0 OR jos_users.id = " . $user_id . ")";
			$select_equipe = $select_equipe . " OR jos_users.id = " . $user_id . ") AND (jos_users.equipe_id > 0 OR jos_users.id = " . $user_id . ")";
			$vendas_consultor = $_GET["vendas_consultor"];
			if ($_GET["vendas_consultor"]) {
				$select_consultor = " AND vendas_consultor = " . $vendas_consultor;
			}
		} else {
			$select_consultor = " AND vendas_consultor = " . $user_id . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		}
	} elseif ($admin_internet) {
		$select_origem = " AND vendas_origem = '2'";
		$select_unidade = "";
		$select_equipe = "";
	} else {
		$select_consultor = " AND vendas_consultor = " . $user_id . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		$select_unidade = "";
	}

	if ($diretoria_empresa) {
		$select_origem = "";
		$select_unidade = "";
		$select_equipe = "";
		$select_consultor = "";
		$count_empresas = 0;

		$result_empresas_diretoria = mysql_query("SELECT cnpj_id FROM sys_cnpjs WHERE cnpj_diretor = '" . $user_id . "';")
			or die(mysql_error());
		while ($row_empresas_diretoria = mysql_fetch_array($result_empresas_diretoria)) {
			if ($row_empresas_diretoria['cnpj_id'] != "") {
				if ($count_empresas == 0) {
					$select_empresa = " AND (empresa = '" . $row_empresas_diretoria['cnpj_id'] . "'";
				} else {
					$select_empresa = $select_empresa . " OR empresa = '" . $row_empresas_diretoria['cnpj_id'] . "'";
				}
				$count_empresas++;
			}
		}
		if ($count_empresas) {
			$select_empresa = $select_empresa . " OR vendas_consultor = " . $user_id . ") AND empresa > 0";
		} else {
			$select_consultor = " AND vendas_consultor = " . $user_id . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		}
		$vendas_consultor = $_GET["vendas_consultor"];
		if ($_GET["vendas_consultor"]) {
			$select_consultor = " AND vendas_consultor = " . $vendas_consultor;
		}
	}

	if ($supervisor_unidade) {
		$select_origem = "";
		$select_unidade = "";
		$select_equipe = "";
		$select_consultor = "";
		$select_equipe_supervisor = "";
		$count_unidades = 0;
		$result_un_supervisor = mysql_query("SELECT empresa_nome FROM sys_empresas WHERE empresa_rep_credito LIKE '%," . $user_id . ",%' OR empresa_supervisores LIKE '%," . $user_id . ",%';")
			or die(mysql_error());
		while ($row_un_supervisor = mysql_fetch_array($result_un_supervisor)) {
			if ($row_un_supervisor['empresa_nome'] != "") {
				if ($count_unidades == 0) {
					$select_equipe = " AND (jos_users.unidade = '" . $row_un_supervisor['empresa_nome'] . "'";
				} else {
					$select_equipe = $select_equipe . " OR jos_users.unidade = '" . $row_un_supervisor['empresa_nome'] . "'";
				}
				$count_unidades++;
			}
		}
		if ($count_unidades) {
			$select_equipe_supervisor = $select_equipe . " OR jos_users.id = " . $user_id . ") AND (jos_users.unidade IS NOT NULL OR jos_users.id = " . $user_id . ")";
			$select_equipe = $select_equipe . " OR jos_users.id = " . $user_id . ") AND (jos_users.unidade IS NOT NULL OR jos_users.id = " . $user_id . ")";
			$vendas_consultor = $_GET["vendas_consultor"];
			if ($_GET["vendas_consultor"]) {
				$select_consultor = " AND vendas_consultor = " . $vendas_consultor;
			}
		} else {
			$select_consultor = " AND vendas_consultor = " . $user_id . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		}
	}

	if ($operacional_equipes) {
		$select_origem = "";
		$select_unidade = "";
		$select_equipe = "";
		$select_consultor = "";
		$select_equipe_supervisor = "";
		$count_equipes = 0;
		$result_eq_operacional = mysql_query("SELECT equipe_id FROM sys_equipes WHERE equipe_operacional LIKE '%" . $user_id . ",%';")
			or die(mysql_error());
		while ($row_eq_operacional = mysql_fetch_array($result_eq_operacional)) {
			if ($row_eq_operacional['equipe_id'] != "") {
				if ($count_equipes == 0) {
					$select_equipe = " AND (jos_users.equipe_id = '" . $row_eq_operacional['equipe_id'] . "'";
				} else {
					$select_equipe = $select_equipe . " OR jos_users.equipe_id = '" . $row_eq_operacional['equipe_id'] . "'";
				}
				$count_equipes++;
			}
		}
		if ($count_equipes) {
			$select_equipe_supervisor = $select_equipe . " OR jos_users.id = " . $user_id . ") AND (jos_users.equipe_id > 0 OR jos_users.id = " . $user_id . ")";
			$select_equipe = $select_equipe . " OR jos_users.id = " . $user_id . ") AND (jos_users.equipe_id > 0 OR jos_users.id = " . $user_id . ")";
			$vendas_consultor = $_GET["vendas_consultor"];
			if ($_GET["vendas_consultor"]) {
				$select_consultor = " AND vendas_consultor = " . $vendas_consultor;
			}
		} else {
			$select_consultor = " AND vendas_consultor = " . $user_id . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		}
	}

	if ($retencao_safra) {
		$select_unidade = "";
		$select_equipe = "";
		$select_consultor = "";
		$select_equipe_supervisor = "";
		$select_origem = " AND (vendas_tipo_contrato = '1')";
		$vendas_origem = "3";
		$select_bank = " AND vendas_banco like 'SAFRA'";
		$vendas_banco = "SAFRA";
	} else {
		$vendas_origem = $_GET["vendas_origem"];
		if ($_GET["vendas_origem"]) {
			$select_origem = " AND vendas_origem = '" . $vendas_origem . "'";
		} else {
			$select_origem = "";
		}

		$vendas_banco = "";
		if ($_GET["vendas_banco"]) {
			//$select_bank= " AND vendas_banco like '" . $vendas_banco . "'";
			$select_bank = implode("','", $_GET["vendas_banco"]);
			$select_bank = " AND vendas_banco IN ('" . $select_bank . "')";

			for ($i = 0; $i < count($_GET["vendas_banco"]); $i++) {
				if ($_GET["vendas_banco"][$i] != "") {
					$vendas_banco = $vendas_banco . "&vendas_banco[]=" . $_GET["vendas_banco"][$i];
				}
			}
		} else {
			$select_bank = "";
		}
	}

	if ($supervisor_treinamento == 1) {
		$select_origem = "";
		$select_unidade = "";
		$select_consultor = "";
		$select_equipe_supervisor = "";
		$select_equipe = " AND jos_users.situacao = '1'";
		if ($_GET["vendas_consultor"]) {
			$select_consultor = " AND vendas_consultor = " . $vendas_consultor;
		}
	}

	if (($administracao == 1) || ($user_nivel == "7")) {
		if ($_GET["equipe_id"]) {
			$equipe_id = $_GET["equipe_id"];
			for ($i = 0; $i < count($equipe_id); $i++) {
				if ($equipe_id[$i] != "") {
					if ($i == 0) {
						$select_equipe = $select_equipe . " AND (jos_users.equipe_id = '" . $equipe_id[$i] . "'";
					} else {
						$select_equipe = $select_equipe . " OR jos_users.equipe_id = '" . $equipe_id[$i] . "'";
					}
				}
				$aux_stat = $i;
			}
			if ($equipe_id[$aux_stat] != "") {
				$select_equipe = $select_equipe . ")";
			}
			for ($i = 0; $i < count($equipe_id); $i++) {
				if ($equipe_id[$i] != "") {
					$pag_equipe = $pag_equipe . "&equipe_id[]=" . $equipe_id[$i];
				}
			}
		}
	}

	if ($_GET["vendas_cartao_consig"]) {
		$vendas_cartao_consig = $_GET["vendas_cartao_consig"];
		$pag_vendas_cartao_consig = "&vendas_cartao_consig[]=" . implode("&vendas_cartao_consig[]=", $vendas_cartao_consig);
		$select_vendas_cartao_consig = " AND sys_vendas.vendas_cartao_consig IN (" . implode(', ', $vendas_cartao_consig) . ")";
	}

	if ($_GET["consultor_unidade"]) {
		$consultor_unidade = $_GET["consultor_unidade"];
		for ($i = 0; $i < count($consultor_unidade); $i++) {
			if ($consultor_unidade[$i] != "") {
				if ($i == 0) {
					$select_unidade = $select_unidade . " AND (jos_users.unidade = '" . $consultor_unidade[$i] . "'";
				} else {
					$select_unidade = $select_unidade . " OR jos_users.unidade = '" . $consultor_unidade[$i] . "'";
				}
			}
			$aux_stat = $i;
		}
		if ($consultor_unidade[$aux_stat] != "") {
			$select_unidade = $select_unidade . ")";
		}
		for ($i = 0; $i < count($consultor_unidade); $i++) {
			if ($consultor_unidade[$i] != "") {
				$pag_unidade = $pag_unidade . "&consultor_unidade[]=" . $consultor_unidade[$i];
			}
		}
	} else {
		$select_unidade = "";
	}

	$p = $_GET["p"];
	if (isset($p)) {
		$p = $p;
	} else {
		$p = 1;
	}
	if ($_GET["qnt"]) {
		$qnt = $_GET["qnt"];
	} else {
		$qnt = 20;
	}
	$inicio = ($p * $qnt) - $qnt;

	$filtros_sql = $select_prec .
		$select_nome .
		$select_id .
		$select_proposta .
		$select_tabela .
		$select_portabilidade .
		$select_vendedor .
		$select_state .
		$select_city .
		$select_bank .
		$select_data_ini .
		$select_data_fim .
		$select_data_imp_ini .
		$select_data_imp_fim .
		$select_status .
		$select_orgao .
		$select_tipo .
		$select_consultor .
		$select_unidade .
		$select_empresa .
		$select_carteira .
		$select_equipe .
		$select_promotora .
		$select_origem .
		$select_mes .
		$select_contrato .
		$select_envio .
		$select_envio_objeto .
		$select_intencionada .
		$select_pos_venda .
		$select_tipo_tabela .
		$select_banco_compra .
		$select_protegido .
		$select_jud .
		$select_estoque .
		$select_turno .
		$select_vendas_cartao_consig .
		$select_nobjeto_retorno;

	$filtros_sql_relatorios = $filtros_sql . $select_produto;
	$filtros_sql = $filtros_sql . $select_produto;

	$filtros_sql_pesquisa = $select_tabela .
		$select_portabilidade .
		$select_vendedor .
		$select_bank .
		$select_data_ini .
		$select_data_fim .
		// $select_data_imp_ini . 
		// $select_data_imp_fim . 
		$select_status .
		$select_orgao .
		$select_tipo .
		$select_consultor .
		$select_unidade .
		$select_empresa .
		$select_equipe .
		$select_promotora .
		$select_origem .
		$select_mes .
		$select_contrato .
		$select_envio .
		$select_envio_objeto .
		$select_intencionada .
		$select_pos_venda .
		$select_protegido .
		$select_jud .
		$select_estoque .
		$select_turno .
		$select_vendas_cartao_consig .
		$select_nobjeto_retorno;

	if ($_GET["notificacao_revisadas"]) {
		$alteracao_status_data_format = ", DATE_FORMAT(registro_data,'%d/%m/%Y') AS registro_data";
		$join_notificacao_revisadas = " INNER JOIN sys_vendas_registros ON sys_vendas.vendas_id = sys_vendas_registros.vendas_id";
		$filtro_notificacao_revisadas = " AND (registro_status_old != registro_status AND (registro_status = '22' OR registro_status = '23') ) ";
		$exibe_data_alteracao_status = true;
	}

	if (($_GET["buscar"]) || (!$administracao)) {

		echo "<pre style='display: none;'>";
		echo "SELECT *" . $contagem . $alteracao_status_data_format . " FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)" .
			$join_unidade . $join_tabela . $join_banco_compra . $join_notificacao_revisadas .
			" WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" .
			$filtros_sql . $filtro_notificacao_revisadas .
			$agrupamento . " ORDER BY " . $ordem . " " . $ordenacao . " LIMIT " . $inicio . ", " . $qnt . ";";
		echo "</pre>";

		$result = mysql_query("SELECT *" . $contagem . $alteracao_status_data_format . ", sys_vendas.clients_cpf AS cpf FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)" .
			$join_unidade . $join_tabela . $join_banco_compra . $join_notificacao_revisadas .
			" WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" .
			$filtros_sql . $filtro_notificacao_revisadas .
			$agrupamento . " ORDER BY " . $ordem . " " . $ordenacao . " LIMIT " . $inicio . ", " . $qnt . ";")
			or die(mysql_error());
	}

	if ($administracao == 1) {
		if ($frame_revisadas == 1) {
			$result_pendentes_total = mysql_query("SELECT COUNT(vendas_id) AS total FROM sys_vendas 
		INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id 
		WHERE (vendas_status = '22' 
		OR vendas_status = '23' 
		OR vendas_status = '12'
		OR vendas_status = '34') 
		AND jos_users.nivel <> 4 AND jos_users.nivel <> 8;") or die(mysql_error());
			$row_pendentes_total = mysql_fetch_array($result_pendentes_total);
			$pendentes = $row_pendentes_total["total"];
			if ($pendentes) {
				$result_pendentes = mysql_query("SELECT * FROM sys_vendas 
												LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
												LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf) 
												INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id
												WHERE (vendas_status = '22' 
													OR vendas_status = '23' 
													OR vendas_status = '12'
													OR vendas_status = '34') 
													AND jos_users.nivel <> 4 AND jos_users.nivel <> 8
												ORDER BY vendas_alteracao ASC;")
					or die(mysql_error());
			}
		}
		if ($frame_averbadas == 1) {
			$result_aprovadas_total = mysql_query("SELECT COUNT(vendas_id) AS total FROM sys_vendas WHERE vendas_status = '6' AND DATEDIFF(CURDATE(), vendas_dia_apr) >= '2';") or die(mysql_error());
			$row_aprovadas_total = mysql_fetch_array($result_aprovadas_total);
			$aprovadas = $row_aprovadas_total["total"];
			if ($aprovadas) {
				$result_aprovadas = mysql_query("SELECT *, DATEDIFF(CURDATE(), vendas_dia_apr) AS age FROM sys_vendas 
												LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
												LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf) 
												INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id
												WHERE vendas_status = '6' 
													AND DATEDIFF(CURDATE(), vendas_dia_apr) >= '2' 
													AND jos_users.nivel <> 4 AND jos_users.nivel <> 8
												ORDER BY age DESC;")
					or die(mysql_error());
			}
		}
		if ($frame_fracionadas == 1) {
			$result_fracionadas_total = mysql_query("SELECT COUNT(vendas_id) AS total FROM sys_vendas WHERE vendas_receita_fr >= '1' AND vendas_recebido_fr = '0' AND DATEDIFF(CURDATE(), vendas_dia_pago) >= '60';") or die(mysql_error());
			$row_fracionadas_total = mysql_fetch_array($result_fracionadas_total);
			$fracionadas = $row_fracionadas_total["total"];
			if ($fracionadas) {
				$result_fracionadas = mysql_query("SELECT *, DATEDIFF(CURDATE(), vendas_dia_pago) AS age FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf) 
			WHERE vendas_receita_fr >= '1' AND vendas_recebido_fr = '0' AND DATEDIFF(CURDATE(), vendas_dia_pago) >= '60' ORDER BY age DESC;")
					or die(mysql_error());
			}
		}
		if ($frame_autorizacao == 1) {
			$result_autorizacao_total = mysql_query("SELECT COUNT(vendas_id) AS total FROM sys_vendas WHERE vendas_status = '24';") or die(mysql_error());
			$row_autorizacao_total = mysql_fetch_array($result_autorizacao_total);
			$autorizacao = $row_autorizacao_total["total"];
			if ($autorizacao) {
				$result_autorizacao = mysql_query("SELECT * FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf) 
			WHERE vendas_status = '24' ORDER BY vendas_dia_venda ASC;")
					or die(mysql_error());
			}
		}
	}

	$result_calendar = mysql_query("SELECT * FROM sys_clients_calendar INNER JOIN sys_clients ON sys_clients_calendar.clients_cpf = sys_clients.clients_cpf WHERE username = '" . $username . "' ORDER BY calendar_date_schedule;")
		or die(mysql_error());
	?>
	<?php $curURL = $_SERVER["REQUEST_URI"]; ?>

	<?php if ($super_user || (($diretoria == 1) || ($sup_operacional_seg == 1) || ($sup_operacional == 1) || ($suporte_equipes == 1) || ($operacional_fisico == 1)) && ($_GET["buscar"]) && ((date('H') >= 12 && date('H') <= 13) || date('H') >= 18 || date('H:i:s') <= "09:00:00")) : ?>
		<?php
		$link_exportacao = "sistema/vendas/relatorios/credito_xls.php?cpf=" . $cpf . "&ordemi=" . $_GET["ordemi"] . "&ordenacao=" . $_GET["ordenacao"] . "&vendas_tipo_tabela=" . $_GET['vendas_tipo_tabela'] . "&vendas_banco_compra" . $_GET['vendas_banco_compra'] . "&filtros_sql=" . $filtros_sql_relatorios;
		?>
		<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_exportacao; ?>">Exportar para Excel</a><br>
	<?php endif; ?>


	<?php if (($diretoria == 1) || ($financeiro == 1)) : ?>

		<?php if ($vendas_mes) : ?>
			<?php $link_rel_receitas = "index.php?option=com_k2&view=item&layout=item&id=502&Itemid=123&somente_conteudo=1&somente_xls=1&filtros_sql=" . $filtros_sql; ?>
			<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_rel_receitas; ?>">Relatório de Receitas</a><br>
		<?php endif; ?>

		<?php if (($vendas_mes) && (($pag_status == "&vendas_status[]=9&vendas_status[]=8") || ($pag_status == "&vendas_status[]=8&vendas_status[]=9"))) : ?>
			<?php
			$mes_anterior = date("m/Y", strtotime("-1 months"));
			$mes_anterior2 = date("m/Y", strtotime("-2 months"));
			$mes_anterior3 = date("m/Y", strtotime("-3 months"));

			$link_rel_consultor = "index.php?option=com_k2&view=item&id=64:relatorio-do-consultor&Itemid=123&tmpl=component&print=1&acao=relatorio_consultor&vendas_id=" . $vendas_id . "&nome=" . $nome . "&prec=" . $prec . "&cpf=" . $cpf . $pag_mes . $pag_unidade . "&vendas_consultor=" . $vendas_consultor . $pag_status . $pag_contrato . $pag_tipo . $pag_orgao . "&vendas_promotora=" . $vendas_promotora . "&vendas_banco=" . $vendas_banco . "&dp-normal-3=" . $pag_data_imp_ini . "&dp-normal-4=" . $pag_data_imp_fim . "&dp-normal-5=" . $pag_data_ini . "&dp-normal-6=" . $pag_data_fim . "&filtro_data1=" . $GET['filtro_data1'] . "&filtro_data2=" . $GET['filtro_data2'];
			$link_rel_comissoes = "index.php?option=com_k2&view=item&id=64:relatorio-de-comissoes&Itemid=123&tmpl=component&print=1&somente_conteudo=1&acao=relatorio_comissoes&vendas_id=" . $vendas_id . "&nome=" . $nome . "&prec=" . $prec . "&cpf=" . $cpf . $pag_mes . $pag_unidade . $pag_status . $pag_contrato . $pag_tipo . $pag_orgao . "&vendas_promotora=" . $vendas_promotora . "&vendas_banco=" . $vendas_banco . "&dp-normal-3=" . $pag_data_imp_ini . "&dp-normal-4=" . $pag_data_imp_fim . "&dp-normal-5=" . $pag_data_ini . "&dp-normal-6=" . $pag_data_fim . "&filtro_data1=" . $GET['filtro_data1'] . "&filtro_data2=" . $GET['filtro_data2'];
			$link_rel_receitas = "index.php?option=com_k2&view=item&layout=item&id=502&Itemid=123&somente_conteudo=1&somente_xls=1&filtros_sql=" . $filtros_sql;
			$link_atualiza_lote = "index.php?option=com_k2&view=item&layout=item&id=368&Itemid=440&somente_conteudo=1&vendas_id=" . $vendas_id . "&nome=" . $nome . "&prec=" . $prec . "&cpf=" . $cpf . $pag_mes . $pag_unidade . $pag_status . $pag_contrato . $pag_tipo . $pag_orgao . "&vendas_promotora=" . $vendas_promotora . "&vendas_banco=" . $vendas_banco . "&dp-normal-3=" . $pag_data_imp_ini . "&dp-normal-4=" . $pag_data_imp_fim . "&dp-normal-5=" . $pag_data_ini . "&dp-normal-6=" . $pag_data_fim . "&filtro_data1=" . $GET['filtro_data1'] . "&filtro_data2=" . $GET['filtro_data2'];
			?>
			<?php if ($vendas_consultor) : ?>
				<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_rel_consultor; ?>">Relatório do Consultor</a><br>
			<?php elseif (($vendas_mes[0] == $mes_anterior) || ($vendas_mes[0] == $mes_anterior2) || ($vendas_mes[0] == $mes_anterior3)) : ?>
				<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_rel_comissoes; ?>">Relatório de Comissões</a>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ($_GET["erro_proposta"]) : ?>
		<div align="center" style="width: 100%; background-color: #fbc146; line-height: 36px; border-bottom-width: 2px; border-bottom-color: #d69e28; border-bottom-style: solid; font-weight: bold;">
			Venda <a target="_blank" href='index.php?option=com_k2&view=item&layout=item&id=341&Itemid=398&acao=edita_venda&vendas_id=<?php echo $_GET["erro_proposta"]; ?>'><?php echo $_GET["erro_proposta"]; ?></a> já possui o Nº de Proposta digitado.
		</div>
	<?php endif; ?>

	<?php
	/* 
#############
BUSCA RÁPIDA 
#############
*/
	$base_url = "/sistema/index.php?option=com_k2&view=item&layout=item&id=" . $_GET['id'] . "&Itemid=" . $_GET['Itemid'] . "&carregado=1&buscar=buscar";

	$url_pendentes = "&vendas_status[]=18&vendas_status[]=19&vendas_status[]=20";
	$url_atualizadas = "&ordemi=sys_vendas.vendas_alteracao&ordenacao=DESC";

	$result_mes = mysql_query("SELECT mes_nome FROM sys_vendas_mes ORDER BY mes_id DESC LIMIT 0,1;") or die(mysql_error());
	$row_mes = mysql_fetch_assoc($result_mes);
	$ultimo_mes = $row_mes['mes_nome'];

	$url_paga_concluida = "&vendas_status[]=8&vendas_status[]=9&vendas_mes[]=" . $ultimo_mes;

	$sql_fc_pc_mes = mysql_query("SELECT SUM(vendas_fortcoins) AS total_fortcoins
		FROM sys_vendas 
		INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id
		WHERE (vendas_status = 8 OR vendas_status = 9)
			AND jos_users.nivel != 4
			AND jos_users.nivel != 8
			AND vendas_estoque = 0
			AND vendas_produto != '2'
			" . $select_consultor . $select_equipe . "
			AND vendas_mes LIKE '" . $ultimo_mes . "' AND vendas_mes != '';") or die(mysql_error());
	$row_total_fc = mysql_fetch_array($sql_fc_pc_mes);
	$fc = $row_total_fc['total_fortcoins'];
	$fc = ($fc > 0) ? number_format($fc, 2, ',', '.') : '0';

	$sql_pendentes = mysql_query("SELECT 
		SUM( IF(vendas_status = '18', 1, 0 ) ) AS pendente_banco,
		SUM( IF(vendas_status = '19', 1, 0 ) ) AS pendente_conf,
		SUM( IF(vendas_status = '20', 1, 0 ) ) AS pendente_cip
		FROM sys_vendas 
		INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id
		WHERE (vendas_status = '18' OR vendas_status = '19' OR vendas_status = '20')
			AND jos_users.nivel != 4
			AND jos_users.nivel != 8
			AND vendas_estoque = 0
			AND vendas_produto != '2'
			" . $select_consultor . $select_equipe . ";") or die(mysql_error());
	$row_pendentes_btn = mysql_fetch_array($sql_pendentes);

	?>
	<style type="text/css">
		a.busca_rapida_btn {
			border-radius: 5px;
			line-height: 1.4;
			display: inline-block;
			height: 89px;
			width: 200px;
			color: #fff;
			background: #d27221;
			box-shadow: 0px 1px 2px 1px #999;
			text-align: left;
			margin: -30px 10px 10px 10px;
			position: relative;
			font-size: 12px;
		}

		a.busca_rapida_btn:hover {
			color: #fff;
			background: #7ea8e4;
		}

		a.busca_rapida_btn span {
			text-align: left;
		}

		span.busca_rapida_icon {
			top: 37px;
			left: 15px;
			position: absolute;
			font-size: 18px;
		}

		span.busca_rapida_txt {
			position: relative;
			left: 45px;
		}
	</style>
	<div class="busca_rapida_container" style="text-align: center; display: inherit;">
		<a class="busca_rapida_btn" style="padding: 5px !important;" href="<?php echo $base_url . $url_atualizadas; ?>">
			<span class='busca_rapida_icon'>
				<i class="fas fa-sync-alt"></i>
			</span>
			<span class='busca_rapida_txt'>
				<span style="font-weight: bold;">Atualizadas Recentemente</span>
			</span>
		</a>
		<a class="busca_rapida_btn" style="padding: 5px !important;" href="<?php echo $base_url . $url_paga_concluida; ?>">
			<span class='busca_rapida_icon'>
				<i class="fas fa-dollar-sign"></i>
			</span>
			<span class='busca_rapida_txt'>
				<span style="font-weight: bold;">Pagas / Concluídas</span><br>
				<span style="font-weight: bold;">Mês: <?php echo $ultimo_mes; ?></span><br>
				<span style="font-size: 15px;">BS¢ <?php echo $fc; ?></span>
			</span>
		</a>
		<a class="busca_rapida_btn" style="padding: 5px !important;" href="<?php echo $base_url . $url_pendentes; ?>">
			<span class='busca_rapida_icon'>
				<i class="fas fa-exclamation-triangle"></i>
			</span>
			<span class='busca_rapida_txt'>
				<span style="font-weight: bold;">Pendentes</span><br>
				<span style="font-size: 10px;">
					BCO: <?php echo $row_pendentes_btn['pendente_banco']; ?> vendas.<br>
					CONF: <?php echo $row_pendentes_btn['pendente_conf']; ?> vendas.<br>
					CIP: <?php echo $row_pendentes_btn['pendente_cip']; ?> vendas.<br>
					Total: <?php echo $row_pendentes_btn['pendente_banco'] + $row_pendentes_btn['pendente_conf'] + $row_pendentes_btn['pendente_cip']; ?>
				</span>
			</span>
		</a>
	</div>
	<?php
	/* 
#############
FIM - BUSCA RÁPIDA 
#############
*/
	?>
	<form action="index.php" method="GET">
		<input id="sis_campo" name="option" type="hidden" id="option" value="com_k2" />
		<input id="sis_campo" name="view" type="hidden" id="view" value="item" />
		<input id="sis_campo" name="id" type="hidden" id="id" value="64" />
		<input id="sis_campo" name="Itemid" type="hidden" id="Itemid" value="<?php echo $_GET["Itemid"]; ?>" />
		<input id="sis_campo" name="carregado" type="hidden" id="carregado" value="1" />

		<div class="css_form_container">
			<div class="css_form_group">
				<div class="css_form_campo">Código: <input id="vendas_id" name="vendas_id" value="<?php echo $vendas_id; ?>" type="text" utils="inteiro" maxlength="6" size="6" /></div>
				<div class="css_form_campo">CPF: <input id="cpf" name="cpf" value="<?php echo $cpf; ?>" type="text" onkeyup="cpfSomenteNumero(this)" size="11" /></div>
				<div class="css_form_campo">Nome: <input name="nome" value="<?php echo $nome; ?>" type="text" size="25" /></div>
				<div class="css_form_campo">Nº da Proposta: <input id="vendas_proposta" name="vendas_proposta" value="<?php echo $vendas_proposta; ?>" type="text" maxlength="20" size="12" /></div>
				<div class="css_form_campo">Nº da Portabilidade: <input id="vendas_portabilidade" name="vendas_portabilidade" value="<?php echo $vendas_portabilidade; ?>" type="text" maxlength="20" size="12" /></div>
				<div class="css_form_campo">Matrícula: <input id="prec" name="prec" value="<?php echo $prec; ?>" type="text" maxlength="10" size="10" /></div>
				<div class="css_form_campo">Código da Tabela: <input id="vendas_tabela" name="vendas_tabela" value="<?php echo $vendas_tabela; ?>" type="text" maxlength="10" size="10" /></div>
				<?php if ($frame_fisicos || $financeiro || $super_user) : ?>
					<div class="css_form_campo">Código de Rastreio: <input name="vendas_envio_objeto" value="<?php echo $vendas_envio_objeto; ?>" type="text" size="20" /></div>
				<?php endif; ?>
				<?php if ($user_unidade == "MD Empresas") : ?>
					<div class="css_form_campo">Vendedor: <input name="vendas_vendedor" value="<?php echo $vendas_vendedor; ?>" type="text" size="25" /></div>
				<?php endif; ?>
			</div>
			<div class="css_form_group">
				<div class="css_form_campo" style="text-align: right;">
					<?php if ($diretoria) : ?>
						<strong>Contabilizar o Nº de Vendas de cada cliente.</strong><input type="checkbox" name="contar" value="1" <?php if ($_GET["contar"]) {
																																		echo "checked";
																																	} ?>><br />
					<?php endif; ?>
					<?php if (!$retencao_safra == 1) : ?>
						<strong>Ver Vendas em estoque.</strong><input type="checkbox" name="vendas_estoque" value="1" <?php if ($_GET["vendas_estoque"]) {
																															echo "checked";
																														} ?>>
					<?php endif; ?>
				</div>
				<div class="css_form_campo">
					<a href="index.php?option=com_k2&view=item&layout=item&id=64&Itemid=<?php echo $_GET["Itemid"]; ?>"><button name="limpar" type="button" value="limpar">Limpar</button></a>
					<button name="Pesquisa anterior" type="reset" value="Pesquisa anterior">&#8635;</button>
					<button name="buscar" type="submit" value="buscar">Buscar</button>
				</div>
			</div>
		</div>

		<h3 class="mypets2" style="text-align: center;">Busca Avançada:</h3>
		<div class="thepet2">

			<div class="css_form_container">
				<div class="css_form_group grupo_a">
					<div class="css_form_group">
						<div class="css_form_campo">
							<select name="filtro_data1">
								<option value="1" <?php if ($filtro_data1 == "1") {
														echo " selected";
													} ?>>Data de Implantação</option>
								<option value="2" <?php if ($filtro_data1 == "2") {
														echo " selected";
													} ?>>Data de Pagamento</option>
								<option value="3" <?php if ($filtro_data1 == "3") {
														echo " selected";
													} ?>>Data da Venda</option>
								<option value="4" <?php if ($filtro_data1 == "4") {
														echo " selected";
													} ?>>Envio do Físico</option>
								<option value="5" <?php if ($filtro_data1 == "5") {
														echo " selected";
													} ?>>Data de Importação</option>
								<option value="6" <?php if ($filtro_data1 == "6") {
														echo " selected";
													} ?>>Data Alteração</option>
								<option value="7" <?php if ($filtro_data1 == "7") {
														echo " selected";
													} ?>>Data Vencimento</option>
							</select>
							<?php $data_field = implode(preg_match("~\/~", $data_imp_ini) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_imp_ini) == 0 ? "-" : "/", $data_imp_ini))); ?>
							<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-3" name="dp-normal-3" maxlength="10" size="10" value="<?php echo $data_field; ?>" placeholder="dd/mm/aaaa" />
							<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-4" name="dp-normal-4" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-4"]; ?>" placeholder="dd/mm/aaaa" />
						</div>
						<div class="css_form_campo">
							<select name="filtro_data2">
								<option value="2" <?php if ($filtro_data2 == "2") {
														echo " selected";
													} ?>>Data de Pagamento</option>
								<option value="1" <?php if ($filtro_data2 == "1") {
														echo " selected";
													} ?>>Data de Implantação</option>
								<option value="3" <?php if ($filtro_data2 == "3") {
														echo " selected";
													} ?>>Data da Venda</option>
								<option value="5" <?php if ($filtro_data2 == "5") {
														echo " selected";
													} ?>>Data de Importação</option>
								<option value="6" <?php if ($filtro_data2 == "6") {
														echo " selected";
													} ?>>Data Alteração</option>
							</select>
							<?php $data_field = implode(preg_match("~\/~", $data_imp_ini) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_imp_ini) == 0 ? "-" : "/", $data_imp_ini))); ?>
							<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-5" name="dp-normal-5" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-5"]; ?>" placeholder="dd/mm/aaaa" />
							<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-6" name="dp-normal-6" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-6"]; ?>" placeholder="dd/mm/aaaa" />
						</div>
					</div>

					<div class="css_form_group sel_180px">
						<?php if (!$retencao_safra == 1) : ?>
							<div class="css_form_campo">
								<select name="vendas_promotora">
									<optgroup label="Promotora">
										<option value="">---- Indiferente ----</option>
										<?php
										$optgroup = "";
										$count_g = 0;
										if (!$vendas_promotora) {
											echo "<option value='' disabled selected>------ Promotora ------</option>";
										}
										$result_promo = mysql_query("SELECT promotora_nome FROM sys_vendas_promotoras ORDER BY promotora_nome;")
											or die(mysql_error());
										while ($row_promo = mysql_fetch_array($result_promo)) {
											if ($row_promo["promotora_nome"] == $vendas_promotora) {
												$selected_promo = "selected";
											} else {
												$selected_promo = "";
											}
											if ($optgroup != $row_promo["promotora_nome"][0]) {
												if ($count_g > 0) {
													echo "</optgroup>";
												}
												echo "<optgroup label=" . $row_promo["promotora_nome"][0] . ">";
												$optgroup = $row_promo["promotora_nome"][0];
												$count_g++;
											}
											echo "<option value='{$row_promo['promotora_nome']}'{$selected_promo}>{$row_promo['promotora_nome']}</option>";
										}
										?>
									</optgroup>
								</select>
							</div>
							<div class="css_form_campo">
								<select name="vendas_origem">
									<optgroup label="Origem da Venda">
										<option value="">---- Indiferente ----</option>
										<?php
										if (!$vendas_origem) {
											echo "<option value='' disabled selected>------ Origem da Venda ------</option>";
										}
										$result_origem = mysql_query("SELECT * FROM sys_vendas_origens ORDER BY origem_nome;")
											or die(mysql_error());
										while ($row_origem = mysql_fetch_array($result_origem)) {
											if ($row_origem["origem_id"] == $vendas_origem) {
												$selected_promo = " selected";
											} else {
												$selected_promo = "";
											}
											echo "<option value='{$row_origem['origem_id']}'{$selected_promo}>{$row_origem['origem_nome']}</option>";
										}
										?>
									</optgroup>
								</select>
							</div>
							<div class="css_form_campo">
								<select name="vendas_intencionada">
									<optgroup label="Venda Intencionada">
										<option value="">---- Indiferente ----</option>
										<?php
										if (!$vendas_intencionada) {
											echo "<option value='' disabled selected>------ Venda Intencionada ------</option>";
										}
										?>
										<option value="1" <?php if ($vendas_intencionada == "1") {
																echo "selected";
															} ?>>Não</option>
										<option value="2" <?php if ($vendas_intencionada == "2") {
																echo "selected";
															} ?>>Sim</option>
									</optgroup>
								</select>
							</div>
							<div class="css_form_campo">
								<select name="vendas_tipo_tabela">
									<optgroup label="Tipo de Tabela">
										<option value="">---- Indiferente ----</option>
										<?php
										if (!$vendas_tipo_tabela) {
											echo "<option value='' disabled selected>------ Tipo de Tabela ------</option>";
										}
										?>
										<option value="NORMAL" <?php if ($vendas_tipo_tabela == "NORMAL") {
																	echo "selected";
																} ?>>NORMAL</option>
										<option value="FLEX" <?php if ($vendas_tipo_tabela == "FLEX") {
																	echo "selected";
																} ?>>FLEX</option>
										<option value="TOP" <?php if ($vendas_tipo_tabela == "TOP") {
																echo "selected";
															} ?>>TOP</option>
									</optgroup>
								</select>
							</div>
							<div class="css_form_campo">
								<select name="vendas_banco_compra">
									<optgroup label="Banco da dívida comprada">
										<option value="">---- Indiferente ----</option>
										<?php
										if (!$vendas_banco_compra) {
											echo "<option value='' disabled selected>------ Banco da dívida comprada ------</option>";
										}
										$result_banco_compra = mysql_query("SELECT * FROM sys_vendas_bancos_compra ORDER BY banco_codigo;")
											or die(mysql_error());
										while ($row_banco_compra = mysql_fetch_array($result_banco_compra)) {
											if (($row_banco_compra["banco_id"] == $vendas_banco_compra) && ($vendas_banco_compra)) {
												$selected_banco_compra = " selected";
											} else {
												$selected_banco_compra = "";
											}
											echo "<option value='{$row_banco_compra['banco_id']}'{$selected_banco_compra}>{$row_banco_compra['banco_codigo']} - {$row_banco_compra['banco_nome']}</option>";
										}
										?>
									</optgroup>
								</select>
							</div>
							<div class="css_form_campo">
								<select name="vendas_turno">
									<optgroup label="Turno da Venda">
										<option value="">---- Indiferente ----</option>
										<?php
										if (!$vendas_turno) {
											echo "<option value='' disabled selected>------ Turno da Venda ------</option>";
										}
										$result_turno = mysql_query("SELECT * FROM sys_vendas_turno;")
											or die(mysql_error());
										while ($row_turno = mysql_fetch_array($result_turno)) {
											if ($row_turno["sys_vendas_turno_id"] == $vendas_turno) {
												$selected_turno = " selected";
											} else {
												$selected_turno = "";
											}
											echo "<option value='{$row_turno['sys_vendas_turno_id']}'{$selected_turno}>{$row_turno['sys_vendas_turno_nome']}</option>";
										}
										?>
									</optgroup>
								</select>
							</div>
							<div class="css_form_campo">
								<select name="vendas_pos_venda">
									<optgroup label="Status de Pós Venda">
										<option value="">---- Indiferente ----</option>
										<?php
										if (!$vendas_pos_venda) {
											echo "<option value='' disabled selected>------ Status de Pós Venda ------</option>";
										}
										?>
										<option value="1" <?php if ($vendas_pos_venda == "1") {
																echo "selected";
															} ?>>Não Iniciado</option>
										<option value="2" <?php if ($vendas_pos_venda == "2") {
																echo "selected";
															} ?>>Iniciado</option>
										<option value="3" <?php if ($vendas_pos_venda == "3") {
																echo "selected";
															} ?>>Em Alerta</option>
										<option value="4" <?php if ($vendas_pos_venda == "4") {
																echo "selected";
															} ?>>Concluído</option>
									</optgroup>
								</select>
							</div>
							<?php if ($administracao == 1 || $consultor_pos_venda == 1 || $supervisor_unidade == 1 || $gerente_plataformas) :
							?>
								<div class="css_form_campo">
									<select name='vendas_consultor'>
										<optgroup label="Consultor">
											<option value=''>---- Indiferente ----</option>
											<?php

											$consultor_ids = array();

											$optgroup = "";
											$count_g = 0;
											if (!$vendas_consultor) {
												echo "<option value='' disabled selected>------ Consultor ------</option>";
											}
											$result_user_form = mysql_query("SELECT DISTINCT vendas_consultor, name, nivel FROM sys_vendas INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . $select_id . $select_state . $select_city . $select_bank . $select_data_ini . $select_data_fim . /*$select_data_imp_ini . $select_data_imp_fim .*/ $select_status . $select_orgao . $select_tipo . $select_unidade . $select_empresa . $select_equipe . $select_promotora . $select_mes . $select_contrato . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8 ORDER BY name;")
												or die(mysql_error());
											while ($row_user_form = mysql_fetch_array($result_user_form)) {
												if ($row_user_form["vendas_consultor"] == $vendas_consultor) {
													$selected_consultor = " selected";
													if ($row_user_form["nivel"] == 3) {
														$nivel = "consultor";
														$nivel_numero = 3;
													}
													if ($row_user_form["nivel"] == 2) {
														$nivel = "cordenador";
														$nivel_numero = 2;
													}
												} else {
													$selected_consultor = "";
												}
												if ($optgroup != $row_user_form['name'][0]) {
													if ($count_g > 0) {
														echo "</optgroup>";
													}
													echo "<optgroup label=" . $row_user_form['name'][0] . ">";
													$optgroup = $row_user_form['name'][0];
													$count_g++;
												}
												array_push($consultor_ids, $row_user_form['vendas_consultor']);
												echo "<option value='{$row_user_form['vendas_consultor']}'{$selected_consultor}>{$row_user_form['name']}</option>";
											} ?>
										</optgroup>
									</select>
								</div>
							<?php elseif (($user_nivel == "5") || ($user_nivel == "6") || ($user_nivel == "7") || ($supervisor_equipe_vendas == 1) || ($operacional_equipes == 1) || ($supervisor_treinamento == 1) || ($coordenador_plataformas)) : ?>
								<div class="css_form_campo">
									<select name='vendas_consultor'>
										<optgroup label="Consultor">
											<option value=''>---- Indiferente ----</option>
											<?php
											if (!$vendas_consultor) {
												echo "<option value='' disabled selected>------ Consultor ------</option>";
											}
											$select_unidade_form = " AND unidade = '" . $user_unidade . "'";
											$select_equipe_form = "";
											if (($supervisor_equipe_vendas == 1) || ($operacional_equipes == 1)) {
												$select_equipe_form = $select_equipe_supervisor;
												$select_unidade_form = "";
											}
											if ($supervisor_treinamento == 1) {
												$select_equipe_form = " AND situacao = '1'";
											}
											if ($coordenador_plataformas == 1) {
												$select_equipe_form = $select_equipe_plataforma;
												$select_unidade_form = "";
											}
											if ($supervisor_unidade == 1) {
												$select_equipe_form = $select_equipe_supervisor;
												$select_unidade_form = "";
											}
											//$result_user_form = mysql_query("SELECT id, name FROM jos_users WHERE 1".$select_unidade_form.$select_equipe_form." ORDER BY name;")
											$result_user_form = mysql_query("SELECT DISTINCT vendas_consultor, name, nivel FROM sys_vendas INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . $select_unidade_form . $select_equipe_form . $select_id . $select_state . $select_city . $select_bank . $select_data_ini . $select_data_fim . /* $select_data_imp_ini . $select_data_imp_fim .*/ $select_status . $select_orgao . $select_tipo . $select_unidade . $select_empresa . $select_equipe . $select_promotora . $select_mes . $select_contrato . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8 ORDER BY name;")
												or die(mysql_error());
											while ($row_user_form = mysql_fetch_array($result_user_form)) {
												if ($row_user_form["vendas_consultor"] == $vendas_consultor) {
													$selected_consultor = " selected";
												} else {
													$selected_consultor = "";
												}
												echo "<option value='" . $row_user_form['vendas_consultor'] . "'" . $selected_consultor . ">" . $row_user_form['name'] . "</option>";
											}
											?>
										</optgroup>
									</select>
								</div>
							<?php else : ?>
								&nbsp;
							<?php endif; ?>

							<?php if ($administracao == 1) : ?>
								<div class="css_form_campo">
									<select name="vendas_envio">
										<optgroup label="Método de Envio">
											<option value=''>---- Indiferente ----</option>
											<?php
											if (!$vendas_envio) {
												echo "<option value='' disabled selected>------ Método de Envio ------</option>";
											}
											$result_envio = mysql_query("SELECT * FROM sys_vendas_envio ORDER BY envio_id;")
												or die(mysql_error());
											while ($row_envio = mysql_fetch_array($result_envio)) {
												if ($row_envio["envio_id"] == $vendas_envio) {
													$selected = "selected";
												} else {
													$selected = "";
												}
												echo "<option value='{$row_envio['envio_id']}'{$selected}>{$row_envio['envio_nome']}</option>";
											}
											?>
										</optgroup>
									</select>
								</div>
								<div class="css_form_campo">
									<select name="vendas_seguro_protegido">
										<optgroup label="Seguro Consignado Protegido">
											<option value="">---- Indiferente ----</option>
											<?php
											if (!$vendas_seguro_protegido) {
												echo "<option value='' disabled selected>------ Seguro Consignado Protegido ------</option>";
											}
											?>
											<option value="1" <?php if ($vendas_seguro_protegido == "1") {
																	echo " selected";
																} ?>>Não</option>
											<option value="2" <?php if ($vendas_seguro_protegido == "2") {
																	echo " selected";
																} ?>>Sim</option>
										</optgroup>
									</select>
								</div>
								<div class="css_form_campo">
									<select name="vendas_jud">
										<optgroup label="Liberação de Margem">
											<option value="">---- Indiferente ----</option>
											<?php
											if (!$vendas_jud) {
												echo "<option value='' disabled selected>------ Liberação de Margem ------</option>";
											}
											?>
											<option value="1" <?php if ($vendas_jud == "1") {
																	echo " selected";
																} ?>>Normal</option>
											<option value="2" <?php if ($vendas_jud == "2") {
																	echo " selected";
																} ?>>Via Jurídico</option>
									</select>
								</div>
								<div class="css_form_campo">
									<select name="vendas_produto">
										<optgroup label="Produto">
											<option value=''>---- Indiferente ----</option>
											<?php
											if (!$vendas_produto) {
												echo "<option value='' disabled selected>------ Produto ------</option>";
											}
											$result_produto = mysql_query("SELECT * FROM sys_vendas_produtos ORDER BY produto_id;")
												or die(mysql_error());
											while ($row_produto = mysql_fetch_array($result_produto)) {
												if ($row_produto["produto_id"] == $vendas_produto) {
													$selected = "selected";
												} else {
													$selected = "";
												}
												echo "<option value='{$row_produto['produto_id']}'{$selected}>{$row_produto['produto_nome']}</option>";
											}
											?>
										</optgroup>
									</select>
								</div>
							<?php endif; ?>
						<?php endif; // fim do if (retencao_safra == 1) 
						?>
						<?php if ($diretoria == 1 || $operacional_fisico == 1) : ?>
							<div class="css_form_campo">
								<select name="nobjeto_retorno">
									<optgroup label="Nº do objeto retorno físico">
										<option value="">---- Indiferente ----</option>
										<?php
										if (!$_GET["nobjeto_retorno"]) {
											echo "<option value='' disabled selected>------ Nº do objeto retorno físico ------</option>";
										}
										?>
										<option value="1" <?php if ($_GET["nobjeto_retorno"] == "1") {
																echo " selected";
															} ?>>Possui nº objeto</option>
										<option value="2" <?php if ($_GET["nobjeto_retorno"] == "2") {
																echo " selected";
															} ?>>Não possui nº objeto</option>
								</select>
							</div>
						<?php endif; ?>
					</div>

				</div>
				<!-- corrige espaço do inline-block
-->
				<div class="css_form_group grupo_b">
					<?php if (!$retencao_safra == 1) : ?>
						<div style="width: 100%;">Campos de seleção múltipla. Utilize CTRL</div>
						<div class="css_form_campo css_multisel">Vendas Banco:<br>
							<select name="vendas_banco[]" multiple="multiple" style="height:64px; width:200px">
								<option value="">---- Indiferente ----</option>
								<?php
								$optgroup = "";
								$count_g = 0;
								$result_bancos = mysql_query("SELECT DISTINCT vendas_banco FROM sys_vendas WHERE vendas_banco != '' ORDER BY vendas_banco;")
									or die(mysql_error());
								while ($row_bancos = mysql_fetch_array($result_bancos)) {
									$selected_bank = "";
									if (strpos($select_bank, "'" . $row_bancos['vendas_banco'] . "'")) {
										$selected_bank = " selected";
									}
									if ($optgroup != $row_bancos['vendas_banco'][0]) {
										if ($count_g > 0) {
											echo "</optgroup>";
										}
										echo "<optgroup label=" . $row_bancos['vendas_banco'][0] . ">";
										$optgroup = $row_bancos['vendas_banco'][0];
										$count_g++;
									}
									echo "<option value='{$row_bancos['vendas_banco']}'{$selected_bank}>{$row_bancos['vendas_banco']}</option>";
								}
								?>
							</select>
						</div>

						<div class="css_form_campo css_multisel">Mês válido:<br>
							<select name="vendas_mes[]" multiple="multiple" style="height:64px; width:200px">
								<option value="">---- Indiferente ----</option>
								<?php
								$result_mes = mysql_query("SELECT * FROM sys_vendas_mes ORDER BY mes_id DESC;")
									or die(mysql_error());
								while ($row_mes = mysql_fetch_array($result_mes)) {
									$selected_mes = "";
									for ($i = 0; $i < count($vendas_mes); $i++) {
										if ($vendas_mes[$i] == $row_mes["mes_nome"]) {
											$selected_mes = " selected";
										}
									}
									echo "<option value='{$row_mes['mes_nome']}'{$selected_mes}>{$row_mes['mes_label']}</option>";
								}
								?>
							</select>
						</div>

						<div class="css_form_campo css_multisel">Contrato Físico:<br>
							<select name="vendas_contrato_fisico[]" multiple="multiple" style="height:64px; width:200px">
								<option value="">---- Indiferente ----</option>
								<?php
								$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos ORDER BY contrato_etapa;")
									or die(mysql_error());
								while ($row_fisicos = mysql_fetch_array($result_fisicos)) {
									$selected_fisicos = "";
									for ($i = 0; $i < count($vendas_contrato_fisico); $i++) {
										if ($vendas_contrato_fisico[$i] == $row_fisicos["contrato_id"]) {
											$selected_fisicos = " selected";
										}
									}
									echo "<option value='{$row_fisicos['contrato_id']}'{$selected_fisicos}>{$row_fisicos['contrato_nome']}</option>";
								}
								?>
							</select>
						</div>

						<div class="css_form_campo css_multisel">Tipo de Contrato:<br>
							<select name="vendas_tipo_contrato[]" multiple="multiple" style="height:64px; width:200px">
								<option value="">---- Indiferente ----</option>
								<?php
								$optgroup = "";
								$count_g = 0;
								$result_tipos = mysql_query("SELECT DISTINCT vendas_tipo_contrato, tipo_id, tipo_nome FROM sys_vendas 
											INNER JOIN sys_vendas_tipos ON sys_vendas.vendas_tipo_contrato = sys_vendas_tipos.tipo_id " . $join_unidade . " 
											WHERE 1" . $filtros_sql_pesquisa . " 
											ORDER BY tipo_nome;")
									or die(mysql_error());
								while ($row_tipos = mysql_fetch_array($result_tipos)) {
									$selected_tipo = "";
									for ($i = 0; $i < count($vendas_tipo_contrato); $i++) {
										if ($vendas_tipo_contrato[$i] == $row_tipos["tipo_id"]) {
											$selected_tipo = " selected";
										}
									}
									if ($optgroup != $row_tipos['tipo_nome'][0]) {
										if ($count_g > 0) {
											echo "</optgroup>";
										}
										echo "<optgroup label=" . $row_tipos['tipo_nome'][0] . ">";
										$optgroup = $row_tipos['tipo_nome'][0];
										$count_g++;
									}
									echo "<option value='{$row_tipos['tipo_id']}'{$selected_tipo}>{$row_tipos['tipo_nome']}</option>";
								}
								?>
							</select>
						</div>

						<div class="css_form_campo css_multisel">Status:<br>
							<select name="vendas_status[]" multiple="multiple" style="height:64px; width:200px">
								<option value="">---- Indiferente ----</option>
								<?php
								$result_status = mysql_query("SELECT * FROM sys_vendas_status ORDER BY status_etapa;")
									or die(mysql_error());
								while ($row_status = mysql_fetch_array($result_status)) {
									$selected_status = "";
									for ($i = 0; $i < count($vendas_status); $i++) {
										if ($vendas_status[$i] == $row_status["status_id"]) {
											$selected_status = " selected";
										}
									}
									echo "<option value='{$row_status['status_id']}'{$selected_status}>{$row_status['status_nm']}</option>";
								}
								?>
							</select>
						</div>

						<?php if ($administracao == 1 || $gerente_plataformas == 1) : ?>
							<div class="css_form_campo css_multisel">Unidade:<br>
								<select name='consultor_unidade[]' multiple='multiple' style='height:64px; width:200px'>
									<option value=''>---- Indiferente ----</option>
									<?php
									$optgroup = "";
									$count_g = 0;
									$result_unidade = mysql_query("SELECT DISTINCT unidade FROM jos_users ORDER BY unidade;")
										or die(mysql_error());
									while ($row_unidade = mysql_fetch_array($result_unidade)) {
										$selected = "";
										for ($i = 0; $i < count($consultor_unidade); $i++) {
											if ($consultor_unidade[$i] == $row_unidade["unidade"]) {
												$selected = "selected";
											}
										}
										if ($optgroup != $row_unidade['unidade'][0]) {
											if ($count_g > 0) {
												echo "</optgroup>";
											}
											echo "<optgroup label=" . $row_unidade['unidade'][0] . ">";
											$optgroup = $row_unidade['unidade'][0];
											$count_g++;
										}
										echo "<option value='{$row_unidade['unidade']}'{$selected}>{$row_unidade['unidade']}</option>";
									}
									?>
								</select>
							</div>
						<?php endif; ?>

						<?php if ($diretoria == 1) : ?>
							<div class="css_form_campo css_multisel">Carteira de Origem:<br>
								<select name='cliente_carteira[]' multiple='multiple' style='height:64px; width:200px'>
									<option value=''>---- Indiferente ----</option>
									<?php
									$result_carteira = mysql_query("SELECT carteira_id,carteira_nome FROM sys_carteiras;")
										or die(mysql_error());
									while ($row_carteira = mysql_fetch_array($result_carteira)) {
										$selected = "";
										for ($i = 0; $i < count($cliente_carteira); $i++) {
											if ($cliente_carteira[$i] == $row_carteira["carteira_id"]) {
												$selected = "selected";
											}
										}
										echo "<option value='{$row_carteira['carteira_id']}'{$selected}>{$row_carteira['carteira_nome']}</option>";
									}
									?>
								</select>
							</div>
						<?php endif; ?>

						<div class="css_form_campo css_multisel">Órgão:<br>
							<select name="vendas_orgao[]" multiple="multiple" style="height:64px; width:200px">
								<option value="">---- Indiferente ----</option>
								<?php
								$result_orgao = mysql_query("SELECT * FROM sys_orgaos ORDER BY orgao_nome;")
									or die(mysql_error());
								while ($row_orgao = mysql_fetch_array($result_orgao)) {
									$selected_orgao = "";
									for ($i = 0; $i < count($vendas_orgao); $i++) {
										if ($vendas_orgao[$i] == $row_orgao["orgao_nome"]) {
											$selected_orgao = " selected";
										}
									}
									echo "<option value='{$row_orgao['orgao_nome']}'{$selected_orgao}>{$row_orgao['orgao_label']}</option>";
								}
								?>
							</select>
						</div>
						<?php
						$consultor_ids = implode(",", $consultor_ids);
						if ($consultor_ids != NULL) {
							$result_equipe_jos_users = mysql_query("SELECT DISTINCT equipe_id FROM jos_users WHERE id IN ($consultor_ids)") or die(mysql_error());

							$equipe_jos_users_id = array();
							while ($row_equipe_jos_users = mysql_fetch_array($result_equipe_jos_users)) {
								array_push($equipe_jos_users_id, $row_equipe_jos_users['equipe_id']);
							}
							$equipe_jos_users_id = implode(",", $equipe_jos_users_id);
						}
						if (strlen($consultor_ids) > 0) {
							$equipe_query = "SELECT equipe_id,equipe_nome FROM sys_equipes WHERE equipe_id IN ($equipe_jos_users_id) ORDER BY equipe_nome;";
						} else {
							$equipe_query = "SELECT equipe_id,equipe_nome FROM sys_equipes ORDER BY equipe_nome;";
						}
						// echo "<pre style='display: none;'>";
						// var_dump($consultor_ids);
						// echo "</pre>";
						?>
						<?php if (($administracao == 1) || ($user_nivel == "7") || ($gerente_plataformas)) : ?>
							<div class="css_form_campo css_multisel">Equipe:<br>
								<select name="equipe_id[]" multiple="multiple" style="height:64px; width:200px">
									<option value="">---- Indiferente ----</option>
									<?php
									$result_equipe = mysql_query($equipe_query)
										or die(mysql_error());
									while ($row_equipe = mysql_fetch_array($result_equipe)) {
										$selected_equipe = "";
										for ($i = 0; $i < count($equipe_id); $i++) {
											if ($equipe_id[$i] == $row_equipe["equipe_id"]) {
												$selected_equipe = " selected";
											}
										}
										echo "<option value='{$row_equipe['equipe_id']}'{$selected_equipe}>{$row_equipe['equipe_nome']}</option>";
									}
									?>
								</select>
							</div>
						<?php endif; ?>

						<div class="css_form_campo css_multisel">Cartão Consignado:<br>
							<select name="vendas_cartao_consig[]" multiple="multiple" style="height:64px; width:200px">
								<option value="">---- Indiferente ----</option>
								<option value="0">- Não possui cartão consignado -</option>
								<?php
								$sql_vendas_cartao_consig = "SELECT vendas_bancos_id, vendas_bancos_nome
							FROM sys_vendas_bancos;";
								$result_vendas_cartao_consig = mysql_query($sql_vendas_cartao_consig) or die(mysql_error());
								while ($row_vendas_cartao_consig = mysql_fetch_array($result_vendas_cartao_consig)) : ?>
									<?php
									$selected_vendas_cartao_consig = "";
									for ($i = 0; $i < count($vendas_cartao_consig); $i++) {
										if ($row_vendas_cartao_consig["vendas_bancos_id"] == $vendas_cartao_consig[$i]) {
											$selected_vendas_cartao_consig = " selected";
										}
									}
									?>
									<option value="<?php echo $row_vendas_cartao_consig['vendas_bancos_id']; ?>" <?php echo $selected_vendas_cartao_consig; ?>><?php echo $row_vendas_cartao_consig['vendas_bancos_nome']; ?></option>
								<?php endwhile; ?>
							</select>
						</div>

					<?php endif; // endif (retencao_safra == 1) 
					?>
				</div>
			</div>
		</div>
		<!-- ##################################################################### -->
		<div align="center">
			<table width="100%" height="99%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tbody>
					<?php if (($_GET["bulk"] == "bulk") && ($_GET["massa"]) && ($frame_fisicos == 1)) : ?>
						<tr>
							<td>
								<div align="center">
									<h3 class="mypets">Edição em Lote:</h3>
									Atualizar<br />
									<table>
										<tr>
											<td>
												<div align="left"><label for="vendas_contrato_fisico_lote">
														<?php if ($row["vendas_tipo_contrato"] == 6) {
															echo "Contrato Físico Termo:";
														} else {
															echo "Contrato Físico Básico:";
														} ?>
													</label>
											</td>
											<td>
												<select name="vendas_contrato_fisico_lote">
													<option value="" selected>---- Selecione ----</option>
													<?php
													$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos;")
														or die(mysql_error());
													while ($row_fisicos = mysql_fetch_array($result_fisicos)) {
														$selected_fisicos = "";
														if ($row['vendas_contrato_fisico_lote'] == $row_fisicos["contrato_id"]) {
															$selected_fisicos = " selected";
														}
														echo "<option value='{$row_fisicos['contrato_id']}'{$selected_fisicos}>{$row_fisicos['contrato_nome']}</option>";
													}
													?>
												</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div align="left"><label for="vendas_contrato_fisico2_lote">
										<?php if ($row["vendas_tipo_contrato"] == 3) {
											echo "Contrato Físico Portabilidade:";
										} else {
											echo "Contrato Físico CCB:";
										} ?>
									</label>
							</td>
							<td>
								<select name="vendas_contrato_fisico2_lote">
									<option value="" selected>---- Selecione ----</option>
									<?php
									$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos;")
										or die(mysql_error());
									while ($row_fisicos = mysql_fetch_array($result_fisicos)) {
										$selected_fisicos = "";
										if ($row['vendas_contrato_fisico2_lote'] == $row_fisicos["contrato_id"]) {
											$selected_fisicos = " selected";
										}
										echo "<option value='{$row_fisicos['contrato_id']}'{$selected_fisicos}>{$row_fisicos['contrato_nome']}</option>";
									}
									?>
								</select>
		</div>
		</td>
		</tr>
		</table>
		das vendas: <?php echo $ids_massa; ?><br />
		<?php echo $input_massa; ?>
		<input name="bulk" type="hidden" value="bulk" />
		<button name="processar" type="submit" value="processar" style="float: none;">Processar lote</button>
		</div>
		</td>
		</tr>
	<?php endif; ?>
	</table>
	</td>
	</tr>
	</tbody>
	</table>

	<div align="left">

		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
			<tbody>
				<tr class="cabecalho">
					<div align="left" class="style8">
						<td width="3%">
							<?php if ($frame_fisicos == 1) : ?>
								<button name="bulk" type="submit" value="bulk" style="padding: 2px; height: 17px; line-height: 10px; font-size: 8px; margin: auto; float:none;">LOTE</button>
								<input type="checkbox" id="select_all" name="select_all" title="Selecionar todos"><br>
							<?php endif; ?>
						</td>
						<td width="30%">
							<?php $links_filtros = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=" . $_GET['Itemid'] . "&vendas_id=" . $vendas_id .
								"&nome=" . $nome .
								"&prec=" . $prec .
								"&cpf=" . $cpf . $pag_mes .
								"&contar=" . $_GET['contar'] .
								"&consultor_unidade=" . $pag_unidade .
								"&vendas_consultor=" . $vendas_consultor .
								"&vendas_vendedor=" . $vendas_vendedor .
								$pag_status .
								$pag_tipo .
								$pag_orgao .
								$pag_contrato .
								$pag_carteira .
								$pag_equipe .
								$pag_vendas_cartao_consig .
								"&vendas_promotora=" . $vendas_promotora .
								"&vendas_origem=" . $vendas_origem .
								"&vendas_banco=" . $vendas_banco .
								"&vendas_turno=" . $vendas_turno .
								"&vendas_intencionada=" . $vendas_intencionada .
								"&vendas_pos_venda=" . $vendas_pos_venda .
								"&vendas_tipo_tabela=" . $vendas_tipo_tabela .
								"&vendas_banco_compra=" . $vendas_banco_compra .
								"&vendas_seguro_protegido=" . $vendas_seguro_protegido .
								"&vendas_jud=" . $vendas_jud .
								"&vendas_estoque=" . $vendas_estoque .
								"&vendas_envio=" . $vendas_envio .
								"&vendas_produto=" . $vendas_produto .
								"&vendas_envio_objeto=" . $vendas_envio_objeto .
								"&dp-normal-3=" . $pag_data_imp_ini .
								"&dp-normal-4=" . $pag_data_imp_fim .
								"&dp-normal-5=" . $pag_data_ini .
								"&dp-normal-6=" . $pag_data_fim .
								"&filtro_data1=" . $_GET['filtro_data1'] .
								"&filtro_data2=" . $_GET['filtro_data2'] .
								"&buscar=" . $_GET['buscar'] .
								"&qnt=" . $qnt; ?>

							<?php echo "<a class='style8' href='" . $links_filtros . "&ordemi=sys_clients.clients_nm&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'>Cliente</a> ";
							if ($ordem == 'sys_clients.clients_nm') {
								echo $img_ordem;
							} ?><br>
							<span style="color:#cccccc; font-size:8pt">CPF: | Matrícula:<?php if ($contagem) {
																							echo "<a class='style8' href='" . $links_filtros . "&ordemi=contagem&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'> | Nº de Vendas:</a> ";
																							if ($ordem == 'contagem') {
																								echo $img_ordem;
																							}
																						} ?></span>
						</td>
						<td width="12%">
							<?php echo "<a class='style8' href='" . $links_filtros . "&ordemi=vendas_orgao&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'>Órgão | Banco</a> ";
							if ($ordem == 'vendas_orgao') {
								echo $img_ordem;
							} ?><br>
							<span style="color:#cccccc; font-size:8pt">Proposta | Portabilidade:</span>
						</td>
						<td width="11%">
							<?php echo "<a class='style8' href='" . $links_filtros . "&ordemi=vendas_valor&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'>Valor AF</a> ";
							if ($ordem == 'vendas_valor') {
								echo $img_ordem;
							} ?><br>
							<?php echo "<a href='" . $links_filtros . "&ordemi=vendas_tipo_contrato&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'><span style='color:#cccccc; font-size:8pt'>Tipo de Contrato</span></a> ";
							if ($ordem == 'vendas_tipo_contrato') {
								echo $img_ordem;
							} ?>
						</td>
						<td width="21%">
							<a class="style8" href="#">Consultor</a><br />
							<?php echo "<a href='" . $links_filtros . "&ordemi=vendas_dia_venda&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'><span style='color:#cccccc; font-size:8pt'>Data da venda:</span></a> ";
							if ($ordem == 'vendas_dia_venda') {
								echo $img_ordem;
							} ?><span style='color:#cccccc; font-size:8pt'> | </span>
							<?php echo "<a href='" . $links_filtros . "&ordemi=vendas_dia_pago&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'><span style='color:#cccccc; font-size:8pt'>Data pgto. | Mês</span></a> ";
							if ($ordem == 'vendas_dia_pago') {
								echo $img_ordem;
							} ?>
						</td>
						<td width="15%">
							<?php echo "<a class='style8' href='" . $links_filtros . "&ordemi=vendas_status&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'>Status da Venda</a> ";
							if ($ordem == 'vendas_status') {
								echo $img_ordem;
							} ?><br>
							<?php echo "<a href='" . $links_filtros . "&ordemi=vendas_contrato_fisico&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'><span style='color:#cccccc; font-size:8pt'>Status de Físico</span></a> ";
							if ($ordem == 'vendas_contrato_fisico') {
								echo $img_ordem;
							} ?>
						</td>
						<td width="5%">
							<img src="sistema/imagens/config.png"></br>

							<?php echo "<a class='style8' href='" . $links_filtros . "&ordemi=sys_vendas.vendas_id&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'>Código</a> ";
							if ($ordem == 'sys_vendas.vendas_id') {
								echo $img_ordem;
							} ?><br>
					</div>
				</tr>
				<tr>
					<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
						<tbody>
							<?php
							if ($pendentes) {
								include("sistema/vendas/exibe_lista_revisadas.php");
							}
							if ($aprovadas) {
								include("sistema/vendas/exibe_lista_aprovadas.php");
							}
							if ($fisicos) {
								include("sistema/vendas/exibe_lista_fisicos.php");
							}
							if ($fisicos_pagos) {
								include("sistema/vendas/exibe_lista_fisicos_pagos.php");
							}
							if ($fracionadas) {
								include("sistema/vendas/exibe_lista_fracionadas.php");
							}
							if ($autorizacao) {
								include("sistema/vendas/exibe_lista_autorizacao.php");
							}

							if (($pendentes) || ($aprovadas) || ($fisicos) || ($fisicos_pagos) || ($fracionadas) || ($autorizacao)) {
								echo "<tr><td colspan='7'><div align='center'><h3 class='mypets2'>Todas as Vendas:</h3></div></td></tr>";
							}

							$totalclientes = 0;
							$fracionados_recebidos = 0;
							$exibindo = 1;
							$numero = $exibindo;
							include("sistema/vendas/exibe_lista_adm.php");
							$exibindo = $exibindo  - 1;

							$sum_comissao = ", SUM(vendas_comissao_vendedor) AS total_comissao, SUM(vendas_fortcoins) AS total_fortcoins";
							if (($diretoria == 1) || ($financeiro == 1)) {
								if (($pag_status == "&vendas_status[]=9&vendas_status[]=8") || ($pag_status == "&vendas_status[]=8&vendas_status[]=9")) {
									$sum_comissao = $sum_comissao . ", SUM(vendas_receita_fr) AS total_fracionados, SUM(vendas_recebido_fr) AS total_fracionados_recebido ";
								}
							}

							// COMISSÃO MÍNIMA

							$comissao_minima = 0;

							$result_comissao_minima = mysql_query("SELECT meta_id, meta_nome, meta_valor1, meta_valor2, meta_nivel
	FROM sys_metas WHERE meta_nivel = '" . $nivel_numero . "';")
								or die(mysql_error());
							$row_comissao_minima = mysql_fetch_array($result_comissao_minima);
							if (isset($row_comissao_minima['meta_valor1'])) {
								$comissao_minima = $row_comissao_minima['meta_valor1'];
							}
							if (($_GET["buscar"]) || (!$administracao)) {
								// TOTAIS BASE 1
								$sql_select_total_1 = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_receita) AS total_receita, 
SUM(vendas_base_prod) AS total_base" . $sum_comissao . "
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)" . $join_unidade . $join_tabela . $join_banco_compra . " 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%' AND sys_vendas.vendas_base = '1'" .
									$filtros_sql . ";")
									or die(mysql_error());
								$row_total_valor_1 = mysql_fetch_array($sql_select_total_1);
								$total_valor_1 = ($row_total_valor_1['total_valor'] > 0) ? number_format($row_total_valor_1['total_valor'], 2, ',', '.') : '0';
								$total_receita_1 = ($row_total_valor_1['total_receita'] > 0) ? number_format($row_total_valor_1['total_receita'], 2, ',', '.') : '0';
								$total_base_1 = ($row_total_valor_1['total_base'] > 0) ? number_format($row_total_valor_1['total_base'], 2, ',', '.') : '0';
								if (($diretoria == 1) || ($financeiro == 1)) {
									if (($vendas_mes) && (($pag_status == "&vendas_status[]=9&vendas_status[]=8") || ($pag_status == "&vendas_status[]=8&vendas_status[]=9"))) {
										$total_comissao_1 = ($row_total_valor_1['total_comissao'] > 0) ? number_format($row_total_valor_1['total_comissao'], 2, ',', '.') : '0';
									}
								}

								// TOTAIS BASE 2
								$sql_select_total_2 = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_receita) AS total_receita, 
SUM(vendas_base_prod) AS total_base" . $sum_comissao . "
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)" . $join_unidade . $join_tabela . $join_banco_compra . " 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%' AND sys_vendas.vendas_base = '2'" .
									$filtros_sql . ";")
									or die(mysql_error());
								$row_total_valor_2 = mysql_fetch_array($sql_select_total_2);
								$total_valor_2 = ($row_total_valor_2['total_valor'] > 0) ? number_format($row_total_valor_2['total_valor'], 2, ',', '.') : '0';
								$total_receita_2 = ($row_total_valor_2['total_receita'] > 0) ? number_format($row_total_valor_2['total_receita'], 2, ',', '.') : '0';
								$total_base_2 = ($row_total_valor_2['total_base'] > 0) ? number_format($row_total_valor_2['total_base'], 2, ',', '.') : '0';
								if (($diretoria == 1) || ($financeiro == 1)) {
									if (($vendas_mes) && (($pag_status == "&vendas_status[]=9&vendas_status[]=8") || ($pag_status == "&vendas_status[]=8&vendas_status[]=9"))) {
										$total_comissao_2 = ($row_total_valor_2['total_comissao'] > 0) ? number_format($row_total_valor_2['total_comissao'], 2, ',', '.') : '0';
									}
								}

								// TOTAIS DE RECEITAS POR TIPO
								if ($consultor_mei) {
									$filtro_sql_receitas = " AND cms_tipo_recebimento=1";
								}
								$sql_select_total_tipos = mysql_query("SELECT 
sys_vendas_cms.tipo_id, 
sys_vendas_cms_tipos.cms_tipo_nome, 
SUM(cms_subtotal) AS cms_subtotal, 
SUM( IF(cms_receita = 2, cms_subtotal, null ) ) AS cms_subtotal_ant_sem_dif, 
cms_tipo_recebimento 
FROM sys_vendas_cms 
INNER JOIN sys_vendas ON (sys_vendas.vendas_id = sys_vendas_cms.vendas_id) 
INNER JOIN sys_vendas_cms_tipos ON (sys_vendas_cms_tipos.cms_tipo_id = sys_vendas_cms.tipo_id) 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)" . $join_unidade . $join_tabela . $join_banco_compra . " 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" .
									$filtros_sql . $filtro_sql_receitas . " 
GROUP BY sys_vendas_cms.tipo_id;")
									or die(mysql_error());

								// TOTAIS BASE 1 + 2
								$sql_select_total = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_receita) AS total_receita, 
SUM(vendas_receita_bonus) AS total_receita_bonus, 
SUM(vendas_impostos) AS total_impostos, 
SUM(vendas_base_contrato) AS total_base_contrato, 
SUM(vendas_base_prod) AS total_base" . $sum_comissao . "
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)" . $join_unidade . $join_tabela . $join_banco_compra . " 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" .
									$filtros_sql . ";") or die(mysql_error());

								$row_total_valor = mysql_fetch_array($sql_select_total);
								$total_valor = ($row_total_valor['total_valor'] > 0) ? number_format($row_total_valor['total_valor'], 2, ',', '.') : '0';
								$total_receita = ($row_total_valor['total_receita'] <> 0) ? number_format($row_total_valor['total_receita'], 2, ',', '.') : '0';
								$total_receita_bonus = ($row_total_valor['total_receita_bonus'] > 0) ? number_format($row_total_valor['total_receita_bonus'], 2, ',', '.') : '0';
								$total_impostos = ($row_total_valor['total_impostos'] > 0) ? number_format($row_total_valor['total_impostos'], 2, ',', '.') : '0';
								$total_base_contrato = ($row_total_valor['total_base_contrato'] > 0) ? number_format($row_total_valor['total_base_contrato'], 2, ',', '.') : '0';
								$total_base = ($row_total_valor['total_base'] > 0) ? number_format($row_total_valor['total_base'], 2, ',', '.') : '0';
								$total_fortcoins = ($row_total_valor['total_fortcoins'] > 0) ? number_format($row_total_valor['total_fortcoins'], 2, ',', '.') : '0';
								if (($diretoria == 1) || ($financeiro == 1)) {
									if (($vendas_mes) && (($pag_status == "&vendas_status[]=9&vendas_status[]=8") || ($pag_status == "&vendas_status[]=8&vendas_status[]=9"))) {
										$total_comissao = ($row_total_valor['total_comissao'] > 0) ? number_format($row_total_valor['total_comissao'], 2, ',', '.') : '0';
										$bonus = 0;
										if ($vendas_consultor) {
											$txt_consultor = "Consultor";
										} else {
											$txt_consultor = "Consultores";
										}

										if (!$vendas_consultor) {
											$operacional1 = ($row_total_valor['total_base'] * 0.05) / 100;
											$operacional1_rs = ($operacional1 > 0) ? number_format($operacional1, 2, ',', '.') : '0';
											if ($row_total_valor['total_base'] <= 999999) {
												$operacional2 = 0;
											} else {
												$operacional2 = ($row_total_valor['total_base'] * 0.025) / 100;
												$operacional2_rs = ($operacional2 > 0) ? number_format($operacional2, 2, ',', '.') : '0';
											}
										} else {
											$operacional1 = 0;
											$operacional2 = 0;
										}

										if (($row_total_valor['total_base'] >= $comissao_minima) || ($nivel == "cordenador")) {
											$consulta_total_comissoes = $row_total_valor['total_comissao'];
										} else {
											$consulta_total_comissoes = 0;
										}

										$total_comissoes = $consulta_total_comissoes + $bonus;
										$total_comissoes_rs = ($total_comissoes > 0) ? number_format($total_comissoes, 2, ',', '.') : '0';

										$lucro_bruto = $row_total_valor['total_receita'] - $total_comissoes;
										$lucro_bruto = ($lucro_bruto > 0) ? number_format($lucro_bruto, 2, ',', '.') : '0';
									}
								}
							}
							echo "<tr class='even'><div align='left'>";
							echo "<td colspan='7'>Resultados totais de todos os resultados da Pesquisa:</br><div align='center'>";
							echo "<table width='85%'>";

							if ($franquiado == 1) {
								echo "<tr>";
								echo "<td><strong>Totais:</strong></td>";
								echo "</tr>";
								echo "<tr>";
								echo "<td>";
								echo "Valores de AFs: <strong>R$ " . $total_valor . "</strong></br>";
								echo "Bases dos Contratos: <strong>R$ " . $total_base_contrato . "</strong></br>";
								echo "Receitas: <span style='color:#41546F;'><strong>R$ " . $total_receita . "</strong></span></br><hr>";
							} elseif (($diretoria == 1) || ($financeiro == 1) || ($pag_mes == "&vendas_mes[]=07/2018") || ($user_nivel == "5") || ($user_nivel == "6") || ($assistente_diretoria_consignado) || ($operacional_fisico == 1) || ($operacional_fonado == 1) || ($consultor_mei)) {
								echo "<tr>";
								echo "<td><strong>Vendas com Base 1:</strong></td>";
								echo "<td><strong>Vendas com Base 2:</strong></td>";
								echo "<td><strong>Totais (1 e 2):</strong></td>";
								echo "</tr>";
								echo "<tr>";
								echo "<td>";
								echo "Valores de AFs: <strong>R$ " . $total_valor_1 . "</strong></br>";
								echo "Bases de Produção: <strong>R$ " . $total_base_1 . "</strong></br>";
								if ($diretoria == 1) {
									echo "Receitas: <strong>R$ " . $total_receita_1 . "</strong></br><hr>";
								}
								if (($diretoria == 1) || ($financeiro == 1)) {
									if (($vendas_mes) && (($pag_status == "&vendas_status[]=9&vendas_status[]=8") || ($pag_status == "&vendas_status[]=8&vendas_status[]=9")) && ($row_total_valor['total_base'] >= $comissao_minima)) {
										echo "$% " . $txt_consultor . ": <strong>R$ " . $total_comissao_1 . "</strong>";
									}
								}
								echo "</div>";
								echo "</td>";
								echo "<td>";
								echo "Valores de AFs: <strong>R$ " . $total_valor_2 . "</strong></br>";
								echo "Bases de Produção: <strong>R$ " . $total_base_2 . "</strong></br>";
								if ($diretoria == 1) {
									echo "Receitas: <strong>R$ " . $total_receita_2 . "</strong></br><hr>";
								}
								if (($diretoria == 1) || ($financeiro == 1)) {
									if (($vendas_mes) && (($pag_status == "&vendas_status[]=9&vendas_status[]=8") || ($pag_status == "&vendas_status[]=8&vendas_status[]=9")) && ($row_total_valor['total_base'] >= $comissao_minima)) {
										echo "$% " . $txt_consultor . ": <strong>R$ " . $total_comissao_2 . "</strong>";
									}
								}
								echo "</div>";
								echo "</td>";
								echo "<td>";
								echo "Valores de AFs: <strong>R$ " . $total_valor . "</strong></br>";
								echo "Bases dos Contratos: <strong>R$ " . $total_base_contrato . "</strong></br>";
								if (!$consultor_mei) {
									echo "Bases de Produção: <strong>R$ " . $total_base . "</strong></br>";
								}

								if (($diretoria == 1) || ($user_nivel == "5") || ($user_nivel == "6") || ($assistente_diretoria_consignado) || ($user_id == 2132) || ($consultor_mei)) {
									echo "Impostos: <span style='color:#A5240E;'><strong>R$ " . $total_impostos . "</strong></span></br>";
									echo "Receitas Bônus: <strong>R$ " . $total_receita_bonus . "</strong></br>";

									//IMPRESSAO RECEITAS POR TIPO:
									while ($row_total_tipos = mysql_fetch_array($sql_select_total_tipos)) {
										$cms_subtotal = ($row_total_tipos['cms_subtotal'] > 0) ? number_format($row_total_tipos['cms_subtotal'], 2, ',', '.') : '0';
										echo $row_total_tipos['cms_tipo_nome'] . ": <strong>R$ " . $cms_subtotal;
										if ($row_total_tipos['cms_tipo_recebimento'] == 3) {
											$cms_subtotal_ant_sem_dif = ($row_total_tipos['cms_subtotal_ant_sem_dif'] > 0) ? number_format($row_total_tipos['cms_subtotal_ant_sem_dif'], 2, ',', '.') : '0';
											echo " (R$ " . $cms_subtotal_ant_sem_dif . ")";
										}
										echo "</strong></span></br>";
									}
									echo "<hr>";

									if (!$consultor_mei) {
										echo "Receitas Totais: <span style='color:#41546F;'><strong>R$ " . $total_receita . "</strong></span></br><hr>";
									}
								}
								if (!$consultor_mei) {
									echo "$% Total: <span style='color:#00287b;'><strong>BS¢ " . $total_fortcoins . "</strong></span></br><hr>";
								}
								if (($diretoria == 1) || ($financeiro == 1)) {
									if (($vendas_mes) && (($pag_status == "&vendas_status[]=9&vendas_status[]=8") || ($pag_status == "&vendas_status[]=8&vendas_status[]=9"))) {
										if ($row_total_valor['total_base'] >= $comissao_minima) {
											echo "$% " . $txt_consultor . ": <strong>R$ " . $total_comissao . "</strong></br>";
										}
										if ($bonus > 0) {
											echo "Bônus: <strong>R$ " . $bonus_rs . "</strong></br>";
										}
										echo "$% Total: <span style='color:#A5240E;'><strong>R$ " . $total_comissoes_rs . "</strong></span></br><hr>";
										if ($diretoria == 1) {
											echo "$% Lucro Bruto: <span style='color:#819510;'><strong>R$ " . $lucro_bruto . "</strong></span></br>";
										}
									}
								}

								if (($diretoria == 1) && (($pag_status == "&vendas_status[]=9&vendas_status[]=8") || ($pag_status == "&vendas_status[]=8&vendas_status[]=9"))) {
									$fracionados_recebidos = $row_total_valor['total_fracionados_recebido'];
									$total_fracionados = ($row_total_valor['total_fracionados'] > 0) ? number_format($row_total_valor['total_fracionados'], 2, ',', '.') : '0';
									$fracionados_a_receber = $row_total_valor['total_fracionados'] - $fracionados_recebidos;
									echo "<hr>Receitas Fracionadas Totais: <strong>R$ " . $total_fracionados . "</strong></br>";
									$fracionados_recebidos = ($fracionados_recebidos > 0) ? number_format($fracionados_recebidos, 2, ',', '.') : '0';
									echo "Receitas Fracionadas Recbidas: <span style='color:#41546F;'><strong>R$ " . $fracionados_recebidos . "</strong></span></br>";
									$fracionados_a_receber = ($fracionados_a_receber > 0) ? number_format($fracionados_a_receber, 2, ',', '.') : '0';
									echo "Receitas Fracionadas A Receber: <span style='color:#888;'><strong>R$ " . $fracionados_a_receber . "</strong></span></br>";
								}
							}

							if ((($grupo_credito) || ($supervisor_unidade) || ($supervisor_unidade_seguros) || ($supervisor_equipe_vendas) || ($operacional_fonado) || ($operacional_fisico)) && (!$consultor_mei)) {
								echo "<tr>";
								echo "<td>Valores de AFs: <strong>R$ " . $total_valor . "</strong><br>Bases dos Contratos: <strong>R$ " . $total_base_contrato . "</strong></td>";
								echo "<td>";
								echo "$% Total: <span style='color:#A5240E;'><strong>BS¢ " . $total_fortcoins . "</strong></span></br><hr>";
							}

							echo "</div>";
							echo "</td>";
							echo "</tr>";
							echo "</table>";
							echo "</td>";
							echo "</tr>";
							echo "<tr class='even'><div align='left'>";
							echo "<td colspan='7'><div align='center'>";
							echo "<table>";
							echo "<tr>";
							if (($_GET["buscar"]) || (!$administracao)) {
								$sql_select_all = mysql_query("SELECT COUNT(sys_vendas.vendas_id) AS total FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)" . $join_unidade . $join_tabela . $join_banco_compra . " WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" .
									$filtros_sql . ";")
									or die(mysql_error());
								$row_total_registros = mysql_fetch_array($sql_select_all);
							}
							$total_registros = $row_total_registros["total"];
							$pags = ceil($total_registros / $qnt);
							$max_links = 6;
							echo "<td>";
							echo "<a href='" . $links_filtros . "&ordemi=" . $ordem . "&p=1' target='_self'>primeira pagina</a> ";
							echo "</td>";
							for ($i = $p - $max_links; $i <= $p - 1; $i++) {
								if ($i <= 0) {
								} else {
									echo "<td>";
									echo "<a href='" . $links_filtros . "&ordemi=" . $ordem . "&p=" . $i . "' target='_self'>" . $i . "</a> ";
									echo "</td>";
								}
							}
							echo "<td>";
							echo "<strong> [ " . $p . " ] </strong> ";
							echo "</td>";
							for ($i = $p + 1; $i <= $p + $max_links; $i++) {
								if ($i > $pags) {
								} else {
									echo "<td>";
									echo "<a href='" . $links_filtros . "&ordemi=" . $ordem . "&p=" . $i . "' target='_self'>" . $i . "</a> ";
									echo "</td>";
								}
							}
							echo "<td>";
							echo "<a href='" . $links_filtros . "&ordemi=" . $ordem . "&p=" . $pags . "' target='_self'>ultima pagina</a> ";
							echo "</td>";
							echo "</tr>";
							echo "</table>";
							echo "</div></td>";
							echo "</div></tr>";
							?>
						</tbody>
					</table>
			</tbody>
		</table>
		</table>
		<div align="center">
			Exibindo
			<select name="qnt" style="display: inline;" onchange="this.form.submit()">
				<option value="10" <?php if ($qnt == "10") {
										echo " selected";
									} ?>>10</option>
				<option value="20" <?php if ($qnt == "20") {
										echo " selected";
									} ?>>20</option>
				<option value="30" <?php if ($qnt == "30") {
										echo " selected";
									} ?>>30</option>
			</select>
			de um total de <?php echo $total_registros; ?>
		</div>
	</div>
	</div>
	</form>
<?php endif; ?>