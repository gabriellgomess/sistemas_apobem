<?php

// Dados para homologação.
$CURLOPT_HTTPHEADER = array(
	"Accept: application/json",
	"merchant_id: defatoseguros",
	"merchant_key: 90B039BE764D1CD4A76389AA6446822556C8B8A676B4DA23E1878F1FE83625FD",
	"Content-Type: application/json",
	"cache-control: no-cache"
);
$link_prefixo = "https://esitef-homologacao.softwareexpress.com.br";

// }
$path_includes = "../../../sistema/sistema/";

include($path_includes."connect_seguro.php");    

$data = date('Y-m-d', strtotime('-2 days'));
$data = "2021-10-03";

$con = mysqli_connect("10.100.0.22","root","Theredpil2001","sistema");
if (!$con)
  {
  die('Could not connect: ' . mysqli_error());
  }

mysqli_query($con, "SET character_set_results=utf8");
mb_language('uni'); 
mb_internal_encoding('UTF-8');
mysqli_query($con, "set names 'utf8'");

date_default_timezone_set('America/Sao_Paulo');

$result = mysqli_query($con, "SELECT seg.vendas_id, seg.vendas_id, transacao_status, tef.transacao_data, tef.transacao_token, tef.transacao_cliente_cpf, tef.transacao_username, 
tef.transacao_user_id, tef.transacao_venda_id, tef.transacao_id, 
tef.transacao_tipo_plano,  tef.transacao_cartao_adm, tef.transacao_cartao_num, tef.transacao_cartao_validade_mes, tef.transacao_cartao_validade_ano, tef.transacao_valor
FROM sys_vendas_seguros seg
INNER JOIN sys_vendas_transacoes_tef tef ON seg.vendas_id = tef.transacao_venda_id 
WHERE tef.transacao_status LIKE '%RET%' AND tef.transacao_data LIKE '%".$data."%';") or die(mysqli_error($con));






while($row  = mysqli_fetch_array( $result ))
    {
    
        // captura dos dados postados.
        $dados = (object) array(
        'token' => $row["transacao_token"],
        'cpf' => $row["transacao_cliente_cpf"],
        'username' => $row["transacao_username"],
        'user_id' => $row["transacao_user_id"],
        'venda_id' => $row["transacao_venda_id"],
        'plano' => $row["transacao_tipo_plano"],
        'card_adm' => $row["transacao_cartao_adm"],
        'card_num' => $row["transacao_cartao_num"],
        'card_validade_mes' => $row["transacao_cartao_validade_mes"],
        'card_validade_ano' => $row["transacao_cartao_validade_ano"],
        'transacao_valor' =>$row["transacao_valor"]
        
        );
             
        $transaction_id = insereRegistroTransacao($con, $dados);

        
        /* ----- DADOS DA REQUISIÇÃO DA TRANSAÇÃO ----- */
        $payment_amount = valorParaAmount($dados->transacao_valor); // valor da parcela, exemplo 19,90 R$ deve ser representado como 1990 sem ponto nem virgulas incluindo os centavos sempre.
        $card_number = $dados->card_num; // exemplo: "5281735839609922";
        $card_expiry_date = $dados->card_validade_mes."".substr($dados->card_validade_ano,2); // Preencher com mês ano exemplo '1220' (mês 12 ano 2020)
        //FIM Valores falsos para teste, comentar ou apagar posteriormente.
            
        // Início CURL
        $dataPreAut = (object) array(
            'order_id' => $transaction_id,
            'amount' => $payment_amount,
            'authorizer_id' => $dados->card_adm
        );


        $url_pre_aut = $link_prefixo.'/e-sitef/api/v1/transactions/';
        $curl_resposta = executaCurlPreAutorizacao( $dataPreAut, $url_pre_aut, $CURLOPT_HTTPHEADER );        
        $respostaJson = json_decode($curl_resposta);
        // FIM CURL

        $dados = (object) array(
            'nit' => mysqli_real_escape_string($con, $respostaJson->payment->nit),
            'status' => mysqli_real_escape_string($con, $respostaJson->payment->status),
            'transacao_id' => $transaction_id
        );
        //print_r($dados);
        atualizaTransacao($con, $dados);

        echo json_encode($respostaJson);
        //return json_encode($respostaJson);
        ?><br><?php
?><br><?php
?><br><?php

    
$resultComNit = mysqli_query($con, "SELECT seg.vendas_id, seg.vendas_id, transacao_status, tef.transacao_nit,  tef.transacao_data, tef.transacao_token, tef.transacao_cliente_cpf, tef.transacao_username, 
tef.transacao_user_id, tef.transacao_venda_id, tef.transacao_id,
tef.transacao_tipo_plano,  tef.transacao_cartao_adm, tef.transacao_cartao_num, tef.transacao_cartao_validade_mes, tef.transacao_cartao_validade_ano, tef.transacao_valor
FROM sys_vendas_seguros seg
INNER JOIN sys_vendas_transacoes_tef tef ON seg.vendas_id = tef.transacao_venda_id 
WHERE transacao_id = " .$transaction_id .";") or die(mysqli_error($con));



while($rowComNit  = mysqli_fetch_array( $resultComNit ))
{

    // captura dos dados postados.
    $dados = (object) array(
        'token' => $rowComNit["transacao_token"],
        'cpf' => $rowComNit["transacao_cliente_cpf"],
        'username' => $rowComNit["transacao_username"],
        'user_id' => $rowComNit["transacao_user_id"],
        'venda_id' => $rowComNit["transacao_venda_id"],
        'plano' => $rowComNit["transacao_tipo_plano"],
        'card_adm' => $rowComNit["transacao_cartao_adm"],
        'card_num' => $rowComNit["transacao_cartao_num"],
        'card_validade_mes' => $rowComNit["transacao_cartao_validade_mes"],
        'card_validade_ano' => $rowComNit["transacao_cartao_validade_ano"],
        'transacao_valor' =>$rowComNit["transacao_valor"],
        'transaction_nit' =>$rowComNit["transacao_nit"],
        'transaction_id' =>$rowComNit["transacao_id"]
    );

    // fim captura dos dados postados.
	
	// registro inicial da transação que será efetuada e captura do id.
	$transaction_id = $dados->transaction_id;

	/* ----- DADOS DA REQUISIÇÃO DA TRANSAÇÃO ----- */
	$card_number = $dados->card_num; // exemplo: "5281735839609922";
	$card_expiry_date = $dados->card_validade_mes."".substr($dados->card_validade_ano,2); // Preencher com mês ano exemplo '1220' (mês 12 ano 2020)
	//FIM Valores falsos para teste, comentar ou apagar posteriormente.
	
	// Início CURL
	$dataTransaction = (object) array(
		'card' => (object) array(
			'number' => $card_number,
			'expiry_date' => $card_expiry_date,
            'security_code' => "123"
		)
	);

	$url_do_transaction = $link_prefixo.'/e-sitef/api/v1/payments/'.$dados->transaction_nit;
	//##### Tenta efetivar a transação via curl            


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
            \"expiry_date\" : \"{$dataTransaction->card->expiry_date}\",
            \"security_code\" : \"{$dataTransaction->card->security_code}\"
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
    
	$response_transaction = json_decode(curl_exec($curl));

	$response_transaction->erro = curl_error($curl);
	$response_transaction->transaction_error_number = curl_errno($curl);
	
	


	//#### FIM CURL
    // Caso a transação não tenha obtido sucesso e o erro foi 28 (tempo limite de resposta excedido)
	if($response_transaction->transaction_error_number !='0' && $response_transaction->transaction_error_number == '28')
	{
		$tentativa = 1;
		while($tentativa <= 3 )
		{
			$url_getstatus = $link_prefixo.'/e-sitef/api/v1/transactions/'.$response_transaction->payment->nit;
			// tenta verifica se a transação chegou a ocorrer e com qual resultado, afinal não foi possível obter a resposta antes.
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
		$payment = json_encode($response_transaction->payment->status);

	}
?><br><?php
?><br><?php
?><br><?php
    echo "code: ".$response_transaction->payment->status."<br><br>";
curl_close($curl);
}

            
    }





function valorParaAmount($valor){
    $amount = sprintf('%0.2f', $valor);
    $amount = str_replace(".", "", $amount);
    return $amount;
}
           
function insereRegistroTransacao($con, $dados)
{
    $insere_transaction_query ="INSERT INTO sys_vendas_transacoes_tef (
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
        '".$dados->cpf."',
        '".$dados->username."',
        '".$dados->user_id."',
        '".$dados->token."',
        '".$dados->transacao_valor."',
        '".$dados->venda_id."',
        NOW(),
        NOW(),
        '".$dados->plano."',
        '".$dados->card_adm."',
        '',
        '666',
        '".$dados->card_num."',
        '".$dados->card_validade_mes."',
        '".$dados->card_validade_ano."'
    )";

    //print_r($dados);
    //echo $insere_transaction_query . "       ";
    //echo "nao fez a query";
    $result_transaction = mysqli_query($con, $insere_transaction_query);
    //echo "fez a query";
    return mysqli_insert_id($con);
}

function atualizaTransacao($con, $dados)
{
	$update_transaction_query ="UPDATE sys_vendas_transacoes_tef
	SET transacao_nit = '". $dados->nit."',
	transacao_status = '". $dados->status ."'
	WHERE transacao_id = '". $dados->transacao_id ."'";
	$result_transaction = mysqli_query($con, $update_transaction_query);

    
}

function executaCurlPreAutorizacao($dataPreAut, $url, $CURLOPT_HTTPHEADER)
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
        \"order_id\" : \"{$dataPreAut->order_id}\",
        \"installments\" : \"1\",
		\"installment_type\" : \"4\",
		\"authorizer_id\" : \"{$dataPreAut->authorizer_id}\",
		\"amount\" : \"{$dataPreAut->amount}\"
    }";


    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
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
            \"expiry_date\" : \"{$dataTransaction->card->expiry_date}\",
            \"security_code\" : \"{$dataTransaction->card->security_code}\"
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
	return $response;
}

function validaDados($con, $dados)
{	
    if (empty($row["transacao_username"]))
    {
        $result->valid = 'erro';
        $result->message = 'Campo username é obrigatório.';
        return $result;
    }
    if (empty($row["transacao_user_id"]))
    {
        $result->valid = 'erro';
        $result->message = 'Campo user_id é obrigatório.';
        return $result;
    }
    if (empty($row["transacao_venda_id"]))
    {
        $result->valid = 'erro';
        $result->message = 'Id da venda é obrigatório.';
        return $result;
    }
    if (empty($row["transacao_cartao_adm"]))
    {
        $result->valid = 'erro';
        $result->message = 'ADM do cartao eh obrigatoria.';
        return $result;
    }    
    if (empty($row["transacao_cartao_num"])) {
        $result->valid = 'erro';
        $result->message = 'Preencha o numero do cartão.';
        return $result;
    }
    if (empty($row["transacao_cartao_validade_mes"])) {
        $result->valid = 'erro';
        $result->message = 'Preencha o mês de validade do cartão.';
        return $result;
    }
    if (empty($row["transacao_cartao_validade_ano"])) {
        $result->valid = 'erro';
        $result->message = 'Preencha o ano de validade do cartão.';
        return $result;
    }
    if (empty($row["transacao_valor"])) {
        $result->valid = 'erro';
        $result->message = 'Preencha o valor.';
        return $result;
    }
    if (empty($row["transacao_tipo_plano"])) {
        $result->valid = 'erro';
        $result->message = 'Escolha o tipo de plano.';
        return $result;
    }
    if (empty($row["transacao_cliente_cpf"])) {
        $result->valid = 'erro';
        $result->message = 'CPF não preenchido ou inválido.';
        return $result;
    }
	
	$result_transacoes_tef_dia = mysql_query("SELECT COUNT(transacao_id) AS total FROM sys_vendas_transacoes_tef WHERE transacao_cliente_cpf = '" . $_POST['cpf'] . "' AND transacao_data > '".date('Y-m-d')." 00:00:00';") 
	or die(mysql_error());
	$row_tef_dia = mysql_fetch_array( $result_transacoes_tef_dia );
	if($row_tef_dia['total']){
        $result->valid = 'erro';
        $result->message = 'Cliente já possui transações na data de hoje.';
        return $result;
	}
	
    $result->valid = 'success';
    $result->message = 'Os dados informados são válidos.';
    return $result;
}

?>


