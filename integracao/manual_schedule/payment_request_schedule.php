<?php
	include("get_erros.php");
	include("variaveis_fixas.php");

	header("Access-Control-Allow-Origin: *");
	if (true) {

		include("../../portal/sistema/connect_seguro.php");
		$url = $link_prefixo.'/e-sitef/api/v1/transactions/';

		$metodo = 'POST';
		$dados = $_POST;

		$dados['transacao_valor'] = "5850"; // Preencher esses dados para realização manual da solicitação de recorrência.
		$dados['cpf'] = "xxxxxxxxxxx"; // Preencher esses dados para realização manual da solicitação de recorrência.
		$dados['transacao_venda_id'] = "xxxxxxx"; // Preencher esses dados para realização manual da solicitação de recorrência.
		$dados['authorizer_id'] = "2"; // Preencher esses dados para realização manual da solicitação de recorrência.
		$dados['plano'] = "Apobem Famí­lia Gold R$ 58,50"; // Preencher esses dados para realização manual da solicitação de recorrência.

		$dados['username'] = "cristiane.scheibler"; // Preencher esses dados para realização manual da solicitação de recorrência.
		$dados['user_id'] = "1371"; // Preencher esses dados para realização manual da solicitação de recorrência.
		$dados['token'] = "EsearR31234fpssa0vfc9o"; // Preencher esses dados para realização manual da solicitação de recorrência.

		if (empty($dados['transacao_valor'])) {
			$result->valid = 'erro';
			$result->message = '<span style="color: red; font-weight: bolder;"> Preencha o valor. </span>';
			echo json_encode($result);
			die();
		}
		if (empty($dados['cpf'])) {
			$result->valid = 'erro';
			$result->message = '<span style="color: red; font-weight: bolder;"> CPF não preenchido ou inválido. </span>';
			echo json_encode($result);
			die();
		}

		$dados['transacao_valor'] = sprintf('%0.2f', $dados['transacao_valor']);
		$dados['transacao_valor'] = str_replace(".", "", $dados['transacao_valor']);

		/* ----- DADOS DA REQUISIÇÃO DA TRANSAÇÃO ----- */
		$payment->installments = '1';/* = número de parcelas */
		$payment->installment_type = '4';/* = tipo pagamento, 4 é avista */
		$payment->schedule->amount = $dados['transacao_valor']; /* = valor da parcela, exemplo 19,90 R$ deve ser representado como 1990 sem ponto nem virgulas*/
		$payment->order_id = $dados['transacao_venda_id']; /* Número sequencial único para cada pedido, criado pela loja.*/
		$payment->authorizer_id = $dados['authorizer_id']; /* Código da autorizadora no e-SiTef.*/
		/*$payment->authorizer_id = '2';*/

		$payment->schedule->initial_date = date('d/m/Y',strtotime('+30 days',strtotime(date('Y-m-d'))));/* = dia dos proximos pagamento*/;

	    if(substr($payment->schedule->initial_date,0,2) == '29'){
	        $payment->schedule->initial_date = date('d/m/Y', strtotime('+33 days',strtotime(date('Y-m-d'))));
	    }
	    if(substr($payment->schedule->initial_date,0,2) == '30'){
	        $payment->schedule->initial_date = date('d/m/Y', strtotime('+32 days',strtotime(date('Y-m-d'))));
	    }
	    if(substr($payment->schedule->initial_date,0,2) == '31'){
	        $payment->schedule->initial_date = date('d/m/Y', strtotime('+31 days',strtotime(date('Y-m-d'))));
	    }

		$payment->schedule->installments = '1';/* = número de parcelas */
		$payment->schedule->installment_type = '4'; /* = tipo pagamento, 4 é avista */
		$payment->schedule->soft_descriptor = $dados['plano']; /* = tipo de plano */

		$payment->additional_data->payer->store_identification = $dados['cpf'];
		/*store_identification = Identificação do comprador para armazenamento de cartão.*/
		$payment->additional_data->payer->identification_number = $dados['cpf'];
		/*identification_number = Documento de identificação do comprador (CPF/RG).*/

		/* ----- DADOS DA REQUISIÇÃO DA TRANSAÇÃO ----- */

		@$data_corpo = "{
			\"order_id\" : \"{$payment->order_id}\",
			\"authorizer_id\" : \"{$payment->authorizer_id}\",
			\"schedule\" : {
				\"amount\" : \"{$payment->schedule->amount}\",
				\"do_payment_now\" : \"false\",
				\"initial_date\" : \"{$payment->schedule->initial_date}\"
			},
			\"additional_data\" : {
				\"payer\" : {
					\"store_identification\" : \"{$payment->order_id}\"
				}
			}
		}";

		$orignal_parse = parse_url($url, PHP_URL_HOST);
		$get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
		$read = stream_socket_client(
			"ssl://".$orignal_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get
		);
		$cont = stream_context_get_params($read);
		openssl_x509_export($cont["options"]["ssl"]["peer_certificate"],$certificado);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_FAILONERROR => true,
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

		$respostaJson = json_decode($resposta);

		curl_close($curl);

		print_r($respostaJson);

		if ($erro) {

			$result->valid = 'erro';
			$result->message = '<span style="color: red; font-weight: bolder;"> Ocorreu um erro durante a verificação ! '.getError($respostaJson->code).'</span>';
			echo json_encode($result);

		} else {
			$dia_debito = date('d',strtotime('+30 days',strtotime(date('d/m/Y'))));
			$insere_transaction_query ="INSERT INTO sys_vendas_transacoes_tef (transacao_nit,
				transacao_agendamento_sid,
				transacao_cliente_cpf,
				transacao_username,
				transacao_user_id,
				transacao_token,
				transacao_status,
				transacao_valor,
				transacao_venda_id,
				transacao_data,
				transacao_dia_debito,
				transacao_tipo_plano,
				transacao_cartao_adm,
				transacao_cartao_band)
				VALUES (
				'".mysql_real_escape_string($respostaJson->payment->nit)."',
				'".mysql_real_escape_string($respostaJson->schedule->sid)."',
				'".mysql_real_escape_string($dados['cpf'])."',
				'".mysql_real_escape_string($dados['username'])."',
				'".mysql_real_escape_string($dados['user_id'])."',
				'".mysql_real_escape_string($dados['token'])."',
				'".mysql_real_escape_string($respostaJson->schedule->status)."',
				'".mysql_real_escape_string($dados['transacao_valor'])."',
				'".mysql_real_escape_string($dados['transacao_venda_id'])."',
				NOW(),
				'".mysql_real_escape_string($dia_debito)."',
				'".mysql_real_escape_string($dados['plano'])."',
				'".mysql_real_escape_string($payment->authorizer_id)."',
				'".mysql_real_escape_string($payment->authorizer_id)."'
			)";

			$result_transaction = mysql_query($insere_transaction_query);
			$transaction_id = mysql_insert_id();

			if($respostaJson->code == "0") {
				if ($result_transaction) {
					$result->data = $respostaJson;
					$result->valid = 'success';
					$result->nit = $respostaJson->schedule->sid;
					$result->message ='ok';
				} else {
					$result->json = $respostaJson;
					$result->valid = 'erro';
					$result->message = '<span style="color: red; font-weight: bolder;"> Ocorreu um erro tente novamente. </span>';
				}
			} else {
				$result->initial_date = $payment->schedule->initial_date;
				$result->data = $respostaJson;
				$result->valid = 'erro';
				$result->message = '<span style="color: red; font-weight: bolder;"> Ocorreu um erro durante a verificação, verifique os campos. </span>';
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
								'".$transaction_id."',
								NOW(),
								'".$respostaJson->code."',
								'".$respostaJson->schedule->status."',
								'".mysql_real_escape_string($resposta)."'
								)";
			mysql_query($sql_insert_log);

			$result = json_encode($result);
			echo $result;
		}
	} else {
		echo 'token inválido';
	}
?>