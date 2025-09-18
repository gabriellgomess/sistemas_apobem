<?php
date_default_timezone_set('America/Sao_Paulo');
include("../../connect.php");
$cpf=$_GET["cpf"];
$clients_cat=$_GET["clients_cat"];
if ($_GET["p"]){$pagina=$_GET["p"];}else{$pagina="1";}

if ($_GET["filtro_data1"]) {$filtro_data1 = $_GET["filtro_data1"];}else{$filtro_data1 = "1";}
if ($_GET["filtro_data2"]) {$filtro_data2 = $_GET["filtro_data2"];}else{$filtro_data2 = "2";}
if ($filtro_data1 == "1") {$normal_3_4 = "vendas_dia_imp"; $normal_3_4_hr_ini = "'"; $normal_3_4_hr_fim = "'";}
if ($filtro_data1 == "2") {$normal_3_4 = "vendas_dia_pago"; $normal_3_4_hr_ini = "'"; $normal_3_4_hr_fim = "'";}
if ($filtro_data1 == "3") {$normal_3_4 = "vendas_dia_venda"; $normal_3_4_hr_ini = " 00:00:00'"; $normal_3_4_hr_fim = " 23:59:59'";}
if ($filtro_data1 == "4") {$normal_3_4 = "vendas_envio_data"; $normal_3_4_hr_ini = "'"; $normal_3_4_hr_fim = "'";}

if ($filtro_data2 == "1") {$normal_5_6 = "vendas_dia_imp"; $normal_5_6_hr_ini = "'"; $normal_5_6_hr_fim = "'";}
if ($filtro_data2 == "2") {$normal_5_6 = "vendas_dia_pago"; $normal_5_6_hr_ini = "'"; $normal_5_6_hr_fim = "'";}
if ($filtro_data2 == "3") {$normal_5_6 = "vendas_dia_venda"; $normal_5_6_hr_ini = " 00:00:00'"; $normal_5_6_hr_fim = " 23:59:59'";}
if ($filtro_data2 == "4") {$normal_5_6 = "vendas_envio_data"; $normal_5_6_hr_ini = "'"; $normal_5_6_hr_fim = "'";}

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

$vendas_consultor=$_GET["vendas_consultor"];

$vendas_banco=$_GET["vendas_banco"];
if ($_GET["vendas_banco"]) {$select_bank= " AND vendas_banco like '%" . $vendas_banco . "%'";} else {$select_bank="";}

$vendas_promotora=$_GET["vendas_promotora"];
if ($_GET["vendas_promotora"]) {$select_promotora= " AND vendas_promotora like '%" . $vendas_promotora . "%'";} else {$select_promotora="";}

$vendas_origem=$_GET["vendas_origem"];
if ($_GET["vendas_origem"]) {$select_origem= " AND vendas_origem = '" . $vendas_origem . "'";} else {$select_origem="";}

$vendas_id=$_GET["vendas_id"];
if ($_GET["vendas_id"]) {$select_id= " AND sys_vendas.vendas_id = '" . $vendas_id . "'";} else {$select_id="";}

$vendas_proposta=$_GET["vendas_proposta"];
if ($_GET["vendas_proposta"]) {$select_proposta= " AND vendas_proposta = '" . $vendas_proposta . "'";} else {$select_proposta="";}

$vendas_portabilidade=$_GET["vendas_portabilidade"];
if ($_GET["vendas_portabilidade"]) {$select_portabilidade= " AND vendas_portabilidade = '" . $vendas_portabilidade . "'";} else {$select_portabilidade="";}

$vendas_vendedor=$_GET["vendas_vendedor"];
if ($_GET["vendas_vendedor"]) {$select_vendedor= " AND vendas_vendedor = '" . $vendas_vendedor . "'";} else {$select_vendedor="";}

$nome=$_GET["nome"];
if ($_GET["nome"]) {$select_nome= " AND (clients_nm like '%" . $nome . "%' OR cliente_nome like '%" . $nome . "%')";} else {$select_nome="";}

$prec=$_GET["prec"];
if ($_GET["prec"]) {$select_prec= " AND sys_clients.clients_prec_cp like '%" . $prec . "%'";} else {$select_prec="";}

$vendas_turno=$_GET["vendas_turno"];
if ($_GET["vendas_turno"]) {$select_turno= " AND vendas_turno = '" . $vendas_turno . "'";} else {$select_turno="";}

$vendas_envio=$_GET["vendas_envio"];
if ($_GET["vendas_envio"]) {$select_envio= " AND vendas_envio = '" . $vendas_envio . "'";} else {$select_envio="";}

$vendas_intencionada=$_GET["vendas_intencionada"];
if ($_GET["vendas_intencionada"]) {$select_intencionada= " AND vendas_intencionada = '" . $vendas_intencionada . "'";} else {$select_intencionada="";}

$vendas_pos_venda=$_GET["vendas_pos_venda"];
if ($_GET["vendas_pos_venda"]) {$select_pos_venda= " AND vendas_pos_venda = '" . $vendas_pos_venda . "'";} else {$select_pos_venda="";}

if ($_GET["vendas_tipo_tabela"]) {
	$vendas_tipo_tabela=$_GET["vendas_tipo_tabela"];
	$select_tipo_tabela= " AND tabela_tipo = '" . $vendas_tipo_tabela . "'";
	$join_tabela = " INNER JOIN sys_vendas_tabelas ON sys_vendas.vendas_tabela = sys_vendas_tabelas.tabela_id";
} else {
	$select_tipo_tabela="";
	$join_tabela = "";
}

if ($_GET["vendas_banco_compra"]) {
	$vendas_banco_compra=$_GET["vendas_banco_compra"];
	$select_banco_compra= " AND compra_banco = '" . $vendas_banco_compra . "'";
	$join_banco_compra = " INNER JOIN sys_vendas_compras ON sys_vendas.vendas_id = sys_vendas_compras.vendas_id";
} else {
	$select_banco_compra="";
	$join_banco_compra = "";
}

$vendas_envio_objeto=$_GET["vendas_envio_objeto"];
if ($_GET["vendas_envio_objeto"] == "vazio") {$select_envio_objeto= " AND (vendas_envio_objeto IS NULL OR vendas_envio_objeto='')";}
elseif ($_GET["vendas_envio_objeto"] == "completo") {$select_envio_objeto= " AND (vendas_envio_objeto IS NOT NULL OR vendas_envio_objeto != '')";}
elseif ($_GET["vendas_envio_objeto"]) {$select_envio_objeto= " AND vendas_envio_objeto like '%".$vendas_envio_objeto."%'";} else {$select_envio_objeto="";}

if ($_GET["item_origem"] == "499") {$select_produto= " AND (vendas_produto = '2' OR vendas_produto = '3')";} else {$select_produto= " AND vendas_produto != '2' AND vendas_produto != '3'";}

$vendas_seguro_protegido=$_GET["vendas_seguro_protegido"];
if ($_GET["vendas_seguro_protegido"]) {$select_protegido= " AND vendas_seguro_protegido = '" . $vendas_seguro_protegido . "'";} else {$select_protegido="";}

$vendas_estoque=$_GET["vendas_estoque"];
if ($_GET["vendas_estoque"]) {$select_estoque=" AND vendas_estoque = 1";} else {$select_estoque= " AND vendas_estoque = 0";}

if ($_GET["vendas_contrato_fisico"])
{
	$vendas_contrato_fisico=$_GET["vendas_contrato_fisico"];
	for ($i=0;$i<count($vendas_contrato_fisico);$i++)
	{
		if ($vendas_contrato_fisico[$i] != "")
		{
			if ($i==0)
			{
				$select_contrato = " AND (vendas_contrato_fisico = '" . $vendas_contrato_fisico[$i] . "'";
			}else{
				$select_contrato = $select_contrato." OR vendas_contrato_fisico = '" . $vendas_contrato_fisico[$i] . "'";
			}
			$pag_contrato = $pag_contrato."&vendas_contrato_fisico[]=".$vendas_contrato_fisico[$i];
		}
		$aux_stat = $i;				
	}
	if ($vendas_contrato_fisico[$aux_stat] != "")
	{
		$select_contrato = $select_contrato.")";
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

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="sys_vendas.vendas_id";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="DESC";}
if ($_GET["ordenacao"] == "ASC"){
	$link_ordem = "DESC";
	$img_ordem = "<img src='sistema/imagens/asc.png'>";
}else{
	$link_ordem = "ASC";
	$img_ordem = "<img src='sistema/imagens/desc.png'>";
}

if ($_GET["contar"]) {
	$contagem = ", COUNT(sys_vendas.clients_cpf) AS contagem"; 
	$agrupamento=" GROUP BY sys_vendas.clients_cpf ";
	if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="contagem";}	
}else{
	$agrupamento="";
}

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

if ($_GET["vendas_orgao"]){
$vendas_orgao=$_GET["vendas_orgao"];
				for ($i=0;$i<count($vendas_orgao);$i++){
					if ($vendas_orgao[$i] != ""){
						if ($i==0){
							$select_orgao = " AND (vendas_orgao like '%" . $vendas_orgao[$i] . "%'";
						}else{$select_orgao = $select_orgao." OR vendas_orgao like '%" . $vendas_orgao[$i] . "%'";}					
					}
					$aux_stat = $i;
				}
				if ($vendas_orgao[$aux_stat] != ""){$select_orgao = $select_orgao.")";}
				for ($i=0;$i<count($vendas_orgao);$i++){
					if ($vendas_orgao[$i] != ""){
							$pag_orgao = $pag_orgao."&vendas_orgao[]=".$vendas_orgao[$i];					
					}
				}
}

$join_unidade= " INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id";
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

if ($_GET["cliente_carteira"]){
	$cliente_carteira=$_GET["cliente_carteira"];
				for ($i=0;$i<count($cliente_carteira);$i++){
					if ($cliente_carteira[$i] != ""){
						if ($i==0){
							$select_carteira = " AND (sys_inss_clientes.cliente_campanha = '" . $cliente_carteira[$i] . "'";
						}else{$select_carteira = $select_carteira." OR sys_inss_clientes.cliente_campanha = '" . $cliente_carteira[$i] . "'";}					
						$pag_carteira = $pag_carteira."&cliente_carteira[]=".$cliente_carteira[$i];
					}
					$aux_stat = $i;
				}
				if ($cliente_carteira[$aux_stat] != ""){$select_carteira = $select_carteira.")";}				
} else {$select_carteira="";}

if ($_GET["equipe_id"])
{
	$equipe_id=$_GET["equipe_id"];
	$select_equipe= " AND jos_users.equipe_id = '" . $equipe_id . "'";

	for ($i=0;$i<count($equipe_id);$i++){
		if ($equipe_id[$i] != ""){
			if ($i==0){
				$select_equipe = " AND (jos_users.equipe_id = '" . $equipe_id[$i] . "'";
			}else{$select_equipe = $select_equipe." OR jos_users.equipe_id = '" . $equipe_id[$i] . "'";}			
		}
		$aux_stat = $i;
	}
	if ($equipe_id[$aux_stat] != ""){$select_equipe = $select_equipe.")";}
} else {
	$select_equipe="";
}

if ($_GET["vendas_cartao_consig"])
	{
		$vendas_cartao_consig = $_GET["vendas_cartao_consig"];		
		$select_vendas_cartao_consig = " AND sys_vendas.vendas_cartao_consig IN (".implode(', ', $vendas_cartao_consig).")";
	}

$vendas_consultor=$_GET["vendas_consultor"];
if ($_GET["vendas_consultor"]) {$select_consultor= " AND vendas_consultor = " . $vendas_consultor . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";} else {$select_consultor=" AND jos_users.nivel <> 4 AND jos_users.nivel <> 8";}


$p = $_GET["p"];
if(isset($p)) {
$p = $p;
} else {
$p = 1;
}
if ($_GET["qnt"]){$qnt = $_GET["qnt"];}else{$qnt = 20;}
$inicio = ($p*$qnt) - $qnt;
$filtros_sql = $select_prec . 
$select_nome . 
$select_id . 
$select_proposta . 
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
$select_empresa . 
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
$select_produto . 
$select_turno.
$select_vendas_cartao_consig;

$result = mysql_query("SELECT *, vendas_bancos_nome AS cartao_consig_banco,sys_vendas_clientes.cliente_telefone AS fone1, sys_vendas_clientes.cliente_celular AS fone2  " . $contagem . " FROM sys_vendas 
INNER JOIN sys_vendas_bancos ON sys_vendas.vendas_cartao_consig = sys_vendas_bancos.vendas_bancos_id
LEFT JOIN sys_vendas_clientes ON sys_vendas.vendas_id = sys_vendas_clientes.vendas_id
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade.$join_tabela.$join_banco_compra." 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$filtros_sql . 
$agrupamento." ORDER BY " . $ordem . " " . $ordenacao . " LIMIT 0, 5000;") 
or die(mysql_error());
$agora = date("Ymd_His");
$nome_arquivo = "RelatorioPortal_".$agora;

// Determina que o arquivo é uma planilha do Excel
header("Content-type: application/vnd.ms-excel");   

// Força o download do arquivo
header("Content-type: application/force-download");  

// Seta o nome do arquivo
header("Content-Disposition: attachment; filename=".$nome_arquivo.".xls");

header("Pragma: no-cache");
// Imprime o conteúdo da nossa tabela no arquivo que será gerado

?>

 <?php  $curURL = $_SERVER["REQUEST_URI"]; ?>
	    <div align="left">
	      
	  <table border="2" align="center" cellpadding="0" cellspacing="1">
            <tbody>
		<tr><td colspan="9"><span style="color:#ff0000;"><strong>MAXIMO DE 5000 RESULTADOS!!!</strong></span></td></tr>
		<tr>
			<div align="left">
			<td>NOME DO CLIENTE:</td>
			<td>CPF DO CLIENTE:</td>
			<?php if ($contagem): ?><td>QT:</td><?php endif; ?>
			<td>TELEFONE DA VENDA:</td>
			<td>2o TELEFONE DA VENDA:</td>
			<td>MATRICULA:</td>
			<td>MATRICULA INSTITUIDOR:</td>
			<td>VALOR:</td>
			<td>VENDEDOR:</td>
			<td>DATA DA VENDA:</td>
			<td>STATUS:</td>
			<td>CODIGO:</td>
			<td> </td>
			<td>No DA PROPOSTA:</td>
			<td>No DA PORTABILIDADE:</td>
			<td>RG:</td>
			<td>EXP:</td>
			<td>DATA:</td>
			<td>NASCIMENTO:</td>
			<td>BANCO:</td>
			<td>AG:</td>
			<td>CONTA:</td>
			<td>EMPREGADOR:</td>
			<td>ORGAO:</td>
			<td>ESTADO:</td>
			<td>CEP:</td>
			<td>CIDADE:</td>
			<td>BAIRRO:</td>
			<td>ENDERECO:</td>
			<td>CARTAO CONSIGNADO</td>
            </div>
		</tr>
		  	      <?php
$totalclientes = 0;
$exibindo = 1;
$numero = $exibindo;

while($row = mysql_fetch_array( $result )) {
$endereco_link = "#";

$result_user = mysql_query("SELECT name FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );

$yr=strval(substr($row["vendas_dia_venda"],0,4));
$mo=strval(substr($row["vendas_dia_venda"],5,2));
$da=strval(substr($row["vendas_dia_venda"],8,2));
$hr=strval(substr($row["vendas_dia_venda"],11,2));
$mi=strval(substr($row["vendas_dia_venda"],14,2));
$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));

$result_status = mysql_query("SELECT status_nm FROM sys_vendas_status WHERE status_id = " . $row['vendas_status'] . ";")
or die(mysql_error());
$row_status = mysql_fetch_array( $result_status );

if ($row["vendas_orgao"] == "Exercito"){
	$nome = $row['clients_nm'];
	$cpf = $row['clients_cpf'];
	$matricula = $row['clients_matricula'];
}
else{
	if ($row['cliente_nome']){
		$nome = $row['cliente_nome'];
		$cpf = $row['cliente_cpf'];
		$matricula = $row['cliente_beneficio'];
	}else{
		$nome = $row['clients_nm'];
		$cpf = $row['clients_cpf'];
		$matricula = $row['clients_matricula'];
	}
}

	echo "<tr><td>".$nome."</td>";
	echo "<td>".$cpf."</td>";
	if ($contagem){
		$link_num = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=440&nome=".$nome."&prec=".$prec."&cpf=".$row['cliente_cpf'].$pag_mes."&consultor_unidade=".$pag_unidade."&vendas_consultor=".$vendas_consultor."&vendas_vendedor=".$vendas_vendedor."&vendas_status=".$pag_status."&vendas_contrato_fisico=".$pag_contrato."&vendas_promotora=".$vendas_promotora."&vendas_banco=".$vendas_banco."&vendas_orgao=".$vendas_orgao."&vendas_tipo_contrato=".$vendas_tipo_contrato."&vendas_seguro_protegido=".$vendas_seguro_protegido."&dp-normal-3=".$pag_data_imp_ini."&dp-normal-4=".$pag_data_imp_fim;
		echo "<td>".$row['contagem']."</td>";
	}
	echo "<td>".$row['fone1']."</td>";
	echo "<td>".$row['fone2']."</td>";
	echo "<td>".$row['cliente_beneficio']."</td>";
	echo "<td>".$row['cliente_pagamento']."</td>";
	$vendas_valor = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0' ;
	echo "<td>R$ ".$vendas_valor."</td>";
	echo "<td>".$row_user['name']."<br />";
	echo "<td>".$data_venda."</td>"; 
	echo "<td>".$row_status['status_nm']."</td>"; 
	echo "<td><div align='right'><strong>{$row['vendas_id']}</strong></div></td>";
	echo "<td> </td>";
	echo "<td>".$row['vendas_proposta']."</td>";
	echo "<td>".$row['vendas_portabilidade']."</td>";
	echo "<td>".$row['cliente_rg']."</td>";
	echo "<td>".$row['cliente_rg_exp']."</td>";
	echo "<td>".$row['cliente_rg_dt']."</td>";
	echo "<td>".$row['cliente_nascimento']."</td>";
	echo "<td>".$row['cliente_banco']."</td>";
	echo "<td>".$row['cliente_agencia']."</td>";
	echo "<td>".$row['cliente_conta']."</td>";
	echo "<td>".$row['cliente_empregador']."</td>";
	echo "<td>".$row['cliente_orgao']."</td>";
	echo "<td>".$row['cliente_uf']."</td>";
	echo "<td>".$row['cliente_cep']."</td>";
	echo "<td>".$row['cliente_cidade']."</td>";
	echo "<td>".$row['cliente_bairro']."</td>";
	echo "<td>".$row['cliente_endereco']."</td>";	
	echo "<td>".$row['cartao_consig_banco']."</td>";	
	echo "</tr>"; 
$exibindo = $exibindo + 1;
$numero = $numero + 1;
}

$exibindo = $exibindo  - 1;

	echo "<tr><div align='left'>";
	echo "<td colspan='9'>Resultados totais de todos os resultados da Pesquisa:</br><div align='center'>";
	echo "<table>";	

// TOTAIS
$sql_select_total = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor 
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade.$join_tabela.$join_banco_compra." 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" .  
$filtros_sql . ";")
or die(mysql_error());
$row_total_valor = mysql_fetch_array( $sql_select_total );
$total_valor = ($row_total_valor['total_valor']>0) ? number_format($row_total_valor['total_valor'], 2, ',', '.') : '0' ;
	echo "<tr>";
	echo "<td><strong>Valores Totais: R$ ".$total_valor."</strong></td>";
	echo "</tr>";		
	echo "</table>";	
	
	echo "<tr><div align='left'>";
	echo "<td colspan='9'><div align='center'>";
	echo "<table>";
	echo "<tr>";
$sql_select_all = mysql_query("SELECT COUNT(*) AS total FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade.$join_tabela.$join_banco_compra." WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$filtros_sql . ";")
or die(mysql_error());
$row_total_registros = mysql_fetch_array( $sql_select_all );
$total_registros = $row_total_registros["total"];
?>
</tbody>
          </table>
            </tbody>
          </table>
    </table>
<div align="center">Total de <?php echo $exibindo;?> vendas selecionadas.</div>
  </div>
</form>
<?php mysql_close($con); ?>