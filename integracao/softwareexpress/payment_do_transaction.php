<?php

require('../connect_seguro.php');
include("variaveis_fixas.php");
header("Access-Control-Allow-Origin: *");

// Função para criar logs detalhados
function writeLog($message, $data = null)
{
	$logDir = __DIR__ . '/logs';
	if (!file_exists($logDir)) {
		mkdir($logDir, 0777, true);
	}

	$logFile = $logDir . '/payment_do_transaction_' . date('Y-m-d') . '.log';
	$timestamp = date('Y-m-d H:i:s');
	$logEntry = "[$timestamp] $message";

	if ($data !== null) {
		$logEntry .= "\nDATA: " . print_r($data, true);
	}

	$logEntry .= "\n" . str_repeat('-', 80) . "\n";

	file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

if ($_GET['token'] === 'EsearR31234fpssa0vfc9o') {

	$dados = $_POST;

	// LOG: Início da execução
	writeLog("🚀 INÍCIO - Processamento de pagamento iniciado", [
		'GET' => $_GET,
		'POST' => $_POST,
		'timestamp' => date('Y-m-d H:i:s')
	]);



	$payment = new stdClass();
	$card = new stdClass();

	//$payment->codigo_nit = "8bb40b25101a5d7322aec6a4642f3cc15f4f674540797cfe64afb5f8238583f2";
	$payment->codigo_nit = $dados['transacao_nit'];
	//url Homologação
	$url = $link_prefixo . '/e-sitef/api/v1/preauthorizations/capture/' . $payment->codigo_nit;
	$url_getstatus = $link_prefixo . '/e-sitef/api/v1/transactions/' . $payment->codigo_nit;
	//url Homologação
	$metodo = 'POST';
	$metodo_getstatus = 'GET';
	/* ----- DADOS DA FINALIZAÇÃO DA TRANSAÇÃO ----- */
	$card->number = $dados['transacao_cartao_num'];
	$card->expiry_date = $dados['transacao_data_exp'];
	$card->security_code = $dados['transacao_cartao_cvv'];
	$card->authorizer_id = $dados['authorizer_id'];
	$vendas_id = $dados['vendas_id'];
	//$card->number = "5281735839609922";
	//$card->expiry_date = "1220";
	//$card->security_code = "666";
	//$card->authorizer_id = "2";

	$dados['transacao_valor'] = sprintf('%0.2f', $dados['transacao_valor']);
	$dados['transacao_valor'] = str_replace(".", "", $dados['transacao_valor']);
	$payment->amount = $dados['transacao_valor'];

	// LOG: Dados processados
	writeLog("💳 DADOS - Dados da transação processados", [
		'transacao_nit' => $payment->codigo_nit,
		'amount_original' => $_POST['transacao_valor'] ?? 'N/A',
		'amount_formatted' => $payment->amount,
		'card_number' => substr($card->number, 0, 6) . '****' . substr($card->number, -4),
		'expiry_date' => $card->expiry_date,
		'authorizer_id' => $card->authorizer_id,
		'vendas_id' => $vendas_id
	]);

	/* ----- DADOS DA FINALIZAÇÃO DA TRANSAÇÃO ----- */

	@$data_corpo = "{
			\"amount\" : \"{$payment->amount}\",
			\"installments\" : \"1\",
			\"installment_type\" : \"4\",
			\"card\" : {
				\"number\" : \"{$card->number}\",
				\"expiry_date\" : \"{$card->expiry_date}\"
			}
		}";

	// LOG: JSON que será enviado
	writeLog("📤 REQUEST - JSON que será enviado para a API", [
		'url' => $url,
		'method' => $metodo,
		'json_body' => $data_corpo,
		'headers' => [
			'Content-Type' => 'application/json',
			'merchant_id' => $merchant_id,
			'merchant_key' => '[HIDDEN]'
		]
	]);

	$orignal_parse = parse_url($url, PHP_URL_HOST);
	$get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
	$read = stream_socket_client(
		"ssl://" . $orignal_parse . ":443",
		$errno,
		$errstr,
		30,
		STREAM_CLIENT_CONNECT,
		$get
	);
	$cont = stream_context_get_params($read);
	openssl_x509_export($cont["options"]["ssl"]["peer_certificate"], $certificado);

	//echo $certificado . '<br><br>';

	// Tenta realizar a transação via curl
	$response_transaction = doTransactionCurl($url, $metodo, $data_corpo, $CURLOPT_HTTPHEADER);

	// Caso a transação não tenha obtido sucesso e o erro foi 28 (tempo limite de resposta excedido)
	// LOG: Resposta da API recebida
	writeLog("📥 RESPONSE - Resposta da API recebida", [
		'transaction_error_number' => $response_transaction->transaction_error_number ?? 'N/A',
		'response_raw' => json_encode($response_transaction, JSON_PRETTY_PRINT),
		'timestamp' => date('Y-m-d H:i:s')
	]);

	if ($response_transaction->transaction_error_number != '0' && $response_transaction->transaction_error_number == '28') {
		// LOG: Erro de timeout - tentando verificar status
		writeLog("⏰ TIMEOUT - Erro 28 (timeout) detectado, tentando verificar status", [
			'vendas_id' => $vendas_id,
			'url_getstatus' => $url_getstatus,
			'tentativas_max' => 3
		]);

		$tentativa = 1;
		$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_status='85' WHERE vendas_id='$vendas_id' ") or die(mysql_error());
		while ($tentativa <= 3) {
			// LOG: Tentativa de verificação de status
			writeLog("🔄 RETRY - Tentativa $tentativa de verificação de status");

			// tenta verifica se a transação chegou a ocorrer e com qual resultado, afinal não foi possível obter a resposta antes.
			$response_getstatus = getStatus($url_getstatus, $metodo_getstatus, $CURLOPT_HTTPHEADER);

			// LOG: Resultado da tentativa
			writeLog("📊 RETRY RESULT - Resultado da tentativa $tentativa", [
				'getstatus_error_number' => $response_getstatus->getstatus_error_number ?? 'N/A',
				'response' => json_encode($response_getstatus, JSON_PRETTY_PRINT)
			]);

			if ($response_getstatus->getstatus_error_number != 0) {
				$tentativa++;
			} else {
				$tentativa = 4; // encerra while
			}
		}

		$response_getstatus->getstatus = true;
		$log_resposta = $response_getstatus->resposta;
		$log_resposta_ecoded = json_encode($response_getstatus->resposta);
		$final_response = json_encode($response_getstatus, true);

		// LOG: Resposta final (após timeout/retry)
		writeLog("✅ FINAL RESPONSE (TIMEOUT) - Resposta final após timeout", [
			'payment_status' => $log_resposta->payment->status ?? 'N/A',
			'authorizer_code' => $log_resposta->payment->authorizer_code ?? 'N/A',
			'message' => $log_resposta->message ?? 'N/A',
			'final_response' => $final_response
		]);

		echo $final_response;
	} else { // se obteve sucesso ou um erro diferente de 28...
		$log_resposta = $response_transaction->resposta;
		$log_resposta_ecoded = json_encode($response_transaction->resposta);
		$final_response = json_encode($response_transaction, true);

		// LOG: Resposta final (sucesso ou erro diferente de 28)
		writeLog("✅ FINAL RESPONSE (NORMAL) - Resposta final normal", [
			'transaction_error_number' => $response_transaction->transaction_error_number ?? 'N/A',
			'payment_status' => $log_resposta->payment->status ?? 'N/A',
			'authorizer_code' => $log_resposta->payment->authorizer_code ?? 'N/A',
			'message' => $log_resposta->message ?? 'N/A',
			'final_response' => $final_response
		]);

		echo $final_response;
	}

	$transaction_id = getTransactionIdByNit($dados['transacao_nit']);
	$authorizer_code = $log_resposta->payment->authorizer_code ?? '';
	$payment_status = $log_resposta->payment->status ?? '';

	// LOG: Dados que serão salvos no banco
	writeLog("💾 DATABASE - Salvando log no banco de dados", [
		'transaction_id' => $transaction_id,
		'authorizer_code' => $authorizer_code,
		'payment_status' => $payment_status,
		'response_json_length' => strlen($log_resposta_ecoded)
	]);

	$sql_insert_log = "INSERT INTO sys_vendas_transacoes_tef_log 
				   (
				   	transacao_id,
				   	data,
				   	erro_cod,
				   	status,
				   	response_json
				   )
				   VALUES
				   (
					'" . $transaction_id . "',
					NOW(),
					'" . mysql_real_escape_string($authorizer_code) . "',
					'" . mysql_real_escape_string($payment_status) . "',
					'" . mysql_real_escape_string($log_resposta_ecoded) . "'
					)";
	$result_log = mysql_query($sql_insert_log);

	// LOG: Resultado da inserção no banco
	writeLog("💾 DATABASE RESULT - Resultado da inserção no banco", [
		'success' => $result_log ? 'SIM' : 'NÃO',
		'mysql_error' => $result_log ? 'Nenhum' : mysql_error(),
		'sql_query' => $sql_insert_log
	]);

	// LOG: Fim do processamento
	writeLog("🏁 FIM - Processamento de pagamento finalizado", [
		'timestamp' => date('Y-m-d H:i:s'),
		'final_status' => $payment_status,
		'execution_time' => 'N/A'
	]);
} else {
	echo 'token inválido';
}
?>

<?php

function getTransactionIdByNit($nit)
{
	$sql_tsid = "SELECT transacao_id FROM sys_vendas_transacoes_tef WHERE transacao_nit = '" . $nit . "' LIMIT 0,1;";
	$result_tsid = mysql_query($sql_tsid) or die(mysql_error());
	$row_tsid = mysql_fetch_assoc($result_tsid);
	return $row_tsid['transacao_id'];
}

function doTransactionCurl($url, $metodo, $data_corpo, $CURLOPT_HTTPHEADER)
{
	$response = new stdClass();
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		//CURLOPT_USERPWD => 'usuario:senha',
		CURLOPT_CUSTOMREQUEST => $metodo,
		//CURLOPT_POSTFIELDS => (http_build_query($data_url) != '') ? http_build_query($data_url) : '', // url
		CURLOPT_POSTFIELDS => ($data_corpo != '') ? $data_corpo : '', // corpo do envio
		//CURLINFO_CONTENT_TYPE => 'application/x-www-form-urlencoded"',
		//===========================
		// Certificado SSL do servidor remoto
		//=========================== 
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 0,
		//CURLOPT_CAINFO => $certificado,
		//===========================
		CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER
	));

	$response->resposta = json_decode(curl_exec($curl));

	$response->erro = curl_error($curl);
	$response->transaction_error_number = curl_errno($curl);

	curl_close($curl);
	return $response;
}

function getStatus($url, $metodo, $CURLOPT_HTTPHEADER)
{
	$response = new stdClass();
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		//CURLOPT_USERPWD => 'usuario:senha',
		CURLOPT_CUSTOMREQUEST => $metodo,
		//CURLOPT_POSTFIELDS => (http_build_query($data_url) != '') ? http_build_query($data_url) : '', // url
		//CURLOPT_POSTFIELDS => ($data_corpo != '') ? $data_corpo : '', // corpo do envio
		//CURLINFO_CONTENT_TYPE => 'application/x-www-form-urlencoded"',
		//===========================
		// Certificado SSL do servidor remoto
		//=========================== 
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 0,
		//CURLOPT_CAINFO => $certificado,
		//===========================
		CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER
	));

	$response->resposta = json_decode(curl_exec($curl));

	$response->erro = curl_error($curl);
	$response->getstatus_error_number = curl_errno($curl);

	curl_close($curl);
	return $response;
}
?>