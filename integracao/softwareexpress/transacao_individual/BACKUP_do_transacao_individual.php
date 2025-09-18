<?php
include("../get_erros.php");
include("../variaveis_fixas.php");
header("Access-Control-Allow-Origin: *");

if ($_GET['token'] === 'EsearR31234fpssa0vfc9o') {
    include("connect_seguro.php");        
    $dados = (object) $_POST;

    $transaction_id = mysql_real_escape_string($dados->transaction_id);

    $dataTransaction = (object) array(
        'card' => (object) array(
            'number' => mysql_real_escape_string($dados->card_num),
            'expiry_date' => mysql_real_escape_string($dados->card_validade_mes . substr($dados->card_validade_ano, 2))
        )
    );

    $url_do_transaction = $link_prefixo.'/e-sitef/api/v1/payments/'.$dados->transaction_nit;
    $response_transaction = doTransactionCurl($dataTransaction, $url_do_transaction, $CURLOPT_HTTPHEADER);

    if ($response_transaction->transaction_error_number !='0' && $response_transaction->transaction_error_number == '28') {
        $tentativa = 1;
        while($tentativa <= 3) {
            $url_getstatus = $link_prefixo.'/e-sitef/api/v1/transactions/'.$response_transaction->payment->nit;
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
    echo json_encode(["error" => "token inválido"]);
}

function atualizaTransacao($con, $dados) {
    $transaction_id = mysql_real_escape_string($dados->payment->order_id);
	$transaction_id = explode("_", $transaction_id);
	$transaction_id = $transaction_id[0];
    $transaction_nit = mysql_real_escape_string($dados->payment->nit);
    $transacao_status = mysql_real_escape_string($dados->payment->status);

    $update_transaction_query = "UPDATE sys_vendas_transacoes_tef SET transacao_nit='$transaction_nit', transacao_status='$transacao_status' WHERE transacao_id='$transaction_id'";

    $result_transaction = mysql_query($update_transaction_query) or die(mysql_error());

	if ($result_transaction) {
		return true;
	} else {
		return false;
	}
}

function doTransactionCurl($dataTransaction, $url, $CURLOPT_HTTPHEADER) {
    $data_corpo = json_encode([
        "amount" => "{$dataTransaction->amount}",
        "installments" => "1",
        "installment_type" => "4",
        "card" => [
            "number" => $dataTransaction->card->number,
            "expiry_date" => $dataTransaction->card->expiry_date
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
