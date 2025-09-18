<?php
require('../../connect_seguro.php');
include("variaveis_fixas.php");

	if ($_GET['token'] === 'EsearR31234fpssa0vfc9o') {
		
		$dados = $_POST;

		//$payment->codigo_nit = "8bb40b25101a5d7322aec6a4642f3cc15f4f674540797cfe64afb5f8238583f2";
		$payment->codigo_nit = $dados['transacao_nit'];
		//url Homologação
		$url = $link_prefixo.'/e-sitef/api/v1/preauthorizations/capture/'.$payment->codigo_nit;
		$url_getstatus = $link_prefixo.'/e-sitef/api/v1/transactions/'.$payment->codigo_nit;
		//url Homologação
		$metodo = 'POST';
		$metodo_getstatus = 'GET';
		/* ----- DADOS DA FINALIZAÇÃO DA TRANSAÇÃO ----- */
		$card->number = $dados['transacao_cartao_num'];
		$card->expiry_date = $dados['transacao_data_exp'];
		//$card->security_code = $dados['transacao_cartao_cvv'];
		$card->authorizer_id = $dados['authorizer_id'];
		$vendas_id = $dados['vendas_id'];
		$card->number = "5281735839609922";
		$card->expiry_date = "1220";
		//$card->security_code = "666";
		$card->authorizer_id = "2";

		$dados['transacao_valor'] = sprintf('%0.2f', $dados['transacao_valor']);
		$dados['transacao_valor'] = str_replace(".", "", $dados['transacao_valor']);
		$payment->amount = $dados['transacao_valor'];
		
		/* ----- DADOS DA FINALIZAÇÃO DA TRANSAÇÃO ----- */

		@$data_corpo = "{
			\"amount\" : \"{$payment->amount}\",
			\"installments\" : \"1\",
			\"installment_type\" : \"4\",
			\"card\" : {
				\"number\" : \"{$card->number}\",
				\"expiry_date\" : \"{$card->validade}\"
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
		$response_transaction = doTransactionCurl($url, $metodo, $data_corpo);

		// Caso a transação não tenha obtido sucesso e o erro foi 28 (tempo limite de resposta excedido)
		if($response_transaction->transaction_error_number !='0' && $response_transaction->transaction_error_number == '28')
		{
			$tentativa = 1;
			$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_status='85' WHERE vendas_id='$vendas_id' ") or die(mysql_error());
			while($tentativa <= 3 )
			{
				// tenta verifica se a transação chegou a ocorrer e com qual resultado, afinal não foi possível obter a resposta antes.
				$response_getstatus = getStatus($url_getstatus, $metodo_getstatus);
				if($response_getstatus->getstatus_error_number != 0)
				{
					$tentativa++;
				}else{
					$tentativa=4; // encerra while
				}
			}

			$response_getstatus->getstatus = true;
			echo json_encode($response_getstatus, true);
		}else{ // se obteve sucesso ou um erro diferente de 28...
			echo json_encode($response_transaction, true);
		}

		// $r = json_encode($response_getstatus, true);
		// $r = json_decode($r);
		// echo "<pre>";
		// print_r(json_decode($r->resposta));
		// echo "</pre>";

// if($r->getstatus)
// {
// 	if($r->getstatus_error_number==0){
// 		// a curl repondeu com sucesso nas tentativas de getStatus.
// 		echo "sucesso nas tentativas de get status."."<br>";
// 	}else{
// 		// a curl falhou nas tentativas de getStatus
// 		echo $r->erro."<br>";
// 	}
// }

		// if ($response->erro) {
		// 	echo 'erro';
		// 	echo json_encode($response->erro, true);
		// } else {
		// 	echo $response->resposta;
		// }

	} else {
		echo 'token inválido';
	}
?>

<?php 
function doTransactionCurl($url, $metodo, $data_corpo)
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

function getStatus($url, $metodo)
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
		CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER
	));

	$response->resposta = json_decode(curl_exec($curl));

	$response->erro = curl_error($curl);
	$response->getstatus_error_number = curl_errno($curl);

	curl_close($curl);
	return $response;
}
?>