<?php

// function enviarSMS($telefone, $link)
// {
//     $API_KEY = "18fff0a5-ff86-4195-afdd-b9cadd411c72";
//     // $API_KEY = "D4B290C5-DEAA-4F16-A75B-B5B730C7F78F";

//     $content = $link ;

//     $service_url = "https://sms.comtele.com.br/api/v2/send";

//     $payload = [
//         "Content" => $content,
//         "Receivers" => implode(",", $telefone)
//     ];

//     $headers = [
//         "Content-Type: application/json",
//         "Content-Length: " . strlen(json_encode($payload)),
//         "auth-key: " . $API_KEY 
//     ];

//     $curl = curl_init($service_url);
//     curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
//     curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));

//     $server_output = curl_exec($curl);
//     if (curl_errno($curl)) {
//         echo 'Erro de cURL: ' . curl_error($curl);
//     }
//     curl_close($curl);

//     $res = json_decode($server_output);
    
//     // echo "SMS enviado para " . $telefone . "<br>";

//     echo $server_output;

   
// }

// enviarSMS('51997073430', 'https://www.gabriellgomess.com');

// ************************************************

function enviarSMS($phoneNumber, $message) {
    // URL do endpoint
    $url = 'https://sms.comtele.com.br/api/v2/send';

    // Dados da requisição
    $dados = array(
        'Receivers' => $phoneNumber,
        'Content' => $message
    );
    $dadosJson = json_encode($dados);

    // Inicializa a sessão cURL
    $ch = curl_init($url);

    // Configurações da requisição
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosJson);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'auth-key: cf5a54ea-a68f-4dcd-bce1-8474dcf71270', // Substitua pelo seu auth-key
        'content-type: application/json'
    ));

    // Executa a requisição
    $response = curl_exec($ch);

    // Fecha a sessão cURL
    curl_close($ch);

    // Retorna a resposta
    return $response;
}

// Exemplo de uso da função

$phoneNumber = '5551997073430'; // Número do telefone receptor
$message = 'Mensagem teste'; // Sua mensagem
$resposta = enviarSMS($phoneNumber, $message);

// Imprime a resposta da requisição
echo $resposta;




?>