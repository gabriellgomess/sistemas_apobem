<link href="templates/gk_music/css/template.portal.css" rel="stylesheet" type="text/css" />
<script src="sistema/js/jquery-2.1.4.min.js"></script>

<?php
for ($i=0;$i<count($_GET["vendas_contrato_fisico"]);$i++){
	if ($_GET["vendas_contrato_fisico"][$i] != ""){
			$pag_contrato = $pag_contrato."&vendas_contrato_fisico[]=".$_GET["vendas_contrato_fisico"][$i];					
	}
}
for ($i=0;$i<count($_GET["vendas_mes"]);$i++){
	if ($_GET["vendas_mes"][$i] != ""){
			$pag_mes = $pag_mes."&vendas_mes[]=".$_GET["vendas_mes"][$i];					
	}
}
for ($i=0;$i<count($_GET["vendas_status"]);$i++){
	if ($_GET["vendas_status"][$i] != ""){
			$pag_status = $pag_status."&vendas_status[]=".$_GET["vendas_status"][$i];					
	}
}
for ($i=0;$i<count($_GET["consultor_unidade"]);$i++){
	if ($_GET["consultor_unidade"][$i] != ""){
			$pag_unidade = $pag_unidade."&consultor_unidade[]=".$_GET["consultor_unidade"][$i];					
	}
}

$user =& JFactory::getUser();
$username=$user->username;
$userid=$user->id;

$result_url = mysql_query("SELECT url_consulta_clientes FROM jos_users WHERE id = " . $userid . ";") 
or die(mysql_error());  
$row_url = mysql_fetch_array( $result_url );
$link_consulta = $row_url["url_consulta_clientes"];
?>
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
        function sonumero($this){            
            $this.value = $this.value.replace(/[^0-9]/g, "");
        }
            
        
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
$prec=$_GET["prec"];
$dorm=$_GET["dorm"];
$user =& JFactory::getUser();
$username=$user->username;
$userid=$user->id;
$administracao = 0;
$franquiado = 0;
?>
<?php
$result = mysql_query("SELECT * FROM sys_vendas WHERE sys_vendas.vendas_id = '" . $vendas_id . "';") 
or die(mysql_error());  
$row = mysql_fetch_array( $result );

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

$result_user = mysql_query("SELECT username, name, situacao, nivel, unidade, email FROM jos_users WHERE id = '" . $row['vendas_consultor'] . "';") 
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
	if (($row_grupo_user['id'] == '10')||($row_grupo_user['id'] == '30')){$administracao = 1;}
	if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if ($row_grupo_user['id'] == '28'){$financeiro = 1;}
	if ($row_grupo_user['id'] == '21'){$franquiado = 1;}
	if ($row_grupo_user['id'] == '11'){$sup_operacional = 1;}
	if ($row_grupo_user['id'] == '60'){$admin_fisicos = 1;}
	if ($row_grupo_user['id'] == '66'){$gerente_comercial_agentes = 1;}
	if ($row_grupo_user['id'] == '73'){$gerente_regional = 1; $administracao = 1;}
	if ($row_grupo_user['id'] == '79'){$supervisor_comercial_agentes = 1;}
	if ($row_grupo_user['id'] == '88'){$financeiro_jsr = 1; $administracao = 1; $sup_operacional = 1;}
	if ($row_grupo_user['id'] == '89'){$financeiro_csm = 1; $administracao = 1; $sup_operacional = 1;}
	if ($row_grupo_user['id'] == '63'){$diretoria = 1; $administracao = 1; $sup_operacional = 1;}
}
if ($administracao == 1){$select_permissao_tabela = "";}else{$select_permissao_tabela = "AND tabela_permissao = '1' ";}
if ((($row_user_nivel["nivel"] == "6")||($row_user_nivel["nivel"] == "5")||($gerente_comercial_agentes))&&($row_user_nivel["unidade"] == $row_user["unidade"])){$administracao = 1;}
if (($row_user_nivel["nivel"] == "7")&&($row_user_nivel["unidade"] == $row_user["unidade"])){$administracao = 1;}

$result_banks = mysql_query("SELECT * FROM sys_vendas_bancos WHERE vendas_bancos_employer LIKE '%" . $_GET['clients_employer'] . "%' ORDER BY vendas_bancos_nome;") 
or die(mysql_error());

$result_anexos = mysql_query("SELECT * FROM sys_vendas_anexos WHERE vendas_id = " . $row['vendas_id'] . ";") 
or die(mysql_error());

include("sistema/utf8.php");

if ($administracao == 0){$select_registro_restrito = " AND registro_restrito = '0'";
}else{if ($diretoria == 0){$select_registro_restrito = " AND registro_restrito <= '1'";}else{$select_registro_restrito = "";}}

if ($row['vendas_tipo_contrato'] != "6"){
	$result_compras = mysql_query("SELECT * FROM sys_vendas_compras WHERE vendas_id = " . $row['vendas_id'] . ";") 
	or die(mysql_error());
}

$result_status_nm = mysql_query("SELECT status_nm, status_liberado, status_proximo FROM sys_vendas_status WHERE status_id = " . $row['vendas_status'] . ";")
or die(mysql_error());
$row_status_nm = mysql_fetch_array( $result_status_nm );
$vendas_status_nm = $row_status_nm["status_nm"];
$vendas_status_proximo = $row_status_nm["status_proximo"];

if (($administracao == 0) && ($row_status_nm["status_liberado"] == 0)){$edicao = 0;}else{$edicao = 1;}
?>
<?php if (($userid != $row["vendas_consultor"])&&($administracao != 1)):?>
<div align="center">
	VOCÊ NÃO POSSUI ACESSO A ESTA PÁGINA! </br>
	Entre em contato com a sua supervisão, para solicitar este acesso.
</div>
<?php else: ?>
 <?php  $curURL = $_SERVER["REQUEST_URI"]; ?>
<script language="javascript">
function consultaAjax() {

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
        xmlhttp.open("GET", "sistema/consultas_ajax/consulta_edita_venda.php?vendas_orgao="+vendas_orgao+"&tabela_perfil_venda=3&vendas_produto="+vendas_produto+"&vendas_banco="+vendas_banco+"&vendas_tipo_contrato="+vendas_tipo_contrato+"&vendas_percelas="+vendas_percelas+"&vendas_tabela="+vendas_tabela+"&vendas_valor_parcela="+vendas_valor_parcela+"&atualiza_coeficiente="+atualiza_coeficiente, true);
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
            document.getElementById("save_button").innerHTML = '<button class="button validate png" name="salvar" type="submit" value="observacao" onclick="salvando_modal()">Salvar Observação</button>';
        }
    }

}
</script>
 <style type="text/css">
<!--
.style1 {
	color: #CCCCCC;
	font-weight: bold;
}
.style2 {color: #CCCCCC}
-->
 </style>
<span style="color:red"><strong>* Venda de Agente!</strong></span><br />
<?php if ($_GET["erro_proposta"]):?>
	<div align="center" style="width: 100%; background-color: #fbc146; line-height: 36px; border-bottom-width: 2px; border-bottom-color: #d69e28; border-bottom-style: solid; font-weight: bold;">
		Venda <a target="_blank" href='index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_agente&vendas_id=<?php echo $_GET["erro_proposta"]; ?>'><?php echo $_GET["erro_proposta"]; ?></a> já possui o Nº de Proposta digitado.
	</div>
<?php endif; ?>

<a class="itemPrintLink" rel="nofollow" href="/sistema/index.php?option=com_k2&amp;view=item&amp;id=160:ficha-cadastral&amp;Itemid=123&amp;tmpl=component&amp;print=1&amp;vendas_id=<?php echo $row["vendas_id"]; ?>&amp;acao=ficha_cadastral" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;">
	<span>Imprimir Ficha Cadastral</span>
</a>

<form action="index.php" method="GET">
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
                <?php include("sistema/vendas/blocos_agente/ficha_cliente.php"); ?>
            </div>
        </div>
        <div id="bloco_container">
            <div class="bloco_bloco">
                <?php include("sistema/vendas/blocos_agente/dados_da_proposta.php"); ?>
            </div>
        </div>
        <div id="bloco_container">
            <div class="bloco_bloco">
                <?php include("sistema/vendas/blocos_agente/compra_de_divida.php"); ?>
            </div>
        </div>
        <div id="bloco_container">
            <div class="bloco_bloco">
                <?php include("sistema/vendas/blocos_agente/operacional_da_venda.php"); ?>
            </div>
        </div>
		<?php if ($sup_operacional == 1) :?>
			<div id="bloco_container">
				<div class="bloco_bloco">
					<?php include("sistema/vendas/blocos_agente/gerenciar_venda.php"); ?>
				</div>
			</div>
			<div id="bloco_container">
				<div class="bloco_bloco">
					<?php include("sistema/vendas/blocos/recebimentos_fracionados.php"); ?>
				</div>
			</div>
		<?php elseif ($row_user_nivel["nivel"] == "5") :?>
			<div id="bloco_container">
				<div class="bloco_bloco">
					<?php include("sistema/vendas/blocos_agente/gerenciar_venda_gerentes.php"); ?>
				</div>
			</div>
		<?php else:?>
			<div id="bloco_container">
				<div class="bloco_bloco">
				  <?php $vendas_juros = ($row['vendas_juros']>0) ? number_format($row['vendas_juros'], 2, ',', '.') : '0' ;?>
				  <input value="<?php echo $vendas_juros; ?>" name="vendas_juros" type="hidden"/>
				  <input type="hidden" name="vendas_receita" value="<?php echo $vendas_receita_rs;?>"/></strong>
				</div>
			</div>
		<?php endif;?>
		<div id="bloco_container">
			<div class="bloco_bloco">
				<?php include("sistema/vendas/blocos_agente/anexos.php"); ?>
			</div>
		</div>
        <div id="bloco_container">
            <div class="bloco_bloco">
                <?php include("sistema/vendas/blocos_agente/seguro.php"); ?>
            </div>
        </div>
		<div id="bloco_container">
			<div class="bloco_bloco">
				<?php include("sistema/vendas/blocos/historico_da_venda.php"); ?>
			</div>
		</div>
		<div id="bloco_container">
			<div class="bloco_bloco">
				<div class="linha">
				  <?php 
						$yr=strval(substr($row["vendas_alteracao"],0,4));
						$mo=strval(substr($row["vendas_alteracao"],5,2));
						$da=strval(substr($row["vendas_alteracao"],8,2));
						$hr=strval(substr($row["vendas_alteracao"],11,2));
						$mi=strval(substr($row["vendas_alteracao"],14,2));
						$data_alteracao = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
				  ?>
					<?php if ($data_alteracao == "31/12/1969 21:00:00"): ?>
						<strong>Nunca Alterado.</strong>
					<?php else: ?>
						Alterado em: " <strong><?php echo $data_alteracao; ?></strong> " , por "<strong><?php if ($row["vendas_user"] == "Importer" ){echo "Importador automático";}else{echo $row["vendas_user"];} ?></strong> "
					<?php endif; ?>
				</div>
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
								<button name="salvar" type="submit" value="observacao" onclick="salvando_modal()">Salvar Observação</button>
							<?php endif;?>
						</span>
						<?php if ($administracao == 1) :?>
							 <input type="checkbox" name="notifica_agente" value="1" checked><strong>NOTIFICAR AGENTE!</strong> <span style="font-size:7pt">(marque para enviar e-mail com a notificação da sua atualização ao agente.)</span>
						<?php endif;?>
						<a href="<?php echo $link_consulta;?>"><button class="button validate png" type="button">Fechar Venda</button></a>
						<input name="ordemi" type="hidden" id="ordem" value="ACESSOS" />
						<input name="acao" type="hidden" id="acao" value="atualiza_venda_agente" />
						<input type="hidden" id="edicao" value="<?php echo $edicao; ?>" /> 
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
<?php endif; ?>