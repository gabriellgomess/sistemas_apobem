<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../get_erros.php");
    include("../variaveis_fixas.php");
    include("../../../portal/sistema/connect_seguro_mysqli.php");

    if ($_GET['token'] !== 'EsearR31234fpssa0vfc9o') {
        echo json_encode(['error' => 'Token inválido']);
        exit;
    }

    $dados = filter_input_array(INPUT_POST, [
        'cpf' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'username' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'user_id' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'venda_id' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'plano' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'card_adm' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'card_num' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'card_validade_mes' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'card_validade_ano' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'transacao_valor' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]);

    $validacao = validaDados($con, $dados);
    if ($validacao['valid'] !== 'success') {
        echo json_encode($validacao);
        exit;
    }

    $transaction_id = insereRegistroTransacao($con, $dados);
    $order_id = $transaction_id . "_C";

    $dataPreAut = [
        'order_id' => $order_id,
        'amount' => valorParaAmount($dados['transacao_valor']),
        'authorizer_id' => $dados['card_adm']
    ];

    $url_pre_aut = $link_prefixo . '/e-sitef/api/v1/transactions/';
    $curl_resposta = executaCurlPreAutorizacao($dataPreAut, $url_pre_aut, $CURLOPT_HTTPHEADER);
    $respostaJson = json_decode($curl_resposta, true);

    atualizaTransacao($con, [
        'authorizer_id' => $respostaJson['payment']['authorizer_id'],
        'status' => $respostaJson['payment']['status'],
        'order_id' => $order_id,
        'transacao_id' => $transaction_id
    ]);

    echo json_encode($respostaJson);

function valorParaAmount($valor) {
    return (string)(int)(floatval($valor) * 100);
}

function validaDados($con, $dados) {
    foreach ($dados as $key => $value) {
        if (empty($value)) {
            return ['valid' => 'erro', 'message' => "Campo $key é obrigatório."];
        }
    }

    $stmt = $con->prepare("SELECT COUNT(transacao_id) AS total FROM sys_vendas_transacoes_tef WHERE transacao_cliente_cpf = ? AND transacao_data > ?");
    $stmt->bind_param('ss', $dados['cpf'], date('Y-m-d') . " 00:00:00");
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    if ($total > 0) {
        return ['valid' => 'erro', 'message' => 'Cliente já possui transações na data de hoje.'];
    }

    return ['valid' => 'success', 'message' => 'Os dados são válidos.'];
}

function insereRegistroTransacao($con, $dados) {
    $stmt = $con->prepare(
        "INSERT INTO sys_vendas_transacoes_tef (transacao_cliente_cpf, transacao_username, transacao_user_id, transacao_token, transacao_valor, transacao_venda_id, transacao_data, transacao_tipo_plano, transacao_cartao_adm, transacao_cartao_num, transacao_cartao_validade_mes, transacao_cartao_validade_ano) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        'ssssssssss', 
        $dados['cpf'], $dados['username'], $dados['user_id'], $dados['token'], 
        $dados['transacao_valor'], $dados['venda_id'], $dados['plano'], 
        $dados['card_adm'], $dados['card_num'], $dados['card_validade_mes'], $dados['card_validade_ano']
    );
    $stmt->execute();
    $transaction_id = $stmt->insert_id;
    $stmt->close();
    return $transaction_id;
}

function atualizaTransacao($con, $dados) {
    $stmt = $con->prepare(
        "UPDATE sys_vendas_transacoes_tef SET transacao_status = ?, order_id = ? WHERE transacao_id = ?"
    );
    $stmt->bind_param(
        'ssi', 
        $dados['status'], $dados['order_id'], $dados['transacao_id']
    );
    $stmt->execute();
    $stmt->close();
}

function executaCurlPreAutorizacao($dataPreAut, $url, $headers) {
    $data_corpo = json_encode($dataPreAut);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data_corpo,
        CURLOPT_HTTPHEADER => array_merge($headers, ["Content-Type: application/json"])
    ]);

    $resposta = curl_exec($curl);
    curl_close($curl);
    return $resposta;
}?>
