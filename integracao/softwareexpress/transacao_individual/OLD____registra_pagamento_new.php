<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

date_default_timezone_set('America/Sao_Paulo');

include("../get_erros.php");
include("../variaveis_fixas.php");

$db_ip = "10.100.0.22";
$ip = $_SERVER["REMOTE_ADDR"]; //Pego o IP

$con = mysqli_connect("10.100.0.22", "root", "Theredpil2001", "sistema");
if (!$con) {
    die('Could not connect: ' . mysqli_error());
}

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$username = $_POST['username'];
$userid = $_POST['user_id'];
$vendas_apolice = $_POST['apolice'];

$cliente_cpf = $_POST['cpf'];
$removidos = array(".", "-");
$cliente_cpf = str_replace($removidos, "", $cliente_cpf);
// Pegar a data e hora de hoje no formato aaaa-mm-dd hh:mm:ss
$transacao_data_ = date("Y-m-d H:i:s");
$transacao_data_op = date("Y-m-d");
$transacao_mes = date("m");

$transacao_data = str_replace(array("-", " ", ":"), "", $transacao_data_);

$registra_venda = "INSERT INTO `sys_vendas_seguros` (
    `cliente_cpf`,
    `vendas_consultor`,
    `vendas_apolice`,
    `vendas_valor`,
    `vendas_vencimento_fatura`,
    `vendas_dia_venda`,
    `vendas_cartao_adm`,
    `vendas_cartao_band`,
    `vendas_cartao_num`,
    `vendas_cartao_validade_mes`,
    `vendas_cartao_validade_ano`,
    `vendas_cartao_cvv`,
    `vendas_status`,
    `vendas_user`
) VALUES (
    '" . $cliente_cpf . "',
    '278',
    '" . $_POST['apolice'] . "',
    '" . $_POST['transacao_valor'] . "',
    '',
    '" . $transacao_data_ . "',
    '" . $_POST['card_adm'] . "',
    '" . $_POST['card_band'] . "',
    '" . $_POST['card_num'] . "',
    '" . $_POST['card_validade_mes'] . "',
    '" . $_POST['card_validade_ano'] . "',
    '666',
    '2',
    '" . $_POST['username'] . "'
)";

if (mysqli_query($con, $registra_venda)) {
    $id_venda = mysqli_insert_id($con); // Obter o ID gerado pela última consulta INSERT


    // Atualizar sys_vendas_transacoes_tef
    $atualiza_outra_tabela = "UPDATE sys_vendas_transacoes_tef SET transacao_venda_id = '$id_venda' WHERE transacao_cliente_cpf = '" . $cliente_cpf . "'";
    if (mysqli_query($con, $atualiza_outra_tabela)) {
        // Criar parcela ----------------------------------------------------------------------------------------#
        $transacao_id = $id_venda . "_" . $transacao_data . "_1_2";
        $sql = "INSERT INTO `sistema`.`sys_vendas_transacoes_seg` (`transacao_id`, 
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
        VALUES ('$transacao_id',
        '$importacao_id',
        '$id_venda',
        '$id_venda',
        '" . $_POST['transacao_valor'] . "',
        '1',
        '',
        NOW(),
        '$transacao_data_op',
        '$transacao_mes',
        '1',
        '2');";
        if (mysqli_query($con, $sql)) {
            echo "Cadastrada a venda em sys_vendas_seguros, atualizada sys_vendas_transacoes_tef e cadastrada a parcela em sys_vendas_transacoes_seg";
            // ##########################################################################################################################################################
            // ##########################################################################################################################################################
            // ##########################################################################################################################################################
            // ##########################################################################################################################################################
            // ############ REQUISIÇÃO DE AGENDAMENTO ###################################################################################################################
            // ##########################################################################################################################################################

            $result_nome_nascimento = mysql_query($con,"SELECT cliente_nome, cliente_nascimento, cliente_email, cliente_sexo, cliente_est_civil, cliente_rg FROM `sys_inss_clientes`
            WHERE `cliente_cpf` LIKE '" . $cliente_cpf . "'LIMIT 0 , 30;")
                or die(mysql_error());
            $row_result_nome_nascimento = mysql_fetch_array($result_nome_nascimento);

            $nasc = $row_result_nome_nascimento['cliente_nascimento'];
            $email = $row_result_nome_nascimento['cliente_email'];
            $nomeNome = $row_result_nome_nascimento['cliente_nome'];
            if (!$row_result_nome_nascimento["cliente_nome"]) {
                $result_client_nm = mysql_query($con, "SELECT clients_nm AS cliente_nome, 
                clients_birth AS cliente_nascimento, 
                clients_rg AS cliente_rg, 
                cliente_sexo, 
                cliente_est_civil,
                cliente_rg_exp,
                clients_street_complet AS cliente_endereco, 
                clients_district AS cliente_bairro, 
                clients_city AS cliente_cidade, 
                clients_postalcode AS cliente_cep, 
                clients_state AS cliente_uf, 
                clients_contact_phone1 AS cliente_telefone, 
                clients_contact_phone2 AS cliente_celular, 
                clients_contact_mail1 AS cliente_email, 
                clients_patent AS cliente_cargo 
                FROM sys_clients WHERE clients_cpf = '" . $row_old['cliente_cpf'] . "';")
                    or die(mysql_error());
                $row_client_nm = mysql_fetch_array($result_client_nm);
            }
            $nomeNm = $row_client_nm['cliente_nome'];
            $sexo = $row_result_nome_nascimento['cliente_sexo'];
            $estadoCivil = $row_result_nome_nascimento['cliente_est_civil'];
            $rg = $row_result_nome_nascimento['cliente_rg'];

            if (!$row_result_nome_nascimento['cliente_sexo']) {
                $sexo = $row_client_nm['cliente_sexo'];
            }
            if (!$row_result_nome_nascimento['cliente_est_civil']) {
                $estadoCivil = $row_client_nm['cliente_est_civil'];
            }
            if (!$row_result_nome_nascimento['cliente_rg']) {
                $rg = $row_client_nm['cliente_rg'];
            }
            $url = 'https://grupofortune.com.br/integracao/softwareexpress/payment_request_schedule.php?token=EsearR31234fpssa0vfc9o';

            $transacao_data_exp = $_POST['card_validade_mes'] . substr($_POST['card_validade_ano'], -2);

            $data = array(
                'cpf' => $cliente_cpf,
                'username' => $username,
                'user_id' => $userid,
                'token' => 'EsearR31234fpssa0vfc9o',
                'transacao_valor' => $_POST['transacao_valor'],
                'transacao_cartao_cvv' => '666',
                'transacao_cartao_num' => $_POST['card_num'],
                'transacao_data_exp' => $transacao_data_exp,
                'authorizer_id' => $_POST['card_adm'],
                'transacao_venda_id' => $id_venda
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

            if ($userid == 957) {
                echo "Tentou requisitar o schedule";
                echo "<pre>";
                print_r($result);
                echo "</pre>";
            }

            // ############ ATIVAÇÃO DO AGENDAMENTO:
            if ($result['data']['schedule']['status'] == "NOV") {
                $url = 'https://grupofortune.com.br/integracao/softwareexpress/payment_do_schedule.php?token=EsearR31234fpssa0vfc9o';
                $data = array(
                    'cpf' => $cliente_cpf,
                    'username' => $username,
                    'user_id' => $userid,
                    'transacao_cartao_num' => $_POST['card_num'],
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
                if ($userid == 957) {
                    echo "Tentou requisitar o schedule";
                    echo "<pre>";
                    print_r($result);
                    echo "</pre>";
                }
                if ($result['resposta']['schedule']['status'] == "ATV") {
                    echo '<div align="center">Agendamento de cobrança recorrente ativado ok!</div>';
                    $_GET['vendas_status'] = "67";
                    if ($vendas_apolice != 157) {
                        $retorno_tempo_assist = addTempo_assist($id_venda, $vendas_apolice, $nomeNm, $nomeNome, $nasc, $cpf, $email, $sexo, $estadoCivil, $rg);
                        $vendas_num_apolice = $retorno_tempo_assist["idItemCoberto"];
                        if ($vendas_num_apolice) {
                            $registro_obs = "Agendamento de cobrança recorrente ativado ok! Assitencia cadastrada com sucesso codigo - " . $vendas_num_apolice;
                        } else {
                            $registro_obs = "Cadastro da assistência não realizado. Motivo: " . $retorno_tempo_assist["descricao"];
                        }
                    } else {
                        $registro_obs = "Vendas reativada sem cadastro de assistência, pois já estava previamente cadastrada com o codigo: " . $row_old['vendas_num_apolice'];
                    }
                    registraHistorico($id_venda, $vendas_user, $registro_obs, $_GET['vendas_status'], $row_old['vendas_status'], $vendas_alteracao, $vendas_contrato_fisico, $cod_retorno, $result['message']);
                    if (empty($_GET["dp-normal-2"])) {
                        $_GET["dp-normal-2"] = date('d/m/Y');
                    }
                    // #### CHAMAR TEMPO ASSIST!!!  ###

                    //addTempo_assist($id_venda, $vendas_apolice, $nome, $nasc, $cpf, $email);

                }
            } else {
                echo '<div align="center">Erro na criacao do agendamento de recorrência.</div>';
                registraHistorico($id_venda, $vendas_user, "Erro na ativação do agendamento de recorrência!", $_GET['vendas_status'], $row_old['vendas_status'], $vendas_alteracao, $vendas_contrato_fisico, $cod_retorno, $result['message']);
                $nao_redireciona = 1;
            }
            // ############ FIM REQUISIÇÃO E ATIVAÇÃO DO AGENDAMENTO.


            // ##########################################################################################################################################################
            // ##########################################################################################################################################################
            // ##########################################################################################################################################################
            // ##########################################################################################################################################################
        } else {
            die('Error: ' . mysqli_error($con));
        }
        // Criar parcela ----------------------------------------------------------------------------------------#

    } else {
        echo "Error ao atualizar sys_transacoes_tef: " . mysqli_error($con);
    }
} else {
    echo "Error: " . $registra_venda . "<br>" . mysqli_error($con);
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

function registraHistorico($id_venda, $vendas_user, $vendas_obs, $vendas_status, $vendas_status_old, $vendas_alteracao, $vendas_contrato_fisico, $cod_retorno, $authorizer_message)
{
    //$cod_retorno = 51;
    if ($cod_retorno) {
        $result_retornos = mysql_query($con, "SELECT retorno_definicao FROM sys_vendas_transacoes_retorno WHERE retorno_codigo = '" . $cod_retorno . "';") or die(mysql_error());
        $row_retornos = mysql_fetch_assoc($result_retornos);
        $vendas_obs = $vendas_obs . " \n - RETORNO DA TRANSA&Ccedil;&Atilde;O:" . $authorizer_message;
        if ($vendas_status != 9) {
            $vendas_obs = $vendas_obs . " \n - RETORNO DA TRANSA&Ccedil;&Atilde;O:" . $cod_retorno . " - " . $row_retornos['retorno_definicao'];
        }
    }

    if (($vendas_status_old != '89') && ($vendas_status == '89')) {
        $registro_cobranca = 2;
    } else {
        $registro_cobranca = 1;
    }
    if (($vendas_status_old != '45') && ($vendas_status == '45')) {
        $registro_retencao = 2;
    } else {
        $registro_retencao = 1;
    }
    $sql = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, 
	`vendas_id`, 
	`registro_usuario`, 
	`registro_obs`, 
	`registro_status`, 
	`registro_data`, 
	`registro_contrato_fisico`, 
	`registro_cobranca`, 
	`registro_retencao`) 
	VALUES (NULL, 
	'$id_venda',
	'$vendas_user',
	'$vendas_obs',
	'$vendas_status',
	'$vendas_alteracao',
	'$vendas_contrato_fisico',
	'$registro_cobranca',
	'$registro_retencao');";

    if (mysql_query($con, $sql)) {
        $registro_id = mysql_insert_id();
        echo "Histórico Registrado com Sucesso. </br>";
    } else {
        die('Error: ' . mysql_error());
    }
}

mysqli_close($con);
