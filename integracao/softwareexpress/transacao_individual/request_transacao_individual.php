<?php
// Debug PHP
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../get_erros.php");
include("../variaveis_fixas.php");

if ($_GET['token'] === 'EsearR31234fpssa0vfc9o') {
    include("connect_seguro.php");

    $metodo = 'POST';

    // Captura dos dados postados
    $dados = (object) array(
        'token' => $_GET['token'],
        'cpf' => $_POST['cpf'] ?? '',
        'username' => $_POST['username'] ?? '',
        'user_id' => $_POST['user_id'] ?? '',
        'venda_id' => $_POST['venda_id'] ?? '',
        'plano' => $_POST['plano'] ?? '',
        'card_adm' => $_POST['card_adm'] ?? '',
        'card_num' => $_POST['card_num'] ?? '',
        'card_validade_mes' => $_POST['card_validade_mes'] ?? '',
        'card_validade_ano' => $_POST['card_validade_ano'] ?? '',
        'transacao_valor' => $_POST['transacao_valor'] ?? ''
    );

    // Validação dos dados
    $valida_result = validaDados($con, $dados);

    if ($valida_result->valid !== 'success') {
        echo json_encode($valida_result);
        exit;
    }

    // Registro inicial da transação
    $transaction_id = insereRegistroTransacao($con, $dados);
    $order_id = $transaction_id . "_C";

    // Dados da requisição da transação
    $payment_amount = valorParaAmount($dados->transacao_valor);
    $card_number = $dados->card_num;
    $card_expiry_date = $dados->card_validade_mes . substr($dados->card_validade_ano, 2);

    $dataPreAut = (object) array(
        'order_id' => $order_id,
        'amount' => $payment_amount,
        'authorizer_id' => $dados->card_adm
    );

    $url_pre_aut = $link_prefixo . '/e-sitef/api/v1/transactions/';
    $curl_resposta = executaCurlPreAutorizacao($dataPreAut, $url_pre_aut, $CURLOPT_HTTPHEADER);
    $respostaJson = json_decode($curl_resposta);

    // Atualizar transação
    $dadosAtualizacao = (object) array(
        'authorizer_id' => $respostaJson->payment->authorizer_id ?? '',
        'status' => $respostaJson->payment->status ?? '',
        'order_id' => $order_id,
        'transacao_id' => $transaction_id
    );

    atualizaTransacao($con, $dadosAtualizacao);

    echo json_encode($respostaJson);
} else {
    echo json_encode(["error" => "Token inválido"]);
}

// Função para converter valores para formato aceito pela API
function valorParaAmount($valor) {
    return str_replace(".", "", sprintf('%0.2f', $valor));
}

// Função para validar os dados da transação
function validaDados($con, $dados) {
    $result = new stdClass();
    $result->valid = 'success';
    $result->message = 'Os dados informados são válidos.';

    $camposObrigatorios = ['username', 'user_id', 'venda_id', 'card_adm', 'card_num', 'card_validade_mes', 'card_validade_ano', 'transacao_valor', 'plano', 'cpf'];

    foreach ($camposObrigatorios as $campo) {
        if (empty($dados->$campo)) {
            $result->valid = 'erro';
            $result->message = "Campo {$campo} é obrigatório.";
            return $result;
        }
    }
	
	$dataHoje = date('Y-m-d') . " 00:00:00";
	$query = "SELECT COUNT(transacao_id) AS total FROM sys_vendas_transacoes_tef WHERE transacao_cliente_cpf = '".$dados->cpf."' AND transacao_data > '".$dataHoje."'";
	$sql_transacoes = mysqli_query($con, $query) or die(mysql_error());
	$row_transacoes = mysqli_fetch_array( $sql_transacoes );
	$total = $row_transacoes['total'];

    if ($total > 0) {
        $result->valid = 'erro';
        $result->message = 'Cliente já possui transações na data de hoje.';
    }

    return $result;
}

// Função para inserir registro da transação
function insereRegistroTransacao($con, $dados) {
    $query = "INSERT INTO sys_vendas_transacoes_tef (
        transacao_cliente_cpf, transacao_username, transacao_user_id, transacao_token, transacao_valor, transacao_venda_id,
        transacao_data, transacao_tipo_plano, transacao_cartao_adm, transacao_cartao_num, transacao_cartao_validade_mes, transacao_cartao_validade_ano
    ) VALUES ('".$dados->cpf."', '".$dados->username."', '".$dados->user_id."', '".$dados->token."', '".$dados->transacao_valor."', '".$dados->venda_id."', NOW(), '".$dados->plano."', '".$dados->card_adm."', '".$dados->card_num."', '".$dados->card_validade_mes."', '".$dados->card_validade_ano."')";
	
	if (mysqli_query($con, $query)) {
		//echo "insereRegistroTransacao Sucesso. </br>";
		$insertId = mysqli_insert_id($con);
	} else {
		die('Error: ' . mysqli_error($con));
	}
    
    return $insertId;
}

// Função para atualizar a transação
function atualizaTransacao($con, $dados) {
    $query = "UPDATE sys_vendas_transacoes_tef SET transacao_nit = '".$dados->authorizer_id."', transacao_status = '".$dados->status."', order_id = '".$dados->order_id."' WHERE transacao_id = '".$dados->transacao_id."'";
    
	if (mysqli_query($con, $query)) {
		//echo "insereRegistroTransacao Sucesso. </br>";
	} else {
		die('Error: ' . mysqli_error($con));
	}
}

// Função para realizar a requisição cURL
function executaCurlPreAutorizacao($dataPreAut, $url, $CURLOPT_HTTPHEADER) {
    $data_corpo = json_encode([
        "order_id" => $dataPreAut->order_id,
        "installments" => "1",
        "installment_type" => "4",
        "authorizer_id" => $dataPreAut->authorizer_id,
        "amount" => $dataPreAut->amount
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data_corpo,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER
    ));

    $resposta = curl_exec($curl);
    curl_close($curl);

    return $resposta ?: json_encode(["error" => "Erro na requisição"]);
}
?>
