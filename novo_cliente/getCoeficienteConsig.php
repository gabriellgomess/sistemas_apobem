<?php 
header("Content-type: application/json; charset=UTF-8");

if($_GET["empresa"] == "rr") require("../../connect_seguro.php"); 
elseif($_GET["empresa"] == "i1") require("../../connect_crm_i1.php"); 
else require("../../connect.php");

include("../../utf8.php");

$vendas_orgao = $_GET['vendas_orgao'];
$vendas_banco = $_GET['vendas_banco'];
$vendas_tipo_contrato = $_GET["vendas_tipo_contrato"];
$vendas_percelas = $_GET["vendas_percelas"];
$vendas_tabela = $_GET["vendas_tabela"];
$vendas_valor_parcela = $_GET["vendas_valor_parcela"];
$hoje = date("Y-m-d");
$dia = date("d");
$tabela_dia = "tabela_dia_".$dia;

if ($_GET["user_unidade"]){
	$user_unidade = $_GET["user_unidade"];
	$filtro_unidade = "AND (tabela_unidades like '%," . $user_unidade . ",%' OR tabela_unidades like '%,T,%') ";
}

if((!isset($vendas_orgao) || is_null($vendas_orgao) || $vendas_orgao == "") || 
(!isset($vendas_tipo_contrato) || is_null($vendas_tipo_contrato) || $vendas_tipo_contrato == "") || 
(!isset($vendas_percelas) || is_null($vendas_percelas) || $vendas_percelas == "") || 
(!isset($vendas_banco) || is_null($vendas_banco) || $vendas_banco == "") ||
(!isset($vendas_tabela) || is_null($vendas_tabela) || $vendas_tabela == "") ||
(!isset($vendas_valor_parcela) || is_null($vendas_valor_parcela) || $vendas_valor_parcela == "")) 
   exit;

//pega id do banco
$sql_banco = "SELECT * FROM `sys_vendas_bancos` WHERE vendas_bancos_nome = '$vendas_banco'";
$result_banco = mysqli_query($con, $sql_banco) or die(mysqli_error($con));
$row_banco = mysqli_fetch_assoc($result_banco);
$vendas_banco = $row_banco["vendas_bancos_id"];

$sql_tabela = "SELECT tabela_tipo_coeficiente, $tabela_dia 
               FROM sys_vendas_tabelas 
               WHERE tabela_banco = '$vendas_banco' 
               AND tabela_operacao LIKE '%,$vendas_tipo_contrato,%' 
               AND tabela_prazo = '$vendas_percelas' 
               AND tabela_orgao LIKE '%$vendas_orgao%' 
               AND tabela_vigencia_ini <= '$hoje' 
               AND tabela_vigencia_fim >= '$hoje' 
               AND tabela_ativa = '1' 
               AND (tabela_perfil_venda = '1' OR tabela_perfil_venda = '2')
               $filtro_unidade
               AND tabela_permissao = '1'
               AND tabela_id = $vendas_tabela;";
// echo $sql_tabela;
$result_tabela = mysqli_query($con, $sql_tabela) or die(mysqli_error($con));
$row_tabela = mysqli_fetch_array( $result_tabela );
$coeficiente = $row_tabela[$tabela_dia];
$tabela_tipo_coeficiente = $row_tabela['tabela_tipo_coeficiente'];

// echo "coeficiente: ".$coeficiente;
// echo "tabela_tipo_coeficiente: ".$tabela_tipo_coeficiente;

$response = [];
if((isset($coeficiente) && $coeficiente != "") && ($vendas_tipo_contrato != "6") && ($vendas_tipo_contrato != "7") && ($vendas_tipo_contrato != "10") && ($tabela_tipo_coeficiente != "3")){
   $vendas_valor_parcela = str_replace(".", "", $vendas_valor_parcela);
   $vendas_valor_parcela = str_replace(",", ".", $vendas_valor_parcela);

   $vendas_valor = $vendas_valor_parcela / $coeficiente;
   $vendas_valor = ($vendas_valor > 0) ? number_format($vendas_valor, 2, ',', '.') : '0';

   $response["vendas_valor"] = $vendas_valor;
   $response["vendas_coeficiente"] = $coeficiente;

   echo json_encode($response);
   return;
}

// print_r($response);

echo json_encode(["vendas_valor" => null, "vendas_coeficiente" => null])
?>