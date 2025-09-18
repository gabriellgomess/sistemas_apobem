<?php
$vendas_id=$_GET["vendas_id"];
$result = mysql_query("SELECT vendas_status, vendas_user, clients_cpf FROM sys_vendas WHERE vendas_id = " . $vendas_id . ";") 
or die(mysql_error());  
$row = mysql_fetch_array( $result );
$vendas_status = $row["vendas_status"];
$vendas_user = $row["vendas_user"];
$result_auditor = mysql_query("SELECT name, ramal FROM jos_users WHERE username = '" . $vendas_user . "';") 
or die(mysql_error());
$row_auditor = mysql_fetch_array( $result_auditor );
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
<div align="center">Venda: <strong><?php echo $_GET["vendas_id"];?>, CONFERIDA COM SUCESSO</strong>, por <?php echo $row_auditor["name"];?>!</br></br>
<strong>Transação concluída!</strong>.</br>
<img src="sistema/imagens/ok.png"></br>
Ir para:</br>
<a href="index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda&vendas_id=<?php echo $_GET["vendas_id"];?>" target="_parent"><button class="button validate png" type="button">Venda</button></a> 
<a href="index.php" target="_parent"><button class="button validate png" type="button">Início</button></a>
<?php if($_GET["clients_employer"] == "Exercito"): ?>
	<a href="index.php?option=com_k2&view=item&layout=item&id=62&Itemid=272&acao=edita_cliente&campanha=1&cpf=<?php echo $row["clients_cpf"];?>" target="_parent"><button class="button validate png" type="button">Ficha do cliente</button></a></br>
<?php else: ?>
	<a href="index.php?option=com_k2&view=item&layout=item&id=62&Itemid=272&acao=edita_cliente_inss&campanha=1&cpf=<?php echo $row["clients_cpf"];?>" target="_parent"><button class="button validate png" type="button">Ficha do cliente</button></a></br>
<?php endif; ?>
<br/>
</div>