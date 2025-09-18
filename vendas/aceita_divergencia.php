<link type="text/css" href="/sistema/templates/gk_music/css/layout.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/joomla.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/template.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/style3.css" rel="stylesheet"></link>
<div align="center">
<?php
$vendas_id=$_GET["vendas_id"];
$user =& JFactory::getUser();
$vendas_user=$user->username;
$vendas_alteracao = date("Y-m-d H:i:s");
?>
<?php if ($_GET["aceitar"] == "aceitar"):?>
<?php 
$result_venda = mysql_query("SELECT 
vendas_tipo_contrato, 
vendas_divergencia, 
vendas_base_imp, 
vendas_receita_imp, 
vendas_bonus_imp, 
vendas_impostos_perc_imp, 
vendas_impostos_perc_bonus_imp, 
vendas_impostos_perc, 
vendas_impostos_perc_bonus, 
vendas_receita_plastico, 
vendas_receita_ativacao, 
vendas_taxa, 
vendas_cip, 
vendas_consultor, 
vendas_comissao_vendedor_perc, 
vendas_cms_vendedor_plastico, 
vendas_cms_vendedor_saldo_perc, 
vendas_status 
FROM sys_vendas WHERE vendas_id = '" . $vendas_id . "';") 
or die(mysql_error());  
$row_venda = mysql_fetch_array( $result_venda );
$vendas_base_contrato = $row_venda["vendas_base_imp"];
$vendas_receita_bonus = $row_venda["vendas_bonus_imp"];
$vendas_receita_bruta = $row_venda["vendas_receita_imp"];
$vendas_impostos_perc = $row_venda["vendas_impostos_perc_imp"];
$vendas_impostos_perc_bonus = $row_venda["vendas_impostos_perc_bonus_imp"];
$vendas_cms = ($vendas_receita_bruta * 100) / $vendas_base_contrato;
$vendas_juros = round($vendas_cms, 3);
$vendas_cms_bonus = ($vendas_receita_bonus * 100) / $vendas_base_contrato;
$vendas_bonus = round($vendas_cms_bonus, 3);

if ($row_venda["vendas_tipo_contrato"] == "6"){
	$vendas_impostos = (($vendas_receita_bruta + $row_venda["vendas_receita_plastico"] + $row_venda["vendas_receita_ativacao"]) * $row_venda["vendas_impostos_perc_imp"]) / 100;
}else{
	$vendas_impostos_flat = (($vendas_receita_bruta * $row_venda["vendas_impostos_perc_imp"]) / 100);
	$vendas_impostos_bonus = (($vendas_receita_bonus * $row_venda["vendas_impostos_perc_bonus_imp"]) / 100);
	$vendas_impostos = $vendas_impostos_flat + $vendas_impostos_bonus;
}
$vendas_receita = $vendas_receita_bruta + $vendas_receita_bonus + $row_venda["vendas_receita_plastico"] + $row_venda["vendas_receita_ativacao"] - $row_venda["vendas_taxa"] - $vendas_impostos;
$vendas_obs="Aceitadas as divergências: ".$row_venda['vendas_divergencia'];

$result_user = mysql_query("SELECT unidade FROM jos_users WHERE id = '" . $row_venda['vendas_consultor'] . "';") 
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );
if ($row_venda["vendas_status"] != "9"){$update_vendas_unidade = ", vendas_unidade='".$row_user['unidade']."'";}

$vendas_cms_vendedor_flat = (($vendas_base_contrato * $row_venda["vendas_comissao_vendedor_perc"]) / 100) - $row_venda["vendas_taxa"] - $row_venda["vendas_cip"] + $row_venda["vendas_cms_vendedor_plastico"];
$vendas_cms_vendedor_saldo = ($row_venda["vendas_portabilidade_saldo"] * $row_venda["vendas_cms_vendedor_saldo_perc"]) / 100;
$vendas_comissao_vendedor = $vendas_cms_vendedor_flat + $vendas_cms_vendedor_saldo;

$query = mysql_query("UPDATE sys_vendas SET 
vendas_base_contrato='$vendas_base_contrato', 
vendas_juros='$vendas_juros', 
vendas_bonus='$vendas_bonus', 
vendas_receita_bruta='$vendas_receita_bruta', 
vendas_receita_bonus='$vendas_receita_bonus', 
vendas_receita='$vendas_receita', 
vendas_impostos='$vendas_impostos', 
vendas_impostos_perc='$vendas_impostos_perc', 
vendas_impostos_perc_bonus='$vendas_impostos_perc_bonus', 
vendas_impostos_flat='$vendas_impostos_flat', 
vendas_impostos_bonus='$vendas_impostos_bonus', 
vendas_status='9', 
vendas_alteracao='$vendas_alteracao', 
vendas_user='$vendas_user', 
vendas_cms_vendedor_flat='$vendas_cms_vendedor_flat', 
vendas_cms_vendedor_saldo='$vendas_cms_vendedor_saldo', 
vendas_comissao_vendedor='$vendas_comissao_vendedor', 
vendas_divergencia='NENHUMA'".$update_vendas_unidade." 
WHERE vendas_id='$vendas_id' ") or die(mysql_error());
echo "Venda Atualizada com Sucesso";

$sql = "INSERT INTO `sistema`.`sys_vendas_registros` (`registro_id`, 
`vendas_id`, 
`registro_usuario`, 
`registro_obs`, 
`registro_status`, 
`registro_restrito`, 
`registro_data`) 
VALUES (NULL, 
'$vendas_id',
'$vendas_user',
'$vendas_obs',
'9',
'2',
'$vendas_alteracao');"; 
if (mysql_query($sql,$con)){
	$acionamento_id = mysql_insert_id();
	echo "Histórico Registrado com Sucesso. </br>";
} else {
	die('Error: ' . mysql_error());
}
mysql_close($con);
?>
Divergência aceita com sucesso!

<?php elseif ($_GET["aceitar"] == "ignorar"):?>
<?php
$result_venda = mysql_query("SELECT 
vendas_consultor, 
vendas_status 
FROM sys_vendas WHERE vendas_id = '" . $vendas_id . "';") 
or die(mysql_error());  
$row_venda = mysql_fetch_array( $result_venda );

$result_user = mysql_query("SELECT unidade FROM jos_users WHERE id = '" . $row_venda['vendas_consultor'] . "';") 
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );
if ($row_venda["vendas_status"] != "9"){$update_vendas_unidade = ", vendas_unidade='".$row_user['unidade']."'";}

$query = mysql_query("UPDATE sys_vendas SET vendas_status='9', vendas_divergencia='NENHUMA'".$update_vendas_unidade." WHERE vendas_id='$vendas_id' ") or die(mysql_error());
echo "Divergências ignoradas com sucesso!";
?>
<?php elseif ($_GET["aceitar"] == "remover"):?>
<?php
$query = mysql_query("UPDATE sys_vendas SET vendas_divergencia='NENHUMA' WHERE vendas_id='$vendas_id' ") or die(mysql_error());
echo "Divergências excluídas com sucesso!";
?>
<?php else: ?>
<?php 
$vendas_id=$_GET["vendas_id"];
$result_venda = mysql_query("SELECT vendas_divergencia FROM sys_vendas WHERE vendas_id = '" . $vendas_id . "';") 
or die(mysql_error());  
$row_venda = mysql_fetch_array( $result_venda );
?>
<strong> Divergências para a venda:</strong></br>
<?php echo $row_venda['vendas_divergencia']; ?></br>
<div style="width: 33%; float: left;"><a href="<?php echo $_SERVER['REQUEST_URI'];?>&aceitar=aceitar" style="float: none;"><img src="sistema/imagens/aceitar_divergencia.png"><br />Aceitar Divergências</a></div>
<div style="width: 33%; float: left;"><a href="<?php echo $_SERVER['REQUEST_URI'];?>&aceitar=ignorar" style="float: none;"><img src="sistema/imagens/ignorar_divergencia.png"><br />Concluir e Ignorar</a></div>
<div style="width: 33%; float: left;"><a href="<?php echo $_SERVER['REQUEST_URI'];?>&aceitar=remover" style="float: none;"><img src="sistema/imagens/remover_divergencia.png"><br />Remover Divergências<br />(sem concluir a venda)</a></div>
<br/>
<?php endif;?>
</div>