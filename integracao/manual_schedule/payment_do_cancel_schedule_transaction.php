<?php
	include("variaveis_fixas.php");
	if ($_GET['token'] === 'EsearR31234fpssa0vfc9o') {

		$dados = $_POST;

		$payment->transacao_agendamento_seid = $dados['transacao_agendamento_seid'];
		$payment->novo_status = "INA"; //inativar


		$url = $link_prefixo.'/e-sitef/api/v1/schedules/edits/'.$payment->transacao_agendamento_seid;
		//url Homologação
		$metodo = 'PUT';

		@$data_corpo = "{
			\"status\" : \"{$payment->novo_status}\"
		}";

		$orignal_parse = parse_url($url, PHP_URL_HOST);
		$get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
		$read = stream_socket_client(
			"ssl://".$orignal_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get
		);
		$cont = stream_context_get_params($read);
		openssl_x509_export($cont["options"]["ssl"]["peer_certificate"],$certificado);

		//echo $certificado . '<br><br>';

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
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


		$resposta = curl_exec($curl);

		$erro = curl_error($curl);

		curl_close($curl);

		$respostaJson = json_decode($resposta);

		if ($erro) {
			echo 'erro';
			echo json_encode($erro, true);
		} else {
			echo $resposta;
		}
	} else {
		echo 'token inválido';
	}
?>