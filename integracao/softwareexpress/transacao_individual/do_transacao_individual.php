<?php
include("../get_erros.php");
include("../variaveis_fixas.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_GET['token'] === 'EsearR31234fpssa0vfc9o') {
    include("connect_seguro.php");

    $dados = (object) $_POST;

    $transaction_id = mysqli_real_escape_string($con, $dados->transaction_id ?? '');

    $dataTransaction = (object) [
        'card' => (object) [
            'number' => mysqli_real_escape_string($con, $dados->card_num ?? ''),
            'expiry_date' => mysqli_real_escape_string($con, ($dados->card_validade_mes ?? '') . substr($dados->card_validade_ano ?? '', 2))
        ]
    ];

    $url_do_transaction = $link_prefixo . '/e-sitef/api/v1/payments/' . ($dados->transaction_nit ?? '');
    $response_transaction = doTransactionCurl($dataTransaction, $url_do_transaction, $CURLOPT_HTTPHEADER);

    if (!empty($response_transaction->transaction_error_number) && $response_transaction->transaction_error_number == '28') {
        $tentativa = 1;
        while ($tentativa <= 3) {
            $url_getstatus = $link_prefixo . '/e-sitef/api/v1/transactions/' . ($response_transaction->payment->nit ?? '');
            $response_getstatus = getStatus($url_getstatus, $CURLOPT_HTTPHEADER);
            if ($response_getstatus->getstatus_error_number == 0) {
                $tentativa = 4;
            } else {
                $tentativa++;
            }
        }
        $response_transaction = $response_getstatus;
    }

    atualizaTransacao($con, $response_transaction);
    echo json_encode($response_transaction);
} else {
    echo json_encode(["error" => "Token inválido"]);
}

/**
 * Atualiza os dados da transação no banco de dados.
 */
function atualizaTransacao($con, $dados) {
    if (!isset($dados->payment)) {
        return false;
    }

    $transaction_id = isset($dados->payment->order_id) ? explode("_", $dados->payment->order_id)[0] : '';
    $transaction_nit = $dados->payment->nit ?? '';
    $transacao_status = $dados->payment->status ?? '';

    $query = "UPDATE sys_vendas_transacoes_tef SET transacao_nit = ?, transacao_status = ? WHERE transacao_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $transaction_nit, $transacao_status, $transaction_id);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $resultado;
}

/**
 * Faz uma requisição cURL para processar a transação.
 */
function doTransactionCurl($dataTransaction, $url, $CURLOPT_HTTPHEADER) {
    $data_corpo = json_encode([
        "amount" => $dataTransaction->amount ?? '',
        "installments" => "1",
        "installment_type" => "4",
        "card" => [
            "number" => $dataTransaction->card->number ?? '',
            "expiry_date" => $dataTransaction->card->expiry_date ?? ''
        ]
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data_corpo,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response);
}

/**
 * Obtém o status da transação via cURL.
 */
function getStatus($url, $CURLOPT_HTTPHEADER) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    $error_number = curl_errno($curl);

    curl_close($curl);

    return (object) [
        "resposta" => json_decode($response),
        "erro" => $error,
        "getstatus_error_number" => $error_number
    ];
}
?>
