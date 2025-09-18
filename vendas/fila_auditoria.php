        <script type="text/javascript" src="sistema/vendas/js/datepicker.js"></script>
        <link href="sistema/vendas/css/datepicker.css" rel="stylesheet" type="text/css" />
<style type="text/css">
table.split-date-wrap
        {
        width:auto;
        margin-bottom:0;
        }
table.split-date-wrap td
        {
        padding:0 0.2em 0.4em 0;
        border-bottom:0 none;
        }
table.split-date-wrap td input
        {
        margin-right:0.3em;
        }
table.split-date-wrap td label
        {
        font-size:10px;
        font-weight:normal;
        display:block;
        }
</style>
<?php
$cpf=$_GET["cpf"];
$clients_cat=$_GET["clients_cat"];
if ($_GET["p"]){$pagina=$_GET["p"];}else{$pagina="1";}

$vendas_id=$_GET["vendas_id"];
if ($_GET["vendas_id"]) {$select_id= " AND vendas_id = '" . $vendas_id . "'";} else {$select_id="";}

$user =& JFactory::getUser();
$username=$user->username;
$user_id=$user->id;
include("sistema/utf8.php");
$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '10'){$administracao = 1;}
	if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if ($row_grupo_user['id'] == '19'){$financeiro = 1;}
	if ($row_grupo_user['id'] == '21'){$franquiado = 1;}
}

$p = $_GET["p"];
if(isset($p)) {
$p = $p;
} else {
$p = 1;
}
$qnt = 20;
$inicio = ($p*$qnt) - $qnt;

$result = mysql_query("SELECT * FROM sys_vendas_seguros LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
														LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf)
														LEFT JOIN jos_users ON ( sys_vendas_seguros.vendas_consultor = jos_users.id )
														LEFT JOIN sys_equipes ON ( jos_users.equipe_id = sys_equipes.equipe_id )
														WHERE vendas_status = '1' ORDER BY vendas_id DESC;") or die(mysql_error());

/*
$result = mysql_query("SELECT * FROM sys_vendas_seguros LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf) WHERE vendas_dia_venda >= (SELECT TIMESTAMP(CURDATE( )) FROM sys_vendas_seguros LIMIT 1) AND vendas_status = '1' ORDER BY vendas_id DESC;") 
or die(mysql_error());
*/
// --- FIM ALTERAÇÃO --- //
?>
 <?php  $curURL = $_SERVER["REQUEST_URI"]; ?>
<meta http-equiv="Refresh" content="10; url=index.php?option=com_k2&view=item&layout=item&id=101&Itemid=477">
<form action="index.php" method="GET">
<input id="sis_campo" name="option" type="hidden" id="option" value="com_k2" />
					<input id="sis_campo" name="view" type="hidden" id="view" value="item" />
					<input id="sis_campo" name="id" type="hidden" id="id" value="64" />
					<input id="sis_campo" name="Itemid" type="hidden" id="Itemid" value="440" />
  <div align="center">
    <table width="100%" height="99%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tbody>
               
            <tr>
              <td width="100%" valign="top" class="style8" style="text-align: right;"><span class="style5">
<table width="100%" border="1" bordercolor="#266195" align="center" cellpadding="0" cellspacing="2" >
<tbody>
<tr>
<td width="100%">
		</td>					  
       </tr>
     </table>
</td>
                </tr>
            </tbody>
</table>

	    <div align="left">
	      
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
		<tbody>
		  <tr class="cabecalho">
			<div align="left" class="style8">
			<td width="3%"><span style="color:#cccccc; font-size:8pt">#</span></td>
			<td width="25%">
				<span style="color:#fff;">Cliente:</span><br>
				<span style="color:#cccccc; font-size:8pt">CPF:</span></td>
			<td width="12%">
				<span style="color:#fff;">Apólice:</span><br>
				<span style="color:#cccccc; font-size:8pt">Dia Vencimento:</span>
			</td>
			<td width="21%">
                <span style="color:#fff;">Consultor:</span><br>
				<span style="color:#cccccc; font-size:8pt">Data da venda:</span>
			</td>
			<td width="15%">
				<span style="color:#fff;">Staus:</span><br>
			</td>
			<td width="15%">
                <img src="sistema/imagens/config.png"></br>
				<span style="color:#fff;">Código:</span><br>
			</td>
            </div>
		</tr>
<tr>
<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
<tbody>
		  	      <?php
$totalclientes = 0;
$exibindo = 1;
$numero = $exibindo;
include("sistema/vendas/exibe_lista_fila.php");
$exibindo = $exibindo  - 1;

if (($diretoria == 1)||($financeiro == 1)){
	if (($vendas_mes)&&($pag_status == "&vendas_status[]=8&vendas_status[]=9")){$sum_comissao = ", SUM(vendas_comissao_vendedor) AS total_comissao ";}else{$sum_comissao = " ";}
}
	echo "<tr class='even'><div align='left'>";
	echo "<td colspan='7'><div align='center'>";
	echo "<table>";
	echo "<tr>";	
$sql_select_all = mysql_query("SELECT COUNT(*) AS total FROM sys_vendas_seguros WHERE vendas_status = '1';")
or die(mysql_error());
$row_total_registros = mysql_fetch_array( $sql_select_all );
$total_registros = $row_total_registros["total"];
$pags = ceil($total_registros/$qnt);
$max_links = 6;
	echo "<td>"; 
echo "<a href='".$links_filtros."&ordemi=".$ordem."&p=1' target='_self'>primeira pagina</a> ";
echo "</td>";
for($i = $p-$max_links; $i <= $p-1; $i++) {
if($i <=0) {
} else {
echo "<td>";
echo "<a href='".$links_filtros."&ordemi=".$ordem."&p=".$i."' target='_self'>".$i."</a> ";
echo "</td>";
}
}
echo "<td>";
echo "<strong> [ ".$p." ] </strong> ";
echo "</td>";
for($i = $p+1; $i <= $p+$max_links; $i++) {
if($i > $pags)
{
}
else
{
echo "<td>";
echo "<a href='".$links_filtros."&ordemi=".$ordem."&p=".$i."' target='_self'>".$i."</a> ";
echo "</td>";
}
}
echo "<td>";
echo "<a href='".$links_filtros."&ordemi=".$ordem."&p=".$pags."' target='_self'>ultima pagina</a> ";
echo "</td>";	
echo "</tr>";
echo "</table>";
	echo "</div></td>";
	echo "</div></tr>"; 
?>
</tbody>
          </table>
            </tbody>
          </table>
    </table>
<div align="center">Exibindo <?php echo $exibindo;?> de um total de <?php echo $total_registros;?></div>	
  </div>
</form>
<?php if ($total_registros888): ?>
<audio autoplay="autoplay">
    <source src="http://acionamento.grupofortune.com.br/sistema/sistema/audios/auditoria.mp3" type="audio/mp3" />
</audio>
<?php endif; ?>
<?php mysql_close($con); ?>