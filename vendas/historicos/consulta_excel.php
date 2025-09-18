<?php 
require("../../connect.php");
require("../../utf8.php");

if ($_GET["dp-normal-1"]){
$pag_data_ini = $_GET["dp-normal-1"];
$data_ini = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));
$filtros_sql = $filtros_sql." AND registro_data >= '" . $data_ini . " 00:00:00'";
}

if ($_GET["dp-normal-2"]){
$pag_data_fim = $_GET["dp-normal-2"];
$data_fim = implode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "-" : "/", $_GET["dp-normal-2"])));
$filtros_sql = $filtros_sql." AND registro_data <= '" . $data_fim . " 23:59:59'";
}

if ($_GET["dp-normal-5"]){
$pag_venda_data_ini = $_GET["dp-normal-5"];
$venda_data_ini = implode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "-" : "/", $_GET["dp-normal-5"])));
$filtros_sql = $filtros_sql." AND vendas_dia_venda >= '" . $venda_data_ini . " 00:00:00'";
}

if ($_GET["dp-normal-6"]){
$pag_venda_data_fim = $_GET["dp-normal-6"];
$venda_data_fim = implode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "-" : "/", $_GET["dp-normal-6"])));
$filtros_sql = $filtros_sql." AND vendas_dia_venda <= '" . $venda_data_fim . " 23:59:59'";
}

if ($_GET["vendas_id"]) {$filtros_sql = $filtros_sql." AND sys_vendas_registros_seg.vendas_id = '" . $_GET['vendas_id'] . "'";}

if ($_GET["transacao_proposta"]) {$filtros_sql = $filtros_sql." AND vendas_proposta = '" . $_GET['transacao_proposta'] . "'";}

if ($_GET["registro_usuario"]) {$filtros_sql= $filtros_sql." AND registro_usuario like '%" . $_GET['registro_usuario'] . "%'";}

if ($_GET["vendas_banco"]) {$filtro_seguradora= $filtro_seguradora." AND vendas_banco = '" . $_GET['vendas_banco'] . "'";}


if ($_GET["registro_status"]){
$registro_status=$_GET["registro_status"];
				for ($i=0;$i<count($registro_status);$i++){
					if ($registro_status[$i] != ""){
						if ($i==0){
							$select_status = " AND (registro_status = '" . $registro_status[$i] . "'";
						}else{$select_status = $select_status." OR registro_status = '" . $registro_status[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($registro_status[$aux_stat] != ""){$select_status = $select_status.")";}
				for ($i=0;$i<count($registro_status);$i++){
					if ($registro_status[$i] != ""){
							$pag_status = $pag_status."&registro_status[]=".$registro_status[$i];					
					}
				}
	$filtros_sql= $filtros_sql.$select_status;
}

if($_GET["registro_cobranca"]){$filtros_sql = $filtros_sql." AND registro_cobranca = '" . $_GET['registro_cobranca'] . "'";}
if($_GET["registro_retencao"]){$filtros_sql = $filtros_sql." AND registro_retencao = '" . $_GET['registro_retencao'] . "'";}

$pesquisar = true;

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="registro_id";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="DESC";}

// Pegar a página atual por GET
$p = $_GET["p"];
// Verifica se a variável tá declarada, senão deixa na primeira página como padrão
if(isset($p)) {
$p = $p;
} else {
$p = 1;
}
// Defina aqui a quantidade máxima de registros por página.
$qnt = 20;
$inicio = ($p*$qnt) - $qnt;

$sql = "SELECT registro_id,
				sys_vendas_registros_seg.vendas_id,
				vendas_banco,
				registro_usuario,
				registro_obs,
				status_nm,
				status_img,
				vendas_valor,
				DATE_FORMAT(registro_data,'%d/%m/%Y %H:%i:%s') AS registro_data 
				FROM sys_vendas_registros_seg
				LEFT JOIN sys_vendas_seguros ON (sys_vendas_registros_seg.vendas_id = sys_vendas_seguros.vendas_id) 
				INNER JOIN sys_vendas_status_seg ON registro_status = status_id 
				WHERE 1=1 ". $filtros_sql . $filtro_seguradora .  
				" ORDER BY " . $ordem . " " . $ordenacao . " LIMIT 0, 5000;";

$result = mysql_query($sql) or die(mysql_error());

$result_bancos = mysql_query("SELECT * FROM sys_vendas_banco_seg ORDER BY banco_nm;") or die(mysql_error());

while($row_bancos = mysql_fetch_array( $result_bancos ))
{	
	$seguradoras_array[ $row_bancos["banco_id"] ] = $row_bancos['banco_nm'];
}


date_default_timezone_set('America/Sao_Paulo');
$datetime = date("Y-m-d H:i:s");
$file = "relatorio_historico_vendas_".$datetime.".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");
?>

<table>
	<tr>
		<td>
			<?php echo utf8_decode("Consultor:"); ?>
		</td>
		<td>
			<?php echo utf8_decode("Data:"); ?>
		</td>
		<td>
			<?php echo utf8_decode("Observações:"); ?>
		</td>
		<td>
			<?php echo utf8_decode("Seguradora:"); ?>
		</td>
		<td>
			<?php echo utf8_decode("Cód Venda:"); ?>
		</td>
		<td>
			<?php echo utf8_decode("Valor:"); ?>
		</td>
		<td>
			<?php echo utf8_decode("Status:"); ?>
		</td>
		<td>
			<?php echo utf8_decode("Cód Registro:"); ?>
		</td>
	</tr>

<?php while($row = mysql_fetch_array( $result )) : ?>
		<tr>
			<td>				
				<?php echo addslashes(utf8_decode($row['registro_usuario'])); ?>
			</td>
			<td>
				<?php echo addslashes(utf8_decode($row['registro_data'])); ?>
			</td>
			<td>
				<?php echo htmlspecialchars(addslashes(utf8_decode($row['registro_obs']))); ?>
			</td>
			<td>
				<?php echo addslashes(utf8_decode($seguradoras_array[ $row['vendas_banco'] ])); ?>
			</td>
			<td>
				<?php echo addslashes(utf8_decode($row['vendas_id'])); ?>
			</td>
			<td>
				R$ <?php echo number_format($row['vendas_valor'], 2, ',', '.'); ?>
			</td>
			<td>
				<?php echo addslashes(utf8_decode($row['status_nm'])); ?>
			</td>
			<td>
				<?php echo addslashes(utf8_decode($row['registro_id'])); ?>
			</td>
		</tr>
	<?php endwhile; ?>
</table>

