<?php
$_GET["cpf"] ? $cpf = $_GET["cpf"] : $cpf = "";

if ($_GET["vendas_status"]){
$vendas_status=$_GET["vendas_status"];
				for ($i=0;$i<count($vendas_status);$i++){
					if ($vendas_status[$i] != ""){
						if ($i==0){
							$select_status = " AND (vendas_status = '" . $vendas_status[$i] . "'";
						}else{$select_status = $select_status." OR vendas_status = '" . $vendas_status[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($vendas_status[$aux_stat] != ""){$select_status = $select_status.")";}
				for ($i=0;$i<count($vendas_status);$i++){
					if ($vendas_status[$i] != ""){
							$pag_status = $pag_status."&vendas_status[]=".$vendas_status[$i];					
					}
				}
}

if ($_GET["vendas_tipo_contrato"]){
$vendas_tipo_contrato=$_GET["vendas_tipo_contrato"];
				for ($i=0;$i<count($vendas_tipo_contrato);$i++){
					if ($vendas_tipo_contrato[$i] != ""){
						if ($i==0){
							$select_tipo = " AND (vendas_tipo_contrato = '" . $vendas_tipo_contrato[$i] . "'";
						}else{$select_tipo = $select_tipo." OR vendas_tipo_contrato = '" . $vendas_tipo_contrato[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($vendas_tipo_contrato[$aux_stat] != ""){$select_tipo = $select_tipo.")";}
				for ($i=0;$i<count($vendas_tipo_contrato);$i++){
					if ($vendas_tipo_contrato[$i] != ""){
							$pag_tipo = $pag_tipo."&vendas_tipo_contrato[]=".$vendas_tipo_contrato[$i];					
					}
				}
}

if ($_GET["filtro_data1"]) {$filtro_data1 = $_GET["filtro_data1"];}else{$filtro_data1 = "1";}
if ($_GET["filtro_data2"]) {$filtro_data2 = $_GET["filtro_data2"];}else{$filtro_data2 = "2";}
if ($filtro_data1 == "1") {$normal_3_4 = "vendas_dia_imp"; $normal_3_4_hr_ini = "'"; $normal_3_4_hr_fim = "'";}
if ($filtro_data1 == "2") {$normal_3_4 = "vendas_dia_pago"; $normal_3_4_hr_ini = "'"; $normal_3_4_hr_fim = "'";}
if ($filtro_data1 == "3") {$normal_3_4 = "vendas_dia_venda"; $normal_3_4_hr_ini = " 00:00:00'"; $normal_3_4_hr_fim = " 23:59:59'";}
if ($filtro_data2 == "1") {$normal_5_6 = "vendas_dia_imp"; $normal_5_6_hr_ini = "'"; $normal_5_6_hr_fim = "'";}
if ($filtro_data2 == "2") {$normal_5_6 = "vendas_dia_pago"; $normal_5_6_hr_ini = "'"; $normal_5_6_hr_fim = "'";}
if ($filtro_data2 == "3") {$normal_5_6 = "vendas_dia_venda"; $normal_5_6_hr_ini = " 00:00:00'"; $normal_5_6_hr_fim = " 23:59:59'";}

if ($_GET["dp-normal-5"]){
$pag_data_ini = $_GET["dp-normal-5"];
$data_ini = implode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "-" : "/", $_GET["dp-normal-5"])));
$select_data_ini= " AND " . $normal_5_6 . " >= '" . $data_ini . $normal_5_6_hr_ini;
} else {$select_data_ini = "";}

if ($_GET["dp-normal-6"]){
$pag_data_fim = $_GET["dp-normal-6"];
$data_fim = implode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "-" : "/", $_GET["dp-normal-6"])));
$select_data_fim= " AND " . $normal_5_6 . " <= '" . $data_fim . $normal_5_6_hr_fim;
} else {$select_data_fim="";}

if ($_GET["dp-normal-3"]){
$pag_data_imp_ini = $_GET["dp-normal-3"];
$data_imp_ini = implode(preg_match("~\/~", $_GET["dp-normal-3"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-3"]) == 0 ? "-" : "/", $_GET["dp-normal-3"])));
$select_data_imp_ini= " AND " . $normal_3_4 . " >= '" . $data_imp_ini . $normal_3_4_hr_ini;
} else {$select_data_imp_ini = "";}

if ($_GET["dp-normal-4"]){
$pag_data_imp_fim = $_GET["dp-normal-4"];
$data_imp_fim = implode(preg_match("~\/~", $_GET["dp-normal-4"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-4"]) == 0 ? "-" : "/", $_GET["dp-normal-4"])));
$select_data_imp_fim= " AND " . $normal_3_4 . " <= '" . $data_imp_fim . $normal_3_4_hr_fim;
} else {$select_data_imp_fim="";}

//$vendas_banco=$_GET["vendas_banco"];
//if ($_GET["vendas_banco"]) {$select_bank= " AND vendas_banco like '" . $vendas_banco . "'";} else {$select_bank="";}
$vendas_banco="";
	if ($_GET["vendas_banco"])
	{
		$select_bank = implode("','", $_GET["vendas_banco"]);
		$select_bank != "" ? $select_bank = " AND vendas_banco IN ('".$select_bank."')" : $select_bank="";
		

		for ($i=0;$i<count($_GET["vendas_banco"]);$i++){
			if ($_GET["vendas_banco"][$i] != ""){
					$vendas_banco = $vendas_banco."&vendas_banco[]=".$_GET["vendas_banco"][$i];					
			}
		}
		
	} else 
	{
		$select_bank="";
	}

$vendas_orgao=$_GET["vendas_orgao"];
if ($_GET["vendas_orgao"]) {$select_orgao= " AND vendas_orgao like '%" . $vendas_orgao . "%'";} else {$select_orgao="";}

$vendas_promotora=$_GET["vendas_promotora"];
if ($_GET["vendas_promotora"]) {$select_promotora= " AND vendas_promotora like '%" . $vendas_promotora . "%'";} else {$select_promotora="";}

$vendas_id=$_GET["vendas_id"];
if ($_GET["vendas_id"]) {$select_id= " AND vendas_id = '" . $vendas_id . "'";} else {$select_id="";}

$vendas_proposta=$_GET["vendas_proposta"];
if ($_GET["vendas_proposta"]) {$select_proposta= " AND vendas_proposta = '" . $vendas_proposta . "'";} else {$select_proposta="";}

$vendas_tabela=$_GET["vendas_tabela"];
if ($_GET["vendas_tabela"]) {$select_tabela= " AND vendas_tabela = '" . $vendas_tabela . "'";} else {$select_tabela="";}

$vendas_portabilidade=$_GET["vendas_portabilidade"];
if ($_GET["vendas_portabilidade"]) {$select_portabilidade= " AND vendas_portabilidade = '" . $vendas_portabilidade . "'";} else {$select_portabilidade="";}

$nome=$_GET["nome"];
if ($_GET["nome"]) {$select_nome= " AND (clients_nm like '%" . $nome . "%' OR cliente_nome like '%" . $nome . "%')";} else {$select_nome="";}

$prec=$_GET["prec"];
if ($_GET["prec"]) {$select_prec= " AND sys_clients.clients_prec_cp like '%" . $prec . "%'";} else {$select_prec="";}

$vendas_mes=$_GET["vendas_mes"];
if ($_GET["vendas_mes"]) {$select_mes= " AND vendas_mes like '%" . $vendas_mes . "%'";} else {$select_mes="";}

$vendas_turno=$_GET["vendas_turno"];
if ($_GET["vendas_turno"]) {$select_turno= " AND vendas_turno = '" . $vendas_turno . "'";} else {$select_turno="";}

$vendas_envio=$_GET["vendas_envio"];
if ($_GET["vendas_envio"]) {$select_envio= " AND vendas_envio = '" . $vendas_envio . "'";} else {$select_envio="";}

$gerente_comercial=$_GET["gerente_comercial"];
if ($_GET["gerente_comercial"]) {$select_gerente = " AND gerente_comercial = '" . $gerente_comercial . "'";} else {$select_gerente="";}

$supervisor_comercial=$_GET["supervisor_comercial"];
if ($_GET["supervisor_comercial"]) {$select_supervisor = " AND supervisor_comercial = '" . $supervisor_comercial . "'";} else {$select_supervisor="";}

if ($_GET["vendas_tipo_tabela"]) {
	$vendas_tipo_tabela=$_GET["vendas_tipo_tabela"];
	$select_tipo_tabela= " AND tabela_tipo = '" . $vendas_tipo_tabela . "'";
	$join_tabela = " INNER JOIN sys_vendas_tabelas ON sys_vendas.vendas_tabela = sys_vendas_tabelas.tabela_id";
} else {
	$select_tipo_tabela="";
	$join_tabela = "";
}

if ($_GET["Itemid"] == "505") {$select_produto= " AND vendas_produto = '2'";} else {$select_produto= " AND vendas_produto != '2'";}

$vendas_produto=$_GET["vendas_produto"];
if ($_GET["vendas_produto"]) {$select_produto= " AND vendas_produto = '" . $vendas_produto . "'";} else {$select_produto="";}

$vendas_seguro_protegido=$_GET["vendas_seguro_protegido"];
if ($_GET["vendas_seguro_protegido"]) {$select_protegido= " AND vendas_seguro_protegido = '" . $vendas_seguro_protegido . "'";} else {$select_protegido="";}

$vendas_estoque=$_GET["vendas_estoque"];
if ($_GET["vendas_estoque"]) {$select_estoque=" AND vendas_estoque = 1";} else {$select_estoque= " AND vendas_estoque = 0";}

$vendas_pago_agente=$_GET["vendas_pago_agente"];
if ($_GET["vendas_pago_agente"]) {$select_pago_agente= " AND vendas_pago_agente = '" . $vendas_pago_agente . "'";} else {$select_pago_agente="";}

if ($_GET["vendas_contrato_fisico"]){
$vendas_contrato_fisico=$_GET["vendas_contrato_fisico"];
				for ($i=0;$i<count($vendas_contrato_fisico);$i++){
					if ($vendas_contrato_fisico[$i] != ""){
						if ($i==0){
							$select_contrato = " AND (vendas_contrato_fisico = '" . $vendas_contrato_fisico[$i] . "'";
						}else{$select_contrato = $select_contrato." OR vendas_contrato_fisico = '" . $vendas_contrato_fisico[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($vendas_contrato_fisico[$aux_stat] != ""){$select_contrato = $select_contrato.")";}
				for ($i=0;$i<count($vendas_contrato_fisico);$i++){
					if ($vendas_contrato_fisico[$i] != ""){
							$pag_contrato = $pag_contrato."&vendas_contrato_fisico[]=".$vendas_contrato_fisico[$i];					
					}
				}
}

if ($_GET["vendas_mes"]){
$vendas_mes=$_GET["vendas_mes"];
				for ($i=0;$i<count($vendas_mes);$i++){
					if ($vendas_mes[$i] != ""){
						if ($i==0){
							$select_mes = " AND (vendas_mes = '" . $vendas_mes[$i] . "'";
						}else{$select_mes = $select_mes." OR vendas_mes = '" . $vendas_mes[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($vendas_mes[$aux_stat] != ""){$select_mes = $select_mes.")";}
				for ($i=0;$i<count($vendas_mes);$i++){
					if ($vendas_mes[$i] != ""){
							$pag_mes = $pag_mes."&vendas_mes[]=".$vendas_mes[$i];					
					}
				}
}

if ($_GET["consultor_unidade"]){
$consultor_unidade=$_GET["consultor_unidade"];
				for ($i=0;$i<count($consultor_unidade);$i++){
					if ($consultor_unidade[$i] != ""){
						if ($i==0){
							$select_unidade = " AND (jos_users.unidade = '" . $consultor_unidade[$i] . "'";
						}else{$select_unidade = $select_unidade." OR jos_users.unidade = '" . $consultor_unidade[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($consultor_unidade[$aux_stat] != ""){$select_unidade = $select_unidade.")";}
				for ($i=0;$i<count($consultor_unidade);$i++){
					if ($consultor_unidade[$i] != ""){
							$pag_unidade = $pag_unidade."&consultor_unidade[]=".$consultor_unidade[$i];					
					}
				}
} else {$select_unidade="";}

$vendas_consultor=$_GET["vendas_consultor"];
if ($_GET["vendas_consultor"]) {$select_consultor= " AND vendas_consultor = " . $vendas_consultor . "";} else {$select_consultor="";}

$select_rep_unidade = "";
if($representante_comercial_seguros)
{
	$result_rep_unidade = mysql_query("SELECT empresa_nome FROM sys_empresas WHERE empresa_rep_seg = ".$user_id.";") 
		or die(mysql_error());
	while($row_rep_unidade = mysql_fetch_array( $result_rep_unidade )) {
		$select_rep_unidade = $select_rep_unidade . " AND unidade LIKE '".$row_rep_unidade['empresa_nome']."'";
	}
}

if ($gerente_regional){
	$select_origem= "";
	$select_equipe = "";
	$select_consultor= "";
	$select_equipe_supervisor = "";
	$count_unidades = 0;
	$result_unidades_gerente = mysql_query("SELECT empresa_nome FROM sys_empresas WHERE empresa_rep_credito LIKE '%,".$user_id."%';") 
	or die(mysql_error());
	while($row_unidades_gerente = mysql_fetch_array( $result_unidades_gerente )){
		if ($row_unidades_gerente['empresa_nome'] != ""){
			if ($count_unidades==0){
				$select_unidade = $select_unidade." AND (jos_users.unidade = '" . $row_unidades_gerente['empresa_nome'] . "'";
			}else{
				$select_unidade = $select_unidade." OR jos_users.unidade = '" . $row_unidades_gerente['empresa_nome'] . "'";
			}
			$count_unidades++;
		}
	}
	if ($count_unidades){
		$select_unidade = $select_unidade." OR jos_users.id = " . $user_id . ")";
		$vendas_consultor=$_GET["vendas_consultor"];
		if ($_GET["vendas_consultor"]) {$select_consultor= " AND vendas_consultor = " . $vendas_consultor;}
	}else{$select_consultor= " AND vendas_consultor = " . $user_id . " AND (jos_users.nivel = 4 OR jos_users.nivel = 8)";}
}

if ($gerente_comercial_agentes){
	$select_unidade = " AND jos_users.unidade = '".$user_unidade."'";
	$select_consultor = $select_consultor." AND jos_users.gerente_comercial = '".$user_id."'";
}
if ($supervisor_comercial_agentes){
	$select_unidade = " AND jos_users.unidade = '".$user_unidade."'";
	$select_consultor = $select_consultor." AND jos_users.supervisor_comercial = '".$user_id."'";
}

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="vendas_id";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="DESC";}
if ($_GET["ordenacao"] == "ASC"){
	$link_ordem = "DESC";
	$img_ordem = "<img src='sistema/imagens/asc.png'>";
}else{
	$link_ordem = "ASC";
	$img_ordem = "<img src='sistema/imagens/desc.png'>";
}

$join_unidade= " INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id";
$select_agente = " AND (jos_users.nivel = '4' OR jos_users.nivel = '8')";

$filtros_sql = "sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$select_prec . 
$select_id . 
$select_proposta . 
$select_tabela . 
$select_portabilidade . 
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
$select_agente . 
$select_consultor . 
$select_unidade . 
$select_promotora . 
$select_mes . 
$select_contrato . 
$select_turno . 
$select_envio . 
$select_gerente . 
$select_supervisor . 
$select_tipo_tabela . 
$select_protegido . 
$select_estoque . 
$select_produto . 
$select_pago_agente.
$select_rep_unidade;
?>