<?php
date_default_timezone_set('America/Sao_Paulo');
include("../../connect.php");

$cpf=$_GET["cpf"];
$clients_cat=$_GET["clients_cat"];
if ($_GET["p"]){$pagina=$_GET["p"];}else{$pagina="1";}
//$vendas_status=$_GET["vendas_status"];
//if ($_GET["vendas_status"]) {$select_status= " AND vendas_status = '" . $vendas_status . "'";} else {$select_status="";}

if($_GET['apolice_tipo'])
{
	$select_apolice_tipo=" AND apolice_tipo = '".$_GET['apolice_tipo']."'";
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

if ($_GET["vendas_debito_banco"]){
$vendas_debito_banco=$_GET["vendas_debito_banco"];
				for ($i=0;$i<count($vendas_debito_banco);$i++){
					if ($vendas_debito_banco[$i] != ""){
						if ($i==0){
							$select_debito_banco = " AND (vendas_debito_banco = '" . $vendas_debito_banco[$i] . "'";
						}else{$select_debito_banco = $select_debito_banco." OR vendas_debito_banco = '" . $vendas_debito_banco[$i] . "'";}					
					}
					$aux_banco = $i;
				}
				if ($vendas_debito_banco[$aux_banco] != ""){$select_debito_banco = $select_debito_banco.")";}
				for ($i=0;$i<count($vendas_debito_banco);$i++){
					if ($vendas_debito_banco[$i] != ""){
							$pag_debito_banco = $pag_debito_banco."&vendas_debito_banco[]=".$vendas_debito_banco[$i];					
					}
				}
}

if ($_GET["cliente_uf"]){
$cliente_uf=$_GET["cliente_uf"];
				for ($i=0;$i<count($cliente_uf);$i++){
					if ($cliente_uf[$i] != ""){
						if ($i==0){
							$select_cliente_uf = " AND (cliente_uf = '" . $cliente_uf[$i] . "'";
						}else{$select_cliente_uf = $select_cliente_uf." OR cliente_uf = '" . $cliente_uf[$i] . "'";}					
					}
					$aux_banco = $i;
				}
				if ($cliente_uf[$aux_banco] != ""){$select_cliente_uf = $select_cliente_uf.")";}
				for ($i=0;$i<count($cliente_uf);$i++){
					if ($cliente_uf[$i] != ""){
							$pag_cliente_uf = $pag_cliente_uf."&cliente_uf[]=".$cliente_uf[$i];					
					}
				}
}

if ($_GET["vendas_apolice"]){
$vendas_apolice=$_GET["vendas_apolice"];
				for ($i=0;$i<count($vendas_apolice);$i++){
					if ($vendas_apolice[$i] != ""){
						if ($i==0){
							$select_apolice = " AND (vendas_apolice = '" . $vendas_apolice[$i] . "'";
						}else{$select_apolice = $select_apolice." OR vendas_apolice = '" . $vendas_apolice[$i] . "'";}					
					}
					$aux_apolice = $i;
				}
				if ($vendas_apolice[$aux_apolice] != ""){$select_apolice = $select_apolice.")";}
				for ($i=0;$i<count($vendas_apolice);$i++){
					if ($vendas_apolice[$i] != ""){
							$pag_apolice = $pag_apolice."&vendas_apolice[]=".$vendas_apolice[$i];					
					}
				}
}

if ($_GET["data_intencionamento"]) {
	$vendas_dia_intencionamento= dataBR_to_dataDB($_GET["data_intencionamento"]);
	$filtros_sql = $filtros_sql." AND vendas_dia_intencionamento like '%" . $vendas_dia_intencionamento . "%'";
}

if ($_GET["filtro_data1"]) {$filtro_data1 = $_GET["filtro_data1"];}else{$filtro_data1 = "1";}
if ($filtro_data1 == "1") {$normal_1_2 = "vendas_dia_venda"; $normal_1_2_hr_ini = " 00:00:00'"; $normal_1_2_hr_fim = " 23:59:59'";}
if ($filtro_data1 == "2") {$normal_1_2 = "vendas_dia_ativacao"; $normal_1_2_hr_ini = "'"; $normal_1_2_hr_fim = "'";}
if ($filtro_data1 == "3") {
	if ($_GET["dp-normal-1"]){
		$pag_data_ini = $_GET["dp-normal-1"];
		$data_ini_mes = substr($pag_data_ini, 3, 2);
		$data_ini_ano = substr($pag_data_ini, 6, 4);
		$filtros_sql= $filtros_sql." AND vendas_cartao_validade_mes >= '" . $data_ini_mes . "'";
		$filtros_sql= $filtros_sql." AND vendas_cartao_validade_ano >= '" . $data_ini_ano . "'";
	}

	if ($_GET["dp-normal-2"]){
		$pag_data_fim = $_GET["dp-normal-2"];
		$data_fim_mes = substr($pag_data_fim, 3, 2);
		$data_fim_ano = substr($pag_data_fim, 6, 4);
		$filtros_sql= $filtros_sql." AND vendas_cartao_validade_mes <= '" . $data_fim_mes . "'";
		$filtros_sql= $filtros_sql." AND vendas_cartao_validade_ano <= '" . $data_fim_ano . "'";
	}
}
if ($filtro_data1 == "4") {
	if ($_GET["dp-normal-1"]) {
		$pag_data_ini = $_GET["dp-normal-1"];
		$data_inicial_intencionamento = dataBR_to_dataDB($_GET["dp-normal-1"]);
	} else {
		//se não houver data inicial é igual a data de hoje
		$data_inicial_intencionamento = date("Y-m-d");
	}
	if ($_GET["dp-normal-2"]){
		$pag_data_fim = $_GET["dp-normal-2"];
		$data_final_intencionamento = dataBR_to_dataDB($_GET["dp-normal-2"]);
	} else {
		//se não houver data final é igual a inicial + um dia
		$data_final_intencionamento = date('Y-m-d', strtotime("+1 day", strtotime($data_inicial_intencionamento)));
	}

	$filtros_sql = $filtros_sql." AND vendas_dia_intencionamento >= '" . $data_inicial_intencionamento ."' AND vendas_dia_intencionamento <= '" . $data_final_intencionamento ."'";
}
else{
	if ($_GET["dp-normal-1"]){
		$pag_data_ini = $_GET["dp-normal-1"];
		$data_ini = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));
		$filtros_sql= $filtros_sql." AND ". $normal_1_2 ." >= '" . $data_ini . $normal_1_2_hr_ini;
	}

	if ($_GET["dp-normal-2"]){
		$pag_data_fim = $_GET["dp-normal-2"];
		$data_fim = implode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "-" : "/", $_GET["dp-normal-2"])));
		$filtros_sql= $filtros_sql." AND ". $normal_1_2 ." <= '" . $data_fim . $normal_1_2_hr_fim;
	}
}

$vendas_banco=$_GET["vendas_banco"];
if ($_GET["vendas_banco"]) {$select_bank= " AND vendas_banco like '%" . $vendas_banco . "%'";} else {$select_bank="";}

$vendas_proposta=$_GET["vendas_proposta"];
if ($_GET["vendas_proposta"]) {$select_proposta= " AND vendas_proposta like '%" . $vendas_proposta . "%'";} else {$select_proposta="";}

$vendas_id=$_GET["vendas_id"];
if ($_GET["vendas_id"]) {$select_id= " AND vendas_id = '" . $vendas_id . "'";} else {$select_id="";}

$nome=$_GET["nome"];
if ($_GET["nome"]) {$select_nome= " AND (clients_nm like '%" . $nome . "%' OR cliente_nome like '%" . $nome . "%')";} else {$select_nome="";}
if ($_GET["nome"] == "VAZIO!") {$select_nome= " AND (clients_nm is null AND cliente_nome is null)";}

$cliente_matricula=$_GET["cliente_matricula"];
if ($_GET["cliente_matricula"]) {$select_matricula= " AND (clients_prec_cp like '%" . $cliente_matricula . "%' OR cliente_beneficio like '%" . $cliente_matricula . "%')";} else {$select_matricula="";}

$cliente_empregador=$_GET["cliente_empregador"];
if ($_GET["cliente_empregador"]) {$select_empregador= " AND cliente_empregador = '" . $cliente_empregador . "'";} else {$select_empregador="";}

$vendas_turno=$_GET["vendas_turno"];
if ($_GET["vendas_turno"]) {$select_turno= " AND vendas_turno = '" . $vendas_turno . "'";} else {$select_turno="";}

$vendas_pgto=$_GET["vendas_pgto"];
if ($_GET["vendas_pgto"]) {$select_pgto= " AND vendas_pgto = '" . $vendas_pgto . "'";} else {$select_pgto="";}

$vendas_status_motivo="";
$select_status_motivo="";
if ($_GET["vendas_status_motivo"]) {
	$vendas_status_motivo = mysql_real_escape_string( utf8_decode($_GET["vendas_status_motivo"]) );
	$select_status_motivo = " AND vendas_status_motivo LIKE '" . $vendas_status_motivo . "'";
}

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="vendas_id";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="DESC";}
if ($_GET["ordenacao"] == "ASC"){$link_ordem = "DESC";}else{$link_ordem = "ASC";}

if ($_GET["contar"]) {
	$contagem = ", COUNT(sys_vendas_seguros.cliente_cpf) AS contagem"; 
	$agrupamento=" GROUP BY sys_vendas_seguros.cliente_cpf ";
	if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="contagem";}
	if ($_GET["num_vendas"]){$select_num_vendas= " HAVING contagem > '" . $_GET['num_vendas'] . "'";}
}else{
	$agrupamento="";
}

include("sistema/utf8.php");
if ($_GET["vendas_consultor"]){
$vendas_consultor=$_GET["vendas_consultor"];
				for ($i=0;$i<count($vendas_consultor);$i++){
					if ($vendas_consultor[$i] != ""){
						if ($i==0){
							$select_consultor = " AND (vendas_consultor = '" . $vendas_consultor[$i] . "'";
						}else{$select_consultor = $select_consultor." OR vendas_consultor = '" . $vendas_consultor[$i] . "'";}					
					}
					$aux_consultor = $i;
				}
				if ($vendas_consultor[$aux_consultor] != ""){$select_consultor = $select_consultor.")";}
				for ($i=0;$i<count($vendas_consultor);$i++){
					if ($vendas_consultor[$i] != ""){
							$pag_consultor = $pag_consultor."&vendas_consultor[]=".$vendas_consultor[$i];					
					}
				}
} else {$select_consultor="";}

$consultor_unidade=$_GET["consultor_unidade"];

$join_unidade= " INNER JOIN jos_users ON sys_vendas_seguros.vendas_consultor = jos_users.id";
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
} else {
	$select_unidade="";
}



$p = $_GET["p"];
if(isset($p)) {
$p = $p;
} else {
$p = 1;
}
$qnt = 20;
$inicio = ($p*$qnt) - $qnt;
$filtros_sql = $filtros_sql . 
$select_nome . 
$select_id . 
$select_state . 
$select_city . 
$select_bank . 
$select_proposta . 
$select_status . 
$select_debito_banco . 
$select_cliente_uf . 
$select_consultor . 
$select_unidade . 
$select_turno . 
$select_empregador . 
$select_matricula . 
$select_pgto . 
$select_status_motivo .
$select_apolice_tipo .
$select_apolice;

$result = mysql_query("SELECT *" . $contagem . " FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_vendas_pgto ON sys_vendas_seguros.vendas_pgto = sys_vendas_pgto.pgto_id
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
INNER JOIN sys_vendas_banco_seg ON sys_vendas_apolices.apolice_banco = sys_vendas_banco_seg.banco_id
LEFT JOIN sys_instagram ON sys_vendas_seguros.cliente_cpf = sys_instagram.cliente_cpf
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" . 
$filtros_sql . 
$agrupamento . $select_num_vendas ." ORDER BY " . $ordem . " " . $ordenacao . " LIMIT 0, 5000;") or die(mysql_error());

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
			<td>SEXO</td>
			<?php if ($contagem): ?><td>QT:</td><?php endif; ?>
			<td>TELEFONE DA VENDA:</td>
			<td>2o TELEFONE DA VENDA:</td>
			<td>MATRICULA:</td>
			<td>MATRICULA INSTITUIDOR:</td>
			<td>VALOR:</td>
			<td>VENDEDOR:</td>
			<td>DATA DA VENDA:</td>
			<td>DATA DA ATIVACAO:</td>			
			<td>STATUS:</td>
			<td>STATUS MOTIVO:</td>
			<td>CODIGO:</td>
			<td> </td>
			<td>No DA PROPOSTA:</td>
			<td>No DA APOLICE:</td>
			<td>SEGURADORA:</td>
			<td>APOLICE:</td>
			<td>RG:</td>
			<td>EXP:</td>
			<td>DATA:</td>
			<td>NASCIMENTO:</td>
			<td>NATURALIDADE:</td>
			<td>FORMA DE PGTO:</td>
			<td>BANCO:</td>
			<td>AG:</td>
			<td>AG. DIGITO:</td>					
			<td>CONTA:</td>
			<td>DIGITO CC:</td>
			<td>VENCIMENTO:</td>	
			<td>EMPREGADOR:</td>
			<td>ORGAO:</td>
			<td>ESTADO:</td>
			<td>CEP:</td>
			<td>CIDADE:</td>
			<td>BAIRRO:</td>
			<td>ENDERECO:</td>
			<td>EMAIL:</td>
			<td>POSSUI INSTAGRAM</td>
			<td>ID CONSULTOR:</td>
			<td>UNIDADE:</td>
			<td>BENEF(1) NOME:</td>
			<td>BENEF(1) PARENTESCO:</td>
			<td>BENEF(1) PERC (%):</td>
			<td>BENEF(2) NOME:</td>
			<td>BENEF(2) PARENTESCO:</td>
			<td>BENEF(2) PERC (%):</td>
			<td>BENEF(3) NOME:</td>
			<td>BENEF(3) PARENTESCO:</td>
			<td>BENEF(3) PERC (%):</td>
			<td>BENEF(4) NOME:</td>
			<td>BENEF(4) PARENTESCO:</td>
			<td>BENEF(4) PERC (%):</td>
			<td>BENEF(5) NOME:</td>
			<td>BENEF(5) PARENTESCO:</td>
			<td>BENEF(5) PERC (%):</td>

            </div>
		</tr>
		  	      <?php
$totalclientes = 0;
$exibindo = 1;
$numero = $exibindo;

while($row = mysql_fetch_array( $result )) {
$endereco_link = "#";

$vendas_valor = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0' ;

$result_user = mysql_query("SELECT name FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );

$yr=strval(substr($row["vendas_dia_venda"],0,4));
$mo=strval(substr($row["vendas_dia_venda"],5,2));
$da=strval(substr($row["vendas_dia_venda"],8,2));
$hr=strval(substr($row["vendas_dia_venda"],11,2));
$mi=strval(substr($row["vendas_dia_venda"],14,2));
$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));

$data_ativacao = dataDB_to_dataBR($row["vendas_dia_ativacao"]);



$result_status = mysql_query("SELECT status_nm FROM sys_vendas_status_seg WHERE status_id = " . $row['vendas_status'] . ";")
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
	echo "<td>".$row["cliente_sexo"]."</td>";
	if ($contagem){
		$link_num = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=440&nome=".$nome."&prec=".$prec."&cpf=".$row['cliente_cpf'].$pag_mes."&consultor_unidade=".$pag_unidade."&vendas_consultor=".$vendas_consultor."&vendas_vendedor=".$vendas_vendedor."&vendas_status=".$pag_status."&vendas_contrato_fisico=".$pag_contrato."&vendas_promotora=".$vendas_promotora."&vendas_banco=".$vendas_banco."&vendas_orgao=".$vendas_orgao."&vendas_tipo_contrato=".$vendas_tipo_contrato."&vendas_seguro_protegido=".$vendas_seguro_protegido."&dp-normal-3=".$pag_data_imp_ini."&dp-normal-4=".$pag_data_imp_fim;
		echo "<td>".$row['contagem']."</td>";
	}
	echo "<td>".$row['vendas_telefone']."</td>";
	echo "<td>".$row['vendas_telefone2']."</td>";
	echo "<td>".$row['cliente_beneficio']."</td>";
	echo "<td>".$row['cliente_pagamento']."</td>";
	echo "<td>R$ ".$vendas_valor."</td>";
	echo "<td>".$row_user['name']."<br />";
	echo "<td>".$data_venda."</td>";
	echo "<td>".$data_ativacao."</td>";
	echo "<td>".$row_status['status_nm']."</td>"; 
	echo "<td>".$row['vendas_status_motivo']."</td>";
	echo "<td><div align='right'><strong>{$row['vendas_id']}</strong></div></td>";
	echo "<td> </td>";
	echo "<td>".$row['vendas_proposta']."</td>";
	echo "<td>".$row['vendas_num_apolice']."</td>";
	echo "<td>".$row['banco_nm']."</td>";
	echo "<td>".$row['apolice_nome']."</td>";	
	echo "<td>".$row['cliente_rg']."</td>";
	echo "<td>".$row['cliente_rg_exp']."</td>";
	echo "<td>".$row['cliente_rg_dt']."</td>";
	echo "<td>".$row['cliente_nascimento']."</td>";
	echo "<td>".$row['cliente_naturalidade']."</td>";
	echo "<td>".$row['pgto_nm']."</td>";
	echo "<td>".$row['vendas_debito_banco']."</td>";
	echo "<td>".$row['vendas_debito_ag']."</td>";
	echo "<td>".$row['vendas_debito_ag_dig']."</td>";	
	echo "<td>".$row['vendas_debito_cc']."</td>";
	echo "<td>".$row['vendas_debito_cc_dig']."</td>";
	echo "<td>".$row['vendas_dia_desconto']."</td>";
	echo "<td>".$row['cliente_empregador']."</td>";
	echo "<td>".$row['cliente_orgao']."</td>";
	echo "<td>".$row['cliente_uf']."</td>";
	echo "<td>".$row['cliente_cep']."</td>";
	echo "<td>".$row['cliente_cidade']."</td>";
	echo "<td>".$row['cliente_bairro']."</td>";
	echo "<td>".$row['cliente_endereco']."</td>";
	echo "<td>".$row['cliente_email']."</td>";
	$tem_insta = $row['possui_instagram'] ? "SIM" : "NÃO";
	echo "<td>".$tem_insta."</td>";
	echo "<td>".$row['vendas_consultor']."</td>";
	echo "<td>".$row['unidade']."</td>";

	$row_beneficiarios="";
	$result_beneficiarios = mysql_query("SELECT ben_nome, ben_parent, ben_perc FROM sys_vendas_ben WHERE vendas_id = " . $row['vendas_id'] . ";")
								or die(mysql_error());
	
		while( $row_beneficiarios = mysql_fetch_array( $result_beneficiarios ) )
		{
			echo "<td>";
			echo $row_beneficiarios['ben_nome']."<br>";
			echo "</td>";
			echo "<td>";
			echo $row_beneficiarios['ben_parent']."<br>";
			echo "</td>";
			echo "<td>";
			echo $row_beneficiarios['ben_perc']."<br>";
			echo "</td>";
		}
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
FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" .  
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
$sql_select_all = mysql_query("SELECT COUNT(*) AS total FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" . 
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

<?php 
	// FUNÇÃO AJUSTA DATA DO FORMULÁRIO PARA O FORMATO DO BANCO DE DADOS
	function dataBR_to_dataDB($dataBr) {

		return implode("-",array_reverse(explode("-", str_replace("/","-",$dataBr))));
	}

	// FUNÇÃO AJUSTA DATA DO BANCO DE DADOS PARA O FORMATO DO FORMULÁRIO
	function dataDB_to_dataBR($dataDb) {

		return implode("/",array_reverse(explode("/", str_replace("-","/",$dataDb))));
	}
?>