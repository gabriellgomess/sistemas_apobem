<?php 

$CURLOPT_HTTPHEADER = array(
			"access_token: 49188e8a6a676a2c5d5c553225d856040497580ae7552b34e260489cdab4da2c",
			"Content-Type: application/json"
);

//CONSULTA NO BANCO DE DADOS DA ASAAS SE O CLIENTE JA TEM CADASTRO
$link_prefixo_consultaCPF = "https://api.asaas.com/v3/customers?cpfCnpj=";


//CRIAÇÃO DE NOVO CLIENTE PRODUCAO
$link_prefixo_novo_cliente = "https://api.asaas.com/v3/customers";


//CRIAÇÃO DE NOVO BOLETO PRODUÇÃO
$link_prefixo_novo_boleto = "https://api.asaas.com/v3/payments";




///////////////////////////////////////////////////SERVIDORES DE HOMOLOGAÇÃO/////////////////


//CONSULTA NO BANCO DE DADOS DA ASAAS SE O CLIENTE JA TEM CADASTRO
//$link_prefixo_consultaCPF_HOMOLOG = "https://sandbox.asaas.com/api/v3/customers?cpfCnpj=";


//CRIAÇÃO DE NOVO CLIENTE HOMOLOG
//$link_prefixo_novo_cliente_HOMOLOG = "https://sandbox.asaas.com/api/v3/customers";


//CRIAÇÃO DE NOVO BOLETO HOMOLOG
//$link_prefixo_novo_boleto_HOMOLOG = "https://sandbox.asaas.com/api/v3/payments";






?>