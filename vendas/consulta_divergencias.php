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
	defaultexpanded: [1,2], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
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
<?php
$cpf=$_GET["cpf"];
$clients_cat=$_GET["clients_cat"];
if ($_GET["p"]){$pagina=$_GET["p"];}else{$pagina="1";}

$user =& JFactory::getUser();
$username=$user->username;
$user_id=$user->id;

$url_consulta_clientes = $_SERVER['REQUEST_URI'];
$query = mysql_query("UPDATE jos_users SET url_consulta_clientes='$url_consulta_clientes' WHERE id='$user_id';") or die(mysql_error());

if ($_GET["filtro_data1"]) {$filtro_data1 = $_GET["filtro_data1"];}else{$filtro_data1 = "1";}
if ($_GET["filtro_data2"]) {$filtro_data2 = $_GET["filtro_data2"];}else{$filtro_data2 = "2";}
if ($filtro_data1 == "1") {$normal_3_4 = "vendas_dia_imp"; $normal_3_4_hr_ini = "'"; $normal_3_4_hr_fim = "'";}
if ($filtro_data1 == "2") {$normal_3_4 = "vendas_dia_pago"; $normal_3_4_hr_ini = "'"; $normal_3_4_hr_fim = "'";}
if ($filtro_data1 == "3") {$normal_3_4 = "vendas_dia_venda"; $normal_3_4_hr_ini = " 00:00:00'"; $normal_3_4_hr_fim = " 23:59:59'";}
if ($filtro_data2 == "1") {$normal_5_6 = "vendas_dia_imp"; $normal_5_6_hr_ini = "'"; $normal_5_6_hr_fim = "'";}
if ($filtro_data2 == "2") {$normal_5_6 = "vendas_dia_pago"; $normal_5_6_hr_ini = "'"; $normal_5_6_hr_fim = "'";}
if ($filtro_data2 == "3") {$normal_5_6 = "vendas_dia_venda"; $normal_5_6_hr_ini = " 00:00:00'"; $normal_5_6_hr_fim = " 23:59:59'";}

if ($_GET["dp-normal-5"]){
$pag_data_ini = $_GET["dp-normal-5"];
$data_ini = implode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "-" : "/", $_GET["dp-normal-5"])));
$select_data_ini= " AND " . $normal_5_6 . " >= '" . $data_ini . $normal_5_6_hr_ini;
} else {$select_data_ini = "";}

if ($_GET["dp-normal-6"]){
$pag_data_fim = $_GET["dp-normal-6"];
$data_fim = implode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "-" : "/", $_GET["dp-normal-6"])));
$select_data_fim= " AND " . $normal_5_6 . " <= '" . $data_fim . $normal_5_6_hr_fim;
} else {$select_data_fim="";}

if ($_GET["dp-normal-3"]){
$pag_data_imp_ini = $_GET["dp-normal-3"];
$data_imp_ini = implode(preg_match("~\/~", $_GET["dp-normal-3"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-3"]) == 0 ? "-" : "/", $_GET["dp-normal-3"])));
$select_data_imp_ini= " AND " . $normal_3_4 . " >= '" . $data_imp_ini . $normal_3_4_hr_ini;
} else {$select_data_imp_ini = "";}

if ($_GET["dp-normal-4"]){
$pag_data_imp_fim = $_GET["dp-normal-4"];
$data_imp_fim = implode(preg_match("~\/~", $_GET["dp-normal-4"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-4"]) == 0 ? "-" : "/", $_GET["dp-normal-4"])));
$select_data_imp_fim= " AND " . $normal_3_4 . " <= '" . $data_imp_fim . $normal_3_4_hr_fim;
} else {$select_data_imp_fim="";}

$vendas_banco=$_GET["vendas_banco"];
if ($_GET["vendas_banco"]) {$select_bank= " AND vendas_banco like '%" . $vendas_banco . "%'";} else {$select_bank="";}

$vendas_orgao=$_GET["vendas_orgao"];
if ($_GET["vendas_orgao"]) {$select_orgao= " AND vendas_orgao like '%" . $vendas_orgao . "%'";} else {$select_orgao="";}

$vendas_promotora=$_GET["vendas_promotora"];
if ($_GET["vendas_promotora"]) {$select_promotora= " AND vendas_promotora like '%" . $vendas_promotora . "%'";} else {$select_promotora="";}

$vendas_id=$_GET["vendas_id"];
if ($_GET["vendas_id"]) {$select_id= " AND vendas_id = '" . $vendas_id . "'";} else {$select_id="";}

$vendas_proposta=$_GET["vendas_proposta"];
if ($_GET["vendas_proposta"]) {$select_proposta= " AND vendas_proposta = '" . $vendas_proposta . "'";} else {$select_proposta="";}

$vendas_vendedor=$_GET["vendas_vendedor"];
if ($_GET["vendas_vendedor"]) {$select_vendedor= " AND vendas_vendedor = '" . $vendas_vendedor . "'";} else {$select_vendedor="";}

$nome=$_GET["nome"];
if ($_GET["nome"]) {$select_nome= " AND (clients_nm like '%" . $nome . "%' OR cliente_nome like '%" . $nome . "%')";} else {$select_nome="";}

$prec=$_GET["prec"];
if ($_GET["prec"]) {$select_prec= " AND sys_clients.clients_prec_cp like '%" . $prec . "%'";} else {$select_prec="";}

$vendas_turno=$_GET["vendas_turno"];
if ($_GET["vendas_turno"]) {$select_turno= " AND vendas_turno = '" . $vendas_turno . "'";} else {$select_turno="";}

$vendas_envio=$_GET["vendas_envio"];
if ($_GET["vendas_envio"]) {$select_envio= " AND vendas_envio = '" . $vendas_envio . "'";} else {$select_envio="";}

$vendas_seguro_protegido=$_GET["vendas_seguro_protegido"];
if ($_GET["vendas_seguro_protegido"]) {$select_protegido= " AND vendas_seguro_protegido = '" . $vendas_seguro_protegido . "'";} else {$select_protegido="";}

$vendas_estoque=$_GET["vendas_estoque"];
if ($_GET["vendas_estoque"]) {$select_estoque=" AND vendas_estoque = 1";} else {$select_estoque= " AND vendas_estoque = 0";}

if ($_GET["vendas_contrato_fisico"]){
$vendas_contrato_fisico=$_GET["vendas_contrato_fisico"];
				for ($i=0;$i<count($vendas_contrato_fisico);$i++){
					if ($vendas_contrato_fisico[$i] != ""){
						if ($i==0){
							$select_contrato = " AND (vendas_contrato_fisico = '" . $vendas_contrato_fisico[$i] . "'";
						}else{$select_contrato = $select_contrato." OR vendas_contrato_fisico = '" . $vendas_contrato_fisico[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($vendas_contrato_fisico[$aux_stat] != ""){$select_contrato = $select_contrato.")";}
				for ($i=0;$i<count($vendas_contrato_fisico);$i++){
					if ($vendas_contrato_fisico[$i] != ""){
							$pag_contrato = $pag_contrato."&vendas_contrato_fisico[]=".$vendas_contrato_fisico[$i];					
					}
				}
}

if ($_GET["vendas_mes"]){
$vendas_mes=$_GET["vendas_mes"];
				for ($i=0;$i<count($vendas_mes);$i++){
					if ($vendas_mes[$i] != ""){
						if ($i==0){
							$select_mes = " AND (vendas_mes = '" . $vendas_mes[$i] . "'";
						}else{$select_mes = $select_mes." OR vendas_mes = '" . $vendas_mes[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($vendas_mes[$aux_stat] != ""){$select_mes = $select_mes.")";}
				for ($i=0;$i<count($vendas_mes);$i++){
					if ($vendas_mes[$i] != ""){
							$pag_mes = $pag_mes."&vendas_mes[]=".$vendas_mes[$i];					
					}
				}
}

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="vendas_id";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="DESC";}
if ($_GET["ordenacao"] == "ASC"){
	$link_ordem = "DESC";
	$img_ordem = "<img src='sistema/imagens/asc.png'>";
}else{
	$link_ordem = "ASC";
	$img_ordem = "<img src='sistema/imagens/desc.png'>";
}

$user =& JFactory::getUser();
$username=$user->username;
$user_id=$user->id;
if ($_GET["contar"]) {
	$contagem = ", COUNT(sys_vendas.clients_cpf) AS contagem"; 
	$agrupamento=" GROUP BY sys_vendas.clients_cpf ";
	if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="contagem";}	
}else{
	$agrupamento="";
}
include("sistema/utf8.php");
$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '10'){$administracao = 1;}
	if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if ($row_grupo_user['id'] == '19'){$financeiro = 1;}
	if ($row_grupo_user['id'] == '21'){$franquiado = 1;}
	if ($row_grupo_user['id'] == '11'){$sup_operacional = 1;}
	if ($row_grupo_user['id'] == '23'){$frame_revisadas = 1;}
	if ($row_grupo_user['id'] == '24'){$frame_averbadas = 1;}
	if ($row_grupo_user['id'] == '25'){$frame_fisicos = 1;}
	if ($row_grupo_user['id'] == '27'){$frame_fracionadas = 1;}
	if ($row_grupo_user['id'] == '38'){$frame_autorizacao = 1;}
	if ($row_grupo_user['id'] == '34'){$supervisor_agentes = 1;}
	if ($row_grupo_user['id'] == '37'){$supervisor_equipe_vendas = 1;}
}

if ($_GET["vendas_status"]){
$vendas_status=$_GET["vendas_status"];
				for ($i=0;$i<count($vendas_status);$i++){
					if ($vendas_status[$i] != ""){
						if ($i==0){
							$select_status = " AND (vendas_status = '" . $vendas_status[$i] . "'";
						}else{$select_status = $select_status." OR vendas_status = '" . $vendas_status[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($vendas_status[$aux_stat] != ""){$select_status = $select_status.")";}
				for ($i=0;$i<count($vendas_status);$i++){
					if ($vendas_status[$i] != ""){
							$pag_status = $pag_status."&vendas_status[]=".$vendas_status[$i];					
					}
				}
}elseif (($administracao == 1)||($diretoria == 1)||($financeiro == 1)){$select_status= " AND (vendas_status <= '12' OR vendas_status >= '15')";}

if ($_GET["vendas_tipo_contrato"]){
$vendas_tipo_contrato=$_GET["vendas_tipo_contrato"];
				for ($i=0;$i<count($vendas_tipo_contrato);$i++){
					if ($vendas_tipo_contrato[$i] != ""){
						if ($i==0){
							$select_tipo = " AND (vendas_tipo_contrato = '" . $vendas_tipo_contrato[$i] . "'";
						}else{$select_tipo = $select_tipo." OR vendas_tipo_contrato = '" . $vendas_tipo_contrato[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($vendas_tipo_contrato[$aux_stat] != ""){$select_tipo = $select_tipo.")";}
				for ($i=0;$i<count($vendas_tipo_contrato);$i++){
					if ($vendas_tipo_contrato[$i] != ""){
							$pag_tipo = $pag_tipo."&vendas_tipo_contrato[]=".$vendas_tipo_contrato[$i];					
					}
				}
}

$result_user = mysql_query("SELECT nivel, unidade, equipe_id FROM jos_users WHERE id = '" . $user_id . "';") 
or die(mysql_error());
$array_user_id = mysql_fetch_array( $result_user );
$user_nivel = $array_user_id["nivel"];
$user_unidade = $array_user_id["unidade"];
$user_equipe = $array_user_id["equipe_id"];

$consultor_unidade=$_GET["consultor_unidade"];

$join_unidade= " INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id";
if ($_GET["consultor_unidade"]){
$consultor_unidade=$_GET["consultor_unidade"];
				for ($i=0;$i<count($consultor_unidade);$i++){
					if ($consultor_unidade[$i] != ""){
						if ($i==0){
							$select_unidade = " AND (jos_users.unidade = '" . $consultor_unidade[$i] . "'";
						}else{$select_unidade = $select_unidade." OR jos_users.unidade = '" . $consultor_unidade[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($consultor_unidade[$aux_stat] != ""){$select_unidade = $select_unidade.")";}
				for ($i=0;$i<count($consultor_unidade);$i++){
					if ($consultor_unidade[$i] != ""){
							$pag_unidade = $pag_unidade."&consultor_unidade[]=".$consultor_unidade[$i];					
					}
				}
} else {$select_unidade="";}

if ($administracao == 1){
$vendas_consultor=$_GET["vendas_consultor"];
if ($_GET["vendas_consultor"]) {$select_consultor= " AND vendas_consultor = " . $vendas_consultor;} else {$select_consultor="";}
}elseif (($user_nivel == "5")||($user_nivel == "6")||($user_nivel == "7")||($supervisor_equipe_vendas == 1)){
	if ($_GET["vendas_consultor"]) {
		$vendas_consultor=$_GET["vendas_consultor"];
		$select_consultor= " AND vendas_consultor = '" . $vendas_consultor . "'";
	}else{$select_consultor="";}
	$select_unidade= " AND jos_users.unidade = '" . $user_unidade . "'";
	if ($supervisor_equipe_vendas == 1) {$select_equipe = " AND jos_users.equipe_id = '". $user_equipe ."' AND jos_users.equipe_id > 0";}
}else{
	$select_consultor= " AND vendas_consultor = " . $user_id;
	$select_unidade= "";
}

$select_divergencia = " AND vendas_divergencia != 'NENHUMA'";

$p = $_GET["p"];
if(isset($p)) {
$p = $p;
} else {
$p = 1;
}
if ($_GET["qnt"]){$qnt = $_GET["qnt"];}else{$qnt = 20;}
$inicio = ($p*$qnt) - $qnt;
$filtros_sql = $select_prec . 
$select_nome . 
$select_id . 
$select_proposta . 
$select_vendedor . 
$select_state . 
$select_city . 
$select_bank . 
$select_data_ini . 
$select_data_fim . 
$select_data_imp_ini . 
$select_data_imp_fim . 
$select_status . 
$select_orgao . 
$select_tipo . 
$select_consultor . 
$select_unidade . 
$select_equipe . 
$select_promotora . 
$select_mes . 
$select_contrato . 
$select_envio . 
$select_protegido . 
$select_estoque . 
$select_divergencia . 
$select_turno;
$result = mysql_query("SELECT *" . $contagem . " FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$filtros_sql . 
$agrupamento." ORDER BY " . $ordem . " " . $ordenacao . " LIMIT " . $inicio . ", " . $qnt . ";") 
or die(mysql_error());
?>
 <?php  $curURL = $_SERVER["REQUEST_URI"]; ?>

<form action="index.php" method="GET">
<input id="sis_campo" name="option" type="hidden" id="option" value="com_k2" />
					<input id="sis_campo" name="view" type="hidden" id="view" value="item" />
					<input id="sis_campo" name="id" type="hidden" id="id" value="183" />
					<input id="sis_campo" name="Itemid" type="hidden" id="Itemid" value="498" />

  <div align="center">
    <table width="100%" height="99%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tbody>
               
            <tr>
              <td width="32%" valign="top" class="style8" style="text-align: right;"><span class="style5">
<table width="100%" border="1" bordercolor="#266195" align="center" cellpadding="0" cellspacing="2" >
<tbody>
<tr>
<td width="73%">
        <table width="100%" class="blocos" border="0" align="center" cellpadding="0" cellspacing="2">
            <tbody>
                <tr>
                  <td width="22%" style="text-align: right;"><div align="right">Código: <input id="vendas_id" name="vendas_id" value="<?php echo $vendas_id;?>" type="text" maxlength="6" size="5"/></div></td>
                  <td width="27%" style="text-align: right;">CPF: <input id="cpf" name="cpf" value="<?php echo $cpf;?>" type="text" maxlength="11" size="11" /></td>
                  <td width="36%"><span class="style8" style="text-align: right;">Matrícula: <input id="prec" name="prec" value="<?php echo $prec;?>" type="text" maxlength="10" size="10"/></td>                  
                </tr>
                <tr>
                  <td colspan="2" style="text-align: right;">Nome: <input id="nome" name="nome" value="<?php echo $nome;?>" type="text" size="25" /></td>
                  <td><div align="right">
					Nº da Proposta: <input id="vendas_proposta" name="vendas_proposta" value="<?php echo $vendas_proposta;?>" type="text" maxlength="20" size="12"/>
				  </div></td>
                </tr>
                <tr>
                  <td colspan="2" style="text-align: right;">&nbsp;</td>
                  <td><div align="right">
					&nbsp;<a href="index.php?option=com_k2&view=item&layout=item&id=183&Itemid=498"><button name="limpar" type="button" value="limpar">Limpar</button></a><button name="Pesquisa anterior" type="reset" value="Pesquisa anterior">&#8635;</button><button name="buscar" type="submit" value="buscar">Buscar</button> 
				  </div></td>
                </tr>
		</td>
	</tr>
</table>
			<h3 class="mypets2">Busca Avançada:</h3>
			<div class="thepet2">
<table width="100%" class="blocos" border="0" align="center" cellpadding="0" cellspacing="2">
	<tr>	
		<td>			
                <tr>	
				  <td colspan="2">
				  	<div align="left">			
					<table class="split-date-wrap" cellpadding="0" cellspacing="0" border="0">
					  <tbody>
						<tr>
							<td>
							<select name="filtro_data1">
								  <option value="1"<?php if ($filtro_data1 == "1"){echo " selected";}?>>Data de Implantação</option>
								  <option value="2"<?php if ($filtro_data1 == "2"){echo " selected";}?>>Data de Pagamento</option>
								  <option value="3"<?php if ($filtro_data1 == "3"){echo " selected";}?>>Data da Venda</option>
								</select> 
							</td>
<?php $data_field = implode(preg_match("~\/~", $data_imp_ini) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_imp_ini) == 0 ? "-" : "/", $data_imp_ini)));?>						
						  <td><p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-3" name="dp-normal-3" maxlength="10" size="10" value="<?php echo $data_field;?>" placeholder="dd/mm/aaaa"/></p></td>
						  <td><p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-4" name="dp-normal-4" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-4"];?>" placeholder="dd/mm/aaaa"/></p></td>						  
						</tr>
						<tr>
							<td>
							<select name="filtro_data2">
								  <option value="2"<?php if ($filtro_data2 == "2"){echo " selected";}?>>Data de Pagamento</option>
								  <option value="1"<?php if ($filtro_data2 == "1"){echo " selected";}?>>Data de Implantação</option>
								  <option value="2"<?php if ($filtro_data2 == "3"){echo " selected";}?>>Data da Venda</option>
								</select> 
							</td>
<?php $data_field = implode(preg_match("~\/~", $data_imp_ini) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_imp_ini) == 0 ? "-" : "/", $data_imp_ini)));?>						
						  <td><p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-5" name="dp-normal-5" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-5"];?>" placeholder="dd/mm/aaaa"/></p></td>
						  <td><p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-6" name="dp-normal-6" maxlength="10" size="10" value="<?php echo $_GET["dp-normal-6"];?>" placeholder="dd/mm/aaaa"/></p></td>						  
						</tr>
					  </tbody>
					</table>
					</div>	
				  </td>
                  <td><div align="left">Mês válido: <span style="color:#666666; font-size:7pt">(CTRL p/ seleção múltipla)</span>
					<select name="vendas_mes[]" multiple="multiple" style="height:64px; width:200px">
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
				  </td>
				  <td>
				  	<div align="left">Status: <span style="color:#666666; font-size:7pt">(CTRL p/ seleção múltipla)</span>
			<select name="vendas_status[]" multiple="multiple" style="height:64px; width:200px">
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
				  </td>
                </tr>
                <tr>
                  <td><div align="left">
				<select name="vendas_orgao">
<?php
	include("sistema/utf8.php");
	if (!$vendas_orgao){echo "<option value='' selected>------ Órgão ------</option>";}
	echo "<option value=''>---- Indiferente ----</option>";
	$result_orgao = mysql_query("SELECT * FROM sys_orgaos ORDER BY orgao_nome;")
	or die(mysql_error());
	while($row_orgao = mysql_fetch_array( $result_orgao )) {
		if ($row_orgao["orgao_nome"] == $vendas_orgao){$selected = "selected";}else{$selected = "";}
		echo "<option value='{$row_orgao['orgao_nome']}'{$selected}>{$row_orgao['orgao_label']}</option>";
	}
?>
                </select>
				<select name="vendas_banco">
					  <option value="">---- Indiferente ----</option>					
			<?php if ($vendas_banco == "") {echo "<option value='' selected>------ Banco ------</option>";}else{echo "<option value='{$vendas_banco}' selected>{$vendas_banco}</option>";}?>
            <?php
			$result_bancos = mysql_query("SELECT DISTINCT vendas_banco FROM sys_vendas ORDER BY vendas_banco;")
			or die(mysql_error());
			while($row_bancos = mysql_fetch_array( $result_bancos )) {
				echo "<option value='{$row_bancos['vendas_banco']}'>{$row_bancos['vendas_banco']}</option>";
			}

			?>
                    </select>
					</div>
				</td>
				<td>
				<select name="vendas_turno">
					  <option value="">---- Indiferente ----</option>					
<?php
if (!$vendas_turno) {echo "<option value='' selected>------ Turno da Venda ------</option>";}
$result_turno = mysql_query("SELECT * FROM sys_vendas_turno;")
or die(mysql_error());
while($row_turno = mysql_fetch_array( $result_turno )) {
	if ($row_turno["sys_vendas_turno_id"] == $vendas_turno){$selected_turno = " selected";}else{$selected_turno = "";}
	echo "<option value='{$row_turno['sys_vendas_turno_id']}'{$selected_turno}>{$row_turno['sys_vendas_turno_nome']}</option>";
}
?>
                    </select>
<?php if ($administracao == 1) :?>
<?php 
echo "<select name='vendas_consultor'>";
			echo "<option value=''>---- Indiferente ----</option>";
			if (!$vendas_consultor) {echo "<option value='' selected>------ Consultor ------</option>";}
			$result_user_form = mysql_query("SELECT DISTINCT vendas_consultor, name, nivel FROM sys_vendas INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . $select_id . $select_state . $select_city . $select_bank . $select_data_ini . $select_data_fim . $select_data_imp_ini . $select_data_imp_fim . $select_status . $select_orgao . $select_tipo . $select_unidade . $select_equipe . $select_promotora . $select_mes . $select_contrato . " AND jos_users.nivel <> 4 AND jos_users.nivel <> 8 ORDER BY name;")
			or die(mysql_error());
			while($row_user_form = mysql_fetch_array( $result_user_form )) {
				if ($row_user_form["vendas_consultor"] == $vendas_consultor){$selected_consultor = " selected";
				if ($row_user_form["nivel"] == 3){$nivel = "consultor";}
				if ($row_user_form["nivel"] == 2){$nivel = "cordenador";}}else{$selected_consultor = "";}
				echo "<option value='{$row_user_form['vendas_consultor']}'{$selected_consultor}>{$row_user_form['name']}</option>";
			}
echo "</select>";
?>
<?php else:?>
<?php 
if (($user_nivel == "5")||($user_nivel == "6")||($user_nivel == "7")||($supervisor_equipe_vendas == 1)){
echo "<select name='vendas_consultor'>";
			echo "<option value=''>---- Indiferente ----</option>";
			if (!$vendas_consultor) {echo "<option value='' selected>------ Consultor ------</option>";}
			if ($supervisor_equipe_vendas == 1) {$select_equipe = " AND equipe_id = '" . $user_equipe . "'";}else{$select_equipe = "";}
			$result_user_form = mysql_query("SELECT id, name FROM jos_users WHERE unidade = '" . $user_unidade . "'".$select_equipe." ORDER BY name;")
			or die(mysql_error());
			while($row_user_form = mysql_fetch_array( $result_user_form )) {
				if ($row_user_form["id"] == $vendas_consultor){$selected_consultor = " selected";}else{$selected_consultor = "";}
				echo "<option value='{$row_user_form['id']}'{$selected_consultor}>{$row_user_form['name']}</option>";
			}
echo "</select>";
}else{echo "&nbsp;";}
?>
<?php endif;?>
				  </td>
                  <td><div align="left">Contrato Físico: <span style="color:#666666; font-size:7pt">(CTRL p/ seleção múltipla)</span>
			<select name="vendas_contrato_fisico[]" multiple="multiple" style="height:64px; width:200px">
                      <option value="">---- Indiferente ----</option>			
                      <option value="0" <?php for ($i=0;$i<count($vendas_contrato_fisico);$i++){if ($vendas_contrato_fisico[$i] == "0"){echo "selected";}}?>>Não</option>
                      <option value="3" <?php for ($i=0;$i<count($vendas_contrato_fisico);$i++){if ($vendas_contrato_fisico[$i] == "3"){echo "selected";}}?>>Pendente</option>
					  <option value="4" <?php for ($i=0;$i<count($vendas_contrato_fisico);$i++){if ($vendas_contrato_fisico[$i] == "4"){echo "selected";}}?>>Bloqueado</option>
					  <option value="1" <?php for ($i=0;$i<count($vendas_contrato_fisico);$i++){if ($vendas_contrato_fisico[$i] == "1"){echo "selected";}}?>>Físico no Operacional</option>
					  <option value="2" <?php for ($i=0;$i<count($vendas_contrato_fisico);$i++){if ($vendas_contrato_fisico[$i] == "2"){echo "selected";}}?>>Enviado Promotora</option>
                    </select>
				  </div></td>
                  <td><div align="left">
<?php 
if ($administracao == 1){
echo "Unidade: <span style='color:#666666; font-size:7pt'>(CTRL p/ seleção múltipla)</span>";
echo "<select name='consultor_unidade[]' multiple='multiple' style='height:64px; width:200px'>";
			echo "<option value=''>---- Indiferente ----</option>";
$result_unidade = mysql_query("SELECT DISTINCT unidade FROM jos_users ORDER BY unidade;")
or die(mysql_error());
while($row_unidade = mysql_fetch_array( $result_unidade )) {
	$selected = "";
	for ($i=0;$i<count($consultor_unidade);$i++){if ($consultor_unidade[$i] == $row_unidade["unidade"]){$selected = "selected";}}
	echo "<option value='{$row_unidade['unidade']}'{$selected}>{$row_unidade['unidade']}</option>";
}
echo "</select>";
}
?>
				  </div></td>
			  </tr>
			  <tr>
					<td>
						<select name="vendas_promotora">
							  <option value="">---- Indiferente ----</option>			
							<?php
								if (!$vendas_promotora) {echo "<option value='' selected>------ Promotora ------</option>";}
								$result_promo = mysql_query("SELECT promotora_nome FROM sys_vendas_promotoras ORDER BY promotora_nome;")
								or die(mysql_error());
								while($row_promo = mysql_fetch_array( $result_promo )) {
									if ($row_promo["promotora_nome"] == $vendas_promotora){$selected_promo = "selected";}else{$selected_promo = "";}
									echo "<option value='{$row_promo['promotora_nome']}'{$selected_promo}>{$row_promo['promotora_nome']}</option>";
								}
							?>
							</select>
					</td>
					<td>
<?php if ($administracao == 1) :?>
						<select name="vendas_envio">
							<?php
								if (!$vendas_envio) {echo "<option value='' selected>------ Método de Envio ------</option>";}
								echo "<option value=''>---- Indiferente ----</option>";
								$result_envio = mysql_query("SELECT * FROM sys_vendas_envio ORDER BY envio_id;")
								or die(mysql_error());
								while($row_envio = mysql_fetch_array( $result_envio )) {
									if ($row_envio["envio_id"] == $vendas_envio){$selected = "selected";}else{$selected = "";}
									echo "<option value='{$row_envio['envio_id']}'{$selected}>{$row_envio['envio_nome']}</option>";
								}
							?>
						</select>
						<select name="vendas_seguro_protegido">
			<?php if ($vendas_seguro_protegido == "") {echo "<option value='' selected>---- Seguro Consignado Protegido ----</option>";}else{echo "<option value='' selected>---- Indiferente ----</option>";}?>
						  <option value="1"<?php if ($vendas_seguro_protegido == "1"){echo " selected";}?>>Não</option>
						  <option value="2"<?php if ($vendas_seguro_protegido == "2"){echo " selected";}?>>Sim</option>
						</select>
<?php endif;?>
							</td>
					<td>
						<div align="left">Tipo de Contrato: <span style="color:#666666; font-size:7pt">(CTRL p/ seleção múltipla)</span>
						<select name="vendas_tipo_contrato[]" multiple="multiple" style="height:64px; width:200px">
								  <option value="">---- Indiferente ----</option>	
						<?php
							$result_tipos = mysql_query("SELECT * FROM sys_vendas_tipos ORDER BY tipo_nome;")
							or die(mysql_error());
							while($row_tipos = mysql_fetch_array( $result_tipos )) {
								$selected_tipo = "";
								for ($i=0;$i<count($vendas_tipo_contrato);$i++){if ($vendas_tipo_contrato[$i] == $row_tipos["tipo_id"]){$selected_tipo = " selected";}}
								echo "<option value='{$row_tipos['tipo_id']}'{$selected_tipo}>{$row_tipos['tipo_nome']}</option>";
							}
						?>
								</select>
						</div>
					</td>
				</tr>
            </tbody>
        </table>
		</div>
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
<?php $links_filtros = "index.php?option=com_k2&view=item&layout=item&id=183&Itemid=498&vendas_id=".$vendas_id."&nome=".$nome."&prec=".$prec."&cpf=".$cpf.$pag_mes."&contar=".$_GET['contar']."&consultor_unidade=".$pag_unidade."&vendas_consultor=".$vendas_consultor."&vendas_vendedor=".$vendas_vendedor.$pag_status.$pag_tipo.$pag_contrato."&vendas_promotora=".$vendas_promotora."&vendas_banco=".$vendas_banco."&vendas_orgao=".$vendas_orgao."&vendas_seguro_protegido=".$vendas_seguro_protegido."&vendas_estoque=".$vendas_estoque."&dp-normal-3=".$pag_data_imp_ini."&dp-normal-4=".$pag_data_imp_fim."&dp-normal-5=".$pag_data_ini."&dp-normal-6=".$pag_data_fim."&filtro_data1=".$_GET['filtro_data1']."&filtro_data2=".$_GET['filtro_data2']."&qnt=".$qnt;?>
				
		<?php echo "<a class='style8' href='".$links_filtros."&ordemi=sys_clients.clients_nm&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'>Cliente</a> ";
		if ($ordem == 'sys_clients.clients_nm') {echo $img_ordem;}?><br>
					<span style="color:#cccccc; font-size:8pt">CPF:</span></td>
                <td width="12%">
		<?php echo "<a class='style8' href='".$links_filtros."&ordemi=vendas_banco&ordenacao=".$link_ordem."&p=".$pagina."' target='_self'>Banco</a> ";
		if ($ordem == 'vendas_orgao') {echo $img_ordem;}?><br>
				<span style="color:#cccccc; font-size:8pt">Proposta:</span></td>
		<td width="11%"><a class="style8" href="#">Valores:</a></td>
		<td width="26%"><a class="style8" href="#">Divergências</a></td>
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
$totalclientes = 0;
$fracionados_recebidos = 0;
$exibindo = 1;
$numero = $exibindo;
include("sistema/vendas/exibe_lista_divergencias.php");
$exibindo = $exibindo  - 1;

if (($diretoria == 1)||($financeiro == 1)){
if (($diretoria == 1)||($financeiro == 1)){
	if (($vendas_mes)&&($pag_status == "&vendas_status[]=9&vendas_status[]=8")){$sum_comissao = ", SUM(vendas_comissao_vendedor) AS total_comissao";}else{$sum_comissao = " ";}
	if ($pag_status == "&vendas_status[]=9&vendas_status[]=8"){$sum_comissao = $sum_comissao.", SUM(vendas_receita_fr) AS total_fracionados, SUM(vendas_recebido_fr) AS total_fracionados_recebido ";}
}
}

// TOTAIS BASE 1
$sql_select_total_1 = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_receita) AS total_receita, 
SUM(vendas_base_prod) AS total_base".$sum_comissao."
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%' AND sys_vendas.vendas_base = '1'" . 
$filtros_sql . ";")
or die(mysql_error());
$row_total_valor_1 = mysql_fetch_array( $sql_select_total_1 );
$total_valor_1 = ($row_total_valor_1['total_valor']>0) ? number_format($row_total_valor_1['total_valor'], 2, ',', '.') : '0' ;
$total_receita_1 = ($row_total_valor_1['total_receita']>0) ? number_format($row_total_valor_1['total_receita'], 2, ',', '.') : '0' ;
$total_base_1 = ($row_total_valor_1['total_base']>0) ? number_format($row_total_valor_1['total_base'], 2, ',', '.') : '0' ;
if (($diretoria == 1)||($financeiro == 1)){
	if (($vendas_mes)&&($pag_status == "&vendas_status[]=9&vendas_status[]=8")){
		$total_comissao_1 = ($row_total_valor_1['total_comissao']>0) ? number_format($row_total_valor_1['total_comissao'], 2, ',', '.') : '0' ;
		}
}

// TOTAIS BASE 2
$sql_select_total_2 = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_receita) AS total_receita, 
SUM(vendas_base_prod) AS total_base".$sum_comissao."
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%' AND sys_vendas.vendas_base = '2'" . 
$filtros_sql . ";")
or die(mysql_error());
$row_total_valor_2 = mysql_fetch_array( $sql_select_total_2 );
$total_valor_2 = ($row_total_valor_2['total_valor']>0) ? number_format($row_total_valor_2['total_valor'], 2, ',', '.') : '0' ;
$total_receita_2 = ($row_total_valor_2['total_receita']>0) ? number_format($row_total_valor_2['total_receita'], 2, ',', '.') : '0' ;
$total_base_2 = ($row_total_valor_2['total_base']>0) ? number_format($row_total_valor_2['total_base'], 2, ',', '.') : '0' ;
if (($diretoria == 1)||($financeiro == 1)){
	if (($vendas_mes)&&($pag_status == "&vendas_status[]=9&vendas_status[]=8")){
	$total_comissao_2 = ($row_total_valor_2['total_comissao']>0) ? number_format($row_total_valor_2['total_comissao'], 2, ',', '.') : '0' ;
	}
}

// TOTAIS BASE 1 + 2
$sql_select_total = mysql_query("SELECT 
SUM(vendas_valor) AS total_valor, 
SUM(vendas_receita) AS total_receita, 
SUM(vendas_receita_bonus) AS total_receita_bonus, 
SUM(vendas_impostos) AS total_impostos, 
SUM(vendas_base_contrato) AS total_base_contrato, 
SUM(vendas_base_prod) AS total_base".$sum_comissao."
FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$filtros_sql . ";")
or die(mysql_error());
$row_total_valor = mysql_fetch_array( $sql_select_total );
$total_valor = ($row_total_valor['total_valor']>0) ? number_format($row_total_valor['total_valor'], 2, ',', '.') : '0' ;
$total_receita = ($row_total_valor['total_receita']<>0) ? number_format($row_total_valor['total_receita'], 2, ',', '.') : '0' ;
$total_receita_bonus = ($row_total_valor['total_receita_bonus']>0) ? number_format($row_total_valor['total_receita_bonus'], 2, ',', '.') : '0' ;
$total_impostos = ($row_total_valor['total_impostos']>0) ? number_format($row_total_valor['total_impostos'], 2, ',', '.') : '0' ;
$total_base_contrato = ($row_total_valor['total_base_contrato']>0) ? number_format($row_total_valor['total_base_contrato'], 2, ',', '.') : '0' ;
$total_base = ($row_total_valor['total_base']>0) ? number_format($row_total_valor['total_base'], 2, ',', '.') : '0' ;
if (($diretoria == 1)||($financeiro == 1)){
	if (($vendas_mes)&&($pag_status == "&vendas_status[]=9&vendas_status[]=8")){
		$total_comissao = ($row_total_valor['total_comissao']>0) ? number_format($row_total_valor['total_comissao'], 2, ',', '.') : '0' ;
		$bonus = 0;
		if ($vendas_consultor) {
			//if ($nivel == "consultor"){
			//	if (($row_total_valor['total_base'] >= 65000)&&($row_total_valor['total_base'] <= 99999)){$bonus_rs = "R$ 300,00";$bonus = 300;}
			//	if ($row_total_valor['total_base'] >= 100000){$bonus_rs = "R$ 500,00";$bonus = 500;}
			//	if ($row_total_valor['total_base'] >= 200000){$bonus_rs = "R$ 1.000,00";$bonus = 1000;}
			//	if ($row_total_valor['total_base'] >= 300000){$bonus_rs = "R$ 1.500,00";$bonus = 1500;}
			//	if ($row_total_valor['total_base'] >= 400000){$bonus_rs = "R$ 2.000,00";$bonus = 2000;}
			//	if ($row_total_valor['total_base'] >= 500000){$bonus_rs = "R$ 2.500,00";$bonus = 2500;}
			//}
			$txt_consultor = "Consultor";
		}else{$txt_consultor = "Consultores";}
		
		if (!$vendas_consultor) {
			$operacional1 = ($row_total_valor['total_base'] * 0.05) / 100;
			$operacional1_rs = ($operacional1>0) ? number_format($operacional1, 2, ',', '.') : '0' ;
			if ($row_total_valor['total_base'] <= 999999){$operacional2 = 0;}
			else{
				$operacional2 = ($row_total_valor['total_base'] * 0.025) / 100;
				$operacional2_rs = ($operacional2>0) ? number_format($operacional2, 2, ',', '.') : '0' ;
			}
		}else{
			$operacional1 = 0;
			$operacional2 = 0;
		}
		
		if (($row_total_valor['total_base'] >= 50000) || ($nivel == "cordenador")){$consulta_total_comissoes = $row_total_valor['total_comissao'];}else{$consulta_total_comissoes = 0;}
		
		$total_comissoes = $consulta_total_comissoes + $bonus;
		$total_comissoes_rs = ($total_comissoes>0) ? number_format($total_comissoes, 2, ',', '.') : '0' ;
		
		$lucro_bruto = $row_total_valor['total_receita'] - $total_comissoes;
		$lucro_bruto = ($lucro_bruto>0) ? number_format($lucro_bruto, 2, ',', '.') : '0' ;
	}
}
	echo "<tr class='even'><div align='left'>";
	echo "<td colspan='7'>Resultados totais de todos os resultados da Pesquisa:</br><div align='center'>";
	echo "<table width='85%'>";	
	
if ($franquiado == 1){
	echo "<tr>";
	echo "<td><strong>Totais:</strong></td>";
	echo "</tr>";	
	echo "<tr>";
	echo "<td>"; 	
	echo "Valores de AFs: <strong>R$ ".$total_valor."</strong></br>";
	echo "Bases dos Contratos: <strong>R$ ".$total_base_contrato."</strong></br>";	
	echo "Receitas: <span style='color:#41546F;'><strong>R$ ".$total_receita."</strong></span></br><hr>";
}else{
	echo "<tr>";
	echo "<td><strong>Vendas com Base 1:</strong></td>";
	echo "<td><strong>Vendas com Base 2:</strong></td>";
	echo "<td><strong>Totais (1 e 2):</strong></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>";
	echo "Valores de AFs: <strong>R$ ".$total_valor_1."</strong></br>";
	echo "Bases de Produção: <strong>R$ ".$total_base_1."</strong></br>";	
	if ($diretoria == 1){echo "Receitas: <strong>R$ ".$total_receita_1."</strong></br><hr>";}
	if (($diretoria == 1)||($financeiro == 1)){
		if (($vendas_mes)&&($pag_status == "&vendas_status[]=9&vendas_status[]=8")&&($row_total_valor['total_base'] >= 50000)){
			echo "$% ".$txt_consultor.": <strong>R$ ".$total_comissao_1."</strong>";
			}
	}
	echo "</div>";	
	echo "</td>";
	echo "<td>";
	echo "Valores de AFs: <strong>R$ ".$total_valor_2."</strong></br>";
	echo "Bases de Produção: <strong>R$ ".$total_base_2."</strong></br>";	
	if ($diretoria == 1){echo "Receitas: <strong>R$ ".$total_receita_2."</strong></br><hr>";}
	if (($diretoria == 1)||($financeiro == 1)){
		if (($vendas_mes)&&($pag_status == "&vendas_status[]=9&vendas_status[]=8")&&($row_total_valor['total_base'] >= 50000)){
			echo "$% ".$txt_consultor.": <strong>R$ ".$total_comissao_2."</strong>";
			}
	}
	echo "</div>";
	echo "</td>";
	echo "<td>";
	echo "Valores de AFs: <strong>R$ ".$total_valor."</strong></br>";
	echo "Bases de Produção: <strong>R$ ".$total_base."</strong></br>";
	if (($diretoria == 1)||($user_nivel == "5")||($user_nivel == "6")){
		echo "Impostos: <span style='color:#A5240E;'><strong>R$ ".$total_impostos."</strong></span></br>";
		echo "Receitas Bônus: <strong>R$ ".$total_receita_bonus."</strong></br>";
		echo "Receitas Totais: <span style='color:#41546F;'><strong>R$ ".$total_receita."</strong></span></br><hr>";
	}
	if (($diretoria == 1)||($financeiro == 1)){
		if (($vendas_mes)&&($pag_status == "&vendas_status[]=9&vendas_status[]=8")){
			if ($row_total_valor['total_base'] >= 50000){echo "$% ".$txt_consultor.": <strong>R$ ".$total_comissao."</strong></br>";}
			if ($bonus > 0){echo "Bônus: <strong>R$ ".$bonus_rs."</strong></br>";}
			echo "$% Total: <span style='color:#A5240E;'><strong>R$ ".$total_comissoes_rs."</strong></span></br><hr>";
			if ($diretoria == 1){echo "$% Lucro Bruto: <span style='color:#819510;'><strong>R$ ".$lucro_bruto."</strong></span></br>";}
		}
	}
	if (($diretoria == 1)&&($pag_status == "&vendas_status[]=9&vendas_status[]=8")){
		$fracionados_recebidos = $row_total_valor['total_fracionados_recebido'];
		$total_fracionados = ($row_total_valor['total_fracionados']>0) ? number_format($row_total_valor['total_fracionados'], 2, ',', '.') : '0' ;
		$fracionados_a_receber = $row_total_valor['total_fracionados'] - $fracionados_recebidos;
		echo "<hr>Receitas Fracionadas Totais: <strong>R$ ".$total_fracionados."</strong></br>";
		$fracionados_recebidos = ($fracionados_recebidos>0) ? number_format($fracionados_recebidos, 2, ',', '.') : '0' ;
		echo "Receitas Fracionadas Recbidas: <span style='color:#41546F;'><strong>R$ ".$fracionados_recebidos."</strong></span></br>";
		$fracionados_a_receber = ($fracionados_a_receber>0) ? number_format($fracionados_a_receber, 2, ',', '.') : '0' ;
		echo "Receitas Fracionadas A Receber: <span style='color:#888;'><strong>R$ ".$fracionados_a_receber."</strong></span></br>";
	}
}
	echo "</div>";	
	echo "</td>";		
	echo "</tr>";	
	echo "</table>";	
	echo "</td>"; 	
	echo "</tr>";
	echo "<tr class='even'><div align='left'>";
	echo "<td colspan='7'><div align='center'>";
	echo "<table>";
	echo "<tr>";	
$sql_select_all = mysql_query("SELECT COUNT(*) AS total FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$filtros_sql . ";")
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
<div align="center">
	Exibindo 
	<select name="qnt" style="display: inline;" onchange="this.form.submit()">
		<option value="10"<?php if ($qnt == "10"){echo " selected";}?>>10</option>
		<option value="20"<?php if ($qnt == "20"){echo " selected";}?>>20</option>
		<option value="30"<?php if ($qnt == "30"){echo " selected";}?>>30</option>
	</select> 
	de um total de <?php echo $total_registros;?></div>	
</div>	
  </div>
</form>
<?php mysql_close($con); ?>