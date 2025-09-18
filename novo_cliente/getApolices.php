<?php
header("Content-type: application/json; charset=UTF-8");

include("../../connect_seguro.php");
include("../../utf8.php");

$apolices = [];
$vendas_banco = $_GET["vendas_banco"];

if(!isset($vendas_banco) || is_null($vendas_banco) || $vendas_banco == "") 
   exit;

if ($_GET['retencao'] == 1) $select_apolice = " AND (apolice_tipo = '1' OR apolice_tipo = '3')"; 
else $select_apolice = " AND apolice_tipo = '1'";

$equipe_apolices = $_GET["equipe_apolices"] ;

if ($equipe_apolices != "undefined" && $equipe_apolices != "") $filtro_apolices = " AND apolice_id IN (" . trim($equipe_apolices, ',') . ")";

$sql_apolice = "SELECT *
                  FROM sys_vendas_apolices
                  WHERE apolice_banco = '$vendas_banco' 
                  AND apolice_ativa = 1 
                  $select_apolice $filtro_apolices 
                  ORDER BY apolice_valor;";
// echo $sql_apolice;
$result_apolice = mysqli_query($con, $sql_apolice) or die(mysqli_error($con)); 

while($row_apolices = mysqli_fetch_assoc( $result_apolice )){
   array_push($apolices, $row_apolices);
}

echo json_encode($apolices);
?>