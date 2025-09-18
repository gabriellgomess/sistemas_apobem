<?php include("sistema/utf8.php"); ?>
<link type="text/css" href="/sistema/templates/gk_music/css/template.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/layout.css" rel="stylesheet"></link>
<link type="text/css" href="/sistema/templates/gk_music/css/joomla.css" rel="stylesheet"></link>
<script src="sistema/js/jquery-2.1.4.min.js"></script>
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
<script language="javascript">
var cont = 1;

function verificarCPF(cpf){
    /* PREPARACAO DOS DADOS */
    var digitos = cpf.substring(0, cpf.length - 2);
    var primeiro_validador = parseInt(cpf.charAt(9));
    var segundo_validador = parseInt(cpf.charAt(10));

    /* DEFININDO OS VALORES BASE DO CALCULO */
    var multiplicadores_primeiro_digito = 10;
    var multiplicadores_segundo_digito = 11;
    var primeiro_digito = 0;
    var segundo_digito = 0;


    for( i=0; i<digitos.length; i++ ){
        primeiro_digito += parseInt(digitos.charAt(i)) * (multiplicadores_primeiro_digito--);
	}
	var aux_primeiro_digito = 11 - (primeiro_digito % 11);
    primeiro_digito = aux_primeiro_digito > 9 ? 0 : aux_primeiro_digito;

    digitos = (digitos + primeiro_digito);

    for( i=0; i<digitos.length; i++ ){
        segundo_digito += parseInt(digitos.charAt(i)) * (multiplicadores_segundo_digito--);
    }
	var aux_segundo_digito =  11 - (segundo_digito % 11);
    segundo_digito = aux_segundo_digito > 9 ? 0 : aux_segundo_digito;

 
    if(primeiro_validador == primeiro_digito && segundo_validador == segundo_digito){
        return true;
    }

    return false;
}


function emit_alert(el, color){
	el.style.color = color;
	el.style.boxShadow = "0 0 7px " + color;
}

function reset_style(el){
	el.style.color = "#000";
	el.style.boxShadow = "none";
}

document.addEventListener('DOMContentLoaded', function(){
	$("input[name='cpf_geral'], input[name='cpf_mex']").on('keyup', function(e){
		var self = e.target;
		if( self.value.length == 0 ){
			reset_style(self)
			console.log("vazio");
		}
		else if( self.value.length >= 11 ){
			if(verificarCPF(self.value)){
				emit_alert(self, "green");
				console.log("CPF Válido");
			}
			else{
				emit_alert(self, "red");
				console.log("CPF Inválido");
			}
		}
	});
});



function addCampo()
{

	if(cont==1){
		document.getElementById('remove_btn').innerHTML = '<input type="button" value="Remove Dívida" onclick="removeCampo();" />';
	}
	
	if(cont<=10)
	{
		elem = document.createElement("SPAN");
		elem.className = "removivel";
		elem.innerHTML = "<span id='linha"+cont+"'><div style='width:50%; float: left;'><select style='float:none;' name='compra_banco"+cont+"'>"+document.getElementById("conteudo_select").innerHTML+"</select></div><div style='width:50%'>Nº do Contrato: <input type='text' id='compra_contrato"+cont+"' name='compra_contrato"+cont+"' placeholder='contrato a ser comprado' onKeyPress='return SomenteNumero(event)'></div><br /><div style='width:50%; float: left;'>Parcela: <input type='text' id='compra_valor"+cont+"' name='compra_valor"+cont+"' maxlength='10' size='10' placeholder='valor da parcela' onKeyPress="+"return(MascaraMoeda(this,'.',',',event))"+"></div><div style='width:50%'>Saldo: <input type='text' id='compra_saldo"+cont+"' name='compra_saldo"+cont+"' maxlength='10' size='10' placeholder='saldo devedor' onKeyPress="+"return(MascaraMoeda(this,'.',',',event))"+"></div><br /><div style='width:50%; float: left;'>Prazo do Contrato: <input type='text' id='compra_prazo"+cont+"' name='compra_prazo"+cont+"' maxlength='2' size='2' onKeyPress='return SomenteNumero(event)'/></div><div style='width:50%'>Parcelas em aberto: <input type='text' id='compra_parcelas"+cont+"' name='compra_parcelas"+cont+"' maxlength='2' size='2' onKeyPress='return SomenteNumero(event)'/></div><br /><div style='width:50%; float: left;'>Vencimento: <input type='text' id='compra_venc"+cont+"' name='compra_venc"+cont+"' maxlength='10' size='10' placeholder='dd/mm/aaaa'/></div><div style='width:50%'>&nbsp;</div><br /><hr><br /></span>";
		document.getElementById("campo_compra_divida").appendChild(elem);

		cont++;
	}
	if(cont==11)
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
	if(document.getElementById("add_btn").innerHTML == "" && cont<11)
	{
		document.getElementById("add_btn").innerHTML = '<input type="button" value="Adicionar Dívida" onclick="addCampo();" />'
	}
}

function consultaAjax() {
		var user_unidade = "<?php echo $user_unidade; ?>";
		var vendas_orgao = document.getElementsByName("vendas_orgao")[0].value;
		var vendas_produto = document.getElementsByName("vendas_produto")[0].value;
		var vendas_banco, vendas_tipo_contrato, vendas_percelas, vendas_tabela, vendas_valor_parcela;
		document.getElementsByName("vendas_banco")[0] ? vendas_banco = document.getElementsByName("vendas_banco")[0].value : vendas_banco = "";
		document.getElementsByName("vendas_tipo_contrato")[0] ? vendas_tipo_contrato = document.getElementsByName("vendas_tipo_contrato")[0].value : vendas_tipo_contrato = "";
		document.getElementsByName("vendas_percelas")[0] ? vendas_percelas = document.getElementsByName("vendas_percelas")[0].value : vendas_percelas = "";
		document.getElementsByName("vendas_tabela")[0] ? vendas_tabela = document.getElementsByName("vendas_tabela")[0].value : vendas_tabela = "";
		document.getElementsByName("vendas_valor_parcela")[0] ? vendas_valor_parcela = document.getElementsByName("vendas_valor_parcela")[0].value : vendas_valor_parcela = "";
		document.getElementsByName("vendas_valor")[0] ? vendas_valor = document.getElementsByName("vendas_valor")[0].value : vendas_valor = "";

		if(vendas_tipo_contrato == "2" || vendas_tipo_contrato == "3" || vendas_tipo_contrato == "4" || vendas_tipo_contrato == "5" || 
			vendas_tipo_contrato == "9" || vendas_tipo_contrato == "13" || vendas_tipo_contrato == "14" || vendas_tipo_contrato == "15" || vendas_tipo_contrato == "20" )
		{
			document.getElementById("compra_d").style.display = "inherit";
		}
		else
		{
			document.getElementById("compra_d").style.display = "none";
			document.getElementById("campo_compra_divida").innerHTML = "";
			document.getElementById('remove_btn').innerHTML = "";
			cont = 1;
		}


        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("retornoAjax").innerHTML = xmlhttp.responseText;
                check_selects();
            }
        };
        xmlhttp.open("GET", "sistema/consultas_ajax/consulta.php?vendas_orgao="+vendas_orgao+"&vendas_produto="+vendas_produto+"&vendas_banco="+vendas_banco+"&vendas_tipo_contrato="+vendas_tipo_contrato+"&vendas_percelas="+vendas_percelas+"&vendas_tabela="+vendas_tabela+"&vendas_valor_parcela="+vendas_valor_parcela+"&vendas_valor="+vendas_valor+"&user_unidade="+user_unidade, true);
        xmlhttp.send();    
}

function check_selects(){
  	counter = 0;
	for(i=0; i<document.getElementsByTagName("select").length; i++)
    {
	   if ( document.getElementsByTagName("select")[i].value == "")
       {
         document.getElementById("save_button").innerHTML = "";
         counter+=1;
         break;
       }
    }

    if(counter==0)
    {
    	document.getElementById("save_button").innerHTML = '<button name="salvar" type="submit" value="salvar" onclick="salvando_modal()">Salvar Venda</button>';
    }

}
</script>

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

function salvando_modal(){
			svng = document.createElement('div');
			svng.style.position = 'fixed';
			svng.style.width = '100%';
			svng.style.height = '100vh';
			svng.style.top = '0';
			svng.style.left = '0';
			svng.style.background = "rgba(0,0,0,0.5)";
			svng.style.textAlign = "center";
			svng.style.zIndex = "99999";

			gif = document.createElement('img');
			gif.src = 'sistema/imagens/loading_gif.gif';
			gif.style.width = "5%";
			gif.style.display = "inline-block";
			gif.style.transform = "translateY( calc(50vh - 100%) )";

			svng.appendChild(gif);

			document.body.appendChild(svng);
		}
</script>
<?php if ($_GET["salvar"] == "salvar"):?>
<?php include("sistema/vendas/insere.php");?>
<?php else: ?>
<?php if (($_GET["clients_cpf"]) || ($_GET["cpf_geral"]) || ($_GET["cpf_mex"])): ?>
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
	persiststate: false, //persist state of opened contents within browser session?
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
<?php else: ?>
<script type="text/javascript">
//Initialize first demo:
ddaccordion.init({
	headerclass: "mypets", //Shared CSS class name of headers group
	contentclass: "thepet", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
	persiststate: false, //persist state of opened contents within browser session?
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
<?php endif; ?>
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
<body>
<?php 
$user =& JFactory::getUser();
$username=$user->username;
$consultor=$user->name;
$user_id=$user->id;
$diretoria = 0;
$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '10'){$administracao = 1;}
	if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if ($row_grupo_user['id'] == '28'){$financeiro = 1;}
	if ($row_grupo_user['id'] == '21'){$franquiado = 1;}
	if ($row_grupo_user['id'] == '11'){$sup_operacional = 1;}
	if ($row_grupo_user['id'] == '23'){$frame_revisadas = 1;}
	if ($row_grupo_user['id'] == '24'){$frame_averbadas = 1;}
	if ($row_grupo_user['id'] == '25'){$frame_fisicos = 1;}
	if ($row_grupo_user['id'] == '27'){$frame_fracionadas = 1;}
	if ($row_grupo_user['id'] == '38'){$frame_autorizacao = 1;}
	if ($row_grupo_user['id'] == '34'){$supervisor_agentes = 1;}
	if ($row_grupo_user['id'] == '30'){$operacional_agentes = 1;}
	if ($row_grupo_user['id'] == '37'){$supervisor_equipe_vendas = 1;}
	if ($row_grupo_user['id'] == '39'){$exclusao_vendas = 1;}
	if ($row_grupo_user['id'] == '43'){$supervisor_treinamento = 1;}
	if ($row_grupo_user['id'] == '52'){$juridico = 1;}
	if ($row_grupo_user['id'] == '58'){$coordenador_plataformas = 1;}
	if ($row_grupo_user['id'] == '76'){$gerente_plataformas = 1;}
	if ($row_grupo_user['id'] == '53'){$consultores_vendas_internet = 1;}
}

if ($_GET["clients_employer"]){$vendas_orgao = $_GET["clients_employer"];}else{$vendas_orgao = $_GET["vendas_orgao"];}
$banco_de_dados = 1;

$result_grupo = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error()); 

$result_user = mysql_query("SELECT nivel, sys_equipes.equipe_tipo, unidade FROM jos_users 
INNER JOIN sys_equipes ON jos_users.equipe_id = sys_equipes.equipe_id 
WHERE id = " . $user_id . ";") 
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );

$time = mktime(date('H')-3, date('i'), date('s'));

if ($_GET["clients_cpf"]){
	$clients_cpf = $_GET["clients_cpf"];
	$result_geral = mysql_query("SELECT cliente_cpf, cliente_nome, cliente_margem, cliente_empregador, cliente_campanha FROM sys_inss_clientes WHERE cliente_cpf = '" . $clients_cpf . "';") 
	or die(mysql_error());
	$row_geral = mysql_fetch_array( $result_geral );
	$cliente_margem = $row_geral["cliente_margem"];
	$vendas_orgao = $row_geral["cliente_empregador"];
	if (!$row_geral["cliente_cpf"]){
		$result_mex = mysql_query("SELECT clients_nm, clients_margem FROM sys_clients WHERE clients_cpf = '" . $clients_cpf . "';") 
		or die(mysql_error());
		$row_mex = mysql_fetch_array( $result_mex );
		$cliente_margem = $row_mex["clients_margem"];
	}
}

// PROCURA CLIENTE DB01
if ($_GET["cpf_geral"]){
	$cpf_geral = $_GET["cpf_geral"];
	$result_geral = mysql_query("SELECT cliente_cpf, cliente_nome, cliente_margem, cliente_empregador, cliente_campanha FROM sys_inss_clientes WHERE cliente_cpf = '" . $cpf_geral . "';") 
	or die(mysql_error());
	$row_geral = mysql_fetch_array( $result_geral );
	$cliente_margem = $row_geral["cliente_margem"];
	$vendas_orgao = $row_geral["cliente_empregador"];
}
elseif ($_GET["cpf_mex"]){
	$cpf_mex = $_GET["cpf_mex"];
	$result_mex = mysql_query("SELECT clients_cpf, clients_nm, clients_margem FROM sys_clients WHERE clients_cpf = '" . $cpf_mex . "';") 
	or die(mysql_error());
	$row_mex = mysql_fetch_array( $result_mex );
	$cliente_margem = $row_mex["clients_margem"];
}

// PROCURA CLIENTE DB02
if (($_GET["cpf_geral"])||($_GET["cpf_mex"])){
	if ((!$row_geral["cliente_cpf"])&&(!$row_mex["clients_cpf"])){
		include("sistema/connect_db02.php");
		if ($_GET["cpf_geral"]){
			$cpf_geral = $_GET["cpf_geral"];
			$result_geral = mysql_query("SELECT cliente_cpf, cliente_nome, cliente_margem, cliente_empregador, cliente_campanha FROM sys_inss_clientes WHERE cliente_cpf = '" . $cpf_geral . "';") 
			or die(mysql_error());
			$row_geral = mysql_fetch_array( $result_geral );
			$cliente_margem = $row_geral["cliente_margem"];
			$vendas_orgao = $row_geral["cliente_empregador"];
		}
		elseif ($_GET["cpf_mex"]){
			$cpf_mex = $_GET["cpf_mex"];
			$result_mex = mysql_query("SELECT clients_cpf, clients_nm, clients_margem FROM sys_clients WHERE clients_cpf = '" . $cpf_mex . "';") 
			or die(mysql_error());
			$row_mex = mysql_fetch_array( $result_mex );
			$cliente_margem = $row_mex["clients_margem"];
		}
		$banco_de_dados = 2;
		include("sistema/connect.php");
	}
}

include("../utf8.php");
$opcoes_select_banco = $opcoes_select_banco."<option value='' selected>-------- Banco a ser Comprado --------</option>";
$result_bancos_compra = mysql_query("SELECT * FROM sys_vendas_bancos_compra ORDER BY banco_codigo;")
or die(mysql_error());
while($row_bancos_compra = mysql_fetch_array( $result_bancos_compra )) {
	$opcoes_select_banco = $opcoes_select_banco."<option value='{$row_bancos_compra['banco_id']}'>{$row_bancos_compra['banco_codigo']} - {$row_bancos_compra['banco_nome']}</option>";
}
?>
<?php if ($_GET["clients_cpf"]): ?>
<form id="testform" action="index.php" method="GET">
					<input name="option" type="hidden" value="com_k2" />
					<input name="view" type="hidden" value="item" />
					<input name="id" type="hidden" value="64" />
					<input name="Itemid" type="hidden" value="479" />
					<?php if ($_GET["print"] == "1"): ?><input name="tmpl" type="hidden" value="component" />
					<input name="print" type="hidden" value="1" />	
					<input name="acao" type="hidden" value="nova_venda" /><?php endif;?>
					<input name="clients_cpf" type="hidden" value="<?php echo $_GET["clients_cpf"];?>" />
					<input name="clients_nm" type="hidden" value="<?php echo $_GET["clients_nm"];?>" />
<?php else: ?>
<form id="testform" action="index.php" method="GET">
					<input name="option" type="hidden" value="com_k2" />
					<input name="view" type="hidden" value="item" />
					<input name="id" type="hidden" value="64" />
					<input name="Itemid" type="hidden" value="479" />
					<?php if ($_GET["print"] == "1"): ?><input name="tmpl" type="hidden" value="component" />
					<input name="print" type="hidden" value="1" />	
					<input name="acao" type="hidden" value="nova_venda" /><?php endif;?>
<?php endif; ?>
<div align="center">
<table width="99%" class="blocos">	
	<tr>
<?php if ($_GET["clients_cpf"]): ?>
		<td><div align="right">
			Nome do Cliente:</br>
			CPF:</br>
			Orgão:</br>
			</div>
		</td>
	<?php if ($_GET["banco_de_dados"] == 2){include("sistema/cliente/insere_inss_db02.php");} ?>
		<td><div align="left">
			<strong><?php echo $_GET["clients_nm"];?></strong></br>
			<strong><?php echo $_GET["clients_cpf"];?></strong></br>
				<select name="vendas_orgao" onchange="consultaAjax()">
				<option value='' selected>------ Selecione ------</option>
<?php
	//if ($vendas_orgao == "") {echo "<option value='' selected>------ Órgão ------</option>";}else{echo "<option value='{$vendas_orgao}' selected>{$vendas_orgao}</option>";}
	$result_orgao = mysql_query("SELECT * FROM sys_orgaos ORDER BY orgao_nome;")
	or die(mysql_error());
	while($row_orgao = mysql_fetch_array( $result_orgao )) {
		if ($row_orgao["orgao_nome"] == $vendas_orgao){$selected = "selected";}else{$selected = "";}
		echo "<option value='{$row_orgao['orgao_nome']}'{$selected}>{$row_orgao['orgao_label']}</option>";
	}
?>
                </select>
			</div>		
		</td>			
<?php else: ?>
	<td><div align="center">
		<span style="color:red"><strong>*Por favor, selecione ou cadastre o cliente para Prosseguir!</strong></span><br />
		<h5 class="mypets">SELECIONAR CLIENTE</h5><div class="thepet">
			<table>
				<tr>
	<?php if ($row_geral["cliente_nome"]): ?>
		<td><div align="center"><a href="index.php?option=com_k2&view=item&layout=item&id=64&Itemid=479&clients_employer=INSS&clients_cpf=<?php echo $cpf_geral;?>&clients_nm=<?php echo $row_geral["cliente_nome"];?>&banco_de_dados=<?php echo $banco_de_dados;?>"><?php echo $row_geral["cliente_nome"];?><br />
		CPF: <?php echo $cpf_geral;?> <br />
		<input title="Selecionar Cliente <?php echo $row_geral["cliente_nome"];?>" type="button" value="SELECIONAR"></a></div></td>
	<?php elseif ($row_mex["clients_nm"]): ?>
		<td><div align="center"><a href="index.php?option=com_k2&view=item&layout=item&id=64&Itemid=479&clients_employer=Exercito&clients_cpf=<?php echo $cpf_mex;?>&clients_nm=<?php echo $row_mex["clients_nm"];?>&banco_de_dados=<?php echo $banco_de_dados;?>"><?php echo $row_mex["clients_nm"];?><br />
		CPF: <?php echo $cpf_mex;?> <br />
		<input title="Selecionar Cliente <?php echo $row_mex["clients_nm"];?>" type="button" value="SELECIONAR"></a></div></td>
	<?php else: ?>
		<td><div align="center"><img src="sistema/imagens/cliente_geral.jpg"><br />
		Selecionar Cliente GERAL<br />
		CPF: <input name="cpf_geral" type="text" maxlength="11" size="11" /><div style="float:right;"><button class="button validate png" type="submit">OK</button></div></div></td>
		<td><div align="center"><img src="sistema/imagens/cliente_mex.jpg"><br />
		Selecionar Cliente Exército<br />
		CPF: <input name="cpf_mex" type="text" maxlength="11" size="11" /><div style="float:right;"><button class="button validate png" type="submit">OK</button></div></div>
		</div></td>
	<?php endif; ?>
				</tr>
			</table>
		</div> ou 
			<h5 class="mypets">CADASTRAR CLIENTE.</h5><div class="thepet">
							<table>
					<tr>
						<td><div class="itemImageBlock100" align="center"><a href="index.php?option=com_k2&view=item&layout=item&id=61&Itemid=450"><img src="sistema/imagens/cliente_geral.jpg"><br />Cadastrar Cliente GERAL</div></a></td>
						<td><div class="itemImageBlock100" align="center"><a href="index.php?option=com_k2&view=item&layout=item&id=61&Itemid=451"><img src="sistema/imagens/cliente_mex.jpg"><br />Cadastrar Cliente Exército</div></a></div></td>
					</tr>
				</table>
			</div>
			</div>
		</td>
<?php endif; ?>
	</tr>	
	<tr>
		<td><div align="right">Origem:</td>
		<td>
			<div align="left">
			<?php if($row_geral["cliente_campanha"] == "84"): ?>
				<strong>Carteira Publicidade Internet</strong>
				<input name="vendas_origem" type="hidden" value="2" />
			<?php else: ?>
				<select name="vendas_origem" onchange="consultaAjax();">
					<option value=""> --- Selecione --- </option>
					<?php
						$result_origem = mysql_query("SELECT * FROM sys_vendas_origens ORDER BY origem_id;")
						or die(mysql_error());
						while($row_origem = mysql_fetch_array( $result_origem )) {
							if ($row_origem["origem_id"] == $_GET["vendas_origem"]){$selected_promo = "selected";}else{$selected_promo = "";}
							echo "<option value='{$row_origem['origem_id']}'{$selected_promo}>{$row_origem['origem_nome']}</option>";
						}
					?>
				</select>
			<?php endif; ?>
			</div>
		</td>
	</tr>
	<tr>
		<td><div align="right">			
			Dados do Consultor:
			</div>
		</td>
<?php if (($coordenador_plataformas == 1)||($gerente_plataformas == 1)||($supervisor_agentes)||($operacional_agentes)||($row_user["equipe_tipo"] == 2)):?>
		<td>
			<div align="left">
			<select name='vendas_consultor'>
			<option value='<?php echo $user_id;?>'><?php echo $consultor;?></option>
	<?php	
		if($coordenador_plataformas == 1){
			$filtro_consultores = " AND sys_equipes.equipe_coordenador = '".$user_id."'";
		}
		
		if ($gerente_plataformas){
			$filtro_consultores = " AND equipe_tipo = 2";
		}
		//if ($row_user["equipe_tipo"] == 2){$filtro_consultores = $filtro_consultores." AND unidade LIKE '".$row_user['unidade']."'";}
		
		$result_user_form = mysql_query("SELECT id,name,unidade FROM jos_users 
		LEFT JOIN sys_equipes ON jos_users.equipe_id = sys_equipes.equipe_id 
		WHERE 1 ".$filtro_consultores." 
		ORDER BY name;")
		or die(mysql_error());
		while($row_user_form = mysql_fetch_array( $result_user_form ))
		{
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
</table>
<?php if ($_GET["clients_cpf"]): ?>
<h3 class="mypets">Dados da Proposta:</h3>
<div class="thepet">
<table width="99%" class="blocos" onchange="consultaAjax();">
	<tr>
		<td width="50%">
		<div align="left">			
			<label for="vendas_valor_parcela">Valor da Parcela:</label>(somente números)</br>
			<?php $cliente_margem = ($cliente_margem>0) ? number_format($cliente_margem, 2, ',', '.') : '0' ;?>
			R$ <input type="text" name="vendas_valor_parcela" size="15" value="<?php if ($_GET["vendas_valor_parcela"]){echo $_GET["vendas_valor_parcela"];}else{echo $cliente_margem;}?>" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
			</div>
		</td>
		<td>
		<div align="left">		
			<label for="vendas_produto">Produto:</label></br>
			<select name="vendas_produto">
			<option value="">Selecione o Produto</option>
			<?php 
				$result_produtos = mysql_query("SELECT * FROM sys_vendas_produtos;") or die(mysql_error()); 
				while($row_produto = mysql_fetch_array( $result_produtos )) {
					if ($row_produto["produto_id"] == $_GET["vendas_produto"]){$selected = "";}else{$selected = "";}
					echo "<option value='".$row_produto['produto_id']."'{$selected}>".$row_produto['produto_nome']."</option>";
				}
			?>
			</select>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php //MARCACAO #################################################################?>
			<table id="retornoAjax" width="99%" class="blocos" onchange="consultaAjax();">
			<tr >
				<td width="50%">
				<div align="left">			
					<label for="vendas_banco">Banco:</label></br>
					<select name="vendas_banco" >
					<option value="">Selecione o Banco</option>
					</select>
					</div>
				</td>
				<td>
				<div align="left">
					<label for="vendas_tipo_contrato">Tipo de Contrato:</label></br>
					<select name="vendas_tipo_contrato" >
					<option value="">Selecione o Tipo de Contrato</option>
					</select>
					</div>
				</td>		
			</tr>
			<tr height="85px">
				<input name="vendas_percelas" type="hidden" value="1" />
				<td>
				<div align="left">	
					<label for="vendas_percelas">Prazo:</label></br>
						Selecione o Banco e o Tipo de Contrato para prosseguir.
					</div>
				</td>
				<td>
				<div align="left">
					<label for="vendas_tabela">Tabela:</label></br>
						Selecione o Banco, o Tipo e o Prazo para prosseguir.
					</div>
				</td>		
			</tr>
			<tr>
				<td colspan="2">
				<div align="left">			
					<label for="vendas_valor">AF. Valor do Contrato:</label>(somente números)</br>			
					R$ <input type="text" name="vendas_valor" size="15" value="" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
					</div>
				</td>
			</tr>
		</table>
			<?php //FIM MARCACAO ##################################################################?>
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
	<tr>
		<td colspan="2">
		<div align="left">			
			Liberação de Margem: 
				<select name="vendas_jud">
				  <option value="1" <?php if ($_GET['vendas_jud'] == "1"){echo "selected";}?>>Normal</option>
				  <option value="2" <?php if ($_GET['vendas_jud'] == "2"){echo "selected";}?>>Via Jurídico</option>
				</select>
			</div>
		</td>
	</tr>

		<tr>			
			<td colspan="2">
				<div align="left">
					Cartão consignado:
						<select name="vendas_cartao_consig">
							<option value="">- Selecione o Banco -</option>
							<option value="0">- Não possui cartão consignado -</option>
							<?php
							$sql_bancos = "SELECT vendas_bancos_id, vendas_bancos_nome
									FROM sys_vendas_bancos;";
							$result_bancos = mysql_query($sql_bancos) or die(mysql_error());  
							while($row_bancos = mysql_fetch_array( $result_bancos )): ?>
							<?php
								$selected = "";
								if($row_venda['vendas_cartao_consig'] == $row_bancos['vendas_bancos_id']){ $selected = " selected"; }
							?>
								<option value="<?php echo $row_bancos['vendas_bancos_id']; ?>" <?php echo $selected; ?>><?php echo $row_bancos['vendas_bancos_nome']; ?></option>					
							<?php endwhile;?>
						</select>
				</div>
			</td>
		</tr>

</table>
</div>
<?php if ($row_user["nivel"] != 4): ?>
<h3 class="mypets">Seguro:</h3>
<div class="thepet">
<table width="99%" class="blocos">	
<!--
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
-->
		<tr>
		<td>
		<div align="left">			
			<label for="vendas_applus_valor">Valor do Seguro:</label>(somente números)</br>
			R$ <input type="text" name="vendas_applus_valor" size="15" value="<?php echo $_GET["vendas_applus_valor"];?>" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
			</div>
		</td>
        <td style="text-align: left;"> <label for="vendas_seguro_protegido">Seguro Prestamista:</label></br>
			<select name="vendas_seguro_protegido">
			  <option value="1"<?php if ($_GET['vendas_seguro_protegido'] == "1"){echo " selected";}?>>Não</option>
			  <option value="2"<?php if ($_GET['vendas_seguro_protegido'] == "2"){echo " selected";}?>>Sim</option>
			</select>
		</td>		
	</tr>
</table>
</div>
<?php endif;?>

<span id="compra_d" style="display:none">
<h3 class="mypets">Compra de dívida:</h3>
<div class="thepet">
	<br />

	<span id="campo_compra_divida">
	</span>

	<span id="add_btn"><input type="button" value="Adicionar Dívida" onclick="addCampo();" /></span>
	<span id="remove_btn"></span>
	<br /><br />
</div>
</span>
		<table width="99%" class="blocos">	
		<tr>
		<td colspan="2">
			<label for="vendas_obs">Observações:</label></br>
			<textarea name="vendas_obs" cols="70" rows="3" id="obs" value="<?php echo $_GET["vendas_obs"];?>"></textarea>
		</td>
		</tr>
		<tr>
		<td colspan="2" id="save_button">
		</td>
		</tr>
		</div>
		</table>
<?php endif; ?>		
</form>
<?php endif;?>
<span id="conteudo_select" style="display:none"> <?php echo $opcoes_select_banco; ?> </span>
<br /><?php echo date("d/m/Y H:i:s"); ?><br />	
</body>
</html>