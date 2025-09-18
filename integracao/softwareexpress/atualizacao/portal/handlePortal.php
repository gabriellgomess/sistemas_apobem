<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include('api-portal.php');
$path_includes = "/var/www/html/sistema/sistema/";
$arquivo_conect = $path_includes."connect_seguro.php";
include($arquivo_conect);
include('/var/www/html/integracao/softwareexpress/variaveis_fixas.php');
$key = '49188e8a6a676a2c5d5c553225d856040497580ae7552b34e260489cdab4da2c';
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);
$tipo = $_GET['param'];
$portalCobranca = new PortalCobranca($con, $link_prefixo, $CURLOPT_HTTPHEADER);
switch ($tipo) {
    case '1':
        $busca_dados_financeiro = $portalCobranca->consultaFinanceiroParcelas($_POST['vendas_id']);
        echo $busca_dados_financeiro;
        break;
    case '2':
        $busca_dados_cliente = $portalCobranca->consultarAsaas($_POST['cpf']);
        echo $busca_dados_cliente;
        break;
    case '3':
        $cria_cobranca_boleto = $portalCobranca->criarCobrancaBoleto(array(
            "customer" => $_POST['customer'],
            "billingType" => 'BOLETO',
            "dueDate" => $_POST['dueDate'],
            "value" => $_POST['value'],
            "description" => $_POST['description'],
            "externalReference" => $_POST['externalReference'],
            "vendas_id" => $_POST['vendas_id'], 
            "cpfCnpj" => $_POST['cpfCnpj'],
            "parcelas" => $_POST['parcelas_correspondentes'],

        ), $key);
        echo $cria_cobranca_boleto;      
        break;
    case '4':
        $insere_cliente_asaas = $portalCobranca->cadastrarCliente(array(
            "name" => $_POST['name'],
            "email" => $_POST['email'],
            "phone" => $_POST['phone'],
            "mobilePhone" => $_POST['mobilePhone'],
            "cpfCnpj" => $_POST['cpfCnpj'],
            "postalCode" => $_POST['postalCode'],
            "address" => $_POST['address'],
            "addressNumber" => $_POST['addressNumber'],
            "externalReference" => $_POST['externalReference'],
            "observations" => $_POST['observations'],
            "notificationDisabled" => true
        ), $key );
        echo $insere_cliente_asaas;        
        break;
    case '5':
       
        break;
    case '6':
        $buscarCartoes = $portalCobranca->buscarCartoes($_POST['cpf']);
        echo $buscarCartoes;
        
        break;
    case '7':
        $cadastrarCartao = $portalCobranca->cadastrarCartaoAdicional($_POST['cpf'], $_POST['cartao_numero'], $_POST['validade_mes'], $_POST['validade_ano'],  $_POST['cartao_adm'], $_POST['cvv']);
        echo $cadastrarCartao;
       
        break;
    case '8':
        $cadastraVenda = $portalCobranca->cadastraVenda();
        echo $cadastraVenda;
      
        break;
    case '9':
        $token = 'EsearR31234fpssa0vfc9o';
        $cpf = $_POST['cpf'];
        $username = $_POST['username'];
        $user_id = $_POST['user_id'];
        $venda_id = $_POST['venda_id'];
        $plano = $_POST['plano'];
        $card_adm = $_POST['card_adm'];
        $card_num = $_POST['card_num'];        
        $card_cvv = $_POST['card_cvv'];
        $card_validade_mes = $_POST['card_validade_mes'];
        $card_validade_ano = $_POST['card_validade_ano'];
        $transacao_valor = $_POST['transacao_valor'];
        $salvar_cartao_principal = $_POST['save_card_future'];
        $salvar_cartao_secundario = $_POST['save_card_secondary'];

        $iniciaTransacaoCartao = $portalCobranca->iniciaTransacaoCartao($token, $cpf, $username, $user_id, $venda_id, $plano, $card_adm, $card_num, $card_cvv, $card_validade_mes, $card_validade_ano, $transacao_valor, $salvar_cartao_principal, $salvar_cartao_secundario);
        echo $iniciaTransacaoCartao;
    case '10':
        $token = 'EsearR31234fpssa0vfc9o';
        $cpf = $_POST['cpf'];
        $username = $_POST['username'];
        $user_id = $_POST['user_id'];
        $venda_id = $_POST['venda_id'];
        $plano = $_POST['plano'];
        $card_adm = $_POST['card_adm'];
        $card_num = $_POST['card_num'];
        $card_validade_mes = $_POST['card_validade_mes'];
        $card_validade_ano = $_POST['card_validade_ano'];
        $transacao_valor = $_POST['transacao_valor'];
        $transaction_nit = $_POST['transaction_nit'];
        $transaction_id = $_POST['transaction_id'];

        $criaCobrancaCartao = $portalCobranca->cobrarCartao($token, $cpf, $username, $user_id, $venda_id, $plano, $card_adm, $card_num,  $card_validade_mes, $card_validade_ano, $transacao_valor, $transaction_nit, $transaction_id);
        echo $criaCobrancaCartao;


        break;
    case '11':
        
        break;
    case '12':
        ;
        break;
    case '13':
        
        break;
    case '14':
       
        break;
    case '15':
        
        break;
    default:
        echo "Erro";
        break;
}

?>