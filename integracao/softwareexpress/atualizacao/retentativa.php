<?php

// Dados para homologação.
$CURLOPT_HTTPHEADER = array(
	"Accept: application/json",
	"merchant_id: 34064579000178",
	"merchant_key: 7B9117D92A089159AF650D2C28D73C17A6331938C477CC088E013AED2446EDAF",
	"Content-Type: application/json",
	"cache-control: no-cache"
);
$link_prefixo = "https://esitef-ec.softwareexpress.com.br";


// }
$path_includes = "../../../sistema/sistema/";
$path_includes = "../var/www/html/sistema/sistema/";

$Arquivo_conect = "connect_seguro.php";

include($path_includes.$Arquivo_conect);    

//$data = date('Y-m-d', strtotime('-4 days'));
$data = date('Y-m-d', strtotime('-4 days'));
//$data = "2021-10-28";
//$dataAtual ="2021-09-30";

?><br><?php
?><br><?php
?><br><?php

$query = "SELECT seg.vendas_id, seg.vendas_id, transacao_status, tef.transacao_data, tef.transacao_token, tef.transacao_cliente_cpf, tef.transacao_username, 
tef.transacao_user_id, tef.transacao_venda_id, tef.transacao_id, 
tef.transacao_tipo_plano,  tef.transacao_cartao_adm, tef.transacao_cartao_num, tef.transacao_cartao_validade_mes, tef.transacao_cartao_validade_ano, tef.transacao_valor
FROM sys_vendas_seguros seg
INNER JOIN sys_vendas_transacoes_tef tef ON seg.vendas_id = tef.transacao_venda_id 
WHERE tef.transacao_status LIKE '%RET%' AND  tef.transacao_data LIKE '2021-11-13' AND seg.vendas_status = '96';";

$result = mysqli_query($con, "SELECT seg.vendas_id, seg.vendas_id, transacao_status, tef.transacao_data, tef.transacao_token, tef.transacao_cliente_cpf, tef.transacao_username, 
tef.transacao_user_id, tef.transacao_venda_id, tef.transacao_id, 
tef.transacao_tipo_plano,  tef.transacao_cartao_adm, tef.transacao_cartao_num, tef.transacao_cartao_validade_mes, tef.transacao_cartao_validade_ano, tef.transacao_valor
FROM sys_vendas_seguros seg
INNER JOIN sys_vendas_transacoes_tef tef ON seg.vendas_id = tef.transacao_venda_id 
WHERE tef.transacao_status LIKE '%RET%' AND tef.transacao_data LIKE '%".$data."%' AND seg.vendas_status = '96' 
GROUP BY transacao_venda_id ORDER BY `transacao_id` ASC;") or die(mysqli_error($con));

//echo $query;

$count = 0;
while($row  = mysqli_fetch_array( $result ))
{
	echo "inicio transacao_id - ".$row['transacao_id']."<br>";
	$count++;
        
        $query= "SELECT *  FROM `sys_vendas_transacoes_tef` WHERE `transacao_venda_id` = ".$row['transacao_venda_id']." 
        AND `transacao_status` = 'CON' 
        AND `transacao_id` < ".$row['transacao_id']."
        AND transacao_cartao_num != '' ORDER BY `sys_vendas_transacoes_tef`.`transacao_id`";

		//echo $query;

        $resultCartao = mysqli_query($con, $query) or die(mysqli_error($con));

        while($rowCartao  = mysqli_fetch_array( $resultCartao )){

			// if($count % 2 == 0){
				
			// 	$cartao_num = "1234567891234567";
			// 	$transacao_cartao_validade_mes = $rowCartao["transacao_cartao_validade_mes"];
			// 	$transacao_cartao_validade_ano = $rowCartao["transacao_cartao_validade_ano"];

			// }else{
				$cartao_num = $rowCartao["transacao_cartao_num"];
				$transacao_cartao_validade_mes = $rowCartao["transacao_cartao_validade_mes"];
				$transacao_cartao_validade_ano = $rowCartao["transacao_cartao_validade_ano"];
			// }

        }


        // captura dos dados postados.
        $dados_Originais = (object) array(
        'token' => $row["transacao_token"],
        'cpf' => $row["transacao_cliente_cpf"],
        'username' => $row["transacao_username"],
        'user_id' => $row["transacao_user_id"],
        'venda_id' => $row["transacao_venda_id"],
        'plano' => $row["transacao_tipo_plano"],
        'card_adm' => $row["transacao_cartao_adm"],
        'card_num' => $cartao_num,
        'card_validade_mes' => $transacao_cartao_validade_mes,
        'card_validade_ano' => $transacao_cartao_validade_ano,
        'transacao_valor' =>$row["transacao_valor"]
        );
             
        $transaction_id = insereRegistroTransacao($con, $dados_Originais);

        /* ----- DADOS DA REQUISIÇÃO DA TRANSAÇÃO ----- */
        $payment_amount = valorParaAmount($dados_Originais->transacao_valor); // valor da parcela, exemplo 19,90 R$ deve ser representado como 1990 sem ponto nem virgulas incluindo os centavos sempre.
        $card_number = $dados_Originais->card_num; // exemplo: "5281735839609922";
        $card_expiry_date = $dados_Originais->card_validade_mes."".substr($dados_Originais->card_validade_ano,2); // Preencher com mês ano exemplo '1220' (mês 12 ano 2020)
        //FIM Valores falsos para teste, comentar ou apagar posteriormente.
            
        // Início CURL
        $dataPreAut = (object) array(
            'order_id' => $row['transacao_venda_id'],
            'amount' => $payment_amount,
            'authorizer_id' => $dados_Originais->card_adm
        );


        $url_pre_aut = $link_prefixo.'/e-sitef/api/v1/transactions/';
        $curl_resposta = executaCurlPreAutorizacao( $dataPreAut, $url_pre_aut, $CURLOPT_HTTPHEADER );        
        
		echo "Resposta Pre Autorização - ";
		$respostaJson = json_decode($curl_resposta);
		echo "<br>";
        // FIM CURL

        $dadosApi = (object) array(
            'nit' => mysqli_real_escape_string($con, $respostaJson->payment->nit),
            'status' => mysqli_real_escape_string($con, $respostaJson->payment->status),
			'esitef_usn' => mysqli_real_escape_string($con, $respostaJson->payment->esitef_usn),
            'transacao_id' => $transaction_id
        );
        //print_r($dados);
        atualizaTransacao($con, $dadosApi);

        // inserir logs
         echo " <BR> resposta da API - " . print_r($respostaJson);
        $sql_insert_log = "INSERT INTO sys_vendas_transacoes_tef_log 
							   (
							   	transacao_id,
							   	user_id,
							   	clients_cpf,
							   	data,
							   	erro_cod,
							   	status,
							   	response_json
							   )
							   VALUES
							   (
								'".$transaction_id."',
								'".$row["transacao_user_id"]."',
								'".$row["transacao_cliente_cpf"]."',
								NOW(),
								'".$respostaJson->payment->authorizer_code."',
								'".$respostaJson->payment->status."',
								'".mysqli_real_escape_string($con, $curl_resposta)."'
                               );";
                                //echo "query do log insert request iinicial - ".$sql_insert_log;
			$result_log = mysqli_query($con, $sql_insert_log);




			//$result = json_encode($result);
			echo "<br> result -" .$result_log;


        //fim requests
		echo "<br>";
		echo "Resposta(json) fim do request - ";
        echo json_encode($respostaJson);

    //inicio do DO
 
    // fim captura dos dados postados.
	
	// registro inicial da transação que será efetuada e captura do id.
	//$transaction_id = $transaction_id;
   // echo $transaction_id;

	/* ----- DADOS DA REQUISIÇÃO DA TRANSAÇÃO ----- */
	$card_number = $dados_Originais->card_num; // exemplo: "5281735839609922";
	$card_expiry_date = $dados_Originais->card_validade_mes."".substr($dados_Originais->card_validade_ano,2); // Preencher com mês ano exemplo '1220' (mês 12 ano 2020)
	//FIM Valores falsos para teste, comentar ou apagar posteriormente.
    
	
	// Início CURL
	$dataTransaction = (object) array(
		    'card' => (object) array(
			'number' => $card_number,
			'expiry_date' => $card_expiry_date
		)
	);
 
	$url_do_transaction = $link_prefixo.'/e-sitef/api/v1/payments/'. $dadosApi->nit;
    //echo $url_do_transaction;
	// Tenta efetivar a transação via curl            
	$response_transaction = doTransactionCurl($dataTransaction, $url_do_transaction, $CURLOPT_HTTPHEADER);
    echo "<br> response DO - " . $response_transaction;
	// FIM CURL
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
		$log_resposta = $response_transaction->payment;
		$log_resposta_ecoded = json_encode($response_transaction->payment);
		$final_response = json_encode($response_transaction, true);

	}

	$log_resposta = $response_transaction->payment;
		$log_resposta_ecoded = json_encode($response_transaction->payment);
		$final_response = json_encode($response_transaction, true);

    
    $obj =  json_decode($response_transaction);
    $obj2 = json_encode($response_transaction->payment);

    echo "<br> status do retorno API: " . $obj->payment->status;
	
	$result_venda = mysqli_query($con, "SELECT vendas_id, 
	cliente_cpf, 
	vendas_apolice, 
	apolice_nome, 
	vendas_status, 
	vendas_receita, 
	vendas_recebido_prolabore, 
	vendas_dia_ativacao, 
	vendas_banco 
	FROM sys_vendas_seguros 
	INNER JOIN sys_vendas_apolices ON sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id 
	WHERE vendas_id = '" . $row['transacao_venda_id'] . "';")
	or die(mysqli_error($con));
	$row_venda = mysqli_fetch_array($result_venda);
	
	$transacao_data = date("Y-m-d H:m:s");
	$date1=date_create($row_venda['vendas_dia_ativacao']);
	$date2=date_create($transacao_data);
	$diff=date_diff($date1,$date2);
	$transacao_parcela = ($diff->format("%a") / 30);
	$pos = strrpos($transacao_parcela,'.');
	if($pos !== false){
	  $transacao_parcela = substr($transacao_parcela,0,$pos);
	}
	$transacao_tipo = "2";
	$transacao_data_id = date("YmdHms");
	$transacao_id = $row_venda['vendas_id']."_".$transacao_data_id."_".$transacao_parcela."_".$transacao_tipo;
	$vendas_obs = "";
	
	if($obj->payment->status == "CON"){
		$result_pagas = mysqli_query($con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $row_venda['vendas_id'] . "' AND transacao_recebido = 1;")
		or die(mysqli_error($con));
		$row_pagas = mysqli_fetch_array($result_pagas);
		$total_parcelas = $transacao_parcela;
		$parcelas_pagas = $row_pagas["total"];
		if($parcelas_pagas == $total_parcelas){
			$update_status = ", vendas_status='67'";
			$vendas_status = 67;
			echo "<br>Venda ativa OK. <br/>";
		}elseif($parcelas_pagas > $total_parcelas){
			$update_status = ", vendas_status='94'";
			$vendas_status = 94;
			$vendas_obs = "Identificado pagamento de mais parcelas do que o tempo de vigência do plano! Total de Parcelas: ".$total_parcelas.". Pagas: ".$parcelas_pagas.". ";
			echo "<br>PARCELAS A MAIS. <br/>";
		}else{
			$update_status = ", vendas_status='88'";
			$vendas_status = 88;
			$vendas_obs = "Não pagamento de parcelas identificado! Total de Parcelas: ".$total_parcelas.". Pagas: ".$parcelas_pagas.". ";
			echo "<br>Inadimplencia de parcelas anteriores. <br/>";
		}
		
		$update_transaction_query ="UPDATE sys_vendas_transacoes_tef
		SET transacao_status = 'RCON',
		transacao_data = NOW()
		WHERE transacao_id = '". $row["transacao_id"] . "';";
		$result_transaction = mysqli_query($con, $update_transaction_query);
		
		echo "<br>update_transaction_query ORIG: ".$update_transaction_query."<br>";
		
		$update_transaction_query ="UPDATE sys_vendas_transacoes_tef
		SET transacao_status = 'CON',
		transacao_data = NOW()
		WHERE transacao_id = '". $transaction_id . "';";
		$result_transaction = mysqli_query($con, $update_transaction_query);
		
		echo "<br>update_transaction_query NOVA: ".$update_transaction_query."<br>";
		
		$result_parcela = mysqli_query($con, "SELECT transacao_id FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $row_venda['vendas_id'] . "' AND transacao_recebido = 2 ORDER BY transacao_data DESC LIMIT 0, 1;")
		or die(mysqli_error($con));
		$row_parcela = mysqli_fetch_array($result_parcela);
		
		if($row_parcela["transacao_id"]){
			$update_parcela_query ="UPDATE sys_vendas_transacoes_seg
			SET transacao_recebido = 1,
			transacao_motivo = '0', 
			transacao_data_importacao = NOW(), 
			transacao_usuario = 'integrador.automatico' 
			WHERE transacao_id = '". $row_parcela['transacao_id'] . "';";
			$result_transaction = mysqli_query($con, $update_parcela_query);
			echo "<br>update_parcela_query: ".$update_parcela_query."<br>";
		}
		
		$vendas_recebido_prolabore = $row_venda['vendas_recebido_prolabore'] + $row['transacao_valor'];
		$vendas_receita = $row_venda['vendas_receita'] + $row['transacao_valor'];
		$update_valores = ", vendas_recebido_prolabore='$vendas_recebido_prolabore', vendas_receita='$vendas_receita' ";
    }else{
		if($row["transacao_status"] == "RET"){
			$transacao_status = 'RET1';
		}else{
			$num_retentativa = substr($row["transacao_status"], -1);
			if($num_retentativa < 3){
				$num_retentativa++;
				$transacao_status = 'RET'.$num_retentativa;
            }else{
				//ATUALIZAR PARA NEGADA (INV)!
				$transacao_status = 'RINV';
			}

		}
		$update_transaction_query ="UPDATE sys_vendas_transacoes_tef
		SET transacao_status = '".$transacao_status."',
		transacao_data = NOW()
		WHERE transacao_id = '". $row["transacao_id"] . "';";
		$result_transaction = mysqli_query($con, $update_transaction_query);
		
		echo "<br>RET update_transaction_query ORIG: ".$update_transaction_query."<br>";
		
		$update_transaction_query ="UPDATE sys_vendas_transacoes_tef
		SET transacao_status = 'INV',
		transacao_data = NOW()
		WHERE transacao_id = '". $transaction_id . "';";
		$result_transaction = mysqli_query($con, $update_transaction_query);
		
		echo "<br>RET update_transaction_query NOVA: ".$update_transaction_query."<br>";
		
		if($transacao_status == 'RINV'){
			if($transacao_parcela > 3){
				$transacao_parcela_menos1 = $transacao_parcela - 1;
				$transacao_parcela_menos2 = $transacao_parcela - 2;
				$result_critica = mysqli_query($con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE 
														transacao_id_venda = '" . $row_venda['vendas_id'] . "' 
														AND transacao_recebido = 1 
														AND (transacao_parcela = '".$transacao_parcela_menos1."' OR transacao_parcela = '".$transacao_parcela_menos2."');")
				or die(mysqli_error($con));
				$row_pagas_critica = mysqli_fetch_array($result_critica);
				
				if($row_pagas_critica["total"]){
					$update_status = ", vendas_status='97'";
					$vendas_status = 97;
					$transacao_recebido = 2;
					echo "Inadimplencia comum. <br/>";
				}else{
					$update_status = ", vendas_status='93'";
					$vendas_status = 93;
					$transacao_recebido = 2;
					$vendas_obs = "INADIMPLENCIA CRITICA identificada, pelo não recebimento das parcelas ".$transacao_parcela.", ".$transacao_parcela_menos1." e ".$transacao_parcela_menos2.". ";
					echo "Inadimplencia critica. <br/>";
				}
			}else{
				$update_status = ", vendas_status='97'";
				$vendas_status = 97;
				$transacao_recebido = 2;
				echo "Inadimplencia comum. <br/>";
			}
		}else{
			$update_status = ", vendas_status='96'";
			$vendas_status = 96;
			$transacao_recebido = 2;
			echo "Em Retentativa. <br/>";
		}

		//CADASTRANDO NA FILA DE SMS DE COBRANÇA DO PORTAL APOBEM:
		$secret_key = "u";
		$data_link = array("code" => $secret_key."-".$row["transacao_cliente_cpf"]."-".$row_venda['vendas_id']);
		$data_string = json_encode($data_link);
		
		// criptografia
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));
		$encrypted_data = openssl_encrypt($data_string, 'aes-128-cbc', $secret_key, 0, $iv);
		$encrypted_data = base64_encode($encrypted_data . '::' . $iv);
		$link = "https://www.apobem.com.br/portal/?schdl=1&" . http_build_query(array("data" => $encrypted_data));
		$cliente_nome = explode(" ", $row_venda['cliente_nome']);
		$notificacao_mensagem = "Olá ".$cliente_nome[0].", não conseguimos realizar a cobrança da sua mensalidade APOBEM no cartão de crédito. Regularize já através do link ".$link;
    }
	
	$vendas_obs = $vendas_obs."Venda atualizada via Integrador Automatico. (status anterior: ".$row_venda['vendas_status'].", numero proposta: ".$row_venda['vendas_id'].")";
	
    //Insere LOG do DO
    $sql_insert_log = "INSERT INTO sys_vendas_transacoes_tef_log 
    (
        transacao_id,
        user_id,
        clients_cpf,
        data,
        erro_cod,
        status,
        response_json
    )
    VALUES
    (
    '".$transaction_id."',
    '".$row["transacao_user_id"]."',
    '".$row["transacao_cliente_cpf"]."',
    NOW(),
    '".$obj->payment->authorizer_code."',
    '".$obj->payment->status."',
    '".mysqli_real_escape_string($con, $response_transaction)."'
    )";

    //echo "query do DO apos fazer tudo - " .  $sql_insert_log;
    mysqli_query($con, $sql_insert_log);
	
	//echo "<br>sql_insert_log: ".$sql_insert_log."<br>";
	
	$status_nao_atualizar = Array('19', '58', '76', '77', '86', '87', '90', '91');
	if(in_array($row_venda['vendas_status'], $status_nao_atualizar)){
		$update_status = "";
		$vendas_status = $row_venda['vendas_status'];
		$vendas_obs = "Stauts da Proposta ".$row_venda['vendas_id']." não atualizado, pois o status atual (".$row_venda['vendas_status'].") não permite! (19=CANCELADO, 58=ENVIADO PARA RETENCAO ou 76=CANCELADO INTERNO)";
		$log_linha_erro = "<td>Stauts da Proposta ".$row_venda['vendas_id']." não atualizado, pois o status atual (".$row_venda['vendas_status'].") não permite! (19=CANCELADO, 58=ENVIADO PARA RETENCAO ou 76=CANCELADO INTERNO)</td>";
	}else{		
		//ALOCAÇÃO DE CLIENTES INADIMPLENTES EM CAMPANHA:
		//if($vendas_status == 88 || $vendas_status == "RET1"){
		if($vendas_status == 88 || $vendas_status == 96){
			$cliente_cpf = $row_venda['cliente_cpf'];
			$username = "integrador.automatico";
			if($row_venda["vendas_banco"] == 3){$campanha_grupo_id = 13;}else{$campanha_grupo_id = 12;}
			
			mysqli_close($con);
			include($path_includes."connect_db02.php");
			$sql_menor_campanha = mysqli_query($con,"
				SELECT campanha_id, campanha_nome, (SELECT COUNT(cliente_cpf) AS total FROM sys_inss_clientes WHERE cliente_campanha_id = campanha_id AND cliente_parecer = 100) AS total FROM sys_campanhas 
				INNER JOIN sys_campanhas_grupos ON sys_campanhas.campanha_grupo_id = sys_campanhas_grupos.grupo_id 
				WHERE campanha_grupo_id = " . $campanha_grupo_id . " 
				ORDER BY total ASC 
				LIMIT 0, 1;") or die (mysqli_error($con));
			
			$result_campanha_grupos = mysqli_query($con,"SELECT grupo_id, grupo_nome FROM sys_campanhas_grupos WHERE grupo_id = '" . $campanha_grupo_id . "';")
				or die(mysqli_error($con));
				$row_campanha_grupos = mysqli_fetch_array( $result_campanha_grupos );
			$acionamento_obs .= " (Campanha de destino: Grupo ".$row_campanha_grupos['grupo_nome'];
			
			if(mysqli_num_rows($sql_menor_campanha))
			{
				$row_menor_campanha = mysqli_fetch_array( $sql_menor_campanha );
				$cliente_campanha_id_update = ", cliente_campanha_id = '".$row_menor_campanha['campanha_id']."'";

				$acionamento_obs .= " - Nome: ".$row_menor_campanha['campanha_nome']." ".$row_menor_campanha['campanha_id'].").";
				
			}else{
				$acionamento_obs .= " - Nenhuma campanha ativa neste grupo).";
			}
			
			//ESPELHA CLIENTES:
			$clients_cpf = $cliente_cpf;
			include($path_includes."cliente/espelha_confere.php");
			if (!$row_espelha_confere["total"]){
				include($path_includes.$Arquivo_conect);
				include($path_includes."utf8.php");
				
				include($path_includes."cliente/espelha.php");
				
				include($path_includes."connect_db02.php");
				include($path_includes."utf8.php");
				
				include($path_includes."cliente/espelha_insere.php");
			}
			
			$query = mysqli_query($con,"UPDATE sys_inss_clientes
				SET 
				cliente_parecer = '100',
				cliente_usuario = '$username',
				cliente_alteracao = NOW()".
				$cliente_campanha_id_update.
				" WHERE cliente_cpf='$cliente_cpf';") or die(mysqli_error($con));
			echo "<br>Parecer Atualizado com Sucesso <br/>";
			
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
			NOW(),
			'".$row_menor_campanha['campanha_id']."',
			'94');";
			if (mysqli_query($con,$sql)){
				$acionamento_id = mysql_insert_id();
				echo "<br>Acionamento Registrado com Sucesso. </br>";
			} else {
				die('Error: ' . mysqli_error($con));
			}
			
			
		}
		//FIM ALOCAÇÃO DE CLIENTES INADIMPLENTES EM CAMPANHA:
	}
	mysqli_close($con);	
	include($path_includes.$Arquivo_conect); 
	$query = mysqli_query($con, "UPDATE sys_vendas_seguros SET 
	vendas_alteracao = NOW(), vendas_user='integrador.automatico'".$update_status.$update_valores." 
	WHERE vendas_id='".$row_venda['vendas_id']."';") or die(mysqli_error($con));
	
	echo "<br>Venda atualizada Registrado com Sucesso. </br>";
	
	$insere_registro_venda_query = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, 
	`vendas_id`, 
	`registro_usuario`, 
	`registro_obs`, 
	`registro_status`, 
	`registro_data`) 
	VALUES (NULL, 
	'".$row_venda['vendas_id']."',
	'integrador.automatico',
	'$vendas_obs',
	'$vendas_status',
	NOW());"; 
	//echo "<br>insere_registro_venda_query: <br>".$insere_registro_venda_query;
	if (mysqli_query($con, $insere_registro_venda_query)){
		echo "<br>Histórico Registrado com Sucesso.<br>";
	} else {
		die('Error: ' . mysqli_error($con));
	}
	echo "fim<br><br><br><br>";
	//return json_encode($response_transaction);
}


function getTransactionIdByNit($con, $nit){
	$sql_tsid = "SELECT transacao_id FROM sys_vendas_transacoes_tef WHERE transacao_nit = '".$nit."' LIMIT 0,1;";
    echo "query do DO get trans " . $sql_tsid;
	$result_tsid = mysqli_query($con, $sql_tsid) or die(mysql_error($con));
	$row_tsid = mysqli_fetch_assoc($con, $result_tsid);
	return $row_tsid["transacao_id"];
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

function atualizaTransacao($con, $dadosApi)
{
	$update_transaction_query ="UPDATE sys_vendas_transacoes_tef
	SET transacao_nit = '". $dadosApi->nit."',
	transacao_status = '". $dadosApi->status ."',
	transacao_esitef_usn = '". $dadosApi->esitef_usn ."'
	WHERE transacao_id = '". $dadosApi->transacao_id ."'";
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


