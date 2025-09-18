<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$token           = $_POST['token'];
$username        = $_POST['username'];
$userid          = $_POST['user_id'];
$vendas_apolice  = $_POST['apolice'];
$cliente_cpf     = $_POST['cpf'];
$transacao_valor = $_POST['transacao_valor'];
$card_adm        = $_POST['card_adm'];
$card_band       = $_POST['card_band'];
$card_num        = $_POST['card_num'];
$card_val_mes    = $_POST['card_validade_mes'];
$card_val_ano    = $_POST['card_validade_ano'];
$card_ccv        = '666';
$nit             = $_POST['transaction_nit'];
$transacao_id    = $_POST['transaction_id'];

// Limpar o CPF, removendo os pontos e traços
$removidos = array(".", "-");
$cliente_cpf = str_replace($removidos, "", $cliente_cpf);

// Pegar a data e hora de hoje no formato aaaa-mm-dd hh:mm:ss
date_default_timezone_set('America/Sao_Paulo');
$transacao_data_ = date("Y-m-d H:i:s");
$transacao_data_op = date("Y-m-d");
$transacao_mes = date("m");

$transacao_data = str_replace(array("-", " ", ":"), "", $transacao_data_);

$data = array(
    "Token" => $token,
    "Username" => $username,
    "User ID" => $userid,
    "Apolice" => $vendas_apolice,
    "CPF" => $cliente_cpf,
    "Transação Valor" => $transacao_valor,
    "Admin do Cartão" => $card_adm,
    "Bandeira do Cartão" => $card_band,
    "Número do Cartão" => $card_num,
    "Validade do Cartão - Mês" => $card_val_mes,
    "Validade do Cartão - Ano" => $card_val_ano,
    "CCV do Cartão" => $card_ccv,
    "NIT" => $nit,
    "ID da Transação" => $transacao_id,
    "Data da Transação" => $transacao_data
);

// ############################################################################################################
$host = "10.100.0.22";
$username = "root";
$password = "Theredpil2001";
$database = "sistema";

$con = new mysqli($host, $username, $password, $database);

if ($con->connect_errno) {
    echo "Falha na conexão com o banco de dados: " . $con->connect_error;
    exit();
}
$stmt = $con->prepare("INSERT INTO `sys_vendas_seguros` (
    `cliente_cpf`,
    `vendas_consultor`,
    `vendas_banco`,
    `vendas_apolice`,
    `vendas_valor`,
    `vendas_vencimento_fatura`,
    `vendas_dia_venda`,
    `vendas_cartao_adm`,
    `vendas_cartao_num`,
    `vendas_cartao_validade_mes`,
    `vendas_cartao_validade_ano`,
    `vendas_cartao_cvv`,
    `vendas_status`,
    `vendas_user`, 
    `vendas_pgto` 
) VALUES (?, '278', '11', ?, ?, '', ?, ?, ?, ?, ?, '666', '2', ?, '2')");
$stmt->bind_param('sssssssss', $cliente_cpf, $vendas_apolice, $transacao_valor, $transacao_data_, $card_adm, $card_num, $card_val_mes, $card_val_ano, $username);
// executar a consulta
if ($stmt->execute()) {
    // pegar o ID da inserção ###### DEFINIDO O ID DA VENDA ######    
    $id_venda = $stmt->insert_id;
    // Atualizando a tabela sys_vendas_transacoes_tef
    $stmt = $con->prepare("UPDATE sys_vendas_transacoes_tef SET transacao_venda_id = ? WHERE transacao_cliente_cpf = ?");
    $stmt->bind_param('ss', $id_venda, $cliente_cpf);
    if ($stmt->execute()) {
        // CRIANDO A PARCELA
        $transacao_id = $id_venda . "_" . $transacao_data . "_1_2";
        // echo "Transação ID: " . $transacao_id . "<br>";
        $stmt = $con->prepare("INSERT INTO `sistema`.`sys_vendas_transacoes_seg` (`transacao_id`, 
                `importacao_id`, 
                `transacao_id_venda`, 
                `transacao_proposta`, 
                `transacao_valor`, 
                `transacao_recebido`, 
                `transacao_motivo`,
                `transacao_data_importacao`, 
                `transacao_data`, 
                `transacao_mes`, 
                `transacao_parcela`, 
                `transacao_tipo`) 
                VALUES (?, 
                ?, 
                ?, 
                ?, 
                ?, 
                '1',
                '',
                NOW(),
                ?, 
                ?, 
                '1',
                '2')");
        $stmt->bind_param('sssssss', $transacao_id, $importacao_id, $id_venda, $id_venda, $transacao_valor, $transacao_data_op, $transacao_mes);
        if ($stmt->execute()) {
            $stmt = $con->prepare("SELECT cliente_nome, cliente_nascimento, cliente_email, cliente_sexo, cliente_est_civil, cliente_rg FROM `sys_inss_clientes`
        WHERE `cliente_cpf` LIKE ? LIMIT 0 , 30");
            $stmt->bind_param('s', $cliente_cpf);
            $stmt->execute();
            $stmt->store_result();            
            $stmt->bind_result($nomeNome, $nasc, $email, $sexo, $estadoCivil, $rg);
            $stmt->fetch();
            $stmt->free_result();
            
            $nomeNm = $nomeNome;
            // $sexo = $row_result_nome_nascimento['cliente_sexo'];
            // $estadoCivil = $row_result_nome_nascimento['cliente_est_civil'];
            // $rg = $row_result_nome_nascimento['cliente_rg'];
            // if (!$row_result_nome_nascimento['cliente_sexo']) {
            //     $sexo = $row_client_nm['cliente_sexo'];
            // }
            // if (!$row_result_nome_nascimento['cliente_est_civil']) {
            //     $estadoCivil = $row_client_nm['cliente_est_civil'];
            // }
            // if (!$row_result_nome_nascimento['cliente_rg']) {
            //     $rg = $row_client_nm['cliente_rg'];
            // }
            $url = 'https://grupofortune.com.br/integracao/softwareexpress/payment_request_schedule.php?token=EsearR31234fpssa0vfc9o';
            $transacao_data_exp = $_POST['card_validade_mes'] . substr($_POST['card_validade_ano'], -2);
            $data = array(
                'cpf' => $cliente_cpf,
                'username' => $username,
                'user_id' => $userid,
                'token' => $token,
                'transacao_valor' => $transacao_valor,
                'transacao_cartao_cvv' => $card_ccv,
                'transacao_cartao_num' => $card_num,
                'transacao_data_exp' => $transacao_data_exp,
                'authorizer_id' => $card_adm,
                'transacao_venda_id' => $id_venda
            );
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $result = json_decode($result, true);
            // ############ ATIVAÇÃO DO AGENDAMENTO: ############
            if ($result['data']['schedule']['status'] == "NOV") {
                $url = 'https://grupofortune.com.br/integracao/softwareexpress/payment_do_schedule.php?token=EsearR31234fpssa0vfc9o';
                $data = array(
                    'cpf' => $cliente_cpf,
                    'username' => $username,
                    'user_id' => $userid,
                    'transacao_cartao_num' => $card_num,
                    'transacao_data_exp' => $transacao_data_exp,
                    'transacao_agendamento_sid' => $result['data']['schedule']['sid']
                );
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data)
                    )
                );
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $result = json_decode($result, true);
                if ($result['resposta']['schedule']['status'] == "ATV") { 
                    if ($vendas_apolice != 157) {
                        $retorno_tempo_assist = addTempo_assist($id_venda, $vendas_apolice, $nomeNm, $nomeNome, $nasc, $cliente_cpf, $email, $sexo, $estadoCivil, $rg);
                        $vendas_num_apolice = $retorno_tempo_assist["idItemCoberto"];
                        if ($vendas_num_apolice) {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                            $registro_obs = "Agendamento de cobrança recorrente ativado ok! Assitencia cadastrada com sucesso codigo - " . $vendas_num_apolice;
                            //ATUALIZAR A VENDA:
                        } else {
                            $registro_obs = "Cadastro da assistência não realizado. Motivo: " . $retorno_tempo_assist["descricao"];
                        }
                    } else {
                        $registro_obs = "Vendas reativada sem cadastro de assistência, pois já estava previamente cadastrada com o codigo: " . $row_old['vendas_num_apolice'];
                    }                 

                    try {
                        $stmt = $con->prepare("UPDATE sys_vendas_seguros SET vendas_proposta=?, vendas_num_apolice=? WHERE vendas_id=?");
                        if ($stmt === false) {
                            throw new Exception('Erro na preparação da consulta: ' . $con->error);
                        }
                        $stmt->bind_param('ssi', $id_venda, $vendas_num_apolice, $id_venda);
                        $stmt->execute();
                        $stmt->store_result();
                        $stmt->free_result();
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                    registraHistorico($con, $retorno_tempo_assist["codigo"], 'Agendamento registrado', '67', $registro_obs, '67', $id_venda, 'portal.apobem');
                }
            } else {
                echo 'Erro na criacao do agendamento de recorrência.';
                registraHistorico($con, $retorno_tempo_assist["codigo"], 'Agendamento registrado', $_GET['vendas_status'], "Erro na ativação do agendamento de recorrência!", '', $id_venda, 'portal.apobem');
                $nao_redireciona = 1;
            }
        } else {
            echo "Erro na inserção: " . $stmt->error . "<br>";
        }
    } else {
        echo "Erro na atualização em sys_vendas_transacoes_tef: " . $stmt->error . "<br>";
    }
} else {
    echo "Erro ao registrar a venda! " . $stmt->error . "<br>";
}
// Função para adicionar usuario na base de dados da tempo assist, função chamada somente quando e feito a cobrança recorrente do cartao de credito, em vendas ativadas, no momento de salvar a venda.
function addTempo_assist($id_venda, $vendas_apolice, $nomeNm, $nomeNome, $nasc, $cpf, $email, $sexo, $estadoCivil, $rg)
{
    $url = 'https://grupofortune.com.br/integracao/tempo_assist/cadastro_segurado.php';
    $data = array(
        'vendas_id' => $id_venda,
        'vendas_apolice' => $vendas_apolice,
        'clients_nm' => $nomeNm,
        'cliente_nome' => $nomeNome,
        'cliente_nascimento' => $nasc,
        'cliente_cpf' => $cpf,
        'cliente_email' => $email,
        'cliente_sexo' => $sexo,
        'cliente_est_civil' => $estadoCivil,
        'cliente_rg' => $rg
    );
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, true, $context);

    //$decodedText = html_entity_decode($result);
    $json = json_encode($result);
    //echo $json;
    $objeto = json_decode($result, true);
    echo "descricao:" . $objeto['descricao'];
    "<br>";
    echo "idItemCoberto:" . $objeto['idItemCoberto'];
    "<br>";
    echo "inicioVig: " . $objeto['dadosRequest']['inicioVig'];
    "<br>";
    echo "codigo: " . $objeto['codigo'];
    "<br>";
    return $objeto;
}
function registraHistorico($con, $cod_retorno, $authorizer_message, $vendas_status, $vendas_obs, $vendas_status_old, $id_venda, $vendas_user)
{
    if ($cod_retorno) {
        $stmt = $con->prepare("SELECT retorno_definicao FROM sys_vendas_transacoes_retorno WHERE retorno_codigo = ?;");
        $stmt->bind_param('s', $cod_retorno);
        $stmt->execute();        
        $stmt->bind_result($retorno_definicao);
        $stmt->fetch();

        $vendas_obs .= " \n - RETORNO DA TRANSA&Ccedil;&Atilde;O:" . $authorizer_message;
        if ($vendas_status != 9) {
            $vendas_obs .= " \n - RETORNO DA TRANSA&Ccedil;&Atilde;O:" . $cod_retorno . " - " . $retorno_definicao;
        }
    }
    $registro_cobranca = (($vendas_status_old != '89') && ($vendas_status == '89')) ? 2 : 1;
    $registro_retencao = (($vendas_status_old != '45') && ($vendas_status == '45')) ? 2 : 1;
    $stmt = $con->prepare("INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, 
    `vendas_id`, 
    `registro_usuario`, 
    `registro_obs`, 
    `registro_status`, 
    `registro_data`,
    `registro_cobranca`, 
    `registro_retencao`) 
    VALUES (NULL, 
    ?,
    ?,
    ?,
    ?,
    NOW(),
    ?,
    ?);");

    $stmt->bind_param('issiii', $id_venda, $vendas_user, $vendas_obs, $vendas_status, $registro_cobranca, $registro_retencao);

    if ($stmt->execute()) {
        $registro_id = $stmt->insert_id;
        echo "Histórico Registrado com Sucesso. </br>";
    } else {
        die('Error: ' . $stmt->error);
    }

    $stmt->close();
}


$stmt->close();
