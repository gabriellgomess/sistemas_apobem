<?php
	

include("variaveis_fixas.php");
header("Access-Control-Allow-Origin: *");

$idAsaas = $_POST["idAsaas"];
$dueDate = $_POST["dueDate"];
$value = $_POST["value"];
$externalReference = $_POST["externalReference"];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $link_prefixo_novo_boleto);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"customer\": \"{$idAsaas}\",
  \"billingType\": \"BOLETO\",
  \"dueDate\": \"{$dueDate}\",
  \"value\": {$value},
  \"description\": \"Tarifa bancária de R$ 3.50\",
  \"externalReference\": \"{$externalReference}\",
  \"discount\": {
    \"value\": 0,
    \"dueDateLimitDays\": 0
  },
  \"fine\": {
    \"value\": 0
  },
  \"interest\": {
    \"value\": 0
  },
  \"postalService\": false
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, $CURLOPT_HTTPHEADER);

$response = curl_exec($ch);
curl_close($ch);



$respostaJson = json_encode($response);
curl_close($ch);

echo $respostaJson;

?>