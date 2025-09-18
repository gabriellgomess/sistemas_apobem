<?php
// Função para registrar logs
function logMessage($message) {
    $logFile = __DIR__ . '/logs_cadastro.txt';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

logMessage("Iniciando script");

// Inicializa cURL para obter access token
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

$json = json_decode($response);
$ACESS_TOKEM = $json->access_token ?? null;

curl_close($curl);

// Verifica se obteve um token válido
if (!$ACESS_TOKEM) {
    logMessage("Erro: Access token não foi obtido.");
    exit("Erro ao obter token de acesso.");
}

$vendas_id = $_POST["vendas_id"] ?? '';
$vendas_apolice = $_POST["vendas_apolice"] ?? '';
$id_tempo_assist = $_POST["id_tempo_assist"] ?? '';
$nome = $_POST['cliente_nome'] ?? ($_POST['clients_nm'] ?? '');
$nasc = $_POST['cliente_nascimento'] ?? '';
$cpf = $_POST['cliente_cpf'] ?? '';
$email = $_POST['cliente_email'] ?? '';
$sexo = $_POST['cliente_sexo'] ?? '';
$estadoCivil = $_POST['cliente_est_civil'] ?? '';
$rg = $_POST['cliente_rg'] ?? '';

// Ajuste do sexo
$sexo = ($sexo == "F") ? 2 : (($sexo == "M") ? 1 : 1);

// Ajuste do estado civil
$estadoCivilMap = [
    "casado" => 1, "solteiro" => 2, "divorciado" => 3,
    "VIUVO" => 4, "outros" => 5
];
$estadoCivil = $estadoCivilMap[strtolower($estadoCivil)] ?? 2;

// Definição do ID do plano
$idPlanoUss = $id_tempo_assist ?: (
    in_array($vendas_apolice, [113, 121, 112, 120, 119, 111, 175, 176]) ? 526471 :
    (in_array($vendas_apolice, [110, 118, 173, 174]) ? 526473 :
    (in_array($vendas_apolice, [130, 129, 128, 171, 172, 184]) ? 526475 : null))
);

$agora = round(microtime(true) * 1000);
$fim = date('Y-m-d', strtotime('+10 years'));

logMessage("Preparando requisição para o endpoint itemCoberto...");

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.tempoassist.com.br/segurado/itemCoberto',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode([
    "segmento" => 3,
    "apolice" => $vendas_id,
    "idPlanoUss" => $idPlanoUss,
    "inicioVig" => $agora,
    "fimVig" => $fim,
    "segurado" => $nome,
    "cpfCnpj" => $cpf,
    "email" => $email,
    "vip" => "N",
    "idClienteCorporativo" => 592,
    "pessoa" => [
        "nome" => $nome,
        "sexo" => $sexo,
        "dataNasc" => $nasc,
        "cpf" => $cpf,
        "rg" => $rg,
        "estadoCivil" => $estadoCivil,
        "profissao" => "",
        "qtdVidas" => "1"
    ]
  ]),
  CURLOPT_HTTPHEADER => array(
    'client_id: ffae4215-b34b-3bf9-9528-984b4443f445',
    'access_token: ' . $ACESS_TOKEM,
    'token: 51057BB0D576B0D868DEE276DE8B921B',
    'chave: APOBEM',
    'idClienteCorporativo: 592',
    'idTipoCarteira: 3',
    'idPlanoUss: ' . $idPlanoUss,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
if (curl_errno($curl)) {
    logMessage("Erro na requisição ao endpoint itemCoberto: " . curl_error($curl));
} else {
    logMessage("Requisição ao endpoint itemCoberto realizada com sucesso.");
    logMessage("Resposta: " . $response);
}

curl_close($curl);

echo $response;
?>
