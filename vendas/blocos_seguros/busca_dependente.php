<?php 
header('Content-Type: application/json; charset=utf-8');

require("/var/www/html/sistema/sistema/connect_seguro.php");

$dependente_id = $_GET["dependente_id"];

$sql = "SELECT * FROM sys_vendas_dependentes WHERE dependente_id = $dependente_id";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
// print_r($row);

if(mysql_num_rows($result) > 0){
   echo json_encode([
      "status" => "success", 
      "msg" => "Dependente encontrado!",
      "data" => $row
   ]);

   return;
}

echo json_encode(["status" => "error", "msg" => "Dependente não encontrado!"]);
?>