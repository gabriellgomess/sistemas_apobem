<?php
// echo "gestao : $gestao_equipes";
// if($user_id==957)
// {
// 	echo implode(",",getEquipesSupervisionadas($user_id));
// }

// function getEquipesSupervisionadas($user_id)
// {
// 	$sql_equipes_superv="SELECT equipe_id FROM sys_equipes WHERE equipe_supervisor='1004'";
// 	$result_equipes_superv = mysql_query($sql_equipes_superv) or die("Erro: " . mysql_error());
// 	while ($row_equipes_superv = mysql_fetch_assoc($result_equipes_superv))
// 	{
// 		$equipes_superv[] = $row_equipes_superv['equipe_id'];
// 	}
// 	return $equipes_superv;
// }

/*
<script type="text/javascript">
window.onload = function()
{
    if(typeof(Storage) !== "undefined") {
        if (localStorage.acesscount) {
        	if(Number(localStorage.acesscount) < 3)
        	{
        		localStorage.acesscount = Number(localStorage.acesscount)+1;
        		document.getElementById('aviso').innerHTML = "<strong style='color: red; border: solid 2px red; padding: 2px;'>AVISO: Caso a página esteja desconfigurada favor atualizar com CTRL+F5.</strong>";
        	}            
        } else {
            localStorage.acesscount = 1;
            document.getElementById('aviso').innerHTML = "<strong style='color: red; border: solid 2px red; padding: 2px;'>AVISO: Caso a página esteja desconfigurada favor atualizar com CTRL+F5.</strong>";
        }        
    } else {
        document.getElementById('aviso').innerHTML = "<strong style='color: red; border: solid 2px red; padding: 2px;'>AVISO: Caso a página esteja desconfigurada favor atualizar com CTRL+F5.</strong>";
    }	
}
</script>
<div id="aviso"> </div>
*/

include("sistema/utils/utils.php");
?>

<!-- PRE CARREGAMENTO -->
<?php if ($_GET["carregado"] != "1") : ?>
	<meta http-equiv="Refresh" content="0; url=<?php echo $_SERVER[REQUEST_URI]; ?>&carregado=1"></br>
	<div align="center"><img src="sistema/imagens/loading.gif"></div>
	</br>
	<div align="center">Carregando... Aguarde!</div>
	</br></br></br></br>
<?php else : ?>

	<script type="text/javascript" src="sistema/vendas/js/datepicker.js?<?php echo filemtime("sistema/vendas/js/datepicker.js"); ?>"></script>
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
	<?php


	$cpf = $_GET["cpf"];
	$clients_cat = $_GET["clients_cat"];
	if ($_GET["p"]) {
		$pagina = $_GET["p"];
	} else {
		$pagina = "1";
	}

	$query = mysql_query("UPDATE jos_users SET atendimento='0', atendimento_fim='" . date('Y-m-d H:i:s') . "' WHERE id='$user_id' AND atendimento='1';") or die(mysql_error());

	$url_consulta_clientes = $_SERVER['REQUEST_URI'];
	$query = mysql_query("UPDATE jos_users SET url_consulta_clientes='$url_consulta_clientes' WHERE id='$user_id';") or die(mysql_error());
	//echo "Consulta Salva com Sucesso <br/>";

	if ($_GET["nome"]) {
		$nome = $_GET["nome"];
		$select_nome = " AND (clients_nm like '%" . $nome . "%' OR cliente_nome like '%" . $nome . "%')";
	}
	if ($_GET["nome"] == "VAZIO!") {
		$nome = $_GET["nome"];
		$select_nome = " AND (clients_nm is null AND cliente_nome is null)";
	}
	if ($_GET["cliente_empregador"]) {
		$cliente_empregador = $_GET["cliente_empregador"];
		$select_empregador = " AND cliente_empregador = '" . $cliente_empregador . "'";
	}

	if ($_GET["vendas_banco"]) {
		$vendas_banco = $_GET["vendas_banco"];
		$filtros_sql = $filtros_sql . " AND vendas_banco = '" . $vendas_banco . "'";
	}
	if ($_GET["vendas_proposta"]) {
		$vendas_proposta = $_GET["vendas_proposta"];
		$filtros_sql = $filtros_sql . " AND vendas_proposta = '" . $vendas_proposta . "'";
	}
	if ($_GET["vendas_num_apolice"]) {
		$vendas_num_apolice = $_GET["vendas_num_apolice"];
		$filtros_sql = $filtros_sql . " AND vendas_num_apolice like '%" . $vendas_num_apolice . "%'";
	}
	if ($_GET["vendas_id"]) {
		$vendas_id = $_GET["vendas_id"];
		$filtros_sql = $filtros_sql . " AND vendas_id = '" . $vendas_id . "'";
	}
	if ($_GET["cliente_matricula"]) {
		$cliente_matricula = $_GET["cliente_matricula"];
		$filtros_sql = $filtros_sql . " AND (clients_prec_cp like '%" . $cliente_matricula . "%' OR cliente_beneficio like '%" . $cliente_matricula . "%')";
	}
	if ($_GET["vendas_vendedor"]) {
		$vendas_vendedor = $_GET["vendas_vendedor"];
		$filtros_sql = $filtros_sql . " AND vendas_vendedor like '%" . $vendas_vendedor . "%'";
	}
	if ($_GET["vendas_turno"]) {
		$vendas_turno = $_GET["vendas_turno"];
		$filtros_sql = $filtros_sql . " AND vendas_turno = '" . $vendas_turno . "'";
	}
	if ($_GET["vendas_pgto"]) {
		$vendas_pgto = $_GET["vendas_pgto"];
		$filtros_sql = $filtros_sql . " AND vendas_pgto = '" . $vendas_pgto . "'";
	}
	if ($_GET["vendas_pago_vendedor"]) {
		$vendas_pago_vendedor = $_GET["vendas_pago_vendedor"];
		$filtros_sql = $filtros_sql . " AND vendas_pago_vendedor = '" . $vendas_pago_vendedor . "'";
	}
	if ($_GET["vendas_status_motivo"]) {
		$vendas_status_motivo = $_GET["vendas_status_motivo"];
		$filtros_sql = $filtros_sql . " AND vendas_status_motivo LIKE '" . $vendas_status_motivo . "'";
	}


	if ($_GET["vendas_apolice"]) {
		$vendas_apolice = $_GET["vendas_apolice"];
		for ($i = 0; $i < count($vendas_apolice); $i++) {
			if ($vendas_apolice[$i] != "") {
				if ($i == 0) {
					$select_apolice = " AND (vendas_apolice = '" . $vendas_apolice[$i] . "'";
				} else {
					$select_apolice = $select_apolice . " OR vendas_apolice = '" . $vendas_apolice[$i] . "'";
				}
			}
			$aux_stat = $i;
		}
		if ($vendas_apolice[$aux_stat] != "") {
			$select_apolice = $select_apolice . ")";
		}
		for ($i = 0; $i < count($vendas_apolice); $i++) {
			if ($vendas_apolice[$i] != "") {
				$pag_apolice = $pag_apolice . "&vendas_apolice[]=" . $vendas_apolice[$i];
			}
		}
		$filtros_sql = $filtros_sql . $select_apolice;
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
		$filtros_sql = $filtros_sql . $select_status;
	}


	if ($_GET["vendas_debito_banco"]) {
		$vendas_debito_banco = $_GET["vendas_debito_banco"];
		for ($i = 0; $i < count($vendas_debito_banco); $i++) {
			if ($vendas_debito_banco[$i] != "") {
				if ($i == 0) {
					$select_debito_banco = " AND (vendas_debito_banco = '" . $vendas_debito_banco[$i] . "'";
				} else {
					$select_debito_banco = $select_debito_banco . " OR vendas_debito_banco = '" . $vendas_debito_banco[$i] . "'";
				}
			}
			$aux_banco = $i;
		}
		if ($vendas_debito_banco[$aux_banco] != "") {
			$select_debito_banco = $select_debito_banco . ")";
		}
		for ($i = 0; $i < count($vendas_debito_banco); $i++) {
			if ($vendas_debito_banco[$i] != "") {
				$pag_debito_banco = $pag_debito_banco . "&vendas_debito_banco[]=" . $vendas_debito_banco[$i];
			}
		}
		$filtros_sql = $filtros_sql . $select_debito_banco;
	}

	if ($_GET["cliente_uf"]) {
		$cliente_uf = $_GET["cliente_uf"];
		for ($i = 0; $i < count($cliente_uf); $i++) {
			if ($cliente_uf[$i] != "") {
				if ($i == 0) {
					$select_cliente_uf = " AND (cliente_uf = '" . $cliente_uf[$i] . "'";
				} else {
					$select_cliente_uf = $select_cliente_uf . " OR cliente_uf = '" . $cliente_uf[$i] . "'";
				}
			}
			$aux_banco = $i;
		}
		if ($cliente_uf[$aux_banco] != "") {
			$select_cliente_uf = $select_cliente_uf . ")";
		}
		for ($i = 0; $i < count($cliente_uf); $i++) {
			if ($cliente_uf[$i] != "") {
				$pag_cliente_uf = $pag_cliente_uf . "&cliente_uf[]=" . $cliente_uf[$i];
			}
		}
		$filtros_sql = $filtros_sql . $select_cliente_uf;
	}

	$consultor_unidade = $_GET["consultor_unidade"];

	$join_unidade = " INNER JOIN jos_users ON sys_vendas_seguros.vendas_consultor = jos_users.id";
	if ($_GET["consultor_unidade"]) {
		for ($i = 0; $i < count($consultor_unidade); $i++) {
			if ($consultor_unidade[$i] != "") {
				if ($i == 0) {
					$select_unidade = " AND (jos_users.unidade = '" . $consultor_unidade[$i] . "'";
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
		$filtros_sql = $filtros_sql . $select_unidade;
	}

	if ($_GET["vendas_user"]) {
		$vendas_user = $_GET["vendas_user"];
		for ($i = 0; $i < count($vendas_user); $i++) {
			if ($vendas_user[$i] != "") {
				if ($i == 0) {
					$select_vendas_user = " AND (vendas_user = '" . $vendas_user[$i] . "'";
				} else {
					$select_vendas_user = $select_vendas_user . " OR vendas_user = '" . $vendas_user[$i] . "'";
				}
			}
			$aux_banco = $i;
		}
		if ($vendas_user[$aux_banco] != "") {
			$select_vendas_user = $select_vendas_user . ")";
		}
		for ($i = 0; $i < count($vendas_user); $i++) {
			if ($vendas_user[$i] != "") {
				$pag_vendas_user = $pag_vendas_user . "&vendas_user[]=" . $vendas_user[$i];
			}
		}
	}

	if ($_GET["data_intencionamento"]) {
		$vendas_dia_intencionamento = dataBR_to_dataDB($_GET["data_intencionamento"]);
		$filtros_sql = $filtros_sql . " AND vendas_dia_intencionamento like '%" . $vendas_dia_intencionamento . "%'";
	}

	if ($_GET["vendas_cartao_validade_ano"]) {
		$filtros_sql = $filtros_sql . " AND vendas_cartao_validade_ano = '" . $_GET['vendas_cartao_validade_ano'] . "'";
		if ($_GET["vendas_cartao_validade_mes_ini"]) {
			$filtros_sql = $filtros_sql . " AND vendas_cartao_validade_mes >= '" . $_GET['vendas_cartao_validade_mes_ini'] . "'";
		}
		if ($_GET["vendas_cartao_validade_mes_fim"]) {
			$filtros_sql = $filtros_sql . " AND vendas_cartao_validade_mes <= '" . $_GET['vendas_cartao_validade_mes_fim'] . "'";
		}
	}

	if ($_GET["filtro_data1"]) {
		$filtro_data1 = $_GET["filtro_data1"];
	} else {
		$filtro_data1 = "1";
	}
	if ($filtro_data1 == "1") {
		$normal_1_2 = "vendas_dia_venda";
		$normal_1_2_hr_ini = " 00:00:00'";
		$normal_1_2_hr_fim = " 23:59:59'";
	}
	if ($filtro_data1 == "1") {
		$normal_1_2 = "vendas_dia_venda";
		$normal_1_2_hr_ini = " 00:00:00'";
		$normal_1_2_hr_fim = " 23:59:59'";
	}
	if ($filtro_data1 == "2") {
		$normal_1_2 = "vendas_dia_ativacao";
		$normal_1_2_hr_ini = "'";
		$normal_1_2_hr_fim = "'";
	}
	if ($filtro_data1 == "4") {
		if ($_GET["dp-normal-1"]) {
			$pag_data_ini = $_GET["dp-normal-1"];
			$data_inicial_intencionamento = dataBR_to_dataDB($_GET["dp-normal-1"]);
		} else {
			//se não houver data inicial é igual a data de hoje
			$data_inicial_intencionamento = date("Y-m-d");
		}
		if ($_GET["dp-normal-2"]) {
			$pag_data_fim = $_GET["dp-normal-2"];
			$data_final_intencionamento = dataBR_to_dataDB($_GET["dp-normal-2"]);
		} else {
			//se não houver data final é igual a inicial + um dia
			$data_final_intencionamento = date('Y-m-d', strtotime("+1 day", strtotime($data_inicial_intencionamento)));
		}

		$filtros_sql = $filtros_sql . " AND vendas_dia_intencionamento >= '" . $data_inicial_intencionamento . "' AND vendas_dia_intencionamento <= '" . $data_final_intencionamento . "'";
	} else {
		if ($_GET["dp-normal-1"]) {
			$pag_data_ini = $_GET["dp-normal-1"];
			$data_ini = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));
			$filtros_sql = $filtros_sql . " AND " . $normal_1_2 . " >= '" . $data_ini . $normal_1_2_hr_ini;
		}

		if ($_GET["dp-normal-2"]) {
			$pag_data_fim = $_GET["dp-normal-2"];
			$data_fim = implode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "-" : "/", $_GET["dp-normal-2"])));
			$filtros_sql = $filtros_sql . " AND " . $normal_1_2 . " <= '" . $data_fim . $normal_1_2_hr_fim;
		}
	}

	if ($_GET["ordemi"]) {
		$ordem = $_GET["ordemi"];
	} else {
		$ordem = "vendas_id";
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

	if ($_GET["contar"]) {
		$contagem = ", COUNT(sys_vendas_seguros.cliente_cpf) AS contagem";
		$agrupamento = " GROUP BY sys_vendas_seguros.cliente_cpf ";
		if ($_GET["ordemi"]) {
			$ordem = $_GET["ordemi"];
		} else {
			$ordem = "contagem";
		}
		if ($_GET["num_vendas"]) {
			$select_num_vendas = " HAVING contagem > '" . $_GET['num_vendas'] . "'";
		}
	} else {
		$agrupamento = "";
	}

	include("sistema/utf8.php");

	/* ABAIXO ALGUMAS EXCEÇÕES NECESSÁRIAS PARA ESTA PÁGINA: */
	if ($auditores) {
		$administracao = 1;
	}
	if ($supervisor_vendas_seguros) {
		$administracao = 1;
	}
	if ($diretoria) {
		$sup_operacional_seg = 1;
	}
	if ($operacional_seguros_consignado) {
		$administracao = 1;
	}
	if ($gerente_comercial_seguros) {
		$administracao = 1;
	}

	if ($administracao == 1) {
		if ($suporte_equipes_seguros) {
			$filtro_apolices = "";
			$apolice_tipo = "";
		} else {
			if ($operacional_seguros_consignado == 1) {
				$filtros_sql_apolice_tipo = " AND apolice_tipo=2";
				$filtro_apolices = " WHERE apolice_tipo=2";
				$apolice_tipo = 2;
			} elseif ((!$sup_operacional_seg) && ($user_id != 739) && (!$supervisor_unidade) && (!$supervisor_unidade_seguros)) {
				$filtros_sql_apolice_tipo = " AND apolice_tipo=1";
				$filtro_apolices = " WHERE apolice_tipo=1";
				$apolice_tipo = 1;
			}
		}
	}

	if ($administracao == 1 || $supervisor_equipe_vendas == 1 || $gerente_plataformas == 1) {
		if ($_GET["vendas_consultor"]) {
			$vendas_consultor = $_GET["vendas_consultor"];
			for ($i = 0; $i < count($vendas_consultor); $i++) {
				if ($vendas_consultor[$i] != "") {
					if ($i == 0) {
						$select_consultor = " AND (vendas_consultor = '" . $vendas_consultor[$i] . "'";
					} else {
						$select_consultor = $select_consultor . " OR vendas_consultor = '" . $vendas_consultor[$i] . "'";
					}
				}
				$aux_consultor = $i;
			}
			if ($vendas_consultor[$aux_consultor] != "") {
				$select_consultor = $select_consultor . ")";
			}

			for ($i = 0; $i < count($vendas_consultor); $i++) {
				if ($vendas_consultor[$i] != "") {
					$pag_consultor = $pag_consultor . "&vendas_consultor[]=" . $vendas_consultor[$i];
				}
			}
		}
	} else {
		$select_consultor = " AND vendas_consultor = '" . $user_id . "'";
		if ((!$recuperacao_seguros) && (!$empresa_fortune)) {

			/*
		$filtros_sql = $filtros_sql." AND (vendas_status = '1' 
		OR vendas_status = '2' 
		OR vendas_status = '3' 
		OR vendas_status = '15' 
		OR vendas_status = '16' 
		OR vendas_status = '67' 
		OR vendas_status = '69' 
		OR apolice_tipo = '2')";
		*/
			$select_vendas_status = " AND (vendas_status = '3' 
		OR vendas_status = '9' 
		OR vendas_status = '10' 
		OR vendas_status = '15'
		OR vendas_status = '16'
		OR vendas_status = '19'
		OR vendas_status = '45'
		OR vendas_status = '67'
		OR vendas_status = '73'
		OR vendas_status = '76'
		OR vendas_status = '69')";
			/*OR ( vendas_apolice = '18' OR vendas_apolice = '19' OR vendas_apolice = '20' OR vendas_apolice = '21' OR vendas_apolice = '22' OR vendas_apolice = '23' OR vendas_apolice = '24' )*/
		}
	}

	if ($gerente_comercial_seguros) {
		$filtros_sql_apolice_tipo = " AND apolice_tipo=2";
		$filtro_apolices = " WHERE apolice_tipo=2";
		$select_consultor = $select_consultor . " AND jos_users.gerente_comercial = '" . $user_id . "'";
	}

	if ($supervisor_equipe_vendas || $coordenador_plataformas) {
		$select_origem = "";
		$select_unidade = "";
		$select_equipe = "";
		$select_consultor = "";
		$select_equipe_supervisor = "";
		$select_vendas_status = "";
		$count_equipes = 0;
		if ($coordenador_plataformas) {
			$select_coordenador_plataforma = " OR equipe_coordenador = '" . $user_id . "'";
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
			if ($_GET["vendas_consultor"]) {
				$select_consultor = retornaSelectVendasConsultor();
			} /* --> Função 'retornaSelectVendasConsultor()' inserida no final do código. (Horácio)*/
		} else {
			if (!$gestao_equipes) {
				$select_consultor = " AND vendas_consultor = " . $user_id . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
			}
		}
		$filtros_sql = $filtros_sql . $select_equipe;
	}

	if ($gerente_plataformas) {
		$select_origem = "";
		$select_unidade = "";
		$select_equipe = "";
		$select_consultor = "";
		$count_equipes = 0;

		$result_eq_coordenador = mysql_query("SELECT equipe_id FROM sys_equipes WHERE equipe_tipo = 2;")
			or die(mysql_error());
		while ($row_eq_coordenador = mysql_fetch_array($result_eq_coordenador)) {
			if ($row_eq_coordenador['equipe_id'] != "") {
				if ($count_equipes == 0) {
					$select_equipe = " AND (jos_users.equipe_id = '" . $row_eq_coordenador['equipe_id'] . "'";
					$select_equipe_plataforma = " AND (jos_users.equipe_id = '" . $row_eq_coordenador['equipe_id'] . "'";
				} else {
					$select_equipe = $select_equipe . " OR jos_users.equipe_id = '" . $row_eq_coordenador['equipe_id'] . "'";
					$select_equipe_plataforma = $select_equipe_plataforma . " OR equipe_id = '" . $row_eq_coordenador['equipe_id'] . "'";
				}
				$count_equipes++;
			}
		}
		if ($count_equipes) {
			$select_equipe = $select_equipe . ") AND jos_users.equipe_id > 0";
			$select_equipe_plataforma = $select_equipe_plataforma . ") AND jos_users.equipe_id > 0";
			if ($_GET["vendas_consultor"]) {
				$select_consultor = retornaSelectVendasConsultor();
			} /* --> Função 'retornaSelectVendasConsultor()' inserida no final do código. (Horácio)*/
		} else {
			$select_consultor = " AND vendas_consultor = " . $user_id . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";
		}
		$filtros_sql = $filtros_sql . $select_equipe;
	}

	if ($supervisor_unidade) {
		$select_equipe = "";
		$select_consultor = "";
		$select_equipe_supervisor = "";
		$select_vendas_status = "";
		$count_equipes = 0;
		$select_unidade = " AND jos_users.unidade = '" . $user_unidade . "'";
		if ($_GET["vendas_consultor"]) {
			$select_consultor = retornaSelectVendasConsultor();
		}  /* --> Função 'retornaSelectVendasConsultor()' inserida no final do código. (Horácio)*/
		$filtros_sql = $filtros_sql . $select_unidade;
		$filtros_sql_apolice_tipo = " AND apolice_tipo=2";
		$filtro_apolices = " WHERE apolice_tipo=2";
		$apolice_tipo = 2;
	}

	if ($auditores_seguros) {
		$filtros_sql_apolice_tipo = "";
		$filtro_apolices = "";
		$apolice_tipo = "";
	}

	if ($gerencia_de_propostas == 1) {
		$filtros_sql_apolice_tipo = " AND apolice_tipo=2";
		$filtro_apolices = " WHERE apolice_tipo=2";
		$apolice_tipo = 2;
	}

	$filtros_sql = $filtros_sql . $filtros_sql_apolice_tipo . $select_vendas_status;

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

	echo "<pre style='display: none' id='teste_query'>";
	echo "SELECT *" . $contagem . " FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)" . $join_unidade . " 
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" .
		$select_nome .
		$select_empregador .
		$filtros_sql .
		$select_consultor .
		$select_vendas_user .
		$agrupamento . $select_num_vendas . " ORDER BY " . $ordem . " " . $ordenacao . " LIMIT " . $inicio . ", " . $qnt . ";";
	echo "</pre>";

	$result = mysql_query("SELECT *" . $contagem . " FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)" . $join_unidade . " 
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" .
		$select_nome .
		$select_empregador .
		$filtros_sql .
		$select_consultor .
		$select_vendas_user .
		$agrupamento . $select_num_vendas . " ORDER BY " . $ordem . " " . $ordenacao . " LIMIT " . $inicio . ", " . $qnt . ";")
		or die(mysql_error());

	?>
	<?php $curURL = $_SERVER["REQUEST_URI"]; ?>



	<?php if ($administracao == 1 || $supervisor_equipe_vendas == 1) : ?>
		<?php
		$link_rel_tela = "index.php?option=com_k2&view=item&id=64:minhas-vendas&Itemid=476&tmpl=component&print=1&acao=relatorio_vendas_seguros_tela&vendas_id=" . $vendas_id . "&nome=" . $nome . "&prec=" . $prec . "&cpf=" . $cpf . "&vendas_mes=" . $vendas_mes . "&contar=" . $_GET['contar'] . "&num_vendas=" . $_GET['num_vendas'] . "&consultor_unidade=" . $pag_unidade . "&vendas_consultor=" . $pag_consultor . "&vendas_status=" . $pag_status . "&vendas_debito_banco=" . $pag_debito_banco . "&cliente_uf=" . $pag_cliente_uf . "&cliente_empregador=" . $cliente_empregador . "&vendas_promotora=" . $vendas_promotora . "&vendas_banco=" . $vendas_banco . "&vendas_pgto=" . $vendas_pgto . "&vendas_pago_vendedor=" . $vendas_pago_vendedor . "&apolice_tipo=" . $apolice_tipo . "&vendas_apolice=" . $pag_apolice . "&filtro_data1=" . $filtro_data1 . "&dp-normal-1=" . $pag_data_ini . "&dp-normal-2=" . $pag_data_fim . "&dp-normal-3=" . $pag_data_aud_ini . "&dp-normal-4=" . $pag_data_aud_fim . "&vendas_status_motivo=" . $vendas_status_motivo;
		$link_rel_xls = "sistema/vendas/relatorios/seguros_xls.php?vendas_id=" . $vendas_id . "&nome=" . $nome . "&prec=" . $prec . "&cpf=" . $cpf . "&vendas_mes=" . $vendas_mes . "&contar=" . $_GET['contar'] . "&num_vendas=" . $_GET['num_vendas'] . "&consultor_unidade=" . $pag_unidade . "&vendas_consultor=" . $pag_consultor . "&vendas_status=" . $pag_status . "&vendas_debito_banco=" . $pag_debito_banco . "&cliente_uf=" . $pag_cliente_uf . "&cliente_empregador=" . $cliente_empregador . "&vendas_cartao_validade_ano=" . $_GET['vendas_cartao_validade_ano'] . "&vendas_cartao_validade_mes_ini=" . $_GET['vendas_cartao_validade_mes_ini'] . "&vendas_cartao_validade_mes_fim=" . $_GET['vendas_cartao_validade_mes_fim'] . "&vendas_banco=" . $vendas_banco . "&vendas_pgto=" . $vendas_pgto . "&apolice_tipo=" . $apolice_tipo . "&vendas_apolice=" . $pag_apolice . "&vendas_pago_vendedor=" . $vendas_pago_vendedor . "&filtro_data1=" . $filtro_data1 . "&dp-normal-1=" . $pag_data_ini . "&dp-normal-2=" . $pag_data_fim . "&dp-normal-3=" . $pag_data_aud_ini . "&dp-normal-4=" . $pag_data_aud_fim . "&vendas_status_motivo=" . $vendas_status_motivo;
		$link_rel_blacklist = "sistema/vendas/relatorios/seguros_blacklist.php?vendas_id=" . $vendas_id . "&nome=" . $nome . "&prec=" . $prec . "&cpf=" . $cpf . "&vendas_mes=" . $vendas_mes . "&contar=" . $_GET['contar'] . "&num_vendas=" . $_GET['num_vendas'] . "&consultor_unidade=" . $pag_unidade . "&vendas_consultor=" . $pag_consultor . "&vendas_status=" . $pag_status . "&vendas_debito_banco=" . $pag_debito_banco . "&cliente_uf=" . $pag_cliente_uf . "&cliente_empregador=" . $cliente_empregador . "&vendas_cartao_validade_ano=" . $_GET['vendas_cartao_validade_ano'] . "&vendas_cartao_validade_mes_ini=" . $_GET['vendas_cartao_validade_mes_ini'] . "&vendas_cartao_validade_mes_fim=" . $_GET['vendas_cartao_validade_mes_fim'] . "&vendas_banco=" . $vendas_banco . "&vendas_pgto=" . $vendas_pgto . "&apolice_tipo=" . $apolice_tipo . "&vendas_apolice=" . $pag_apolice . "&vendas_pago_vendedor=" . $vendas_pago_vendedor . "&filtro_data1=" . $filtro_data1 . "&dp-normal-1=" . $pag_data_ini . "&dp-normal-2=" . $pag_data_fim . "&dp-normal-3=" . $pag_data_aud_ini . "&dp-normal-4=" . $pag_data_aud_fim . "&vendas_status_motivo=" . $vendas_status_motivo;
		?>

		<div>
			<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_rel_tela; ?>">Imprimir Relatório</a>
			<br>
			<a id='linkExportar' class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_rel_xls; ?>">Exportar para Excel<br></a>

			<a id='linkExportarBlacklist' class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_rel_blacklist; ?>">Exportar para Blacklist</a>

			<?php if ($finaneiro == 1 || $administracao == 1) : ?>
				<br>
				<a id='linkExportar' class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_rel_xls; ?>">Exportar para Excel/Financeiro<br></a>
			<?php endif; ?>

			<?php if ($_GET["vendas_banco"] == "11" && $diretoria == 1) : ?>
				<?php $link_rel_xls = "sistema/vendas/relatorios/seguros_apobem_xls.php?vendas_id=" . $vendas_id . "&nome=" . $nome . "&prec=" . $prec . "&cpf=" . $cpf . "&vendas_mes=" . $vendas_mes . "&contar=" . $_GET['contar'] . "&num_vendas=" . $_GET['num_vendas'] . "&consultor_unidade=" . $pag_unidade . "&vendas_consultor=" . $pag_consultor . "&vendas_status=" . $pag_status . "&vendas_debito_banco=" . $pag_debito_banco . "&cliente_uf=" . $pag_cliente_uf . "&cliente_empregador=" . $cliente_empregador . "&vendas_promotora=" . $vendas_promotora . "&vendas_banco=" . $vendas_banco . "&vendas_pgto=" . $vendas_pgto . "&apolice_tipo=" . $apolice_tipo . "&vendas_apolice=" . $pag_apolice . "&vendas_pago_vendedor=" . $vendas_pago_vendedor . "&filtro_data1=" . $filtro_data1 . "&dp-normal-1=" . $pag_data_ini . "&dp-normal-2=" . $pag_data_fim . "&dp-normal-3=" . $pag_data_aud_ini . "&dp-normal-4=" . $pag_data_aud_fim . "&vendas_status_motivo=" . $vendas_status_motivo; ?>
				<br><a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_rel_xls; ?>">Exportar planilha de movimentação de vidas</a>
			<?php endif; ?>



		</div>

		<!-- Javascript que bloqueia o elemento nos horários definidos -->

		<script>
			let periodo_1_a = new Date(0, 0, 0, 9, 0, 0, 0).toLocaleTimeString();
			let periodo_1_b = new Date(0, 0, 0, 12, 0, 0, 0).toLocaleTimeString();
			let periodo_2_a = new Date(0, 0, 0, 13, 0, 0, 0).toLocaleTimeString();
			let periodo_2_b = new Date(0, 0, 0, 18, 0, 0, 0).toLocaleTimeString();
			let periodo_atual = new Date().toLocaleTimeString();

			console.log(periodo_1_a)
			console.log(periodo_1_b)
			console.log(periodo_2_a)
			console.log(periodo_2_b)
			console.log(periodo_atual)

			if (periodo_atual < periodo_1_a || periodo_atual > periodo_1_b && periodo_atual < periodo_2_a || periodo_atual > periodo_2_b) {
				document.getElementById("linkExportar").style.display = "block";
				console.log('no horário')
			} else {
				document.getElementById("linkExportar").style.display = "none";
				console.log('fora de horário')
			}
		</script>

	<?php endif; ?>

	<script type="text/javascript">
		//Initialize first demo:
		ddaccordion.init({
			headerclass: "mypets2", //Shared CSS class name of headers group
			contentclass: "thepet2", //Shared CSS class name of contents group
			revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
			mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
			collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
			defaultexpanded: [7, 10], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
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

		function SomenteNumero(e) {
			var tecla = (window.event) ? event.keyCode : e.which;
			if ((tecla > 47 && tecla < 58)) return true;
			else {
				if (tecla == 8 || tecla == 0) return true;
				else return false;
			}
		}
	</script>
	<form action="index.php" method="GET">
		<input id="sis_campo" name="option" type="hidden" id="option" value="com_k2" />
		<input id="sis_campo" name="view" type="hidden" id="view" value="item" />
		<input id="sis_campo" name="id" type="hidden" id="id" value="64" />
		<input id="sis_campo" name="Itemid" type="hidden" id="Itemid" value="476" />
		<?php if ((!$_GET["dp-normal-1"]) && (!$_GET["dp-normal-2"])) : ?>
			<?php

			/*
			<div class="css_consulta_aviso">
			<strong>AVISO</strong>: Por padrão, estão sendo exibidas abaixo,<strong> 
			<span style="color:#ff0000;">TODAS AS VENDAS</span> desde o início do sistema.</strong><br/>
			Para exibir vendas de outras datas, utilize o filtro da data da implantação abaixo.
			</div>
			*/
			?>
		<?php endif; ?>
		<div id="teste" style="display: none">
			<?php var_dump($_GET); ?>
		</div>
		<div class="css_form_container">
			<div class="css_form_group">
				<div class="css_form_campo">Código: <input id="vendas_id" name="vendas_id" value="<?php echo $vendas_id; ?>" type="text" maxlength="6" size="5" /></div>
				<div class="css_form_campo">CPF: <input id="cpf" name="cpf" value="<?php echo $cpf; ?>" type="text" onkeyup="cpfSomenteNumero(this)" size="11" /></div>
				<div class="css_form_campo">Nome: <input id="nome" name="nome" value="<?php echo $nome; ?>" type="text" size="25" /></div>
				<div class="css_form_campo">Nº da Proposta: <input id="vendas_proposta" name="vendas_proposta" value="<?php echo $vendas_proposta; ?>" type="text" maxlength="20" size="10" onKeyPress="return SomenteNumero(event);" /></div>
				<div class="css_form_campo">Nº da Apólice: <input id="vendas_num_apolice" name="vendas_num_apolice" value="<?php echo $vendas_num_apolice; ?>" type="text" maxlength="20" size="15" /></div>
				<div class="css_form_campo">Órgão: <input id="cliente_empregador" name="cliente_empregador" value="<?php echo $cliente_empregador; ?>" type="text" size="12" /></div>
				<div class="css_form_campo">Matrícula: <input id="cliente_matricula" name="cliente_matricula" value="<?php echo $cliente_matricula; ?>" type="text" maxlength="10" size="10" /></div>
				<div class="css_form_campo">Vendedor: <input id="vendas_vendedor" name="vendas_vendedor" value="<?php echo $vendas_vendedor; ?>" type="text" maxlength="50" size="30" /></div>

				<!-- Data Intencionamento: <input type="text" class="w8em format-d-m-y highlight-days-67" id="data_pesquisa_intencao" name="data_intencionamento" maxlength="10" size="8" value="<?php echo dataDB_to_dataBR($vendas_dia_intencionamento); ?>" /> -->

			</div>
			<div class="css_form_group">
				<?php if ($administracao) : ?>
					<div class="css_form_campo">
						<strong>Contar o nº de Vendas de cada cliente.</strong><input type="checkbox" name="contar" value="1" <?php if ($_GET["contar"]) {
																																	echo "checked";
																																} ?>>
						<?php if ($_GET["contar"]) : ?>
							<br>Exibir clientes com mais de <input id="num_vendas" name="num_vendas" value="<?php echo $_GET["num_vendas"]; ?>" type="text" size="1" maxlength="2" /> vendas.
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="css_form_campo">
					<a href="index.php?option=com_k2&view=item&layout=item&id=64&Itemid=476"><button name="limpar" type="button" value="limpar">Limpar</button></a>
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
													} ?>>Data da Venda</option>
								<option value="2" <?php if ($filtro_data1 == "2") {
														echo " selected";
													} ?>>Data de Ativação</option>
								<option value="4" <?php if ($filtro_data1 == "4") {
														echo " selected";
													} ?>>Data Intencionamento</option>
							</select>
							<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-1" name="dp-normal-1" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-1"]; ?>" />
							<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-2" name="dp-normal-2" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-2"]; ?>" />
						</div>
					</div>

					<div class="css_form_group sel_180px">
						<div class="css_form_campo">
							Validade do Cartão. Ano:<input id="vendas_cartao_validade_ano" name="vendas_cartao_validade_ano" value="<?php echo $_GET['vendas_cartao_validade_ano']; ?>" type="text" size="2" maxlength="4" />
							Mês: de<input id="vendas_cartao_validade_mes_ini" name="vendas_cartao_validade_mes_ini" value="<?php echo $_GET['vendas_cartao_validade_mes_ini']; ?>" type="text" size="1" maxlength="2" />
							até<input id="vendas_cartao_validade_mes_fim" name="vendas_cartao_validade_mes_fim" value="<?php echo $_GET['vendas_cartao_validade_mes_fim']; ?>" type="text" size="1" maxlength="2" />
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
							<select name="vendas_banco">
								<optgroup label="Seguradora">
									<option value="">---- Indiferente ----</option>
									<?php
									if (!$vendas_banco) {
										echo "<option value='' disabled selected>------ Seguradora ------</option>";
									}
									$result_bancos = mysql_query("SELECT * FROM sys_vendas_banco_seg ORDER BY banco_nm;")
										or die(mysql_error());
									while ($row_bancos = mysql_fetch_array($result_bancos)) {
										if ($row_bancos["banco_id"] == $vendas_banco) {
											$selected_banco = " selected";
										} else {
											$selected_banco = "";
										}
										echo "<option value='{$row_bancos['banco_id']}'{$selected_banco}>{$row_bancos['banco_nm']}</option>";
									}
									?>
								</optgroup>
							</select>
						</div>
						<div class="css_form_campo">
							<select name="vendas_pgto">
								<optgroup label="Forma de Pagamento">
									<option value="">---- Indiferente ----</option>
									<?php
									if (!$vendas_pgto) {
										echo "<option value='' disabled selected>------ Forma de Pagamento ------</option>";
									}
									$result_pgto = mysql_query("SELECT pgto_id, pgto_nm FROM sys_vendas_pgto ORDER BY pgto_nm;")
										or die(mysql_error());
									while ($row_pgto = mysql_fetch_array($result_pgto)) {
										if ($row_pgto["pgto_id"] == $vendas_pgto) {
											$selected_apolice = " selected";
										} else {
											$selected_apolice = "";
										}
										echo "<option value='{$row_pgto['pgto_id']}'{$selected_apolice}>{$row_pgto['pgto_nm']}</option>";
									}
									?>
								</optgroup>
							</select>
						</div>
						<div class="css_form_campo">
							<select name="vendas_pago_vendedor">
								<optgroup label="Pago ao Vendedor">
									<option value="">---- Indiferente ----</option>
									<option value="" <?php if (!$vendas_pago_vendedor) {
															echo " selected";
														} ?>>---- Pago ao Vendedor ----</option>
									<option value="1" <?php if ($vendas_pago_vendedor == "1") {
															echo " selected";
														} ?>>Não</option>
									<option value="2" <?php if ($vendas_pago_vendedor == "2") {
															echo " selected";
														} ?>>Sim</option>
								</optgroup>
							</select>
						</div>

						<div class="css_form_campo">
							<select name="vendas_status_motivo">
								<optgroup label="Forma de Pagamento">
									<option value="">---- Selecione o Motivo do Status ----</option>
									<?php
									$sql_status_motivo = "SELECT DISTINCT vendas_status_motivo FROM sys_vendas_seguros WHERE vendas_status_motivo IS NOT NULL ORDER BY vendas_status_motivo;";
									$result_status_motivo = mysql_query($sql_status_motivo)	or die(mysql_error());
									while ($row_status_motivo = mysql_fetch_array($result_status_motivo)) {
										if ($row_status_motivo["vendas_status_motivo"] == $vendas_status_motivo) {
											$selected_status_motivo = " selected";
										} else {
											$selected_status_motivo = "";
										}
										echo "<option value='{$row_status_motivo['vendas_status_motivo']}'{$selected_status_motivo}>{$row_status_motivo['vendas_status_motivo']}</option>";
									}
									?>
								</optgroup>
							</select>
						</div>

					</div>
				</div>
				<!-- corrige espaço do inline-block
	-->
				<div class="css_form_group grupo_b">
					<div style="width: 100%;">Campos de seleção múltipla. Utilize CTRL</div>

					<div class="css_form_campo css_multisel">Status:<br>
						<select name="vendas_status[]" multiple="multiple">
							<option value="">---- Indiferente ----</option>
							<?php
							$result_status = mysql_query("SELECT * FROM sys_vendas_status_seg ORDER BY status_id;")
								or die(mysql_error());
							while ($row_status = mysql_fetch_array($result_status)) {
								$selected_status = "";
								for ($i = 0; $i < count($vendas_status); $i++) {
									if ($vendas_status[$i] == $row_status["status_id"]) {
										$selected_status = "selected";
									}
								}
								echo "<option value='{$row_status['status_id']}'{$selected_status}>{$row_status['status_nm']}</option>";
							}
							?>
						</select>
					</div>

					<?php if ($administracao == 1) : ?>
						<div class="css_form_campo css_multisel">Alterado por último por:<br>
							<select name="vendas_user[]" multiple="multiple" style="height:64px; width:200px">
								<option value="">---- Indiferente ----</option>
								<?php
								$optgroup = "";
								$count_g = 0;
								$result_lastuser_form = mysql_query("SELECT DISTINCT vendas_user, name FROM sys_vendas_seguros 
				INNER JOIN jos_users ON sys_vendas_seguros.vendas_user = jos_users.username 
				INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
				LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)
				WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" . $filtros_sql . $select_consultor . " ORDER BY name;")
									or die(mysql_error());
								while ($row_lastuser_form = mysql_fetch_array($result_lastuser_form)) {
									$selected_user = "";
									for ($i = 0; $i < count($vendas_user); $i++) {
										if ($vendas_user[$i] == $row_lastuser_form["vendas_user"]) {
											$selected_user = " selected";
										}
									}
									if ($optgroup != $row_lastuser_form['name'][0]) {
										if ($count_g > 0) {
											echo "</optgroup>";
										}
										echo "<optgroup label=" . $row_lastuser_form['name'][0] . ">";
										$optgroup = $row_lastuser_form['name'][0];
										$count_g++;
									}
									echo "<option value='{$row_lastuser_form['vendas_user']}'{$selected_user}>{$row_lastuser_form['name']}</option>";
								}
								?>
							</select>
						</div>
					<?php endif; ?>

					<div class="css_form_campo css_multisel">Apólices:<br>
						<select name="vendas_apolice[]" multiple="multiple">
							<option value=''>------ Indiferente ------</option>
							<?php
							$result_apolice = mysql_query("SELECT apolice_id, apolice_nome FROM sys_vendas_apolices" . $filtro_apolices . " ORDER BY apolice_nome;")
								or die(mysql_error());
							while ($row_apolice = mysql_fetch_array($result_apolice)) {
								$selected_apolice = "";
								for ($i = 0; $i < count($vendas_apolice); $i++) {
									if ($vendas_apolice[$i] == $row_apolice["apolice_id"]) {
										$selected_apolice = " selected";
									}
								}
								echo "<option value='{$row_apolice['apolice_id']}'{$selected_apolice}>{$row_apolice['apolice_nome']}</option>";
							}
							?>
						</select>
					</div>

					<?php if ($administracao == 1) : ?>
						<div class="css_form_campo css_multisel">Estado:<br>
							<select name="cliente_uf[]" multiple="multiple">
								<option value="">---- Indiferente ----</option>
								<option value="AC" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "AC") {
															echo "selected";
														}
													} ?>>Acre</option>
								<option value="AL" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "AL") {
															echo "selected";
														}
													} ?>>Alagoas</option>
								<option value="AP" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "AP") {
															echo "selected";
														}
													} ?>>Amapá</option>
								<option value="AM" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "AM") {
															echo "selected";
														}
													} ?>>Amazonas</option>
								<option value="BA" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "BA") {
															echo "selected";
														}
													} ?>>Bahia</option>
								<option value="CE" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "CE") {
															echo "selected";
														}
													} ?>>Ceará</option>
								<option value="DF" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "DF") {
															echo "selected";
														}
													} ?>>Distrito Federal</option>
								<option value="ES" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "ES") {
															echo "selected";
														}
													} ?>>Espírito Santo</option>
								<option value="GO" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "GO") {
															echo "selected";
														}
													} ?>>Goiás</option>
								<option value="MA" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "MA") {
															echo "selected";
														}
													} ?>>Maranhão</option>
								<option value="MT" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "MT") {
															echo "selected";
														}
													} ?>>Mato Grosso</option>
								<option value="MS" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "MS") {
															echo "selected";
														}
													} ?>>Mato Grosso do Sul</option>
								<option value="MG" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "MG") {
															echo "selected";
														}
													} ?>>Minas Gerais</option>
								<option value="PA" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "PA") {
															echo "selected";
														}
													} ?>>Pará</option>
								<option value="PB" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "PB") {
															echo "selected";
														}
													} ?>>Paraíba</option>
								<option value="PR" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "PR") {
															echo "selected";
														}
													} ?>>Paraná</option>
								<option value="PE" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "PE") {
															echo "selected";
														}
													} ?>>Pernambuco</option>
								<option value="PI" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "PI") {
															echo "selected";
														}
													} ?>>Piauí</option>
								<option value="RJ" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "RJ") {
															echo "selected";
														}
													} ?>>Rio de Janeiro</option>
								<option value="RN" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "RN") {
															echo "selected";
														}
													} ?>>Rio Grande do Norte</option>
								<option value="RS" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "RS") {
															echo "selected";
														}
													} ?>>Rio Grande do Sul</option>
								<option value="RO" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "RO") {
															echo "selected";
														}
													} ?>>Rondônia</option>
								<option value="RR" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "RR") {
															echo "selected";
														}
													} ?>>Roraima</option>
								<option value="SC" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "SC") {
															echo "selected";
														}
													} ?>>Santa Catarina</option>
								<option value="SP" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "SP") {
															echo "selected";
														}
													} ?>>São Paulo</option>
								<option value="SE" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "SE") {
															echo "selected";
														}
													} ?>>Sergipe</option>
								<option value="TO" <?php for ($i = 0; $i < count($cliente_uf); $i++) {
														if ($cliente_uf[$i] == "TO") {
															echo "selected";
														}
													} ?>>Tocantins</option>
							</select>
						</div>
					<?php endif; ?>

					<?php if ($administracao == 1) : ?>
						<div class="css_form_campo css_multisel">Unidade:<br>
							<select name='consultor_unidade[]' multiple='multiple'>
								<option value=''>---- Indiferente ----</option>
								<?php
								$optgroup = "";
								$count_g = 0;
								$result_unidade = mysql_query("SELECT empresa_nome FROM sys_empresas ORDER BY empresa_nome;")
									or die(mysql_error());
								while ($row_unidade = mysql_fetch_array($result_unidade)) {
									$selected = "";
									for ($i = 0; $i < count($consultor_unidade); $i++) {
										if ($consultor_unidade[$i] == $row_unidade["empresa_nome"]) {
											$selected = "selected";
										}
									}
									if ($optgroup != $row_unidade['empresa_nome'][0]) {
										if ($count_g > 0) {
											echo "</optgroup>";
										}
										echo "<optgroup label=" . $row_unidade['empresa_nome'][0] . ">";
										$optgroup = $row_unidade['empresa_nome'][0];
										$count_g++;
									}
									echo "<option value='{$row_unidade['empresa_nome']}'{$selected}>{$row_unidade['empresa_nome']}</option>";
								}
								?>
							</select>
						</div>
					<?php endif; ?>

					<?php if ($administracao == 1 || $supervisor_equipe_vendas == 1 || $gerente_plataformas == 1) : ?>

						<?php if ($vendas_pgto == 1) : ?>
							<div class="css_form_campo css_multisel">Banco de Débito:<br>
								<select name="vendas_debito_banco[]" multiple="multiple">
									<option value="">---- Indiferente ----</option>
									<option value="104" <?php for ($i = 0; $i < count($vendas_debito_banco); $i++) {
															if ($vendas_debito_banco[$i] == "104") {
																echo "selected";
															}
														} ?>>104 - CAIXA</option>
									<option value="237" <?php for ($i = 0; $i < count($vendas_debito_banco); $i++) {
															if ($vendas_debito_banco[$i] == "237") {
																echo "selected";
															}
														} ?>>237 - BRADESCO</option>
									<option value="041" <?php for ($i = 0; $i < count($vendas_debito_banco); $i++) {
															if ($vendas_debito_banco[$i] == "041") {
																echo "selected";
															}
														} ?>>041 - BANRISUL</option>
									<option value="341" <?php for ($i = 0; $i < count($vendas_debito_banco); $i++) {
															if ($vendas_debito_banco[$i] == "341") {
																echo "selected";
															}
														} ?>>341 - ITAÚ</option>
									<option value="399" <?php for ($i = 0; $i < count($vendas_debito_banco); $i++) {
															if ($vendas_debito_banco[$i] == "399") {
																echo "selected";
															}
														} ?>>399 - HSBC</option>
									<option value="001" <?php for ($i = 0; $i < count($vendas_debito_banco); $i++) {
															if ($vendas_debito_banco[$i] == "001") {
																echo "selected";
															}
														} ?>>001 - BANCO DO BRASIL</option>
									<option value="033" <?php for ($i = 0; $i < count($vendas_debito_banco); $i++) {
															if ($vendas_debito_banco[$i] == "033") {
																echo "selected";
															}
														} ?>>033 - SANTANDER</option>
									<option value="748" <?php for ($i = 0; $i < count($vendas_debito_banco); $i++) {
															if ($vendas_debito_banco[$i] == "748") {
																echo "selected";
															}
														} ?>>748 - SICRED</option>
									<option value="003" <?php for ($i = 0; $i < count($vendas_debito_banco); $i++) {
															if ($vendas_debito_banco[$i] == "003") {
																echo "selected";
															}
														} ?>>003 - BANCO DA AMAZÔNIA</option>
								</select>
							</div>
						<?php endif; ?>

						<div class="css_form_campo css_multisel">Consultor:<br>
							<select name="vendas_consultor[]" multiple="multiple">
								<option value="">---- Indiferente ----</option>
								<?php
								$optgroup = "";
								$count_g = 0;
								if ($_GET['vendas_consultor']) {
									$vendas_consultor = $_GET['vendas_consultor'];
								}
								$result_user_form = mysql_query("SELECT DISTINCT vendas_consultor, name FROM sys_vendas_seguros 
				INNER JOIN jos_users ON sys_vendas_seguros.vendas_consultor = jos_users.id 
				INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
				LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf) 
				WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" . $filtros_sql . $select_vendas_user . " ORDER BY name;")
									or die(mysql_error());
								while ($row_user_form = mysql_fetch_array($result_user_form)) {
									$selected_consultor = "";
									for ($i = 0; $i < count($vendas_consultor); $i++) {
										if ($vendas_consultor[$i] == $row_user_form["vendas_consultor"]) {
											$selected_consultor = " selected";
										}
									}
									if ($optgroup != $row_user_form['name'][0]) {
										if ($count_g > 0) {
											echo "</optgroup>";
										}
										echo "<optgroup label=" . $row_user_form['name'][0] . ">";
										$optgroup = $row_user_form['name'][0];
										$count_g++;
									}
									echo "<option value='{$row_user_form['vendas_consultor']}'{$selected_consultor}>{$row_user_form['name']}</option>";
								}
								?>
							</select>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div align="left">
			<?php
			$links_filtros = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=476&vendas_id=" . $vendas_id . "
	&nome=" . $nome . "
	&prec=" . $prec . "
	&cpf=" . $cpf . "
	&vendas_mes=" . $vendas_mes . "
	&contar=" . $_GET['contar'] . "
	&num_vendas=" . $_GET['num_vendas'] . "
	&consultor_unidade=" . $pag_unidade . "
	&vendas_consultor=" . $pag_consultor . "
	&vendas_status=" . $pag_status . "
	&vendas_debito_banco=" . $pag_debito_banco . "
	&cliente_uf=" . $pag_cliente_uf . "
	&cliente_empregador=" . $cliente_empregador . "
	&vendas_promotora=" . $vendas_promotora . "
	&vendas_banco=" . $vendas_banco . "
	&vendas_pgto=" . $vendas_pgto . "
	&vendas_pago_vendedor=" . $vendas_pago_vendedor . "
	&vendas_vendedor=" . $vendas_vendedor . "
	&vendas_cartao_validade_ano=" . $_GET['vendas_cartao_validade_ano'] . "
	&vendas_cartao_validade_mes_ini=" . $_GET['vendas_cartao_validade_mes_ini'] . "
	&vendas_cartao_validade_mes_fim=" . $_GET['vendas_cartao_validade_mes_fim'] . "
	&vendas_apolice=" . $pag_apolice . "
	&dp-normal-1=" . $pag_data_ini . "
	&dp-normal-2=" . $pag_data_fim . "
	&dp-normal-3=" . $pag_data_aud_ini . "
	&dp-normal-4=" . $pag_data_aud_fim . "
	&filtro_data1=" . $_GET['filtro_data1'] . "
	&qnt=" . $qnt; ?>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
				<tbody>
					<tr class="cabecalho">
						<div align="left" class="style8">
							<td width="3%"><span style="color:#cccccc; font-size:8pt">#</span></td>
							<td width="25%">
								<span style="color:#fff;">Cliente:</span><br>
								<span style="color:#cccccc; font-size:8pt">CPF:<?php if ($contagem) {
																					echo "<a class='style8' href='" . $links_filtros . "&ordemi=contagem&ordenacao=" . $link_ordem . "&p=" . $pagina . "' target='_self'> | Nº de Vendas:</a> ";
																					if ($ordem == 'contagem') {
																						echo $img_ordem;
																					}
																				} ?></span>
							</td>
							<td width="12%">
								<span style="color:#fff;">Apólice:</span><br>
								<span style="color:#cccccc; font-size:8pt">Dia Vencimento:</span>
							</td>
							<td width="21%">
								<span style="color:#fff;">Consultor:</span><br>
								<span style="color:#cccccc; font-size:8pt">Data da venda:</span>
							</td>
							<td width="15%">
								<span style="color:#fff;">Status:</span><br>
							</td>
							<td width="6%">
								<img src="sistema/imagens/config.png"></br>
								<span style="color:#fff;">Código:</span><br>
							</td>
						</div>
					</tr>
					<tr>
						<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
							<tbody>
								<?php
								$totalclientes = 0;
								$exibindo = 1;
								$numero = $exibindo;
								include("sistema/vendas/exibe_lista_seguro.php");
								$exibindo = $exibindo  - 1;

								if (($diretoria == 1) || ($financeiro == 1)) {
									if (($vendas_mes) && ($pag_status == "&vendas_status[]=8&vendas_status[]=9")) {
										$sum_comissao = ", SUM(vendas_comissao_vendedor) AS total_comissao ";
									} else {
										$sum_comissao = " ";
									}
								}
								echo "<tr class='even'><div align='left'>";
								echo "<td colspan='7'>Resultados totais de todos os resultados da Pesquisa:</br><div align='center'>";
								echo "<table width='85%'>";

								// TOTAIS
								$sql_select_total = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_recebido_agenc) AS total_recebido_agenc, 
SUM(vendas_recebido_prolabore) AS total_recebido_prolabore, 
SUM(vendas_comissao_vendedor) AS total_cms_vendedor 
FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)" . $join_unidade . " 
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" .
									$select_nome .
									$select_empregador .
									$filtros_sql .
									$select_consultor .
									$select_vendas_user . ";")
									or die(mysql_error());
								$row_total_valor = mysql_fetch_array($sql_select_total);
								$total_valor = ($row_total_valor['total_valor'] > 0) ? number_format($row_total_valor['total_valor'], 2, ',', '.') : '0';
								echo "<tr>";
								echo "<td><strong>Totais:</strong></td>";
								echo "</tr>";
								echo "<tr>";
								echo "<td>";

								if (!$cobranca_seguros) {
									echo "Valores das Apólices: <strong>R$ " . $total_valor . "</strong></br><hr>";
								}

								if ($sup_operacional_seg == 1) {
									$total_recebido = $row_total_valor['total_recebido_agenc'] + $row_total_valor['total_recebido_prolabore'];
									$total_receita = $total_recebido - $total_cms_vendedor;

									$total_recebido_agenc = ($row_total_valor['total_recebido_agenc'] > 0) ? number_format($row_total_valor['total_recebido_agenc'], 2, ',', '.') : '0';
									$total_recebido_prolabore = ($row_total_valor['total_recebido_prolabore'] > 0) ? number_format($row_total_valor['total_recebido_prolabore'], 2, ',', '.') : '0';
									$total_recebido = ($total_recebido > 0) ? number_format($total_recebido, 2, ',', '.') : '0';
									$total_cms_vendedor = ($total_cms_vendedor > 0) ? number_format($total_cms_vendedor, 2, ',', '.') : '0';
									$total_receita = ($total_receita > 0) ? number_format($total_receita, 2, ',', '.') : '0';
									echo "Total de Agenciamento: <strong>R$ " . $total_recebido_agenc . "</strong></br>";
									echo "Total de Prolabore: <strong>R$ " . $total_recebido_prolabore . "</strong></br>";
									echo "Total Recebido: <strong><span style='color:blue;'>R$ " . $total_recebido . "</span></strong></br><hr>";
									echo "% Vendedor: <strong><span style='color:red;'>R$ " . $total_cms_vendedor . "</span></strong></br><hr>";
									echo "Receita: <strong><span style='color:green;'>R$ " . $total_receita . "</span></strong></br><hr>";
								}
								echo "</div>";
								echo "</td>";
								echo "</tr>";
								echo "</table>";

								echo "<tr class='even'><div align='left'>";
								echo "<td colspan='7'><div align='center'>";
								echo "<table>";
								echo "<tr>";
								$sql_select_all = mysql_query("SELECT COUNT(vendas_id) AS total FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)" . $join_unidade . " 
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" .
									$select_nome .
									$select_empregador .
									$filtros_sql .
									$select_consultor .
									$select_vendas_user . ";")
									or die(mysql_error());
								$row_total_registros = mysql_fetch_array($sql_select_all);
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
	<?php mysql_close($con); ?>
<?php endif; ?>

<?php

function retornaSelectVendasConsultor()
{
	if ($_GET["vendas_consultor"]) {
		$vendas_consultor = $_GET["vendas_consultor"];
		for ($i = 0; $i < count($vendas_consultor); $i++) {
			if ($vendas_consultor[$i] != "") {
				if ($i == 0) {
					$select_consultor = " AND (vendas_consultor = '" . $vendas_consultor[$i] . "'";
				} else {
					$select_consultor = $select_consultor . " OR vendas_consultor = '" . $vendas_consultor[$i] . "'";
				}
			}
			$aux_consultor = $i;
		}
		if ($vendas_consultor[$aux_consultor] != "") {
			$select_consultor = $select_consultor . ")";
		}

		return $select_consultor;
	} else {
		return "";
	}
}

?>