<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
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
        <script type="text/javascript" src="sistema/vendas/js/datepicker.js"></script>
        <link href="sistema/vendas/css/demo.css"       rel="stylesheet" type="text/css" />
        <link href="sistema/vendas/css/datepicker.css" rel="stylesheet" type="text/css" />
<script language="javascript">
//-----------------------------------------------------
//Funcao: MascaraMoeda
//Sinopse: Mascara de preenchimento de moeda
//Parametro:
//   objTextBox : Objeto (TextBox)
//   SeparadorMilesimo : Caracter separador de milésimos
//   SeparadorDecimal : Caracter separador de decimais
//   e : Evento
//Retorno: Booleano
//Autor: Gabriel Fróes - www.codigofonte.com.br
//-----------------------------------------------------
function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true;
	var t = new String(objTextBox.value);
if (whichCode == 8){
objTextBox.value = t.substring(0, t.length-1);
} 
    key = String.fromCharCode(whichCode); // Valor para o código da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inválida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
        objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
}
</script>
<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
    	if (tecla==8 || tecla==0) return true;
	else  return false;
    }
}
</script>
<script type="text/javascript" src="/sistema/templates/gk_music/js/ddaccordion.js"></script>
<style type="text/css">
.mypets{ /*header of 1st demo*/
cursor: hand;
cursor: pointer;
padding: 2px 5px;
border: 1px solid gray;
background: #E1E1E1;
}
.mypets:hover {
    background: #FFFFFF;
}
.openpet{ /*class added to contents of 1st demo when they are open*/
background: #5474A9;
}
.openpet:hover {
    background: #FFFFFF;
}
.technology{ /*header of 2nd demo*/
cursor: hand;
cursor: pointer;
font: bold 14px Verdana;
margin: 10px 0;
}
.openlanguage{ /*class added to contents of 2nd demo when they are open*/
color: green;
}
.closedlanguage{ /*class added to contents of 2nd demo when they are closed*/
color: red;
}
</style>
<script type="text/javascript">
//Initialize first demo:
ddaccordion.init({
	headerclass: "mypets", //Shared CSS class name of headers group
	contentclass: "thepet", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
	persiststate: true, //persist state of opened contents within browser session?
	toggleclass: ["", "openpet"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["none", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})
//Initialize 2nd demo:
ddaccordion.init({
	headerclass: "technology", //Shared CSS class name of headers group
	contentclass: "thelanguage", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
	persiststate: false, //persist state of opened contents within browser session?
	toggleclass: ["closedlanguage", "openlanguage"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["prefix", "<img src='http://i13.tinypic.com/80mxwlz.gif' style='width:13px; height:13px' /> ", "<img src='http://i18.tinypic.com/6tpc4td.gif' style='width:13px; height:13px' /> "], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})
</script>
<script type="text/javascript">
var totalCampos = 10;
var iLoop = 1;
var iCount = 0;
var linhaAtual;

function AddCampos() {
var hidden1 = document.getElementById("hidden1");
var hidden2 = document.getElementById("hidden2");
if (iCount < totalCampos) {
hidden2.value = "";
for (iLoop = 1; iLoop <= totalCampos; iLoop++) {
        if (document.getElementById("linha"+iLoop).style.display == "none") {
                if (hidden2.value == "") {
                        hidden2.value = "linha"+iLoop;
                }else{
                        hidden2.value += ",linha"+iLoop;
                }
        }
}

linhasOcultas = hidden2.value.split(",");
        if (linhasOcultas.length > 0) {
                document.getElementById(linhasOcultas[0]).style.display = "block"; iCount++;
                if (hidden1.value == "") {
                        hidden1.value = linhasOcultas[0];
                }else{
                        hidden1.value += ","+linhasOcultas[0];
                }
        }
}
}
</script>
</head>
<body>
<?php if ($_GET["salvar"] == "salvar"):?>
<?php include("sistema/vendas/insere.php");?>
<?php else: ?>
<?php 
$user =& JFactory::getUser();
$username=$user->username;
$consultor=$user->name;
$user_id=$user->id;
$diretoria = 0;
if ($_GET["clients_employer"]){$vendas_orgao = $_GET["clients_employer"];}else{$vendas_orgao = $_GET["vendas_orgao"];}

$result_grupo = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error()); 

$result_user = mysql_query("SELECT nivel FROM jos_users WHERE id = " . $user_id . ";") 
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );

$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '10'){$administracao = 1;}
	if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if ($row_grupo_user['id'] == '19'){$financeiro = 1;}
}

if ($_GET["clients_cpf"]){
	$clients_cpf = $_GET["clients_cpf"];
	$result_geral = mysql_query("SELECT cliente_cpf, cliente_nome, cliente_margem FROM sys_inss_clientes WHERE cliente_cpf = '" . $clients_cpf . "';") 
	or die(mysql_error());
	$row_geral = mysql_fetch_array( $result_geral );
	$cliente_margem = $row_geral["cliente_margem"];
	if (!$row_geral["cliente_cpf"]){
		$result_mex = mysql_query("SELECT clients_nm, clients_margem FROM sys_clients WHERE clients_cpf = '" . $clients_cpf . "';") 
		or die(mysql_error());
		$row_mex = mysql_fetch_array( $result_mex );
		$cliente_margem = $row_mex["clients_margem"];
	}
}

$result_banks = mysql_query("SELECT * FROM sys_vendas_bancos WHERE vendas_bancos_employer LIKE '%" . $_GET['clients_employer'] . "%' ORDER BY vendas_bancos_nome;") 
or die(mysql_error()); 
$time = mktime(date('H')-3, date('i'), date('s'));

include("../utf8.php");
$opcoes_select_banco = $opcoes_select_banco."<option value='' selected>-------- Banco a ser Comprado --------</option>";
$result_bancos_compra = mysql_query("SELECT * FROM sys_vendas_bancos_compra ORDER BY banco_codigo;")
or die(mysql_error());
while($row_bancos_compra = mysql_fetch_array( $result_bancos_compra )) {
	$opcoes_select_banco = $opcoes_select_banco."<option value='{$row_bancos_compra['banco_id']}'>{$row_bancos_compra['banco_codigo']} - {$row_bancos_compra['banco_nome']}</option>";
}
?>
<form id="testform" action="index.php" method="GET">
					<input name="option" type="hidden" value="com_k2" />
					<input name="view" type="hidden" value="item" />
					<input name="id" type="hidden" value="64" />
					<input name="Itemid" type="hidden" value="123" />
					<input name="tmpl" type="hidden" value="component" />
					<input name="print" type="hidden" value="1" />
					<input name="acao" type="hidden" value="nova_venda" />
					<input name="clients_cpf" type="hidden" value="<?php echo $_GET["clients_cpf"];?>" />
					<input name="clients_nm" type="hidden" value="<?php echo $_GET["clients_nm"];?>" />
<div align="center">
<table width="99%" class="blocos">	
	<tr>
		<td><div align="right">
			Dado do Cliente:</br>
			CPF:</br>
			Orgão:</br>
			</div>
		</td>
		<td><div align="left">
			<strong><?php echo $_GET["clients_nm"];?></strong></br>
			<strong><?php echo $_GET["clients_cpf"];?></strong></br>
				<select name="vendas_orgao">
<?php
	include("sistema/utf8.php");
	if ($vendas_orgao == "") {echo "<option value='' selected>------ Órgão ------</option>";}else{echo "<option value='{$vendas_orgao}' selected>{$vendas_orgao}</option>";}
	$result_orgao = mysql_query("SELECT * FROM sys_orgaos ORDER BY orgao_nome;")
	or die(mysql_error());
	while($row_orgao = mysql_fetch_array( $result_orgao )) {
		if ($row_orgao["orgao_nome"] == $row["vendas_orgao"]){$selected = "selected";}else{$selected = "";}
		echo "<option value='{$row_orgao['orgao_nome']}'{$selected}>{$row_orgao['orgao_label']}</option>";
	}
?>
                </select>
			</div>
		</td>
	</tr>
	<tr>
		<td><div align="right">			
			Dados do Consultor:
			</div>
		</td>
<?php if ($administracao == 1):?>
		<td><div align="left">
			<select name='vendas_consultor'>
			<option value='<?php echo $user_id;?>'><?php echo $consultor;?></option>
<?php			
	$result_user_form = mysql_query("SELECT id,name,unidade FROM jos_users ORDER BY name;")
	or die(mysql_error());
	while($row_user_form = mysql_fetch_array( $result_user_form )) {echo "<option value='{$row_user_form['id']}'>{$row_user_form['name']}</option>";}
?>	
			</select>
			</div>
		</td>		
<?php else:?>		
		<td><div align="left">
			<strong><?php echo $consultor;?></strong> (<?php echo $username;?>)</br>
			<input name="vendas_consultor" type="hidden" value="<?php echo $user_id;?>" />
			<?php while($row_grupo = mysql_fetch_array( $result_grupo )) {$user_groups = $user_groups.$row_grupo['title']." | ";}?>
			<span style="font-size:6pt"><?php echo $user_groups;?></span>
			</div>
		</td>
<?php endif;?>		
	</tr>
</table>
<h3 class="mypets">Dados da Proposta:</h3>
<div class="thepet">
<table width="99%" class="blocos">	
	<tr>
		<td colspan="2">
		<div align="left">			
			<label for="vendas_valor_parcela">Valor da Parcela:</label>(somente números)</br>
			<?php $cliente_margem = ($cliente_margem>0) ? number_format($cliente_margem, 2, ',', '.') : '0' ;?>
			R$ <input type="text" name="vendas_valor_parcela" size="15" value="<?php if ($_GET["vendas_valor_parcela"]){echo $_GET["vendas_valor_parcela"];}else{echo $cliente_margem;}?>" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
			</div>
		</td>
	</tr>
	<tr>
		<td>
		<div align="left">			
			<label for="vendas_banco">Banco:</label></br>
			<select name="vendas_banco" onchange="this.form.submit()">
			<option value="Não Informado">Selecione o Banco</option>
			<?php 
				while($row_banks = mysql_fetch_array( $result_banks )) {
					if ($row_banks["vendas_bancos_nome"] == $_GET["vendas_banco"]){$selected = " selected"; $tabela_banco = $row_banks["vendas_bancos_id"];}else{$selected = "";}
					echo "<option value='".$row_banks['vendas_bancos_nome']."'{$selected}>".$row_banks['vendas_bancos_nome']."</option>";
				}
			?>
			</select>
			</div>
		</td>
		<td>
		<div align="left">
			<label for="vendas_tipo_contrato">Tipo de Contrato:</label></br>
			<select name="vendas_tipo_contrato" onchange="this.form.submit()">
			<option value="">Selecione o Tipo de Contrato</option>
			<?php
				$result_tipos = mysql_query("SELECT * FROM sys_vendas_tipos;")
				or die(mysql_error());
				while($row_tipos = mysql_fetch_array( $result_tipos )) {
					if ($row_tipos["tipo_id"] == $_GET["vendas_tipo_contrato"]){$selected = "selected";}else{$selected = "";}
					echo "<option value='{$row_tipos['tipo_id']}'{$selected}>{$row_tipos['tipo_nome']}</option>";
				}
			?>
			</select>
			</div>
		</td>		
	</tr>
	<tr>
<?php if ($_GET["vendas_tipo_contrato"] == "6"): ?>
		<input name="vendas_percelas" type="hidden" value="1" />
		<?php 
		$_GET["vendas_percelas"] = "1";
		$vendas_tipo_contrato = $_GET["vendas_tipo_contrato"];
		$hoje = date("Y-m-d");
		?>
<?php else: ?>
		<td>
		<div align="left">
			<label for="vendas_percelas">Prazo:</label></br>
	<?php if((!$tabela_banco) || (!$_GET["vendas_tipo_contrato"])): ?>
				Selecione o Banco e o Tipo de Contrato para prosseguir.
	<?php else: ?>
				<select name="vendas_percelas" onchange="this.form.submit()">
					<option value="">Selecione um Prazo</option>
				<?php
					$vendas_tipo_contrato = $_GET["vendas_tipo_contrato"];
					$num_tabelas = 0;
					$hoje = date("Y-m-d");
					$result_prazo = mysql_query("SELECT DISTINCT tabela_prazo FROM sys_vendas_tabelas WHERE 
					tabela_banco = '".$tabela_banco."' 
					AND tabela_operacao like '%".$vendas_tipo_contrato."%' 
					AND tabela_orgao like '%".$vendas_orgao."%' 
					AND tabela_vigencia_ini <= '".$hoje."' 
					AND tabela_vigencia_fim >= '".$hoje."' 
					AND tabela_ativa = '1' 
					AND tabela_permissao = '1';")
					or die(mysql_error());
					while($row_prazo = mysql_fetch_array( $result_prazo )) {
						if ($row_prazo["tabela_prazo"] == $_GET["vendas_percelas"]){
							$selected = " selected";
							$prazo = $row_prazo['tabela_prazo'];
							$coeficiente = $row_prazo[$tabela_dia];
							}else{$selected = "";}
						echo "<option value='{$row_prazo['tabela_prazo']}'{$selected}>{$row_prazo['tabela_prazo']} X</option>";
						$num_tabelas = $num_tabelas + 1;
					}
				?>
				</select>
				<?php if($num_tabelas == 0): ?> <span style="color:#ff0000;"><strong>* Não há tabela disponível para o banco selecionado!</strong></span><br /> Selecione outro Banco! <?php endif; ?>
	<?php endif; ?>
			</div>
		</td>
<?php endif; ?>
		<td>
		<div align="left">
			<label for="vendas_tabela">Tabela:</label></br>
	<?php if((!$tabela_banco) || (!$_GET["vendas_tipo_contrato"]) || (!$_GET["vendas_percelas"])): ?>
				Selecione o Banco, o Tipo e o Prazo para prosseguir.
	<?php else: ?>
				<select name="vendas_tabela" onchange="this.form.submit()">
					<option value="">Selecione a Tabela</option>
				<?php
					$vendas_percelas = $_GET["vendas_percelas"];
					$num_tabelas = 0;
					$dia = date("d");
					$tabela_dia = "tabela_dia_".$dia;
					$result_tabela = mysql_query("SELECT tabela_id, tabela_nome, tabela_prazo, tabela_tipo, ".$tabela_dia." FROM sys_vendas_tabelas WHERE 
					tabela_banco = '".$tabela_banco."' 
					AND tabela_operacao like '%".$vendas_tipo_contrato."%' 
					AND tabela_prazo = '".$vendas_percelas."' 
					AND tabela_orgao like '%".$vendas_orgao."%' 
					AND tabela_vigencia_ini <= '".$hoje."' 
					AND tabela_vigencia_fim >= '".$hoje."' 
					AND tabela_ativa = '1' 
					AND tabela_permissao = '1';")
					or die(mysql_error());
					while($row_tabela = mysql_fetch_array( $result_tabela )) {
						if ($row_tabela["tabela_id"] == $_GET["vendas_tabela"]){
							$selected = " selected";
							$prazo = $row_tabela['tabela_prazo'];
							$coeficiente = $row_tabela[$tabela_dia];
							}else{$selected = "";}
						if ($vendas_tipo_contrato == "6"){echo "<option value='{$row_tabela['tabela_id']}'{$selected}>{$row_tabela['tabela_nome']}.</option>";}
						else {echo "<option value='{$row_tabela['tabela_id']}'{$selected}>{$row_tabela['tabela_nome']}. - Tipo: {$row_tabela['tabela_tipo']} - Prazo: {$row_tabela['tabela_prazo']}X</option>";}
						$num_tabelas = $num_tabelas + 1;
					}
				?>
				</select>
				<?php if($num_tabelas == 0): ?> <span style="color:#ff0000;"><strong>* Não há tabela disponível para o banco selecionado!</strong></span><br /> Selecione outro Banco! <?php endif; ?>
				<?php 
					if(($_GET["vendas_tabela"]) && ($vendas_tipo_contrato != "6")){
						if($coeficiente){
							echo "Coeficiente: <strong>".$coeficiente."</strong>";
							$vendas_valor_parcela=$_GET["vendas_valor_parcela"];
							if(strpos($vendas_valor_parcela,".")){$vendas_valor_parcela=substr_replace($vendas_valor_parcela, '', strpos($vendas_valor_parcela, "."), 1);}
							if(!strpos($vendas_valor_parcela,".")&&(strpos($vendas_valor_parcela,","))){$vendas_valor_parcela=substr_replace($vendas_valor_parcela, '.', strpos($vendas_valor_parcela, ","), 1);}
							$vendas_valor = $vendas_valor_parcela / $coeficiente;
							echo "<input type='hidden' name='vendas_coeficiente' value='".$coeficiente."'/>";
						}else{echo "<span style='color:#ff0000;'><strong>* Não há coeficiente cadastrado para hoje!</strong></span> | ";}
					}
				?>
	<?php endif; ?>
			</div>
		</td>		
	</tr>
	<tr>
		<td colspan="2">
		<div align="left">			
			<label for="vendas_valor">AF. Valor do Contrato:</label>(somente números)</br>
			<?php $vendas_valor = ($vendas_valor>0) ? number_format($vendas_valor, 2, ',', '.') : '0' ;?>
			R$ <input type="text" name="vendas_valor" size="15" value="<?php if ($vendas_valor){echo $vendas_valor;}else{echo $_GET["vendas_valor"];}?>" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
			</div>
		</td>
	</tr>
	<tr>
		<td>
		<div align="left">			
			<label for="vendas_margem">Margem:</label>(somente números)</br>
			R$ <input type="text" name="vendas_margem" size="15" value="<?php echo $_GET["vendas_margem"];?>" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
			</div>
		</td>
		<td>
		<div align="left">			
			<label for="vendas_liquido">Líquido:</label>(somente números)</br>
			R$ <input type="text" name="vendas_liquido" size="15" value="<?php echo $_GET["vendas_liquido"];?>" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
			</div>
		</td>			
	</tr>
<?php if ($administracao == 1):?>
	<tr>
		<td colspan="2">
		<div align="left">			
			<label for="vendas_estoque">Venda em Estoque:</label></br>
				<select name="vendas_estoque">
				  <option value="0" <?php if ($_GET['vendas_estoque'] == "0"){echo "selected";}?>>Não</option>
				  <option value="1" <?php if ($_GET['vendas_estoque'] == "1"){echo "selected";}?>>Sim</option>
				</select>
			</div>
		</td>
	</tr>
<?php endif;?>
	</tr>
</table>
</div>
<?php if (($row_user["nivel"] != 4) && ($vendas_tipo_contrato != "6")): ?>
<h3 class="mypets">Seguro:</h3>
<div class="thepet">
<table width="99%" class="blocos">	
		<tr>
		<td>
		<div align="left">			
			<label for="vendas_applus_ben">Nome do Beneficiário:</label></br>
			<input type="text" name="vendas_applus_ben" size="20" value="<?php echo $_GET["vendas_applus_ben"];?>"/>
			</div>
		</td>
		<td>
		<div align="left">			
			<label for="vendas_applus_parent">Parentesco:</label></br>
			<input type="text" name="vendas_applus_parent" size="20" value="<?php echo $_GET["vendas_applus_parent"];?>"/>
			</div>
		</td>		
	</tr>
		<tr>
		<td colspan="2">
		<div align="left">			
			<label for="vendas_applus_valor">Valor da Parcela:</label>(somente números)</br>
			R$ <input type="text" name="vendas_applus_valor" size="15" value="<?php echo $_GET["vendas_applus_valor"];?>" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
			</div>
		</td>			
	</tr>
</table>
</div>
<?php endif;?>
<?php if(($vendas_tipo_contrato == "2") || ($vendas_tipo_contrato == "3") || ($vendas_tipo_contrato == "4") || ($vendas_tipo_contrato == "5")): ?>
<h3 class="mypets">Compra de dívida:</h3>
<div class="thepet">
<table width="99%" class="blocos">	
		<tr>
		<td>
		<div align="left">			
			<label for="vendas_compra_banco1">Banco a ser Comprado:</label></br>
			<input type="text" name="vendas_compra_banco1" size="20" value="<?php echo $_GET["vendas_compra_banco1"];?>"/>
			</div>
		</td>
		<td>
		<div align="left">			
			<label for="vendas_compra_contrato1">Numero do Contrato a ser Comprado:</label></br>
			<input type="text" name="vendas_compra_contrato1" size="15" maxlength="50" value="<?php echo $_GET["vendas_compra_contrato1"];?>" onkeypress="return SomenteNumero(event)"/>
			</div>
		</td>
	</tr>
		<tr>
		<td>
		<div align="left">			
			<label for="vendas_compra_valor1">Parcela a ser Comprada:</label>(somente números)</br>
			R$ <input type="text" name="vendas_compra_valor1" size="15" value="<?php echo $_GET["vendas_compra_valor1"];?>" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
			</div>
		</td>
		<td>
		<div align="left">			
			<label for="vendas_compra_saldo1">Saldo Devedor:</label>(somente números)</br>
			R$ <input type="text" name="vendas_compra_saldo1" size="15" value="<?php echo $_GET["vendas_compra_saldo1"];?>" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
			</div>
		</td>		
		</tr>
		<tr>
		<td>
		<div align="left">
			<label for="vendas_compra_prazo">Prazo do Contrato:</label>
			<input type="text" name="vendas_compra_prazo" size="2" value="<?php echo $_GET["vendas_compra_prazo"];?>" onkeypress="return SomenteNumero(event)"/>
			</div>
		</td>
		<td>
		<div align="left">
			<label for="vendas_compra_parcelas">Parcelas em aberto:</label>
			<input type="text" name="vendas_compra_parcelas" size="2" value="<?php echo $_GET["vendas_compra_parcelas"];?>" onkeypress="return SomenteNumero(event)"/>
			</div>
		</td>
		</tr>
		<tr>
		<td>
		<div align="left">			
	        <label for="dp-normal-1">Data de Vencimento:</label>
			<p class="lastup"><input type="text" class="w8em format-d-m-y range-low-today highlight-days-67" id="dp-normal-1" name="dp-normal-1" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-1"];?>" readonly="true" /></p>
			</div>
		</td>
		</tr>
</table>
<?php if ($user_id == 42):?>
	<br />
	<script type="text/javascript">
	//Escrevendo o código-fonte HTML e ocultando os campos criados:
	for (iLoop = 1; iLoop <= totalCampos; iLoop++) {
		document.write("<span id='linha"+iLoop+"' style='display:none'><select style='float:none;' name='compra_banco_"+iLoop+"'><?php echo $opcoes_select_banco; ?></select><br />");
		document.write("Nº do Contrato: <input type='text' id='compra_contrato"+iLoop+"' name='compra_contrato"+iLoop+"' size='10' placeholder='contrato a ser comprado' onKeyPress='return SomenteNumero(event)'><br />");
		document.write("Parcela: <input type='text' id='compra_valor"+iLoop+"' name='compra_valor"+iLoop+"' maxlength='10' size='10' placeholder='valor da parcela' onKeyPress='return(MascaraMoeda(this,'.',',',event))'><br />");
		document.write("Saldo: <input type='text' id='compra_saldo"+iLoop+"' name='compra_saldo"+iLoop+"' maxlength='10' size='10' placeholder='saldo devedor' onKeyPress='return(MascaraMoeda(this,'.',',',event))'><br />");
		document.write("Prazo do Contrato: <input type='text' id='compra_prazo"+iLoop+"' name='compra_prazo"+iLoop+"' maxlength='2' size='2' onKeyPress='return SomenteNumero(event)'><br />");
		document.write("Parcelas em aberto: <input type='text' id='compra_parcelas"+iLoop+"' name='compra_parcelas"+iLoop+"' maxlength='2' size='2' onKeyPress='return(MascaraMoeda(this,'.',',',event))'><br />");
		document.write("Vencimento: <input type='text' id='compra_venc"+iLoop+"' name='compra_venc"+iLoop+"' maxlength='10' size='10' placeholder='dd/mm/aaaa'><br /><hr><br /></span>");
	}
	</script>
	<input type="button" value="Adicionar Compra" onclick="AddCampos()">
	<br /><br />
	<input type="hidden" name="hidden1" id="hidden1">
	<input type="hidden" name="hidden2" id="hidden2">
	</div>
<?php endif;?>
</div>
<?php endif;?>
		<table width="99%" class="blocos">	
		<tr>
		<td colspan="2">
			<label for="vendas_obs">Observações:</label></br>
			<textarea name="vendas_obs" cols="70" rows="3" id="obs" value="<?php echo $_GET["vendas_obs"];?>"></textarea>
		</td>
		</tr>
		<tr>
		<td colspan="2">
			<button name="salvar" type="submit" value="salvar">Salvar Venda</button>
		</td>
		</tr>
		</div>
		</table>  
</form>
<?php endif;?>
</body>
</html>