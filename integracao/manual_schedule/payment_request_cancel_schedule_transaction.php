<?php
header("Access-Control-Allow-Origin: *");
include("variaveis_fixas.php");
if (true /*$_GET['token'] === 'EsearR31234fpssa0vfc9o'*/ )
{
	include("../../portal/sistema/connect_seguro.php");
	$url = $link_prefixo.'/e-sitef/api/v1/schedules/edits';
	$metodo = 'POST';
	$dados = $_POST;

	$sid = "218e69dd25758dc159156d69a1216e43ca9a7104234831e6b6340782bf5b482d";
	$payment->transacao_agendamento_sid = $sid;

	@$data_corpo = "{
		\"sid\" : \"{$payment->transacao_agendamento_sid}\"
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
			// Consultando e armazenando os dados do registro da transação que está em nosso banco de dados.
			$retornoGetTransation = getTransactionDataBySid($sid);

			// Caso a consulta dos dados da transação tenha ocorrido com sucesso (zero erros)
			if($retornoGetTransation->erro == 0)
			{
				// Atribui todos os dados da transação que está armazenada em nosso banco de dados na variável $transactionData
				$transactionData = $retornoGetTransation->transacao;

				registraLog($respostaJson, $transactionData);
				$vendas_obs = "Realizada solicitacao de cancelamento de cobrança recorrente. Aguarde a confirmação do cancelamento.";
				$vendas_status = '86';
				registraHistorico($transactionData->transacao_venda_id, $vendas_obs, $vendas_status);
			}
		}
	} else {
		echo 'token inválido';
	}
?>

<?php 
function registraLog($respostaJson, $transactionData)
{
	$sql_insert_log = "INSERT INTO sys_vendas_transacoes_tef_log 
							(
								transacao_id,
								clients_cpf,
								data,
								erro_cod,
								status,
								response_json
							)
						VALUES
							(
							'".$transactionData->transacao_id."',
							'".$transactionData->transacao_cliente_cpf."',
							NOW(),
							'".$respostaJson->code."',
							'',
							'".mysql_real_escape_string(json_encode($respostaJson))."'
							)";
	mysql_query($sql_insert_log);
}

function registraHistorico($vendas_id, $vendas_obs, $vendas_status)
{
	$sql = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, 
	`vendas_id`, 
	`registro_usuario`, 
	`registro_obs`, 
	`registro_status`, 
	`registro_data`) 
	VALUES (NULL, 
	'".$vendas_id."',
	'sistema',
	'".$vendas_obs."',
	'".$vendas_status."',
	NOW());";
	mysql_query($sql);
}

function getTransactionDataBySid($sid)
{
	$sql = "SELECT * FROM sys_vendas_transacoes_tef WHERE transacao_agendamento_sid = '".$sid."' LIMIT 0,1";
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
		$retorno->mensagem = "Nenhuma transação localizada para o sid: ".$sid;
	}

	return $retorno;
}
?>