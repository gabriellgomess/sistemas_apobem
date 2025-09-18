<?php
    // debug php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	include("../get_erros.php");
	include("../variaveis_fixas.php");

	if ($_GET['token'] === 'EsearR31234fpssa0vfc9o')
	{
        //captura e definição dos dados
        include("connect_seguro.php");        
        $metodo = 'POST';

        // captura dos dados postados.
        $dados = (object) array(
            'token' => $_GET['token'],
            'cpf' => $_POST['cpf'],
            'username' => $_POST['username'],
            'user_id' => $_POST['user_id'],
            'venda_id' => $_POST['venda_id'],
            'plano' => $_POST['plano'],
            'card_adm' => $_POST['card_adm'],
            'card_num' => $_POST['card_num'],
            'card_validade_mes' => $_POST['card_validade_mes'],
            'card_validade_ano' => $_POST['card_validade_ano'],
            'transacao_valor' => $_POST['transacao_valor']
        );
        // fim captura dos dados postados.

        //validação dos dados
        $valida_result = validaDados($con, $dados);
        
		if($valida_result->valid != 'success')
		{
            echo json_encode($valida_result);
            
        }
        
        // echo "<pre>";
        // print_r($dados);
        // echo "</pre>";

        // registro inicial da transação que será efetuada e captura do id.
        $transaction_id = insereRegistroTransacao($con, $dados);
		$order_id = $transaction_id."_C";

        /* ----- DADOS DA REQUISIÇÃO DA TRANSAÇÃO ----- */
        $payment_amount = valorParaAmount($dados->transacao_valor); // valor da parcela, exemplo 19,90 R$ deve ser representado como 1990 sem ponto nem virgulas incluindo os centavos sempre.
        $card_number = $dados->card_num; // exemplo: "5281735839609922";
        $card_expiry_date = $dados->card_validade_mes."".substr($dados->card_validade_ano,2); // Preencher com mês ano exemplo '1220' (mês 12 ano 2020)
        //FIM Valores falsos para teste, comentar ou apagar posteriormente.

        // Início CURL
        $dataPreAut = (object) array(
            'order_id' => $order_id,
            'amount' => $payment_amount,
			'authorizer_id' => $dados->card_adm
        );

        $url_pre_aut = $link_prefixo.'/e-sitef/api/v1/transactions/';
        $curl_resposta = executaCurlPreAutorizacao( $dataPreAut, $url_pre_aut, $CURLOPT_HTTPHEADER );        
        $respostaJson = json_decode($curl_resposta);
        // FIM CURL
		
        $dados = (object) array(
            'authorizer_id' => mysql_real_escape_string($respostaJson->payment->authorizer_id),
            'status' => mysql_real_escape_string($respostaJson->payment->status),
			'order_id' => $order_id,
            'transacao_id' => $transaction_id
        );
		atualizaTransacao($con, $dados);

		echo json_encode($respostaJson);
		return json_encode($respostaJson);
	} else {
		echo 'token inválido';
	}
?>
<?php
function valorParaAmount($valor){
    $amount = sprintf('%0.2f', $valor);
    $amount = str_replace(".", "", $amount);
    return $amount;
}

function validaDados($con, $dados)
{	
    if (empty($_POST['username']))
    {
        $result->valid = 'erro';
        $result->message = 'Campo username é obrigatório.';
        return $result;
    }
    if (empty($_POST['user_id']))
    {
        $result->valid = 'erro';
        $result->message = 'Campo user_id é obrigatório.';
        return $result;
    }
    if (empty($_POST['venda_id']))
    {
        $result->valid = 'erro';
        $result->message = 'Id da venda é obrigatório.';
        return $result;
    }
    if (empty($_POST['card_adm']))
    {
        $result->valid = 'erro';
        $result->message = 'ADM do cartao eh obrigatoria.';
        return $result;
    }    
    if (empty($_POST['card_num'])) {
        $result->valid = 'erro';
        $result->message = 'Preencha o numero do cartão.';
        return $result;
    }
    if (empty($_POST['card_validade_mes'])) {
        $result->valid = 'erro';
        $result->message = 'Preencha o mês de validade do cartão.';
        return $result;
    }
    if (empty($_POST['card_validade_ano'])) {
        $result->valid = 'erro';
        $result->message = 'Preencha o ano de validade do cartão.';
        return $result;
    }
    if (empty($_POST['transacao_valor'])) {
        $result->valid = 'erro';
        $result->message = 'Preencha o valor.';
        return $result;
    }
    if (empty($_POST['plano'])) {
        $result->valid = 'erro';
        $result->message = 'Escolha o tipo de plano.';
        return $result;
    }
    if (empty($_POST['cpf'])) {
        $result->valid = 'erro';
        $result->message = 'CPF não preenchido ou inválido.';
        return $result;
    }
	
	$result_transacoes_tef_dia = mysql_query("SELECT COUNT(transacao_id) AS total FROM sys_vendas_transacoes_tef WHERE transacao_cliente_cpf = '" . $_POST['cpf'] . "' AND transacao_data > '".date('Y-m-d')." 00:00:00';") 
	or die(mysql_error());
	$row_tef_dia = mysql_fetch_array( $result_transacoes_tef_dia );
	if($row_tef_dia['total']){
        $result->valid = 'erro';
        $result->message = 'Cliente ja possui transaçoes na data de hoje...';
        return $result;
	}
	
    $result->valid = 'success';
    $result->message = 'Os dados informados são válidos.';
    return $result;
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
        '".mysql_real_escape_string($dados->cpf)."',
        '".mysql_real_escape_string($dados->username)."',
        '".mysql_real_escape_string($dados->user_id)."',
        '".mysql_real_escape_string($dados->token)."',
        '".mysql_real_escape_string($dados->transacao_valor)."',
        '".mysql_real_escape_string($dados->venda_id)."',
        NOW(),
        NOW(),
        '".mysql_real_escape_string($dados->plano)."',
        '".mysql_real_escape_string($dados->card_adm)."',
        '',
        '666',
        '".mysql_real_escape_string($dados->card_num)."',
        '".mysql_real_escape_string($dados->card_validade_mes)."',
        '".mysql_real_escape_string($dados->card_validade_ano)."'
    )";

    $result_transaction = mysql_query($insere_transaction_query);
    return mysql_insert_id($con);
}

function atualizaTransacao($con, $dados)
{
	$update_transaction_query ="UPDATE sys_vendas_transacoes_tef
	SET transacao_nit = '".mysql_real_escape_string($dados->transacao_nit)."',
	transacao_status = '".mysql_real_escape_string($dados->transacao_status)."',
	order_id = '".mysql_real_escape_string($dados->order_id)."'
	WHERE transacao_id = '".mysql_real_escape_string($dados->transacao_id)."'";
	$result_transaction = mysql_query($update_transaction_query);
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
?>