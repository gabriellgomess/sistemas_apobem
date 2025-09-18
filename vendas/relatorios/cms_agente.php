<link href="templates/gk_music/css/template.portal.css" rel="stylesheet" type="text/css" />
<?php
include("sistema/utf8.php");
$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '10'){$administracao = 1;}
	if (($row_grupo_user['id'] == '18')||($row_grupo_user['id'] == '63')){$diretoria = 1;}
	if ($row_grupo_user['id'] == '28'){$financeiro = 1;}
	if ($row_grupo_user['id'] == '21'){$franquiado = 1;}
	if (($row_grupo_user['id'] == '11')||($row_grupo_user['id'] == '30')){$sup_operacional = 1;}
	if ($row_grupo_user['id'] == '23'){$frame_revisadas = 1;}
	if ($row_grupo_user['id'] == '24'){$frame_averbadas = 1;}
	if ($row_grupo_user['id'] == '25'){$frame_fisicos = 1;}
	if ($row_grupo_user['id'] == '39'){$exclusao_vendas = 1;}
	if ($row_grupo_user['id'] == '34'){$supervisor_operacional_agentes = 1;}
}

include("sistema/vendas/filtros_sql_agentes.php");

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="vendas_id";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="DESC";}
if ($_GET["ordenacao"] == "ASC"){
	$link_ordem = "DESC";
	$img_ordem = "<img src='sistema/imagens/asc.png'>";
}else{
	$link_ordem = "ASC";
	$img_ordem = "<img src='sistema/imagens/desc.png'>";
}
// filtro status de físico:" AND (vendas_contrato_fisico = '1' OR vendas_contrato_fisico = '2' OR vendas_contrato_fisico = '101')"
$filtros_sql = $filtros_sql . " AND vendas_pago_agente = '1'";
$result = mysql_query("SELECT vendas_id, 
sys_vendas.clients_cpf, 
clients_nm, 
cliente_nome, 
vendas_orgao, 
vendas_banco, 
vendas_valor, 
tipo_nome, 
name, 
vendas_dia_venda, 
vendas_dia_pago, 
vendas_mes, 
status_nm, 
vendas_contrato_fisico, 
vendas_mes, 
vendas_produto, 
vendas_status, 
vendas_comissao_vendedor FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
INNER JOIN sys_vendas_tipos ON (sys_vendas.vendas_tipo_contrato = sys_vendas_tipos.tipo_id) 
INNER JOIN sys_vendas_status ON (sys_vendas.vendas_status = sys_vendas_status.status_id) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE " . $filtros_sql . " ORDER BY " . $ordem . " " . $ordenacao . ";") 
or die(mysql_error());
?>

<div class="cabecalho-vendas">
	<div class="coluna" style="margin-left: 20px; width: 20%;">Cliente:<br>CPF:</div>
	<div class="coluna" style="width: 12%;">Órgão:<br>Banco:</div>
	<div class="coluna" style="width: 10%;">Valor:<br>Tipo:</div>
	<div class="coluna" style="width: 15%;">Data da venda:<br>Data pgto. | Mês:</div>
	<div class="coluna" style="width: 20%;">Status da Venda:<br>Status de Físico:</div>
	<div class="coluna" style="width: 15%;">Código:<br>CMS:</div>
</div>

<?php
$totalclientes = 0;
$fracionados_recebidos = 0;
$exibindo = 1;
$numero = $exibindo;
include("sistema/utf8.php");
include("sistema/vendas/relatorios/exibe_lista_cms_agente.php");
$exibindo = $exibindo  - 1;

// TOTAIS
$sql_select_total = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_comissao_vendedor) AS total_cms 
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE " . $filtros_sql . ";")
or die(mysql_error());
$row_total_valor = mysql_fetch_array( $sql_select_total );
$total_valor = ($row_total_valor['total_valor']>0) ? number_format($row_total_valor['total_valor'], 2, ',', '.') : '0' ;
//$total_cms = ($row_total_valor['total_cms']>0) ? number_format($row_total_valor['total_cms'], 2, ',', '.') : '0' ;

$total_cms_rs = ($total_cms>0) ? number_format($total_cms, 2, ',', '.') : '0' ;

echo "<div class='linha'>";
echo "Valores de AFs: <span style='font-weight: bold;' id='total_valor_tela' >R$ ".$total_valor."</span><br>";
echo "Total de CMS: <span style='font-weight: bold;' id='total_cms_tela' >R$ ".$total_cms_rs."</span>";
echo "</div>";
?>

<input type="hidden" name="total_valor" id='total_valor' value="<?php echo $row_total_valor['total_valor']; ?>">
<input type="hidden" name="total_cms" id='total_cms' value="<?php echo $total_cms; ?>">