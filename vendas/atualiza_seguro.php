<?php
$user = &JFactory::getUser();
$username = $user->username;
$userid = $user->id;


$vendas_id = $_GET["vendas_id"];
$vendas_pgto = $_GET["vendas_pgto"];
$vendas_banco = $_GET["vendas_banco"];
$vendas_orgao = $_GET["vendas_orgao"];
$vendas_obs = $_GET["vendas_obs"];
$forma_envio_kitcert = $_GET["forma_envio_kitcert"];


$vendas_user = $username;
$vendas_alteracao = date("Y-m-d H:i:s");
$vendas_contrato_fisico = isset($_GET["vendas_contrato_fisico"]) ? $_GET["vendas_contrato_fisico"] : "";
include("sistema/utils/utils.php");
include("sistema/utf8.php");
$result_old = mysql_query("SELECT cliente_cpf, vendas_dia_venda, vendas_status, vendas_dia_venda, vendas_proposta, vendas_num_apolice, id_tempo_assist FROM sys_vendas_seguros 
	LEFT JOIN sys_vendas_apolices ON sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id 
	WHERE vendas_id='" . $vendas_id . "';")
	or die(mysql_error());
$query = "SELECT cliente_cpf, vendas_dia_venda, vendas_status, vendas_dia_venda, vendas_proposta, vendas_num_apolice FROM sys_vendas_seguros 
WHERE vendas_id='" . $vendas_id . "';";
//echo $query;
$row_old = mysql_fetch_array($result_old);
$cpf = $row_old['cliente_cpf'];
$id_tempo_assist = $row_old['id_tempo_assist'];

$result_nome_nascimento = mysql_query("SELECT cliente_nome, cliente_nascimento, cliente_email, cliente_sexo, cliente_est_civil, cliente_rg FROM `sys_inss_clientes`
WHERE `cliente_cpf` LIKE '" . $cpf . "'LIMIT 0 , 30;")
	or die(mysql_error());
$row_result_nome_nascimento = mysql_fetch_array($result_nome_nascimento);

$nasc = $row_result_nome_nascimento['cliente_nascimento'];
$email = $row_result_nome_nascimento['cliente_email'];
$nomeNome = $row_result_nome_nascimento['cliente_nome'];
if (!$row_result_nome_nascimento["cliente_nome"]) {
	$result_client_nm = mysql_query("SELECT clients_nm AS cliente_nome, 
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




//echo "to aqui"
$vendas_apolice = $_GET['vendas_apolice'];

// $nome = "nome";
// $nasc = "22021989";
// $cpf = "01596910011";
// $email = "email@teste.com";

//addTempo_assist($vendas_id, $vendas_apolice, $nomeNm, $nomeNome,$nasc, $cpf, $email, $sexo, $estadoCivil, $rg );


// Função para adicionar usuario na base de dados da tempo assist, função chamada somente quando e feito a cobrança recorrente do cartao de credito, em vendas ativadas, no momento de salvar a venda.
function addTempo_assist($vendas_id, $vendas_apolice, $nomeNm, $nomeNome, $nasc, $cpf, $email, $sexo, $estadoCivil, $rg, $id_tempo_assist)
{

	$url = 'https://sistema.apobem.com.br/integracao/tempo_assist/cadastro_segurado.php';


	$data = array(
		'vendas_id' => $vendas_id,
		'vendas_apolice' => $vendas_apolice,
		'id_tempo_assist' => $id_tempo_assist,
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
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);

	$context  = stream_context_create($options);
	$result = file_get_contents($url, true, $context);

	// Verificar se a requisição foi bem-sucedida
	if ($result === FALSE) {
		echo "Erro ao contactar servidor de assistência<br>";
		return array('descricao' => 'Erro de conexão', 'idItemCoberto' => '', 'codigo' => 'ERRO');
	}

	$objeto = json_decode($result, true);

	// Verificar se o JSON foi decodificado corretamente
	if (json_last_error() !== JSON_ERROR_NONE) {
		echo "Erro ao processar resposta do servidor de assistência<br>";
		return array('descricao' => 'Erro de processamento JSON', 'idItemCoberto' => '', 'codigo' => 'ERRO');
	}

	// Exibir informações com verificação de segurança
	if (isset($objeto['descricao'])) {
		echo "descricao:" . $objeto['descricao'];
	}
	echo "<br>";

	if (isset($objeto['idItemCoberto'])) {
		echo "idItemCoberto:" . $objeto['idItemCoberto'];
	}
	echo "<br>";

	if (isset($objeto['dadosRequest']['inicioVig'])) {
		echo "inicioVig: " . $objeto['dadosRequest']['inicioVig'];
	}
	echo "<br>";

	if (isset($objeto['codigo'])) {
		echo "codigo: " . $objeto['codigo'];
	}
	echo "<br>";
	return $objeto;
}

// Função para deletar  usuario na base de dados da tempo assist, função chamada somente quando e feito a cobrança recorrente do cartao de credito, em vendas ativadas, no momento de salvar a venda.
function DelTempo_assist($vendas_id)
{

	$url2 = 'https://sistema.apobem.com.br/integracao/tempo_assist/delete_segurado.php';


	$data2 = array(
		'vendas_id' => $vendas_id
	);

	$options2 = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data2)
		)
	);

	//var_dump($options2) ;

	$context2  = stream_context_create($options2);
	$result2 = file_get_contents($url2, true, $context2);

	// Verificar se a requisição foi bem-sucedida
	if ($result2 === FALSE) {
		echo "Erro ao contactar servidor de assistência para exclusão<br>";
		return array('descricao' => 'Erro de conexão');
	}

	$objeto2 = json_decode($result2, true);

	// Verificar se o JSON foi decodificado corretamente
	if (json_last_error() !== JSON_ERROR_NONE) {
		echo "Erro ao processar resposta do servidor de assistência<br>";
		return array('descricao' => 'Erro de processamento JSON');
	}

	// Exibir informações com verificação de segurança
	if (isset($objeto2['descricao'])) {
		echo "descricao da desativação da assistencia: " . $objeto2['descricao'] . " Assistencia desativada.<br>";
	} else {
		echo "Assistencia desativada.<br>";
	}
	// echo "idItemCoberto:". $objeto['idItemCoberto']; "<br>";
	// echo "inicioVig: " . $objeto['dadosRequest']['inicioVig']; "<br>";
	// echo "codigo: ". $objeto['codigo']; "<br>";		
	return $objeto2;
}

//DelTempo_assist($vendas_id);

### FUNÇÕES PSICODELICAS:
function registraHistorico($vendas_id, $vendas_user, $vendas_obs, $vendas_status, $vendas_status_old, $vendas_alteracao, $vendas_contrato_fisico, $cod_retorno, $authorizer_message)
{
	//$cod_retorno = 51;
	if ($cod_retorno) {
		$result_retornos = mysql_query("SELECT retorno_definicao FROM sys_vendas_transacoes_retorno WHERE retorno_codigo = '" . $cod_retorno . "';") or die(mysql_error());
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
	'$vendas_id',
	'$vendas_user',
	'$vendas_obs',
	'$vendas_status',
	'$vendas_alteracao',
	'$vendas_contrato_fisico',
	'$registro_cobranca',
	'$registro_retencao');";

	if (mysql_query($sql)) {
		$registro_id = mysql_insert_id();
		echo "Histórico Registrado com Sucesso. </br>";
	} else {
		die('Error: ' . mysql_error());
	}
}




/* ------------ EFETUA O CANCELAMENTO DA RECORRENCIA DA TRANSAÇÃO -------------*/
sleep(2);
$result_transactions = mysql_query("SELECT *
	FROM sys_vendas_transacoes_tef
	WHERE transacao_venda_id = '" . $vendas_id . "' AND transacao_status = 'CON'")
	or die(mysql_error());

if (($row_old['vendas_status'] != "19" && $_GET["vendas_status"] == "19") || ($row_old['vendas_status'] != "76" && $_GET["vendas_status"] == "76")) {
	DelTempo_assist($vendas_id);
	$desativa_tempoassist = 1;
}

// Se houver uma transação para esta venda em que o status esteja igual a 'CON'
if (mysql_num_rows($result_transactions) > 0) {
	// Se a venda não estava cancelada e está sendo cancelada agora
	// 19 CANCELADO - 76 CANCELADO INTERNO - 98	Cancelar Recorrência
	if (($row_old['vendas_status'] != "19" && $_GET["vendas_status"] == "19") || ($row_old['vendas_status'] != "98" && $_GET["vendas_status"] == "98") || ($row_old['vendas_status'] != "76" && $_GET["vendas_status"] == "76")) {

		// Consulta uma transação que esteja com status 'NOV' para esta venda.
		$result_transactions_nov = mysql_query("SELECT *
			FROM sys_vendas_transacoes_tef
			WHERE transacao_venda_id = '" . $vendas_id . "' AND transacao_status = 'NOV'
			ORDER BY transacao_data DESC limit 0,1;")
			or die(mysql_error());

		$query2 = "SELECT *
			FROM sys_vendas_transacoes_tef
			WHERE transacao_venda_id = '" . $vendas_id . "' AND transacao_status = 'NOV'
			ORDER BY transacao_data DESC limit 0,1;";

		//echo $query2;
		$row_transactions = mysql_fetch_assoc($result_transactions_nov);

		// Se existir uma transação com status 'NOV' 
		if (mysql_num_rows($result_transactions_nov) > 0) {
			// Preenchendo as variáveis necessárias para a requisição do cancelamento da cobrança recorrente.
			//echo "sid" . $row_transactions["transacao_agendamento_sid"];
			$url = 'https://sistema.apobem.com.br/integracao/softwareexpress/payment_request_cancel_schedule_transaction.php?token=EsearR31234fpssa0vfc9o';
			$data = array('transacao_agendamento_sid' => $row_transactions["transacao_agendamento_sid"]);
			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data)
				)
			);

			// Realiza a solicitação de edição para o cancelamento da recorrência.
			$context = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			$result = json_decode($result, true);

			// Se não retornou nada (erro ao conectar), notifica através da mensagem e não altera o status.
			if ($result === FALSE) {
				echo '<div align="center">Erro ao contactar servidor de pagamento/div>';
				//volta para status antigo em caso de erro
				if ($_GET["vendas_status"] != "76") {
					$_GET['vendas_status'] = $_GET['vendas_status_old'];
				} else {
					echo '<div align="center">Status da venda atualizado, para CANCELADO INTERNO, porém não foi concluído o processo de cancelamento no cartão!</div>';
					if ($desativa_tempoassist) {
						DelTempo_assist($vendas_id);
					}
				}
			}

			// Caso tenha retornado e a variável ['code'] for igual a 0...
			echo $result['code'];

			echo $_GET['vendas_status'];

			if ($result['code'] == '0') {
				if ($desativa_tempoassist) {
					// 86 = Cancelamento Solicitado
					$_GET['vendas_status'] = '86';
				} else {
					// 99 = Aguardando Cancelamento da Recorrência
					$_GET['vendas_status'] = '99';
				}
				// echo "<pre>";
				// print_r($result);
				// echo "</pre>";
			} else {
				if ($desativa_tempoassist) {
					DelTempo_assist($vendas_id);
				}
				// echo "<pre>";
				// print_r($result);
				// echo "</pre>";
				echo '<div align="center">Erro na tentativa de solicitar o cancelamento!</div>';
				//volta para status antigo em caso de erro
				if ($_GET["vendas_status"] != "76") {
					$_GET['vendas_status'] = $_GET['vendas_status_old'];
				} else {
					echo '<div align="center">Status da venda atualizado, para CANCELADO INTERNO, porém não foi concluído o processo de cancelamento no cartão!</div>';
				}
			}
		}
	}
}

/*	die();
}*/
/* ------------ FIM CANCELAMENTO DA RECORRENCIA DA TRANSAÇÃO -------------*/

/* ------------ EFETUA A CONFIRMAÇÃO DA TRANSAÇÃO -------------*/
$result_INA = mysql_query("SELECT COUNT(transacao_id) AS total FROM sys_vendas_transacoes_tef
WHERE transacao_venda_id = '" . $vendas_id . "' AND transacao_status = 'INA'")
	or die(mysql_error());
$row_INA = mysql_fetch_assoc($result_INA);

if (mysql_num_rows($result_transactions) == 0 || $row_INA["total"]) {
	if ($_GET["vendas_status"] == "15") {
		/*enviado para cobrança*/

		$result_transactions = mysql_query("SELECT *
    	FROM sys_vendas_transacoes_tef
    	WHERE transacao_venda_id = '" . $vendas_id . "' AND transacao_status = 'VER'
    	ORDER BY transacao_data DESC limit 0,1;")
			or die(mysql_error());

		$row_transactions = mysql_fetch_assoc($result_transactions);

		// SOLUÇÃO AUTOMÁTICA: Buscar transação VER não vinculada e vincular à venda atual
		if (mysql_num_rows($result_transactions) == 0) {
			// Buscar transação VER com venda_id = 0 ou NULL
			$result_ver_unlinked = mysql_query("SELECT * FROM sys_vendas_transacoes_tef WHERE transacao_status = 'VER' AND (transacao_venda_id = 0 OR transacao_venda_id IS NULL) ORDER BY transacao_data DESC LIMIT 1;") or die(mysql_error());

			if (mysql_num_rows($result_ver_unlinked) > 0) {
				$row_ver_unlinked = mysql_fetch_assoc($result_ver_unlinked);

				// Verificar se o CPF da transação confere com o CPF da venda
				$result_venda_cpf = mysql_query("SELECT cliente_cpf FROM sys_vendas_seguros WHERE vendas_id = '" . $vendas_id . "';") or die(mysql_error());
				if (mysql_num_rows($result_venda_cpf) > 0) {
					$row_venda_cpf = mysql_fetch_assoc($result_venda_cpf);
					$venda_cpf = $row_venda_cpf['cliente_cpf'];

					if ($venda_cpf == $row_ver_unlinked['transacao_cliente_cpf']) {
						// Vincular a transação à venda atual
						$update_link = mysql_query("UPDATE sys_vendas_transacoes_tef SET transacao_venda_id = '" . $vendas_id . "' WHERE transacao_id = '" . $row_ver_unlinked['transacao_id'] . "';") or die(mysql_error());

						if ($update_link) {
							// Recarregar a consulta original
							$result_transactions = mysql_query("SELECT *
								FROM sys_vendas_transacoes_tef
								WHERE transacao_venda_id = '" . $vendas_id . "' AND transacao_status = 'VER'
								ORDER BY transacao_data DESC limit 0,1;")
								or die(mysql_error());

							$row_transactions = mysql_fetch_assoc($result_transactions);
						}
					}
				}
			}
		}

		if (mysql_num_rows($result_transactions) > 0) :

			$url = 'https://sistema.apobem.com.br/integracao/softwareexpress/payment_do_transaction.php?token=EsearR31234fpssa0vfc9o';

			$transacao_data_exp = $row_transactions["transacao_cartao_validade_mes"] . substr($row_transactions["transacao_cartao_validade_ano"], -2);

			$data = array(
				'transacao_nit' => $row_transactions["transacao_nit"],
				'transacao_valor' => $row_transactions["transacao_valor"],
				'transacao_cartao_cvv' => $row_transactions["transacao_cartao_cvv"],
				'transacao_cartao_num' => $row_transactions["transacao_cartao_num"],
				'transacao_data_exp' => $transacao_data_exp,
				'authorizer_id' => $row_transactions["transacao_authorizer_id"],
				'vendas_id' => $vendas_id
			);

			// LOG: Mostrar dados que serão enviados para a API
			echo '<div style="background: #e8f4fd; padding: 15px; margin: 10px 0; border: 1px solid #2196F3; border-radius: 5px;">';
			echo '<h3 style="color: #1976D2; margin-top: 0;">📤 DADOS ENVIADOS PARA API DE PAGAMENTO</h3>';
			echo '<div style="background: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; font-size: 12px;">';
			echo '<strong>URL:</strong> ' . $url . '<br><br>';
			echo '<strong>Método:</strong> POST<br>';
			echo '<strong>Content-Type:</strong> application/x-www-form-urlencoded<br><br>';
			echo '<strong>Dados enviados:</strong><br>';
			echo '• <strong>transacao_nit:</strong> ' . $row_transactions["transacao_nit"] . '<br>';
			echo '• <strong>transacao_valor:</strong> ' . $row_transactions["transacao_valor"] . '<br>';
			echo '• <strong>transacao_cartao_num:</strong> ' . substr($row_transactions["transacao_cartao_num"], 0, 6) . '****' . substr($row_transactions["transacao_cartao_num"], -4) . ' (mascarado)<br>';
			echo '• <strong>transacao_cartao_cvv:</strong> ' . str_repeat('*', strlen($row_transactions["transacao_cartao_cvv"])) . ' (mascarado)<br>';
			echo '• <strong>transacao_data_exp:</strong> ' . $transacao_data_exp . '<br>';
			echo '• <strong>authorizer_id:</strong> ' . $row_transactions["transacao_authorizer_id"] . '<br>';
			echo '• <strong>vendas_id:</strong> ' . $vendas_id . '<br><br>';
			echo '<strong>Query String (POST body):</strong><br>';
			echo '<code style="background: #fff; padding: 5px; border: 1px solid #ddd; display: block; word-break: break-all;">' .
				str_replace('&', '&<br>', http_build_query($data)) . '</code>';
			echo '</div>';
			echo '</div>';

			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data)
				)
			);
			$context  = stream_context_create($options);

			echo '<div style="background: #fff3cd; padding: 10px; margin: 10px 0; border: 1px solid #ffc107; border-radius: 5px;">';
			echo '<h4 style="color: #856404; margin-top: 0;">⏳ Enviando requisição para API...</h4>';
			echo '</div>';

			$result = file_get_contents($url, false, $context);

			// LOG: Mostrar resposta recebida da API
			echo '<div style="background: #d4edda; padding: 15px; margin: 10px 0; border: 1px solid #c3e6cb; border-radius: 5px;">';
			echo '<h3 style="color: #155724; margin-top: 0;">📥 RESPOSTA RECEBIDA DA API</h3>';

			if ($result === FALSE) {
				echo '<div style="background: #f8d7da; padding: 10px; border-radius: 3px; color: #721c24;">';
				echo '<strong>❌ ERRO:</strong> Falha na comunicação com a API<br>';
				echo '<strong>Possíveis causas:</strong><br>';
				echo '• Timeout na conexão<br>';
				echo '• Servidor da API indisponível<br>';
				echo '• Problema de rede<br>';
				echo '• URL incorreta<br>';
				echo '</div>';

				echo '<div align="center" style="background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; color: #721c24; margin-top: 10px;">❌ Erro ao contactar servidor de pagamento, status alterado para não debitado</div>';
				$_GET['vendas_status'] = "8";
			} else {
				echo '<div style="background: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; font-size: 12px;">';
				echo '<strong>✅ SUCESSO:</strong> Resposta recebida da API<br><br>';
				echo '<strong>Resposta bruta:</strong><br>';
				echo '<pre style="background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 3px; max-height: 300px; overflow-y: auto; white-space: pre-wrap; word-wrap: break-word;">' . htmlspecialchars($result) . '</pre>';

				// Tentar decodificar JSON para mostrar formatado
				$decoded_result = json_decode($result, true);
				if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_result)) {
					echo '<br><strong>Resposta formatada (JSON):</strong><br>';
					echo '<pre style="background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 3px; max-height: 300px; overflow-y: auto;">' . htmlspecialchars(json_encode($decoded_result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>';

					// Destacar informações importantes
					if (isset($decoded_result['resposta']['payment']['status'])) {
						$status = $decoded_result['resposta']['payment']['status'];
						$color = ($status === 'CON') ? '#155724' : (($status === 'INV') ? '#721c24' : '#856404');
						echo '<div style="background: #fff; padding: 10px; border: 2px solid ' . $color . '; border-radius: 3px; margin-top: 10px;">';
						echo '<strong style="color: ' . $color . ';">STATUS DA TRANSAÇÃO: ' . $status . '</strong><br>';

						if (isset($decoded_result['resposta']['payment']['authorizer_code'])) {
							echo '<strong>Código do Autorizador:</strong> ' . $decoded_result['resposta']['payment']['authorizer_code'] . '<br>';
						}

						if (isset($decoded_result['resposta']['message'])) {
							echo '<strong>Mensagem:</strong> ' . $decoded_result['resposta']['message'] . '<br>';
						}
						echo '</div>';
					}
				}
				echo '</div>';
			}
			echo '</div>';

			/*
###################################################################################################################
###################################################################################################################
###################################################################################################################
##########################        WORKING HERE       ############################################################
###################################################################################################################
###################################################################################################################
###################################################################################################################
###################################################################################################################
*/
			$result = json_decode($result, true);

			if ($result->getstatus) {	// se entrou aqui é porque ocorreu um erro de timeout e foi necessário tentar checar o status.
				if ($result->getstatus_error_number == 0) {
					// se entrou aqui a curl repondeu com sucesso nas tentativas de getStatus
					// Se obteve sucesso, segue normalmente o código
					$retorno_ok = true;
				} else {
					// a curl falhou nas tentativas de getStatus
					// Se não obteve sucesso, vai ser necessário colocar a venda no status...
					// ... status "Falha na Cobrança" esse status deve bloquear qualquer alteração na venda, somente diretoria poderá alterar.
					//echo $result->erro."<br>";
					echo '<div align="center">Erro na confirmação da transação, status alterado para Falha no Processamento. curl_error_number: ' . $result->getstatus_error_number . '</div>';
					$_GET['vendas_status'] = "85"; // Falha no Processamento
					$retorno_ok = false;
				}
			} else {
				// Não ocorreu erro de timeout, mas pode ter ocorrido outro erro.
				if ($result->transaction_error_number == 0) {
					$retorno_ok = true;
				} else {
					echo '<div align="center">Erro na realização da transação, status alterado para Não Debitado. curl_error_number: ' . $result->transaction_error_number . '</div>';
					$_GET['vendas_status'] = "8";
					$retorno_ok = false;
				}
			}

			if ($retorno_ok == true) {
				// echo "<pre>";
				// 	print_r($result);
				// echo "</pre>";

				$result = $result['resposta'];

				// echo "<pre>";
				// 	print_r($result);
				// echo "</pre>";


				if ($result['capture']['authorizer_date']) {
					$result_confirmacao = explode("T", $result['capture']['authorizer_date']);
					$result_confirmacao[0] = implode("-", array_reverse(explode("-", str_replace("/", "-", $result_confirmacao[0]))));
					$data_confirmacao = $result_confirmacao[0] . ' ' . $result_confirmacao[1] . ':00';
					$insert_data_confirmacao = "transacao_data_confirmacao = '" . $data_confirmacao . "',";
				} else {
					$insert_data_confirmacao = '';
				}
				/*var_dump($result);
					die();*/
				$update_transaction_query = "UPDATE sys_vendas_transacoes_tef
							SET transacao_status = '" . $result['capture']['status'] . "',
							transacao_agendamento_sid = '" . $result['schedule']['sid'] . "',
							" . $insert_data_confirmacao . "
							transacao_merchant_usn = '" . $result['capture']['esitef_usn'] . "', 
							transacao_esitef_usn = '" . $result['capture']['esitef_usn'] . "'
							WHERE transacao_id = '" . $row_transactions['transacao_id'] . "'";

				$result_transaction = mysql_query($update_transaction_query);

				if ($result['capture']['status'] == "CON") {
					echo '<div align="center">Pagamento com cartão confirmado!</div>';
					$_GET['vendas_status'] = "9";

					$transacao_data = date("Y-m-d");
					if ($transacao_data >= "2021-05-01") {
						//INSERÇÃO DA TRANSAÇÃO - (PARCELA):
						//CONSULTA VALOR DA CMS DA PARCELA RECEBIDA:
						$result_cms = mysql_query("SELECT transacao_valor FROM sys_vendas_aplices_cms WHERE 
																cms_apolices LIKE '%," . $_GET['vendas_apolice'] . ",%' 
																AND (cms_authorizers LIKE '%," . $result['capture']['authorizer_id'] . ",%' OR cms_authorizers = '0') 
																ORDER BY cms_id ASC 
																LIMIT 0,1;")
							or die(mysql_error());
						$row_cms = mysql_fetch_array($result_cms);
						$vendas_receita = $row_cms['transacao_valor'];
						$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_receita='$vendas_receita' WHERE vendas_id='" . $vendas_id . "';") or die(mysql_error());

						$transacao_tipo = "1";
						$transacao_parcela = "1";
						$transacao_mes = date("m/Y");
						$transacao_data_id = date("Ymd_Hms");
						$transacao_id = $vendas_id . "_" . $transacao_data_id . "_" . $transacao_parcela . "_" . $transacao_tipo;
						$insere_transacoes_seg_query = "INSERT INTO `sistema`.`sys_vendas_transacoes_seg` (`transacao_id`, 
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
							'$vendas_id',
							'$vendas_id',
							'$vendas_receita',
							'1',
							'0',
							NOW(),
							'$transacao_data',
							'$transacao_mes',
							'$transacao_parcela',
							'$transacao_tipo');";
						//echo "<br>insere_transacoes_seg_query: <br>".$insere_transacoes_seg_query;
						if (mysql_query($insere_transacoes_seg_query, $con)) {
							echo "Transação <strong>" . $transacao_id . "</strong> Cadastrada com Sucesso, para a Proposta <strong>" . $vendas_id . "</strong>, Cod da venda: . <strong>" . $vendas_id . "</strong></br>";
						} else {
							die('Error: ' . mysql_error());
						}
						//FIM INSERÇÃO DA TRANSAÇÃO - (PARCELA).
					}

					registraHistorico($vendas_id, $vendas_user, $vendas_obs, $_GET['vendas_status'], $row_old['vendas_status'], $vendas_alteracao, $vendas_contrato_fisico, $result['capture']['authorizer_code'], $result['capture']['authorizer_message']);

					// ############ REQUISIÇÃO DE AGENDAMENTO:
					$url = 'https://sistema.apobem.com.br/integracao/softwareexpress/payment_request_schedule.php?token=EsearR31234fpssa0vfc9o';

					$transacao_data_exp = $row_transactions["transacao_cartao_validade_mes"] . substr($row_transactions["transacao_cartao_validade_ano"], -2);

					$data = array(
						'cpf' => $row_old["cliente_cpf"],
						'username' => $username,
						'user_id' => $userid,
						'token' => 'EsearR31234fpssa0vfc9o',
						'transacao_valor' => $row_transactions["transacao_valor"],
						'transacao_cartao_cvv' => $row_transactions["transacao_cartao_cvv"],
						'transacao_cartao_num' => $row_transactions["transacao_cartao_num"],
						'transacao_data_exp' => $transacao_data_exp,
						'authorizer_id' => $row_transactions["transacao_authorizer_id"],
						'transacao_venda_id' => $vendas_id
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

					// Verificar se o JSON foi decodificado corretamente
					if (json_last_error() !== JSON_ERROR_NONE) {
						echo '<div align="center">Erro ao processar resposta do servidor de agendamento</div>';
						$_GET['vendas_status'] = "8";
						$nao_redireciona = 1;
						return;
					}

					if ($userid == 957) {
						echo "Tentou requisitar o schedule";
						// echo "<pre>";
						// print_r($result);
						// echo "</pre>";
					}
					registraHistorico($vendas_id, $vendas_user, "Cadastro de agendamento.", $_GET['vendas_status'], $row_old['vendas_status'], $vendas_alteracao, $vendas_contrato_fisico, $cod_retorno, $result['message']);

					// ############ ATIVAÇÃO DO AGENDAMENTO:
					if (isset($result['data']['schedule']['status']) && $result['data']['schedule']['status'] == "NOV") {
						// Verificar se o SID está disponível
						if (!isset($result['data']['schedule']['sid']) || empty($result['data']['schedule']['sid'])) {
							echo '<div align="center">Erro: SID do agendamento não encontrado</div>';
							$_GET['vendas_status'] = "8";
							$nao_redireciona = 1;
							return;
						}
						$url = 'https://sistema.apobem.com.br/integracao/softwareexpress/payment_do_schedule.php?token=EsearR31234fpssa0vfc9o';
						$data = array(
							'cpf' => $row_old["cliente_cpf"],
							'username' => $username,
							'user_id' => $userid,
							'transacao_cartao_num' => $row_transactions["transacao_cartao_num"],
							'transacao_data_exp' => $transacao_data_exp,
							'transacao_agendamento_sid' => isset($result['data']['schedule']['sid']) ? $result['data']['schedule']['sid'] : ''
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

						// Verificar se o JSON foi decodificado corretamente
						if (json_last_error() !== JSON_ERROR_NONE) {
							echo '<div align="center">Erro ao processar resposta da ativação do agendamento</div>';
							$_GET['vendas_status'] = "8";
							$nao_redireciona = 1;
							return;
						}

						if ($userid == 957) {
							echo "Tentou requisitar o schedule";
							// echo "<pre>";
							// print_r($result);
							// echo "</pre>";
						}
						if (isset($result['resposta']['schedule']['status']) && $result['resposta']['schedule']['status'] == "ATV") {
							echo '<div align="center">Agendamento de cobrança recorrente ativado ok!</div>';
							$_GET['vendas_status'] = "67";
							if (((!$row_old['vendas_num_apolice']) || $row_old['vendas_num_apolice'] == '') && $vendas_apolice != 157) {
								$retorno_tempo_assist = addTempo_assist($vendas_id, $vendas_apolice, $nomeNm, $nomeNome, $nasc, $cpf, $email, $sexo, $estadoCivil, $rg, $id_tempo_assist);
								$vendas_num_apolice = $retorno_tempo_assist["idItemCoberto"];
								if ($vendas_num_apolice) {
									$registro_obs = "Agendamento de cobrança recorrente ativado ok! Assitencia cadastrada com sucesso codigo - " . $vendas_num_apolice;
								} else {
									$registro_obs = "Cadastro da assistência não realizado. Motivo: " . $retorno_tempo_assist["descricao"];
								}
							} else {
								$registro_obs = "Vendas reativada sem cadastro de assistência, pois já estava previamente cadastrada com o codigo: " . $row_old['vendas_num_apolice'];
							}
							registraHistorico($vendas_id, $vendas_user, $registro_obs, $_GET['vendas_status'], $row_old['vendas_status'], $vendas_alteracao, $vendas_contrato_fisico, $cod_retorno, $result['message']);
							if (empty($_GET["dp-normal-2"])) {
								$_GET["dp-normal-2"] = date('d/m/Y');
							}
							// #### CHAMAR TEMPO ASSIST!!!  ###

							//addTempo_assist($vendas_id, $vendas_apolice, $nome, $nasc, $cpf, $email);

						}
					} else {
						echo '<div align="center">Erro na criacao do agendamento de recorrência.</div>';
						registraHistorico($vendas_id, $vendas_user, "Erro na ativação do agendamento de recorrência!", $_GET['vendas_status'], $row_old['vendas_status'], $vendas_alteracao, $vendas_contrato_fisico, $cod_retorno, $result['message']);
						$nao_redireciona = 1;
					}
					// ############ FIM REQUISIÇÃO E ATIVAÇÃO DO AGENDAMENTO.

				} else {
					echo '<div align="center">Pagamento não confirmado, ' . $result['capture']['authorizer_message'] . ' cod:' . $result['capture']['status'] . ' status alterado para não debitado</div>';
					$_GET['vendas_status'] = "8";
					echo "<pre>";
					print_r($result);
					echo "</pre>";
					$nao_redireciona = 1;
				}
			}
		endif;
	}
}
/*}*/
/* ------------ FIM CONFIRMAÇÃO DA TRANSAÇÃO -------------*/
?>

<?php
if (mysql_num_rows($result_transactions) == 0 && $user_id == "957") {
	/* ------------ EFETUA A CANCELAMENTO DA TRANSAÇÃO -------------*/
	if ($_GET["vendas_status"] == "19" || $_GET["vendas_status"] == "76") {
		/*enviado para cobrança*/
		$result_transactions = mysql_query("SELECT *
    	FROM sys_vendas_transacoes_tef
    	WHERE transacao_venda_id = '" . $vendas_id . "' AND transacao_status = 'VER'
    	ORDER BY transacao_data DESC limit 0,1;")
			or die(mysql_error());

		$row_transactions = mysql_fetch_assoc($result_transactions);

		if (mysql_num_rows($result_transactions) > 0) :
			$url = 'https://sistema.apobem.com.br/integracao/softwareexpress/payment_do_transaction_cancel.php?token=EsearR31234fpssa0vfc9o';

			$transacao_data_exp = $row_transactions["transacao_cartao_validade_mes"] . substr($row_transactions["transacao_cartao_validade_ano"], -2);

			$data = array(
				'transacao_nit' => $row_transactions["transacao_nit"],
				'transacao_valor' => 0,
				'transacao_cartao_cvv' => $row_transactions["transacao_cartao_cvv"],
				'transacao_cartao_num' => $row_transactions["transacao_cartao_num"],
				'transacao_data_exp' => $transacao_data_exp,
				'authorizer_id' => $row_transactions["transacao_authorizer_id"],
				'vendas_id' => $vendas_id
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

			if ($result === FALSE) {

				echo '<div align="center">Erro ao contactar servidor de pagamento, status não foi alterado.</div>';
				$_GET['vendas_status'] = $_GET['vendas_status_old'];
			}

			$result = json_decode($result, true);

			if ($result->getstatus) {	// se entrou aqui é porque ocorreu um erro de timeout e foi necessário tentar checar o status.
				if ($result->getstatus_error_number == 0) {
					// se entrou aqui a curl repondeu com sucesso nas tentativas de getStatus
					// Se obteve sucesso, segue normalmente o código
					$retorno_ok = true;
				} else {
					// a curl falhou nas tentativas de getStatus
					// Se não obteve sucesso, vai ser necessário colocar a venda no status...
					// ... status "Falha na Cobrança" esse status deve bloquear qualquer alteração na venda, somente diretoria poderá alterar.
					//echo $result->erro."<br>";
					echo '<div align="center">Erro na confirmação do cancelamento da transação, status alterado para Falha no Processamento. curl_error_number: ' . $result->getstatus_error_number . '</div>';
					$_GET['vendas_status'] = "85"; // Falha no Processamento
					$retorno_ok = false;
				}
			} else {
				// Não ocorreu erro de timeout, mas pode ter ocorrido outro erro.
				if ($result->transaction_error_number == 0) {
					$retorno_ok = true;
				} else {
					echo '<div align="center">Erro na realização da transação, status não alterado. curl_error_number: ' . $result->transaction_error_number . '</div>';
					$_GET['vendas_status'] = $_GET['vendas_status_old'];
					$retorno_ok = false;
				}
			}

			if ($retorno_ok == true) {
				// echo "<pre>";
				// print_r($result);
				// echo "</pre>";

				$result = $result['resposta'];

				// echo "<pre>";
				// print_r($result);
				// echo "</pre>";


				if ($result['capture']['authorizer_date']) {
					$result_confirmacao = explode("T", $result['capture']['authorizer_date']);
					$result_confirmacao[0] = implode("-", array_reverse(explode("-", str_replace("/", "-", $result_confirmacao[0]))));
					$data_confirmacao = $result_confirmacao[0] . ' ' . $result_confirmacao[1] . ':00';
					$insert_data_confirmacao = "transacao_data_confirmacao = '" . $data_confirmacao . "',";
				} else {
					$insert_data_confirmacao = '';
				}
				// var_dump($result);
				// die();
				$update_transaction_query = "UPDATE sys_vendas_transacoes_tef
							SET transacao_status = '" . $result['capture']['status'] . "',
							transacao_agendamento_sid = '" . $result['schedule']['sid'] . "',
							" . $insert_data_confirmacao . "
							transacao_merchant_usn = '" . $result['capture']['esitef_usn'] . "',
							transacao_esitef_usn = '" . $result['capture']['esitef_usn'] . "'
							WHERE transacao_id = '" . $row_transactions['transacao_id'] . "'";

				$result_transaction = mysql_query($update_transaction_query);

				if ($result['capture']['status'] == "CON") {
					echo '<div align="center">Pagamento com cartão cancelado (valor zero)!</div>';
					//$_GET['vendas_status'] = "xxx"; // STATUS SE MANTÉM COM O QUE VEIO DO GET.
					registraHistorico($vendas_id, $vendas_user, $vendas_obs, $_GET['vendas_status'], $row_old['vendas_status'], $vendas_alteracao, $vendas_contrato_fisico, $result['capture']['authorizer_code'], $result['capture']['authorizer_message']);
				} else {
					echo '<div align="center">Cancelamento não confirmado, ' . $result['capture']['authorizer_message'] . ' cod:' . $result['capture']['status'] . ' status não alterado.</div>';
					$_GET['vendas_status'] = $_GET['vendas_status_old'];
					// echo "<pre>";
					// print_r($result);
					// echo "</pre>";
					$nao_redireciona = 1;
				}
			}
		endif;
	}
	/* ------------ FIM CANCELAMENTO DA TRANSAÇÃO -------------*/
}
?>

<?php if (!$_GET["vendas_dia_desconto"]) : ?>
	<meta http-equiv="Refresh" content="5; url=index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_seguro&vendas_id=<?php echo $vendas_id; ?>">
	<div align="center"><strong>Campo DIA DE DESCONTO vazio ou inválido, tente novamente!</strong></br>
	<?php else : ?>
		<?php if (($_GET["salvar"] != "salvar") && ($_GET["salvar"] != "salvar_fechar")) : ?>
			<?php
			$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_pgto='$vendas_pgto', vendas_banco='$vendas_banco' WHERE vendas_id='$vendas_id' ") or die(mysql_error());
			echo "";
			?>
			<meta http-equiv="Refresh" content="0; url=index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_seguro&vendas_id=<?php echo $vendas_id; ?>">
			<div align="center">Forma de Pagamento e Seguradora Atualizadas com Sucesso!</strong></br>

			<?php elseif (($_GET["vendas_status"] == "3") && (!$_GET["vendas_gravacao"])) : ?>
				<div align="center">
					Informe o Caminho da Gravação no S: para auditar a Venda!<br /><br />
					<button class="button validate png" onclick="history.go(-1)" type="button">Voltar</button>
				</div>
			<?php else : ?>
				<?php

				$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $userid . ";")
					or die(mysql_error());
				while ($row_grupo_user = mysql_fetch_array($result_grupo_user)) {
					if (($row_grupo_user['id'] == '10') || ($row_grupo_user['id'] == '26')) {
						$administracao = 1;
					}
					if ($row_grupo_user['id'] == '18') {
						$diretoria = 1;
					}
					if ($row_grupo_user['id'] == '11') {
						$sup_operacional = 1;
					}
				}

				$result_apolice = mysql_query("SELECT apolice_valor, 
						apolice_cms_vendedor, 
						cbocod, 
						cbodesc, 
						vltotprem,
						vltotcap 
						FROM sys_vendas_apolices WHERE apolice_id='" . $_GET['vendas_apolice'] . "';")
					or die(mysql_error());
				$row_apolice = mysql_fetch_array($result_apolice);

				// $id_tempo_assist = $_GET["id_tempo_assist"];
				// $dias_vencimento_tempo_assist = $_GET["dias_vencimento_tempo_assist"];

				$vendas_consultor = $_GET["vendas_consultor"];
				$vendas_apolice = $_GET["vendas_apolice"];
				$vendas_proposta = trim($_GET["vendas_proposta"]);
				if ($vendas_num_apolice == "") {
					$vendas_num_apolice = $_GET["vendas_num_apolice"];
				}

				if ($_GET["vendas_valor"]) {
					$vendas_valor = $_GET["vendas_valor"];
					if (strpos($vendas_valor, ".")) {
						$vendas_valor = substr_replace($vendas_valor, '', strpos($vendas_valor, "."), 1);
					}
					if (!strpos($vendas_valor, ".") && (strpos($vendas_valor, ","))) {
						$vendas_valor = substr_replace($vendas_valor, '.', strpos($vendas_valor, ","), 1);
					}
				} else {
					$vendas_valor = $row_apolice["apolice_valor"];
				}
				$vendas_dia_desconto = $_GET["vendas_dia_desconto"];

				$vendas_comissao_vendedor = (($vendas_valor * $row_apolice['apolice_cms_vendedor']) / 100);
				$campos_update = $campos_update . ", vendas_comissao_vendedor='" . $vendas_comissao_vendedor . "'";

				if ($_GET["vendas_cartao_adm"]) {
					$campos_update = $campos_update . ", vendas_cartao_adm='" . $_GET['vendas_cartao_adm'] . "'";
				}
				if ($_GET["vendas_cartao_band"]) {
					$campos_update = $campos_update . ", vendas_cartao_band='" . $_GET['vendas_cartao_band'] . "'";
				}
				if ($_GET["vendas_cartao_num"]) {
					$campos_update = $campos_update . ", vendas_cartao_num='" . $_GET['vendas_cartao_num'] . "'";
				}
				if ($_GET["cartao_cvv"]) {
					$campos_update = $campos_update . ", vendas_cartao_cvv='" . $_GET['cartao_cvv'] . "'";
				}
				if ($_GET["vendas_cartao_validade_mes"]) {
					$campos_update = $campos_update . ", vendas_cartao_validade_mes='" . $_GET['vendas_cartao_validade_mes'] . "'";
				}
				if ($_GET["vendas_cartao_validade_ano"]) {
					$campos_update = $campos_update . ", vendas_cartao_validade_ano='" . $_GET['vendas_cartao_validade_ano'] . "'";
				}
				if ($_GET["vendas_vencimento_fatura"]) {
					$campos_update = $campos_update . ", vendas_vencimento_fatura='" . $_GET['vendas_vencimento_fatura'] . "'";
				}
				if ($_GET["data_intencionamento"]) {
					$campos_update = $campos_update . ", vendas_dia_intencionamento='" . dataBR_to_dataDB($_GET['data_intencionamento']) . "'";
				}
				$vendas_ben = $_GET["vendas_ben"];
				$vendas_parent = $_GET["vendas_parent"];
				$vendas_debito_banco = $_GET["vendas_debito_banco"];
				$vendas_debito_ag = $_GET["vendas_debito_ag"];
				$vendas_debito_ag_dig = $_GET["vendas_debito_ag_dig"];
				$vendas_debito_cc = $_GET["vendas_debito_cc"];
				$vendas_debito_cc_dig = $_GET["vendas_debito_cc_dig"];
				$vendas_debito_banco_2 = $_GET["vendas_debito_banco_2"];
				$vendas_debito_ag_2 = $_GET["vendas_debito_ag_2"];
				$vendas_debito_cc_2 = $_GET["vendas_debito_cc_2"];
				$vendas_debito_banco_3 = $_GET["vendas_debito_banco_3"];
				$vendas_debito_ag_3 = $_GET["vendas_debito_ag_3"];
				$vendas_debito_cc_3 = $_GET["vendas_debito_cc_3"];
				$vendas_status = $_GET["vendas_status"];
				$vendas_telefone = $_GET["vendas_telefone"];
				$vendas_telefone2 = $_GET["vendas_telefone2"];
				$vendas_gravacao = $_GET["vendas_gravacao"];
				$vendas_gravacao = mysql_real_escape_string($vendas_gravacao, $con);

				if ($_GET["dp-normal-1"]) {
					$vendas_dia_venda = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));
				}
				$vendas_dia_venda = $vendas_dia_venda . " " . $_GET["data_venda_hora"];
				if ($_GET["dp-normal-2"]) {
					$vendas_dia_ativacao = implode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "-" : "/", $_GET["dp-normal-2"])));
				}
				$vendas_turno = $_GET["vendas_turno"];

				if ($vendas_banco == 11 && $row_old["vendas_status"] != "67" && $vendas_status == "67" && (!$row_old["vendas_dia_ativacao"] || $row_old["vendas_dia_ativacao"] == "0000-00-00") && (!$vendas_dia_ativacao || $vendas_dia_ativacao == "0000-00-00")) {
					$vendas_dia_ativacao = date("Y-m-d");
				}

				//if ((($_GET["vendas_status_old"] == "1") || ($_GET["vendas_status_old"] == "2") || ($_GET["vendas_status_old"] == "10") || ($_GET["vendas_status_old"] == "5")) && ($vendas_status == "3")){$vendas_dia_venda = $vendas_alteracao;}

				// INICIO CHAMADA TEMPO ASSIST PARA BOLETO ATIVO:

				if ($row_old['vendas_status'] != "67" && $_GET["vendas_status"] == "67" && $vendas_banco == 11 && ($vendas_pgto == 3 || $vendas_pgto == 4 || $vendas_pgto == 1)) {
					if (!$row_old['vendas_num_apolice'] && $vendas_apolice != 157) {
						$retorno_tempo_assist = addTempo_assist($vendas_id, $vendas_apolice, $nomeNm, $nomeNome, $nasc, $cpf, $email, $sexo, $estadoCivil, $rg, $id_tempo_assist);
						$vendas_num_apolice = $retorno_tempo_assist["idItemCoberto"];
						if ($vendas_num_apolice) {
							$registro_obs = "Agendamento de cobrança recorrente ativado ok! Assitencia cadastrada com sucesso codigo - " . $vendas_num_apolice;
						} else {
							$registro_obs = "Vendas reativada sem cadastro de assistência, retorno do processamento: " . implode($retorno_tempo_assist);
						}
						registraHistorico($vendas_id, $vendas_user, $registro_obs, $_GET['vendas_status'], $row_old['vendas_status'], $vendas_alteracao, $vendas_contrato_fisico, $cod_retorno, $result['message']);
					}
				}

				// INICIO ENCAMINHAMENTO DE CAMPANHA DE COBRANÇA:

				if ($row_old['vendas_status'] != "88" && $_GET["vendas_status"] == "88" && ($vendas_banco == 3 || $vendas_banco == 11)) {
					include("sistema/connect_db02.php");
					include("sistema/utf8.php");
					$cliente_cpf = $row_old['cliente_cpf'];
					if ($vendas_banco == 3) {
						$campanha_grupo_id = 13;
					} else {
						$campanha_grupo_id = 12;
					}
					$sql_menor_campanha = mysql_query("
							SELECT campanha_id, campanha_nome, (SELECT COUNT(cliente_cpf) AS total FROM sys_inss_clientes WHERE cliente_campanha_id = campanha_id AND cliente_parecer = 100) AS total FROM sys_campanhas 
							INNER JOIN sys_campanhas_grupos ON sys_campanhas.campanha_grupo_id = sys_campanhas_grupos.grupo_id 
							WHERE campanha_grupo_id = " . $campanha_grupo_id . " 
							ORDER BY total ASC 
							LIMIT 0, 1;") or die(mysql_error());

					$result_campanha_grupos = mysql_query("SELECT grupo_id, grupo_nome FROM sys_campanhas_grupos WHERE grupo_id = '" . $campanha_grupo_id . "';")
						or die(mysql_error());
					$row_campanha_grupos = mysql_fetch_array($result_campanha_grupos);
					$acionamento_obs .= " (Campanha de destino: Grupo " . $row_campanha_grupos['grupo_nome'];

					if (mysql_num_rows($sql_menor_campanha)) {
						$row_menor_campanha = mysql_fetch_array($sql_menor_campanha);
						$cliente_campanha_id_update = ", cliente_campanha_id = '" . $row_menor_campanha['campanha_id'] . "'";

						$acionamento_obs .= " - Nome: " . $row_menor_campanha['campanha_nome'] . " " . $row_menor_campanha['campanha_id'] . ").";
					} else {
						$acionamento_obs .= " - Nenhuma campanha ativa neste grupo).";
					}

					$query = mysql_query("UPDATE sys_inss_clientes
							SET 
							cliente_parecer = '100',
							cliente_usuario = '$username',
							cliente_alteracao = '$vendas_alteracao'" .
						$cliente_campanha_id_update .
						" WHERE cliente_cpf='$cliente_cpf';") or die(mysql_error());
					echo "Parecer Atualizado com Sucesso <br/>";

					$sql = "INSERT INTO `sistema`.`sys_acionamentos` (`acionamento_id`, 
						`clients_cpf`, 
						`acionamento_usuario`, 
						`acionamento_obs`, 
						`acionamento_parecer`, 
						`acionamento_empregador`, 
						`acionamento_data`, 
						`acionamento_campanha`,
						`acionamento_equipe_id`) 
						VALUES (NULL, 
						'$cliente_cpf',
						'$username',
						'$acionamento_obs',
						'29',
						'INSS',
						'$vendas_alteracao',
						'" . $row_menor_campanha['campanha_id'] . "',
						'94');";
					if (mysql_query($sql, $con)) {
						$acionamento_id = mysql_insert_id();
						echo "Acionamento Registrado com Sucesso. </br>";
					} else {
						die('Error: ' . mysql_error());
					}

					include("sistema/connect.php");
					include("sistema/utf8.php");
				}


				// FIM ENCAMINHAMENTO DE CAMPANHA DE COBRANÇA


				if ($vendas_banco == 7 && $row_old["vendas_status"] != "15" && $vendas_status == "15" && $row_apolice["cbocod"] > 0) {
					$result_client = mysql_query("SELECT cliente_nome, 
						cliente_nascimento, 
						cliente_pagamento, 
						cliente_rg, 
						cliente_rg_exp, 
						cliente_beneficio, 
						cliente_endereco, 
						cliente_endereco_complemento, 
						cliente_bairro, 
						cliente_cidade, 
						cliente_cep, 
						cliente_uf, 
						cliente_telefone, 
						cliente_celular, 
						cliente_empregador, 
						cliente_margem_idt, 
						cliente_orgao, 
						cliente_sexo, 
						cliente_est_civil, 
						cliente_cargo,
						cliente_banco,
						cliente_agencia,
						cliente_conta
						FROM sys_inss_clientes WHERE cliente_cpf = '" . $row_old['cliente_cpf'] . "';")
						or die(mysql_error());
					$row_client = mysql_fetch_array($result_client);

					if (!$row_client["cliente_nome"]) {
						$result_client = mysql_query("SELECT clients_nm AS cliente_nome, 
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
						$row_client = mysql_fetch_array($result_client);
					}

					if ($vendas_pgto == 3) {
						echo "inicio chamada API.";
						if ($row_client["cliente_empregador"] == "SIAPE") {
							$grupo = 17;
							$cliente_upag = $row_client["cliente_orgao"];
						} else {
							$cliente_upag = "";
						}
						if ($row_client["cliente_empregador"] == "Exercito") {
							$grupo = 35;
						}
						echo "passo 2 api.";
						if ($grupo) {
							include("sistema/integracao/mbm/insere_proposta_folha.php");
						}
					} else {
						include("sistema/integracao/mbm/insere_proposta.php");
					}
					$nao_redireciona = 1;
				}

				if (($vendas_banco == 11) && (!$vendas_proposta)) {
					$campos_update = $campos_update . ", vendas_proposta=vendas_id";
				}

				if (($row_old['vendas_status'] != "19" && $_GET["vendas_status"] == "19") || ($row_old['vendas_status'] != "76" && $_GET["vendas_status"] == "76")) {
					$campos_update = $campos_update . ", vendas_dia_cancelamento = NOW()";
				}

				if ($administracao == 1) {
					$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_consultor='$vendas_consultor', 
							vendas_apolice='$vendas_apolice', 
							vendas_proposta='$vendas_proposta', 
							vendas_num_apolice='$vendas_num_apolice', 
							vendas_valor='$vendas_valor', 
							vendas_dia_desconto='$vendas_dia_desconto',  
							vendas_pgto='$vendas_pgto'" . $campos_update . " ,
							vendas_ben='$vendas_ben', 
							vendas_parent='$vendas_parent', 
							vendas_debito_banco='$vendas_debito_banco', 
							vendas_debito_ag='$vendas_debito_ag', 
							vendas_debito_ag_dig='$vendas_debito_ag_dig', 
							vendas_debito_cc='$vendas_debito_cc', 
							vendas_debito_cc_dig='$vendas_debito_cc_dig', 
							vendas_debito_banco_2='$vendas_debito_banco_2', 
							vendas_debito_ag_2='$vendas_debito_ag_2', 
							vendas_debito_cc_2='$vendas_debito_cc_2', 
							vendas_debito_banco_3='$vendas_debito_banco_3', 
							vendas_debito_ag_3='$vendas_debito_ag_3', 
							vendas_debito_cc_3='$vendas_debito_cc_3', 
							vendas_banco='$vendas_banco', 
							vendas_status='$vendas_status', 
							vendas_dia_venda='$vendas_dia_venda', 
							vendas_dia_ativacao='$vendas_dia_ativacao', 
							vendas_turno='$vendas_turno', 
							vendas_alteracao='$vendas_alteracao', 
							vendas_telefone='$vendas_telefone', 
							vendas_telefone2='$vendas_telefone2', 
							vendas_gravacao='$vendas_gravacao', 
							vendas_user='$vendas_user',
							forma_envio_kitcert='$forma_envio_kitcert'
							WHERE vendas_id='$vendas_id' ") or die(mysql_error());

					$nome = "nome";
					$nasc = "nascimento";
					$cpf = "cpf";
					$email = "email";
					// echo "vendasid -" . $vendas_id . "<br>vendas_apolice -". $vendas_apolice . "<br> nome -" . $nome . " <br> nasci -". $nasc . " <br> cpf-" .$cpf. " <br> email-". $email;
					// addTempo_assist($vendas_id, $vendas_apolice, $nome, $nasc, $cpf, $email);
					echo "Venda Atualizada com Sucesso";
				} else {
					$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_apolice='$vendas_apolice', 
							vendas_proposta='$vendas_proposta', 
							vendas_num_apolice='$vendas_num_apolice', 
							vendas_valor='$vendas_valor', 
							vendas_dia_desconto='$vendas_dia_desconto',  
							vendas_pgto='$vendas_pgto'" . $campos_update . " ,
							vendas_ben='$vendas_ben', 
							vendas_parent='$vendas_parent', 
							vendas_debito_banco='$vendas_debito_banco', 
							vendas_debito_ag='$vendas_debito_ag', 
							vendas_debito_ag_dig='$vendas_debito_ag_dig', 
							vendas_debito_cc='$vendas_debito_cc', 
							vendas_debito_cc_dig='$vendas_debito_cc_dig', 
							vendas_debito_banco_2='$vendas_debito_banco_2', 
							vendas_debito_ag_2='$vendas_debito_ag_2', 
							vendas_debito_cc_2='$vendas_debito_cc_2', 
							vendas_debito_banco_3='$vendas_debito_banco_3', 
							vendas_debito_ag_3='$vendas_debito_ag_3', 
							vendas_debito_cc_3='$vendas_debito_cc_3', 
							vendas_banco='$vendas_banco', 
							vendas_status='$vendas_status', 
							vendas_alteracao='$vendas_alteracao', 
							vendas_telefone='$vendas_telefone', 
							vendas_telefone2='$vendas_telefone2', 
							vendas_user='$vendas_user',
							forma_envio_kitcert='$forma_envio_kitcert'
							WHERE vendas_id='$vendas_id' ") or die(mysql_error());

					$nome = "nome";
					$nasc = "nascimento";
					$cpf = "cpf";
					$email = "email";
					// echo "vendasid -" . $vendas_id . "<br>vendas_apolice -". $vendas_apolice . "<br> nome -" . $nome . " <br> nasci -". $nasc . " <br> cpf-" .$cpf. " <br> email-". $email;
					// addTempo_assist($vendas_id, $vendas_apolice, $nome, $nasc, $cpf, $email);
					echo "Venda Atualizada com Sucesso";
				}
				if ($_GET["cliente_cargo_cod"]) {
					$query = mysql_query("UPDATE sys_inss_clientes SET cliente_cargo_cod='" . $_GET['cliente_cargo_cod'] . "' 
						WHERE cliente_cpf='" . $row_old['cliente_cpf'] . "';") or die(mysql_error());
					echo "<br>Cargo do cliente Atualizado com Sucesso<br>";
				}

				if ($vendas_proposta != $row_old['vendas_proposta']) {
					if (!$row_old['vendas_proposta']) {
						if (!$proposta_api) {
							$vendas_obs .= " (Nº da proposta cadastrado manualmente " . $vendas_proposta . ")";
						}
					} else {
						if (!$vendas_proposta) {
							$vendas_obs .= " (Nº da proposta removido)";
						} else {
							if (!$proposta_api) {
								$vendas_obs .= " (Nº da proposta alterado manualmente de " . $row_old['vendas_proposta'] . " para " . $vendas_proposta . ")";
							} else {
								$vendas_obs .= " (Nº de proposta anterior era " . $row_old['vendas_proposta'] . ")";
							}
						}
					}
				}
				registraHistorico($vendas_id, $vendas_user, $vendas_obs, $vendas_status, $row_old['vendas_status'], $vendas_alteracao, $vendas_contrato_fisico, $result['capture']['authorizer_message']);
				?>
				<br>
				<?php if ($userid != 42) : ?>
					<?php if (((!$retornoJson->ttError->error) && (!$err) && ($userid != 42)) || ($vendas_banco != 7) || ($vendas_pgto == 3)) : ?>
						<?php if (!$nao_redireciona) : ?>
							<meta http-equiv="Refresh" content="2; url=<?php echo $_SERVER['HTTP_REFERER'];
																		if ($_GET["salvar"] == "salvar_fechar") {
																			echo "&fechar=1";
																		} ?>"><?php endif; ?>
						<table width="100%" height="99%" border="0" align="center" cellpadding="0" cellspacing="2" bgcolor="#eeeee0">
							<div align="center">
								</br>
								<img src="sistema/imagens/calculando.gif">
								</br>
								<strong> SALVANDO VENDA! </strong></br>
								<br />
							<?php else : ?>
								<a href="<?php echo $_SERVER['HTTP_REFERER'];
											if ($_GET["salvar"] == "salvar_fechar") {
												echo "&fechar=1";
											} ?>"><button class="button validate png" type="button">Prosseguir</button></a>
							<?php endif; ?>
						<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					<?php


					?>