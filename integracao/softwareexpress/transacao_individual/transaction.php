<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include("../get_erros.php");
include("../variaveis_fixas.php");

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if ($_GET['token'] == 'EsearR31234fpssa0vfc9o') {
    $db_ip = "10.100.0.22";
    $con = mysqli_connect($db_ip, "root", "Theredpil2001", "sistema");

    // Limpa CPF
    $cliente_cpf = $_POST['cpf'];
    $removidos = array(".", "-");
    $cliente_cpf = str_replace($removidos, "", $cliente_cpf);

    $dados = (object) [
        'token' => $_GET['token'],
        'cpf' => $cliente_cpf,
        'username' => $_POST['username'],
        'user_id' => $_POST['user_id'],
        'venda_id' => $_POST['venda_id'],
        'plano' => $_POST['plano'],
        'card_adm' => $_POST['card_adm'],
        'card_num' => $_POST['card_num'],
        'card_validade_mes' => $_POST['card_validade_mes'],
        'card_validade_ano' => $_POST['card_validade_ano'],
        'transacao_valor' => $_POST['transacao_valor'],
        'so' => $_POST['so'],
    ];

    $transaction_id = insereRegistroTransacao($con, $dados);
    $order_id = $transaction_id . "_C";

    function valorParaAmount($valor)
    {
        $valor = str_replace(",", "", $valor);
        $valor = str_replace(".", "", $valor);
        return floatval($valor);
    }

    $payment_amount = valorParaAmount(number_format($dados->transacao_valor, 2, '.', ''));
    $card_number = $dados->card_num;
    $card_expiry_date = $dados->card_validade_mes . substr($dados->card_validade_ano, 2);

    $dataPreAut = (object) [
        'order_id' => $order_id,
        'amount' => $payment_amount,
        'authorizer_id' => "2"
    ];

    $url_pre_aut = $link_prefixo . '/e-sitef/api/v1/transactions/';
    $curl_resposta = executaCurlPreAutorizacao($dataPreAut, $url_pre_aut, $CURLOPT_HTTPHEADER);
    $respostaJson = json_decode($curl_resposta);

    $dados = (object) [
        'authorizer_id' => mysqli_real_escape_string($con, $respostaJson->payment->authorizer_id),
        'status' => mysqli_real_escape_string($con, $respostaJson->payment->status),
        'order_id' => $order_id,
        'transacao_id' => $transaction_id,
        'transacao_nit' => mysqli_real_escape_string($con, $respostaJson->payment->nit),
    ];

    atualizaTransacao2($con, $dados);
    if ($respostaJson->code != "0") {
        echo "erro na pre-autorizacao";
        exit;
    }

    // ####################################################################

    finalizaVenda($dados, $transaction_id, $respostaJson);

    if ($respostaJson->code != "0") {
        echo "erro na pre-autorizacao";
        exit;
    }

    function finalizaVenda($dados, $transaction_id, $respostaJson)
    {
        $data = array(
            "card_number" => $dados->card_num,
            "card_expiry_date" => $dados->card_validade_mes . substr($dados->card_validade_ano, 2),
            "payment_amount" => valorParaAmount(number_format($dados->transacao_valor, 2, '.', '')),
            "installments" => 1,
            "receipt_print" => "N",
            "additional_data" => [
                "cpf" => $dados->cpf,
                "order_id" => $dados->order_id,
                "transaction_id" => $transaction_id,
                "so" => $dados->so,
            ]
        );

        $url_venda = $link_prefixo . '/e-sitef/api/v1/transactions/';
        $curl_resposta = executaCurlVenda($data, $url_venda, $CURLOPT_HTTPHEADER);

        if ($curl_resposta) {
            $respostaJson = json_decode($curl_resposta);

            $dados = (object) [
                'authorizer_id' => mysqli_real_escape_string($con, $respostaJson->payment->authorizer_id),
                'status' => mysqli_real_escape_string($con, $respostaJson->payment->status),
                'order_id' => $dados->order_id,
                'transacao_id' => $transaction_id,
                'transacao_nit' => mysqli_real_escape_string($con, $respostaJson->payment->nit),
            ];

            atualizaTransacao2($con, $dados);

            if ($respostaJson->code != "0") {
                echo "erro na venda";
                exit;
            }
        } else {
            echo "Erro na conexão com o E-Sitef";
            exit;
        }
    }

    function insereRegistroTransacao($con, $dados)
    {
        $sql = "INSERT INTO transacoes (
            token,
            cpf,
            username,
            user_id,
            venda_id,
            plano,
            card_adm,
            card_num,
            card_validade_mes,
            card_validade_ano,
            transacao_valor,
            so
        )
        VALUES (
            '" . mysqli_real_escape_string($con, $dados->token) . "',
            '" . mysqli_real_escape_string($con, $dados->cpf) . "',
            '" . mysqli_real_escape_string($con, $dados->username) . "',
            '" . mysqli_real_escape_string($con, $dados->user_id) . "',
            '" . mysqli_real_escape_string($con, $dados->venda_id) . "',
            '" . mysqli_real_escape_string($con, $dados->plano) . "',
            '" . mysqli_real_escape_string($con, $dados->card_adm) . "',
            '" . mysqli_real_escape_string($con, $dados->card_num) . "',
            '" . mysqli_real_escape_string($con, $dados->card_validade_mes) . "',
            '" . mysqli_real_escape_string($con, $dados->card_validade_ano) . "',
            '" . mysqli_real_escape_string($con, $dados->transacao_valor) . "',
            '" . mysqli_real_escape_string($con, $dados->so) . "'
        )";

        if (!mysqli_query($con, $sql)) {
            echo "Erro ao inserir o registro da transação: " . mysqli_error($con);
            exit;
        }
    }

    function atualizaTransacao1($con, $dados)
    {
        $sql = "UPDATE transacoes SET
            token='" . mysqli_real_escape_string($con, $dados->token) . "',
            cpf='" . mysqli_real_escape_string($con, $dados->cpf) . "',
            username='" . mysqli_real_escape_string($con, $dados->username) . "',
            user_id='" . mysqli_real_escape_string($con, $dados->user_id) . "',
            venda_id='" . mysqli_real_escape_string($con, $dados->venda_id) . "',
            plano='" . mysqli_real_escape_string($con, $dados->plano) . "',
            card_adm='" . mysqli_real_escape_string($con, $dados->card_adm) . "',
            card_num='" . mysqli_real_escape_string($con, $dados->card_num) . "',
            card_validade_mes='" . mysqli_real_escape_string($con, $dados->card_validade_mes) . "',
            card_validade_ano='" . mysqli_real_escape_string($con, $dados->card_validade_ano) . "',
            transacao_valor='" . mysqli_real_escape_string($con, $dados->transacao_valor) . "',
            so='" . mysqli_real_escape_string($con, $dados->so) . "',
            criado_em=NOW()
            WHERE id='" . mysqli_real_escape_string($con, $dados->id) . "'";

        if (!mysqli_query($con, $sql)) {
            echo "Erro ao atualizar a transação: " . mysqli_error($con);
            exit;
        }
    }

    function atualizaTransacao2($con, $dados)
    {
        $sql = "UPDATE transacoes SET
            authorizer_id='" . mysqli_real_escape_string($con, $dados->authorizer_id) . "',
            status='" . mysqli_real_escape_string($con, $dados->status) . "',
            transacao_id='" . mysqli_real_escape_string($con, $dados->transacao_id) . "',
            transacao_nit='" . mysqli_real_escape_string($con, $dados->transacao_nit) . "'
            WHERE order_id='" . mysqli_real_escape_string($con, $dados->order_id) . "'";

        if (!mysqli_query($con, $sql)) {
            echo "Erro ao atualizar a transação: " . mysqli_error($con);
            exit;
        }
    }

    function valorParaAmount($valor)
    {
        return str_replace('.', '', $valor);
    }

    function executaCurlVenda($data, $url, $CURLOPT_HTTPHEADER)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER,
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Erro ao executar a solicitação do cURL: ' . curl_error($curl);
            exit;
        }

        curl_close($curl);

        return $response;
    }
}
?>

