<?php

$customer = $_POST['customer'];
$billingType = $_POST['billingType'];
$dueDate = $_POST['dueDate'];
$value = $_POST['value'];
$description = $_POST['description'];
$externalReference = $_POST['externalReference'];
$installmentCount = $_POST['installmentCount'];
$installmentValue = $_POST['installmentValue'];
$discountValue = $_POST['discountValue'];
$discountDays = $_POST['discountDays'] ;
$discountType = $_POST['dyscountType'];
$fineValue = $_POST['fineValue'];
$interestValue = $_POST['interestValue'];
$postalService = $_POST['postalService'];
$id_boleto = $_POST['id_boleto'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://www.asaas.com/api/v3/payments/".$id_boleto."");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

// $array = array(
//     'billingType' => $billingType,
//     'dueDate' => $dueDate,
//     'value' => $value,
//     'description' => $description,
//     'externalReference' => $externalReference,
//     'installmentCount' => $installmentCount,
//     'installmentValue' => $installmentValue,
//     'discount' => array(
//         'value' => $discountValue,
//         'dueDateLimitDay' => $discountDays,
//         'type' => $discountType
//     ),
//     'fine' => array(
//         'value' => $fineValue
//     ),
//     'interest' => array(
//         'value' => $interestValue
//     )
// );

// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
    \"billingType\": \"$billingType\",
    \"dueDate\": \"$dueDate\",
    \"value\": $value,
    \"description\": \"$description\",
    \"externalReference\": \"$externalReference\",
    \"discount\": {
      \"value\": $discountValue,
      \"dueDateLimitDays\": $discountDays
    },
    \"fine\": {
      \"value\": $fineValue
    },
    \"interest\": {
      \"value\": $interestValue
    },
    \"postalService\": false
  }");


curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "access_token: 49188e8a6a676a2c5d5c553225d856040497580ae7552b34e260489cdab4da2c"
  ));
  
  $response = curl_exec($ch);
  curl_close($ch);
   echo print_r($array);
?>