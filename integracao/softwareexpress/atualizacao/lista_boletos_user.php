<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$path_includes = "/var/www/html/sistema/sistema/";
$arquivo_conect = $path_includes."connect_seguro.php";
include($arquivo_conect);

// Query busca boletos por usuário

$busca_boletos_por_usuário = "SELECT * FROM sys_vendas_transacoes_boleto 
WHERE id_boleto IS NOT NULL AND username IS NOT NULL 
ORDER BY dateCreated DESC";

$executa_busca_boletos_por_usuário = mysqli_query($con, $busca_boletos_por_usuário);

while($row = mysqli_fetch_array($executa_busca_boletos_por_usuário)){
    $data[] = array(
        "transacao_id"=>$row['transacao_id'],
        "id_boleto"=>$row['id_boleto'],
        "dateCreated"=>$row['dateCreated'],
        "customer"=>$row['customer'],
        "dueDate"=>$row['dueDate'],
        "value"=>$row['value'],
        "status"=>$row['status'],
        "invoiceUrl"=>$row['invoiceUrl'],
        "vendas_id"=>$row['vendas_id'],
        "parcelas_correspondentes"=>$row['parcelas_correspondentes'],
        "username"=>$row['username']

    );
}

echo json_encode($data);






?>