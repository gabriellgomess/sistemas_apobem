<?php
date_default_timezone_set('America/Sao_Paulo');
include("../../connect.php");

$cpf = $_GET["cpf"];
$clients_cat = $_GET["clients_cat"];
if ($_GET["p"]) {
	$pagina = $_GET["p"];
} else {
	$pagina = "1";
}
//$vendas_status=$_GET["vendas_status"];
//if ($_GET["vendas_status"]) {$select_status= " AND vendas_status = '" . $vendas_status . "'";} else {$select_status="";}

if ($_GET['apolice_tipo']) {
	$select_apolice_tipo = " AND apolice_tipo = '" . $_GET['apolice_tipo'] . "'";
}

if ($_GET["vendas_status"]) {
	$vendas_status = $_GET["vendas_status"];
	for ($i = 0; $i < count($vendas_status); $i++) {
		if ($vendas_status[$i] != "") {
			if ($i == 0) {
				$select_status = " AND (vendas_status = '" . $vendas_status[$i] . "'";
			} else {
				$select_status = $select_status . " OR vendas_status = '" . $vendas_status[$i] . "'";
			}
		}
		$aux_stat = $i;
	}
	if ($vendas_status[$aux_stat] != "") {
		$select_status = $select_status . ")";
	}
	for ($i = 0; $i < count($vendas_status); $i++) {
		if ($vendas_status[$i] != "") {
			$pag_status = $pag_status . "&vendas_status[]=" . $vendas_status[$i];
		}
	}
}

if ($_GET["vendas_debito_banco"]) {
	$vendas_debito_banco = $_GET["vendas_debito_banco"];
	for ($i = 0; $i < count($vendas_debito_banco); $i++) {
		if ($vendas_debito_banco[$i] != "") {
			if ($i == 0) {
				$select_debito_banco = " AND (vendas_debito_banco = '" . $vendas_debito_banco[$i] . "'";
			} else {
				$select_debito_banco = $select_debito_banco . " OR vendas_debito_banco = '" . $vendas_debito_banco[$i] . "'";
			}
		}
		$aux_banco = $i;
	}
	if ($vendas_debito_banco[$aux_banco] != "") {
		$select_debito_banco = $select_debito_banco . ")";
	}
	for ($i = 0; $i < count($vendas_debito_banco); $i++) {
		if ($vendas_debito_banco[$i] != "") {
			$pag_debito_banco = $pag_debito_banco . "&vendas_debito_banco[]=" . $vendas_debito_banco[$i];
		}
	}
}


if ($_GET["vendas_apolice"]) {
	$vendas_apolice = $_GET["vendas_apolice"];
	for ($i = 0; $i < count($vendas_apolice); $i++) {
		if ($vendas_apolice[$i] != "") {
			if ($i == 0) {
				$select_apolice = " AND (vendas_apolice = '" . $vendas_apolice[$i] . "'";
			} else {
				$select_apolice = $select_apolice . " OR vendas_apolice = '" . $vendas_apolice[$i] . "'";
			}
		}
		$aux_apolice = $i;
	}
	if ($vendas_apolice[$aux_apolice] != "") {
		$select_apolice = $select_apolice . ")";
	}
	for ($i = 0; $i < count($vendas_apolice); $i++) {
		if ($vendas_apolice[$i] != "") {
			$pag_apolice = $pag_apolice . "&vendas_apolice[]=" . $vendas_apolice[$i];
		}
	}
}

if ($_GET["data_intencionamento"]) {
	$vendas_dia_intencionamento = dataBR_to_dataDB($_GET["data_intencionamento"]);
	$filtros_sql = $filtros_sql . " AND vendas_dia_intencionamento like '%" . $vendas_dia_intencionamento . "%'";
}

if ($_GET["filtro_data1"]) {
	$filtro_data1 = $_GET["filtro_data1"];
} else {
	$filtro_data1 = "1";
}
if ($filtro_data1 == "1") {
	$normal_1_2 = "vendas_dia_venda";
	$normal_1_2_hr_ini = " 00:00:00'";
	$normal_1_2_hr_fim = " 23:59:59'";
}
if ($filtro_data1 == "2") {
	$normal_1_2 = "vendas_dia_ativacao";
	$normal_1_2_hr_ini = "'";
	$normal_1_2_hr_fim = "'";
}
if ($filtro_data1 == "3") {
	if ($_GET["dp-normal-1"]) {
		$pag_data_ini = $_GET["dp-normal-1"];
		$data_ini_mes = substr($pag_data_ini, 3, 2);
		$data_ini_ano = substr($pag_data_ini, 6, 4);
		$filtros_sql = $filtros_sql . " AND vendas_cartao_validade_mes >= '" . $data_ini_mes . "'";
		$filtros_sql = $filtros_sql . " AND vendas_cartao_validade_ano >= '" . $data_ini_ano . "'";
	}

	if ($_GET["dp-normal-2"]) {
		$pag_data_fim = $_GET["dp-normal-2"];
		$data_fim_mes = substr($pag_data_fim, 3, 2);
		$data_fim_ano = substr($pag_data_fim, 6, 4);
		$filtros_sql = $filtros_sql . " AND vendas_cartao_validade_mes <= '" . $data_fim_mes . "'";
		$filtros_sql = $filtros_sql . " AND vendas_cartao_validade_ano <= '" . $data_fim_ano . "'";
	}
}
if ($filtro_data1 == "4") {
	if ($_GET["dp-normal-1"]) {
		$pag_data_ini = $_GET["dp-normal-1"];
		$data_inicial_intencionamento = dataBR_to_dataDB($_GET["dp-normal-1"]);
	} else {
		//se não houver data inicial é igual a data de hoje
		$data_inicial_intencionamento = date("Y-m-d");
	}
	if ($_GET["dp-normal-2"]) {
		$pag_data_fim = $_GET["dp-normal-2"];
		$data_final_intencionamento = dataBR_to_dataDB($_GET["dp-normal-2"]);
	} else {
		//se não houver data final é igual a inicial + um dia
		$data_final_intencionamento = date('Y-m-d', strtotime("+1 day", strtotime($data_inicial_intencionamento)));
	}

	$filtros_sql = $filtros_sql . " AND vendas_dia_intencionamento >= '" . $data_inicial_intencionamento . "' AND vendas_dia_intencionamento <= '" . $data_final_intencionamento . "'";
} else {
	if ($_GET["dp-normal-1"]) {
		$pag_data_ini = $_GET["dp-normal-1"];
		$data_ini = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));
		$filtros_sql = $filtros_sql . " AND " . $normal_1_2 . " >= '" . $data_ini . $normal_1_2_hr_ini;
	}

	if ($_GET["dp-normal-2"]) {
		$pag_data_fim = $_GET["dp-normal-2"];
		$data_fim = implode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "-" : "/", $_GET["dp-normal-2"])));
		$filtros_sql = $filtros_sql . " AND " . $normal_1_2 . " <= '" . $data_fim . $normal_1_2_hr_fim;
	}
}

$vendas_banco = $_GET["vendas_banco"];
if ($_GET["vendas_banco"]) {
	$select_bank = " AND vendas_banco like '%" . $vendas_banco . "%'";
} else {
	$select_bank = "";
}

$vendas_proposta = $_GET["vendas_proposta"];
if ($_GET["vendas_proposta"]) {
	$select_proposta = " AND vendas_proposta like '%" . $vendas_proposta . "%'";
} else {
	$select_proposta = "";
}

$vendas_id = $_GET["vendas_id"];
if ($_GET["vendas_id"]) {
	$select_id = " AND vendas_id = '" . $vendas_id . "'";
} else {
	$select_id = "";
}

$cliente_matricula = $_GET["cliente_matricula"];
if ($_GET["cliente_matricula"]) {
	$select_matricula = " AND (clients_prec_cp like '%" . $cliente_matricula . "%' OR cliente_beneficio like '%" . $cliente_matricula . "%')";
} else {
	$select_matricula = "";
}

$cliente_empregador = $_GET["cliente_empregador"];
if ($_GET["cliente_empregador"]) {
	$select_empregador = " AND cliente_empregador = '" . $cliente_empregador . "'";
} else {
	$select_empregador = "";
}

$vendas_turno = $_GET["vendas_turno"];
if ($_GET["vendas_turno"]) {
	$select_turno = " AND vendas_turno = '" . $vendas_turno . "'";
} else {
	$select_turno = "";
}

$vendas_status_motivo = "";
$select_status_motivo = "";
if ($_GET["vendas_status_motivo"]) {
	$vendas_status_motivo = mysql_real_escape_string(utf8_decode($_GET["vendas_status_motivo"]));
	$select_status_motivo = " AND vendas_status_motivo LIKE '" . $vendas_status_motivo . "'";
}

if ($_GET["ordemi"]) {
	$ordem = $_GET["ordemi"];
} else {
	$ordem = "vendas_id";
}
if ($_GET["ordenacao"]) {
	$ordenacao = $_GET["ordenacao"];
} else {
	$ordenacao = "DESC";
}
if ($_GET["ordenacao"] == "ASC") {
	$link_ordem = "DESC";
} else {
	$link_ordem = "ASC";
}

if ($_GET["contar"]) {
	$contagem = ", COUNT(sys_vendas_seguros.cliente_cpf) AS contagem";
	$agrupamento = " GROUP BY sys_vendas_seguros.cliente_cpf ";
	if ($_GET["ordemi"]) {
		$ordem = $_GET["ordemi"];
	} else {
		$ordem = "contagem";
	}
	if ($_GET["num_vendas"]) {
		$select_num_vendas = " HAVING contagem > '" . $_GET['num_vendas'] . "'";
	}
} else {
	$agrupamento = "";
}

include("sistema/utf8.php");
if ($_GET["vendas_consultor"]) {
	$vendas_consultor = $_GET["vendas_consultor"];
	for ($i = 0; $i < count($vendas_consultor); $i++) {
		if ($vendas_consultor[$i] != "") {
			if ($i == 0) {
				$select_consultor = " AND (vendas_consultor = '" . $vendas_consultor[$i] . "'";
			} else {
				$select_consultor = $select_consultor . " OR vendas_consultor = '" . $vendas_consultor[$i] . "'";
			}
		}
		$aux_consultor = $i;
	}
	if ($vendas_consultor[$aux_consultor] != "") {
		$select_consultor = $select_consultor . ")";
	}
	for ($i = 0; $i < count($vendas_consultor); $i++) {
		if ($vendas_consultor[$i] != "") {
			$pag_consultor = $pag_consultor . "&vendas_consultor[]=" . $vendas_consultor[$i];
		}
	}
} else {
	$select_consultor = "";
}

$consultor_unidade = $_GET["consultor_unidade"];

$join_unidade = " INNER JOIN jos_users ON sys_vendas_seguros.vendas_consultor = jos_users.id";
if ($_GET["consultor_unidade"]) {
	$consultor_unidade = $_GET["consultor_unidade"];
	for ($i = 0; $i < count($consultor_unidade); $i++) {
		if ($consultor_unidade[$i] != "") {
			if ($i == 0) {
				$select_unidade = " AND (jos_users.unidade = '" . $consultor_unidade[$i] . "'";
			} else {
				$select_unidade = $select_unidade . " OR jos_users.unidade = '" . $consultor_unidade[$i] . "'";
			}
		}
		$aux_stat = $i;
	}
	if ($consultor_unidade[$aux_stat] != "") {
		$select_unidade = $select_unidade . ")";
	}
	for ($i = 0; $i < count($consultor_unidade); $i++) {
		if ($consultor_unidade[$i] != "") {
			$pag_unidade = $pag_unidade . "&consultor_unidade[]=" . $consultor_unidade[$i];
		}
	}
} else {
	$select_unidade = "";
}

$p = $_GET["p"];
if (isset($p)) {
	$p = $p;
} else {
	$p = 1;
}
$qnt = 20;
$inicio = ($p * $qnt) - $qnt;
$filtros_sql = $filtros_sql .
	$select_id .
	$select_bank .
	$select_proposta .
	$select_status .
	$select_unidade .
	$select_turno .
	$select_empregador .
	$select_matricula;

$result = mysql_query("SELECT * FROM sys_vendas_seguros WHERE sys_vendas_seguros.cliente_cpf like '%" . $cpf . "%'" .
	$filtros_sql .
	$agrupamento . $select_num_vendas . " ORDER BY " . $ordem . " " . $ordenacao . " LIMIT 0, 200000;") or die(mysql_error());

$agora = date("Ymd_His");
$nome_arquivo = "RelatorioPortal_Blacklist_" . $agora;

// Determina que o arquivo é uma planilha do Excel
header("Content-type: application/vnd.ms-excel");

// Força o download do arquivo
header("Content-type: application/force-download");

// Seta o nome do arquivo
header("Content-Disposition: attachment; filename=" . $nome_arquivo . ".xls");

header("Pragma: no-cache");
// Imprime o conteúdo da nossa tabela no arquivo que será gerado

?>

<?php $curURL = $_SERVER["REQUEST_URI"]; ?>
<div align="left">

	<table border="2" align="center" cellpadding="0" cellspacing="1">
		<tbody>
			<tr>
				<td colspan="9"><span style="color:#ff0000;"><strong>MAXIMO DE 5000 RESULTADOS!!!</strong></span></td>
			</tr>
			<tr>
				<div align="left">
					<td>CPF DO CLIENTE:</td>
					<td>DATA DA VENDA:</td>
					<td>CODIGO:</td>
					<td>No DA PROPOSTA:</td>
				</div>
			</tr>
			<?php
			$totalclientes = 0;
			$exibindo = 1;
			$numero = $exibindo;

			while ($row = mysql_fetch_array($result)) {
				$endereco_link = "#";

				$vendas_valor = ($row['vendas_valor'] > 0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0';

				$result_user = mysql_query("SELECT name FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
					or die(mysql_error());
				$row_user = mysql_fetch_array($result_user);

				$yr = strval(substr($row["vendas_dia_venda"], 0, 4));
				$mo = strval(substr($row["vendas_dia_venda"], 5, 2));
				$da = strval(substr($row["vendas_dia_venda"], 8, 2));
				$hr = strval(substr($row["vendas_dia_venda"], 11, 2));
				$mi = strval(substr($row["vendas_dia_venda"], 14, 2));
				$data_venda = date("d/m/Y H:i:s", mktime($hr, $mi, 0, $mo, $da, $yr));

				$data_ativacao = dataDB_to_dataBR($row["vendas_dia_ativacao"]);

				$result_status = mysql_query("SELECT status_nm FROM sys_vendas_status_seg WHERE status_id = " . $row['vendas_status'] . ";")
					or die(mysql_error());
				$row_status = mysql_fetch_array($result_status);

				echo "<tr>";
				echo "<td>" . $row['cliente_cpf'] . "</td>";
				echo "<td>" . $data_venda . "</td>";
				echo "<td><div align='right'><strong>{$row['vendas_id']}</strong></div></td>";
				echo "<td>" . $row['vendas_proposta'] . "</td>";
				echo "</tr>";

				$exibindo = $exibindo + 1;
				$numero = $numero + 1;
			}

			$exibindo = $exibindo  - 1;

			?>
		</tbody>
	</table>
	</tbody>
	</table>
	</table>
	<div align="center">Total de <?php echo $exibindo; ?> vendas selecionadas.</div>
</div>
</form>
<?php mysql_close($con); ?>

<?php
// FUNÇÃO AJUSTA DATA DO FORMULÁRIO PARA O FORMATO DO BANCO DE DADOS
function dataBR_to_dataDB($dataBr)
{

	return implode("-", array_reverse(explode("-", str_replace("/", "-", $dataBr))));
}

// FUNÇÃO AJUSTA DATA DO BANCO DE DADOS PARA O FORMATO DO FORMULÁRIO
function dataDB_to_dataBR($dataDb)
{

	return implode("/", array_reverse(explode("/", str_replace("-", "/", $dataDb))));
}
?>