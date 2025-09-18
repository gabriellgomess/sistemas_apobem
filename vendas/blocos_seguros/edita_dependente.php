<?php 
header('Content-Type: application/json; charset=utf-8');

// $connect = $_SERVER['DOCUMENT_ROOT']."/sistema/sistema/connect_seguro.php";
// echo $connect;
require("/var/www/html/sistema/sistema/connect_seguro.php");

$dependente_id = $_POST["dependente_id"];
$vendas_id = $_POST["vendas_id"];
$dependente_nome = $_POST["dependente_nome"];
$dependente_cpf = str_replace(".", "", str_replace("-", "", $_POST["dependente_cpf"]));;
$dependente_nascimento = $_POST["dependente_nascimento"];
$dependente_celular = str_replace("(", "", str_replace(")", "", str_replace("-", "", str_replace(" ", "", $_POST["dependente_celular"]))));;
$dependente_sexo = $_POST["dependente_sexo"];
$dependente_email = $_POST["dependente_email"];
$dependente_parentesco = $_POST["dependente_parentesco"];

$sql = "UPDATE sys_vendas_dependentes
         SET dependente_nome = '$dependente_nome',
         dependente_cpf = '$dependente_cpf',
         dependente_nascimento = '$dependente_nascimento',
         dependente_celular = '$dependente_celular',
         dependente_sexo = '$dependente_sexo',
         dependente_email = '$dependente_email',
         dependente_parentesco = '$dependente_parentesco'
         WHERE dependente_id = $dependente_id";
// echo $sql;

if(mysql_query($sql)){
   $sql = "SELECT * FROM sys_vendas_dependentes WHERE dependente_id = $dependente_id";
   $result = mysql_query($sql);
   $row = mysql_fetch_assoc($result);

   echo json_encode([
      "status" => "success", 
      "msg" => "Dependente editado com sucesso!",
      "data" => $row
   ]);

   return;
}

echo json_encode(["status" => "error", "msg" => "Erro ao editar dependente!"]);
?>