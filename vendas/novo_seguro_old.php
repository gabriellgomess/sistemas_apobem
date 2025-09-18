<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta content="IE=9" http-equiv="X-UA-Compatible">
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<meta content="index, follow" name="robots">
<meta content="/sistema" name="image">
<meta content=" " name="description">
<title>Cadastro de Venda de SEGURO</title>
<link type="image/vnd.microsoft.icon" rel="shortcut icon" href="/sistema/templates/gk_music/images/favicon.ico"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/k2.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/layout.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/joomla.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/template.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/gk.stuff.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/style3.css" rel="stylesheet"></link>
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
<script type="text/javascript">
/* Máscaras ER */
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mcc(v){
    v=v.replace(/\D/g,"");
    v=v.replace(/^(\d{4})(\d)/g,"$1 $2");
    v=v.replace(/^(\d{4})\s(\d{4})(\d)/g,"$1 $2 $3");
    v=v.replace(/^(\d{4})\s(\d{4})\s(\d{4})(\d)/g,"$1 $2 $3 $4");
    return v;
}
function id( el ){
	return document.getElementById( el );
}
window.onload = function(){
	id('vendas_cartao_num').onkeypress = function(){
		mascara( this, mcc );
	}
}
</script>
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
</script>
<script language="javascript">
var cont = 1;

function addCampo()
{

	if(cont==1){
		document.getElementById('remove_btn').innerHTML = '<input type="button" value="Remover Beneficiário" onclick="removeCampo();" />'
	}
	
	if(cont<=20)
	{
		elem = document.createElement("SPAN");
		elem.className = "removivel";
		elem.innerHTML = "<span id='linha"+cont+"'><div style='width:35%; float: left;'><div align='right'>Nome do Beneficiário: </div></div><div style='width:65%; display: inherit;'><input type='text' id='ben_nome"+cont+"' name='ben_nome"+cont+"'></div><br /><div style='width:35%; float: left;'><div align='right'>Data da Nascimento: </div></div><div style='width:65%; display: inherit;'><input type='text' id='ben_nasc"+cont+"' name='ben_nasc"+cont+"' placeholder='dd/mm/aaaa' maxlength='10' size='10'></div><br /><div style='width:35%; float: left;'><div align='right'>Parentesco: </div></div><div style='width:65%; display: inherit;'><input type='text' id='ben_parent"+cont+"' name='ben_parent"+cont+"' maxlength='40' size='20'></div><br /><div style='width:35%; float: left;'><div align='right'>Percentual: </div></div><div style='width:65%; display: inherit;'><input type='text' id='ben_perc"+cont+"' name='ben_perc"+cont+"' maxlength='5' size='5' onKeyPress="+"return(MascaraMoeda(this,'.',',',event))"+"></div><br /><br /><hr><br /></span>";
		document.getElementById("campo_beneficiarios").appendChild(elem);

		cont++;
	}
	if(cont==21)
	{
		document.getElementById("add_btn").innerHTML = "";
	}
}

function removeCampo()
{
	if(document.getElementsByClassName("removivel").length>0)
		{
			len = document.getElementsByClassName("removivel").length;
			document.getElementsByClassName("removivel")[len-1].remove();
			cont--;
			if(cont==1){document.getElementById('remove_btn').innerHTML = ''};
		};
	if(document.getElementById("add_btn").innerHTML == "" && cont<20)
	{
		document.getElementById("add_btn").innerHTML = '<input type="button" value="Adicionar Beneficiário" onclick="addCampo();" />'
	}
}
</script>
</head>
<?php $vendas_orgao = $_GET["clients_employer"]; ?>
<?php if ($_GET["salvar"] == "salvar"):?>
<?php include("sistema/vendas/insere_seguro.php");?>
<?php else: ?>
<body>
<?php 
$user =& JFactory::getUser();
$username=$user->username;
$consultor=$user->name;
$user_id=$user->id;
$diretoria = 0;

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

$result_cliente = mysql_query("SELECT cliente_banco, cliente_agencia, cliente_conta FROM sys_inss_clientes WHERE cliente_cpf = " . $_GET['clients_cpf'] . ";") 
or die(mysql_error());
$row_cliente = mysql_fetch_array( $result_cliente );

$result_banks = mysql_query("SELECT * FROM sys_vendas_bancos WHERE vendas_bancos_employer LIKE '%" . $_GET['clients_employer'] . "%' ORDER BY vendas_bancos_nome;") 
or die(mysql_error()); 
$time = mktime(date('H')-3, date('i'), date('s'));
?>
<form id="testform" action="index.php" method="GET">
					<input name="option" type="hidden" value="com_k2" />
					<input name="view" type="hidden" value="item" />
					<input name="id" type="hidden" value="64" />
					<input name="Itemid" type="hidden" value="123" />
					<input name="tmpl" type="hidden" value="component" />
					<input name="print" type="hidden" value="1" />
					<input name="acao" type="hidden" value="nova_venda_seguro" />
					<input name="clients_cpf" type="hidden" value="<?php echo $_GET["clients_cpf"];?>" />
					<input name="clients_nm" type="hidden" value="<?php echo $_GET["clients_nm"];?>" />
					<input name="clients_employer" type="hidden" value="<?php echo $_GET["clients_employer"];?>" />
<div align="center">
<table width="99%" class="blocos">	
	<tr>
		<td><div align="right">
			Dado do Cliente:</br>
			CPF:</br>
			</div>
		</td>
		<td><div align="left">
			<strong><?php echo $_GET["clients_nm"];?></strong></br>
			<strong><?php echo $_GET["clients_cpf"];?></strong></br>	
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
	while($row_user_form = mysql_fetch_array( $result_user_form )) {
		if ($row_user_form["id"] == $_GET["vendas_consultor"]){$selected = "selected";}else{$selected = "";}
		echo "<option value='{$row_user_form['id']}'{$selected}>{$row_user_form['name']}</option>";
	}
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
	<tr>
		<td>
			<div align="right"><label for="vendas_debito_banco">Telefone de Contato da Venda:</label></div>
		</td>
		<td>
		<div align="left">			
			Fixo: <input type="text" name="vendas_telefone" value="<?php echo $_GET["vendas_telefone"]; ?>" size="11" maxlength="12" onKeyPress="return SomenteNumero(event)"/> 
			Celular: <input type="text" name="vendas_telefone2" value="<?php echo $_GET["vendas_telefone2"]; ?>" size="11" maxlength="12" onKeyPress="return SomenteNumero(event)"/>
		</div>
		</td>
	</tr>
<?php if ($administracao == 1):?>
                <tr>
                  <td><div align="right"><label for="vendas_status">Status:</label></div></td>
				  <td><div align="left">
					<select name="vendas_status">
						<option value="" selected> --- Selecione --- </option>
<?php
	$result_status = mysql_query("SELECT * FROM sys_vendas_status_seg ORDER BY status_id;")
	or die(mysql_error());
	while($row_status = mysql_fetch_array( $result_status )) {
		if ($row_status["status_id"] == $_GET["vendas_status"]){$selected = "selected";}else{$selected = "";}
		echo "<option value='{$row_status['status_id']}'{$selected}>{$row_status['status_nm']}</option>";
	}
?>
                    </select>
				</div>
				</td>
             </tr>	
		<tr>
		<td><div align="right"><label for="dp-normal-1">Data da Venda:</label></div></td>
		<td><div align="left">			
			<p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-1" name="dp-normal-1" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-1"];?>" /></p>
			</div>
		</td>
		</tr>
<?php endif;?>	
</table>
<h3 class="mypets">Dados da Proposta:</h3>
<table width="99%" class="blocos">
    <tr>
		<td><div align="right"><label for="vendas_banco">Seguradora:</label></div></td>
		<td><div align="left">
			<select name="vendas_banco" onchange="this.form.submit()">
<?php
	if (!$_GET["vendas_banco"]){echo "<option value='' selected> --- Selecione --- </option>";}
	$result_banco = mysql_query("SELECT * FROM sys_vendas_banco_seg WHERE banco_ativo = 1 ORDER BY banco_nm;")
	or die(mysql_error());
	while($row_banco = mysql_fetch_array( $result_banco )) {
		if ($row_banco["banco_id"] == $_GET["vendas_banco"]){$selected = " selected";}else{$selected = "";}
		echo "<option value='{$row_banco['banco_id']}'{$selected}>{$row_banco['banco_nm']}</option>";
	}
?>
			</select></div>
		</td>
    </tr>
<?php if ($_GET["vendas_banco"]) :?>
	<tr>
		<td width="40%" >
		<div align="left">			
			<label for="vendas_apolice">Apólice:</label><br />
<?php
	$vendas_banco = $_GET["vendas_banco"];
	$result_apolice = mysql_query("SELECT * FROM sys_vendas_apolices WHERE apolice_banco = '".$vendas_banco."' AND apolice_ativa=1 ORDER BY apolice_nome;")
	or die(mysql_error());
	while($row_apolice = mysql_fetch_array( $result_apolice )) {
		if ($row_apolice["apolice_id"] == $_GET["vendas_apolice"]){
			$checked = " checked";
			$vendas_valor = $row_apolice['apolice_valor'];
		}else{$checked = "";}
		$apolice_valor = ($row_apolice['apolice_valor']>0) ? number_format($row_apolice['apolice_valor'], 2, ',', '.') : '0' ;
		echo "<input type='radio' id='vendas_apolice' name='vendas_apolice' value='{$row_apolice['apolice_id']}'{$checked}> <label for='vendas_apolice'>R$ {$apolice_valor}</label><br />";
	}
?>
<input name="vendas_valor" type="hidden" id="vendas_valor" value="<?php echo $vendas_valor; ?>" />
		</div>
		</td>
		<td width="60%">
		<div align="left">			
			<label for="vendas_dia_desconto">Data de Desconto:</label></br>
				<select name="vendas_dia_desconto">
<?php
	if (!$_GET["vendas_dia_desconto"]){echo "<option value='' selected> --- Selecione --- </option>";}
	for ($i=1;$i<31;$i++){
		if ($i < 10){$dia = "0".$i;}else{$dia = $i;}
		if ($dia == $_GET["vendas_dia_desconto"]){$selected = " selected";}else{$selected = "";}
		echo "<option value='".$dia."'".$selected.">".$dia."</option>";
	}
?>	
				</select>
		</div>
		</td>		
	</tr>
<?php endif; ?>
	<tr>
        <td colspan="2">
<h3 class="mypets">Dados Financeiros:</h3>
		</td>		
	</tr>
               <tr>
                  <td><div align="right"><label for="vendas_pgto">Forma de Pagamento:</label></div></td>
				  <td><div align="left">
					<select name="vendas_pgto" onchange="this.form.submit()">
<?php
	if (!$_GET["vendas_pgto"]){echo "<option value='' selected> --- Selecione --- </option>";}
	$result_pgto = mysql_query("SELECT * FROM sys_vendas_pgto ORDER BY pgto_nm;")
	or die(mysql_error());
	while($row_pgto = mysql_fetch_array( $result_pgto )) {
		if ($row_pgto["pgto_id"] == $_GET["vendas_pgto"]){$selected = "selected";}else{$selected = "";}
		echo "<option value='{$row_pgto['pgto_id']}'{$selected}>{$row_pgto['pgto_nm']}</option>";
	}
?>
                    </select></div>
				</td>
                  </tr>
<?php if ($_GET["vendas_pgto"]) :?>
<?php if ($_GET["vendas_pgto"] == 1) :?>
	<tr>
		<td colspan="2">
			<div align="center">
				Banco: <input type="text" name="vendas_debito_banco" value="<?php echo $row_cliente["cliente_banco"]; ?>" size="4" maxlength="4" onKeyPress="return SomenteNumero(event)"/> &nbsp; &nbsp; 
				Agência: <input type="text" name="vendas_debito_ag" value="<?php echo $row_cliente["cliente_agencia"]; ?>" size="8" maxlength="8"/> &nbsp; &nbsp; 
				Conta Corrente: <input type="text" name="vendas_debito_cc" value="<?php echo $row_cliente["cliente_conta"]; ?>" size="12" maxlength="12"/>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div align="center">
				Banco: <input type="text" name="vendas_debito_banco_2" size="4" maxlength="4" onKeyPress="return SomenteNumero(event)"/> &nbsp; &nbsp; 
				Agência: <input type="text" name="vendas_debito_ag_2" size="8" maxlength="8"/> &nbsp; &nbsp; 
				Conta Corrente: <input type="text" name="vendas_debito_cc_2" size="12" maxlength="12"/>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div align="center">
				Banco: <input type="text" name="vendas_debito_banco_3" size="4" maxlength="4" onKeyPress="return SomenteNumero(event)"/> &nbsp; &nbsp; 
				Agência: <input type="text" name="vendas_debito_ag_3" size="8" maxlength="8"/> &nbsp; &nbsp; 
				Conta Corrente: <input type="text" name="vendas_debito_cc_3" size="12" maxlength="12"/>
			</div>
		</td>
	</tr>
<?php elseif ($_GET["vendas_pgto"] == 2) :?>
	<tr>
		<td>
			<div align="right"><label for="vendas_cartao_adm">Adm. do Cartão:</label></div>
		</td>
		<td>
		<div align="left">			
			<input type="text" name="vendas_cartao_adm" size="15" maxlength="20"/>
		</div>
		</td>
	</tr>
	<tr>
		<td>
			<div align="right"><label for="vendas_cartao_band">Bandeira:</label></div>
		</td>
		<td>
		<div align="left">			
			<input type="text" name="vendas_cartao_band" size="10" maxlength="20"/>
		</div>
		</td>
	</tr>
	<tr>
		<td>
			<div align="right"><label for="vendas_cartao_num">Nº do Cartão:</label></div>
		</td>
		<td>
		<div align="left">			
			<input type="text" name="vendas_cartao_num" id="vendas_cartao_num" size="19" maxlength="19" onKeyPress="return SomenteNumero(event)"/>
		</div>
		</td>
	</tr>
	<tr>
		<td>
			<div align="right"><label for="vendas_cartao_validade">Validade:</label></div>
		</td>
		<td>
		<div align="left">			
			<input type="text" name="vendas_cartao_validade" size="5" maxlength="5"/> (MM/AA)
		</div>
		</td>
	</tr>
	<tr>
		<td>
			<div align="right"><label for="vendas_cartao_cvv">CVV:</label></div>
		</td>
		<td>
		<div align="left">			
			<input type="text" name="vendas_cartao_cvv" size="3" maxlength="3" onKeyPress="return SomenteNumero(event)";}/>
		</div>
		</td>
	</tr>
<?php else:?>
	<tr>
		<td colspan="2">
			<div align="center">Dados de Desconto em Folha já salvos na Ficha do cliente.</div>
		</td>
	</tr>
<?php endif;?>
<?php endif;?>
	<tr>
        <td colspan="2">
<h3 class="mypets">Beneficiários:</h3>
	<br />

	<span id="campo_beneficiarios">
	</span>
	
	<span id="add_btn"><input type="button" value="Adicionar Beneficiário" onclick="addCampo();" /></span>
	<span id="remove_btn"></span>
		</td>		
	</tr>
	<tr>
		<td colspan="2">
			<label for="vendas_obs">Observações:</label></br>
			<textarea name="vendas_obs" cols="70" rows="3" id="obs"></textarea>
		</td>
	</tr>
<?php if (($_GET["vendas_pgto"])&&($_GET["vendas_banco"])) :?>
	<tr>
		<td colspan="2">
			<button name="salvar" type="submit" value="salvar">Salvar Venda</button>
		</td>
	</tr>
<?php endif;?>
</table>
</form>
</body>
<?php endif;?>
<br /><?php echo date("d/m/Y H:i:s"); ?><br />	
</html>