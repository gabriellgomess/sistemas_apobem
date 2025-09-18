<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// include 'connect.php';

// $hash = uniqid();

// $url = "https://www.apobem.com.br/portal/?schdl=1&data=STlkSXhQWFlzOExleDZ0SEZPcmpxZmdkcXlLaU9VaTJHSjVwa2VUNEZsVT06Ok6ezJJHonrgQLrmG3rJKCI%3D";

// $url_prefix = "https://www.apobem.com.br/portal/";

// $count = 0;

// // Using PDO prepared statements to prevent SQL injection
// $stmt = $conn->prepare("INSERT INTO sys_url_shortner (id, original_url, access_count) VALUES (:id, :original_url, :access_count)");
// $stmt->bindParam(':id', $hash);
// $stmt->bindParam(':original_url', $url);
// $stmt->bindParam(':access_count', $count);
// $stmt->execute();

// echo "URL encurtada: " . $url_prefix . $hash;

// URL do endpoint
$url = 'https://sms.comtele.com.br/api/v2/accounturls';

// Dados da requisição
$dados = array(
    'Url' => 'https://www.apobem.com.br/portal/?schdl=1&data=STlkSXhQWFlzOExleDZ0SEZPcmpxZmdkcXlLaU9VaTJHSjVwa2VUNEZsVT06Ok6ezJJHonrgQLrmG3rJKCI%3D'
);
$dadosJson = json_encode($dados);

// Inicializa a sessão cURL
$ch = curl_init($url);

// Configurações da requisição
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosJson);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'auth-key: cf5a54ea-a68f-4dcd-bce1-8474dcf71270',
    'content-type: application/json'
));

// Executa a requisição
$response = curl_exec($ch);

$responseArray = json_decode($response, true);

// Acessa a URL encurtada
$shorterUrl = $responseArray['Object']['ShorterUrl'];

// Fecha a sessão cURL
curl_close($ch);

// Exibe a URL encurtada
echo $shorterUrl;
