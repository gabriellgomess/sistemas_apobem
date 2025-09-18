<style>
	td{
		border-bottom:2px solid #333;
		padding-left: 2px;
	}
</style>
<?php
$cpf=$_GET["cpf"];
$clients_cat=$_GET["clients_cat"];
if ($_GET["p"]){$pagina=$_GET["p"];}else{$pagina="1";}
//$vendas_status=$_GET["vendas_status"];
//if ($_GET["vendas_status"]) {$select_status= " AND vendas_status = '" . $vendas_status . "'";} else {$select_status="";}

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
if ($_GET["dp-normal-1"]){
$pag_data_ini = $_GET["dp-normal-1"];
$data_ini = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));
$select_data_ini= " AND vendas_dia_venda >= '" . $data_ini . " 00:00:00'";
} else {$select_data_ini = "";}

if ($_GET["dp-normal-2"]){
$pag_data_fim = $_GET["dp-normal-2"];
$data_fim = implode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "-" : "/", $_GET["dp-normal-2"])));
$select_data_fim= " AND vendas_dia_venda <= '" . $data_fim . " 23:59:59'";
} else {$select_data_fim="";}

if ($_GET["dp-normal-3"]){
$pag_data_imp_ini = $_GET["dp-normal-3"];
$data_imp_ini = implode(preg_match("~\/~", $_GET["dp-normal-3"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-3"]) == 0 ? "-" : "/", $_GET["dp-normal-3"])));
$select_data_imp_ini= " AND vendas_dia_imp >= '" . $data_imp_ini . " 00:00:00'";
} else {$select_data_imp_ini = "";}

if ($_GET["dp-normal-4"]){
$pag_data_imp_fim = $_GET["dp-normal-4"];
$data_imp_fim = implode(preg_match("~\/~", $_GET["dp-normal-4"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-4"]) == 0 ? "-" : "/", $_GET["dp-normal-4"])));
$select_data_imp_fim= " AND vendas_dia_imp <= '" . $data_imp_fim . " 23:59:59'";
} else {$select_data_imp_fim="";}

$vendas_banco=$_GET["vendas_banco"];
if ($_GET["vendas_banco"]) {$select_bank= " AND vendas_banco like '%" . $vendas_banco . "%'";} else {$select_bank="";}

$vendas_orgao=$_GET["vendas_orgao"];
if ($_GET["vendas_orgao"]) {$select_orgao= " AND vendas_orgao like '%" . $vendas_orgao . "%'";} else {$select_orgao="";}

$vendas_promotora=$_GET["vendas_promotora"];
if ($_GET["vendas_promotora"]) {$select_promotora= " AND vendas_promotora like '%" . $vendas_promotora . "%'";} else {$select_promotora="";}

$vendas_id=$_GET["vendas_id"];
if ($_GET["vendas_id"]) {$select_id= " AND vendas_id = '" . $vendas_id . "'";} else {$select_id="";}

$nome=$_GET["nome"];
if ($_GET["nome"]) {$select_nome= " AND (clients_nm like '%" . $nome . "%' OR cliente_nome like '%" . $nome . "%')";} else {$select_nome="";}

$prec=$_GET["prec"];
if ($_GET["prec"]) {$select_prec= " AND sys_clients.clients_prec_cp like '%" . $prec . "%'";} else {$select_prec="";}

$vendas_turno=$_GET["vendas_turno"];
if ($_GET["vendas_turno"]) {$select_turno= " AND vendas_turno = '" . $vendas_turno . "'";} else {$select_turno="";}

$vendas_contrato_fisico=$_GET["vendas_contrato_fisico"];
if (($_GET["vendas_contrato_fisico"] == "0")
|| ($_GET["vendas_contrato_fisico"] == "1")
|| ($_GET["vendas_contrato_fisico"] == "2")
|| ($_GET["vendas_contrato_fisico"] == "3")
|| ($_GET["vendas_contrato_fisico"] == "4")) {$select_contrato= " AND vendas_contrato_fisico = '" . $vendas_contrato_fisico . "'";} else {$select_contrato="";}

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="vendas_id";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="DESC";}
if ($_GET["ordenacao"] == "ASC"){
	$link_ordem = "DESC";
	$img_ordem = "<img src='sistema/imagens/asc.png'>";
}else{
	$link_ordem = "ASC";
	$img_ordem = "<img src='sistema/imagens/desc.png'>";
}

$user =& JFactory::getUser();
$username=$user->username;
$user_id=$user->id;

include("sistema/utf8.php");

$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '10'){$administracao = 1;}
	if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if ($row_grupo_user['id'] == '19'){$financeiro = 1;}
}

$vendas_consultor=$_GET["vendas_consultor"];
$select_consultor= " AND vendas_consultor = " . $vendas_consultor . "";
$result_user = mysql_query("SELECT unidade, equipe_id, situacao, nivel, empresa, data_contrato_90 FROM jos_users WHERE id = " . $vendas_consultor . ";") 
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );
$vendas_unidade = $row_user["unidade"];
$vendas_equipe = $row_user["equipe_id"];
$consultor_situacao = $row_user["situacao"];
$consultor_nivel = $row_user["nivel"];
$consultor_empresa = $row_user["empresa"];
if ($row_user['nivel'] == 3){$nivel = "consultor";}
if ($row_user['nivel'] == 2){$nivel = "cordenador";}

// Consulta a base de produção do mês corrente, do vendedor:
$result_base_consultor = mysql_query("SELECT SUM(vendas_fortcoins) AS total_fortcoins FROM sys_vendas 
WHERE vendas_consultor = '" . $vendas_consultor . "' 
AND (vendas_status = 8 OR vendas_status = 9)".$select_mes.";")
or die(mysql_error());
$row_base_consultor = mysql_fetch_array( $result_base_consultor );

$result_regra_cms = mysql_query("SELECT * FROM sys_regras_cms 
WHERE regras_cms_base_ini <= ".$row_base_consultor['total_fortcoins']." 
AND regras_cms_base_fim >= '".$row_base_consultor['total_fortcoins']."' 
AND (consultor_situacao LIKE '%,".$consultor_situacao.",%' OR consultor_situacao LIKE '0') 
AND (consultor_empresa = '".$consultor_empresa."' OR consultor_empresa = '0') 
AND (consultor_unidade LIKE '%,".$vendas_unidade.",%' OR consultor_unidade LIKE '0') 
AND (consultor_equipe = '".$vendas_equipe."' OR consultor_equipe = '0') 
ORDER BY regras_cms_ordem ASC 
LIMIT 0, 1;")
or die(mysql_error());
$row_regra_cms = mysql_fetch_array( $result_regra_cms );

$result_comissao_minima = mysql_query("SELECT meta_id, meta_nome, meta_valor1, meta_valor2, meta_nivel
	FROM sys_metas WHERE meta_nivel = '" . $row_user['nivel'] . "';") 
or die(mysql_error());
$row_comissao_minima = mysql_fetch_array( $result_comissao_minima );
if( isset($row_comissao_minima['meta_valor1']) )
{
	$comissao_minima = $row_comissao_minima['meta_valor1'];
}else{
	$comissao_minima = 0;
}

/*
if($row["nivel"] == 2){
	if (($row['unidade'] == "Santa Maria")||($row['unidade'] == "Santa Cruz do Sul")){
		$unidade = $row['unidade'];
		$sql_select_total = mysql_query("SELECT 
		SUM(vendas_base_prod) AS total_base 
		FROM sys_vendas INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id
		WHERE jos_users.unidade = '" . $unidade . "'" . 
		$select_bank . 
		$select_data_ini . 
		$select_data_fim . 
		$select_data_imp_ini . 
		$select_data_imp_fim . 
		$select_status . 
		$select_orgao . 
		$select_consultor . 
		$select_promotora . 
		$select_mes . 
		$select_contrato . 
		$select_turno .";")
		or die(mysql_error());
		$row_total_valor_unidade = mysql_fetch_array( $sql_select_total );
		if ($row['unidade'] == "Santa Maria"){$base_minima_loja = 150000;}
		if ($row['unidade'] == "Santa Cruz do Sul"){$base_minima_loja = 120000;}
		if ($row_total_valor_unidade['total_base'] >= $base_minima_loja){$comissao_minima = 0;}else{$comissao_minima = 50000;}
	}else{$comissao_minima = 0;}
}else{
	if (($row['unidade'] == "Santa Maria")||($row['unidade'] == "Santa Cruz do Sul")||($row['unidade'] == "Porto Alegre")){$comissao_minima = 50000;}else{$comissao_minima = 40000;}
}
*/

$select_unidade="";
$join_unidade="";
	
$p = $_GET["p"];
if(isset($p)) {
$p = $p;
} else {
$p = 1;
}
$qnt = 20;
$inicio = ($p*$qnt) - $qnt;
$result = mysql_query("SELECT * FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$select_prec . 
$select_nome . 
$select_id . 
$select_state . 
$select_city . 
$select_bank . 
$select_data_ini . 
$select_data_fim . 
$select_data_imp_ini . 
$select_data_imp_fim . 
$select_status . 
$select_orgao . 
$select_consultor . 
$select_unidade . 
$select_promotora . 
$select_mes . 
$select_contrato . 
$select_turno . 
" ORDER BY " . $ordem . " " . $ordenacao . ";") 
or die(mysql_error());
?>

 <?php  $curURL = $_SERVER["REQUEST_URI"]; ?>
 
<div align="left">
	<h2>VENDAS DE CRÉDITO:</h2>
  <table width="100%" border="2" align="center" cellpadding="0" cellspacing="1">
		<tbody>
		  <tr class="cabecalho">
			<div align="left" class="style8">
				<td width="3%"><span style="color:#666; font-size:8pt">#</span></td>
				<td width="30%">				
					Cliente<br>
					<span style="color:#666; font-size:8pt">CPF: | Matrícula:</span></td>
				<td width="12%">
					Órgão<br>
					<span style="color:#666; font-size:8pt">Banco:</span></td>
				<td width="11%">
					Valor AF<br>
					<span style='color:#666; font-size:8pt'>Tipo</span>
				</td>
				<td width="21%">
					Consultor<br>
					<span style='color:#666; font-size:8pt'>Data da venda:</span></td>
				<td width="15%">
					Status<br>
					<span style='color:#666; font-size:8pt'>Data pgto. | Mês</span>
				</td>
				<td width="5%">Cód.</td>
			</div>
		</tr>
	<tr>
<table class="listaValores" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 2px solid #333;">
<tbody>

<?php
$totalclientes = 0;
$exibindo = 1;
$numero = $exibindo;
include("sistema/vendas/lista_rel_consultor.php");
$exibindo = $exibindo  - 1;

if (($diretoria == 1)||($financeiro == 1)){
	if (($vendas_mes)&&($pag_status == "&vendas_status[]=8&vendas_status[]=9")){$sum_comissao = ", SUM(vendas_comissao_vendedor) AS total_comissao ";}else{$sum_comissao = " ";}
}

// TOTAIS BASE 1
$sql_select_total_1 = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_receita) AS total_receita, 
SUM(vendas_base_prod) AS total_base".$sum_comissao."
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%' AND sys_vendas.vendas_base = '1'" . 
$select_prec . 
$select_nome . 
$select_id . 
$select_state . 
$select_city . 
$select_bank . 
$select_data_ini . 
$select_data_fim . 
$select_data_imp_ini . 
$select_data_imp_fim . 
$select_status . 
$select_orgao . 
$select_consultor . 
$select_unidade . 
$select_promotora . 
$select_mes . 
$select_contrato . 
$select_turno .";")
or die(mysql_error());
$row_total_valor_1 = mysql_fetch_array( $sql_select_total_1 );
$total_valor_1 = ($row_total_valor_1['total_valor']>0) ? number_format($row_total_valor_1['total_valor'], 2, ',', '.') : '0' ;
$total_receita_1 = ($row_total_valor_1['total_receita']>0) ? number_format($row_total_valor_1['total_receita'], 2, ',', '.') : '0' ;
$total_base_1 = ($row_total_valor_1['total_base']>0) ? number_format($row_total_valor_1['total_base'], 2, ',', '.') : '0' ;
if (($diretoria == 1)||($financeiro == 1)){
	if (($vendas_mes)&&($pag_status == "&vendas_status[]=8&vendas_status[]=9")){
		$total_comissao_1 = ($row_total_valor_1['total_comissao']>0) ? number_format($row_total_valor_1['total_comissao'], 2, ',', '.') : '0' ;
		}
}

// TOTAIS BASE 2
$sql_select_total_2 = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_receita) AS total_receita, 
SUM(vendas_base_prod) AS total_base".$sum_comissao."
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%' AND sys_vendas.vendas_base = '2'" . 
$select_prec . 
$select_nome . 
$select_id . 
$select_state . 
$select_city . 
$select_bank . 
$select_data_ini . 
$select_data_fim . 
$select_data_imp_ini . 
$select_data_imp_fim . 
$select_status . 
$select_orgao . 
$select_consultor . 
$select_unidade . 
$select_promotora . 
$select_mes . 
$select_contrato . 
$select_turno .";")
or die(mysql_error());
$row_total_valor_2 = mysql_fetch_array( $sql_select_total_2 );
$total_valor_2 = ($row_total_valor_2['total_valor']>0) ? number_format($row_total_valor_2['total_valor'], 2, ',', '.') : '0' ;
$total_receita_2 = ($row_total_valor_2['total_receita']>0) ? number_format($row_total_valor_2['total_receita'], 2, ',', '.') : '0' ;
$total_base_2 = ($row_total_valor_2['total_base']>0) ? number_format($row_total_valor_2['total_base'], 2, ',', '.') : '0' ;
if (($diretoria == 1)||($financeiro == 1)){
	if (($vendas_mes)&&($pag_status == "&vendas_status[]=8&vendas_status[]=9")){
	$total_comissao_2 = ($row_total_valor_2['total_comissao']>0) ? number_format($row_total_valor_2['total_comissao'], 2, ',', '.') : '0' ;
	}
}

// TOTAIS BASE 1 + 2
$sql_select_total = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_receita) AS total_receita, 
SUM(vendas_base_prod) AS total_base".$sum_comissao."
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$select_prec . 
$select_nome . 
$select_id . 
$select_state . 
$select_city . 
$select_bank . 
$select_data_ini . 
$select_data_fim . 
$select_data_imp_ini . 
$select_data_imp_fim . 
$select_status . 
$select_orgao . 
$select_consultor . 
$select_unidade . 
$select_promotora . 
$select_mes . 
$select_contrato . 
$select_turno .";")
or die(mysql_error());
$row_total_valor = mysql_fetch_array( $sql_select_total );
$total_valor = ($row_total_valor['total_valor']>0) ? number_format($row_total_valor['total_valor'], 2, ',', '.') : '0' ;
$total_receita = ($row_total_valor['total_receita']>0) ? number_format($row_total_valor['total_receita'], 2, ',', '.') : '0' ;
$total_base = ($row_total_valor['total_base']>0) ? number_format($row_total_valor['total_base'], 2, ',', '.') : '0' ;
if (($diretoria == 1)||($financeiro == 1)){
	if (($vendas_mes)&&($pag_status == "&vendas_status[]=8&vendas_status[]=9")&&($row_total_valor['total_base'] >= $comissao_minima)){
		$total_comissao = ($row_total_valor['total_comissao']>0) ? number_format($row_total_valor['total_comissao'], 2, ',', '.') : '0' ;
		$bonus = 0;
		//if ($nivel == "consultor"){
			//if (($row_total_valor['total_base'] >= 65000)&&($row_total_valor['total_base'] <= 99999)){$bonus_rs = "R$ 300,00";$bonus = 300;}
			//if ($row_total_valor['total_base'] >= 100000){$bonus_rs = "R$ 500,00";$bonus = 500;}
			//if ($row_total_valor['total_base'] >= 200000){$bonus_rs = "R$ 1.000,00";$bonus = 1000;}
			//if ($row_total_valor['total_base'] >= 300000){$bonus_rs = "R$ 1.500,00";$bonus = 1500;}
			//if ($row_total_valor['total_base'] >= 400000){$bonus_rs = "R$ 2.000,00";$bonus = 2000;}
			//if ($row_total_valor['total_base'] >= 500000){$bonus_rs = "R$ 2.500,00";$bonus = 2500;}
		//}
		$total_comissao_bonus = $row_total_valor['total_comissao'] + $bonus;
		$total_comissao_bonus = ($total_comissao_bonus>0) ? number_format($total_comissao_bonus, 2, ',', '.') : '0' ;
	}else{$total_comissao = 0;}
}

	echo "<tr style='vertical-align:baseline;'><div align='left'>";
	echo "<td colspan='7'>Resultados totais de todos os resultados da Pesquisa:</br><div align='center'>";
	echo "<table width='85%'>";	
	echo "<tr style='vertical-align:baseline;'>";
	echo "<td><strong>Vendas com Base 1:</strong></td>";
	echo "<td><strong>Vendas com Base 2:</strong></td>";
	echo "<td><strong>Totais (1 e 2):</strong></td>";
	echo "</tr>";	
	echo "<tr style='vertical-align:baseline;'>";
	echo "<td>"; 	
	echo "Valores de AFs: <strong>R$ ".$total_valor_1."</strong></br>";
	echo "Bases: <strong>R$ ".$total_base_1."</strong></br><hr>";	
	if (($diretoria == 1)||($financeiro == 1)){
		if (($vendas_mes)&&($pag_status == "&vendas_status[]=8&vendas_status[]=9")&&($row_total_valor['total_base'] >= $comissao_minima)){
			echo "$% <strong>R$ ".$total_comissao_1."</strong>";
			}
	}
	echo "</div>";	
	echo "</td>";
	echo "<td>"; 	
	echo "Valores de AFs: <strong>R$ ".$total_valor_2."</strong></br>";
	echo "Bases: <strong>R$ ".$total_base_2."</strong></br><hr>";	
	if (($diretoria == 1)||($financeiro == 1)){
		if (($vendas_mes)&&($pag_status == "&vendas_status[]=8&vendas_status[]=9")&&($row_total_valor['total_base'] >= $comissao_minima)){
			echo "$% <strong>R$ ".$total_comissao_2."</strong>";
			}
	}
	echo "</div>";	
	echo "</td>";
	echo "<td>"; 	
	echo "Valores de AFs: <strong>R$ ".$total_valor."</strong></br>";
	echo "Bases: <strong>R$ ".$total_base."</strong></br><hr>";	
	if (($diretoria == 1)||($financeiro == 1)){
		if (($vendas_mes)&&($pag_status == "&vendas_status[]=8&vendas_status[]=9")){
			if ($bonus > 0){
				echo "$% <strong>R$ ".$total_comissao."</strong></br>";
				echo "Bônus: <strong>R$ ".$bonus_rs."</strong></br>";
				echo "$% <span style='color:#000; font-size:14pt'><strong>R$ ".$total_comissao_bonus."</strong></span></br>";
			}else{
				echo "$% <span style='color:#000; font-size:14pt'><strong>R$ ".$total_comissao."</strong></span></br>";
			}
		}
	}
	echo "</div>";	
	echo "</td>";		
	echo "</tr>";	
	echo "</table>";	
	echo "</td>"; 	
	echo "</tr>";
	echo "<tr style='vertical-align:baseline;'><div align='left'>";
	echo "<td colspan='7'><div align='center'>";
	echo "<table>";
	echo "<tr style='vertical-align:baseline;'>";
$sql_select_all = mysql_query("SELECT COUNT(*) AS total FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$select_prec . 
$select_nome . 
$select_id . 
$select_state . 
$select_city . 
$select_bank . 
$select_data_ini . 
$select_data_fim . 
$select_data_imp_ini . 
$select_data_imp_fim . 
$select_status . 
$select_orgao . 
$select_consultor . 
$select_unidade . 
$select_promotora . 
$select_mes . 
$select_contrato . 
$select_turno .";")
or die(mysql_error());
$row_total_registros = mysql_fetch_array( $sql_select_all );
$total_registros = $row_total_registros["total"];
?>
</tbody>
          </table>
            </tbody>
          </table>
    </table>
<div align="center">Total de <?php echo $total_registros;?> vendas.</div><br>
<hr><br>
<?php
$result_cont_seguros = mysql_query("SELECT COUNT(vendas_id) AS total, SUM(vendas_comissao_vendedor) AS total_cms, SUM(vendas_valor) AS total_valor FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf) 
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
WHERE vendas_pago_vendedor = 1 AND (vendas_status = 9 OR vendas_status = 10 OR vendas_status = 67)" . $select_consultor .";") 
or die(mysql_error());
$row_cont_seguros = mysql_fetch_array( $result_cont_seguros );
?>
	<?php if ($row_cont_seguros['total'] > 4): ?>
		<h2>VENDAS DE SEGUROS:</h2>
		<?php
		$result_seguros = mysql_query("SELECT vendas_id, sys_vendas_seguros.cliente_cpf AS cliente_cpf, 
		clients_nm, cliente_nome, apolice_nome, name, status_nm, vendas_comissao_vendedor, vendas_valor, vendas_dia_venda, apolice_cms_vendedor 
		FROM sys_vendas_seguros 
		LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
		LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf) 
		INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
		INNER JOIN sys_vendas_status_seg ON (sys_vendas_seguros.vendas_status = sys_vendas_status_seg.status_id) 
		INNER JOIN jos_users ON (sys_vendas_seguros.vendas_consultor = jos_users.id) 
		WHERE vendas_pago_vendedor = 1 AND (vendas_status = 9 OR vendas_status = 10 OR vendas_status = 67)" . $select_consultor ." ORDER BY " . $ordem . " " . $ordenacao . ";") 
		or die(mysql_error());
		?>
		<table width="100%" border="2" align="center" cellpadding="0" cellspacing="1">
			<tr class="cabecalho">
				<div align="left" class="style8">
					<td width="3%"><span style="color:#666; font-size:8pt">#</span></td>
					<td width="25%">				
						Cliente<br>
						<span style="color:#666; font-size:8pt">CPF:</span></td>
					<td width="12%">
						Valor<br>
						<span style="color:#666; font-size:8pt">Apólice:</span></td>
					<td width="21%">
						Consultor<br>
						<span style='color:#666; font-size:8pt'>Data da venda:</span></td>
					<td width="15%">
						Status
					</td>
					<td width="10%">Cód.<br>CMS:</td>
				</div>
			</tr>
			<tr>
				<table class="listaValores" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 2px solid #333;">
					<?php
					$totalclientes = 0;
					$exibindo = 1;
					$numero = $exibindo;
					include("sistema/vendas/lista_rel_consultor_seguros.php");
					$exibindo = $exibindo  - 1;
					?>
				</table>
			</tr>
		</table>
		<?php 
		if($_GET["processar"] == "1"){
			$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_pago_vendedor='2' WHERE vendas_pago_vendedor = 1 AND (vendas_status = 9 OR vendas_status = 10 OR vendas_status = 67)" . $select_consultor .";") or die(mysql_error());
		}
		?>
		<hr>
		<div style="text-align: center;">
			<?php $total_valor_seguros = ($row_cont_seguros["total_valor"]>0) ? number_format($row_cont_seguros["total_valor"], 2, ',', '.') : '0' ; ?>
			<?php $total_cms_seguros = ($row_cont_seguros["total_cms"]>0) ? number_format($row_cont_seguros["total_cms"], 2, ',', '.') : '0' ; ?>
			Total de vendas: <strong><?php echo $row_cont_seguros["total"]; ?></strong><br>
			Valor total de vendas: <strong>R$ <?php echo $total_valor_seguros; ?></strong><br>
			Total % Consultor: <strong>R$ <?php echo $total_cms_seguros; ?></strong><br>
			<a href="<?php echo $_SERVER[REQUEST_URI]; ?>&processar=1"><button class="button validate png" type="button">Processar Pagamento Seguros</button></a>
		</div>
	<?php endif; ?>
	<?php if ($row_total_valor['total_base'] >= 65000): ?>
		<h2>FORTCOINS:</h2>
		<div style="text-align: center;">
			<?php $extrato_valor = $row_total_valor['total_base'] / $row_regra_cms['regras_div_fc']; ?>
			<?php $pontos_rs = ($extrato_valor>0) ? number_format($extrato_valor, 2, ',', '.') : '0' ; ?>
			Saldo de FortCoins para creditar: <strong>F&#162; <?php echo $pontos_rs; ?></strong><br><br>
			<a href="<?php echo $_SERVER[REQUEST_URI]; ?>&processar_fc=1"><button class="button validate png" type="button">Creditar FortCoins</button></a>
		</div>
		<?php 
		if($_GET["processar_fc"] == "1"){
			$result_saldo = mysql_query("SELECT pontos FROM jos_users WHERE id = '" . $vendas_consultor . "';") or die(mysql_error());  
			$row_saldo = mysql_fetch_array( $result_saldo );
			$extrato_saldo = $row_saldo["pontos"] + $extrato_valor;
			$extrato_data = date("Y-m-d H:i:s");
			
			$extrato_saldo = round($extrato_saldo, 2);
			$sql = "INSERT INTO sistema.sys_fortcoins_extrato (
			extrato_consultor,
			extrato_tipo,
			extrato_valor,
			extrato_saldo,
			extrato_anexo,
			extrato_data,
			extrato_criador,
			extrato_obs)
			VALUES (
			'$vendas_consultor',
			'1',
			'$extrato_valor',
			'$extrato_saldo',
			'$extrato_anexo',
			'$extrato_data',
			'$user_id',
			'Saldo de FortCoins creditado via processamento de fechamento mensal.');";

			if (mysql_query($sql,$con)){
				$extrato_id = mysql_insert_id();
				echo "Lançamento adicionado ao extrato com sucesso! </br>";
			} else {
				die('Error: ' . mysql_error());
			}

			$query = mysql_query("UPDATE jos_users SET pontos='$extrato_saldo' WHERE id='$vendas_consultor'") or die(mysql_error());
			echo "Saldo Atualizado com Sucesso";
			
		}
		?>
		<hr>
	<?php endif; ?>
  </div>
</form>
<?php
echo "<br>Regra Aplicada:<br>";
echo "regras_cms_id: ".$row_regra_cms['regras_cms_id']."<br>";
echo "regras_cms_ordem: ".$row_regra_cms['regras_cms_ordem']."<br>";
echo "regras_cms_nome: ".$row_regra_cms['regras_cms_nome']."<br>";
echo "regras_cms_base_ini: ".$row_regra_cms['regras_cms_base_ini']."<br>";
echo "regras_cms_base_fim: ".$row_regra_cms['regras_cms_base_fim']."<br>";
echo "regras_cms_cms_1: ".$row_regra_cms['regras_cms_cms_1']."<br>";
echo "regras_cms_cms_2: ".$row_regra_cms['regras_cms_cms_2']."<br>";
echo "regras_div_fc: ".$row_regra_cms['regras_div_fc']."<br>";
echo "consultor_situacao: ".$row_regra_cms['consultor_situacao']."<br>";
echo "consultor_empresa: ".$row_regra_cms['consultor_empresa']."<br>";
echo "consultor_unidade: ".$row_regra_cms['consultor_unidade']."<br>";
echo "consultor_equipe: ".$row_regra_cms['consultor_equipe']."<br>";
?>
<?php mysql_close($con); ?>