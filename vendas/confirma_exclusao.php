<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt-br" xml:lang="pt-br" xmlns:og="http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" slick-uniqueid="1">
<head>
<meta content="IE=9" http-equiv="X-UA-Compatible">
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<meta content="index, follow" name="robots">
<meta content="/sistema" name="image">
<meta content=" " name="description">
<title>Excluir Venda</title>
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
<?php
$vendas_id=$_GET["vendas_id"];
$registro_ip = $_SERVER['REMOTE_ADDR'];
$user =& JFactory::getUser();
$username=$user->username;
$userid=$user->id;
$registro_data = date("Y-m-d H:i:s");
$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $userid . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '39'){$exclusao_vendas = 1;}
}
$result_url = mysql_query("SELECT url_consulta_clientes FROM jos_users WHERE id = " . $userid . ";") 
or die(mysql_error());  
$row_url = mysql_fetch_array( $result_url );
$link_consulta = $row_url["url_consulta_clientes"];
?>

<?php
if ($exclusao_vendas == 1){
	include("../connect.php");

	$sql = "INSERT INTO `sistema`.`sys_vendas_registros` (`registro_id`, 
	`vendas_id`, 
	`registro_usuario`, 
	`registro_obs`, 
	`registro_data`, 
	`registro_ip`, 
	`registro_tipo`) 
	VALUES (NULL, 
	'$vendas_id',
	'$username',
	'Exclusao da venda',
	'$registro_data',
	'$registro_ip',
	'2');"; 
	if (mysql_query($sql,$con)){
		$acionamento_id = mysql_insert_id();
		echo "Histórico Registrado com Sucesso. </br>";
	} else {
		die('Error: ' . mysql_error());
	}
	
	$result = mysql_query("SELECT * FROM sys_vendas WHERE vendas_id=$vendas_id") or die(mysql_error());
	
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
	'sys_vendas');";

	$query = mysql_query($insert,$con) or die(mysql_error());
	

	$query = mysql_query("DELETE FROM sys_vendas WHERE vendas_id=$vendas_id") or die(mysql_error());
	echo "<div align='center'>Venda EXCLUÍDA com Sucesso!</div>";
	
	$query = mysql_query("DELETE FROM sys_vendas_cms WHERE vendas_id=$vendas_id;") or die(mysql_error());

	mysql_close($con);
}else{echo "<div align='center'>ACESSO NEGADO!</div>";}
?>
<a href="<?php echo $link_consulta;?>" target="_parent"><button class="button validate png" type="button">CONCLUIR</button></a>