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
<script type="text/javascript">
//Initialize first demo:
ddaccordion.init({
	headerclass: "mypets2", //Shared CSS class name of headers group
	contentclass: "thepet2", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [0,1], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
	persiststate: false, //persist state of opened contents within browser session?
	toggleclass: ["", "openpet2"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["none", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})
</script>

<!--
<script type="text/javascript">
	function alertUser(msg) {
		alert(msg);
	}
</script>
-->

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
$qnt = 10;
$inicio = ($p*$qnt) - $qnt;
$result = mysql_query("SELECT * FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf) WHERE vendas_status = '13' ORDER BY vendas_id DESC;") 
or die(mysql_error());

$result_imp = mysql_query("SELECT * FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf) WHERE vendas_status = '15' ORDER BY vendas_id DESC LIMIT " . $inicio . ", " . $qnt . ";") 
or die(mysql_error());

?>
 <?php  $curURL = $_SERVER["REQUEST_URI"]; ?>
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
                <td width="30%">
<?php $links_filtros = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=440&vendas_id=".$vendas_id."&nome=".$nome."&prec=".$prec."&cpf=".$cpf."&vendas_mes=".$vendas_mes."&consultor_unidade=".$pag_unidade."&vendas_consultor=".$vendas_consultor."&vendas_status=".$pag_status."&vendas_contrato_fisico=".$pag_contrato."&vendas_promotora=".$vendas_promotora."&vendas_banco=".$vendas_banco."&vendas_orgao=".$vendas_orgao."&dp-normal-3=".$pag_data_imp_ini."&dp-normal-4=".$pag_data_imp_fim;?>
				
					<span style="color:#fff;">Cliente</span><br>
					<span style="color:#cccccc; font-size:8pt">CPF: | Matrícula:</span></td>
                <td width="12%">
					<span style="color:#fff;">Órgão</span><br>
					<span style="color:#cccccc; font-size:8pt">Banco | Proposta:</span></td>
				<td width="11%">
					<span style="color:#fff;">Valor AF</span><br>
					<span style="color:#cccccc; font-size:8pt">Tipo de Contrato</span>
				</td>
				<td width="21%">
					<span style="color:#fff;">Consultor</span><br>
					<span style="color:#cccccc; font-size:8pt">Data da venda:</span>
				</td>
				<td width="18%">
					<img src="sistema/imagens/config.png"></br>
					<span style="color:#fff;">Código</span>
                </div>
		      </tr>
<tr>
<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
<tbody>
<?php
$total_bo = 0;
include("sistema/vendas/exibe_lista_bo.php");
$totalclientes = 0;
$exibindo = 1;
$numero = $exibindo;
include("sistema/vendas/exibe_lista_bo2.php");
$exibindo = $exibindo  - 1;

	echo "<tr class='even'><div align='left'>";
	echo "<td colspan='7'><div align='center'>";
	echo "<table>";
	echo "<tr>";	
$sql_select_all = mysql_query("SELECT COUNT(*) AS total FROM sys_vendas WHERE vendas_status = '15';")
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
<?php if ($total_bo): ?>
<body onload="alertUser('Nova Venda Aguardando BackOffice!')">
<!-- 
<audio autoplay="autoplay">
    <source src="http://acionamento.grupofortune.com.br/sistema/sistema/audios/auditoria.mp3" type="audio/mp3" />
</audio>
-->
<?php endif; ?>
<?php mysql_close($con); ?>