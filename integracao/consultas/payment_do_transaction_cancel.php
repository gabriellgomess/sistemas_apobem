<?php
header("Access-Control-Allow-Origin: *");

// require('../../connect_seguro.php');
include("variaveis_fixas.php");


	$link_prefixo = "https://esitef-ec.softwareexpress.com.br";
	
		$pedido = $_POST['pedido'];
		$user_id = $_POST['user_id'];

		$metodo = 'PUT';
		/* ----- DADOS DA FINALIZAÇÃO DA TRANSAÇÃO ----- */
		@$data_corpo = "{			
		}";

		// //echo $certificado . '<br><br>';
		// $sql = "SELECT nit, pedido, nsu, codigo_loja FROM sys_vendas_transacoes_cancelamento WHERE pedido = '".$pedido."' ORDER BY data DESC LIMIT 0,1;";
		// $result = mysql_query($sql);

		// $row = mysql_fetch_array($result);
			$nit_cancelamento = "0b22bdf62521870ae942fcdeaf3c0ff499f13df061075064e141fc349f6aa14d";

			$url = $link_prefixo.'/e-sitef/api/v1/cancellations/'.$nit_cancelamento;

			carregaCertificado($url);
			$resposta = doTransactionCurl($url, $metodo, $data_corpo, $CURLOPT_HTTPHEADER);

			//$retornoGetTransation = getTransactionDataById($row['pedido']);
			// echo "<pre>";		
			// print_r($retornoGetTransation);
			// echo "</pre>";			

			// if($retornoGetTransation->erro == 0)
			// {
			// 	$transactionData = $retornoGetTransation->transacao;
			// 	// Tenta realizar a transação via curl
			// 	$respostaJson = doTransactionCurl($url, $metodo, $data_corpo, $CURLOPT_HTTPHEADER);
			// 	 // echo "<pre>";		
			// 	 // print_r($respostaJson);
			// 	 // echo "</pre>";

			// 	if(!$respostaJson->erro)
			// 	{					
			// 		registraLog($respostaJson, $transactionData, $user_id);					
			// 		atualizaTransacaoTef($transactionData);

			// 		$sql_delete = "DELETE FROM sys_vendas_transacoes_cancelamento WHERE pedido = '".$pedido."';";
			// 		$result_delete = mysql_query($sql_delete);

			// 		$resposta->erro = 0;
			// 		$resposta->mensagem = "Cancelado com sucesso.";

			// 	}else{
			// 		$resposta->erro = 1;
			// 		$resposta->mensagem = "Erro: ".$respostaJson->erro." ".$respostaJson->transaction_error_number;
			// 	}
			// }else{
			// 	$resposta->erro = 1;
			// 	$resposta->mensagem = "Erro: ".$retornoGetTransation->mensagem;
			// }

	echo json_encode($resposta);
?>

<?php
function carregaCertificado($url)
{
	$orignal_parse = parse_url($url, PHP_URL_HOST);
	$get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
	$read = stream_socket_client(
		"ssl://".$orignal_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get
	);
	$cont = stream_context_get_params($read);
	openssl_x509_export($cont["options"]["ssl"]["peer_certificate"],$certificado);
}

function atualizaTransacaoTef($transactionData)
{
	//Como a api não retorna o status, mas sei que quando o retorno é sucesso no cancelamento ele fica 'EST'
	$sql_update = "UPDATE sys_vendas_transacoes_tef SET transacao_status= 'EST' WHERE transacao_id = '".$transactionData->transacao_id."';";
	mysql_query($sql_update);
}

function registraLog($respostaJson, $transactionData, $user_id)
{
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
							'".$transactionData->transacao_id."',
							'".$user_id."',
							'".$transactionData->transacao_cliente_cpf."',
							NOW(),
							'".$respostaJson->code."',
							'',
							'".mysql_real_escape_string(json_encode($respostaJson))."'
							)";
	mysql_query($sql_insert_log);
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
		CURLOPT_CUSTOMREQUEST => $metodo,
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

function getTransactionDataById($transacao_id)
{
	$sql = "SELECT * FROM sys_vendas_transacoes_tef WHERE transacao_id = '".$transacao_id."' LIMIT 0,1";
	$result = mysql_query($sql);
	if(mysql_num_rows($result))
	{
		$row = mysql_fetch_assoc($result);

		$retorno->erro = 0;
		$retorno->mensagem = "Transação localizada.";

		$retorno->transacao->transacao_id = $row['transacao_id'];
		$retorno->transacao->transacao_nit = $row['transacao_nit'];
		$retorno->transacao->transacao_agendamento_sid = $row['transacao_agendamento_sid'];
		$retorno->transacao->transacao_agendamento_seid = $row['transacao_agendamento_seid'];
		$retorno->transacao->transacao_merchant_usn = $row['transacao_merchant_usn'];
		$retorno->transacao->transacao_authorizer_id = $row['transacao_authorizer_id'];
		$retorno->transacao->transacao_venda_id = $row['transacao_venda_id'];
		$retorno->transacao->transacao_cliente_cpf = $row['transacao_cliente_cpf'];
		$retorno->transacao->transacao_username = $row['transacao_username'];
		$retorno->transacao->transacao_user_id = $row['transacao_user_id'];
		$retorno->transacao->transacao_token = $row['transacao_token'];
		$retorno->transacao->transacao_status = $row['transacao_status'];
		$retorno->transacao->transacao_authorizer_message = $row['transacao_authorizer_message'];
		$retorno->transacao->transacao_data = $row['transacao_data'];
		$retorno->transacao->transacao_data_confirmacao = $row['transacao_data_confirmacao'];
		$retorno->transacao->transacao_dia_debito = $row['transacao_dia_debito'];
		$retorno->transacao->transacao_valor = $row['transacao_valor'];
		$retorno->transacao->transacao_tipo_plano = $row['transacao_tipo_plano'];
		$retorno->transacao->transacao_cartao_adm = $row['transacao_cartao_adm'];
		$retorno->transacao->transacao_cartao_band = $row['transacao_cartao_band'];
		$retorno->transacao->transacao_cartao_cvv = $row['transacao_cartao_cvv'];
		$retorno->transacao->transacao_cartao_num = $row['transacao_cartao_num'];
		$retorno->transacao->transacao_cartao_validade_mes = $row['transacao_cartao_validade_mes'];
		$retorno->transacao->transacao_cartao_validade_ano = $row['transacao_cartao_validade_ano'];
	}else{
		$retorno->erro = 1;
		$retorno->mensagem = "Nenhuma transação localizada para o id: ".$transacao_id;
	}

	return $retorno;
}
?>