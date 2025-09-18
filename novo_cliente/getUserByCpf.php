<?php

include("../../connect_db02.php");

$cpf = $_POST['cpf'];

if(isset($cpf) && $cpf !== null && $cpf !== ""){
   $sql = "SELECT * FROM `sys_inss_clientes` WHERE `cliente_cpf` LIKE '$cpf'";
   $result = mysqli_query($con, $sql) or die(mysqli_error($con));
   $data = mysqli_fetch_assoc($result);

   echo json_encode($data);
}
else{
   echo json_encode(null);
}