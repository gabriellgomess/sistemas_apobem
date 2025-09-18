<?php
// *
// *
// * INICIO DA ÁREA RESPONSÁVEL POR CONSULTAR AS TRANSAÇÕES DO DIA ANTERIOR NA SOFTWARE EXPRESS
// *
// *
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

$path_includes = "/var/www/html/sistema/sistema/";
//$path_includes = "../../../sistema/sistema/";

$Arquivo_conect = "connect_seguro.php";

include($path_includes.$Arquivo_conect);      

$data = date('d/m/Y', strtotime('-1 days'));
$date_ontem = date('Y-m-d', strtotime('-1 days'));
// $dia_='31';
// $data = $dia_.'/05/2024';
// $date_ontem = $dia_.'/05/2024';
$date_ret = date('Y-m-d', strtotime('-9 days'));

$url = $link_prefixo.'/e-sitef/api/v1/transactions?start_date='.$data.'&end_date='.$data;
//$url = $link_prefixo.'/e-sitef/api/v1/transactions?start_date=07/05/2021&end_date=11/05/2021';

$metodo = 'GET';
$arquivo_text = $arquivo_text.$url."<br>";
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

// *
// *
// * FIM DA ÁREA RESPONSÁVEL POR CONSULTAR AS TRANSAÇÕES DO DIA ANTERIOR NA SOFTWARE EXPRESS
// *
// *

$arquivo_text = $arquivo_text."<pre>";
echo "<pre>";
// DEFININDO O NOME DA IMPORTAÇÃO
$importacao_nome = "INTEGRACAO_SE_".date('YmdHis');

// REGISTRANDO NO BANCO DE DADOS A IMPORTAÇÃO
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
	$arquivo_text = $arquivo_text."Importação Registrada com Sucesso.<br>";
	echo "Importação Registrada com Sucesso.<br>";
} else {
	$arquivo_text = $arquivo_text.'Error: ' . mysqli_error($con);
	echo 'Error: ' . mysqli_error($con);
}
echo "RESPOSTA API:<BR>";
print_r($response->resposta);
echo "<br><br><br>FIM RESPOSTA API<hr><br><br><br>";

$file = fopen("/var/www/html/integracao/softwareexpress/atualizacao/logs/ATUALIZADOR_".date('Y-m-d_His').".html","a");

// VERIFICANDO SE A CONSULTA FOI REALIZADA COM SUCESSO
if($response->resposta->code == 0){
	$arquivo_text = $arquivo_text."Consulta OK<br>";
	echo "Consulta OK<br>";
	
	$cont = 1;
	// PERCORRENDO AS TRANSAÇÕES
	foreach ($response->resposta->transactions as $transacao){
		$update_valores = "";
		// VERIFICANDO SE A TRANSAÇÃO É DO TIPO PAGAMENTO
		if($transacao->order_id > 100000 && $transacao->type == "P"){
			$transacao_valor = $transacao->amount / 100;
			$transacao_data = substr($transacao->creation_date, 6, 4)."-".substr($transacao->creation_date, 3, 2)."-".substr($transacao->creation_date, 0, 2)." ".substr($transacao->creation_date, 11, 2).":".substr($transacao->creation_date, 14, 2).":00";
			$transacao_mes = substr($transacao->creation_date, 3, 2)."/".substr($transacao->creation_date, 6, 4);
			$arquivo_text = $arquivo_text."authorizer_code: ".$transacao->authorizer_code." | ";
			echo "authorizer_code: ".$transacao->authorizer_code." | ";
			$arquivo_text = $arquivo_text."authorizer_message: ".$transacao->authorizer_message." | ";
			echo "authorizer_message: ".$transacao->authorizer_message." | ";
			$arquivo_text = $arquivo_text."status: ".$transacao->status." | ";
			echo "status: ".$transacao->status." | ";
			$arquivo_text = $arquivo_text."nit: ".$transacao->nit." | ";
			echo "nit: ".$transacao->nit." | ";
			$arquivo_text = $arquivo_text."order_id: ".$transacao->order_id." | ";
			echo "order_id: ".$transacao->order_id." | ";
			$arquivo_text = $arquivo_text."type: ".$transacao->type." | ";
			echo "type: ".$transacao->type." | ";
			$arquivo_text = $arquivo_text."<br>";
			echo "<br>";
			
			// VERIFICANDO SE A TRANSAÇÃO JÁ EXISTE NO BANCO DE DADOS			
			if($transacao->esitef_usn){$esitef_usn = $transacao->esitef_usn;}else{$esitef_usn = $transacao->payment->esitef_usn;}
			if($transacao->nit){$transacao_nit = $transacao->nit;}else{$transacao_nit = $transacao->payment->nit;}
			if($transacao->authorizer_code){$authorizer_code = $transacao->authorizer_code;}else{$authorizer_code = $transacao->payment->authorizer_code;}
			$transacao_ja_existe = 0;
			
			$sql_tsid = "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_tef WHERE transacao_nit = '".$transacao_nit."' OR (transacao_venda_id = '".$transacao->order_id."' AND transacao_data LIKE '%".$date_ontem."%');";
			$arquivo_text = $arquivo_text."query sql_tsid: ".$sql_tsid."<br>";
			echo "query sql_tsid: ".$sql_tsid."<br>";
			$result_tsid = mysqli_query($con, $sql_tsid) or die(mysql_error($con));
			$row_tsid = mysqli_fetch_assoc($result_tsid);
			echo "TOTAL tsid: ".$row_tsid['total']."<br>";
			if($row_tsid["total"]){
				$arquivo_text = $arquivo_text."Transação já existente - NIT: ".$transacao_nit."<br>";
				echo "Transação já existente - NIT: ".$transacao_nit."<br>";
				$transacao_ja_existe = 1;
			}
			// SE A TRANSAÇÃO NÃO EXISTIR NO BANCO DE DADOS
			if($transacao_ja_existe == 0){
				// Será verificado se a venda existe no banco de dados				
				$result_venda = mysqli_query($con, "SELECT vendas_id, 
				sys_vendas_seguros.cliente_cpf, 
				vendas_apolice, 
				apolice_nome, 
				vendas_status, 
				vendas_receita, 
				vendas_recebido_prolabore, 
				vendas_dia_ativacao, 
				vendas_banco, 
				vendas_telefone, 
				cliente_email,
				cliente_nome 
				FROM sys_vendas_seguros 
				INNER JOIN sys_vendas_apolices ON sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id 
				INNER JOIN sys_inss_clientes ON sys_vendas_seguros.cliente_cpf = sys_inss_clientes.cliente_cpf 
				WHERE vendas_id = '" . $transacao->order_id . "';")
				or die(mysqli_error($con));
				$row_venda = mysqli_fetch_array($result_venda);
				// SE A VENDA EXISTIR NO BANCO DE DADOS
				if($row_venda["vendas_id"]){
					$em_retentativa = 0;
					if($row_venda["vendas_status"] == 96){
						$result_data_ret = mysqli_query($con, "SELECT registro_data 
															FROM sys_vendas_registros_seg 
															WHERE vendas_id = '" . $transacao->order_id . "' 
															AND registro_status = 96 
															AND registro_data > '".$date_ret." 00:00:00' 
															ORDER BY registro_id DESC LIMIT 0, 1;")
						or die(mysqli_error($con));
						$row_data_ret = mysqli_fetch_array($result_data_ret);
						if(mysqli_num_rows($result_data_ret)){
							$em_retentativa = 1;
						}
					}
					
					if($em_retentativa == 1){
						$arquivo_text = $arquivo_text."Venda em retentativa! Última retentativa em ".$row_data_ret['registro_data'].".<br>";
						echo "Venda em retentativa! Última retentativa em ".$row_data_ret['registro_data'].".<br>";
					}else{
						$vendas_obs = "";
						$transacao_proposta = $row_venda["vendas_id"];
						
						// LÓGICA DE RETENTATIVA PRÓPRIA:
						// SE A TRANSAÇÃO FOR CONCLUIDA COM SUCESSO
						if($transacao->status == "CON"){
							$transacao_status = "CON";
						}else{
							$arquivo_text = $arquivo_text."SELECT COUNT(retorno_id) AS total FROM sys_vendas_transacoes_retorno WHERE retorno_retentativa != 'Não' AND retorno_codigo = '".$transacao->authorizer_code."';<br>";
							echo "SELECT COUNT(retorno_id) AS total FROM sys_vendas_transacoes_retorno WHERE retorno_retentativa != 'Não' AND retorno_codigo = '".$transacao->authorizer_code."';<br>";
							// SE A TRANSAÇÃO FOR CONCLUIDA COM ERRO E TIVER RETENTATIVA
							$result_retentatvas = mysqli_query($con, "SELECT COUNT(retorno_id) AS total FROM sys_vendas_transacoes_retorno WHERE retorno_retentativa != 'Não' AND retorno_codigo = '".$transacao->authorizer_code."';")
							or die(mysqli_error($con));
							$row_retentatvas = mysqli_fetch_array($result_retentatvas);
							// SE HOUVER REGISTRO DE RETENTATIVA
							if($row_retentatvas["total"]){
								$transacao_status = "RET";
								$arquivo_text = $arquivo_text."RET<BR>";
								echo "RET<BR>";
							}else{
								$transacao_status = "INV";
							}
						}
						
						$arquivo_text = $arquivo_text."Venda não em RET e sem transação cadastrada!<br>";
						echo "Venda não em RET e sem transação cadastrada!<br>";
						// SE A VENDA NÃO ESTIVER EM RETENTATIVA E NÃO TIVER TRANSAÇÃO CADASTRADA NO BANCO DE DAD
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
								transacao_esitef_usn,
								transacao_authorizer_message,
								transacao_status)
								VALUES (
								'".$transacao_nit."',
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
								'".$esitef_usn."',
								'".$transacao->authorizer_message."',
								'".$transacao_status."'
								);";
						$arquivo_text = $arquivo_text."insere_transaction_query: <br>".$insere_transaction_query;
						echo "insere_transaction_query: <br>".$insere_transaction_query;
						$result_transaction = mysqli_query($con, $insere_transaction_query);
						$transaction_id = mysqli_insert_id($con);
						
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
								'".$transacao_data."',
								'".$authorizer_code."',
								'".$transacao->status."',
								'".$esitef_usn."',
								'".json_encode($transacao)."'
								);";
						$arquivo_text = $arquivo_text."<br>insere_log_query: <br>".$insere_log_query;
						echo "<br>insere_log_query: <br>".$insere_log_query;
						$result_transaction = mysqli_query($con, $insere_log_query);
						$transaction_id = mysqli_insert_id($con);
						
						// ## IMPLEMENTAR O INSERT DO HISTORICO DA VENDA
						$status_nao_atualizar = Array('19', '58', '76', '77', '86', '87', '90', '91', '92');
						if(in_array($row_venda['vendas_status'], $status_nao_atualizar)){
							$update_status = "";
							$vendas_status = $row_venda['vendas_status'];
							$vendas_obs = "Stauts da Proposta ".$transacao_proposta." não atualizado, pois o status atual (".$row_venda['vendas_status'].") não permite! (19=CANCELADO, 58=ENVIADO PARA RETENCAO ou 76=CANCELADO INTERNO)";
							$arquivo_text = $arquivo_text."vendas_obs: ".$vendas_obs."<br>";
							echo "vendas_obs: ".$vendas_obs."<br>";
						}elseif($row_tsid["total"]){
							$update_status = "";
							$vendas_status = $row_venda['vendas_status'];
							$vendas_obs = "Stauts da Proposta ".$transacao_proposta." não atualizado, pois a venda já foi atualizada pelo fluxo de retentativas.";
							$arquivo_text = $arquivo_text."vendas_obs: ".$vendas_obs."<br>";
							echo "vendas_obs: ".$vendas_obs."<br>";
						}else{
							$arquivo_text = $arquivo_text."Venda em status de ATUALIZAR, sem transação cadastrada!<br>";
							echo "Venda em status de ATUALIZAR, sem transação cadastrada!<br>";
							$date1=date_create($row_venda['vendas_dia_ativacao']);
							$date2=date_create($transacao_data);
							$diff=date_diff($date1,$date2);
							
							if($diff->format("%a") > 27 && $diff->format("%a") < 31){
								$transacao_parcela = 2;
								$aux_parcela = 1;
							}else{
								$transacao_parcela = ($diff->format("%a") / 30);
								$aux_parcela = 0;
							}
							$pos = strrpos($transacao_parcela,'.');
							if($pos !== false){
							  $transacao_parcela = substr($transacao_parcela,0,$pos);
							}
							$transacao_tipo = "2";
							$transacao_data_id = date("YmdHms");
							$transacao_id = $row_venda['vendas_id']."_".$transacao_data_id."_".$transacao_parcela."_".$transacao_tipo;
							
							if($transacao->status == "CON"){
								$transacao_recebido = 1;
								
								if($row_venda['vendas_dia_ativacao'] >= "2021-05-01"){
									$result_pagas = mysqli_query($con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $row_venda['vendas_id'] . "' AND transacao_recebido = 1;")
									or die(mysqli_error($con));
									$row_pagas = mysqli_fetch_array($result_pagas);
									$total_parcelas = $transacao_parcela;
									$parcelas_pagas = $row_pagas["total"] + $aux_parcela;
									if($parcelas_pagas == $total_parcelas){
										$update_status = ", vendas_status='67'";
										$vendas_status = 67;
										$arquivo_text = $arquivo_text."Venda ativa OK. <br/>";
										echo "Venda ativa OK. <br/>";
									}elseif($parcelas_pagas > $total_parcelas){
										$update_status = ", vendas_status='94'";
										$vendas_status = 94;
										$vendas_obs = "Identificado pagamento de mais parcelas do que o tempo de vigência do plano! Total de Parcelas: ".$total_parcelas.". Pagas: ".$parcelas_pagas.". ";
										$arquivo_text = $arquivo_text."PARCELAS A MAIS. <br/>";
										echo "PARCELAS A MAIS. <br/>";
									}else{
										$update_status = ", vendas_status='101'"; //ORIG: ", vendas_status='88'"
										$vendas_status = 101; //ORIG: 88
										$total_parcelas++;
										$parcelas_pagas++;
										$vendas_obs = "Não pagamento de parcelas identificado! Total de Parcelas: ".$total_parcelas.". Pagas: ".$parcelas_pagas.". ";
										$arquivo_text = $arquivo_text."Inadimplencia de parcelas anteriores. <br/>";
										echo "Inadimplencia de parcelas anteriores. <br/>";
									}
								}else{
									$update_status = ", vendas_status='67'";
									$vendas_status = 67;
									$arquivo_text = $arquivo_text."Venda ativa OK. <br/>";
									echo "Venda ativa OK. <br/>";
								}
								
								//CONSULTA VALOR DA CMS DA PARCELA RECEBIDA:
								$result_cms = mysqli_query($con, "SELECT transacao_valor FROM sys_vendas_aplices_cms WHERE 
																	cms_apolices LIKE '%," . $row_venda['vendas_apolice'] . ",%' 
																	AND (cms_authorizers LIKE '%," . $transacao->authorizer_id . ",%' OR cms_authorizers = '0') 
																	ORDER BY cms_id ASC 
																	LIMIT 0,1;")
								or die(mysqli_error($con));
								$row_cms = mysqli_fetch_array($result_cms);
								
								$vendas_recebido_prolabore = $row_venda['vendas_recebido_prolabore'] + $row_cms['transacao_valor'];
								$vendas_receita = $row_venda['vendas_receita'] + $row_cms['transacao_valor'];
								$update_valores = ", vendas_recebido_prolabore='$vendas_recebido_prolabore', vendas_receita='$vendas_receita' ";
							}elseif($transacao_status == "RET"){
								$update_status = ", vendas_status='96'";
								$vendas_status = 96;
								$transacao_recebido = 2;
								$arquivo_text = $arquivo_text."Entrando EM RETENTATIVA. <br/>";
								echo "Entrando EM RETENTATIVA. <br/>";
							}else{
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
										$update_status = ", vendas_status='88'";
										$vendas_status = 88;
										$transacao_recebido = 2;
										$arquivo_text = $arquivo_text."Inadimplencia comum. <br/>";
										echo "Inadimplencia comum. <br/>";
									}else{
										$update_status = ", vendas_status='93'";
										$vendas_status = 93;
										$transacao_recebido = 2;
										$vendas_obs = "INADIMPLENCIA CRITICA identificada, pelo não recebimento das parcelas ".$transacao_parcela.", ".$transacao_parcela_menos1." e ".$transacao_parcela_menos2.". ";
										$arquivo_text = $arquivo_text."Inadimplencia critica. <br/>";
										echo"Inadimplencia critica. <br/>";
									}
								}else{
									$update_status = ", vendas_status='88'";
									$vendas_status = 88;
									$transacao_recebido = 2;
									$arquivo_text = $arquivo_text."Inadimplencia comum. <br/>";
									echo "Inadimplencia comum. <br/>";
								}
							}
							
							//INSERÇÃO DA TRANSAÇÃO - (PARCELA):
							if(!$row_cms['transacao_valor']){$row_cms['transacao_valor'] = $row_venda["vendas_valor"];}
							$vendas_recebido_novo = $row_cms['transacao_valor'];
							$transacao_id_venda = $row_venda['vendas_id'];
							
							$result_parecela_existente = mysqli_query($con, "SELECT transacao_id FROM sys_vendas_transacoes_seg WHERE 
																	transacao_id_venda = '" . $row_venda['vendas_id'] . "' 
																	AND transacao_parcela = '".$transacao_parcela."' 
																	AND transacao_recebido != 1;")
							or die(mysqli_error($con));
							$row_parecela_existente = mysqli_fetch_array($result_parecela_existente);
							//
							if($row_parecela_existente["transacao_id"]){
								$update_parcela_query ="UPDATE sys_vendas_transacoes_seg
								SET transacao_recebido = '$transacao_recebido', 
								transacao_valor = '".$row_cms['transacao_valor']."',
								transacao_motivo = '".$authorizer_code."', 
								transacao_data_importacao = NOW(), 
								transacao_data = '$transacao_data', 
								transacao_usuario = 'integrador.automatico' 
								WHERE transacao_id = '". $row_parecela_existente['transacao_id'] . "';";
								$result_transaction = mysqli_query($con, $update_parcela_query);
								
								$vendas_recebido_prolabore = $row_venda['vendas_recebido_prolabore'];
								$vendas_receita = $row_venda['vendas_receita'];
								$update_valores = "";
								
								$arquivo_text = $arquivo_text."<br>update_parcela_query: ".$update_parcela_query."<br>";
								echo "<br>update_parcela_query: ".$update_parcela_query."<br>";
							}else{
								$insere_transacoes_seg_query = "INSERT INTO `sistema`.`sys_vendas_transacoes_seg` (`transacao_id`, 
								`importacao_id`, 
								`transacao_id_venda`, 
								`transacao_proposta`, 
								`transacao_valor`, 
								`transacao_recebido`, 
								`transacao_motivo`,
								`transacao_data_importacao`, 
								`transacao_data`, 
								`transacao_usuario`, 
								`transacao_mes`, 
								`transacao_parcela`, 
								`transacao_tipo`) 
								VALUES ('$transacao_id',
								'$importacao_id',
								'$transacao_id_venda',
								'$transacao_proposta',
								'".$row_cms['transacao_valor']."',
								'$transacao_recebido',
								'".$authorizer_code."',
								NOW(),
								'$transacao_data',
								'integrador.automatico',
								'$transacao_mes',
								'$transacao_parcela',
								'$transacao_tipo');";
								$arquivo_text = $arquivo_text."<br>insere_transacoes_seg_query: <br>".$insere_transacoes_seg_query;
								echo "<br>insere_transacoes_seg_query: <br>".$insere_transacoes_seg_query;
								if (mysqli_query($con,$insere_transacoes_seg_query)){
									$arquivo_text = $arquivo_text."Transação <strong>".$transacao_id."</strong> Cadastrada com Sucesso, para a Proposta <strong>".$transacao_proposta."</strong>, Cod da venda: . <strong>".$transacao_id_venda."</strong></br>";
									echo "Transação <strong>".$transacao_id."</strong> Cadastrada com Sucesso, para a Proposta <strong>".$transacao_proposta."</strong>, Cod da venda: . <strong>".$transacao_id_venda."</strong></br>";
								} else {
									die('Error: ' . mysqli_error($con));
								}
							}
							$vendas_obs = $vendas_obs."Venda atualizada via Integrador Automatico. (status anterior: ".$row_venda['vendas_status'].", numero proposta: ".$transacao_proposta.")";
							
							//ALOCAÇÃO DE CLIENTES INADIMPLENTES EM CAMPANHA:
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
								echo "<br>".$acionamento_obs."<br>";
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
								echo "<br>CLIENTE ESPELHADO OK<br>";
								$query = mysqli_query($con,"UPDATE sys_inss_clientes
									SET 
									cliente_parecer = '100',
									cliente_usuario = '$username',
									cliente_alteracao = NOW()".
									$cliente_campanha_id_update.
									" WHERE cliente_cpf='$cliente_cpf';") or die(mysqli_error($con));
								$arquivo_text = $arquivo_text."Parecer Atualizado com Sucesso <br/>";
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
								NOW(),
								'".$row_menor_campanha['campanha_id']."',
								'94');";
								if (mysqli_query($con,$sql)){
									$acionamento_id = mysql_insert_id();
									$arquivo_text = $arquivo_text."Acionamento Registrado com Sucesso. </br>";
									echo "Acionamento Registrado com Sucesso. </br>";
								} else {
									die('Error: ' . mysqli_error($con));
								}
								mysqli_close($con);	
								include($path_includes.$Arquivo_conect); 
								
								// //CADASTRANDO NA FILA DE SMS DE COBRANÇA DO PORTAL APOBEM:
								// $secret_key = "u";
								// $data_link = array("code" => $secret_key."-".$cliente_cpf."-".$transacao_id_venda);
								// $data_string = json_encode($data_link);
								
								// // criptografia
								// $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));
								// $encrypted_data = openssl_encrypt($data_string, 'aes-128-cbc', $secret_key, 0, $iv);
								// $encrypted_data = base64_encode($encrypted_data . '::' . $iv);
								// $link = "https://www.apobem.com.br/portal/?schdl=1&" . http_build_query(array("data" => $encrypted_data));
								// $cliente_nome = explode(" ", $row_venda['cliente_nome']);
								// $notificacao_mensagem = "Olá ".$cliente_nome[0].", não conseguimos realizar a cobrança da sua mensalidade APOBEM no cartão de crédito. Regularize já através do link ".$link;
								
								// $sql = "INSERT INTO `sistema`.`sys_notificacoes_cobranca` (`notificacao_id`, 
								// `cliente_cpf`, 
								// `venda_id`, 
								// `parcela_id`, 
								// `transacao_id`, 
								// `notificacao_mensagem`,  
								// `notificacao_telefone`,
								// `notificacao_data`) 
								// VALUES (NULL, 
								// '$cliente_cpf',
								// '$transacao_id_venda',
								// '$transacao_id',
								// '$transaction_id',
								// '$notificacao_mensagem', 
								// '".$row_venda['vendas_telefone']."',
								// NOW());";
								// if (mysqli_query($con,$sql)){
								// 	$notificacao_id = mysql_insert_id();
								// 	$arquivo_text = $arquivo_text."Notificação Registrada an fila com Sucesso. </br>";
								// 	echo "Notificação Registrada an fila com Sucesso. </br>";
								// } else {
								// 	die('Error: ' . mysqli_error($con));
								// }
							}
							//FIM ALOCAÇÃO DE CLIENTES INADIMPLENTES EM CAMPANHA:
						}
						
						$query = mysqli_query($con, "UPDATE sys_vendas_seguros SET 
						vendas_alteracao = NOW(), vendas_user='integrador.automatico'".$update_status.$update_valores." 
						WHERE vendas_id='".$row_venda['vendas_id']."';") or die(mysqli_error($con));
						
						$arquivo_text = $arquivo_text."<br>update_venda_query: <br>UPDATE sys_vendas_seguros SET 
						vendas_alteracao = NOW(), vendas_user='integrador.automatico'".$update_status.$update_valores." 
						WHERE vendas_id='".$row_venda['vendas_id']."';";
						echo "<br>update_venda_query: <br>UPDATE sys_vendas_seguros SET 
						vendas_alteracao = NOW(), vendas_user='integrador.automatico'".$update_status.$update_valores." 
						WHERE vendas_id='".$row_venda['vendas_id']."';";
						
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
						$arquivo_text = $arquivo_text."<br>insere_log_query: <br>".$insere_registro_venda_query;
						echo "<br>insere_log_query: <br>".$insere_registro_venda_query;
						if (mysqli_query($con, $insere_registro_venda_query)){
							$arquivo_text = $arquivo_text."Histórico Registrado com Sucesso.<br>";
							echo "Histórico Registrado com Sucesso.<br>";
						} else {
							$arquivo_text = $arquivo_text.'Error: ' . mysqli_error($con);
							echo 'Error: ' . mysqli_error($con);
						}
					}
				}	
			}
			$arquivo_text = $arquivo_text."<br>";
			echo "<br><br><br><hr><br><br><br>";
			$cont++;
		}
	}
}

$remetente_email = "acionamento@grupofortune.com.br";
$remetente_senha = "Suporte2015";

if($response->resposta->code){$log_erro = "ERRO DE EXECUÇÃO: ".$response->resposta->code." ";}
$de = $remetente_email;
$de_nome = "Integrador Automático";
$para = "financeiro.seguros@grupofortune.com.br";
$assunto = $log_erro."Integrador. ".$cont." Transações processadas!";
$data_execucao = date('d/m/Y H:i:s');
$corpo = "Registro de execução do Intergrador automático (consulta_schedule).<br>".
		 "ID da importação: ".$importacao_id."<br>".
		 "Nome da importação: ".$importacao_nome."<br>".
		 "Data da execução: ".$data_execucao."<br>".
		 "Data consultada: ".$data."<br>".
		 "URL da consulta: ".$url."<br>".
		 "Transações: ".$cont."<br>".$log_erro;

//define('GUSER', $remetente_email);	// <-- Insira aqui o seu GMail
//define('GPWD', $remetente_senha);		// <-- Insira aqui a senha do seu GMail

//require_once("sistema/utils/phpmailer/class.phpmailer.php");
//function smtpmailer($para, $de, $de_nome, $assunto, $corpo)
//{ 
//	global $error;
//	$mail = new PHPMailer();
//	$mail->CharSet = 'UTF-8';
//	$mail->IsSMTP();		// Ativar SMTP
//	$mail->SMTPDebug = 0;		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
//	$mail->SMTPAuth = true;		// Autenticação ativada
//	$mail->SMTPSecure = 'ssl';	// SSL REQUERIDO pelo GMail
//	$mail->Host = 'smtp.gmail.com';	// SMTP utilizado
//	$mail->Port = 465;  		// A porta 465 deverá estar aberta em seu servidor (outra porta possível *587*)
//	$mail->Username = GUSER;
//	$mail->Password = GPWD;	
//	$mail->SetFrom($de, $de_nome);
//	$mail->AddReplyTo( $remetente_email, 'Grupo Fortune');
//	$mail->Subject = $assunto;
//	$mail->Body = $corpo;
//	$mail->IsHTML(true); // Descomentar caso o email enviado seja escrito em html	
	//$mail->AddCC($value);
//	$mail->AddAddress($para);
//

//	if(!$mail->Send()) {
//		$error = 'Mail error: '.$mail->ErrorInfo; 
//		return false;
//	} else {
//		$error = 'sucess';
//		return true;
//	}
//}
//if ( smtpmailer($para, $de, $de_nome, $assunto, $corpo) )
//{	
//	$arquivo_text = $arquivo_text."<br>Notificação de email enviada.<br>";
//}

if (!empty($error)){ $arquivo_text = $arquivo_text.$error; }
$arquivo_text = $arquivo_text."</pre>";
echo "</pre>";
fwrite($file, $arquivo_text);
fclose($file);
?>