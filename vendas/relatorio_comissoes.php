<?php
$username=$_GET["username"];

if ($_GET["consultor_unidade"]){
$join_unidade= " INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id";
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
	$join_unidade="";
}

$vendas_consultor=$_GET["vendas_consultor"];

if ($_GET["vendas_mes"]){
$vendas_mes=$_GET["vendas_mes"];
				for ($i=0;$i<count($vendas_mes);$i++){
					if ($vendas_mes[$i] != ""){
						if ($i==0){
							$select_mes = " AND (vendas_mes = '" . $vendas_mes[$i] . "'";
							$select_mes_sextou = " AND (adiantamento_mes = '" . $vendas_mes[$i] . "'";
						}else{
							$select_mes = $select_mes." OR vendas_mes = '" . $vendas_mes[$i] . "'";
							$select_mes_sextou = $select_mes_sextou." OR adiantamento_mes = '" . $vendas_mes[$i] . "'";
						}					
					}
					$aux_stat = $i;
				}
				if ($vendas_mes[$aux_stat] != ""){
					$select_mes = $select_mes.")";
					$select_mes_sextou = $select_mes_sextou.")";
				}
				for ($i=0;$i<count($vendas_mes);$i++){
					if ($vendas_mes[$i] != ""){
							$pag_mes = $pag_mes."&vendas_mes[]=".$vendas_mes[$i];					
					}
				}
}

$select_grupo = " AND jos_user_usergroup_map.group_id = '31'";

$vendas_orgao=$_GET["vendas_orgao"];
if ($_GET["vendas_orgao"]) {$select_orgao= " AND vendas_orgao like '%" . $vendas_orgao . "%'";} else {$select_orgao="";}

$vendas_promotora=$_GET["vendas_promotora"];
if ($_GET["vendas_promotora"]) {$select_promotora= " AND vendas_promotora like '%" . $vendas_promotora . "%'";} else {$select_promotora="";}

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

$vendas_turno=$_GET["vendas_turno"];
if ($_GET["vendas_turno"]) {$select_turno= " AND vendas_turno = '" . $vendas_turno . "'";} else {$select_turno="";}

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="name";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="ASC";}

$user =& JFactory::getUser();
$user_id=$user->id;
include("sistema/utf8.php");


$sql_relatorio = "SELECT name, 
					username, 
					unidade, 
					situacao, 
					empresa, 
					unidade, 
					jos_users.equipe_id, 
					equipe_nome, 
					data_admissao, 
					data_contrato_90, 
					vendas_consultor, 
					SUM(vendas_fortcoins) AS total_fortcoins, 
					SUM(vendas_comissao_vendedor) AS total_comissao, 
					SUM(vendas_valor) AS total_valor, 
					SUM(vendas_receita) AS total_receita_vendedor 
					FROM sys_vendas 
					INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id 
					INNER JOIN jos_users_situacao ON jos_users.situacao = jos_users_situacao.situacao_id 
					INNER JOIN sys_equipes ON jos_users.equipe_id = sys_equipes.equipe_id 
					WHERE vendas_valor > 0 
					AND nivel != 4 AND nivel != 8 " . 
					$select_unidade . 
					$select_situacao . 
					$select_mes . 
					$select_status . 
					" GROUP BY vendas_consultor 
					ORDER BY " . $ordem . " " . $ordenacao . ";";

$result = mysql_query($sql_relatorio) or die(mysql_error());

$agora = date("Ymd_His");
$nome_arquivo = "RelatorioComissoes_".$agora;

if (($_GET["exportar"])||($_GET["processar"])){
	// Determina que o arquivo é uma planilha do Excel
	header("Content-type: application/vnd.ms-excel");   

	// Força o download do arquivo
	header("Content-type: application/force-download");  

	// Seta o nome do arquivo
	header("Content-Disposition: attachment; filename=".$nome_arquivo.".xls");

	// Imprime o conteúdo da nossa tabela no arquivo que será gerado
	header("Pragma: no-cache");
}
?>
<table style="width: 1900px;">
	<tr>
		<td>NOME:</td>
		<td>UNIDADE:</td>
		<td>EQUIPE:</td>
		<td>SITUACAO:</td>
		<td>DATA ADMISSAO:</td>
		<td>FC GATILHO:</td>
		<td>REGRA APLICADA:</td>
		<td>FORTCOINS:</td>
		<td>CMS %:</td>
		<td>COMISSAO:</td>
		<td>FC PENDENTE:</td>
		<td>COMISSAO PENDENTE:</td>
		<td>SEXTOU:</td>
		<td>CMS FINAL:</td>
		<td>RECEITA:</td>
	</tr>
<?php
$exibindo = 1;
include("sistema/vendas/lista_rel_comissoes.php");
$exibindo = $exibindo  - 1;
?>
	<tr><td><?php echo $exibindo;?> Consultores com vendas.</td></tr>
</table>
<div align="center">
	<a href="<?php echo $_SERVER["REQUEST_URI"]; ?>&exportar=1"><button name="exportar" type="button" value="exportar">Exportar para Excel</button></a> 
	<a href="<?php echo $_SERVER["REQUEST_URI"]; ?>&processar=1"><button name="processar" type="button" value="processar">Processar Comissionamento</button></a>
</div>
<?php mysql_close($con); ?>