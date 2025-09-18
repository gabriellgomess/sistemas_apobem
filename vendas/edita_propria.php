<link href="templates/gk_music/css/template.portal.css" rel="stylesheet" type="text/css" />
<script src="sistema/js/jquery-2.1.4.min.js"></script>

<?php
include("sistema/utf8.php");
$user =& JFactory::getUser();
$username=$user->username;
$userid=$user->id;

//if($username == 'admin'){ $username = 'thalita.ruhle'; }
//if($userid == '42'){ $userid = '1666'; }

//if($userid == '957'){ $userid = '432'; }
//if($username == 'horacio.admin'){ $username = 'paulo.silva'; }


$result_url = mysql_query("SELECT url_consulta_clientes FROM jos_users WHERE id = " . $userid . ";") 
or die(mysql_error());  
$row_url = mysql_fetch_array( $result_url );
$link_consulta = $row_url["url_consulta_clientes"];
?>
<?php if ($_GET["fechar"] == "1"):?>
<meta http-equiv="Refresh" content="0; url=<?php echo $link_consulta;?>">
<?php else: ?>
<script type="text/javascript" src="sistema/vendas/js/datepicker.js"></script>
<link href="sistema/vendas/css/datepicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
//<![CDATA[

/* The following function creates a new input field and then calls datePickerController.create();
   to dynamically create a new datePicker widgit for it */
function newline() {
        var total = document.getElementById("newline-wrapper").getElementsByTagName("table").length;
        total++;

        // Clone the first div in the series
        var tbl = document.getElementById("newline-wrapper").getElementsByTagName("table")[0].cloneNode(true);

        // DOM inject the wrapper div
        document.getElementById("newline-wrapper").appendChild(tbl);

        var buts = tbl.getElementsByTagName("a");
        if(buts.length) {
                buts[0].parentNode.removeChild(buts[0]);
                buts = null;
        }

        // Reset the cloned label's "for" attributes
        var labels = tbl.getElementsByTagName('label');

        for(var i = 0, lbl; lbl = labels[i]; i++) {
                // Set the new labels "for" attribute
                if(lbl["htmlFor"]) {
                        lbl["htmlFor"] = lbl["htmlFor"].replace(/[0-9]+/g, total);
                } else if(lbl.getAttribute("for")) {
                        lbl.setAttribute("for", lbl.getAttribute("for").replace(/[0-9]+/, total));
                }
        }

        // Reset the input's name and id attributes
        var inputs = tbl.getElementsByTagName('input');
        for(var i = 0, inp; inp = inputs[i]; i++) {
                // Set the new input's id and name attribute
                inp.id = inp.name = inp.id.replace(/[0-9]+/g, total);
                if(inp.type == "text") inp.value = "";
        }

        // Call the create method to create and associate a new date-picker widgit with the new input
        datePickerController.create(document.getElementById("date-" + total));

        var dp = datePickerController.datePickers["dp-normal-1"];

        // No more than 5 inputs
        if(total == 5) document.getElementById("newline").style.display = "none";

        // Stop the event
        return false;
}

function createNewLineButton() {
    if(document.getElementById("newline-wrapper"))
    {
        var nlw = document.getElementById("newline-wrapper");

        var a = document.createElement("a");
        a.href="#";
        a.id = "newline";
        a.title = "Create New Input";
        a.onclick = newline;
        nlw.parentNode.appendChild(a);

        a.appendChild(document.createTextNode("+"));
        a = null;
    }
}

datePickerController.addEvent(window, 'load', createNewLineButton);

//]]>
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

function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
        if (tecla==8 || tecla==0) return true;
    else  return false;
    }
}
$(document).on("change", "#vendas_status", function(){
    if($(this).val() == 7 || $(this).val() == 8)
    {
        console.log($(this).val());
        $("#notificar_title").remove();
        $("<span id='notificar_title'><span style='font-size: 10px;'>Notificar cliente via SMS?</span><select id='notificar_cliente_sms' name='notificar_cliente_sms'><option value='1' >SIM</option><option value='0' >NÃO</option></select></span>").insertAfter("#vendas_status");
    }else{
        $("#notificar_title").remove();
    }
});
</script>
<script type="text/javascript">
//Initialize first demo:
ddaccordion.init({
    headerclass: "mypets2", //Shared CSS class name of headers group
    contentclass: "thepet2", //Shared CSS class name of contents group
    revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
    mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
    collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
    defaultexpanded: [], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
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

$vendas_id=$_GET["vendas_id"];
$result = mysql_query("SELECT * FROM sys_vendas WHERE sys_vendas.vendas_id = '" . $vendas_id . "';") 
or die(mysql_error());  
$row = mysql_fetch_array( $result );
?>
<input type="hidden" name="clients_cpf" value="<?php echo $row['clients_cpf']; ?>">

<?php if (($row["vendas_status"] != 13) && ($_GET["bloquear"] == "bloquear")):?>
<meta http-equiv="Refresh" content="2; url=index.php?option=com_k2&view=item&layout=item&id=127&Itemid=480">
<div align="center"></br>
    VENDA JÁ EM BACKOFFICE! </br>
    Analista: <?php echo $row['vendas_user']; ?>.</br></br>
    Retornando a Fila...
</div>
<?php else: ?>

<?php
if ($_GET["bloquear"] == "bloquear"){
$row["vendas_status"] = 14;
$query = mysql_query("UPDATE sys_vendas SET vendas_status='14', vendas_user='".$username."' WHERE vendas_id='$vendas_id' ") or die(mysql_error());
echo "Venda colocada em BackOffice Sucesso!<br/>";
}

$result_venda_cliente = mysql_query("SELECT COUNT(venda_cliente_id) AS cliente FROM sys_vendas_clientes WHERE vendas_id = '" . $vendas_id . "';") or die(mysql_error());  
$row_venda_cliente = mysql_fetch_array( $result_venda_cliente );

if ($row_venda_cliente["cliente"]){
    $result_client = mysql_query("SELECT * FROM sys_vendas_clientes WHERE vendas_id = '" . $vendas_id . "';") or die(mysql_error());  
    $row_client = mysql_fetch_array( $result_client );
}else{
    $result_client = mysql_query("SELECT cliente_nome, cliente_nascimento, cliente_rg, cliente_beneficio, cliente_endereco, cliente_bairro, cliente_cidade, cliente_cep, cliente_uf, cliente_telefone, cliente_celular FROM sys_inss_clientes WHERE cliente_cpf = '" . $row['clients_cpf'] . "';") 
    or die(mysql_error());  
    $row_client = mysql_fetch_array( $result_client );
    if (!$row_client["cliente_nome"]){
        $result_client = mysql_query("SELECT clients_nm AS cliente_nome, clients_birth AS cliente_nascimento, clients_rg AS cliente_rg, clients_street_complet AS cliente_endereco, clients_district AS cliente_bairro, clients_city AS cliente_cidade, clients_postalcode AS cliente_cep, clients_state AS cliente_uf, clients_contact_phone1 AS cliente_telefone, clients_contact_phone2 AS cliente_celular FROM sys_clients WHERE clients_cpf = '" . $row['clients_cpf'] . "';") 
        or die(mysql_error());  
        $row_client = mysql_fetch_array( $result_client );
    }
}

if (!$row_client['cliente_nome']){
	$clients_cpf = $row['clients_cpf'];
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
}
//if ($userid == 42){$userid = 1378;}

$result_user = mysql_query("SELECT username, name, situacao, nivel, unidade, equipe_id FROM jos_users WHERE id = '" . $row['vendas_consultor'] . "';") 
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );

$result_user_nivel = mysql_query("SELECT nivel, unidade FROM jos_users WHERE id = '" . $userid . "';") 
or die(mysql_error());
$row_user_nivel = mysql_fetch_array( $result_user_nivel );

$result_grupo = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $row['vendas_consultor'] . ";") 
or die(mysql_error());

$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $userid . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
    if ($row_grupo_user['id'] == '10'){$administracao = 1;}
    if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
    if ($row_grupo_user['id'] == '21'){$franquiado = 1;}
    if ($row_grupo_user['id'] == '11'){$sup_operacional = 1;}
    if ($row_grupo_user['id'] == '12'){$sup_vendas = 1;}
    if ($row_grupo_user['id'] == '37'){$supervisor_equipe_vendas = 1;}
	if ($row_grupo_user['id'] == '48'){$supervisor_de_unidade = 1;}
    if ($row_grupo_user['id'] == '60'){$admin_fisicos = 1;}
    if ($row_grupo_user['id'] == '59'){$pos_venda = 1;}
    if ($row_grupo_user['id'] == '52'){$juridico = 1;}
    if ($row_grupo_user['id'] == '56'){$seguros_consignado = 1; $administracao = 0;}
    if ($row_grupo_user['id'] == '58'){$coordenador_plataformas = 1; $administracao = 0; $supervisor_equipe_vendas = 1;}
	if ($row_grupo_user['id'] == '76'){$gerente_plataformas = 1; $administracao = 0; $supervisor_equipe_vendas = 1;}
    if ($row_grupo_user['id'] == '61'){$operacional_equipes = 1; $administracao = 1;}
    if ($row_grupo_user['id'] == '64'){$retencao_safra = 1;}
	if ($row_grupo_user['id'] == '43'){$supervisor_treinamento = 1; $administracao = 0;}
	if ($row_grupo_user['id'] == '41'){$operacional_fisico = 1;}
}
if ($operacional_fisico == 1){$administracao = 1;}

if ($administracao == 1){
    $select_permissao_tabela = "";
    echo "<input name='select_permissao_tabela' type='hidden' value=''/>"; 
}else{
    $select_permissao_tabela = "AND tabela_permissao = '1' AND (tabela_perfil_venda = '1' OR tabela_perfil_venda = '2') ";
    echo "<input name='select_permissao_tabela' type='hidden' value='AND tabela_permissao = '1' AND (tabela_perfil_venda = '1' OR tabela_perfil_venda = '2') '/>";
}
if ((($row_user_nivel["nivel"] == "6")||($row_user_nivel["nivel"] == "5"))&&($row_user_nivel["unidade"] == $row_user["unidade"])){$sup_operacional = 1; $administracao = 1;}

$result_anexos = mysql_query("SELECT * FROM sys_vendas_anexos WHERE vendas_id = " . $row['vendas_id'] . ";") 
or die(mysql_error());

if ($administracao == 0){$select_registro_restrito = " AND registro_restrito = '0'";
}else{if ($diretoria == 0){$select_registro_restrito = " AND registro_restrito <= '1'";}else{$select_registro_restrito = "";}}

if ($row['vendas_tipo_contrato'] != "6")
{
    $result_compras = mysql_query("SELECT * FROM sys_vendas_compras WHERE vendas_id = " . $row['vendas_id'] . ";") or die(mysql_error());
}

$result_status_nm = mysql_query("SELECT status_nm, status_liberado, status_proximo FROM sys_vendas_status WHERE status_id = " . $row['vendas_status'] . ";")
or die(mysql_error());
$row_status_nm = mysql_fetch_array( $result_status_nm );
$vendas_status_nm = $row_status_nm["status_nm"];
$vendas_status_proximo = $row_status_nm["status_proximo"];

if (($administracao == 0) && ($row_status_nm["status_liberado"] == 0)){$edicao = 0;}else{$edicao = 1;}
//echo "<pre>".$row_status_nm["status_liberado"]."</pre>";
//if ($juridico) {$edicao=0;}
if ($seguros_consignado) {
    $edicao=0;
    $acesso_liberado = 1;
    $acesso_negado = 0;
}

if($operacional_equipes){
    $result_eq_operacional = mysql_query("SELECT COUNT(equipe_id) AS total FROM sys_equipes 
    WHERE equipe_id = ".$row_user['equipe_id']." AND equipe_operacional LIKE '%".$userid.",%';") 
    or die(mysql_error());
    $row_eq_operacional = mysql_fetch_array( $result_eq_operacional );
    if (!$row_eq_operacional["total"]){$acesso_negado = 1;}
}
if(($retencao_safra)&&(($row['vendas_tipo_contrato'] == 12)||($row['vendas_tipo_contrato'] == 13))&&($row['vendas_banco'] == "SAFRA")){
    $acesso_liberado = 1;
    $acesso_negado = 0;
}

if ($supervisor_equipe_vendas){
    if($pos_venda){
        $acesso_liberado = 1;
        $acesso_negado = 0;
    }else{
        $result_eq_supervisor = mysql_query("SELECT COUNT(equipe_id) AS total FROM sys_equipes 
        WHERE equipe_id = ".$row_user['equipe_id']." AND equipe_supervisor = '".$userid."';") 
        or die(mysql_error());
        $row_eq_supervisor = mysql_fetch_array( $result_eq_supervisor );
        if ($row_eq_supervisor["total"]){
            $acesso_liberado = 1;
            $acesso_negado = 0;
        }else{
            $acesso_liberado = 0;
            $acesso_negado = 1;
        }
    }
}

if ($supervisor_de_unidade){
	$result_un_supervisor = mysql_query("SELECT COUNT(empresa_id) AS total FROM sys_empresas 
	WHERE empresa_rep_credito LIKE '%,".$userid.",%' OR empresa_supervisores LIKE '%,".$userid.",%';") 
	or die(mysql_error());
	$row_un_supervisor = mysql_fetch_array( $result_un_supervisor );
	if ($row_un_supervisor["total"]){
		$acesso_liberado = 1;
		$acesso_negado = 0;
	}else{
		$acesso_liberado = 0;
		$acesso_negado = 1;
	}
}

if ($coordenador_plataformas){
    $result_eq_coordenador = mysql_query("SELECT COUNT(equipe_id) AS total FROM sys_equipes 
    WHERE equipe_id = ".$row_user['equipe_id']." AND equipe_coordenador = '".$userid."';") 
    or die(mysql_error());
    $row_eq_coordenador = mysql_fetch_array( $result_eq_coordenador );
    if ($row_eq_coordenador["total"]){
        $acesso_liberado = 1;
        $acesso_negado = 0;
    }else{
        $acesso_liberado = 0;
        $acesso_negado = 1;
    }
}

if ($gerente_plataformas){
    $result_eq_coordenador = mysql_query("SELECT COUNT(equipe_id) AS total FROM sys_equipes 
    WHERE equipe_tipo = 2;") 
    or die(mysql_error());
    $row_eq_coordenador = mysql_fetch_array( $result_eq_coordenador );
    if ($row_eq_coordenador["total"]){
        $acesso_liberado = 1;
        $acesso_negado = 0;
    }else{
        $acesso_liberado = 0;
        $acesso_negado = 1;
    }
}

if ($supervisor_treinamento){
    if ($row_user["situacao"] == 1){
        $acesso_liberado = 1;
        $acesso_negado = 0;
		$edicao=0;
    }else{
        $acesso_liberado = 0;
        $acesso_negado = 1;
    }
}

if ($userid == $row["vendas_consultor"]){
    $acesso_liberado = 1;
    $acesso_negado = 0;
}

if ($row["vendas_status"] == 9){$edicao = 0;}
if ($admin_concluidas){$edicao = 1;}
?>
<?php if (($userid != $row["vendas_consultor"])&&
       ($administracao != 1)&&
       ($row_user_nivel["nivel"] != "5")&&
       ($row_user_nivel["nivel"] != "6")&&
       ($row_user_nivel["nivel"] != "7")&&
       ($pos_venda != 1)&&
       ($acesso_liberado != 1)):?>
<div align="center">
    VOCÊ NÃO POSSUI ACESSO A ESTA PÁGINA! </br>
    Entre em contato com a sua supervisão, para solicitar este acesso.
</div>
<?php elseif($acesso_negado): ?>
<div align="center">
    VOCÊ NÃO POSSUI ACESSO A ESTA PÁGINA! </br>
    Entre em contato com a sua supervisão, para solicitar este acesso.
</div>
<?php else: ?>
 <?php  $curURL = $_SERVER["REQUEST_URI"]; ?>
<script language="javascript">
function consultaAjaxDadosProposta() {
        var vendas_orgao, vendas_produto, vendas_banco, vendas_tipo_contrato, vendas_percelas, vendas_tabela, vendas_valor_parcela, select_permissao_tabela, atualiza_coeficiente;

        document.getElementsByName("atualiza_coeficiente")[0] ? atualiza_coeficiente = document.getElementsByName("atualiza_coeficiente")[0].checked : atualiza_coeficiente = false;
        document.getElementsByName("vendas_orgao")[0] ? vendas_orgao = document.getElementsByName("vendas_orgao")[0].value : vendas_orgao = "";
        document.getElementsByName("vendas_produto")[0] ? vendas_produto = document.getElementsByName("vendas_produto")[0].value : vendas_produto = "";
        document.getElementsByName("vendas_banco")[0] ? vendas_banco = document.getElementsByName("vendas_banco")[0].value : vendas_banco = "";
        document.getElementsByName("vendas_tipo_contrato")[0] ? vendas_tipo_contrato = document.getElementsByName("vendas_tipo_contrato")[0].value : vendas_tipo_contrato = "";
        document.getElementsByName("vendas_percelas")[0] ? vendas_percelas = document.getElementsByName("vendas_percelas")[0].value : vendas_percelas = "";
        document.getElementsByName("vendas_tabela")[0] ? vendas_tabela = document.getElementsByName("vendas_tabela")[0].value : vendas_tabela = "";
        document.getElementsByName("vendas_valor_parcela")[0] ? vendas_valor_parcela = document.getElementsByName("vendas_valor_parcela")[0].value : vendas_valor_parcela = "";
        document.getElementsByName("select_permissao_tabela")[0] ? select_permissao_tabela = document.getElementsByName("select_permissao_tabela")[0].value : select_permissao_tabela = "";

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("retornoAjax").innerHTML = xmlhttp.responseText;
                check_selects();             
            }
        };
        xmlhttp.open("GET", "sistema/consultas_ajax/consulta_edita_venda.php?vendas_orgao="+vendas_orgao+"&vendas_produto="+vendas_produto+"&vendas_banco="+vendas_banco+"&vendas_tipo_contrato="+vendas_tipo_contrato+"&vendas_percelas="+vendas_percelas+"&vendas_tabela="+vendas_tabela+"&vendas_valor_parcela="+vendas_valor_parcela+"&atualiza_coeficiente="+atualiza_coeficiente, true);
        xmlhttp.send();    
}

window.onload = function(){ check_selects(); }
function check_selects() {
  counter = 0;
  var tabela = document.getElementById("retornoAjax");

for(i=0; i<tabela.getElementsByTagName("select").length; i++)
    {
       if (tabela.getElementsByTagName("select")[i].value == "")
           {
             document.getElementById("save_button").innerHTML = "";
             counter=1;
             break;
           }         
    }

if(counter==0)
    {
        if(document.getElementById("edicao").value == 1 )
        {
            document.getElementById("save_button").innerHTML = '<button class="button validate png" name="salvar" type="submit" value="salvar" onclick="salvando_modal()">Salvar Venda</button><button class="button validate png" name="salvar" type="submit" value="salvar_fechar" onclick="salvando_modal()">Salvar Venda & Fechar</button>';
        }
        else
        {
            document.getElementById("save_button").innerHTML = '<button class="button validate png" name="salvar" type="submit" value="observacao">Salvar Observação</button>';
        }
    }

}
</script>
<a class="itemPrintLink" rel="nofollow" href="/sistema/index.php?option=com_k2&amp;view=item&amp;id=160:ficha-cadastral&amp;Itemid=123&amp;tmpl=component&amp;print=1&amp;vendas_id=<?php echo $row["vendas_id"]; ?>&amp;acao=ficha_cadastral" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;">
    <span>Imprimir Ficha Cadastral</span>
</a>

<?php 
/*
############################################
ALERTA DE VENDA SEM REGISTRO DE ACIONAMENTO
############################################
*/
?>
<?php if($super_user || $sup_operacional || $diretoria): ?>
    <style type="text/css">
        .slim_table
        {
            margin: 5px;
        }
        .slim_table td,
        .slim_table th
        {
            padding: 1px 3px;
            font-size: 12px;
        }
        .ver_acio_btn:hover
        {
            cursor: pointer;
            background: #ccc;
        }
        .slim_table_container
        {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
    <script type="text/javascript">
        $(document).on("click", ".ver_acio_btn", function(){
            $(".slim_table_container").slideToggle();
            if($(this).text() == "Ver Acionamentos")
            {
                $(this).text("Ocultar Acionamentos");
            }else
            {
                $(this).text("Ver Acionamentos");
            }
            
        });
    </script>
    <div class='slim_table_container' style="display: none;">
    <h3 style=" position: absolute;
                left: 50%;
                transform: translateX(-50%);
                top: 135px;">Registros de acionamento do cliente:</h3>
<?php 
    include('sistema/connect_db02.php');
    include('sistema/utf8.php');

    $possui_acionamento = false;

    $sql_acionamentos = "SELECT acionamento_id, acionamento_usuario, parecer_nome, DATE_FORMAT(acionamento_data,'%d/%m/%Y') AS acionamento_data, acionamento_obs FROM sys_acionamentos
                            JOIN sys_pareceres ON sys_acionamentos.acionamento_parecer = sys_pareceres.parecer_id
                            WHERE clients_cpf LIKE '".$row['clients_cpf']."'";
    $result_acionamentos = mysql_query($sql_acionamentos) or die(mysql_error());
    include('sistema/connect.php');
    include('sistema/utf8.php');
?>
    <?php if(mysql_num_rows($result_acionamentos)): ?>
            <table class='slim_table'>
                <tr>
                    <th>Id:</th>
                    <th>Usuário:</th>
                    <th>Parecer:</th>
                    <th>Data:</th>
                    <th>Obs.:</th>
                </tr>
            <?php
                while($row_acionamentos = mysql_fetch_assoc($result_acionamentos)):
            ?>
            <tr <?php if($row_acionamentos['acionamento_usuario'] == $row_user['username']){ echo "style='color: green;'"; } ?>>
                <td><?php echo $row_acionamentos['acionamento_id']; ?></td>
                <td><?php echo $row_acionamentos['acionamento_usuario']; ?></td>
                <td><?php echo $row_acionamentos['parecer_nome']; ?></td>
                <td><?php echo $row_acionamentos['acionamento_data']; ?></td>
                <td><?php echo $row_acionamentos['acionamento_obs']; ?></td>
            </tr>

            <?php
                if($row_acionamentos['acionamento_usuario'] == $row_user['username'])
                {
                    $possui_acionamento = true;
                }
                endwhile;
            ?>
            </table>
    <?php else: ?>
        <div style="text-align: center;">Não foram encontrados registros de acionamento para este CPF.</div>
    <?php endif; ?>
    </div>
    <br>
    <?php if($possui_acionamento): ?>
        <span style='border: solid 1px #ccc; padding: 3px 5px; line-height: 1.5; border-radius: 11px;'>
            <div style="background: green; width: 20px; height: 20px; border-radius: 50%; display: inline-block;"></div>
            <span>Consultor da venda possui registro de acionamento do cliente.</span>
        </span>
    <?php else: ?>
        <span style='border: solid 1px #ccc; padding: 3px 5px; line-height: 1.5; border-radius: 11px;'>
            <div style="background: red; width: 20px; height: 20px; border-radius: 50%;  display: inline-block;"></div>
            <span>Consultor da venda <strong>NÃO</strong> possui registro de acionamento do cliente.</span>
        </span>
    <?php endif; ?>
    <div class='ver_acio_btn' style="display: inline-block; border: solid 1px #ccc; padding: 3px 5px; line-height: 1; border-radius: 11px;">Ver Acionamentos</div>
<?php endif; ?>
<?php 
/*
############################################
ALERTA DE VENDA SEM REGISTRO DE ACIONAMENTO
############################################
*/
?>
<form id="edita_form" action="index.php" method="GET">
	<input name="option" type="hidden" id="option" value="com_k2" />
	<input name="view" type="hidden" id="view" value="item" />
	<input name="id" type="hidden" id="id" value="64" />
	<input name="Itemid" type="hidden" id="Itemid" value="398" />
	<input name="username" type="hidden" id="username" value="<?php echo $username; ?>" />
	<input name="vendas_id" type="hidden" id="vendas_id" value="<?php echo $row["vendas_id"]; ?>" />
	<input name="user_situacao" type="hidden" id="user_situacao" value="<?php echo $row_user["situacao"]; ?>" />
	<input name="user_nivel" type="hidden" id="user_nivel" value="<?php echo $row_user["nivel"]; ?>" />
	<input name="vendas_status_old" type="hidden" id="vendas_status_old" value="<?php echo $row['vendas_status']; ?>" />
	<div align="center">
		<div id="bloco_container">
			<div class="bloco_bloco">
                <!-- em desenvolvimento -->
                <?php 
                $debug = false;
                if( $debug ){
                    include("sistema/vendas/blocos/ficha_cliente_dev.php");
                }else{
                    include("sistema/vendas/blocos/ficha_cliente.php");
                }
                ?>
			</div>
		</div>
		<div id="bloco_container">
			<div class="bloco_bloco">
				<?php include("sistema/vendas/blocos/dados_da_proposta.php"); ?>
			</div>
		</div>
		<?php 
            $contratos_de_compra = array("2", "3", "4", "5", "6", "9", "13", "14", "15", "17", "19", "20");
        if ( in_array($row['vendas_tipo_contrato'], $contratos_de_compra) ): ?>
			<div id="bloco_container">
				<div class="bloco_bloco">
					<?php include("sistema/vendas/blocos/compra_de_divida.php"); ?>
				</div>
			</div>
		<?php endif;?>
		<div id="bloco_container">
			<div class="bloco_bloco">
				<?php include("sistema/vendas/blocos/operacional_da_venda.php"); ?>
			</div>
		</div>
		<?php if (($sup_operacional == 1)||($consultor_mei)) :?>
			<div id="bloco_container">
				<div class="bloco_bloco">
					<?php include("sistema/vendas/blocos/gerenciar_venda.php"); ?>
				</div>
			</div>
			<?php if ($sup_operacional == 1) :?>
				<div id="bloco_container">
					<div class="bloco_bloco">
						<?php include("sistema/vendas/blocos/recebimentos_fracionados.php"); ?>
					</div>
				</div>
			<?php endif;?>
		<?php endif;?>
		<div id="bloco_container">
			<div class="bloco_bloco">
				<?php include("sistema/vendas/blocos/anexos.php"); ?>
			</div>
		</div>
		<div id="bloco_container">
			<div class="bloco_bloco">
				<?php include("sistema/vendas/blocos/seguros.php"); ?>
			</div>
		</div>
		<div id="bloco_container">
			<div class="bloco_bloco">
				<?php include("sistema/vendas/blocos/historico_da_venda.php"); ?>
			</div>
		</div>
		<div id="bloco_container">
			<div class="bloco_bloco">
				<?php 
				$yr=strval(substr($row["vendas_alteracao"],0,4));
				$mo=strval(substr($row["vendas_alteracao"],5,2));
				$da=strval(substr($row["vendas_alteracao"],8,2));
				$hr=strval(substr($row["vendas_alteracao"],11,2));
				$mi=strval(substr($row["vendas_alteracao"],14,2));
				$data_alteracao = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
				?>
				<div class="linha"><div align="center">Alterado em: " <strong><?php echo $data_alteracao; ?></strong> " , por "<strong><?php if ($row["vendas_user"] == "Importer" ){echo "Importador automático";}else{echo $row["vendas_user"];} ?></strong> "</div></div>
			</div>
		</div>
		<div id="bloco_container">
			<div class="bloco_bloco">
				<div class="linha">
					<div class="coluna campo-titulo">Observações:</div>
					<div class="coluna"><textarea name="vendas_obs" cols="70" rows="4" id="vendas_obs"></textarea></div>
				</div>
				<?php if ($administracao == 1) :?>
					<div class="linha">
						<div class="coluna campo-titulo">&nbsp;</div>
						<div class="coluna">
							<select name="registro_restrito">
							  <option value="0">Registro Público</option>
							  <option value="1">Registro Restrito</option>
							</select>
						</div>
					</div>
                    <div class="linha">
                        <div class="coluna campo-titulo">Notificar consultor?</div>
                        <div class="coluna">
                            <select name="notificar_consultor" style="width: 40px;">
                              <option value="0">Não</option>
                              <option value="1">Sim</option>
                            </select>
                        </div>
                    </div>
				<?php endif;?>
				<?php if ($pos_venda == 1) :?>
					<div class="coluna campo-titulo">Status de Pós Venda:</div>
					<div class="coluna">
						<select name="vendas_pos_venda">
							<option value="1" <?php if ($row['vendas_pos_venda'] == "1"){echo "selected";}?>>Não Iniciado</option>
							<option value="2" <?php if ($row['vendas_pos_venda'] == "2"){echo "selected";}?>>Iniciado</option>
							<option value="3" <?php if ($row['vendas_pos_venda'] == "3"){echo "selected";}?>>Em Alerta</option>
							<option value="4" <?php if ($row['vendas_pos_venda'] == "4"){echo "selected";}?>>Concluído</option>
						</select><br>
						<label for="dp-normal-9">Agendar cliente para:</label>
						<p class="lastup"><input type="text" class="w8em format-d-m-y range-low-today highlight-days-67" id="dp-normal-9" name="dp-normal-9" maxlength="10" size="10" readonly="true" value="<?php echo $_GET["dp-normal-9"]; ?>"/> &nbsp; 
						às <input id="sis_campo" name="hora" type="text" size="2" maxlength="2" value="<?php echo $_GET["hora"]; ?>"/> : <input id="sis_campo" name="minuto" type="text" size="2" maxlength="2" value="<?php echo $_GET["minuto"]; ?>"/>
						</p><br>
					</div>
				<?php endif;?>
				<div class="linha">
					<div class="coluna campo-titulo">Registro de Abertura:</div>
					<div class="coluna"><?php echo $row["vendas_obs"]; ?></div>
				</div>
				<div class="linha">
					<div align="center">
						<span id="save_button" >
							 <?php if ($edicao == 1) :?>
								<button name="salvar" type="submit" value="salvar" onclick="salvando_modal()">Salvar Venda</button>
								<button name="salvar" type="submit" value="salvar_fechar" onclick="salvando_modal()">Salvar Venda & Fechar</button>
							<?php else:?>
								<button name="salvar" type="submit" value="observacao">Salvar Observação</button>
							<?php endif;?>
						</span>
						<a href="<?php echo $link_consulta;?>"><button class="button validate png" type="button">Fechar Venda</button></a>
						<input name="ordemi" type="hidden" id="ordem" value="ACESSOS" />
						<input name="acao" type="hidden" id="acao" value="atualiza_venda" />
						<input type="hidden" id="edicao" value="<?php echo $edicao; ?>" /> 
                    </div>
				</div>
			</div>
		</div>
	</div>
</form>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>