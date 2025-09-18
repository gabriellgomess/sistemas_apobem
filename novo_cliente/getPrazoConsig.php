<?php 
header("Content-type: application/json; charset=UTF-8");

if($_GET["empresa"] == "rr") require("../../connect_seguro.php"); 
elseif($_GET["empresa"] == "i1") require("../../connect_crm_i1.php"); 
else require("../../connect.php");

include("../../utf8.php");

$vendas_orgao = $_GET['vendas_orgao'];
$vendas_banco = $_GET['vendas_banco'];
$vendas_tipo_contrato = $_GET["vendas_tipo_contrato"];
$hoje = date("Y-m-d");

if ($_GET["user_unidade"]){
	$user_unidade = $_GET["user_unidade"];
	$filtro_unidade = "AND (tabela_unidades like '%," . $user_unidade . ",%' OR tabela_unidades like '%,T,%') ";
}

if((!isset($vendas_banco) || is_null($vendas_banco) || $vendas_banco == "") || 
(!isset($vendas_tipo_contrato) || is_null($vendas_tipo_contrato) || $vendas_tipo_contrato == "") || 
(!isset($vendas_orgao) || is_null($vendas_orgao) || $vendas_orgao == "")) 
   exit;

//pega id do banco
$sql_banco = "SELECT * FROM `sys_vendas_bancos` WHERE vendas_bancos_nome = '$vendas_banco'";
$result_banco = mysqli_query($con, $sql_banco) or die(mysqli_error($con));
$row_banco = mysqli_fetch_assoc($result_banco);
$vendas_banco = $row_banco["vendas_bancos_id"];
// echo $vendas_banco;

$parcelas = [];
if (($_GET["vendas_tipo_contrato"] == "6")||($_GET["vendas_tipo_contrato"] == "7")||($_GET["vendas_tipo_contrato"] == "10")){
   array_push($parcelas, ["tabela_prazo" => "1"]);
}
else{
   // $num_tabelas = 0;
   $sql_prazo = "SELECT DISTINCT tabela_prazo FROM sys_vendas_tabelas WHERE 
                  tabela_banco = '$vendas_banco' 
                  AND tabela_operacao LIKE '%,$vendas_tipo_contrato,%' 
                  AND tabela_orgao like '%$vendas_orgao%' 
                  AND tabela_vigencia_ini <= '$hoje' 
                  AND tabela_vigencia_fim >= '$hoje' 
                  AND tabela_ativa = '1' 
                  AND (tabela_perfil_venda = '1' OR tabela_perfil_venda = '2') 
                  $filtro_unidade
                  AND tabela_permissao = '1';";
   // echo $sql_prazo;
   $result_prazo = mysqli_query($con, $sql_prazo) or die(mysqli_error($con));
   while($row_prazo = mysqli_fetch_array( $result_prazo )){
      array_push($parcelas, ["tabela_prazo" => $row_prazo['tabela_prazo']]);
      // $num_tabelas++;
   }
}

// print_r($parcelas);

echo json_encode($parcelas);
?>