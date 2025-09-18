<?php
header("Access-Control-Allow-Origin: *");
include("variaveis_fixas.php");

// $nit = "d5d62749d3d7b0a524282618cf3011a58858926810e7b2370ef6e573298844fa";
// $status = "";

$nit = $_POST['nit'];
$nit_api = $_POST['nit_api'];
$atualizar = $_POST['atualizar'];
$user = $_POST["user"];


$Arquivo_conect = "../connect_seguro.php";

include($Arquivo_conect);

$cpf = $_POST['cpf'];
$json = $_POST['json'];

$url = $link_prefixo.'/e-sitef/api/v1/transactions/'.$nit;
$metodo = 'GET';
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

	$resposta = $response->resposta->payment;
	$consulta = mysqli_query($con, "SELECT * FROM `sys_vendas_transacoes_retorno` WHERE `retorno_codigo` = $resposta->authorizer_code;") or die(mysql_error());
	$row_consulta = mysqli_fetch_array( $consulta );

	$ok = "<svg xmlns='http://www.w3.org/2000/svg' height='20px' viewBox='0 0 24 24' width='20px' fill='#378805'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M9 16.2l-3.5-3.5c-.39-.39-1.01-.39-1.4 0-.39.39-.39 1.01 0 1.4l4.19 4.19c.39.39 1.02.39 1.41 0L20.3 7.7c.39-.39.39-1.01 0-1.4-.39-.39-1.01-.39-1.4 0L9 16.2z'/></svg>";
    $nok = "<svg xmlns='http://www.w3.org/2000/svg' height='20px' viewBox='0 0 24 24' width='20px' fill='#c41b1b'><path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm6.31-3.1L7.1 5.69C8.45 4.63 10.15 4 12 4c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z'/></svg>";
	$warning = "<svg xmlns='http://www.w3.org/2000/svg' height='20px' viewBox='0 0 24 24' width='20px' fill='#565454'><path d='M4.47 21h15.06c1.54 0 2.5-1.67 1.73-3L13.73 4.99c-.77-1.33-2.69-1.33-3.46 0L2.74 18c-.77 1.33.19 3 1.73 3zM12 14c-.55 0-1-.45-1-1v-2c0-.55.45-1 1-1s1 .45 1 1v2c0 .55-.45 1-1 1zm1 4h-2v-2h2v2z'/></svg>";
	$calendar = "<svg xmlns='http://www.w3.org/2000/svg' enable-background='new 0 0 24 24' height='14px' viewBox='0 0 24 24' width='14px' fill='#757575'><g><rect fill='none' height='24' width='24'/><rect fill='none' height='24' width='24'/></g><g><path d='M17,2c-0.55,0-1,0.45-1,1v1H8V3c0-0.55-0.45-1-1-1S6,2.45,6,3v1H5C3.89,4,3.01,4.9,3.01,6L3,20c0,1.1,0.89,2,2,2h14 c1.1,0,2-0.9,2-2V6c0-1.1-0.9-2-2-2h-1V3C18,2.45,17.55,2,17,2z M19,20H5V10h14V20z M11,13c0-0.55,0.45-1,1-1s1,0.45,1,1 s-0.45,1-1,1S11,13.55,11,13z M7,13c0-0.55,0.45-1,1-1s1,0.45,1,1s-0.45,1-1,1S7,13.55,7,13z M15,13c0-0.55,0.45-1,1-1s1,0.45,1,1 s-0.45,1-1,1S15,13.55,15,13z M11,17c0-0.55,0.45-1,1-1s1,0.45,1,1s-0.45,1-1,1S11,17.55,11,17z M7,17c0-0.55,0.45-1,1-1 s1,0.45,1,1s-0.45,1-1,1S7,17.55,7,17z M15,17c0-0.55,0.45-1,1-1s1,0.45,1,1s-0.45,1-1,1S15,17.55,15,17z'/></g></svg>";
	$hour = "<svg xmlns='http://www.w3.org/2000/svg' height='14px' viewBox='0 0 24 24' width='14px' fill='#757575'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-.22-13h-.06c-.4 0-.72.32-.72.72v4.72c0 .35.18.68.49.86l4.15 2.49c.34.2.78.1.98-.24.21-.34.1-.79-.25-.99l-3.87-2.3V7.72c0-.4-.32-.72-.72-.72z'/></svg>";
	$refresh = "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#000000'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z'/></svg>";

	// echo "<textarea>".json_encode($response->resposta)."</textarea>" ;
?>

<div>
	<?php if($resposta->status == 'CON'): ?>
	<?php
		$status_bd = mysqli_query($con, "SELECT `transacao_status` FROM `sys_vendas_transacoes_tef` WHERE `transacao_nit` = '$resposta->nit';") or die(mysql_error());
		$row_status_bd = mysqli_fetch_array( $status_bd );
	?>
	<?php if($row_status_bd['transacao_status'] == "RCON" || $row_status_bd['transacao_status'] == "RET" || $row_status_bd['transacao_status'] == "RINV"): ?>
	<div class="card-grey">
	<?php else: ?>
	<div class='card-green'>
	<?php endif; ?>
	<?php
		$data_hora_transacao = mysqli_query($con, "SELECT `transacao_data` FROM `sys_vendas_transacoes_tef` WHERE `transacao_nit` = '$resposta->nit';") or die(mysql_error());
		$row_data_hora = mysqli_fetch_array( $data_hora_transacao );
		if($row_data_hora['transacao_data'] != ""){
			$data_d_h = explode(" ", $row_data_hora['transacao_data']);
			$data_d = new DateTime($data_d_h[0]) ;
			$data = $data_d->format('d/m/Y');
			$hora = $data_d_h[1];
			$res_data =  " | ".$calendar." ".$data." | ".$hour." ".$hora;
		}else{
			$res_data = " | Data e hora indisponíveis";
		}
	?>
		<p class="p-V2 p-destaque">Pedido: <?php echo $resposta->order_id; ?> | Valor : <?php 
																				$amount1 = substr($response->resposta->schedule->amount, 0, -2) ;
																				$amount2 = substr($response->resposta->schedule->amount, -2) ;
																				echo "R$ ".$amount1.",".$amount2;
																				?> | Status: <?php echo $resposta->status; ?><?php echo $res_data; ?>
		</p>		
		<p class="p-V2">Cód. de Autorização: <?php echo $resposta->authorizer_code; ?>-<?php echo $row_consulta['retorno_definicao']; ?></p>
		<?php if($atualizar == 'sim'):?>
			<div style="width: 100%; display: flex; align-items: center; justify-content: space-between">
				<p class="p-V2 p-destaque-aviso">Validação dos dados: Inconsistência dos dados.</p>				
				
					<?php
				$nit = $resposta->nit;
				$cpf = $cpf;
				$valor = $response->resposta->schedule->amount;
				$order_id = $resposta->order_id;
				$authorizer_date =  $resposta->authorizer_date;
				$authorizer_id = $resposta->authorizer_id;
				$esitef_usn = $resposta->esitef_usn;
				$authorizer_message = $resposta->authorizer_message;
				$status = $resposta->status;
				$json = $json		
				?>
				<?php if($row_status_bd['transacao_status'] != "RCON" || $row_status_bd['transacao_status'] != "RET" || $row_status_bd['transacao_status'] != "RINV"): ?>			
				<div class="atualizar-warning" onclick="atualizarTransacao('<?php echo $nit; ?>','<?php echo $cpf; ?>','<?php echo $valor ?>','<?php echo $order_id; ?>','<?php echo $authorizer_date; ?>','<?php echo $authorizer_id; ?>','<?php echo $esitef_usn; ?>','<?php echo $authorizer_message; ?>','<?php echo $status; ?>')"><?php echo $warning; ?>Atualizar</div>
				<?php else: ?>
					<p class="p-V2 p-destaque-aviso">EM RETENTAIVA <?php echo $refresh; ?></p>
				<?php endif; ?>
			</div>
			
		<?php elseif($atualizar == 'não'): ?>
			
			<p class="p-V2 p-destaque-aviso">Validação dos dados: Dados Atualizados <?php echo $resposta->nit; ?><?php echo $ok; ?></p>
		<?php endif; ?>
	</div>
	<?php elseif($resposta->status == 'NEG'): ?>
		<?php
		$status_bd = mysqli_query($con, "SELECT `transacao_status` FROM `sys_vendas_transacoes_tef` WHERE `transacao_nit` = '$resposta->nit';") or die(mysql_error());
		$row_status_bd = mysqli_fetch_array( $status_bd ); 
	?>
	<?php if($row_status_bd['transacao_status'] == "RCON" || $row_status_bd['transacao_status'] == "RET" || $row_status_bd['transacao_status'] == "RINV"): ?>
	<div class="card-grey">
	<?php else: ?>
		<div class='card-red'>
	<?php endif; ?>
		
		<?php
			$data_hora_transacao = mysqli_query($con, "SELECT `transacao_data` FROM `sys_vendas_transacoes_tef` WHERE `transacao_nit` = '$resposta->nit';") or die(mysql_error());
			$row_data_hora = mysqli_fetch_array( $data_hora_transacao );
			
			if($row_data_hora['transacao_data'] != ""){
				$data_d_h = explode(" ", $row_data_hora['transacao_data']);
				$data_d = new DateTime($data_d_h[0]) ;
				$data = $data_d->format('d/m/Y');
				$hora = $data_d_h[1];
				$res_data =  " | ".$calendar." ".$data." | ".$hour." ".$hora;
			}else{
				$res_data = " | Data e hora indisponíveis";
			}			
		
		?>
		
		<p class="p-V2 p-destaque">Pedido: <?php echo $resposta->order_id; ?> | Valor : <?php 
																				$amount1 = substr($response->resposta->schedule->amount, 0, -2) ;
																				$amount2 = substr($response->resposta->schedule->amount, -2) ;
																				echo "R$ ".$amount1.",".$amount2;
																				?> | Status: <?php echo $resposta->status; ?><?php echo $res_data; ?>
		</p>		
		<p class="p-V2">Cód. de Autorização: <?php echo $resposta->authorizer_code; ?>-<?php echo $row_consulta['retorno_definicao']; ?></p>
		<?php if($atualizar == 'sim'):?>
			<div style="width: 100%; display: flex; align-items: center; justify-content: space-between">
				<p class="p-V2 p-destaque-aviso">Validação dos dados: Inconsistência dos dados.</p>
				<?php
				$nit = $resposta->nit;
				$cpf = $cpf;
				$valor = $response->resposta->schedule->amount;
				$order_id = $resposta->order_id;
				$authorizer_date =  $resposta->authorizer_date;
				$authorizer_id = $resposta->authorizer_id;
				$esitef_usn = $resposta->esitef_usn;
				$authorizer_message = $resposta->authorizer_message;
				$status = $resposta->status;
				$json = json_encode($json);			
				?>				
				<?php if($row_status_bd['transacao_status'] != "RCON" || $row_status_bd['transacao_status'] != "RET" || $row_status_bd['transacao_status'] != "RINV"): ?>			
				<div class="atualizar-warning" onclick="atualizarTransacao('<?php echo $nit; ?>','<?php echo $cpf; ?>','<?php echo $valor ?>','<?php echo $order_id; ?>','<?php echo $authorizer_date; ?>','<?php echo $authorizer_id; ?>','<?php echo $esitef_usn; ?>','<?php echo $authorizer_message; ?>','<?php echo $status; ?>')"><?php echo $warning; ?>Atualizar</div>
				<?php else: ?>
					<p class="p-V2 p-destaque-aviso">EM RETENTAIVA <?php echo $refresh; ?></p>
				<?php endif; ?>
			</div>
				
		<?php elseif($atualizar == 'não'): ?>
			<p class="p-V2 p-destaque-aviso">Validação dos dados: Dados Atualizados <?php echo $ok; ?><?php echo $resposta->nit; ?></p>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>

<script>

	function atualizarTransacao(nit, cpf, valor, order_id, authorizer_date, authorizer_id, esitef_usn, authorizer_message, status){
		jQuery.ajax({
			type: "POST",
			url: "https://www.grupofortune.com.br/integracao/softwareexpress/atualiza_card_transacao.php",
			data: {
				nit: nit,
				cpf: cpf,
				valor: valor,
				order_id: order_id,
				authorizer_date: authorizer_date,
				authorizer_id: authorizer_id,
				esitef_usn: esitef_usn,
				authorizer_message: authorizer_message,
				status: status,
				json: json
			},
			success: function(res){
				console.log(res);
				jQuery(".cortina-modal").removeClass("cortina-modal-show");
                jQuery(".modal-atualizador").removeClass("modal-atualizador-show");
			}
		});
		
	}	

</script>

