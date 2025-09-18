<style>
	td{
		border-bottom:2px solid #333;
		padding-left: 2px;
	}
</style>
<?php
$cpf=$_GET["cpf"];
$clients_cat=$_GET["clients_cat"];
if ($_GET["p"]){$pagina=$_GET["p"];}else{$pagina="1";}
//$vendas_status=$_GET["vendas_status"];
//if ($_GET["vendas_status"]) {$select_status= " AND vendas_status = '" . $vendas_status . "'";} else {$select_status="";}

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
if ($_GET["dp-normal-1"]){
$pag_data_ini = $_GET["dp-normal-1"];
$data_ini = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));
$select_data_ini= " AND vendas_dia_venda >= '" . $data_ini . " 00:00:00'";
} else {$select_data_ini = "";}

if ($_GET["dp-normal-2"]){
$pag_data_fim = $_GET["dp-normal-2"];
$data_fim = implode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "-" : "/", $_GET["dp-normal-2"])));
$select_data_fim= " AND vendas_dia_venda <= '" . $data_fim . " 23:59:59'";
} else {$select_data_fim="";}

if ($_GET["dp-normal-3"]){
$pag_data_imp_ini = $_GET["dp-normal-3"];
$data_imp_ini = implode(preg_match("~\/~", $_GET["dp-normal-3"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-3"]) == 0 ? "-" : "/", $_GET["dp-normal-3"])));
$select_data_imp_ini= " AND vendas_dia_imp >= '" . $data_imp_ini . " 00:00:00'";
} else {$select_data_imp_ini = "";}

if ($_GET["dp-normal-4"]){
$pag_data_imp_fim = $_GET["dp-normal-4"];
$data_imp_fim = implode(preg_match("~\/~", $_GET["dp-normal-4"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-4"]) == 0 ? "-" : "/", $_GET["dp-normal-4"])));
$select_data_imp_fim= " AND vendas_dia_imp <= '" . $data_imp_fim . " 23:59:59'";
} else {$select_data_imp_fim="";}

$vendas_banco=$_GET["vendas_banco"];
if ($_GET["vendas_banco"]) {$select_bank= " AND vendas_banco like '%" . $vendas_banco . "%'";} else {$select_bank="";}

$vendas_orgao=$_GET["vendas_orgao"];
if ($_GET["vendas_orgao"]) {$select_orgao= " AND vendas_orgao like '%" . $vendas_orgao . "%'";} else {$select_orgao="";}

$vendas_promotora=$_GET["vendas_promotora"];
if ($_GET["vendas_promotora"]) {$select_promotora= " AND vendas_promotora like '%" . $vendas_promotora . "%'";} else {$select_promotora="";}

$vendas_id=$_GET["vendas_id"];
if ($_GET["vendas_id"]) {$select_id= " AND vendas_id = '" . $vendas_id . "'";} else {$select_id="";}
//$select_id= " AND (vendas_id = '59179' OR vendas_id = '60720')";

$nome=$_GET["nome"];
if ($_GET["nome"]) {$select_nome= " AND (clients_nm like '%" . $nome . "%' OR cliente_nome like '%" . $nome . "%')";} else {$select_nome="";}

$prec=$_GET["prec"];
if ($_GET["prec"]) {$select_prec= " AND sys_clients.clients_prec_cp like '%" . $prec . "%'";} else {$select_prec="";}

$vendas_turno=$_GET["vendas_turno"];
if ($_GET["vendas_turno"]) {$select_turno= " AND vendas_turno = '" . $vendas_turno . "'";} else {$select_turno="";}

$vendas_contrato_fisico=$_GET["vendas_contrato_fisico"];
if (($_GET["vendas_contrato_fisico"] == "0")
|| ($_GET["vendas_contrato_fisico"] == "1")
|| ($_GET["vendas_contrato_fisico"] == "2")
|| ($_GET["vendas_contrato_fisico"] == "3")
|| ($_GET["vendas_contrato_fisico"] == "4")) {$select_contrato= " AND vendas_contrato_fisico = '" . $vendas_contrato_fisico . "'";} else {$select_contrato="";}

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

include("sistema/utf8.php");

$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '10'){$administracao = 1;}
	if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if ($row_grupo_user['id'] == '19'){$financeiro = 1;}
}

$select_unidade="";
$join_unidade="";
	
$p = $_GET["p"];
if(isset($p)) {
$p = $p;
} else {
$p = 1;
}
$qnt = 20;
$inicio = ($p*$qnt) - $qnt;
$result = mysql_query("SELECT * FROM sys_vendas 
LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf) 
INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id 
WHERE sys_vendas.clients_cpf like '%" . $cpf . "%' 
AND vendas_receita <= 0 
AND jos_users.nivel <> 4 AND jos_users.nivel <> 8 " . 
$select_prec . 
$select_nome . 
$select_id . 
$select_state . 
$select_city . 
$select_bank . 
$select_data_ini . 
$select_data_fim . 
$select_data_imp_ini . 
$select_data_imp_fim . 
$select_status . 
$select_orgao . 
$select_consultor . 
$select_unidade . 
$select_promotora . 
$select_mes . 
$select_contrato . 
$select_turno . 
" ORDER BY " . $ordem . " " . $ordenacao . ";") 
or die(mysql_error());
?>

 <?php  $curURL = $_SERVER["REQUEST_URI"]; ?>
 
<div align="left">
	<h2>VENDAS DE CRÉDITO:</h2>
  <table width="100%" border="2" align="center" cellpadding="0" cellspacing="1">
		<tbody>
		  <tr class="cabecalho">
			<div align="left" class="style8">
				<td width="3%"><span style="color:#666; font-size:8pt">#</span></td>
				<td width="30%">				
					Cliente<br>
					<span style="color:#666; font-size:8pt">CPF: | Matrícula:</span></td>
				<td width="12%">
					Órgão<br>
					<span style="color:#666; font-size:8pt">Banco:</span></td>
				<td width="11%">
					Valor AF<br>
					<span style='color:#666; font-size:8pt'>Tipo</span>
				</td>
				<td width="21%">
					Consultor<br>
					<span style='color:#666; font-size:8pt'>Data da venda:</span></td>
				<td width="15%">
					Status<br>
					<span style='color:#666; font-size:8pt'>Data pgto. | Mês</span>
				</td>
				<td width="5%">Cód.</td>
			</div>
		</tr>
	<tr>
<table class="listaValores" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 2px solid #333;">
<tbody>

<?php
$totalclientes = 0;
$exibindo = 1;
$numero = $exibindo;
include("sistema/vendas/lista_atualiza_lote.php");
$exibindo = $exibindo  - 1;

	echo "<tr style='vertical-align:baseline;'><div align='left'>";
	echo "<td colspan='7'><div align='center'>";
	echo "<table>";
	echo "<tr style='vertical-align:baseline;'>";
$sql_select_all = mysql_query("SELECT COUNT(*) AS total FROM sys_vendas LEFT JOIN sys_clients ON (sys_vendas.clients_cpf = sys_clients.clients_cpf) LEFT JOIN sys_inss_clientes ON (sys_vendas.clients_cpf = sys_inss_clientes.cliente_cpf)".$join_unidade." WHERE sys_vendas.clients_cpf like '%" . $cpf . "%'" . 
$select_prec . 
$select_nome . 
$select_id . 
$select_state . 
$select_city . 
$select_bank . 
$select_data_ini . 
$select_data_fim . 
$select_data_imp_ini . 
$select_data_imp_fim . 
$select_status . 
$select_orgao . 
$select_consultor . 
$select_unidade . 
$select_promotora . 
$select_mes . 
$select_contrato . 
$select_turno .";")
or die(mysql_error());
$row_total_registros = mysql_fetch_array( $sql_select_all );
$total_registros = $row_total_registros["total"];
?>
</tbody>
          </table>
            </tbody>
          </table>
    </table>
<div align="center">Total de <?php echo $total_registros;?> vendas.</div><br>
<hr><br>
<?php
$result_cont_seguros = mysql_query("SELECT COUNT(vendas_id) AS total, SUM(vendas_comissao_vendedor) AS total_cms, SUM(vendas_valor) AS total_valor FROM sys_vendas_seguros 
LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf) 
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
WHERE vendas_pago_vendedor = 1 AND (vendas_status = 9 OR vendas_status = 10 OR vendas_status = 67)" . $select_consultor .";") 
or die(mysql_error());
$row_cont_seguros = mysql_fetch_array( $result_cont_seguros );
?>
	<?php if ($row_cont_seguros['total'] > 4): ?>
		<h2>VENDAS DE SEGUROS:</h2>
		<?php
		$result_seguros = mysql_query("SELECT vendas_id, sys_vendas_seguros.cliente_cpf AS cliente_cpf, 
		clients_nm, cliente_nome, apolice_nome, name, status_nm, vendas_comissao_vendedor, vendas_valor, vendas_dia_venda, apolice_cms_vendedor 
		FROM sys_vendas_seguros 
		LEFT JOIN sys_clients ON (sys_vendas_seguros.cliente_cpf = sys_clients.clients_cpf) 
		LEFT JOIN sys_inss_clientes ON (sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf) 
		INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
		INNER JOIN sys_vendas_status_seg ON (sys_vendas_seguros.vendas_status = sys_vendas_status_seg.status_id) 
		INNER JOIN jos_users ON (sys_vendas_seguros.vendas_consultor = jos_users.id) 
		WHERE vendas_pago_vendedor = 1 AND (vendas_status = 9 OR vendas_status = 10 OR vendas_status = 67)" . $select_consultor ." ORDER BY " . $ordem . " " . $ordenacao . ";") 
		or die(mysql_error());
		?>
		<table width="100%" border="2" align="center" cellpadding="0" cellspacing="1">
			<tr class="cabecalho">
				<div align="left" class="style8">
					<td width="3%"><span style="color:#666; font-size:8pt">#</span></td>
					<td width="25%">				
						Cliente<br>
						<span style="color:#666; font-size:8pt">CPF:</span></td>
					<td width="12%">
						Valor<br>
						<span style="color:#666; font-size:8pt">Apólice:</span></td>
					<td width="21%">
						Consultor<br>
						<span style='color:#666; font-size:8pt'>Data da venda:</span></td>
					<td width="15%">
						Status
					</td>
					<td width="10%">Cód.<br>CMS:</td>
				</div>
			</tr>
			<tr>
				<table class="listaValores" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 2px solid #333;">
					<?php
					$totalclientes = 0;
					$exibindo = 1;
					$numero = $exibindo;
					include("sistema/vendas/lista_rel_consultor_seguros.php");
					$exibindo = $exibindo  - 1;
					?>
				</table>
			</tr>
		</table>
		<?php 
		if($_GET["processar"] == "1"){
			$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_pago_vendedor='2' WHERE vendas_pago_vendedor = 1 AND (vendas_status = 9 OR vendas_status = 10 OR vendas_status = 67)" . $select_consultor .";") or die(mysql_error());
		}
		?>
		<hr>
		<div style="text-align: center;">
			<?php $total_valor_seguros = ($row_cont_seguros["total_valor"]>0) ? number_format($row_cont_seguros["total_valor"], 2, ',', '.') : '0' ; ?>
			<?php $total_cms_seguros = ($row_cont_seguros["total_cms"]>0) ? number_format($row_cont_seguros["total_cms"], 2, ',', '.') : '0' ; ?>
			Total de vendas: <strong><?php echo $row_cont_seguros["total"]; ?></strong><br>
			Valor total de vendas: <strong>R$ <?php echo $total_valor_seguros; ?></strong><br>
			Total % Consultor: <strong>R$ <?php echo $total_cms_seguros; ?></strong><br>
			<a href="<?php echo $_SERVER[REQUEST_URI]; ?>&processar=1"><button class="button validate png" type="button">Processar Pagamento Seguros</button></a>
		</div>
	<?php endif; ?>
	<?php if ($row_total_valor['total_base'] >= 65000): ?>
		<h2>BSCOINS:</h2>
		<div style="text-align: center;">
			<?php $extrato_valor = $row_total_valor['total_base'] / $row_regra_cms['regras_div_fc']; ?>
			<?php $pontos_rs = ($extrato_valor>0) ? number_format($extrato_valor, 2, ',', '.') : '0' ; ?>
			Saldo de BSCoins para creditar: <strong>F&#162; <?php echo $pontos_rs; ?></strong><br><br>
			<a href="<?php echo $_SERVER[REQUEST_URI]; ?>&processar_fc=1"><button class="button validate png" type="button">Creditar BSCoins</button></a>
		</div>
		<?php 
		if($_GET["processar_fc"] == "1"){
			$result_saldo = mysql_query("SELECT pontos FROM jos_users WHERE id = '" . $vendas_consultor . "';") or die(mysql_error());  
			$row_saldo = mysql_fetch_array( $result_saldo );
			$extrato_saldo = $row_saldo["pontos"] + $extrato_valor;
			$extrato_data = date("Y-m-d H:i:s");
			
			$extrato_saldo = round($extrato_saldo, 2);
			$sql = "INSERT INTO sistema.sys_fortcoins_extrato (
			extrato_consultor,
			extrato_tipo,
			extrato_valor,
			extrato_saldo,
			extrato_anexo,
			extrato_data,
			extrato_criador,
			extrato_obs)
			VALUES (
			'$vendas_consultor',
			'1',
			'$extrato_valor',
			'$extrato_saldo',
			'$extrato_anexo',
			'$extrato_data',
			'$user_id',
			'Saldo de BSCoins creditado via processamento de fechamento mensal.');";

			if (mysql_query($sql,$con)){
				$extrato_id = mysql_insert_id();
				echo "Lançamento adicionado ao extrato com sucesso! </br>";
			} else {
				die('Error: ' . mysql_error());
			}

			$query = mysql_query("UPDATE jos_users SET pontos='$extrato_saldo' WHERE id='$vendas_consultor'") or die(mysql_error());
			echo "Saldo Atualizado com Sucesso";
			
		}
		?>
		<hr>
	<?php endif; ?>
  </div>
</form>
<?php mysql_close($con); ?>