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
(!isset($vendas_banco) || is_null($vendas_banco) || $vendas_banco == "")) 
   exit;

//pega id do banco
$sql_banco = "SELECT * FROM `sys_vendas_bancos` WHERE vendas_bancos_nome = '$vendas_banco'";
$result_banco = mysqli_query($con, $sql_banco) or die(mysqli_error($con));
$row_banco = mysqli_fetch_assoc($result_banco);
$vendas_banco = $row_banco["vendas_bancos_id"];


$tabelas = [];
$sql_tabela = "SELECT tabela_id, tabela_nome, tabela_prazo, tabela_tipo, tabela_tipo_coeficiente, $tabela_dia 
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
               AND tabela_permissao = '1';";
// echo $sql_tabela;
$result_tabela = mysqli_query($con, $sql_tabela) or die(mysqli_error($con));
while($row_tabela = mysqli_fetch_array( $result_tabela )){
   array_push($tabelas, ["tabela_id" => $row_tabela['tabela_id'], "tabela_nome" => $row_tabela['tabela_nome'], "tabela_tipo" => $row_tabela['tabela_tipo'], "tabela_prazo" => $row_tabela['tabela_prazo']]);
}

// print_r($tabelas);

echo json_encode($tabelas);
?>