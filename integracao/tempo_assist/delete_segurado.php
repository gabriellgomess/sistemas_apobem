<?php

// Função para registrar logs
function logMessage($message) {
    $logFile = __DIR__ . '/logs_cancelamento.txt';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

logMessage("Iniciando script");

// Inicializa cURL para obter o access token
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tempoassist.com.br/oauth2/access-token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => '{
"grant_type": "client_credentials"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Basic ZmZhZTQyMTUtYjM0Yi0zYmY5LTk1MjgtOTg0YjQ0NDNmNDQ1OmQ2YmYzZGU1LTk4ZTItM2E4ZS1iOGIwLTUyZTI5OGNhNTM2Yg==',
    'client_id: ffae4215-b34b-3bf9-9528-984b4443f445'
  ),
));

$response = curl_exec($curl);
if (curl_errno($curl)) {
    logMessage("Erro na requisição do access token: " . curl_error($curl));
} else {
    logMessage("Access token obtido com sucesso.");
}

$json = json_decode($response, true);
$ACESS_TOKEM = $json['access_token'] ?? null;

curl_close($curl);

// Verifica se o token foi obtido com sucesso
if (!$ACESS_TOKEM) {
    logMessage("Erro: Access token não foi obtido.");
    exit("Erro ao obter token de acesso.");
}

$vendas_id = $_POST["vendas_id"] ?? '';
logMessage("Iniciando requisição para obter itemCoberto da apólice: $vendas_id");

// Inicializa nova requisição cURL
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tempoassist.com.br/segurado/titular?apolice=' . $vendas_id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'token: 51057BB0D576B0D868DEE276DE8B921B',
    'chave: APOBEM',
    'idclientecorporativo: 592',
    'idtipocarteira: 3',
    'Content-Type: application/json',
    'client_id: 8667bcdf-3ff7-3b61-8526-2c29fe58dad5',
    'access_token: ' . $ACESS_TOKEM
  ),
));

$response = curl_exec($curl);
if (curl_errno($curl)) {
    logMessage("Erro na requisição do itemCoberto: " . curl_error($curl));
} else {
    logMessage("Requisição ao itemCoberto realizada com sucesso.");
    logMessage("Resposta: " . $response);
}

$objeto = json_decode($response, true);
$lista = [];

if (!empty($objeto['itemCoberto'][0])) {
    foreach ($objeto['itemCoberto'][0] as $key => $value) {
        $lista[] = $value;
    }
    $id = $lista[0] ?? null;
} else {
    logMessage("Erro: Nenhum itemCoberto encontrado para a apólice: $vendas_id");
    exit("Nenhum itemCoberto encontrado.");
}

// Verifica se um ID foi obtido
if (!$id) {
    logMessage("Erro: ID do itemCoberto não encontrado.");
    exit("Erro ao obter ID do itemCoberto.");
}

logMessage("ID do itemCoberto obtido: $id");

// Inicializa nova requisição cURL para deletar o itemCoberto
logMessage("Iniciando requisição para deletar itemCoberto com ID: $id");

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.tempoassist.com.br/sandbox/segurado/itemCoberto/' . $id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'DELETE',
  CURLOPT_HTTPHEADER => array(
    'token: 51057BB0D576B0D868DEE276DE8B921B',
    'chave: APOBEM',
    'idclientecorporativo: 592',
    'idtipocarteira: 3',
    'Content-Type: application/json',
    'client_id: 8667bcdf-3ff7-3b61-8526-2c29fe58dad5',
    'access_token: ' . $ACESS_TOKEM
  ),
));

$response2 = curl_exec($curl);
if (curl_errno($curl)) {
    logMessage("Erro ao deletar itemCoberto: " . curl_error($curl));
} else {
    logMessage("ItemCoberto deletado com sucesso.");
    logMessage("Resposta: " . $response2);
}

curl_close($curl);
echo $response2;

?>
