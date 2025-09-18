<?php 
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

include("../../utf8.php");
include("../../connect.php");
$filtros_vendas = " AND vendas_dia_ativacao <= '".substr($_GET['mes'], 3, 4)."-".substr($_GET['mes'], 0, 2)."-31'";
$filtros_transacoes = " AND transacao_data >= '".substr($_GET['mes'], 3, 4)."-".substr($_GET['mes'], 0, 2)."-01' AND transacao_data <= '".substr($_GET['mes'], 3, 4)."-".substr($_GET['mes'], 0, 2)."-31'";

$result = mysql_query("SELECT cliente_nome, 
sys_vendas_seguros.cliente_cpf, 
vendas_id, 
status_nm,
apolice_nome, 
vendas_valor, 
vendas_dia_venda 
FROM `sys_vendas_seguros` 
INNER JOIN sys_vendas_status_seg ON sys_vendas_seguros.vendas_status = sys_vendas_status_seg.status_id 
INNER JOIN sys_vendas_apolices ON sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id 
LEFT JOIN sys_inss_clientes ON sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf 
WHERE `vendas_pgto` = 2 
AND `vendas_banco` = 11 
AND `vendas_status` = ".$_GET['vendas_status'].$filtros_vendas." ORDER BY vendas_id DESC;") 
or die(mysql_error());

 ?>
<table border="1" align="center" cellpadding="0" cellspacing="1">
	<tr>
		<td>NOME</td>
		<td>CPF</td>
		<td>VENDA</td>
		<td>STATUS</td>
		<td>APOLICE</td>
		<td>VALOR</td>
		<td>DATA VENDA</td>
	</tr>
	<?php while($row = mysql_fetch_array( $result )): ?>
		<tr>
			<td><?php echo $row["cliente_nome"]; ?></td>
			<td><?php echo $row["cliente_cpf"]; ?></td>
			<td><?php echo $row["vendas_id"]; ?></td>
			<td><?php echo $row["status_nm"]; ?></td>
			<td><?php echo $row["apolice_nome"]; ?></td>
			<td><?php echo $row["vendas_valor"]; ?></td>
			<td><?php echo $row["vendas_dia_venda"]; ?></td>
		</tr>
	<?php endwhile; ?>
</table>