<?php
date_default_timezone_set('America/Sao_Paulo');
include("../../connect.php");
$cpf=$_GET["cpf"];
$clients_cat=$_GET["clients_cat"];

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
}else{
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

$select_bank = "";
if($_GET['vendas_banco'])
{
	$select_bank= " AND vendas_banco = '".$_GET['vendas_banco']."'";
}


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

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="vendas_id";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="DESC";}
if ($_GET["ordenacao"] == "ASC"){$link_ordem = "DESC";}else{$link_ordem = "ASC";}

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
$select_apolice_tipo .
$select_apolice;
$result = mysql_query("SELECT * FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" . 
$filtros_sql.$select_num_vendas." ORDER BY " . $ordem . " " . $ordenacao . " LIMIT 0, 5000;") 
or die(mysql_error());

if($_GET['teste']){
	echo "SELECT * FROM sys_vendas_seguros 
			LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
			LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
			WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" . 
			$filtros_sql.$select_num_vendas." ORDER BY " . $ordem . " " . $ordenacao . " LIMIT 0, 5000;";

die();
}

$agora = date("Ymd_His");
$nome_arquivo = "RelatorioAPOBEM_".$agora;

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
	      
	  <table border="1" align="center" cellpadding="0" cellspacing="1">
            <tbody>
		<tr><td colspan="9"><span style="color:#ff0000;"><strong>MAXIMO DE 5000 RESULTADOS!!!</strong></span></td></tr>
		<tr>
			<div align="left">
			<td>NOME COMPLETO (SEM ACENTOS)</td>
			<td>CPF</td>
			<td>SEXO</td>
			<td>NASCIMENTO</td>
			<td>ENDEREÇO(RUA,Nº, COMPLEMENTO)</td>
			<td>MUNICÍPIO</td>
			<td>CEP:</td>
			<td>PROFISSÃO:</td>
			<td>(DDD)TELEFONE</td>
			<td>PPE (SIM OU NÃO)</td>
			<td>FAIXA DE RENDA MENSAL</td>
			<td>INCLUSÃO</td>
			<td>EXCLUSÃO</td>
			<td>ALTERAÇÃO</td>
            </div>
		</tr>
		  	      <?php
$totalclientes = 0;
$exibindo = 1;
$numero = $exibindo;

while($row = mysql_fetch_array( $result )) {
$endereco_link = "#";

$vendas_valor = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0' ;

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
	echo "<td>".$row['cliente_sexo']."</td>";
	echo "<td>".$row['cliente_nascimento']."</td>";
	echo "<td>".$row['cliente_endereco']."</td>";
	echo "<td>".$row['cliente_cidade']."</td>";
	echo "<td>".$row['cliente_cep']."</td>";
	echo "<td>".$row['cliente_cargo']."</td>";
	echo "<td>".$row['vendas_telefone']."</td>";
	echo "<td>NAO</td>";
	echo "<td>".$row['cliente_salario']."</td>";
	echo "<td>R$ ".$vendas_valor."</td>";
	echo "<td>&nbsp;</td>";
	echo "<td>&nbsp;</td>";
	echo "</tr>"; 
$exibindo = $exibindo + 1;
$numero = $numero + 1;
}

$exibindo = $exibindo  - 1;

	echo "<tr><div align='left'>";
	echo "<td colspan='9'>Resultados totais de todos os resultados da Pesquisa:</br><div align='center'>";
	echo "<table>";
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