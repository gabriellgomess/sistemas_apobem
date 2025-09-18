<?php
date_default_timezone_set('America/Sao_Paulo');
include("sistema/utf8.php");

$cpf=$_GET["cpf"];

if($_GET['filtros_sql'])
{
	$filtros_sql = $_GET['filtros_sql'];
}else
{	
	echo "Nenhum filtro foi definido.";
	die();
}

if ($_GET["vendas_tipo_tabela"]) {
	$join_tabela = " INNER JOIN sys_vendas_tabelas ON sys_vendas.vendas_tabela = sys_vendas_tabelas.tabela_id";
} else {
	$join_tabela = "";
}

$join_unidade= " INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id";

$sql = "SELECT vendas_orgao, vendas_banco, vendas_promotora, tipo_nome, SUM(vendas_receita) AS total_receita, SUM(vendas_comissao_vendedor) AS total_vendedor FROM sys_vendas 
INNER JOIN sys_vendas_tipos ON sys_vendas.vendas_tipo_contrato = sys_vendas_tipos.tipo_id ".
$join_unidade.$join_tabela.$join_banco_compra.
" WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$filtros_sql . 
"GROUP BY vendas_orgao, vendas_banco, vendas_promotora, vendas_tipo_contrato;";

$result = mysql_query($sql) 
or die(mysql_error());
$agora = date("Ymd_His");
$nome_arquivo = "RelatorioReceitas_".$agora;

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
		<tr>
			<div align="left">
			<td>ORGAO:</td>
			<td>BANCO:</td>
			<td>PROMOTORA:</td>
			<td>TIPO DE CONTRATO:</td>
			<td>RECEITA BRUTA:</td>
			<td>CMS VENDEDOR:</td>
            </div>
		</tr>
		  	      <?php
$totalclientes = 0;
$exibindo = 1;
$numero = $exibindo;

while($row = mysql_fetch_array( $result )) {
$endereco_link = "#";

	echo "<td>".$row['vendas_orgao']."</td>";
	echo "<td>".$row['vendas_banco']."</td>";
	echo "<td>".$row['vendas_promotora']."</td>";
	echo "<td>".$row['tipo_nome']."</td>";
	$total_receita = ($row['total_receita']>0) ? number_format($row['total_receita'], 2, ',', '.') : '0' ;
	echo "<td>R$ ".$total_receita."</td>";	
	$total_vendedor = ($row['total_vendedor']>0) ? number_format($row['total_vendedor'], 2, ',', '.') : '0' ;
	echo "<td>R$ ".$total_vendedor."</td>";	
	echo "</tr>"; 
$exibindo = $exibindo + 1;
$numero = $numero + 1;
}

$exibindo = $exibindo  - 1;

	echo "<tr><div align='left'>";
	echo "<td colspan='9'>Resultados totais de todos os resultados da Pesquisa:</br><div align='center'></tr>";
?>
</tbody>
</table>
<?php mysql_close($con); ?>