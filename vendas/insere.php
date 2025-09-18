<?php
$incompleto = 0;
$clients_cpf=$_GET["clients_cpf"];
$user =& JFactory::getUser();
$vendas_user=$user->username;
$user_id=$user->id;
$user_unidade=$user->unidade;

if(strpos($user_unidade,"PLATAFORMA") !== false)
{
	$vendas_contrato_fisico = "101";
}else{
	$vendas_contrato_fisico = "100";
}
$vendas_contrato_fisico = "100";

include("sistema/cliente/espelha_confere.php");

if ($row_espelha_confere["total"]){

	include("sistema/connect_db02.php");
	include("sistema/utf8.php");

	include("sistema/cliente/espelha_existente.php");

	include("sistema/connect.php");
	include("sistema/utf8.php");
	
	include("sistema/cliente/espelha_atualiza.php");
}else{

	include("sistema/connect_db02.php");
	include("sistema/utf8.php");

	include("sistema/cliente/espelha.php");

	include("sistema/connect.php");
	include("sistema/utf8.php");
	
	include("sistema/cliente/espelha_insere.php");
}

$vendas_consultor=$_GET["vendas_consultor"];
if ($_GET["vendas_banco"]){$vendas_banco=$_GET["vendas_banco"];}else{$incompleto = 1; $campo_vazio = " - Banco. ";}
if ($_GET["vendas_tipo_contrato"]){$vendas_tipo_contrato=$_GET["vendas_tipo_contrato"];}else{$incompleto = 1; $campo_vazio = "<br /> - Tipo de Contrato. ";}
if ($_GET["vendas_tabela"]){$vendas_tabela=$_GET["vendas_tabela"];}else{$incompleto = 1; $campo_vazio = "<br /> - Tabela. ";}
if ($_GET["vendas_orgao"]){$vendas_orgao=$_GET["vendas_orgao"];}else{$incompleto = 1; $campo_vazio = "<br /> - Órgão. ";}
if ($_GET["vendas_produto"]){$vendas_produto=$_GET["vendas_produto"];}else{$vendas_produto="1";}
if ($_GET["vendas_cartao_consig"]){$vendas_cartao_consig = $_GET['vendas_cartao_consig'];}else{$vendas_cartao_consig = 0;}

if ($_GET["vendas_valor"] > 0){
	$vendas_valor=$_GET["vendas_valor"];
	if(strpos($vendas_valor,".")){$vendas_valor=substr_replace($vendas_valor, '', strpos($vendas_valor, "."), 1);}
	if(!strpos($vendas_valor,".")&&(strpos($vendas_valor,","))){$vendas_valor=substr_replace($vendas_valor, '.', strpos($vendas_valor, ","), 1);}
}else{$incompleto = 1; $campo_vazio = "<br /> - Valor do AF. ";}

if ($_GET["vendas_percelas"]){$vendas_percelas=$_GET["vendas_percelas"];}else{$incompleto = 1; $campo_vazio = "<br /> - Quantidade de Parcelas. ";}
$vendas_valor_parcela=$_GET["vendas_valor_parcela"];
if(strpos($vendas_valor_parcela,".")){$vendas_valor_parcela=substr_replace($vendas_valor_parcela, '', strpos($vendas_valor_parcela, "."), 1);}
if(!strpos($vendas_valor_parcela,".")&&(strpos($vendas_valor_parcela,","))){$vendas_valor_parcela=substr_replace($vendas_valor_parcela, '.', strpos($vendas_valor_parcela, ","), 1);}
$vendas_margem=$_GET["vendas_margem"];
if(strpos($vendas_margem,".")){$vendas_margem=substr_replace($vendas_margem, '', strpos($vendas_margem, "."), 1);}
if(!strpos($vendas_margem,".")&&(strpos($vendas_margem,","))){$vendas_margem=substr_replace($vendas_margem, '.', strpos($vendas_margem, ","), 1);}

if ($_GET["vendas_liquido"]){
	$vendas_liquido=$_GET["vendas_liquido"];
	if(strpos($vendas_liquido,".")){$vendas_liquido=substr_replace($vendas_liquido, '', strpos($vendas_liquido, "."), 1);}
	if(!strpos($vendas_liquido,".")&&(strpos($vendas_liquido,","))){$vendas_liquido=substr_replace($vendas_liquido, '.', strpos($vendas_liquido, ","), 1);}
}elseif ($vendas_tipo_contrato == "2" || $vendas_tipo_contrato == "17"){$incompleto = 1; $campo_vazio = "<br /> - Líquido. ";}

$vendas_coeficiente=$_GET["vendas_coeficiente"];
if ($_GET["vendas_tabela"]){$vendas_tabela=$_GET["vendas_tabela"];}else{$incompleto = 1; $campo_vazio = "<br /> - Tabela. ";}
$vendas_applus_ben=$_GET["vendas_applus_ben"];
$vendas_applus_parent=$_GET["vendas_applus_parent"];
$vendas_applus_valor=$_GET["vendas_applus_valor"];
$vendas_estoque=$_GET["vendas_estoque"];
$vendas_origem=$_GET["vendas_origem"];
$vendas_seguro_protegido=$_GET["vendas_seguro_protegido"];
if(strpos($vendas_seguro_protegido,".")){$vendas_seguro_protegido=substr_replace($vendas_seguro_protegido, '', strpos($vendas_seguro_protegido, "."), 1);}
if(!strpos($vendas_seguro_protegido,".")&&(strpos($vendas_seguro_protegido,","))){$vendas_seguro_protegido=substr_replace($vendas_seguro_protegido, '.', strpos($vendas_seguro_protegido, ","), 1);}
$vendas_jud=$_GET['vendas_jud'];

$vendas_obs=$_GET["vendas_obs"];
$vendas_compra_venc1 = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));
$vendas_dia_venda = date("Y-m-d H:i:s");

if (($vendas_tipo_contrato == "2") || ($vendas_tipo_contrato == "3") || ($vendas_tipo_contrato == "4") || ($vendas_tipo_contrato == "5") || ($vendas_tipo_contrato == "9") || ($vendas_tipo_contrato == "14") || ($vendas_tipo_contrato == "15") || ($vendas_tipo_contrato == "17") || ($vendas_tipo_contrato == "20") )
{
	if ($_GET["compra_banco1"] == ""){$incompleto = 1; $campo_vazio = $campo_vazio."<br />'compra_banco1";}
	for ($i = 1; $i <= 10; $i++) {
		if ($_GET["compra_banco".$i]){
			if ($_GET["compra_valor".$i] == ""){$incompleto = 1; $campo_vazio = $campo_vazio."', 'compra_valor".$i;}
			if ($_GET["compra_saldo".$i] == ""){$incompleto = 1; $campo_vazio = $campo_vazio."', 'compra_saldo".$i;}
			if ($_GET["compra_parcelas".$i] == ""){$incompleto = 1; $campo_vazio = $campo_vazio."', 'compra_parcelas".$i."'";}
			
			$compra_saldo = $_GET["compra_saldo".$i];
			if(strpos($compra_saldo,".")){$compra_saldo=substr_replace($compra_saldo, '', strpos($compra_saldo, "."), 1);}
			if(!strpos($compra_saldo,".")&&(strpos($compra_saldo,","))){$compra_saldo=substr_replace($compra_saldo, '.', strpos($compra_saldo, ","), 1);}
			$vendas_portabilidade_saldo = $vendas_portabilidade_saldo + $compra_saldo;
			
			$result_banco_compra = mysql_query("SELECT banco_nome FROM sys_vendas_bancos_compra 
			WHERE banco_id = '".$_GET['compra_banco'.$i]."';")
			or die(mysql_error());
			$row_banco_compra = mysql_fetch_array( $result_banco_compra );
			if ($row_banco_compra["banco_nome"] != $_GET['compra_banco'.$i]){$vendas_portabilidade_saldo_outros = $vendas_portabilidade_saldo_outros + $compra_saldo;}
		}
	}
}
?>
<?php if ($incompleto == 1) : ?>
<div align="center">
<span style="color:red;"><strong>Campo(s) <?php echo $campo_vazio; ?>, não preenchido(s) corretamente!</strong></span><br /><br />
<button class="button validate png" style="float: none;" onclick="history.go(-1)" type="button">Voltar</button>
</div>

<?php else: ?>

<?php
include("../connect.php");

$result_user = mysql_query("SELECT username, unidade, equipe_id, situacao, nivel, empresa FROM jos_users WHERE id = " . $vendas_consultor . ";") 
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );
$vendas_unidade = $row_user["unidade"];
$vendas_usuario = $row_user["username"];
$vendas_equipe = $row_user["equipe_id"];
$consultor_situacao = $row_user["situacao"];
$consultor_nivel = $row_user["nivel"];
$consultor_empresa = $row_user["empresa"];

$dia = date("d");
$tabela_dia = "tabela_dia_".$dia;
$result_tabela = mysql_query("SELECT tabela_id, 
tabela_base, 
tabela_coeficiente_base2, 
tabela_base_contrato, 
tabela_perc_base_prod, 
tabela_cms_consultor_interno, 
tabela_juros, 
tabela_juros_liquido, 
tabela_juros_fr, 
tabela_cms_pmt, 
tabela_cms_saldo, 
tabela_bonus, 
vendas_banco_fortcoins, 
tabela_fortcoins, 
tabela_tipo,".$tabela_dia." FROM sys_vendas_tabelas 
INNER JOIN sys_vendas_bancos ON sys_vendas_tabelas.tabela_banco = sys_vendas_bancos.vendas_bancos_id 
WHERE tabela_id = '".$vendas_tabela."';") 
or die(mysql_error());
$row_tabela = mysql_fetch_array( $result_tabela );
if ($row_tabela["tabela_id"]){
	$vendas_juros = $row_tabela['tabela_juros'];
	$vendas_juros_fr = $row_tabela['tabela_juros_fr'];
	$vendas_pmt = $row_tabela['tabela_cms_pmt'];
	$vendas_cms_saldo = $row_tabela['tabela_cms_saldo'];
	$vendas_bonus = $row_tabela['tabela_bonus'];
	if ($row_tabela['tabela_coeficiente_base2']){$coeficiente_base = $row_tabela['tabela_coeficiente_base2'];}else{$coeficiente_base = 5.0;}
	if (($vendas_tipo_contrato == "6") || ($vendas_tipo_contrato == "10")){
		$vendas_receita_plastico = $row_tabela['tabela_cms_plastico'];
		$vendas_receita_ativacao = $row_tabela['tabela_cms_ativacao'];
	}
	
	// ### CALCULO AUTOMATICO DE BASE DE PRODUÇÃO E CMS DO VENDEDOR! ###
	$venda_nova = 1;
	include("sistema/vendas/calcula_base.php");

}

$result_acionamentos = mysql_query("SELECT COUNT(acionamento_id) AS total FROM sys_acionamentos WHERE clients_cpf = '" . $cpf . "' AND acionamento_usuario = '".$vendas_usuario."';")or die(mysql_error());
$row_acionamentos = mysql_fetch_array( $result_acionamentos );
if(!$row_acionamentos["total"]){$vendas_acionamentos = 2;}else{$vendas_acionamentos = 1;}

if ($vendas_produto == "2"){$vendas_status="13";}else{$vendas_status="100";}

//$vendas_status="1";

$sql = "INSERT INTO `sistema`.`sys_vendas` (`vendas_id`, 
`clients_cpf`, 
`vendas_consultor`, 
`vendas_acionamentos`, 
`vendas_banco`, 
`vendas_produto`, 
`vendas_cartao_consig`,
`vendas_orgao`, 
`vendas_tipo_contrato`,
`vendas_valor`, 
`vendas_percelas`, 
`vendas_valor_parcela`, 
`vendas_margem`,
`vendas_liquido`,
`vendas_coeficiente`,
`vendas_dia_venda`,
`vendas_tabela`, 
`vendas_juros`, 
`vendas_juros_fr`, 
`vendas_pmt`, 
`vendas_cms_saldo`, 
`vendas_bonus`, 
`vendas_receita_plastico`, 
`vendas_receita_ativacao`, 
`vendas_base`, 
`vendas_base_contrato`, 
`vendas_base_prod`, 
`vendas_comissao_vendedor_perc`, 
`vendas_comissao_vendedor`, 
`vendas_fortcoins`, 
`vendas_applus_ben`, 
`vendas_applus_parent`, 
`vendas_applus_valor`, 
`vendas_estoque`, 
`vendas_origem`, 
`vendas_seguro_protegido`, 
`vendas_jud`, 
`vendas_status`, 
`vendas_user`, 
`vendas_unidade`, 
`vendas_equipe`, 
`vendas_obs`,
`vendas_contrato_fisico`) 
VALUES (NULL, 
'$clients_cpf',
'$vendas_consultor',
'$vendas_acionamentos',
'$vendas_banco',
'$vendas_produto',
'$vendas_cartao_consig',
'$vendas_orgao',
'$vendas_tipo_contrato',
'$vendas_valor',
'$vendas_percelas',
'$vendas_valor_parcela',
'$vendas_margem',
'$vendas_liquido',
'$vendas_coeficiente',
'$vendas_dia_venda',
'$vendas_tabela',
'$vendas_juros',
'$vendas_juros_fr',
'$vendas_pmt',
'$vendas_cms_saldo',
'$vendas_bonus',
'$vendas_receita_plastico',
'$vendas_receita_ativacao',
'$vendas_base',
'$vendas_base_contrato',
'$vendas_base_prod',
'$vendas_comissao_vendedor_perc',
'$vendas_comissao_vendedor',
'$vendas_fortcoins',
'$vendas_applus_ben',
'$vendas_applus_parent',
'$vendas_applus_valor',
'$vendas_estoque',
'$vendas_origem',
'$vendas_seguro_protegido',
'$vendas_jud',
'$vendas_status',
'$vendas_user',
'$vendas_unidade',
'$vendas_equipe',
'$vendas_obs',
'$vendas_contrato_fisico');";
if (mysql_query($sql,$con)){
	$vendas_id = mysql_insert_id();

	adicionaAnexosDoClienteNaVendaConsignado($vendas_id, $clients_cpf, $vendas_user);

	echo "Venda de CONSIGNADO Cadastrada com Sucesso. </br>";
} else {
	die('Error insert sys_vendas: ' . mysql_error());
}

// ### CALCULO AUTOMATICO DE BASE DE PRODUÇÃO E CMS DO VENDEDOR! ###
include("sistema/vendas/calcula_base.php");

if ($vendas_orgao != "Exercito"){
	$query = mysql_query("UPDATE sys_inss_clientes SET cliente_venda='1' WHERE cliente_cpf='$cliente_cpf' ") or die(mysql_error());
	echo "Cliente Atualizado com Sucesso <br/>";
}
$query = mysql_query("UPDATE sys_vendas_tabelas SET tabela_venda='2' WHERE tabela_id='$vendas_tabela' ") or die(mysql_error());
	echo "Tabela Atualizada com Sucesso <br/>";

if( $vendas_tipo_contrato == "2" || $vendas_tipo_contrato == "3" || $vendas_tipo_contrato == "4" || $vendas_tipo_contrato == "5" || $vendas_tipo_contrato == "9" || $vendas_tipo_contrato == "14" || $vendas_tipo_contrato == "15" || $vendas_tipo_contrato == "17" || $vendas_tipo_contrato == "20"){
	for ($i = 1; $i <= 10; $i++) {
		if ($_GET["compra_banco".$i]){
			$compra_banco = $_GET["compra_banco".$i];
			$compra_contrato = $_GET["compra_contrato".$i];
			$compra_valor = $_GET["compra_valor".$i];
			if(strpos($compra_valor,".")){$compra_valor=substr_replace($compra_valor, '', strpos($compra_valor, "."), 1);}
			if(!strpos($compra_valor,".")&&(strpos($compra_valor,","))){$compra_valor=substr_replace($compra_valor, '.', strpos($compra_valor, ","), 1);}
			$compra_saldo = $_GET["compra_saldo".$i];
			if(strpos($compra_saldo,".")){$compra_saldo=substr_replace($compra_saldo, '', strpos($compra_saldo, "."), 1);}
			if(!strpos($compra_saldo,".")&&(strpos($compra_saldo,","))){$compra_saldo=substr_replace($compra_saldo, '.', strpos($compra_saldo, ","), 1);}
			$compra_prazo = $_GET["compra_prazo".$i];
			$compra_parcelas = $_GET["compra_parcelas".$i];
			$compra_venc = implode(preg_match("~\/~", $_GET["compra_venc".$i]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["compra_venc".$i]) == 0 ? "-" : "/", $_GET["compra_venc".$i])));
			
			$sql = "INSERT INTO `sistema`.`sys_vendas_compras` (`compra_id`, 
			`vendas_id`, 
			`compra_banco`, 
			`compra_contrato`, 
			`compra_valor`, 
			`compra_saldo`, 
			`compra_prazo`, 
			`compra_parcelas`, 
			`compra_venc`)
			VALUES (NULL, 
			'$vendas_id',
			'$compra_banco',
			'$compra_contrato',
			'$compra_valor',
			'$compra_saldo',
			'$compra_prazo',
			'$compra_parcelas',
			'$compra_venc');"; 
			if (mysql_query($sql,$con)){
			echo "Compra de Dívida Cadastrada com Sucesso. </br>";
			} else {
				die('Error insert sys_vendas_compras: ' . mysql_error());
			}
		}
	}
}

###  VENDAS DE SEGUROS:
if($_GET["vendas_banco1"]){
	for ($i = 1; $i <= 10; $i++) {
		if ($_GET["vendas_banco".$i]){
			$_GET["vendas_banco"] = $_GET["vendas_banco".$i];
			$_GET["vendas_apolice"] = $_GET["vendas_apolice".$i];
			$_GET["vendas_consultor"] = $_GET["vendas_consultor".$i];
			$_GET["vendas_dia_desconto"] = $_GET["vendas_dia_desconto".$i];
			$_GET["vendas_pgto"] = $_GET["vendas_pgto".$i];
			$_GET["vendas_cartao_adm"] = $_GET["vendas_cartao_adm".$i];
			$_GET["vendas_cartao_band"] = $_GET["vendas_cartao_band".$i];
			$_GET["vendas_cartao_num"] = $_GET["vendas_cartao_num".$i];
			$_GET["vendas_cartao_validade"] = $_GET["vendas_cartao_validade".$i];
			$_GET["vendas_cartao_cvv"] = $_GET["vendas_cartao_cvv".$i];
			$_GET["vendas_debito_banco"] = $_GET["vendas_debito_banco".$i];
			$_GET["vendas_debito_ag"] = $_GET["vendas_debito_ag".$i];
			$_GET["vendas_debito_cc"] = $_GET["vendas_debito_cc".$i];
			$_GET["vendas_debito_banco_2"] = $_GET["vendas_debito_banco_2".$i];
			$_GET["vendas_debito_ag_2"] = $_GET["vendas_debito_ag_2".$i];
			$_GET["vendas_debito_cc_2"] = $_GET["vendas_debito_cc_2".$i];
			$_GET["vendas_debito_banco_3"] = $_GET["vendas_debito_banco_3".$i];
			$_GET["vendas_debito_ag_3"] = $_GET["vendas_debito_ag_3".$i];
			$_GET["vendas_debito_cc_3"] = $_GET["vendas_debito_cc_3".$i];
			$_GET["vendas_obs"] = $_GET["vendas_obs".$i];
			
			for ($j = 1; $j <= 20; $j++) {
				$_GET["ben_nome".$j] = $_GET["ben_nome".$i."-".$j];
				$_GET["ben_nasc".$i] = $_GET["ben_nasc".$i."-".$j];
				$_GET["ben_parent".$i] = $_GET["ben_parent".$i."-".$j];
				$_GET["ben_perc".$i] = $_GET["ben_perc".$i."-".$j];
			}
			## Include insere_seguro.php
			include("sistema/vendas/insere_seguro.php");
		}
	}
}
###  FIM VENDAS DE SEGUROS:

mysql_close($con);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt-br" xml:lang="pt-br" xmlns="http://www.w3.org/1999/xhtml" slick-uniqueid="1">
<head>
<meta content="IE=9" http-equiv="X-UA-Compatible">
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<meta content="index, follow" name="robots">
<meta content="/sistema" name="image">
<meta content=" " name="description">
<title>Cadastro de Venda</title>
<link type="image/vnd.microsoft.icon" rel="shortcut icon" href="/sistema/templates/gk_music/images/favicon.ico"></link>
<link type="text/css" href="/sistema/media/system/css/modal.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/k2.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/menu.gkmenu.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/reset/meyer.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/layout.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/joomla.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/template.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/menu.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/gk.stuff.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/k2.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/typography.style3.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/typography.iconset.1.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/style3.css" rel="stylesheet"></link>
<script type="text/javascript" src="/sistema/media/system/js/core.js"></script>
<script type="text/javascript" src="/sistema/media/system/js/mootools-core.js"></script>
<script type="text/javascript" src="/sistema/media/system/js/mootools-more.js"></script>
<script type="text/javascript" src="/sistema/media/system/js/modal.js"></script>
<script type="text/javascript" src="/sistema/components/com_k2/js/k2.js"></script>
<script type="text/javascript" src="/sistema/templates/gk_music/js/menu.gkmenu.js"></script>
<script type="text/javascript" src="/sistema/templates/gk_music/js/gk.scripts.js"></script>
<script type="text/javascript"></script>
<script type="text/javascript"></script>
<script src="/sistema/plugins/system/azrul.system/pc_includes/ajax_1.5.pack.js" type="text/javascript"></script>
        <script type="text/javascript" src="./js/datepicker.js"></script>
        <link href="./css/demo.css"       rel="stylesheet" type="text/css" />
        <link href="./css/datepicker.css" rel="stylesheet" type="text/css" />
</head>
<div align="center">Cliente: <strong><?php echo $_GET["clients_nm"];?></strong></br>
Dados da Venda: </br>
CPF do Cliente: <strong><?php echo $clients_cpf;?></strong></br>
Orgão: <strong><?php echo $vendas_orgao;?></strong></br>
Banco: <strong><?php echo $vendas_banco;?></strong></br>
Valor: <strong><?php echo $_GET["vendas_valor"];?></strong></br>
Nº de Parcelas: <strong><?php echo $vendas_percelas;?></strong></br>
Status: <strong><?php echo $vendas_status;?></strong></br>
<br/>
<?php if($row_user["unidade"] == "Porto Alegre"): ?>
<meta http-equiv="Refresh" content="2; url=index.php?option=com_k2&view=item&id=64:cadastro-de-venda&Itemid=123&tmpl=component&print=1&acao=aguarda_bo&vendas_id=<?php echo $vendas_id;?>&vendas_orgao=<?php echo $vendas_orgao;?>">
</br>
<img src="sistema/imagens/calculando.gif">
</br>
<strong> ENVIANDO PARA BACKOFFICE! </strong></br>
<br/>
<?php else: ?>
<a href="index.php?option=com_k2&view=item&layout=item&id=64&Itemid=479" target="_parent"><button class="button validate png" type="button">Cadastrar nova Venda</button></a></br>
<?php endif; ?>
</div>
<?php endif; ?>

<?php 
function adicionaAnexosDoClienteNaVendaConsignado($vendas_id, $clients_cpf, $vendas_user)
{
	$sql = "SELECT anexo_id, anexo_cpf, anexo_nome, anexo_caminho, anexo_tipo, anexo_usuario, anexo_data, anexo_documento
			FROM sys_cliente_anexos WHERE anexo_cpf = '".$clients_cpf."' AND anexo_permissao != 2";
	$result = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($result))
	{	
		$insert_values = "";
		$addVirgula = 0;
		while ($row = mysql_fetch_assoc($result))
		{
			if ($addVirgula>0) {
				$insert_values .= ", ";
			}
			$addVirgula++;

			$insert_values .= "(";
			$insert_values .= "NULL ,'".$vendas_id."' , '".$row['anexo_nome']."' , '".$row['anexo_caminho']."' , '".$row['anexo_tipo']."' , '".$vendas_user."' , '".$row['anexo_data']."' , '".$row['anexo_documento']."'";
			$insert_values .= ")";
		}

		$sql_insert = "INSERT INTO sys_vendas_anexos (anexo_id, vendas_id, anexo_nome, anexo_caminho, anexo_tipo, anexo_usuario, anexo_data, anexo_documento)
					   VALUES ".$insert_values.";";

		if(mysql_query($sql_insert)){
			echo "Sucesso na inserção dos anexos do cliente para a venda. </br>";
		}else{
			die("Erro ao migrar anexos: ".mysql_error());
		}
	}
}
?>