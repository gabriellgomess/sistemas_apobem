<?php

class SMSSender
{
    private $apiKey;
    private $serviceUrl;

    public function __construct($apiKey, $serviceUrl = "https://sms.comtele.com.br/api/v2/send")
    {
        $this->apiKey = $apiKey;
        $this->serviceUrl = $serviceUrl;
    }

    public function enviarSMS($telefone, $link, $nomeFull)
    {
        $nome = explode(' ', $nomeFull)[0];
        $telefone = preg_replace('/[^0-9]/', '', $telefone);

        $dados = array(
            'Receivers' => $telefone,
            'Content' => $nome . ', bem-vindo(a) a APOBEM! Baixe o seu cartão virtual: ' . $link
        );
        $dadosJson = json_encode($dados);

        $ch = curl_init($this->serviceUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosJson);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'auth-key: ' . $this->apiKey,
            'content-type: application/json'
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}

// Exemplo de uso:
$apiKey = "cf5a54ea-a68f-4dcd-bce1-8474dcf71270";
$smsSender = new SMSSender($apiKey);

$telefone = "51997073430"; // coloque um número real aqui
$link = "https://apobem.com.br/portal/seucartao";
$nome = "Maria da Silva";

$retorno = $smsSender->enviarSMS($telefone, $link, $nome);

echo "<pre>";
print_r($retorno);
echo "</pre>";
