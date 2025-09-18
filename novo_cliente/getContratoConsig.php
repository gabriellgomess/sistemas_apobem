<?php 
header("Content-type: application/json; charset=UTF-8");

if($_GET["empresa"] == "rr") require("../../connect_seguro.php"); 
elseif($_GET["empresa"] == "i1") require("../../connect_crm_i1.php"); 
else require("../../connect.php");

include("../../utf8.php");
// echo $con->host_info;

$hoje = date("Y-m-d");
$vendas_banco = $_GET['vendas_banco'];
$vendas_orgao = $_GET['vendas_orgao'];

if ($_GET["user_unidade"]){
	$user_unidade = $_GET["user_unidade"];
	$filtro_unidade = "AND (tabela_unidades LIKE '%," . $user_unidade . ",%' OR tabela_unidades like '%,T,%') ";
}

if((!isset($vendas_banco) || is_null($vendas_banco) || $vendas_banco == "") ||
(!isset($vendas_orgao) || is_null($vendas_orgao) || $vendas_orgao == "")) 
   exit;

//pega id do banco
$sql_banco = "SELECT * FROM `sys_vendas_bancos` WHERE vendas_bancos_nome = '$vendas_banco'";
$result_banco = mysqli_query($con, $sql_banco) or die(mysqli_error($con));
$row_banco = mysqli_fetch_assoc($result_banco);
$vendas_banco = $row_banco["vendas_bancos_id"];

// echo $vendas_banco;

//OPERAÇÕES
$array_op = [];
$sql_op = "SELECT tabela_operacao 
            FROM sys_vendas_tabelas 
            WHERE tabela_ativa = 1 
            AND tabela_banco = '$vendas_banco'
            AND tabela_orgao LIKE '%$vendas_orgao%' 
            AND tabela_vigencia_ini <= '$hoje' 
            AND tabela_vigencia_fim >= '$hoje' 
            AND (tabela_perfil_venda = '1' OR tabela_perfil_venda = '2') 
            $filtro_unidade
            AND tabela_permissao = '1'
            GROUP BY tabela_operacao";
// echo $sql_op;
$result_op = mysqli_query($con, $sql_op) or die(mysqli_error($con));
while ($row_op = mysqli_fetch_array($result_op)){
   $array_op[] = $row_op['tabela_operacao'];
}
$array_op = implode("", $array_op);
$array_op = preg_replace('/,+/', ',', trim($array_op, ','));
$array_op = explode(",", $array_op);
$array_op = array_unique($array_op);
sort($array_op);
$array_op = "'".implode("','", $array_op)."'";

// echo $array_op;

//TIPO DE CONTRATO
$tipos = [];
$sql_tipos = "SELECT * FROM sys_vendas_tipos WHERE tipo_id IN ($array_op);";
$result_tipos = mysqli_query($con, $sql_tipos) or die(mysqli_error($con));
// echo $sql_tipos;
while($row_tipos = mysqli_fetch_array( $result_tipos )) {
   array_push($tipos, ["tipo_id" => $row_tipos['tipo_id'], "tipo_nome" => $row_tipos['tipo_nome']]);
}

// print_r($tipos);

echo json_encode($tipos);
?>