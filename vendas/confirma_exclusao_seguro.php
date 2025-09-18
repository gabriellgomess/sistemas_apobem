<?php
$vendas_id=$_GET["vendas_id"];
?>
<?php
include("../connect.php");
	
	$result = mysql_query("SELECT * FROM sys_vendas_seguros WHERE vendas_id=$vendas_id") or die(mysql_error());
	
	$row_venda = mysql_fetch_assoc($result);
	$rows = $row_venda;
	$dados_venda = json_encode($rows);

	$insert = "INSERT INTO sys_vendas_excluidas ( 
	`venda_dados`, 
	`venda_id`, 
	`sys_tabela`) 
	VALUES (
	'$dados_venda',
	'$vendas_id',
	'sys_vendas_seguros');";
	$query = mysql_query($insert,$con) or die(mysql_error());
	
$query = mysql_query("DELETE FROM sys_vendas_seguros WHERE vendas_id=$vendas_id") or die(mysql_error());
echo "</br></br></br></br></br><div align='center'>Venda EXCLUÍDA com Sucesso!</div>";

mysql_close($con)
?> 