<?php
header("Access-Control-Allow-Origin: *");
include("variaveis_fixas.php");

$arquivo_conect = "/var/www/html/sistema/sistema/connect_seguro.php";

include($arquivo_conect);

$hoje = date("Y-m-d");
$menos_2_dias = date("Y-m-d", strtotime("-2 days", strtotime($hoje)));

$url = "https://www.asaas.com/api/v3/payments?dueDate%5Bge%5D=".$menos_2_dias."&dueDate%5Ble%5D=".$hoje."&limit=100";

$metodo = "GET";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "access_token: 49188e8a6a676a2c5d5c553225d856040497580ae7552b34e260489cdab4da2c",
    "Content-Type: application/json"
));

$response = json_decode(curl_exec($ch));

curl_close($ch);

$count = 0;



// print_r($response->data[0]);

foreach($response->data as $key => $value){
    echo "VALUE: ".$value->id." - Status: ".$value->status." - Data: ".$value->dueDate."<br>";
    $query = "UPDATE sys_vendas_transacoes_boleto SET status = $value->status WHERE id_boleto = $value->id";
    // $atualiza_transacao_boleto = mysqli_query($con, $query) or die(mysqli_error($con));
    echo "QUERY: ".$query."<br>";  
}


?>



