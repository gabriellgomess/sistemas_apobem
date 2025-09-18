<style type="text/css">
td.borda {border-bottom: 2px solid #333;}
</style>
<?php

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

$vendas_apolice=$_GET["vendas_apolice"];
if ($_GET["vendas_apolice"]) {$select_apolice= " AND vendas_apolice = '" . $vendas_apolice . "'";} else {$select_apolice="";}

$vendas_pgto=$_GET["vendas_pgto"];
if ($_GET["vendas_pgto"]) {$select_pgto= " AND vendas_pgto = '" . $vendas_pgto . "'";} else {$select_pgto="";}

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
if ($_GET["contar"]) {
	$contagem = ", COUNT(sys_vendas_seguros.cliente_cpf) AS contagem"; 
	$agrupamento=" GROUP BY sys_vendas_seguros.cliente_cpf ";
	if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="contagem";}	
}else{
	$agrupamento="";
}
include("sistema/utf8.php");
$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if (($row_grupo_user['id'] == '10')||($row_grupo_user['id'] == '26')){$administracao = 1;}
	if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if ($row_grupo_user['id'] == '19'){$financeiro = 1;}
	if ($row_grupo_user['id'] == '21'){$franquiado = 1;}
}

if ($administracao == 1){
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
}else{$select_consultor= " AND vendas_consultor = '" . $user_id . "'";}

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
$filtros_sql = $select_nome . 
$select_id . 
$select_state . 
$select_city . 
$select_bank . 
$select_proposta . 
$select_data_ini . 
$select_data_fim . 
$select_status . 
$select_debito_banco . 
$select_cliente_uf . 
$select_consultor . 
$select_unidade . 
$select_turno . 
$select_empregador . 
$select_matricula . 
$select_pgto .
$select_apolice_tipo . 
$select_apolice;
$result = mysql_query("SELECT * FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id)
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" . 
$filtros_sql . 
" ORDER BY " . $ordem . " " . $ordenacao . " LIMIT 0, 500;") 
or die(mysql_error());
?>

 <?php  $curURL = $_SERVER["REQUEST_URI"]; ?>
<div align="center"><span style="color:#ff0000;"><strong>MAXIMO DE 500 RESULTADOS!!!</strong></span></div>
	    <div align="left">
	      
	  <table width="100%" border="2" align="center" cellpadding="0" cellspacing="1">
            <tbody>
		<tr class="cabecalho">
			<div align="left" class="style8">
			<td width="3%">#</td>
			<td width="25%">
				Cliente:<br>
				CPF:</td>
			<td width="12%">Apólice:</td>
			<td width="21%">
				Consultor:<br>
				Data da venda:
			</td>
			<td width="15%">Staus:</td>
			<td width="6%">Código:</td>
            </div>
		</tr>
<tr>
<table class="listaValores" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 2px solid #333;">
<tbody>
		  	      <?php
$totalclientes = 0;
$exibindo = 1;
$numero = $exibindo;
include("sistema/vendas/relatorios/lista_rel_tela.php");
$exibindo = $exibindo  - 1;

	echo "<tr class='even'><div align='left'>";
	echo "<td colspan='7'>Resultados totais de todos os resultados da Pesquisa:</br><div align='center'>";
	echo "<table width='85%'>";	

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
	echo "<td><strong>Totais:</strong></td>";
	echo "</tr>";	
	echo "<tr>";
	echo "<td>"; 	
	echo "Valores das Apólices: <strong>R$ ".$total_valor."</strong></br>";

	echo "</div>";	
	echo "</td>";		
	echo "</tr>";	
	echo "</table>";	
	
	echo "<tr class='even'><div align='left'>";
	echo "<td colspan='7'><div align='center'>";
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