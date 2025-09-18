<?php
require('../../connect_seguro.php');
include("variaveis_fixas.php");

	if (true) {
		
		$dados = $_POST;

		$dados['transacao_agendamento_sid'] = "40df378e1126dd3618f153ab8aa1de19d97896eca1386928722776835dd1176c"; // preencher esses dados para efetivação manual da recorrência.
		$dados['transacao_cartao_num'] = "xxxxxxxxxxxxxxx"; // preencher esses dados para efetivação manual da recorrência.
		$dados['transacao_data_exp'] = "1023"; // preencher esses dados para efetivação manual da recorrência.

	
		$payment->codigo_nit = $dados['transacao_agendamento_sid'];
		//url Homologação
		$url = $link_prefixo.'/e-sitef/api/v1/schedules/'.$payment->codigo_nit;
		$url_getstatus = $link_prefixo.'/e-sitef/api/v1/transactions/'.$payment->codigo_nit;
		//url Homologação
		$metodo = 'POST';
		$metodo_getstatus = 'GET';
		/* ----- DADOS DA FINALIZAÇÃO DA TRANSAÇÃO ----- */
		$card->number = $dados['transacao_cartao_num'];
		$card->expiry_date = $dados['transacao_data_exp'];
		
		/* ----- DADOS DA FINALIZAÇÃO DA TRANSAÇÃO ----- */

		@$data_corpo = "{
			\"card\" : {
				\"number\" : \"{$card->number}\",
				\"expiry_date\" : \"{$card->expiry_date}\"
			}
		}";

		$orignal_parse = parse_url($url, PHP_URL_HOST);
		$get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
		$read = stream_socket_client(
			"ssl://".$orignal_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get
		);
		$cont = stream_context_get_params($read);
		openssl_x509_export($cont["options"]["ssl"]["peer_certificate"],$certificado);

		//echo $certificado . '<br><br>';

		// Tenta realizar a transação via curl
		$response_transaction = doTransactionCurl($url, $metodo, $data_corpo, $CURLOPT_HTTPHEADER);

		// Caso a transação não tenha obtido sucesso e o erro foi 28 (tempo limite de resposta excedido)
		if($response_transaction->transaction_error_number !='0' && $response_transaction->transaction_error_number == '28')
		{
			$tentativa = 1;
			$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_status='85' WHERE vendas_id='$vendas_id' ") or die(mysql_error());
			while($tentativa <= 3 )
			{
				// tenta verifica se a transação chegou a ocorrer e com qual resultado, afinal não foi possível obter a resposta antes.
				$response_getstatus = getStatus($url_getstatus, $metodo_getstatus, $CURLOPT_HTTPHEADER);
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
			echo $final_response;

		}else{ // se obteve sucesso ou um erro diferente de 28...
			$log_resposta = $response_transaction->resposta;
			$log_resposta_ecoded = json_encode($response_transaction->resposta);
			$final_response = json_encode($response_transaction, true);
			echo $final_response;
		}

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
					'".getTransactionIdByNit($dados['transacao_agendamento_sid'])."',
					NOW(),
					'".$log_resposta->code."',
					'".$log_resposta->schedule->status."',
					'".mysql_real_escape_string($log_resposta_ecoded)."'
					)";
		mysql_query($sql_insert_log);
	} else {
		echo 'token inválido';
	}
?>

<?php

function getTransactionIdByNit($sid){
	$sql_tsid = "SELECT transacao_id FROM sys_vendas_transacoes_tef WHERE transacao_agendamento_sid = '".$sid."' LIMIT 0,1;";
	$result_tsid = mysql_query($sql_tsid) or die(mysql_error());
	$row_tsid = mysql_fetch_assoc($result_tsid);
	return $row_tsid['transacao_id'];
}

function doTransactionCurl($url, $metodo, $data_corpo, $CURLOPT_HTTPHEADER)
{
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
		CURLOPT_HTTPHEADER => array(
			"Accept: application/json",
			"merchant_id: 34064579000178",
			"merchant_key: 7B9117D92A089159AF650D2C28D73C17A6331938C477CC088E013AED2446EDAF",
			"Content-Type: application/json",
			"cache-control: no-cache"
			),
	));

	$response->resposta = json_decode(curl_exec($curl));

	$response->erro = curl_error($curl);
	$response->getstatus_error_number = curl_errno($curl);

	curl_close($curl);
	return $response;
}
?>