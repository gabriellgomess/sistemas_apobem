<?php 

$CURLOPT_HTTPHEADER = array(
			"access_token: 7c782efb3773b076780bbd295d78f4e6f12ffe0c06af0606c4ffdbe1945e0cc5",
			"Content-Type: application/json"
);

//CONSULTA NO BANCO DE DADOS DA ASAAS SE O CLIENTE JA TEM CADASTRO
$link_prefixo_consultaCPF = "https://www.asaas.com/api/v3/customers?cpfCnpj=";


//CRIAÇÃO DE NOVO CLIENTE PRODUCAO
$link_prefixo_novo_cliente = "https://www.asaas.com/api/v3/customers";


//CRIAÇÃO DE NOVO BOLETO PRODUÇÃO
$link_prefixo_novo_boleto = "https://www.asaas.com/api/v3/payments";




///////////////////////////////////////////////////SERVIDORES DE HOMOLOGAÇÃO/////////////////


//CONSULTA NO BANCO DE DADOS DA ASAAS SE O CLIENTE JA TEM CADASTRO
$link_prefixo_consultaCPF_HOMOLOG = "https://sandbox.asaas.com/api/v3/customers?cpfCnpj=";


//CRIAÇÃO DE NOVO CLIENTE HOMOLOG
$link_prefixo_novo_cliente_HOMOLOG = "https://sandbox.asaas.com/api/v3/customers";


//CRIAÇÃO DE NOVO BOLETO HOMOLOG
$link_prefixo_novo_boleto_HOMOLOG = "https://sandbox.asaas.com/api/v3/payments";






?>