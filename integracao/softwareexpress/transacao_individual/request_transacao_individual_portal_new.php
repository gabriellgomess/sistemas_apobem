<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include("../get_erros.php");
include("../variaveis_fixas.php");

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if ($_GET['token'] == 'EsearR31234fpssa0vfc9o') {
    $db_ip = "10.100.0.22";
    $con = mysqli_connect($db_ip, "root", "Theredpil2001", "sistema");
    // Limpa CPF
    $cliente_cpf = $_POST['cpf'];
    $removidos = array(".", "-");
    $cliente_cpf = str_replace($removidos, "", $cliente_cpf);

    $dados = (object) array(
        'token' => $_GET['token'],
        'cpf' => $cliente_cpf,
        'username' => $_POST['username'],
        'user_id' => $_POST['user_id'],
        'venda_id' => $_POST['venda_id'],
        'plano' => $_POST['plano'],
        'card_adm' => $_POST['card_adm'],
        'card_num' => $_POST['card_num'],
        'card_validade_mes' => $_POST['card_validade_mes'],
        'card_validade_ano' => $_POST['card_validade_ano'],
        'transacao_valor' => $_POST['transacao_valor'],
        'so' => $_POST['so'],
    );

    $transaction_id = insereRegistroTransacao($con, $dados);
    $order_id = $transaction_id."_C";

    function valorParaAmount($valor){
        $valor = str_replace(",", "", $valor);
        $valor = str_replace(".", "", $valor);
        return floatval($valor);
    }

    $payment_amount = valorParaAmount(number_format($dados->transacao_valor, 2, '.', ''));
    $card_number = $dados->card_num;
    $card_expiry_date = $dados->card_validade_mes."".substr($dados->card_validade_ano,2);

    $dataPreAut = (object) array(
        'order_id' => $order_id,
        'amount' => $payment_amount,
        'authorizer_id' => "2"
    );

    $url_pre_aut = $link_prefixo.'/e-sitef/api/v1/transactions/';
    $curl_resposta = executaCurlPreAutorizacao($dataPreAut, $url_pre_aut, $CURLOPT_HTTPHEADER);
    $respostaJson = json_decode($curl_resposta);

    $dados = (object) array(
        'authorizer_id' => mysqli_real_escape_string($con, $respostaJson->payment->authorizer_id),
        'status' => mysqli_real_escape_string($con, $respostaJson->payment->status),
        'order_id' => $order_id,
        'transacao_id' => $transaction_id,
        'transacao_nit' => mysqli_real_escape_string($con, $respostaJson->payment->nit),
    );

    atualizaTransacao($con, $dados);

    echo json_encode($respostaJson);
} else {
    echo "Token inválido";
    exit;
}

$result_transacoes_tef_dia = mysqli_query($con, "SELECT COUNT(transacao_id) AS total FROM sys_vendas_transacoes_tef WHERE transacao_cliente_cpf = '" . $_POST['cpf'] . "' AND transacao_data > '".date('Y-m-d')." 00:00:00';") or die(mysqli_error($con));
$row_tef_dia = mysqli_fetch_array($result_transacoes_tef_dia);


function insereRegistroTransacao($con, $dados) {
    $insere_transaction_query = "INSERT INTO sys_vendas_transacoes_tef (
        transacao_nit,
        transacao_cliente_cpf,
        transacao_username,
        transacao_user_id,
        transacao_token,
        transacao_valor,
        transacao_venda_id,
        transacao_data,
        transacao_dia_debito,
        transacao_tipo_plano,
        transacao_cartao_adm,
        transacao_cartao_band,
        transacao_cartao_cvv,
        transacao_cartao_num,
        transacao_cartao_validade_mes,
        transacao_cartao_validade_ano,
        transacao_so)
        VALUES (
        '',
        '".$dados->cpf."',
        '".$dados->username."',
        '".$dados->user_id."',
        '".$dados->token."',
        '".$dados->transacao_valor."',
        '".$dados->venda_id."',
        NOW(),
        NOW(),
        '".$dados->plano."',
        '".$dados->card_adm."',
        '',
        '666',
        '".$dados->card_num."',
        '".$dados->card_validade_mes."',
        '".$dados->card_validade_ano."',
        '".$dados->so."'
    )";

    $result_transaction = mysqli_query($con, $insere_transaction_query);
    
    if (!$result_transaction) {
        $msg = "Erro na consulta: " . mysqli_error($con);
        return $msg;
    }
    
    return mysqli_insert_id($con);
}

function atualizaTransacao($con, $dados) {
    $update_transaction_query = "UPDATE sys_vendas_transacoes_tef
        SET transacao_nit = '".$dados->transacao_nit."',
        transacao_status = '".$dados->transacao_status."',
        order_id = '".$dados->order_id."'
        WHERE transacao_id = '".$dados->transacao_id."'";

    $result_transaction = mysqli_query($con, $update_transaction_query);
    
    if (!$result_transaction) {
        $msg = "Erro na consulta: " . mysqli_error($con);
        return $msg;
    }
}

function executaCurlPreAutorizacao($dataPreAut, $url, $CURLOPT_HTTPHEADER) {
    $orignal_parse = parse_url($url, PHP_URL_HOST);
    $get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
    $read = stream_socket_client(
        "ssl://".$orignal_parse.":443",
        $errno,
        $errstr,
        30,
        STREAM_CLIENT_CONNECT,
        $get
    );        
    $cont = stream_context_get_params($read);
    openssl_x509_export($cont["options"]["ssl"]["peer_certificate"],$certificado);

    $data_corpo = "{
        \"order_id\" : \"{$dataPreAut->order_id}\",
        \"installments\" : \"1\",
        \"installment_type\" : \"4\",
        \"authorizer_id\" : \"{$dataPreAut->authorizer_id}\",
        \"amount\" : \"{$dataPreAut->amount}\"
    }";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => ($data_corpo != '') ? $data_corpo : '',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER
    ));    

    $erro = curl_error($curl);
    $resposta = curl_exec($curl);
    curl_close($curl);

    if ($erro) {
        return $erro;
    } else {
        return $resposta;
    }
}
?>