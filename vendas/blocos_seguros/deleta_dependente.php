<?php 
header('Content-Type: application/json; charset=utf-8');

require("/var/www/html/sistema/sistema/connect_seguro.php");

$dependente_id = $_POST["dependente_id"];

$sql = "DELETE FROM sys_vendas_dependentes WHERE dependente_id = $dependente_id";

if(mysql_query($sql)){
   echo json_encode([
      "status" => "success", 
      "msg" => "Dependente excluído com sucesso!"
   ]);

   return;
}

echo json_encode(["status" => "error", "msg" => "Erro ao excluir dependente!"]);
?>