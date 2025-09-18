<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../get_erros.php");
include("../variaveis_fixas.php");
// include("/var/www/html/connect_seguro.php");

$db_ip = "10.100.0.22";
$ip =$_SERVER["REMOTE_ADDR"]; //Pego o IP

$con = mysqli_connect("10.100.0.22","root","Theredpil2001", "sistema");
if (!$con)
    {
    die('Could not connect: ' . mysqli_error());
    }



$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$venda_id = $_POST['venda_id'];
$parcelas = $_POST['parcelas'];

$output = '';

$output .= "Iniciando o script...<br>";


$output .= "Dados recebidos: venda_id = {$venda_id}<br> parcelas = <br>" . json_encode($parcelas) . "<br>";

$total_parcelas = mysqli_query($con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $venda_id."'")
or die(mysqli_error($con));
$row_total_parcelas = mysqli_fetch_array($total_parcelas);

$data_hoje = date('Y-m-d');

for($i = 0; $i < count($parcelas); $i++){
   
    $query_atualiza_parcelas_correspondentes = "UPDATE `sys_vendas_transacoes_seg` 
                                                SET `transacao_recebido` = '1', transacao_motivo = '0', transacao_data = '".$data_hoje."'  WHERE transacao_id_venda = '" . $venda_id."' AND transacao_mes = '".$parcelas[$i]['vigencia']."'";
    $atualiza_parcelas = mysqli_query($con, $query_atualiza_parcelas_correspondentes);
    $output .= $query_atualiza_parcelas_correspondentes."<br>";
}


$result_pagas = mysqli_query($con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" .$venda_id. "' AND transacao_recebido = 1;")
or die(mysqli_error($con));            
$row_pagas = mysqli_fetch_array($result_pagas);
$parcelas_pagas = $row_pagas["total"];

if($row_total_parcelas['total'] == $parcelas_pagas){
    $query_atualiza_vendas = "UPDATE `sys_vendas_seguros` SET `vendas_status` = '67' WHERE `vendas_id` = '".$venda_id."'";
    $atualiza_vendas = mysqli_query($con, $query_atualiza_vendas);
   
    $insere_registro_venda_query = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, `vendas_id`, `registro_usuario`, `registro_obs`, `registro_status`, `registro_data`) VALUES (NULL, '".$venda_id."','Portal do Cliente','Parcela atualizada como recebida e venda ATIVA','67',NOW());";
    $insere_registro_venda = mysqli_query($con, $insere_registro_venda_query);      
   
    include($path_includes.$Arquivo_conect);
    include($path_includes."utf8.php");                            
    include($path_includes."cliente/espelha.php");                            
    include($path_includes."connect_db02.php");
    include($path_includes."utf8.php");                            
    include($path_includes."cliente/espelha_insere.php");
    // Atualiza o cliente no banco de dados do sistema de campanhas ********
    // Query que remove da campanha de cobrança
    $query_atualiza_cliente_campanha = "UPDATE sys_inss_clientes SET cliente_campanha_id = '0', cliente_usuario = 'integrador.automatico', cliente_alteracao = NOW() WHERE cliente_cpf='".$json2['cpf']."';";
    $atualiza_cliente_campanha = mysqli_query($con, $query_atualiza_cliente_campanha);
    $output .= "Venda atualizada com sucesso.<br>";

}      

$insere_registro_venda_query_parcelas_em_aberto = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, `vendas_id`, `registro_usuario`, `registro_obs`, `registro_status`, `registro_data`) VALUES (NULL, '".$venda_id."','integrador.automatico','Pagamento realizado mas nem todas as parcelas foram pagas','',NOW());";
$insere_registro_venda_parcelas_em_aberto = mysqli_query($con, $insere_registro_venda_query_parcelas_em_aberto);

$output .= "Registro inserido na tabela sys_vendas_registros_seg.<br>";

echo $output;

?>