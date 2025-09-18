<?php
class PortalCobranca
{
    private $con;
    private $link_prefixo;
    private $CURLOPT_HTTPHEADER;

    public function __construct($con, $link_prefixo, $CURLOPT_HTTPHEADER)
    {
        $this->con = $con;
        $this->link_prefixo = $link_prefixo;
        $this->CURLOPT_HTTPHEADER = $CURLOPT_HTTPHEADER;
    }
    // ************************MÉTODOS DA API***********************************************

    public function consultar($sale_id, $cpf)
    {
        $query = "
            SELECT sys_vendas_seguros.*, sys_inss_clientes.*, sys_vendas_apolices.*, sys_vendas_transacoes_tef.*, 
                sys_vendas_pgto.pgto_nm,
                GROUP_CONCAT(DISTINCT CONCAT_WS('|', 
                    sys_vendas_transacoes_tef_cartoes_secundarios.cartao_numero, 
                    sys_vendas_transacoes_tef_cartoes_secundarios.cartao_validade_mes, 
                    sys_vendas_transacoes_tef_cartoes_secundarios.cartao_validade_ano, 
                    sys_vendas_transacoes_tef_cartoes_secundarios.cartao_adm, 
                    sys_vendas_transacoes_tef_cartoes_secundarios.cartao_cvv
                )) AS cartoes_secundarios
            FROM sys_vendas_seguros
            INNER JOIN sys_inss_clientes 
                ON sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf
            INNER JOIN sys_vendas_apolices 
                ON sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id
            INNER JOIN sys_vendas_transacoes_tef 
                ON sys_vendas_seguros.vendas_id = sys_vendas_transacoes_tef.transacao_venda_id
            INNER JOIN sys_vendas_pgto 
                ON sys_vendas_seguros.vendas_pgto = sys_vendas_pgto.pgto_id
            LEFT JOIN sys_vendas_transacoes_tef_cartoes_secundarios 
                ON sys_inss_clientes.cliente_cpf = sys_vendas_transacoes_tef_cartoes_secundarios.cpf
            WHERE sys_inss_clientes.cliente_cpf = '" . $cpf . "' 
                AND sys_vendas_seguros.vendas_id = '" . $sale_id . "'
            ORDER BY sys_vendas_seguros.vendas_id DESC";


        $result = mysqli_query($this->con, $query);
        if (!$result) {
            die('Invalid query: ' . mysqli_error($this->con));
        }
        $rows = array();
        while ($r = mysqli_fetch_assoc($result)) {
            // Decodificar entidades HTML para UTF-8
            foreach ($r as $key => $value) {
                if (is_string($value)) {
                    $r[$key] = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }
            }
            if (!empty($r['cartoes_secundarios'])) {
                $cartoes_array = explode(',', $r['cartoes_secundarios']);
                foreach ($cartoes_array as &$cartao) {
                    $cartao_parts = explode('|', $cartao);
                    $cartao_assoc_array['numero'] = $cartao_parts[0];
                    $cartao_assoc_array['cartao_validade_mes'] = isset($cartao_parts[1]) ? $cartao_parts[1] : null;
                    $cartao_assoc_array['cartao_validade_ano'] = isset($cartao_parts[2]) ? $cartao_parts[2] : null;
                    $cartao_assoc_array['cartao_adm'] = isset($cartao_parts[3]) ? $cartao_parts[3] : null;
                    $cartao_assoc_array['cartao_cvv'] = isset($cartao_parts[4]) ? $cartao_parts[4] : null;

                    $cartao = $cartao_assoc_array;
                }
                unset($cartao);
                $r['cartoes_secundarios'] = $cartoes_array;
            } else {
                # Caso não haja registros na tabela secudária para o cpf fornecido.
                # A chave cartões secudários será um array vazio.
                # Isso é opcional e pode ser removido se não for necessário.
                # Caso contrário a chave cartões secudários não existirá no resultado final.
                # O que pode gerar erros em outras partes do código que esperam essa chave no resultado final.
                # Mesmo que ela esteja vazia.

                #$r['cartões secudários']=[];
            }
            $rows[] = $r;
        }

        $hoje = date('Y-m-d H:i:s');

        // Preparar a consulta SQL para evitar SQL Injection
        $update_query = "UPDATE sys_url_shortner SET access_count = access_count + 1, access_date = ? WHERE vendas_id = ?";
        $stmt = mysqli_prepare($this->con, $update_query);
        mysqli_stmt_bind_param($stmt, 'si', $hoje, $sale_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);



        return json_encode($rows);
    }

    public function consultaFinanceiro($sale_id)
    {
        $query = "SELECT * FROM sys_vendas_transacoes_boleto WHERE vendas_id = $sale_id";
        $result = mysqli_query($this->con, $query);
        $rows = array();
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        $json = json_encode($rows);
        return $json;
    }

    public function consultaFinanceiroParcelas($sale_id)
    {
        $query = "SELECT * 
        FROM sys_vendas_transacoes_seg
        INNER JOIN sys_vendas_seguros ON sys_vendas_transacoes_seg.transacao_id_venda = sys_vendas_seguros.vendas_id
        WHERE transacao_id_venda = $sale_id GROUP BY transacao_mes";
        $result = mysqli_query($this->con, $query);
        $rows = array();
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        $json = json_encode($rows);
        return $json;
    }

    public function consultarAsaas($cpf)
    {
        $query = "SELECT * FROM sys_vendas_transacoes_boleto
                    JOIN sys_inss_clientes
                    ON sys_vendas_transacoes_boleto.cliente_cpf = sys_inss_clientes.cliente_cpf
                    WHERE sys_vendas_transacoes_boleto.cliente_cpf = '$cpf'
                    LIMIT 1;";
        $result = mysqli_query($this->con, $query);
        $rows = array();
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        $json = json_encode($rows);
        return $json;
    }

    function criarCobrancaBoleto($data, $key)
    {
        $url = "https://www.asaas.com/api/v3/payments";
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'access_token: ' . $key,
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);

        // Decodifica a string JSON em um array associativo
        $result_array = json_decode($result, true);

        // Se a cobrança for criada, salva no banco de dados
        if ($result) {
            $query =
                "INSERT INTO sys_vendas_transacoes_boleto (dateCreated, customer, dueDate, value, status, vendas_id, id_boleto, invoiceUrl, bankSlipUrl, invoiceNumber, cliente_cpf, parcelas_correspondentes, username) 
            VALUES ('" . $result_array['dateCreated'] . "','" . $result_array['customer'] . "','" . $result_array['dueDate'] . "'," . $result_array['value'] . ", 'Emitido o boleto', '" . $data['vendas_id'] . "', '" . $result_array['id'] . "', '" . $result_array['invoiceUrl'] . "', '" . $result_array['bankSlipUrl'] . "','" . $result_array['invoiceNumber'] . "','" . $data['cpfCnpj'] . "', '" . $data['parcelas'] . "', 'Portal do Cliente')";
            mysqli_query($this->con, $query);
        }
        return $result;
    }


    function cadastrarCliente($data, $key)
    {
        $url = "https://www.asaas.com/api/v3/customers";
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'access_token: ' . $key,
                'Content-Length: ' . strlen($data_string)
            )
        );
        $cpf = $data['cpfCnpj'];
        $result = curl_exec($ch);
        $result_array = json_decode($result, true);
        if ($result) {
            $query = "UPDATE sys_vendas_transacoes_boleto SET customer = '" . $result_array['id'] . "' WHERE cliente_cpf = $cpf AND vendas_id = " . $result_array['externalReference'] . "";
            mysqli_query($this->con, $query);
        }
        return $result;
    }

    function buscarCartoes($cpf)
    {
        $query = "SELECT * FROM sys_vendas_transacoes_tef_cartoes_secundarios WHERE cpf = $cpf";
        $result = mysqli_query($this->con, $query);
        $rows = array();
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        $json = json_encode($rows);
        return $json;
    }

    function cadastrarCartaoAdicional($cpf, $cartao_numero, $validade_mes, $validade_ano, $cartao_adm, $cvv)
    {
        if (!empty($cpf)) {
            $query = "INSERT INTO sys_vendas_transacoes_tef_cartoes_secundarios (`id`, `cpf`, `cartao_numero`, `cartao_validade_mes`, `cartao_validade_ano`, `cartao_adm`, `cartao_cvv`) VALUES (NULL, '" . $cpf . "', '" . $cartao_numero . "', '" . $validade_mes . "','" . $validade_ano . "', '" . $cartao_adm . "', '" . $cvv . "')";
            $insere_cartao = mysqli_query($this->con, $query) or die(mysqli_error($this->con));
            // Testar se foi executado
            if (mysqli_affected_rows($this->con) > 0) {
                echo "Cartão cadastrado com sucesso!";
            } else {
                echo "Erro ao cadastrar cartão!";
            }
        } else {
            echo "Preencha todos os campos!";
        }
    }

    // *********************************************************************************************************************************
    // ************************ FUNÇÃO QUE INICIA A TRANSAÇÃO COM O CARTÃO DE CRÉDITO **************************************************
    // *********************************************************************************************************************************
    function iniciaTransacaoCartao($token, $cpf, $username, $user_id, $venda_id, $plano, $card_adm, $card_num, $card_cvv, $card_validade_mes, $card_validade_ano, $transacao_valor, $salvar_cartao_principal, $salvar_cartao_secundario)
    {
        $relatorio = '';
        $relatorio .= 'Iniciando transação com os dados... Token: ' . $token . ' CPF: ' . $cpf . ' Username: ' . $username . ' User ID: ' . $user_id . ' Venda ID: ' . $venda_id . ' Plano: ' . $plano . ' Card Adm: ' . $card_adm . ' Card Num: ' . $card_num . ' Card CVV: ' . $card_cvv . ' Card Validade Mes: ' . $card_validade_mes . ' Card Validade Ano: ' . $card_validade_ano . ' Transação Valor: ' . $transacao_valor . ' Salvar Cartão Principal: ' . $salvar_cartao_principal . ' Salvar Cartão Secundário: ' . $salvar_cartao_secundario;
        $relatorio .= "<br>";
        $dados = (object) array(
            'token' => $token,
            'cpf' => $cpf,
            'username' => $username,
            'user_id' => $user_id,
            'venda_id' => $venda_id,
            'plano' => $plano,
            'card_adm' => $card_adm,
            'card_num' => $card_num,
            'card_cvv' => $card_cvv,
            'card_validade_mes' => $card_validade_mes,
            'card_validade_ano' => $card_validade_ano,
            'transacao_valor' => $transacao_valor,
            'salvar_cartao_principal' => $salvar_cartao_principal,
            'salvar_cartao_secundario' => $salvar_cartao_secundario
        );
        $relatorio .= 'Dados convertidos em objeto...';
        $relatorio .= "<br>";
        $relatorio .= 'Verificando token...';
        $relatorio .= "<br>";
        if ($dados->token === 'EsearR31234fpssa0vfc9o') { // Início Veririfca token
            $relatorio .= 'Token OK!';
            $relatorio .= "<br>";
            function insereRegistroTransacao($dados, $con)
            {
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
                    '" . $dados->cpf . "',
                    '" . $dados->username . "',
                    '" . $dados->user_id . "',
                    '" . $dados->token . "',
                    '" . $dados->transacao_valor . "',
                    '" . $dados->venda_id . "',
                    NOW(),
                    NOW(),
                    '" . $dados->plano . "',
                    '" . $dados->card_adm . "',
                    '',
                    '" . $dados->card_cvv . "',
                    '" . $dados->card_num . "',
                    '" . $dados->card_validade_mes . "',
                    '" . $dados->card_validade_ano . "'
                )";

                $result_transaction = mysqli_query($con, $insere_transaction_query);
                return mysqli_insert_id($con);
            } // Fim insereRegistroTransacao

            $relatorio .= 'Inserindo registro de transação...';
            $relatorio .= "<br>";

            $transaction_id = insereRegistroTransacao($dados, $this->con);

            $relatorio .= 'Registro de transação inserido com sucesso! ID: ' . $transaction_id;
            $relatorio .= "<br>";

            $order_id = $transaction_id . "_C";

            $relatorio .= 'Gerando order_id: ' . $order_id;
            $relatorio .= "<br>";

            function valorParaAmount($valor)
            {
                $valor = str_replace(",", "", $valor);
                $valor = str_replace(".", "", $valor);
                return floatval($valor);
            } // Fim valorParaAmount

            $relatorio .= 'Convertendo valor para amount...';
            $relatorio .= "<br>";

            $payment_amount = valorParaAmount(number_format($dados->transacao_valor, 2, '.', '')); // valor da parcela, exemplo 19,90 R$ deve ser representado como 1990 sem ponto nem virgulas incluindo os centavos sempre.

            $relatorio .= 'Valor convertido para amount: ' . $payment_amount;
            $relatorio .= "<br>";

            $relatorio .= 'Iniciando cURL...';
            $relatorio .= "<br>";

            // Início do cURL
            $dataPreAut = (object) array(
                'order_id' => $order_id,
                'amount' => $payment_amount,
                'authorizer_id' => "2"
            );

            $relatorio .= 'Dados para cURL convertidos em objeto...';
            $relatorio .= "<br>";

            $url_pre_aut = $this->link_prefixo . '/e-sitef/api/v1/transactions/';

            $relatorio .= 'URL para cURL: ' . $url_pre_aut;
            $relatorio .= "<br>";

            function executaCurlPreAutorizacao($dataPreAut, $url, $CURLOPT_HTTPHEADER)
            { // Início executaCurlPreAutorizacao
                // CERTIFICADO HACK
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

                if ($erro) {
                    return $erro;
                } else {
                    return $resposta;
                }
            } // Fim executaCurlPreAutorizacao

            $relatorio .= 'cURL executado com sucesso!';
            $relatorio .= "<br>";

            $curl_resposta = executaCurlPreAutorizacao($dataPreAut, $url_pre_aut, $this->CURLOPT_HTTPHEADER);

            $relatorio .= 'Resposta do cURL: ' . $curl_resposta;
            $relatorio .= "<br>";

            $curl_resposta = json_decode($curl_resposta);

            $relatorio .= 'Resposta do cURL convertida em objeto...';

            $relatorio .= "<br>";

            echo $relatorio;
        } // Fim Veririfca token
    } // Fim iniciaTransacaoCartao

    // *********************************************************************************************************************************
    // ************************ FUNÇÃO QUE EXECUTA A COBRANÇA COM O CARTÃO DE CRÉDITO **************************************************
    // *********************************************************************************************************************************
    function cobrarCartao($token, $cpf, $username, $user_id, $venda_id, $plano, $card_adm, $card_num, $card_validade_mes, $card_validade_ano, $transacao_valor, $transaction_nit, $transaction_id)
    {

        $dados = (object) array(
            'token' => $token,
            'cpf' => $cpf,
            'username' => $username,
            'user_id' => $user_id,
            'venda_id' => $venda_id,
            'plano' => $plano,
            'card_adm' => $card_adm,
            'card_num' => $card_num,
            'card_validade_mes' => $card_validade_mes,
            'card_validade_ano' => $card_validade_ano,
            'transacao_valor' => $transacao_valor,
            'transaction_nit' => $transaction_nit,
            'transaction_id' => $transaction_id
        );

        if ($dados->token === 'EsearR31234fpssa0vfc9o') { // Início Verifica token
            // registro inicial da transação que será efetuada e captura do id.
            $transaction_id = $dados->transaction_id;
            $card_number = $dados->card_num;
            $card_expiry_date = $dados->card_validade_mes . "" . $dados->card_validade_ano;

            // Início cURL
            $dataTransaction = (object) array(
                'card' => (object) array(
                    'number' => $card_number,
                    'expiry_date' => $card_expiry_date
                )
            );

            $url_do_transaction = $this->link_prefixo . '/e-sitef/api/v1/payments/' . $dados->transaction_nit;

            function doTransactionCurl($dataTransaction, $url, $CURLOPT_HTTPHEADER)
            { // Início doTransactionCurl
                // CERTIFICADO HACK
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
                // FIM CERTIFICADO HACK

                @$data_corpo = "{
                        \"amount\" : \"{$dataTransaction->amount}\",
                        \"installments\" : \"1\",
                        \"installment_type\" : \"4\",
                        \"card\" : {
                            \"number\" : \"{$dataTransaction->card->number}\",
                            \"expiry_date\" : \"{$dataTransaction->card->expiry_date}\"
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

                if ($erro) {
                    return $erro;
                } else {
                    return $resposta;
                }
            } // Fim doTransactionCurl



            // Tenta efetivar a transação via curl            
            $response_transaction = doTransactionCurl($dataTransaction, $url_do_transaction, $this->CURLOPT_HTTPHEADER);

            return $response_transaction;




            // FIM CURL
            // Caso a transação não tenha obtido sucesso e o erro foi 28 (tempo limite de resposta excedido)
            if ($response_transaction->transaction_error_number != '0' && $response_transaction->transaction_error_number == '28') {
                $tentativa = 1;
                while ($tentativa <= 3) {
                    $url_getstatus = $link_prefixo . '/e-sitef/api/v1/transactions/' . $response_transaction->payment->nit;
                    // tenta verifica se a transação chegou a ocorrer e com qual resultado, afinal não foi possível obter a resposta antes.
                    $response_getstatus = getStatus($url_getstatus, $CURLOPT_HTTPHEADER);
                    if ($response_getstatus->getstatus_error_number != 0) {
                        $tentativa++;
                    } else {
                        $tentativa = 4; // encerra while
                    }
                }

                $response_getstatus->getstatus = true;
                $log_resposta = $response_getstatus->resposta;
                $log_resposta_ecoded = json_encode($response_getstatus->resposta);
                $final_response = json_encode($response_getstatus, true);
            } else { // se obteve sucesso ou um erro diferente de 28...
                $log_resposta = $response_transaction->resposta;
                $log_resposta_ecoded = json_encode($response_transaction->resposta);
                $final_response = json_encode($response_transaction, true);
            }
            echo json_encode($response_transaction);
            return json_encode($response_transaction);

            function atualizaTransacao($con, $dados)
            { // Início atualizaTransacao
                $update_transaction_query = "UPDATE sys_vendas_transacoes_tef
                    SET transacao_nit = '" . mysql_real_escape_string($dados->transacao_nit) . "',
                    transacao_status = '" . mysql_real_escape_string($dados->transacao_status) . "'
                    WHERE transacao_id = '" . $transaction_id . "'";
                $result_transaction = mysql_query($update_transaction_query);
            } // Fim atualizaTransacao

            function getStatus($url, $CURLOPT_HTTPHEADER)
            {
                // CERTIFICADO HACK
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

                $response->resposta = json_decode(curl_exec($curl), false);

                $response->erro = curl_error($curl);
                $response->getstatus_error_number = curl_errno($curl);

                curl_close($curl);
                return $response->resposta;
            } // Fim getStatus

        } // Fim Verifica token

    } // Fim cobrarCartao

    function cadastraVenda()
    {

        $json2 = json_decode($json);
        $parcelas = [];
        foreach (json_decode($json) as $key => $value) {
            if ($key == 'parcelas') {
                foreach ($value as $key => $value) {
                    $parcelas[] = $value;
                }
            }
        }
        $resultados = "";
        $total_parcelas = mysqli_query($this->con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $json2->venda_id . "'")
            or die(mysqli_error($this->con));
        $row_total_parcelas = mysqli_fetch_array($total_parcelas);
        $resultados .= "Total de parcelas da transação: " . $row_total_parcelas['total'] . "<br>";
        $resultados .= "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $json2->venda_id . "'";
        $resultados .= "<br>";
        $resultados .= "*****************************************************<br>";
        // Pegando a data de hoje no formato aaaa-mm-dd
        $data_hoje = date('Y-m-d');
        for ($i = 0; $i < count($parcelas); $i++) {
            $resultados .= "Atualiza as parcelas que foram pagas: <br>";
            $query_atualiza_parcelas_correspondentes = "UPDATE `sys_vendas_transacoes_seg` SET `transacao_recebido` = '1', transacao_motivo = '0', transacao_data = '" . $data_hoje . "'  WHERE `transacao_id` = '" . $parcelas[$i]->transacao_id . "'";
            $atualiza_parcelas = mysqli_query($this->con, $query_atualiza_parcelas_correspondentes);
            $resultados .= $query_atualiza_parcelas_correspondentes;
            $resultados .= "<br>";
        }

        $result_pagas = mysqli_query($this->con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $json2->venda_id . "' AND transacao_recebido = 1;")
            or die(mysqli_error($this->con));
        $row_pagas = mysqli_fetch_array($result_pagas);
        $parcelas_pagas = $row_pagas["total"];

        $resultados .= "*****************************************************<br>";
        $resultados .= "Total de parcelas pagas: " . $parcelas_pagas . "<br>";
        $resultados .= "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $json2->venda_id . "' AND transacao_recebido = 1;";
        $resultados .= "<br>";
        $resultados .= "*****************************************************<br>";
        $resultados .= "Total de Parcelas: " . $row_total_parcelas["total"] . ", sendo que " . $parcelas_pagas . " estão pagas.<br>";
        $resultados .= "*****************************************************<br>";
        $resultados .= "Caso todas as parcelas estejam pagas, será atualizado o status da venda para 67 (Ativo) e inserido o registro.<br><br>";
        if ($row_total_parcelas['total'] == $parcelas_pagas) {
            $query_atualiza_vendas = "UPDATE `sys_vendas_seguros` SET `vendas_status` = '67' WHERE `vendas_id` = '" . $json2->venda_id . "'";
            $atualiza_vendas = mysqli_query($this->con, $query_atualiza_vendas);
            $resultados .= $query_atualiza_vendas;
            $resultados .= "<br><br>";
            $insere_registro_venda_query = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, `vendas_id`, `registro_usuario`, `registro_obs`, `registro_status`, `registro_data`) VALUES (NULL, '" . $json2->venda_id . "','integrador.automatico','Boleto atualizado como recebido a venda ATIVA','67',NOW());";
            $insere_registro_venda = mysqli_query($this->con, $insere_registro_venda_query);
            $resultados .= $insere_registro_venda_query;
            $resultados .= "<br><br>";
            $resultados .= "Removendo o cliente da campanha de cobrança, caso esteja<br><br>";
            include($path_includes . $Arquivo_conect);
            include($path_includes . "utf8.php");
            include($path_includes . "cliente/espelha.php");
            include($path_includes . "connect_db02.php");
            include($path_includes . "utf8.php");
            include($path_includes . "cliente/espelha_insere.php");
            // Atualiza o cliente no banco de dados do sistema de campanhas ********
            // Query que remove da campanha de cobrança
            $query_atualiza_cliente_campanha = "UPDATE sys_inss_clientes SET cliente_campanha_id = '0', cliente_usuario = 'integrador.automatico', cliente_alteracao = NOW() WHERE cliente_cpf='" . $json2->cpf . "';";
            $atualiza_cliente_campanha = mysqli_query($con, $query_atualiza_cliente_campanha);
            $resultados .= $query_atualiza_cliente_campanha;
            $resultados .= "<br>";
            $resultados .= "*****************************************************<br>";
        }

        $resultados .= "Senão mantém as parcelas que já foram atualizadas e não mexe no status da venda.<br>";
        $resultados .= "Insere o registro do pagamento, colocando o status atual da venda.<br>";
        $insere_registro_venda_query_parcelas_em_aberto = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, `vendas_id`, `registro_usuario`, `registro_obs`, `registro_status`, `registro_data`) VALUES (NULL, '" . $json2->venda_id . "','integrador.automatico','Pagamento realizado mas nem todas as parcelas foram pagas','',NOW());";
        $insere_registro_venda_parcelas_em_aberto = mysqli_query($this->con, $insere_registro_venda_query_parcelas_em_aberto);
        $resultados .= $insere_registro_venda_query_parcelas_em_aberto;
        $resultados .= "<br>";
        $resultados .= "*****************************************************<br>";

        echo $resultados;
    }




    // ********************FIM DOS MÉTODOS DA API*******************************************
}
