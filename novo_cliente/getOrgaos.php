<?php 
header("Content-type: application/json; charset=UTF-8");

include("../../connect_seguro.php");
include("../../utf8.php");

$orgaos = [];
$vendas_apolice = $_GET["vendas_apolice"];

if(!isset($vendas_apolice) || is_null($vendas_apolice) || $vendas_apolice == "") 
   exit;

$sql_apolice = "SELECT apolice_orgaos FROM sys_vendas_apolices WHERE apolice_id = $vendas_apolice";
$result_apolice = mysqli_query($con, $sql_apolice) or die(mysqli_error($con));
$row_apolice = mysqli_fetch_assoc($result_apolice);
$orgaos_permitidos = $row_apolice["apolice_orgaos"];

//remove virgulas extras caso query retorne algum valor
if(isset($orgaos_permitidos) && !is_null($orgaos_permitidos) && $orgaos_permitidos != "")
   $orgaos_permitidos = "WHERE orgao_id IN (" . implode(",", array_filter(explode(",", $orgaos_permitidos))) . ")";

else //se query retornar null, remove qualquer informação da var
   $orgaos_permitidos = "";

$sql_orgaos = "SELECT * FROM sys_orgaos $orgaos_permitidos";
$result_orgaos = mysqli_query($con, $sql_orgaos) or die(mysqli_error($con));

while($row_orgaos = mysqli_fetch_assoc( $result_orgaos )){
   array_push($orgaos, $row_orgaos);
}

echo json_encode($orgaos);
?>