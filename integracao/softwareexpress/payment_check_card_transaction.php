<?php
include("get_erros.php");
include("variaveis_fixas.php");

header("Access-Control-Allow-Origin: *");
if ($_GET['token'] === 'EsearR31234fpssa0vfc9o') {

	include("../connect_seguro.php");

	// Inicializar objetos necessários
	$result = new stdClass();
	$payment = new stdClass();
	$card = new stdClass();

	$dados = $_POST;

	// Adicionar suporte ao vendas_id
	if (isset($dados['vendas_id']) && $dados['vendas_id']) {
		$dados['transacao_venda_id'] = $dados['vendas_id'];
	} else {
		$dados['transacao_venda_id'] = 0;
	}

	if ($_GET['verificar_novamente'] && isset($dados['transacao_id'])) {
		$result_transactions = mysql_query("SELECT *
    		FROM sys_vendas_transacoes_tef
   	 		WHERE transacao_id = '" . $dados['transacao_id'] . "';")
			or die(mysql_error());

		$row_transaction = mysql_fetch_assoc($result_transactions);

		$dados['card']['adm'] = $row_transaction['transacao_cartao_adm'];
		$dados['card']['num'] = $row_transaction['transacao_cartao_num'];

		$dados['card']['validade_mes'] = $row_transaction['transacao_cartao_validade_mes'];
		$dados['card']['validade_ano'] = $row_transaction['transacao_cartao_validade_ano'];
	}

	if (empty($dados['nit'])) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Erro durante a verificação. </span>';
		echo json_encode($result);
		die();
	}
	if (empty($dados['card']['num'])) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Preencha corretamente o número do cartão. </span>';
		echo json_encode($result);
		die();
	}

	if (empty($dados['card']['adm'])) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Selecione a administradora do cartão. </span>';
		echo json_encode($result);
		die();
	}

	if (empty($dados['card']['validade_mes'])) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Preencha corretamente o mês da validade. </span>';
		echo json_encode($result);
		die();
	}

	if (empty($dados['card']['validade_ano'])) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Preencha corretamente o ano da validade. </span>';
		echo json_encode($result);
		die();
	}

	$payment->codigo_nit = mysql_real_escape_string($dados['nit']);
	//url Homologação
	//$url ='https://esitef-ec.softwareexpress.com.br/e-sitef/api/v1/payments/'.$payment->codigo_nit.'/cards';
	// Tentar a URL comentada que pode ser a correta
	$url = $link_prefixo . '/e-sitef/api/v1/payments/' . $payment->codigo_nit . '/cards';

	$metodo = 'POST'; // API espera POST

	/* ----- DADOS DA FINALIZAÇÃO DA TRANSAÇÃO ----- */
	$card->number = $dados['card']['num'];
	$card->validade = $dados['card']['validade_mes'] . "" . substr($dados['card']['validade_ano'], 2);
	$card->authorizer_id = $dados['card']['adm'];
	$card->security_code = "666";

	/* ----- DADOS DA FINALIZAÇÃO DA TRANSAÇÃO ----- */

	@$data_corpo = "{
			\"card\" : {
				\"number\" : \"{$card->number}\",
				\"expiry_date\" : \"{$card->validade}\"
			},
			\"authorizer_id\":\"$card->authorizer_id\",
			\"installments\":\"1\",
			\"installment_type\":\"4\"
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

	//echo $certificado . '<br><br>';


	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => $metodo,
		CURLOPT_POSTFIELDS => ($data_corpo != '') ? $data_corpo : '',
		CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 0
	));

	$resposta = curl_exec($curl);
	$erro = curl_error($curl);
	curl_close($curl);

	// Verificar se a requisição foi bem-sucedida
	if ($resposta === FALSE || $erro) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Erro de conexão com o servidor de pagamento ! </span>';
		echo json_encode($result);
		return;
	}

	$respostaJson = json_decode($resposta);

	// Verificar se o JSON foi decodificado corretamente
	if (json_last_error() !== JSON_ERROR_NONE) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Erro ao processar resposta do servidor ! </span>';
		echo json_encode($result);
		return;
	}

	// Verificar se a estrutura da resposta está correta
	if (!isset($respostaJson->payment)) {
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> Resposta inválida do servidor ! </span>';
		echo json_encode($result);
		return;
	}

	if (isset($respostaJson->code) && $respostaJson->code == "0") {
		// Sucesso - cartão válido
		$update_transaction_query = "UPDATE sys_vendas_transacoes_tef
		SET transacao_authorizer_id = '" . mysql_real_escape_string($respostaJson->card->authorizer_id) . "',
		transacao_status = 'VER',
		transacao_venda_id = '" . mysql_real_escape_string($dados['transacao_venda_id']) . "'
		WHERE transacao_nit = " . "'$payment->codigo_nit'";

		$result_transaction = mysql_query($update_transaction_query);

		if ($result_transaction) {
			$result->valid = 'success';
			$result->status = $respostaJson->payment->status;
			$result->data = $respostaJson;
			$result->authorizer_id = $respostaJson->card->authorizer_id;
			$result->message = '<span style="color: green; font-weight: bolder;"> Cartão pronto para utilização.<br>' . $respostaJson->message . '</span>';
		} else {
			$result->valid = 'erro';
			$result->message = '<span style="color: red; font-weight: bolder;"> Ocorreu um erro tente novamente.<br>' . $respostaJson->message . '</span>';
		}
	} else {
		// Erro - cartão inválido
		$update_transaction_query = "UPDATE sys_vendas_transacoes_tef
		SET transacao_authorizer_id = '" . mysql_real_escape_string($respostaJson->card->authorizer_id) . "',
		transacao_status = 'INV',
		transacao_venda_id = '" . mysql_real_escape_string($dados['transacao_venda_id']) . "'
		WHERE transacao_nit = " . "'$payment->codigo_nit'";

		$result_transaction = mysql_query($update_transaction_query);

		$result->status = $respostaJson->payment->status;
		$result->data = $respostaJson;
		$result->authorizer_id = $respostaJson->card->authorizer_id;
		$result->valid = 'erro';
		$result->message = '<span style="color: red; font-weight: bolder;"> ' . $respostaJson->message . '</span>';
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
							'" . getTransactionIdByNit($dados['nit']) . "',
							'" . mysql_real_escape_string($dados['user_id']) . "',
							'" . mysql_real_escape_string($dados['cpf']) . "',
							NOW(),
							'" . mysql_real_escape_string($respostaJson->code) . "',
							'" . mysql_real_escape_string($respostaJson->payment->status) . "',
							'" . mysql_real_escape_string($resposta) . "'
							)";
	mysql_query($sql_insert_log);

	echo json_encode($result);
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
?>
