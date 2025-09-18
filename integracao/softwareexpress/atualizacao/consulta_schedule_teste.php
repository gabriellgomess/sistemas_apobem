<?php
header("Access-Control-Allow-Origin: *");
date_default_timezone_set('America/Sao_Paulo');
$CURLOPT_HTTPHEADER = array(
			"Accept: application/json",
			"merchant_id: 34064579000178",
			"merchant_key: 7B9117D92A089159AF650D2C28D73C17A6331938C477CC088E013AED2446EDAF",
			"Content-Type: application/json",
			"cache-control: no-cache"
);
$link_prefixo = "https://esitef-ec.softwareexpress.com.br";
include("../../../sistema/sistema/connect_seguro.php");      
$data = date('d/m/Y', strtotime('-1 days'));

$url = $link_prefixo.'/e-sitef/api/v1/transactions?start_date='.$data.'&end_date='.$data;
$metodo = 'GET';
echo $url."<br>";
$orignal_parse = parse_url($url, PHP_URL_HOST);
$get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
$read = stream_socket_client(
	"ssl://".$orignal_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get
);
$cont = stream_context_get_params($read);
openssl_x509_export($cont["options"]["ssl"]["peer_certificate"],$certificado);

$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 60,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			//CURLOPT_USERPWD => 'usuario:senha',
	CURLOPT_CUSTOMREQUEST => $metodo,
	//CURLOPT_POSTFIELDS => (http_build_query($data_url) != '') ? http_build_query($data_url) : '', // url
	//CURLOPT_POSTFIELDS => ($data_corpo != '') ? $data_corpo : '', // corpo do envio
	//CURLINFO_CONTENT_TYPE => 'application/x-www-form-urlencoded"',
			//===========================
			// Certificado SSL do servidor remoto
			//=========================== 
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_SSL_VERIFYHOST => 0,
	//CURLOPT_CAINFO => $certificado,
			//===========================
	CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER
));

$response->resposta = json_decode(curl_exec($curl));

$response->erro = curl_error($curl);
$response->transaction_error_number = curl_errno($curl);

curl_close($curl);

echo "<pre>";
print_r( $response );
$importacao_nome = "INTEGRACAO_SE_".date('YmdHis');

//Registrando importação:
$sql = "INSERT INTO `sistema`.`sys_vendas_importacoes` (`importacao_id`, 
`importacao_nome`, 
`importacao_tipo`, 
`importacao_arquivo`, 
`importacao_data`, 
`importacao_usuario`) 
VALUES (NULL, 
'".$_POST['importacao_nome']."',
'4',
'$url',
NOW(),
'integrador.automatico');"; 
if (mysqli_query($con,$sql)){
	$importacao_id = mysqli_insert_id($con);
	echo "Importação Registrada com Sucesso.<br>";
} else {
	die('Error: ' . mysqli_error($con));
}
$transacao_data_id = date("Ymd_Hms");
if($response->resposta->code == 0){
	echo "Consulta OK<br>";
	
	/* [authorizer_code] => 51
	[authorizer_message] => Autorizacao negada
	[status] => NEG
	[nit] => c7e044cf075dffa49eb7f7f9f5cb34f399d53aa1477cde0d56c0753f74ec81fd
	[order_id] => 33879
	[authorizer_id] => 2
	[acquirer_id] => 201
	[acquirer_name] => Cielo e-Commerce
	[esitef_usn] => 210323732391934
	[tid] => e9ed53dc-c557-4c88-963f-1b8a557ac415
	[amount] => 8250
	[payment_type] => C
	[authorizer_merchant_id] => 484e644e-053a-458e-b5f5-caf8e879e4ce
	[type] => F
	[merchant_id] => 34064579000178
	[creation_date] => 23/03/2021T17:37
	[installments] => 1 */
	
	$cont = 1;
	foreach ($response->resposta->transactions as $transacao){
		if($transacao->order_id > 100000 && $transacao->type == "P" && $cont < 2){
			$transacao_valor = $transacao->amount / 100;
			$transacao_data = substr($transacao->creation_date, 6, 4)."-".substr($transacao->creation_date, 3, 2)."-".substr($transacao->creation_date, 0, 2)." ".substr($transacao->creation_date, 11, 2).":".substr($transacao->creation_date, 14, 2).":00";
			$transacao_mes = substr($transacao->creation_date, 3, 2)."/".substr($transacao->creation_date, 6, 4);
			echo "authorizer_code: ".$transacao->authorizer_code." | ";
			echo "authorizer_message: ".$transacao->authorizer_message." | ";
			echo "status: ".$transacao->status." | ";
			echo "nit: ".$transacao->nit." | ";
			echo "order_id: ".$transacao->order_id." | ";
			echo "type: ".$transacao->type." | ";
			echo "<br>";
			//print_r( $transacao );
			if($row_venda["vendas_id"] == 789465123465789){
			$result_venda = mysqli_query($con, "SELECT vendas_id, 
			cliente_cpf, 
			apolice_nome, 
			vendas_status, 
			vendas_receita, 
			vendas_recebido_prolabore, 
			vendas_dia_ativacao 
    		FROM sys_vendas_seguros 
			INNER JOIN sys_vendas_apolices ON sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id 
   	 		WHERE vendas_id = '" . $transacao->order_id . "';")
    		or die(mysqli_error($con));
			$row_venda = mysqli_fetch_array($result_venda);
			if($row_venda["vendas_id"]){
				$transacao_proposta = $row_venda["vendas_id"];
				if($transacao->status == "CON"){$transacao_status = "CON";}else{$transacao_status = "INV";}
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
						transacao_esitef_usn,
						transacao_authorizer_message,
						transacao_status)
						VALUES (
						'".$transacao->nit."',
						'".$row_venda['cliente_cpf']."',
						'integrador.automatico',
						'42',
						'EsearR31234fpssa0vfc9o',
						'".$transacao_valor."',
						'".$row_venda['vendas_id']."',
						'".$transacao_data."',
						'".substr($transacao->creation_date, 0, 2)."',
						'".$row_venda['apolice_nome']."',
						'".$transacao->authorizer_id."',
						'".$transacao->authorizer_id."',
						'".$transacao->esitef_usn."',
						'".$transacao->authorizer_message."',
						'".$transacao_status."'
						);";
				echo "insere_transaction_query: <br>".$insere_transaction_query;
				//$result_transaction = mysqli_query($con, $insere_transaction_query);
				//$transaction_id = mysqli_insert_id($con);
				
				$insere_log_query ="INSERT INTO sys_vendas_transacoes_tef_log (
						transacao_id,
						user_id,
						clients_cpf,
						data,
						erro_cod,
						status,
						esitef_usn,
						response_json)
						VALUES (
						'".$transaction_id."',
						'42',
						'".$row_venda['cliente_cpf']."',
						NOW(),
						'".$transacao->authorizer_code."',
						'".$transacao->status."',
						'".$transacao->esitef_usn."',
						'".json_encode($transacao)."'
						);";
				echo "<br>insere_log_query: <br>".$insere_log_query;
				//$result_transaction = mysqli_query($con, $insere_log_query);
				//$transaction_id = mysqli_insert_id($con);
				
				// ## IMPLEMENTAR O INSERT DO HISTORICO DA VENDA
				$status_nao_atualizar = Array('19', '58', '76', '77', '86', '87', '90', '91');
				if(in_array($row_venda['vendas_status'], $status_nao_atualizar)){
					$update_status = "";
					$vendas_status = $row_venda['vendas_status'];
					$vendas_obs = "Stauts da Proposta ".$transacao_proposta." não atualizado, pois o status atual (".$row_venda['vendas_status'].") não permite! (19=CANCELADO, 58=ENVIADO PARA RETENCAO ou 76=CANCELADO INTERNO)";
					$log_linha_erro = "<td>Stauts da Proposta ".$transacao_proposta." não atualizado, pois o status atual (".$row_venda['vendas_status'].") não permite! (19=CANCELADO, 58=ENVIADO PARA RETENCAO ou 76=CANCELADO INTERNO)</td>";
				}else{
					if($transacao->status == "CON"){
						$transacao_tipo = "2";
						$transacao_recebido = 1;
						$date1=date_create($row_venda['vendas_dia_ativacao']);
						$date2=date_create($transacao_data);
						$diff=date_diff($date1,$date2);
						$transacao_parcela = ($diff->format("%a") / 30);
						$pos = strrpos($transacao_parcela,'.');
						if($pos !== false){
						  $transacao_parcela = substr($transacao_parcela,0,$pos);
						}
						$update_status = ", vendas_status='10'";
						$vendas_status = 10;
						$vendas_recebido_prolabore = $row_venda['vendas_recebido_prolabore'] + $transacao_valor;
						$vendas_receita = $row_venda['vendas_receita'] + $transacao_valor;
						$update_valores = ", vendas_recebido_prolabore='$vendas_recebido_prolabore', vendas_receita='$vendas_receita' ";
						$transacao_id = $row_venda['vendas_id']."_".$transacao_data."_".$transacao_parcela."_".$transacao_tipo;
					}else{
						$update_status = ", vendas_status='88'";
						$vendas_status = 88;
						$transacao_recebido = 2;
					}
					
					//INSERÇÃO DA TRANSAÇÃO - (PARCELA):
					$vendas_recebido_novo = $transacao_valor;
					$transacao_id_venda = $row_venda['vendas_id'];
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
					'$transacao_id_venda',
					'$transacao_proposta',
					'$transacao_valor',
					'$transacao_recebido',
					'".$transacao->authorizer_code."',
					NOW(),
					'$transacao_data',
					'$transacao_mes',
					'$transacao_parcela',
					'$transacao_tipo');";
					if (mysqli_query($con,$sql)){
						echo "Transação <strong>".$transacao_id."</strong> Cadastrada com Sucesso, para a Proposta <strong>".$transacao_proposta."</strong>, Cod da venda: . <strong>".$transacao_id_venda."</strong></br>";
					} else {
						die('Error: ' . mysqli_error($con));
					}
					$vendas_obs = "Venda atualizada via Integrador Automatico. (status anterior: ".$row_venda['vendas_status'].", numero proposta: ".$transacao_proposta.")";
				}
				
				$query = mysqli_query($con, "UPDATE sys_vendas_seguros SET 
				vendas_alteracao = NOW(), vendas_user='integrador.automatico'".$update_status.$update_valores." 
				WHERE vendas_id='".$row_venda['vendas_id']."';") or die(mysqli_error($con));
				
				$sql = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, 
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
				'$agora');"; 
				if (mysqli_query($con, $sql)){
					$log_linha = $log_linha."<td>Histórico Registrado com Sucesso.</td>";
				} else {
					die('Error: ' . mysqli_error($con));
				}
			}
			echo "<br>";
			}
			$cont++;
		}
	}
}
echo "</pre>";
?>