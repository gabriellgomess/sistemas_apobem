<?php

$cpfCnpj = $_POST["cpfCnpj"];
$name = $_POST["name"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$mobilePhone = $_POST["mobilePhone"];
$postalCode = $_POST["postalCode"];
$address = $_POST["address"];
$addressNumber = $_POST["addressNumber"];
$complement = $_POST["complement"];
$province = $_POST["province"];
$externalReference = $_POST["externalReference"];
$notificationDisabled = $_POST["notificationDisabled"];
$additionalEmails = $_POST["additionalEmails"];
$municipalInscription = $_POST["municipalInscription"];
$stateInscription = $_POST["stateInscription"];
$observations = $_POST["observations"];


include("variaveis_fixas.php");
header("Access-Control-Allow-Origin: *");

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $link_prefixo_novo_cliente);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"name\": \"{$name}\",
  \"email\": \"{$email} \",
  \"phone\": \"{$phone}\",
  \"mobilePhone\": \"{$mobilePhone}\",
  \"cpfCnpj\": \"{$cpfCnpj}\",
  \"postalCode\": \"{$postalCode}\",
  \"address\": \"{$address}\",
  \"addressNumber\": \"{$addressNumber}\",
  \"complement\": \"{$complement}\",
  \"province\": \"{$province}\",
  \"externalReference\": \"{$externalReference}\",
  \"notificationDisabled\": {$notificationDisabled},
  \"additionalEmails\": \"{$additionalEmails}\",
  \"municipalInscription\": \"{$municipalInscription}\",
  \"stateInscription\": \"{$stateInscription}\",
  \"observations\": \"{$observations}\"
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, $CURLOPT_HTTPHEADER);

$response = curl_exec($ch);
$respostaJson = json_encode($response);
curl_close($ch);

echo $respostaJson;

?>