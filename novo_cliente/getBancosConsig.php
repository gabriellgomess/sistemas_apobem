<?php 
header("Content-type: application/json; charset=UTF-8");

if($_GET["empresa"] == "rr") require("../../connect_seguro.php"); 
elseif($_GET["empresa"] == "i1") require("../../connect_crm_i1.php"); 
else require("../../connect.php");

include("../../utf8.php");

$banks = [];
$vendas_orgao = $_GET['vendas_orgao'];

if(!isset($vendas_orgao) || is_null($vendas_orgao) || $vendas_orgao == "") 
   exit;

$sql_banks = "SELECT DISTINCT sys_vendas_bancos.vendas_bancos_id, sys_vendas_bancos.vendas_bancos_nome 
               FROM sys_vendas_bancos
               JOIN sys_vendas_tabelas ON sys_vendas_bancos.vendas_bancos_id = sys_vendas_tabelas.tabela_banco
               WHERE sys_vendas_tabelas.tabela_orgao = '$vendas_orgao' 
                  AND sys_vendas_tabelas.tabela_vigencia_fim >= DATE(NOW()) 
                  AND sys_vendas_tabelas.tabela_ativa = 1
               ORDER BY sys_vendas_bancos.vendas_bancos_nome;";
// echo $sql_banks;
$result_banks = mysqli_query($con, $sql_banks) or die(mysqli_error($con));
while($row_banks = mysqli_fetch_array( $result_banks )){
   array_push($banks, ["banco_id" => $row_banks["vendas_bancos_id"], "banco_nome" => $row_banks['vendas_bancos_nome']]);
}

echo json_encode($banks);
?>