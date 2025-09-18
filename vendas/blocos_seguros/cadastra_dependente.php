<?php 
header('Content-Type: application/json; charset=utf-8');

// $connect = $_SERVER['DOCUMENT_ROOT']."/sistema/sistema/connect_seguro.php";
// echo $connect;
require("/var/www/html/sistema/sistema/connect_seguro.php");
require("/var/www/html/sistema/sistema/utf8.php");

$vendas_id = $_POST["vendas_id"];
$dependente_nome = $_POST["dependente_nome"];
$dependente_cpf = str_replace(".", "", str_replace("-", "", $_POST["dependente_cpf"]));;
$dependente_nascimento = $_POST["dependente_nascimento"];
$dependente_celular = str_replace("(", "", str_replace(")", "", str_replace("-", "", str_replace(" ", "", $_POST["dependente_celular"]))));;
$dependente_sexo = $_POST["dependente_sexo"];
$dependente_email = $_POST["dependente_email"];
$dependente_parentesco = $_POST["dependente_parentesco"];

$sql = "INSERT INTO sys_vendas_dependentes
         (dependente_id,
         vendas_id,
         dependente_nome,
         dependente_cpf,
         dependente_nascimento,
         dependente_celular,
         dependente_sexo,
         dependente_email,
         dependente_parentesco)
         VALUES
         (NULL,
         '$vendas_id',
         '$dependente_nome',
         '$dependente_cpf',
         '$dependente_nascimento',
         '$dependente_celular',
         '$dependente_sexo',
         '$dependente_email',
         '$dependente_parentesco')";
// echo $sql;

if(mysql_query($sql)){
   $id = mysql_insert_id();

   echo json_encode([
      "status" => "success", 
      "msg" => "Dependente cadastrado com sucesso!",
      "id" => $id
   ]);

   return;
}

echo json_encode(["status" => "error", "msg" => "Erro ao cadastrar dependente!"]);
?>