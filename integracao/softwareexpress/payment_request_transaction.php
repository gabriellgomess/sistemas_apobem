<?php
// debug
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


header("Access-Control-Allow-Origin: *");
include("get_erros.php");
include("variaveis_fixas.php");

header("Access-Control-Allow-Origin: *");
if ($_GET['token'] === 'EsearR31234fpssa0vfc9o') {

	include("../connect_seguro.php");

	$payment = new stdClass();
	$payment->schedule = new stdClass();
	$payment->additional_data = new stdClass();
	$payment->additional_data->payer = new stdClass();
	$result = new stdClass();

	$url = $link_prefixo . '/e-sitef/api/v1/transactions/';
	$metodo = 'POST';

	$dados = $_POST;

	if ($dados['venda_id']) {
		$dados['transacao_venda_id'] = $dados['venda_id'];
	} else {
		$dados['transacao_venda_id'] = null;
	}

	if ($dados['vendas_cartao_adm']) {
		$dados['card']['adm'] = $dados['vendas_cartao_adm'];
	}

	if ($_GET['verificar_novamente'] && $dados['transacao_id']) {

		$result_transactions = mysql_query("SELECT *
    		FROM sys_vendas_transacoes_tef
   	 		WHERE transacao_id = '" . $dados['transacao_id'] . "';")
			or die(mysql_error());

		$row_transaction = mysql_fetch_assoc($result_transactions);

		$dados['transacao_venda_id'] = $row_transaction['transacao_venda_id'];
		$dados['cpf'] = $row_transaction['transacao_cliente_cpf'];
		$dados['valor'] = $row_transaction['transacao_valor'];
		$dados['plano'] = $row_transaction['transacao_tipo_plano'];
		$dados['card']['adm'] = $row_transaction['transacao_cartao_adm'];
		$dados['card']['band'] = $row_transaction['transacao_cartao_band'];
		$dados['card']['cvv'] = $row_transaction['transacao_cartao_cvv'];
		$dados['card']['num'] = $row_transaction['transacao_cartao_num'];
		$dados['card']['validade_mes'] = $row_transaction['transacao_cartao_validade_mes'];
		$dados['card']['validade_ano'] = $row_transaction['transacao_cartao_validade_ano'];
	}

	if (empty($dados['dia_debito'])) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Preencha o dia do débito. </span>';
		echo json_encode($result);
		die();
	}
	if (empty($dados['valor'])) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Preencha o valor. </span>';
		echo json_encode($result);
		die();
	}
	if (empty($dados['plano'])) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Escolha o tipo de plano. </span>';
		echo json_encode($result);
		die();
	}
	if (empty($dados['cpf'])) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> CPF não preenchido ou inválido. </span>';
		echo json_encode($result);
		die();
	}
	if (empty($dados['data_prox_pgt'])) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Necessário informar data para próximo pagamento.</span>';
		echo json_encode($result);
		die();
	}

	$dados['transacao_valor'] = $dados['valor'];
	$dados['valor'] = sprintf('%0.2f', $dados['valor']);
	$dados['valor'] = str_replace(".", "", $dados['valor']);

	// Adicionar estas linhas antes da linha 89:
	$payment = new stdClass();
	$payment->schedule = new stdClass();
	$payment->additional_data = new stdClass();
	$payment->additional_data->payer = new stdClass();

	/* ----- DADOS DA REQUISIÇÃO DA TRANSAÇÃO ----- */
	$payment->installments = '1';/* = número de parcelas */
	$payment->installment_type = '4';/* = tipo pagamento, 4 é avista */
	$payment->amount = $dados['valor']; /* = valor da parcela, exemplo 19,90 R$ deve ser representado como 1990 sem ponto nem virgulas*/
	/*$payment->authorizer_id = '2';*/

	$payment->schedule->initial_date = $dados['data_prox_pgt'];/* = dia dos proximos pagamento*/;
	$payment->schedule->installments = '1';/* = número de parcelas */
	$payment->schedule->installment_type = '4'; /* = tipo pagamento, 4 é avista */
	$payment->schedule->soft_descriptor = $dados['plano']; /* = tipo de plano */

	$payment->additional_data->payer->store_identification = $dados['cpf'];
	/*store_identification = Identificação do comprador para armazenamento de cartão.*/
	$payment->additional_data->payer->identification_number = $dados['cpf'];
	/*identification_number = Documento de identificação do comprador (CPF/RG).*/

	/* ----- DADOS DA REQUISIÇÃO DA TRANSAÇÃO ----- */
	$transaction_type = "preauthorization";

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
				transacao_cartao_validade_ano)
				VALUES (
				'',
				'" . mysql_real_escape_string($dados['cpf']) . "',
				'" . mysql_real_escape_string($dados['username']) . "',
				'" . mysql_real_escape_string($dados['user_id']) . "',
				'" . mysql_real_escape_string($dados['token']) . "',
				'" . mysql_real_escape_string($dados['transacao_valor']) . "',
				'" . mysql_real_escape_string($dados['transacao_venda_id']) . "',
				NOW(),
				'" . mysql_real_escape_string($dados['dia_debito']) . "',
				'" . mysql_real_escape_string($dados['plano']) . "',
				'" . mysql_real_escape_string($dados['card']['adm']) . "',
				'" . mysql_real_escape_string($dados['card']['band']) . "',
				'" . mysql_real_escape_string($dados['card']['cvv']) . "',
				'" . mysql_real_escape_string($dados['card']['num']) . "',
				'" . mysql_real_escape_string($dados['card']['validade_mes']) . "',
				'" . mysql_real_escape_string($dados['card']['validade_ano']) . "'
			)";

	$result_transaction = mysql_query($insere_transaction_query);
	$transaction_id = mysql_insert_id();

	$data_corpo = "{
			\"order_id\" : \"{$transaction_id}\",
			\"amount\" : \"{$payment->amount}\",
			\"transaction_type\" : \"{$transaction_type}\"
		}";

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
	//echo "json: ".$respostaJson;
	if ($erro) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Ocorreu um erro durante a verificação ! ' . getError($respostaJson->code) . '</span>';
		echo json_encode($result);
	} else {
		$update_transaction_query = "UPDATE sys_vendas_transacoes_tef SET
				transacao_nit = '" . mysql_real_escape_string($respostaJson->pre_authorization->nit) . "',
				transacao_status = '" . mysql_real_escape_string($respostaJson->pre_authorization->status) . "'
				WHERE transacao_id = '" . $transaction_id . "';";

		$update_transaction = mysql_query($update_transaction_query);


		if ($respostaJson->code == "0") {
			if ($update_transaction) {
				$result->data = $respostaJson;
				$result->valid = 'success';
				$result->nit = $respostaJson->pre_authorization->nit;
				$result->message = 'ok';
			} else {
				$result->json = $respostaJson;
				$result->valid = 'erro';
				$result->message = '<span style="color: red; font-weight: bolder;"> Ocorreu um erro tente novamente. </span>';
				$result->message .= '<br>Erro:' . mysql_error();
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
							   	user_id,
							   	clients_cpf,
							   	data,
							   	erro_cod,
							   	status,
							   	response_json
							   )
							   VALUES
							   (
								'" . $transaction_id . "',
								'" . $dados['user_id'] . "',
								'" . $dados['cpf'] . "',
								NOW(),
								'" . $respostaJson->pre_authorization->authorizer_code . "',
								'" . $respostaJson->pre_authorization->status . "',
								'" . mysql_real_escape_string($resposta) . "'
								)";
		mysql_query($sql_insert_log);

		$result = json_encode($result);
		echo $result;
	}
} else {
	echo 'token inválido';
}
