<?php

include("variaveis_fixas.php");
header("Access-Control-Allow-Origin: *");

$cpfCnpj = $_POST["cpfCnpj"];


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $link_prefixo_consultaCPF . $cpfCnpj);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, $CURLOPT_HTTPHEADER);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => curl_error($ch)]);
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode !== 200) {
        echo json_encode(['error' => 'Erro na API', 'status' => $httpCode]);
    } else {
        echo $response;
    }
}
curl_close($ch);

?>
