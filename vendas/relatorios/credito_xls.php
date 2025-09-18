<?php
date_default_timezone_set('America/Sao_Paulo');
include("../../connect.php");
include("../../utf8.php");

$cpf=$_GET["cpf"];

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="sys_vendas.vendas_id";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="DESC";}

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

if ($_GET["vendas_banco_compra"]) {
	$join_banco_compra = " INNER JOIN sys_vendas_compras ON sys_vendas.vendas_id = sys_vendas_compras.vendas_id";
} else {
	$join_banco_compra = "";
}

$join_unidade= " INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id";


$sql = "SELECT *,sys_vendas.vendas_id AS vendas_id,  vendas_bancos_nome AS cartao_consig_banco, sys_vendas_clientes.cliente_telefone AS fone1, sys_vendas_clientes.cliente_celular AS fone2  " . $contagem . " FROM sys_vendas 
LEFT JOIN sys_vendas_bancos ON sys_vendas.vendas_cartao_consig = sys_vendas_bancos.vendas_bancos_id
LEFT JOIN sys_vendas_clientes ON sys_vendas.vendas_id = sys_vendas_clientes.vendas_id
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".
$join_unidade.$join_tabela.$join_banco_compra.
" WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$filtros_sql . 
$agrupamento." ORDER BY " . $ordem . " " . $ordenacao . " LIMIT 0, 5000;";

$result = mysql_query($sql) 
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
			<td>VENDAS BANCO:</td>
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
	echo "<td><div align='right'><strong>{$row['vendas_banco']}</strong></div></td>";
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