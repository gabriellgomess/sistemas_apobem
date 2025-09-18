<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../get_erros.php");
include("../variaveis_fixas.php");

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);


if ($_GET['token'] === 'EsearR31234fpssa0vfc9o')
{
	//captura e definição dos dados
	// include("../../../portal/sistema/connect_seguro.php");   
	$db_ip = "10.100.0.22"; 
    $con = mysqli_connect($db_ip,"root","Theredpil2001", "sistema");     
	

	// captura dos dados postados.
	$dados = (object) array(
		'token' => $_GET['token'],
		'cpf' => $_POST['cpf'],
		'username' => $_POST['username'],
		'user_id' => $_POST['user_id'],
		'venda_id' => $_POST['venda_id'],
		'plano' => $_POST['plano'],
		'card_adm' => $_POST['card_adm'],
		'card_num' => $_POST['card_num'],
		'card_validade_mes' => $_POST['card_validade_mes'],
		'card_validade_ano' => $_POST['card_validade_ano'],
		'transacao_valor' => $_POST['transacao_valor'],
		'transaction_nit' => $_POST['transaction_nit'],
		'transaction_id' => $_POST['transaction_id']
	);
	// fim captura dos dados postados.
	
	// registro inicial da transação que será efetuada e captura do id.
	$transaction_id = $dados->transaction_id;

	/* ----- DADOS DA REQUISIÇÃO DA TRANSAÇÃO ----- */
	$card_number = $dados->card_num; // exemplo: "5281735839609922";
	$card_expiry_date = $dados->card_validade_mes."".$dados->card_validade_ano; // Preencher com mês ano exemplo '1220' (mês 12 ano 2020)
	//FIM Valores falsos para teste, comentar ou apagar posteriormente.
	
	// Início CURL
	$dataTransaction = (object) array(
		'card' => (object) array(
			'number' => $card_number,
			'expiry_date' => $card_expiry_date
		)
	);

	$url_do_transaction = $link_prefixo.'/e-sitef/api/v1/payments/'.$dados->transaction_nit;
	// Tenta efetivar a transação via curl            
	$response_transaction = doTransactionCurl($dataTransaction, $url_do_transaction, $CURLOPT_HTTPHEADER);
	// FIM CURL

	// Caso a transação não tenha obtido sucesso e o erro foi 28 (tempo limite de resposta excedido)
	if($response_transaction->transaction_error_number !='0' && $response_transaction->transaction_error_number == '28')
	{
		$tentativa = 1;
		while($tentativa <= 3 )
		{
			$url_getstatus = $link_prefixo.'/e-sitef/api/v1/transactions/'.$response_transaction->payment->nit;
			// tenta verificar se a transação chegou a ocorrer e com qual resultado, afinal não foi possível obter a resposta antes.
			$response_getstatus = getStatus($url_getstatus, $CURLOPT_HTTPHEADER);
			if($response_getstatus->getstatus_error_number != 0)
			{
				$tentativa++;
			}else{
				$tentativa=4; // encerra while
			}
		}

		$response_getstatus->getstatus = true;
		$log_resposta = $response_getstatus->resposta;
		$log_resposta_ecoded = json_encode($response_getstatus->resposta);
		$final_response = json_encode($response_getstatus, true);
	}else{ // se obteve sucesso ou um erro diferente de 28...
		$log_resposta = $response_transaction->resposta;
		$log_resposta_ecoded = json_encode($response_transaction->resposta);
		$final_response = json_encode($response_transaction, true);

	}

	atualizaTransacao($con, $dados);
	echo json_encode($response_transaction);
	return json_encode($response_transaction);
} else {
	echo 'token inválido';
}

function atualizaTransacao($con, $dados)
{
	$update_transaction_query ="UPDATE sys_vendas_transacoes_tef
	SET transacao_nit = '".$dados->transacao_nit."',
	transacao_status = '".$dados->transacao_status."',
	transacao_username = '".$dados->username."'
	WHERE transacao_id = '".$dados->transaction_id."'";
	$result_transaction = mysql_query($update_transaction_query);
}

function doTransactionCurl($dataTransaction, $url, $CURLOPT_HTTPHEADER)
{
    // CERTIFICADO HACK
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
    // FIM CERTIFICADO HACK

    @$data_corpo = "{
        \"amount\" : \"{$dataTransaction->amount}\",
        \"installments\" : \"1\",
        \"installment_type\" : \"4\",
        \"card\" : {
            \"number\" : \"{$dataTransaction->card->number}\",
            \"expiry_date\" : \"{$dataTransaction->card->expiry_date}\"
        }
    }";

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 60,
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

    if ($erro){
        return $erro;
    }else{
        return $resposta;
    }
}

function getStatus($url, $CURLOPT_HTTPHEADER)
{		
    // CERTIFICADO HACK
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
    // FIM CERTIFICADO HACK

	$curl = curl_init();
	curl_setopt_array($curl, array(
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
	));

	$response->resposta = json_decode(curl_exec($curl));

	$response->erro = curl_error($curl);
	$response->getstatus_error_number = curl_errno($curl);

	curl_close($curl);
	return $response->resposta;
}
?>