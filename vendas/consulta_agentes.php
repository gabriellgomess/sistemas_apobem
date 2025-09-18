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
<?php
if ($_GET["p"]){$pagina=$_GET["p"];}else{$pagina="1";}


$url_consulta_clientes = $_SERVER['REQUEST_URI'];
$query = mysql_query("UPDATE jos_users SET url_consulta_clientes='$url_consulta_clientes' WHERE id='$user_id';") or die(mysql_error());

include("sistema/utf8.php");
$result_grupo_user = mysql_query("SELECT id FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if (($row_grupo_user['id'] == '18')||($row_grupo_user['id'] == '63')){
		$diretoria = 1;
		$administracao = 1;
		$sup_operacional = 1;
	}
	if (($row_grupo_user['id'] == '11')||($row_grupo_user['id'] == '30')){$sup_operacional = 1;}
	if ($row_grupo_user['id'] == '34'){$supervisor_operacional_agentes = 1; $administracao = 1;}
	if ($row_grupo_user['id'] == '73'){$administracao = 1;}
	/* ATENÇÃO! O restante dos grupos vem do 'sistema/includes_sistema.php' */
}

$result_user = mysql_query("SELECT nivel, unidade FROM jos_users WHERE id = '" . $user_id . "';") 
or die(mysql_error());
$array_user_id = mysql_fetch_array( $result_user );
$user_nivel = $array_user_id["nivel"];
$user_unidade = $array_user_id["unidade"];
if ($array_user_id['nivel'] == '5'){$gerente_unidade = 1;}

include("sistema/vendas/filtros_sql_agentes.php");

$p = $_GET["p"];
if(isset($p)) {
$p = $p;
} else {
$p = 1;
}
if ($_GET["qnt"]){$qnt = $_GET["qnt"];}else{$qnt = 20;}
$inicio = ($p*$qnt) - $qnt;

echo "<pre style='display: none'>";
echo "SELECT * FROM sys_vendas 
		LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
		LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade.$join_tabela." 
		WHERE " . $filtros_sql . $select_nome . " ORDER BY " . $ordem . " " . $ordenacao . " LIMIT " . $inicio . ", " . $qnt . ";";
echo "</pre>";

$result = mysql_query("SELECT * FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade.$join_tabela." 
WHERE " . $filtros_sql . $select_nome . " ORDER BY " . $ordem . " " . $ordenacao . " LIMIT " . $inicio . ", " . $qnt . ";") 
or die(mysql_error());


if ($administracao == 1){
	if ($frame_revisadas == 1){
		$result_pendentes_total = mysql_query("SELECT COUNT(vendas_id) AS total FROM sys_vendas WHERE vendas_status = '22' OR vendas_status = '23' OR vendas_status = '12' ORDER BY vendas_alteracao ASC;") or die(mysql_error());
		$row_pendentes_total = mysql_fetch_array( $result_pendentes_total );
		$pendentes = $row_pendentes_total["total"];
		if ($pendentes){
			$result_pendentes = mysql_query("SELECT vendas_id, 
			vendas_consultor, 
			vendas_tipo_contrato, 
			vendas_orgao, 
			clients_nm, 
			cliente_nome, 
			vendas_valor, 
			vendas_status 
			FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf) 
			WHERE vendas_status = '22' OR vendas_status = '23' OR vendas_status = '12' ORDER BY vendas_alteracao ASC;") 
			or die(mysql_error());
		}
	}
	if ($frame_averbadas == 1){
		$result_aprovadas_total = mysql_query("SELECT COUNT(vendas_id) AS total FROM sys_vendas WHERE vendas_status = '6' ORDER BY vendas_alteracao ASC;") or die(mysql_error());
		$row_aprovadas_total = mysql_fetch_array( $result_aprovadas_total );
		$aprovadas = $row_aprovadas_total["total"];
		if ($aprovadas){
			$result_aprovadas = mysql_query("SELECT vendas_id, 
			vendas_consultor, 
			vendas_tipo_contrato, 
			vendas_orgao, 
			clients_nm, 
			cliente_nome, 
			vendas_valor, 
			vendas_status, 
			DATEDIFF(CURDATE(), vendas_dia_apr) AS age 
			FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf) 
			WHERE vendas_status = '6' AND DATEDIFF(CURDATE(), vendas_dia_apr) >= '2' ORDER BY age DESC;") 
			or die(mysql_error());
		}
	}
}

$links_gerais = "&vendas_id=".$vendas_id.
"&nome=".$nome.
"&prec=".$prec.
"&cpf=".$cpf.$pag_mes.
"&consultor_unidade=".$pag_unidade.
"&vendas_consultor=".$vendas_consultor.$pag_status.$pag_tipo.$pag_contrato.
"&vendas_promotora=".$vendas_promotora.
"&vendas_banco=".$vendas_banco.
"&vendas_orgao=".$vendas_orgao.
"&vendas_produto=".$vendas_produto.
"&vendas_seguro_protegido=".$vendas_seguro_protegido.
"&vendas_estoque=".$vendas_estoque.
"&vendas_tipo_tabela=".$vendas_tipo_tabela.
"&dp-normal-3=".$pag_data_imp_ini.
"&dp-normal-4=".$pag_data_imp_fim.
"&dp-normal-5=".$pag_data_ini.
"&dp-normal-6=".$pag_data_fim.
"&filtro_data1=".$_GET['filtro_data1'].
"&filtro_data2=".$_GET['filtro_data2'].
"&buscar=".$_GET['buscar'].
"&vendas_pago_agente=".$vendas_pago_agente.
"&qnt=".$qnt;

$links_filtros = $_SERVER['REQUEST_URI'].$links_gerais;
?>

<?php
$sql_select_all = mysql_query("SELECT COUNT(vendas_id) AS total FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade.$join_tabela." WHERE " . $filtros_sql .";")
or die(mysql_error());
$row_total_registros = mysql_fetch_array( $sql_select_all );
$total_registros = $row_total_registros["total"];
?>

<?php if ($administracao == 1 && $total_registros <= 5000): ?>
	<?php $link_exportacao = "sistema/vendas/relatorios/credito_agentes_xls.php".
	"?user_id=".$user_id.			//id do usuáio logado
	"&user_unidade=".$user_unidade.	//unidade do usuário logado

	"&vendas_id=".$vendas_id.	// código
	"&cpf=".$cpf.				// cpf
	"&prec=".$prec.				// matrícula
	"&nome=".$nome.				// nome
	"&vendas_proposta=".$vendas_proposta.	// nº da proposta 
	"&vendas_tabela=".$vendas_tabela.	// vendas_tabela
	"&vendas_portabilidade=".$vendas_portabilidade.	// nº da portabilidade
	"&vendas_produto=".$vendas_produto.	// nº da portabilidade
	"&vendas_estoque=".$vendas_estoque.	// ver vendas em estoque

	$pag_mes.		// mês válido
	$pag_status.	// status
	$pag_contrato.	// contrato físico
	$pag_unidade.	//  unidade
	$pag_tipo.		// tipo de contrato
	$vendas_banco.	// vendas banco

	"&vendas_orgao=".$vendas_orgao.	// orgão
	"&vendas_turno=".$vendas_turno.	// turno da venda
	"&vendas_consultor=".$vendas_consultor. // consultor da venda
	"&vendas_promotora=".$vendas_promotora.	// promotora da venda
	"&vendas_envio=".$vendas_envio.	// método de envio
	"&gerente_comercial=".$gerente_comercial.	// gerente comercial
	"&supervisor_comercial=".$supervisor_comercial.	// supervisor_comercial
	"&vendas_tipo_tabela=".$vendas_tipo_tabela.	// Tipo de tabela da venda, normal, flex ou top
	"&vendas_pago_agente=".$vendas_pago_agente.	// pago ao agente
	"&vendas_seguro_protegido=".$vendas_seguro_protegido.	// seguro consignado protegido

	"&dp-normal-3=".$pag_data_imp_ini.
	"&dp-normal-4=".$pag_data_imp_fim.
	"&dp-normal-5=".$pag_data_ini.
	"&dp-normal-6=".$pag_data_fim.
	"&filtro_data1=".$_GET['filtro_data1'].
	"&filtro_data2=".$_GET['filtro_data2'];?>
	<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_exportacao; ?>">Exportar para Excel</a>
<?php endif;?>

<?php if ((($diretoria == 1)||($financeiro == 1))&&($pag_status == "&vendas_status[]=8&vendas_status[]=9")&&($vendas_consultor)) :?>
	<?php 
	$link_rel_agente = "index.php?option=com_k2&view=item&layout=item&id=249&Itemid=473&somente_conteudo=1&origem=consulta".$links_gerais;
	?>
	<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_rel_agente; ?>">Relatório de Comissões do Agente</a>
<?php endif;?>
<script type="text/javascript">
//Initialize first demo:
ddaccordion.init({
	headerclass: "mypets2", //Shared CSS class name of headers group
	contentclass: "thepet2", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [7,10], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
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
<?php if ($_GET["erro_proposta"]):?>
	<div align="center" style="width: 100%; background-color: #fbc146; line-height: 36px; border-bottom-width: 2px; border-bottom-color: #d69e28; border-bottom-style: solid; font-weight: bold;">
		Venda <a target="_blank" href='index.php?option=com_k2&view=item&layout=item&id=356&Itemid=398&acao=edita_venda_agente&vendas_id=<?php echo $_GET["erro_proposta"]; ?>'><?php echo $_GET["erro_proposta"]; ?></a> já possui o Nº de Proposta digitado.
	</div>
<?php endif; ?>

<form action="index.php" method="GET">
<input id="sis_campo" name="option" type="hidden" id="option" value="com_k2" />
					<input id="sis_campo" name="view" type="hidden" id="view" value="item" />
					<input id="sis_campo" name="id" type="hidden" id="id" value="64" />
					<input id="sis_campo" name="Itemid" type="hidden" id="Itemid" value="<?php echo $_GET["Itemid"]; ?>" />
<?php if ((!$_GET["dp-normal-3"]) && (!$_GET["dp-normal-4"])) :?>
	<div class="css_consulta_aviso">
		<strong>CONSULTA DE VENDAS SOMENTE DE AGENTES!</strong><br/>
	</div>
<?php endif;?>

<div class="css_form_container">
	<div class="css_form_group">
		<div class="css_form_campo">Código: <input id="vendas_id" name="vendas_id" value="<?php echo $vendas_id;?>" type="text" maxlength="6" size="5"/></div>
		<div class="css_form_campo">CPF: <input id="cpf" name="cpf" value="<?php echo $cpf;?>" type="text" onkeyup="cpfSomenteNumero(this)" size="11" /></div>
		<div class="css_form_campo">Nome: <input id="nome" name="nome" value="<?php echo $nome;?>" type="text" size="25" /></div>
		<div class="css_form_campo">Nº da Proposta: <input id="vendas_proposta" name="vendas_proposta" value="<?php echo $vendas_proposta;?>" type="text" maxlength="20" size="12"/></div>
		<div class="css_form_campo">Nº da Portabilidade: <input id="vendas_portabilidade" name="vendas_portabilidade" value="<?php echo $vendas_portabilidade;?>" type="text" maxlength="20" size="12"/></div>
		<div class="css_form_campo">Matrícula: <input id="prec" name="prec" value="<?php echo $prec;?>" type="text" maxlength="10" size="10"/></div>
		<div class="css_form_campo">Código da Tabela: <input id="vendas_tabela" name="vendas_tabela" value="<?php echo $vendas_tabela;?>" type="text" maxlength="10" size="10"/></div>
	</div>
	<div class="css_form_group">
		<div class="css_form_campo"><strong>Ver Vendas em estoque.</strong><input type="checkbox" name="vendas_estoque" value="1" <?php if ($_GET["vendas_estoque"]){echo "checked";}?>></div>
		<div class="css_form_campo">
			<a href="index.php?option=com_k2&view=item&layout=item&id=64&Itemid=473"><button name="limpar" type="button" value="limpar">Limpar</button></a>
			<button name="Pesquisa anterior" type="reset" value="Pesquisa anterior">&#8635;</button>
			<button name="buscar" type="submit" value="buscar">Buscar</button>
		</div>			
	</div>	
</div>

<h3 class="mypets2" style="text-align: center;">Busca Avançada:</h3>
<div class="thepet2">

<div class="css_form_container">
	<div class="css_form_group grupo_a">
		<div class="css_form_group">
			<div class="css_form_campo">
				<select name="filtro_data1">
					  <option value="1"<?php if ($filtro_data1 == "1"){echo " selected";}?>>Data de Implantação</option>
					  <option value="2"<?php if ($filtro_data1 == "2"){echo " selected";}?>>Data de Pagamento</option>
					  <option value="3"<?php if ($filtro_data1 == "3"){echo " selected";}?>>Data da Venda</option>
				</select>		
				<?php $data_field = implode(preg_match("~\/~", $data_imp_ini) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_imp_ini) == 0 ? "-" : "/", $data_imp_ini)));?>						
				<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-3" name="dp-normal-3" maxlength="10" size="10" value="<?php echo $data_field;?>" />
				<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-4" name="dp-normal-4" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-4"];?>" />
			</div>
			<div class="css_form_campo">
				<select name="filtro_data2">
					  <option value="2"<?php if ($filtro_data2 == "2"){echo " selected";}?>>Data de Pagamento</option>
					  <option value="1"<?php if ($filtro_data2 == "1"){echo " selected";}?>>Data de Implantação</option>
					  <option value="2"<?php if ($filtro_data2 == "3"){echo " selected";}?>>Data da Venda</option>
				</select>
				<?php $data_field = implode(preg_match("~\/~", $data_imp_ini) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_imp_ini) == 0 ? "-" : "/", $data_imp_ini)));?>						
				<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-5" name="dp-normal-5" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-5"];?>" />
				<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-6" name="dp-normal-6" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-6"];?>" />
			</div>
		</div>

		<div class="css_form_group sel_180px">
			<div class="css_form_campo">
				<select name="vendas_orgao">
				<optgroup label="Órgão">
					<?php
					$optgroup = ""; $count_g = 0;				
					if (!$vendas_orgao){echo "<option value='' selected>------ Órgão ------</option>";}
					echo "<option value=''>---- Indiferente ----</option>";
					$result_orgao = mysql_query("SELECT * FROM sys_orgaos ORDER BY orgao_nome;")
					or die(mysql_error());
					while($row_orgao = mysql_fetch_array( $result_orgao )) {
					if ($row_orgao["orgao_nome"] == $vendas_orgao){$selected = "selected";}else{$selected = "";}
					if($optgroup != $row_orgao['orgao_label'][0]){
						if($count_g > 0) { echo "</optgroup>"; }
						echo "<optgroup label=".$row_orgao['orgao_label'][0].">";
						$optgroup = $row_orgao['orgao_label'][0];
						$count_g++;
					}
					echo "<option value='{$row_orgao['orgao_nome']}'{$selected}>{$row_orgao['orgao_label']}</option>";
					}
					?>
				</optgroup>
				</select>
			</div>

			<div class="css_form_campo">
				<select name="vendas_turno">
				<optgroup label="Turno da Venda">
				<option value="">---- Indiferente ----</option>					
					<?php
					if (!$vendas_turno) {echo "<option value='' disabled selected>------ Turno da Venda ------</option>";}
					$result_turno = mysql_query("SELECT * FROM sys_vendas_turno;")
					or die(mysql_error());
					while($row_turno = mysql_fetch_array( $result_turno )) {
					if ($row_turno["sys_vendas_turno_id"] == $vendas_turno){$selected_turno = " selected";}else{$selected_turno = "";}
					echo "<option value='{$row_turno['sys_vendas_turno_id']}'{$selected_turno}>{$row_turno['sys_vendas_turno_nome']}</option>";
					}
					?>
				</optgroup>
				</select>
			</div>

			<div class="css_form_campo">
				<select name="vendas_tipo_tabela">
				<optgroup label="Tipo de Tabela">
				<option value="">---- Indiferente ----</option>
					<?php 
					if (!$vendas_tipo_tabela) {echo "<option value='' disabled selected>------ Tipo de Tabela ------</option>";}
					?>	
					<option value="NORMAL" <?php if ($vendas_tipo_tabela == "NORMAL"){echo "selected";}?>>NORMAL</option>
					<option value="FLEX" <?php if ($vendas_tipo_tabela == "FLEX"){echo "selected";}?>>FLEX</option>
					<option value="TOP" <?php if ($vendas_tipo_tabela == "TOP"){echo "selected";}?>>TOP</option>
				</optgroup>
				</select>
			</div>
			
			<div class="css_form_campo">
			<select name="vendas_promotora">
				<optgroup label="Promotora">
				<option value="">---- Indiferente ----</option>			
					<?php
					$optgroup = ""; $count_g = 0;
					if (!$vendas_promotora) {echo "<option value='' disabled selected>------ Promotora ------</option>";}
					$result_promo = mysql_query("SELECT promotora_nome FROM sys_vendas_promotoras ORDER BY promotora_nome;")
					or die(mysql_error());
					while($row_promo = mysql_fetch_array( $result_promo )) {
					if ($row_promo["promotora_nome"] == $vendas_promotora){$selected_promo = "selected";}else{$selected_promo = "";}
					if($optgroup != $row_promo['promotora_nome'][0]){
						if($count_g > 0) { echo "</optgroup>"; }
						echo "<optgroup label=".$row_promo['promotora_nome'][0].">";
						$optgroup = $row_promo['promotora_nome'][0];
						$count_g++;
					}
					echo "<option value='{$row_promo['promotora_nome']}'{$selected_promo}>{$row_promo['promotora_nome']}</option>";
					}
					?>
				</optgroup>
				</select>
			</div>

			<?php if (($diretoria == 1) || ($financeiro == 1) || ($supervisor_operacional_agentes == 1)): ?>
				<div class="css_form_campo">
					<select name="vendas_pago_agente">
					<optgroup label="Pago ao Agente">
					<option value="">---- Indiferente ----</option>
					<?php if ($vendas_pago_agente == "") {echo "<option value='' disabled selected>------ Pago ao Agente ------</option>";}?>
						<option value="1" <?php if ($vendas_pago_agente == "1"){echo "selected";}?>>Não</option>
						<option value="2" <?php if ($vendas_pago_agente == "2"){echo "selected";}?>>Sim</option>
					</optgroup>
					</select>
				</div>
			<?php endif;?>

			<?php if ($administracao == 1) :?>
				<div class="css_form_campo">
					<select name='vendas_consultor'>
					<optgroup label="Consultor">
					<option value="">---- Indiferente ----</option>						
						<?php
						$optgroup = ""; $count_g = 0;
						if (!$vendas_consultor) {echo "<option value='' disabled selected>------ Consultor ------</option>";}
						$result_user_form = mysql_query("SELECT DISTINCT vendas_consultor, name, nivel FROM sys_vendas 
						INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id ".$join_tabela."
						WHERE " . $filtros_sql . " ORDER BY name;")
						or die(mysql_error());
						while($row_user_form = mysql_fetch_array( $result_user_form )) {
						if ($row_user_form["vendas_consultor"] == $vendas_consultor){$selected_consultor = " selected";
						if ($row_user_form["nivel"] == 3){$nivel = "consultor";}
						if ($row_user_form["nivel"] == 2){$nivel = "cordenador";}}else{$selected_consultor = "";}
						
						if($optgroup != $row_user_form['name'][0]){
						if($count_g > 0) { echo "</optgroup>"; }
						echo "<optgroup label=".$row_user_form['name'][0].">";
						$optgroup = $row_user_form['name'][0];
						$count_g++;
						}		
						echo "<option value='{$row_user_form['vendas_consultor']}'{$selected_consultor}>{$row_user_form['name']}</option>";
						}
						?>
					</optgroup>
					</select>
				</div>
			<?php elseif($gerente_regional): ?>
				<div class="css_form_campo">
					<select name='vendas_consultor'>
					<optgroup label="Consultor">
					<option value="">---- Indiferente ----</option>
						<?php
						$optgroup = ""; $count_g = 0;
						if (!$vendas_consultor) {echo "<option value='' disabled selected>------ Consultor ------</option>";}
						$result_user_form = mysql_query("SELECT id, name FROM jos_users WHERE unidade = '" . $user_unidade . "' ORDER BY name;")
						or die(mysql_error());
						while($row_user_form = mysql_fetch_array( $result_user_form )) {
						if ($row_user_form["id"] == $vendas_consultor){$selected_consultor = " selected";}else{$selected_consultor = "";}
						
						if($optgroup != $row_user_form['name'][0]){
						if($count_g > 0) { echo "</optgroup>"; }
						echo "<optgroup label=".$row_user_form['name'][0].">";
						$optgroup = $row_user_form['name'][0];
						$count_g++;
						}
						echo "<option value='{$row_user_form['id']}'{$selected_consultor}>{$row_user_form['name']}</option>";
						}
						?>
					</optgroup>
					</select>
				</div>
			<?php elseif($gerente_comercial_agentes): ?>
					<select name='vendas_consultor'>
					<optgroup label="Consultor">
					<option value="">---- Indiferente ----</option>
						<?php
						$optgroup = ""; $count_g = 0;
						if (!$vendas_consultor) {echo "<option value='' disabled selected>------ Consultor ------</option>";}
						$result_user_form = mysql_query("SELECT id, name FROM jos_users WHERE unidade = '" . $user_unidade . "' AND jos_users.gerente_comercial = '".$user_id."' ORDER BY name;")
						or die(mysql_error());
						while($row_user_form = mysql_fetch_array( $result_user_form )) {
						if ($row_user_form["id"] == $vendas_consultor){$selected_consultor = " selected";}else{$selected_consultor = "";}
						
						if($optgroup != $row_user_form['name'][0]){
						if($count_g > 0) { echo "</optgroup>"; }
						echo "<optgroup label=".$row_user_form['name'][0].">";
						$optgroup = $row_user_form['name'][0];
						$count_g++;
						}
						echo "<option value='{$row_user_form['id']}'{$selected_consultor}>{$row_user_form['name']}</option>";
						}
						?>
					</optgroup>
					</select>
			<?php else: ?>
					<select name='vendas_consultor'>
					<optgroup label="Consultor">
					<option value="">---- Indiferente ----</option>
						<?php
						$optgroup = ""; $count_g = 0;
						if (!$vendas_consultor) {echo "<option value='' disabled selected>------ Consultor ------</option>";}
						$result_user_form = mysql_query("SELECT id, name FROM jos_users WHERE unidade = '" . $user_unidade . "' AND jos_users.supervisor_comercial = '".$user_id."' ORDER BY name;")
						or die(mysql_error());
						while($row_user_form = mysql_fetch_array( $result_user_form )) {
						if ($row_user_form["id"] == $vendas_consultor){$selected_consultor = " selected";}else{$selected_consultor = "";}
						
						if($optgroup != $row_user_form['name'][0]){
						if($count_g > 0) { echo "</optgroup>"; }
						echo "<optgroup label=".$row_user_form['name'][0].">";
						$optgroup = $row_user_form['name'][0];
						$count_g++;
						}
						echo "<option value='{$row_user_form['id']}'{$selected_consultor}>{$row_user_form['name']}</option>";
						}
						?>
					</optgroup>
					</select>
			<?php endif;?>

			<?php if ($administracao == 1) :?>
				<div class="css_form_campo">
					<select name="vendas_envio">
					<optgroup label="Método de Envio">
					<option value="">---- Indiferente ----</option>
						<?php
						if (!$vendas_envio) {echo "<option value='' disabled selected>------ Método de Envio ------</option>";}
						$result_envio = mysql_query("SELECT * FROM sys_vendas_envio ORDER BY envio_id;")
						or die(mysql_error());
						while($row_envio = mysql_fetch_array( $result_envio )) {
						if ($row_envio["envio_id"] == $vendas_envio){$selected = "selected";}else{$selected = "";}
						echo "<option value='{$row_envio['envio_id']}'{$selected}>{$row_envio['envio_nome']}</option>";
						}
						?>
					</optgroup>
					</select>
				</div>
				<div class="css_form_campo">
					<select name="vendas_seguro_protegido">
					<optgroup label="Seguro Consignado Protegido">
					<option value="">---- Indiferente ----</option>
						<?php if ($vendas_seguro_protegido == "") {echo "<option value='' disabled selected>---- Seguro Consignado Protegido ----</option>";}else{echo "<option value='' selected>---- Indiferente ----</option>";}?>
						<option value="1"<?php if ($vendas_seguro_protegido == "1"){echo " selected";}?>>Não</option>
						<option value="2"<?php if ($vendas_seguro_protegido == "2"){echo " selected";}?>>Sim</option>
					</optgroup>
					</select>
				</div>
			<?php endif; ?>
				<div class="css_form_campo">
					<select name="vendas_produto">
					<optgroup label="Produto">
					<option value=''>---- Indiferente ----</option>
						<?php
						if (!$vendas_produto) {echo "<option value='' disabled selected>------ Produto ------</option>";}
						$result_produto = mysql_query("SELECT * FROM sys_vendas_produtos ORDER BY produto_id;")
						or die(mysql_error());
						while($row_produto = mysql_fetch_array( $result_produto )) {
						if ($row_produto["produto_id"] == $vendas_produto){$selected = "selected";}else{$selected = "";}
						echo "<option value='{$row_produto['produto_id']}'{$selected}>{$row_produto['produto_nome']}</option>";
						}
						?>
					</optgroup>
					</select>
				</div>
			<?php if (($administracao == 1)||($gerente_regional)) :?>
				<div class="css_form_campo">
					<select name="gerente_comercial">
						<optgroup label="Gerente Comercial">
					<?php
						if ($gerente_regional){$select_gerentes = $select_unidade;}else{$select_gerentes = "";}
						if (!$gerente_comercial) {echo "<option value='' disabled selected>------ Gerente Comercial ------</option>";}
						$result_gerente = mysql_query("SELECT id,name,username FROM jos_user_usergroup_map 
						INNER JOIN jos_users ON jos_user_usergroup_map.user_id = jos_users.id 
						WHERE group_id = '66'".$select_gerentes." GROUP BY user_id ORDER BY name;")
						or die(mysql_error());
						while($row_gerente = mysql_fetch_array( $result_gerente )) {
							if ($row_gerente["id"] == $gerente_comercial){$selected = "selected";}else{$selected = "";}
							echo "<option value='{$row_gerente['id']}'{$selected}>{$row_gerente['name']} ({$row_gerente['username']})</option>";
						}
					?>	
					</select>
				</div>
				<div class="css_form_campo">
					<select name="supervisor_comercial">
						<optgroup label="Supervisor Comercial">
					<?php
						if (!$supervisor_comercial) {echo "<option value='' disabled selected>------ Supervisor Comercial ------</option>";}
						$result_supervisor = mysql_query("SELECT id,name,username FROM jos_user_usergroup_map 
						INNER JOIN jos_users ON jos_user_usergroup_map.user_id = jos_users.id 
						WHERE group_id = '79' GROUP BY user_id ORDER BY name;")
						or die(mysql_error());
						while($row_supervisor = mysql_fetch_array( $result_supervisor )) {
							if ($row_supervisor["id"] == $supervisor_comercial){$selected = "selected";}else{$selected = "";}
							echo "<option value='{$row_supervisor['id']}'{$selected}>{$row_supervisor['name']} ({$row_supervisor['username']})</option>";
						}
					?>	
					</select>
				</div>	
			<?php endif; ?>
		</div>
	</div><!-- corrige espaço do inline-block  
--><div class="css_form_group grupo_b">
		<div style="width: 100%;">Campos de seleção múltipla. Utilize CTRL</div>

		<div class="css_form_campo css_multisel">Mês válido:<br>
			<select name="vendas_mes[]" multiple="multiple" >
			<option value="">---- Indiferente ----</option>	
				<?php
				$result_mes = mysql_query("SELECT * FROM sys_vendas_mes ORDER BY mes_id DESC;")
				or die(mysql_error());
				while($row_mes = mysql_fetch_array( $result_mes )) {
				$selected_mes = "";
				for ($i=0;$i<count($vendas_mes);$i++){if ($vendas_mes[$i] == $row_mes["mes_nome"]){$selected_mes = " selected";}}
				echo "<option value='{$row_mes['mes_nome']}'{$selected_mes}>{$row_mes['mes_label']}</option>";
				}
				?>
			</select>
		</div>	

		<div class="css_form_campo css_multisel">Status:<br>
			<select name="vendas_status[]" multiple="multiple" >
			<option value="">---- Indiferente ----</option>	
				<?php
				$result_status = mysql_query("SELECT * FROM sys_vendas_status ORDER BY status_etapa;")
				or die(mysql_error());
				while($row_status = mysql_fetch_array( $result_status )) {
				$selected_status = "";
				for ($i=0;$i<count($vendas_status);$i++){if ($vendas_status[$i] == $row_status["status_id"]){$selected_status = " selected";}}
				echo "<option value='{$row_status['status_id']}'{$selected_status}>{$row_status['status_nm']}</option>";
				}
				?>
			</select>
		</div>

		<div class="css_form_campo css_multisel">Contrato Físico:<br>
			<select name="vendas_contrato_fisico[]" multiple="multiple" >
			<option value="">---- Indiferente ----</option>			
				<?php
				$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos ORDER BY contrato_etapa;")
				or die(mysql_error());
				while($row_fisicos = mysql_fetch_array( $result_fisicos )) {
				$selected_fisicos = "";
				for ($i=0;$i<count($vendas_contrato_fisico);$i++){if ($vendas_contrato_fisico[$i] == $row_fisicos["contrato_id"]){$selected_fisicos = " selected";}}
				echo "<option value='{$row_fisicos['contrato_id']}'{$selected_fisicos}>{$row_fisicos['contrato_nome']}</option>";
				}
				?>
			</select>
		</div>

		<div class="css_form_campo css_multisel">Tipo de Contrato:<br>
			<select name="vendas_tipo_contrato[]" multiple="multiple" >
			<option value="">---- Indiferente ----</option>	
				<?php
				$optgroup = ""; $count_g = 0;
				$result_tipos = mysql_query("SELECT * FROM sys_vendas_tipos ORDER BY tipo_nome;")
				or die(mysql_error());
				while($row_tipos = mysql_fetch_array( $result_tipos )) {
				$selected_tipo = "";
				for ($i=0;$i<count($vendas_tipo_contrato);$i++){if ($vendas_tipo_contrato[$i] == $row_tipos["tipo_id"]){$selected_tipo = " selected";}}
				if($optgroup != $row_tipos['tipo_nome'][0]){
				if($count_g > 0) { echo "</optgroup>"; }
				echo "<optgroup label=".$row_tipos['tipo_nome'][0].">";
				$optgroup = $row_tipos['tipo_nome'][0];
				$count_g++;
				}
				echo "<option value='{$row_tipos['tipo_id']}'{$selected_tipo}>{$row_tipos['tipo_nome']}</option>";
				}
				?>
			</select>
		</div>

		<div class="css_form_campo css_multisel">Vendas Banco:<br>						
			<select name="vendas_banco[]" multiple="multiple" >
			<option value="">---- Indiferente ----</option>					
				<?php
				$optgroup = ""; $count_g = 0;
				$result_bancos = mysql_query("SELECT DISTINCT vendas_banco FROM sys_vendas WHERE vendas_banco != '' ORDER BY vendas_banco;")
				or die(mysql_error());
				while($row_bancos = mysql_fetch_array( $result_bancos )) {
				$selected_bank = "";
				if( $select_bank != "" && strpos($select_bank, "'".$row_bancos['vendas_banco']."'" ) ){ $selected_bank = " selected"; }
				if($optgroup != $row_bancos['vendas_banco'][0]){
					if($count_g > 0) { echo "</optgroup>"; }
					echo "<optgroup label=".$row_bancos['vendas_banco'][0].">";
					$optgroup = $row_bancos['vendas_banco'][0];
					$count_g++;
				}
				echo "<option value='{$row_bancos['vendas_banco']}'{$selected_bank}>{$row_bancos['vendas_banco']}</option>";
				}
				?>
			</select> 
		</div>

		<?php if ($administracao == 1): ?>
		<div class="css_form_campo css_multisel">Unidade:<br>
			<select name='consultor_unidade[]' multiple='multiple' >
			<option value=''>---- Indiferente ----</option>
				<?php
				$optgroup = ""; $count_g = 0;
				$result_unidade = mysql_query("SELECT DISTINCT unidade FROM jos_users ORDER BY unidade;")
				or die(mysql_error());
				while($row_unidade = mysql_fetch_array( $result_unidade )) {
				$selected = "";
				for ($i=0;$i<count($consultor_unidade);$i++){if ($consultor_unidade[$i] == $row_unidade["unidade"]){$selected = "selected";}}
				if($optgroup != $row_unidade['unidade'][0]){
					if($count_g > 0) { echo "</optgroup>"; }
					echo "<optgroup label=".$row_unidade['unidade'][0].">";
					$optgroup = $row_unidade['unidade'][0];
					$count_g++;
				}
				echo "<option value='{$row_unidade['unidade']}'{$selected}>{$row_unidade['unidade']}</option>";
				}
				?>
			</select>
		</div>
		<?php endif; ?>
		</div>
	</div>
</div>

	    <div align="left">
	      
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
            <tbody>
              <tr class="cabecalho">
                <div align="left" class="style8">
				<td width="3%"><span style="color:#cccccc; font-size:8pt">#</span></td>
                <td width="30%">				
		<?php echo "<a class='style8' href='".$links_filtros."&ordemi=sys_clients.clients_nm&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'>Cliente</a> ";
		if ($ordem == 'sys_clients.clients_nm') {echo $img_ordem;}?><br>
					<span style="color:#cccccc; font-size:8pt">CPF: | Matrícula:</span></td>
                <td width="12%">
		<?php echo "<a class='style8' href='".$links_filtros."&ordemi=vendas_orgao&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'>Órgão</a> ";
		if ($ordem == 'vendas_orgao') {echo $img_ordem;}?><br>
				<span style="color:#cccccc; font-size:8pt">Banco: | Proposta:</span></td>
		<td width="11%">
		<?php echo "<a class='style8' href='".$links_filtros."&ordemi=vendas_valor&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'>Valor AF</a> ";
		if ($ordem == 'vendas_valor') {echo $img_ordem;}?><br>
		<?php echo "<a href='".$links_filtros."&ordemi=vendas_tipo_contrato&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'><span style='color:#cccccc; font-size:8pt'>Tipo de Contrato</span></a> ";
		if ($ordem == 'vendas_tipo_contrato') {echo $img_ordem;}?>
    </td>
	<td width="21%">
        <a class="style8" href="#">Consultor</a> | 
		<?php echo "<a href='".$links_filtros."&ordemi=vendas_dia_venda&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'><span style='color:#cccccc; font-size:8pt'>Data da venda:</span></a> ";
		if ($ordem == 'vendas_dia_venda') {echo $img_ordem;}?><br />
		<?php echo "<a href='".$links_filtros."&ordemi=vendas_dia_pago&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'><span style='color:#cccccc; font-size:8pt'>Data pgto. | Mês</span></a> ";
		if ($ordem == 'vendas_dia_pago') {echo $img_ordem;}?>
	</td>
	<td width="15%">
		<?php echo "<a class='style8' href='".$links_filtros."&ordemi=vendas_status&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'>Status</a> ";
		if ($ordem == 'vendas_status') {echo $img_ordem;}?><br>
		<?php echo "<a href='".$links_filtros."&ordemi=vendas_dia_pago&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'><span style='color:#cccccc; font-size:8pt'>Data pgto. | Mês</span></a> ";
		if ($ordem == 'vendas_dia_pago') {echo $img_ordem;}?>
    </td>
	<td width="5%">
                  <img src="sistema/imagens/config.png"></br>

		<?php echo "<a class='style8' href='".$links_filtros."&ordemi=vendas_id&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'>Código</a> ";
		if ($ordem == 'vendas_id') {echo $img_ordem;}?><br>
                </div>
		      </tr>
<tr>
<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
<tbody>
<?php
if ($pendentes){include("sistema/vendas/exibe_lista_revisadas.php");}
if ($aprovadas){include("sistema/vendas/exibe_lista_aprovadas.php");}
if ($fisicos){include("sistema/vendas/exibe_lista_fisicos.php");}
if ($fisicos_pagos){include("sistema/vendas/exibe_lista_fisicos_pagos.php");}

if (($pendentes)||($aprovadas)){echo "<tr><td colspan='7'><div align='center'><h3 class='mypets2'>Todas as Vendas:</h3></div></td></tr>";}
$totalclientes = 0;
$exibindo = 1;
$numero = $exibindo;
include("sistema/vendas/exibe_lista_agente.php");
$exibindo = $exibindo  - 1;

if (($diretoria == 1)||($financeiro == 1)){
	if ($pag_status == "&vendas_status[]=8&vendas_status[]=9"){
		$sum_comissao = ", SUM(vendas_comissao_vendedor) AS total_comissao, SUM(vendas_receita_fr) AS total_fracionados, SUM(vendas_recebido_fr) AS total_fracionados_recebido ";
	}
}

// TOTAIS BASE 1 + 2
$sql_select_total = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_comissao_fortune) AS total_receita_flat, 
SUM(vendas_receita_bruta) AS total_receita_bruta, 
SUM(vendas_receita) AS total_receita, 
SUM(vendas_receita_plastico) AS total_receita_plastico, 
SUM(vendas_receita_ativacao) AS total_receita_ativacao, 
SUM(vendas_receita_saldo) AS total_receita_saldo, 
SUM(vendas_receita_bonus) AS total_receita_bonus, 
SUM(vendas_impostos_flat) AS total_impostos_flat, 
SUM(vendas_impostos_bonus) AS total_impostos_bonus, 
SUM(vendas_taxa) AS total_taxa, 
SUM(vendas_cip) AS total_cip, 
SUM(vendas_impostos_plastico_ativacao) AS total_impostos_plastico_ativacao, 
SUM(vendas_impostos) AS total_impostos, 
SUM(vendas_base_contrato) AS total_base_contrato, 
SUM(vendas_base_prod) AS total_base".$sum_comissao."
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade.$join_tabela." WHERE " . $filtros_sql .";")
or die(mysql_error());
$row_total_valor = mysql_fetch_array( $sql_select_total );
$total_valor = ($row_total_valor['total_valor']) ? number_format($row_total_valor['total_valor'], 2, ',', '.') : '0' ;
$total_receita_flat = ($row_total_valor['total_receita_flat']) ? number_format($row_total_valor['total_receita_flat'], 2, ',', '.') : '0' ;
$total_receita_plastico = ($row_total_valor['total_receita_plastico']) ? number_format($row_total_valor['total_receita_plastico'], 2, ',', '.') : '0' ;
$total_receita_ativacao = ($row_total_valor['total_receita_ativacao']) ? number_format($row_total_valor['total_receita_ativacao'], 2, ',', '.') : '0' ;
$total_receita_bonus = ($row_total_valor['total_receita_bonus']) ? number_format($row_total_valor['total_receita_bonus'], 2, ',', '.') : '0' ;
$total_receita_saldo = ($row_total_valor['total_receita_saldo']) ? number_format($row_total_valor['total_receita_saldo'], 2, ',', '.') : '0' ;
$total_impostos_flat = ($row_total_valor['total_impostos_flat']) ? number_format($row_total_valor['total_impostos_flat'], 2, ',', '.') : '0' ;
$total_taxa = ($row_total_valor['total_taxa']) ? number_format($row_total_valor['total_taxa'], 2, ',', '.') : '0' ;
$total_cip = ($row_total_valor['total_cip']) ? number_format($row_total_valor['total_cip'], 2, ',', '.') : '0' ;
$total_impostos_bonus = ($row_total_valor['total_impostos_bonus']) ? number_format($row_total_valor['total_impostos_bonus'], 2, ',', '.') : '0' ;
$total_impostos_plastico_ativacao = ($row_total_valor['total_impostos_plastico_ativacao']) ? number_format($row_total_valor['total_impostos_plastico_ativacao'], 2, ',', '.') : '0' ;

$total_base_contrato = ($row_total_valor['total_base_contrato']) ? number_format($row_total_valor['total_base_contrato'], 2, ',', '.') : '0' ;
$total_base = ($row_total_valor['total_base']) ? number_format($row_total_valor['total_base'], 2, ',', '.') : '0' ;
	echo "<tr class='even'><div align='left'>";
	echo "<td colspan='7'><div align='right'>Resultados totais de todos os resultados da Pesquisa:</br>";
	echo "<table width='40%'>";	
	echo "<tr>";
	echo "<td><strong>Totais:</strong></td>";
	echo "</tr>";	
	echo "<tr>";
	echo "<td>"; 	
	echo "Valores de AFs: <strong>R$ ".$total_valor."</strong></br>";
	echo "Bases dos Contratos: <strong>R$ ".$total_base_contrato."</strong></br><hr>";	
if (($diretoria == 1)||($financeiro == 1)||($receitas_parceiros == 1)){

	echo "Receita Flat:<strong>R$ ".$total_receita_flat."</strong></span></br>";
	echo "Receitas Bônus: <strong>R$ ".$total_receita_bonus."</strong></br>";
	echo "Receitas Saldo: <strong>R$ ".$total_receita_saldo."</strong></br>";
	echo "Receitas Plástico: <strong>R$ ".$total_receita_plastico."</strong></br>";
	echo "Receitas Ativação: <strong>R$ ".$total_receita_ativacao."</strong></br>";
	$total_receita_bruta = ($row_total_valor['total_receita_bruta']) ? number_format($row_total_valor['total_receita_bruta'], 2, ',', '.') : '0' ;
	echo "Receitas Bruta: <strong>R$ ".$total_receita_bruta."</strong></br><hr>";
	
	echo "Deduções receita Flat: <span style='color:#A5240E;'><strong>R$ ".$total_impostos_flat."</strong></span></br>";
	echo "Deduções receita Bônus: <span style='color:#A5240E;'><strong>R$ ".$total_impostos_bonus."</strong></span></br>";
	echo "Deduções receita Plástico e Ativação: <span style='color:#A5240E;'><strong>R$ ".$total_impostos_plastico_ativacao."</strong></span></br><hr>";
	
	echo "Taxas: <span style='color:#A5240E;'><strong>R$ ".$total_taxa."</strong></span></br>";
	echo "CIP: <span style='color:#A5240E;'><strong>R$ ".$total_cip."</strong></span></br>";
	if ($pag_status == "&vendas_status[]=8&vendas_status[]=9"){
		$total_comissao = ($row_total_valor['total_comissao']>0) ? number_format($row_total_valor['total_comissao'], 2, ',', '.') : '0' ;
		echo "Comissão Agente: <span style='color:#A5240E;'><strong>R$ ".$total_comissao."</strong></span></br><hr>";
	}

	$total_receita = ($row_total_valor['total_receita']) ? number_format($row_total_valor['total_receita'], 2, ',', '.') : '0' ;
	if ($row_total_valor['total_receita'] >= 0){$cor_receita = "#41546F";}else{$cor_receita = "#A5240E";}
	echo "Receita Líquida: <span style='color:".$cor_receita.";'><strong>R$ ".$total_receita."</strong></span></br><hr>";
}
	if (($diretoria == 1)&&($pag_status == "&vendas_status[]=8&vendas_status[]=9")){
		$fracionados_recebidos = $row_total_valor['total_fracionados_recebido'];
		$fracionados_a_receber = $row_total_valor['total_fracionados'] - $fracionados_recebidos;
		$total_fracionados = ($row_total_valor['total_fracionados']>0) ? number_format($row_total_valor['total_fracionados'], 2, ',', '.') : '0' ;
		echo "Receitas Fracionadas Totais: <span style='color:#41546F;'><strong>R$ ".$total_fracionados."</strong></span></br>";
		$fracionados_recebidos = ($fracionados_recebidos>0) ? number_format($fracionados_recebidos, 2, ',', '.') : '0' ;
		echo "Receitas Fracionadas Recbidas: <span style='color:#41546F;'><strong>R$ ".$fracionados_recebidos."</strong></span></br>";
		$fracionados_a_receber = ($fracionados_a_receber>0) ? number_format($fracionados_a_receber, 2, ',', '.') : '0' ;
		echo "Receitas Fracionadas A Receber: <span style='color:#888;'><strong>R$ ".$fracionados_a_receber."</strong></span></br>";
	}
	echo "</div>";	
	echo "</td>";		
	echo "</tr>";	
	echo "</table></div>";	
	echo "</td>"; 	
	echo "</tr>";
	echo "<tr class='even'><div align='left'>";
	echo "<td colspan='7'><div align='center'>";
	echo "<table>";
	echo "<tr>";	
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
<div align="center">
	Exibindo 
	<select name="qnt" style="display: inline;" onchange="this.form.submit()">
		<option value="10"<?php if ($qnt == "10"){echo " selected";}?>>10</option>
		<option value="20"<?php if ($qnt == "20"){echo " selected";}?>>20</option>
		<option value="30"<?php if ($qnt == "30"){echo " selected";}?>>30</option>
	</select> 
	de um total de <?php echo $total_registros;?></div>	
</div>
</form>
<?php mysql_close($con); ?>