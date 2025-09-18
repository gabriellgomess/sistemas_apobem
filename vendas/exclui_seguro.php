<?php
$vendas_id=$_GET["vendas_id"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt-br" xml:lang="pt-br" xmlns:og="http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" slick-uniqueid="1">
<head>
<meta content="IE=9" http-equiv="X-UA-Compatible">
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<meta content="index, follow" name="robots">
<meta content="/sistema" name="image">
<meta content=" " name="description">
<title>Excluir Venda de Seguro</title>
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
<table width="100%" height="99%" border="0" align="center" cellpadding="0" cellspacing="2" bgcolor="#eeeee0">
<tbody>
            <tr>
                <td width="32%" valign="top" class="style8" style="text-align: right;"><span class="style5">
                  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="2" bgcolor="#eeeee0">
    <tbody>
		        <tr>
            <td><div align="center">Tem certeza que quer <strong>EXCLUIR</strong> a venda de SEGURO nº: <strong><?php echo $_GET["vendas_id"];?></strong></br>
			Cliente: <?php echo $_GET["clients_nm"];?></div> </td>
        </tr>
		        <tr>
            <td class="style43"><span class="style47"><a href="index.php?option=com_k2&view=item&id=64:excluir-venda&Itemid=123&tmpl=component&print=1&vendas_id=<?php echo $_GET["vendas_id"];?>&acao=confirma_exclusao_seguro"><button class="button validate png" type="button">EXCLUIR DEFINITIVAMENTE</button></a></td>
        </tr>
    </tbody>
</table>
</td>
</tr>
</table>