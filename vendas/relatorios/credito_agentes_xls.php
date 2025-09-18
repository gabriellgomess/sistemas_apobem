<?php
date_default_timezone_set('America/Sao_Paulo');
include("../../connect.php");

$user_id = $_GET['user_id'];
$user_unidade = $_GET['user_unidade'];

include("sistema/utf8.php");

$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '10'){$administracao = 1;}
	if (($row_grupo_user['id'] == '18')||($row_grupo_user['id'] == '63')){
		$diretoria = 1;
		$administracao = 1;
		$sup_operacional = 1;
	}
	if ($row_grupo_user['id'] == '28'){$financeiro = 1;}
	if ($row_grupo_user['id'] == '21'){$franquiado = 1;}
	if (($row_grupo_user['id'] == '11')||($row_grupo_user['id'] == '30')){$sup_operacional = 1;}
	if ($row_grupo_user['id'] == '23'){$frame_revisadas = 1;}
	if ($row_grupo_user['id'] == '24'){$frame_averbadas = 1;}
	if ($row_grupo_user['id'] == '25'){$frame_fisicos = 1;}
	if ($row_grupo_user['id'] == '39'){$exclusao_vendas = 1;}
	if ($row_grupo_user['id'] == '34'){$supervisor_operacional_agentes = 1;}
	if ($row_grupo_user['id'] == '68'){$representante_comercial_seguros = 1;}
}

include("../filtros_sql_agentes.php");

$result = mysql_query("SELECT * FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
INNER JOIN sys_vendas_tipos ON (sys_vendas.vendas_tipo_contrato = sys_vendas_tipos.tipo_id)
WHERE " . $filtros_sql . $select_nome . " ORDER BY " . $ordem . " " . $ordenacao . ";") 
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
			<td>TIPO DE CONTRATO:</td>			
			<td>VALOR:</td>
			<td>VENDEDOR:</td>
			<td>DATA DA VENDA:</td>
			<td>STATUS:</td>
			<td>CODIGO:</td>
			<td>BANCO VENDA:</td>
			<td>DATA PAGTO:</td>
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
			<td>ULTIMA OBSERVACAO:</td>
			<td>DATA DA OBS:</td>
			<td>USUARIO:</td>
			
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
		echo "<td>".$row['contagem']."</td>";
	}
	echo "<td>".$row['vendas_telefone']."</td>";
	echo "<td>".$row['vendas_telefone2']."</td>";
	echo "<td>".$row['cliente_beneficio']."</td>";
	echo "<td>".$row['cliente_pagamento']."</td>";
	echo "<td>".$row['tipo_nome']."</td>";	
	$vendas_valor = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0' ;
	echo "<td>R$ ".$vendas_valor."</td>";
	echo "<td>".$row_user['name']."<br />";
	echo "<td>".$data_venda."</td>"; 
	echo "<td>".$row_status['status_nm']."</td>"; 
	echo "<td><div align='right'><strong>{$row['vendas_id']}</strong></div></td>";
	echo "<td>".$row['vendas_banco']."</td>";
	echo "<td>".$row['vendas_dia_pago']."</td>";
	echo "<td>".$row['vendas_proposta']."</td>";
	echo "<td>".$row['vendas_portabilidade']."</td>";
	echo "<td>".$row['cliente_rg']."</td>";
	echo "<td>".$row['cliente_rg_exp']."</td>";
	echo "<td>".$row['cliente_rg_dt']."</td>";
	echo "<td>".$row['cliente_nascimento']."</td>";
	echo "<td>".$row['cliente_banco']."</td>";
	echo "<td>".$row['cliente_agencia']."</td>";
	echo "<td>".$row['cliente_conta']."</td>";
	echo "<td>".$row['vendas_orgao']."</td>";
	echo "<td>".$row['cliente_orgao']."</td>";
	echo "<td>".$row['cliente_uf']."</td>";
	echo "<td>".$row['cliente_cep']."</td>";
	echo "<td>".$row['cliente_cidade']."</td>";
	echo "<td>".$row['cliente_bairro']."</td>";
	echo "<td>".$row['cliente_endereco']."</td>";
	
	$result_registros = mysql_query("SELECT registro_obs, registro_data, registro_usuario FROM sys_vendas_registros WHERE vendas_id = '" . $row['vendas_id'] . "' ORDER BY registro_data DESC LIMIT 0, 1;")
	or die(mysql_error());
	$row_registros = mysql_fetch_array( $result_registros );
	
	echo "<td>".$row_registros['registro_obs']."</td>";
	echo "<td>".$row_registros['registro_data']."</td>";
	echo "<td>".$row_registros['registro_usuario']."</td>";
	
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
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE " . $filtros_sql . $select_nome . ";")
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

$sql_select_all = mysql_query("SELECT COUNT(vendas_id) FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE " . $filtros_sql . $select_nome . ";")
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
