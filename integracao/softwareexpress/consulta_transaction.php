<?php
header("Access-Control-Allow-Origin: *");
include("variaveis_fixas.php");


// $nit = "d5d62749d3d7b0a524282618cf3011a58858926810e7b2370ef6e573298844fa";
// $status = "";
$id = $_POST['id'];
$transacao_id = $_POST['nit'];
$status = $_POST["status"];
$authTipo = $_POST["auth_id"];
$user = $_POST["user"];

$Arquivo_conect = "../connect_seguro.php";

include($Arquivo_conect);



$url = $link_prefixo . '/e-sitef/api/v1/transactions/' . $transacao_id;
$metodo = 'GET';
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

$ret = false;
$payment_val = false;
$ticket = false;

if ($response->resposta->code == "3") {
	echo "Código: " . $response->resposta->code . "<br>";
	echo "Mensagem: " . $response->resposta->message;
	exit;
}


if ($status == "CON" && $response->resposta->payment == null) {
	$retorno = $response->resposta->capture;
	$tipo_status = "capturado";
	$tipo_transacao = "capture";
} elseif ($status == "NEG" && $response->resposta->payment == null) {
	$retorno = $response->resposta->pre_authorization;
	$tipo_status = "pre_autorizacao";
	$tipo_transacao = "pre_authorization";
} elseif ($status == "INV" && $response->resposta->payment == null) {
	$retorno = $response->resposta->pre_authorization;
	$tipo_status = "pre_autorizacao";
	$tipo_transacao = "pre_authorization";
} elseif ($status == "EST" && $response->resposta->payment == null) {
	$retorno = $response->resposta->pre_authorization;
	$tipo_status = "pre_autorizacao";
	$tipo_transacao = "pre_authorization";
} elseif ($status == "RET" && $response->resposta->payment == null) {
	$retorno = $response->resposta;
	$ret = true;
} elseif ($response->resposta->payment != null) {
	$retorno = $response->resposta->payment;
	$payment_val = true;
	$ret = true;
}

$retorno_transacao_con = explode("\n", $retorno->customer_receipt);
$retorno_transacao_con_2 = explode("\n", $retorno->merchant_receipt);
if ($ret == false) {
	$consulta = mysqli_query($con, "SELECT * FROM `sys_vendas_transacoes_retorno` WHERE `retorno_codigo` = $retorno->authorizer_code;") or die(mysql_error());
	$row_consulta = mysqli_fetch_array($consulta);
	$calendar = "<svg xmlns='http://www.w3.org/2000/svg' enable-background='new 0 0 24 24' height='24px' viewBox='0 0 24 24' width='24px' fill='#757575'><g><rect fill='none' height='24' width='24'/><rect fill='none' height='24' width='24'/></g><g><path d='M17,2c-0.55,0-1,0.45-1,1v1H8V3c0-0.55-0.45-1-1-1S6,2.45,6,3v1H5C3.89,4,3.01,4.9,3.01,6L3,20c0,1.1,0.89,2,2,2h14 c1.1,0,2-0.9,2-2V6c0-1.1-0.9-2-2-2h-1V3C18,2.45,17.55,2,17,2z M19,20H5V10h14V20z M11,13c0-0.55,0.45-1,1-1s1,0.45,1,1 s-0.45,1-1,1S11,13.55,11,13z M7,13c0-0.55,0.45-1,1-1s1,0.45,1,1s-0.45,1-1,1S7,13.55,7,13z M15,13c0-0.55,0.45-1,1-1s1,0.45,1,1 s-0.45,1-1,1S15,13.55,15,13z M11,17c0-0.55,0.45-1,1-1s1,0.45,1,1s-0.45,1-1,1S11,17.55,11,17z M7,17c0-0.55,0.45-1,1-1 s1,0.45,1,1s-0.45,1-1,1S7,17.55,7,17z M15,17c0-0.55,0.45-1,1-1s1,0.45,1,1s-0.45,1-1,1S15,17.55,15,17z'/></g></svg>";
	$hour = "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#757575'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-.22-13h-.06c-.4 0-.72.32-.72.72v4.72c0 .35.18.68.49.86l4.15 2.49c.34.2.78.1.98-.24.21-.34.1-.79-.25-.99l-3.87-2.3V7.72c0-.4-.32-.72-.72-.72z'/></svg>";
}

$ok = "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#378805'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M9 16.2l-3.5-3.5c-.39-.39-1.01-.39-1.4 0-.39.39-.39 1.01 0 1.4l4.19 4.19c.39.39 1.02.39 1.41 0L20.3 7.7c.39-.39.39-1.01 0-1.4-.39-.39-1.01-.39-1.4 0L9 16.2z'/></svg>";
$nok = "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#c41b1b'><path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm6.31-3.1L7.1 5.69C8.45 4.63 10.15 4 12 4c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z'/></svg>";
$copy = "<svg xmlns='http://www.w3.org/2000/svg' height='18px' viewBox='0 0 24 24' width='24px' fill='grey'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z'/></svg>";
$left = "<svg xmlns='http://www.w3.org/2000/svg' height='48' width='48'><path d='m26.95 34.9-9.9-9.9q-.25-.25-.35-.5-.1-.25-.1-.55 0-.3.1-.55.1-.25.35-.5L27 12.95q.45-.45 1.075-.45t1.075.45q.45.45.425 1.1-.025.65-.475 1.1l-8.8 8.8 8.85 8.85q.45.45.45 1.05 0 .6-.45 1.05-.45.45-1.1.45-.65 0-1.1-.45Z'/></svg>";
$right = "<svg xmlns='http://www.w3.org/2000/svg' height='48' width='48'><path d='M17.7 34.9q-.4-.5-.425-1.1-.025-.6.425-1.05l8.8-8.8-8.85-8.85q-.4-.4-.375-1.075.025-.675.425-1.075.5-.5 1.075-.475.575.025 1.025.475l9.95 9.95q.25.25.35.5.1.25.1.55 0 .3-.1.55-.1.25-.35.5l-9.9 9.9q-.45.45-1.05.425-.6-.025-1.1-.425Z'/></svg>";
$card_off = "<svg xmlns='http://www.w3.org/2000/svg' enable-background='new 0 0 20 20' height='60px' viewBox='0 0 20 20' width='60px' fill='tomato'><rect fill='none' height='20' width='20'/><path d='M17.78,17.78L2.22,2.22L1.16,3.28L2.38,4.5C2.15,4.77,2,5.12,2,5.5v9C2,15.33,2.67,16,3.5,16 h10.38l2.84,2.84L17.78,17.78z M4.88,7H3.5V5.62L4.88,7z M3.5,14.5V10h4.38l4.5,4.5H3.5z M6.12,4H16.5C17.33,4,18,4.67,18,5.5v9 c0,0.38-0.15,0.73-0.38,1l-1.12-1.12V10h-4.38l-3-3h7.38V5.5H7.62L6.12,4z' enable-background='new'/></svg>";

$log = mysqli_query($con, "SELECT `response_json` FROM `sys_vendas_transacoes_tef_log` WHERE `transacao_id` = $id ORDER BY `log_id` DESC LIMIT 0, 1;") or die(mysql_error());
$row_log = mysqli_fetch_array($log);

$retorno_bd = json_decode($row_log['response_json']);
$retorno_api = $response->resposta;

// echo "Consulta Transaction<br>";
// foreach($retorno_api->schedule->nits as $key => $value){
// 	$nits = mysqli_query($con, "SELECT `transacao_nit` FROM `sys_vendas_transacoes_tef` WHERE `transacao_nit` = '".$value."';") or die(mysql_error());
// 	$row_nits = mysqli_fetch_array( $nits );
// 	if($row_nits['transacao_nit'] == $value){
// 		echo "NIT: ".$value." - <b>Existe transação no banco de dados</b><br>";
// 	}else{
// 		echo "NIT: ".$value." - <b>Não existe transação no banco de dados</b><br>";
// 	}
// }
// $busca_campos = mysqli_query($con, "SELECT * FROM `sys_vendas_transacoes_campos` WHERE `tipo` = '$tipo_status';") or die(mysql_error());
// echo "TIPO: ".$tipo_status."<br>";

// while($row_campos = mysqli_fetch_array( $busca_campos )){	
// 	if($retorno_api->$tipo_transacao->$row_campos['nm_api'] == $retorno_bd->$tipo_transacao->$row_campos['nm_api']){
// 		echo "<p style='font-size: 12px'>".$row_campos['nm_api']. "( ".$retorno_api->$tipo_transacao->$row_campos['nm_api']." ) <b> IGUAIS</b></p>";
// 	}else{
// 		echo "<p style='font-size: 12px;color: red'>".$row_campos['nm_api']." <b>DIFERENTES</b></p>";
// 	}
// }
if ($user == "1") {
	// echo "<pre>";
	// print_r($retorno_bd);
	// echo "</pre>";
}
?>
<div style="display: flex; padding: 15px">
	<div class="modal-left">
		<h2>Atualização da transação</h2>
		<?php if ($payment_val === true && $ret === true): ?>
			<label for="">Mensagem</label>
			<p><?php echo $retorno->authorizer_message; ?></p>
			<label for="">Status</label>
			<p><?php echo $retorno->status; ?></p>
			<label for="">NIT</label>
			<input style="height: 0; border: none" id="nit" type="text" name="" value="<?php echo $retorno->nit; ?>">
			<p style="font-size: 10px; display: flex; align-items:center"><?php echo $retorno->nit; ?> <span id="copiar_nit" onclick="copiarTexto()" style="cursor: pointer"><?php echo $copy; ?></span><span style="color: green;" id="copiado"></span></p>
			<label for="">Order ID</label>
			<p><?php echo $retorno->order_id; ?></p>
			<label for="">Adquirente ID</label>
			<p><?php echo $retorno->acquirer_id; ?></p>
			<label for="">Nome do adquirente</label>
			<p><?php echo $retorno->acquirer_name; ?></p>
			<label for="">Status</label>
			<p style="display: flex; align-items: center; margin-top: 0">
				<?php
				if ($retorno->status == "CON") {
					echo "CONFIRMADO <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#378805'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M9 16.2l-3.5-3.5c-.39-.39-1.01-.39-1.4 0-.39.39-.39 1.01 0 1.4l4.19 4.19c.39.39 1.02.39 1.41 0L20.3 7.7c.39-.39.39-1.01 0-1.4-.39-.39-1.01-.39-1.4 0L9 16.2z'/></svg>";
				} elseif ($retorno->status == "NEG") {
					echo "NEGADO  <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#c41b1b'><path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm6.31-3.1L7.1 5.69C8.45 4.63 10.15 4 12 4c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z'/></svg>";
				} elseif ($retorno->status == "INV") {
					echo "INVÁLIDO <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='orange'><path d='M12 5.99L19.53 19H4.47L12 5.99M2.74 18c-.77 1.33.19 3 1.73 3h15.06c1.54 0 2.5-1.67 1.73-3L13.73 4.99c-.77-1.33-2.69-1.33-3.46 0L2.74 18zM11 11v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zm0 5h2v2h-2z'/></svg>";
				} elseif ($retorno->status == "EST") {
					echo "ESTORNADO <svg xmlns='http://www.w3.org/2000/svg' enable-background='new 0 0 24 24' height='24px' viewBox='0 0 24 24' width='24px' fill='blue'><rect fill='none' height='24' width='24'/><path d='M16.95,10.23l-5.66,5.66c-0.39,0.39-1.02,0.39-1.41,0l-2.83-2.83c-0.39-0.39-0.39-1.02,0-1.41l0,0 c0.39-0.39,1.02-0.39,1.41,0l2.12,2.12l4.95-4.95c0.39-0.39,1.02-0.39,1.41,0l0,0C17.34,9.21,17.34,9.84,16.95,10.23z M4,12 c0-2.33,1.02-4.42,2.62-5.88l1.53,1.53C8.46,7.96,9,7.74,9,7.29V3c0-0.28-0.22-0.5-0.5-0.5H4.21c-0.45,0-0.67,0.54-0.35,0.85 L5.2,4.7C3.24,6.52,2,9.11,2,12c0,4.75,3.32,8.73,7.76,9.75c0.63,0.14,1.24-0.33,1.24-0.98v0c0-0.47-0.33-0.87-0.79-0.98 C6.66,18.98,4,15.8,4,12z M22,12c0-4.75-3.32-8.73-7.76-9.75C13.61,2.11,13,2.58,13,3.23v0c0,0.47,0.33,0.87,0.79,0.98 C17.34,5.02,20,8.2,20,12c0,2.33-1.02,4.42-2.62,5.88l-1.53-1.53C15.54,16.04,15,16.26,15,16.71V21c0,0.28,0.22,0.5,0.5,0.5h4.29 c0.45,0,0.67-0.54,0.35-0.85L18.8,19.3C20.76,17.48,22,14.89,22,12z'/></svg>";
				}
				?>
			</p>
		<?php endif; ?>
		<?php if ($ret === true && $payment_val === false): ?>
			<label for="">Mensagem</label>
			<p><?php echo $retorno->payment->authorizer_message; ?></p>
			<label for="">Status</label>
			<p><?php echo $retorno->payment->status; ?></p>
		<?php elseif ($payment_val === false): ?>
			<label for="">Mensagem</label>
			<p><?php echo $retorno->authorizer_message; ?></p>
			<label for="">NIT</label>
			<input style="height: 0; border: none" id="nit" type="text" name="" value="<?php echo $retorno->nit; ?>">
			<p style="font-size: 10px; display: flex; align-items:center"><?php echo $retorno->nit; ?> <span id="copiar_nit" onclick="copiarTexto()" style="cursor: pointer"><?php echo $copy; ?></span><span style="color: green;" id="copiado"></span></p>
			<label for="">Order ID</label>
			<p><?php echo $retorno->order_id; ?></p>
			<label for="">Adquirente ID</label>
			<p><?php echo $retorno->acquirer_id; ?></p>
			<label for="">Nome do adquirente</label>
			<p><?php echo $retorno->acquirer_name; ?></p>
			<label for="">Status</label>
			<p style="display: flex; align-items: center; margin-top: 0">
				<?php
				if ($retorno->status == "CON") {
					echo "CONFIRMADO <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#378805'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M9 16.2l-3.5-3.5c-.39-.39-1.01-.39-1.4 0-.39.39-.39 1.01 0 1.4l4.19 4.19c.39.39 1.02.39 1.41 0L20.3 7.7c.39-.39.39-1.01 0-1.4-.39-.39-1.01-.39-1.4 0L9 16.2z'/></svg>";
				} elseif ($retorno->status == "NEG") {
					echo "NEGADO  <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#c41b1b'><path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm6.31-3.1L7.1 5.69C8.45 4.63 10.15 4 12 4c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z'/></svg>";
				} elseif ($retorno->status == "INV") {
					echo "INVÁLIDO <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='orange'><path d='M12 5.99L19.53 19H4.47L12 5.99M2.74 18c-.77 1.33.19 3 1.73 3h15.06c1.54 0 2.5-1.67 1.73-3L13.73 4.99c-.77-1.33-2.69-1.33-3.46 0L2.74 18zM11 11v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zm0 5h2v2h-2z'/></svg>";
				} elseif ($retorno->status == "EST") {
					echo "ESTORNADO <svg xmlns='http://www.w3.org/2000/svg' enable-background='new 0 0 24 24' height='24px' viewBox='0 0 24 24' width='24px' fill='blue'><rect fill='none' height='24' width='24'/><path d='M16.95,10.23l-5.66,5.66c-0.39,0.39-1.02,0.39-1.41,0l-2.83-2.83c-0.39-0.39-0.39-1.02,0-1.41l0,0 c0.39-0.39,1.02-0.39,1.41,0l2.12,2.12l4.95-4.95c0.39-0.39,1.02-0.39,1.41,0l0,0C17.34,9.21,17.34,9.84,16.95,10.23z M4,12 c0-2.33,1.02-4.42,2.62-5.88l1.53,1.53C8.46,7.96,9,7.74,9,7.29V3c0-0.28-0.22-0.5-0.5-0.5H4.21c-0.45,0-0.67,0.54-0.35,0.85 L5.2,4.7C3.24,6.52,2,9.11,2,12c0,4.75,3.32,8.73,7.76,9.75c0.63,0.14,1.24-0.33,1.24-0.98v0c0-0.47-0.33-0.87-0.79-0.98 C6.66,18.98,4,15.8,4,12z M22,12c0-4.75-3.32-8.73-7.76-9.75C13.61,2.11,13,2.58,13,3.23v0c0,0.47,0.33,0.87,0.79,0.98 C17.34,5.02,20,8.2,20,12c0,2.33-1.02,4.42-2.62,5.88l-1.53-1.53C15.54,16.04,15,16.26,15,16.71V21c0,0.28,0.22,0.5,0.5,0.5h4.29 c0.45,0,0.67-0.54,0.35-0.85L18.8,19.3C20.76,17.48,22,14.89,22,12z'/></svg>";
				}
				?>
			</p>
			<?php if ($retorno->status == "CON"): ?>
				<label for="">Data da Autorização</label>
				<?php $date = explode("T", $retorno->authorizer_date); ?>
				<p style="display: flex; align-items: center"><?php echo $calendar . " " . $date[0] . "  " . $hour . " " . $date[1]; ?></p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<div class="modal-right">
		<h2>Resultado</h2>
		<?php if ($retorno->status == "NEG" && $payment_val === false): ?>
			<label for="">Motivo</label>
			<p>(<?php echo $retorno->authorizer_code; ?>) - <?php echo $row_consulta['retorno_definicao']; ?></p>
			<label for="">Significado</label>
			<p><?php echo $row_consulta['retorno_significado']; ?></p>
			<label for="">Ação</label>
			<p><?php echo $row_consulta['retorno_acao']; ?></p>
			<label for="">Permite Retentativa</label>
			<p><?php echo $row_consulta['retorno_retentativa']; ?></p>
			<div style="width: 100%; display: flex; justify-content: center">
				<?php echo $card_off; ?>
			</div>

		<?php endif; ?>

		<?php if ($retorno->status == "NEG" && $payment_val === true): ?>
			<p></p>
		<?php endif; ?>

		<?php if ($retorno->status == "CON"): ?>
			<div id="via-cliente" class="modal-atualizador-ticket">
				<?php foreach ($retorno_transacao_con as $items): ?>
					<span><?php echo trim($items); ?></span><br>
				<?php endforeach; ?>
			</div>
			<div id="via-estabelecimento" class="modal-atualizador-ticket">
				<?php foreach ($retorno_transacao_con_2 as $items_2): ?>
					<span><?php echo trim($items_2); ?></span><br>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ($retorno->status == "EST"): ?>
			<div id="via-cliente" class="modal-atualizador-ticket">
				<?php foreach ($retorno_transacao_con as $items): ?>
					<span><?php echo trim($items); ?></span><br>
				<?php endforeach; ?>
			</div>
			<div id="via-estabelecimento" class="modal-atualizador-ticket">
				<?php foreach ($retorno_transacao_con_2 as $items_2): ?>
					<span><?php echo trim($items_2); ?></span><br>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ($status == "CON" || $status == "EST"): ?>
			<div style="display: flex; justify-content: center; align-items: center;margin-top: 20px">
				<p class="arrows" onclick="left()"><?php echo $left; ?></p>
				<span id="ticket">Via do cliente</span>
				<p class="arrows" onclick="right()"><?php echo $right ?></p>
				<?php $ticket = true; ?>
			</div>
		<?php endif; ?>
		<?php if ($ret === true && $retorno_api->schedule->nits && $ticket = false): ?>
			<?php
			echo "<table style='display: block; height: 270px; overflow-y: auto;'>";
			echo "<thead style='position: sticky; top: 0'>";
			echo "<tr>";
			echo "<th>NIT</th>";
			echo "<th>NSU</th>";
			echo "<th>Status</th>";
			echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
			foreach ($retorno_api->schedule->nits as $key => $value) {
				$nits = mysqli_query($con, "SELECT `transacao_nit` FROM `sys_vendas_transacoes_tef` WHERE `transacao_nit` = '" . $value . "';") or die(mysql_error());
				$row_nits = mysqli_fetch_array($nits);
				if ($row_nits['transacao_nit'] == $value) {
					echo "<tr>";
					echo "<td style='font-size: 10px'>" . $value . "</td>";
					echo "<td style='font-size: 10px'>" . $retorno_api->schedule->nsus[$key] . "</td>";
					echo "<td style='text-align: center'>" . $ok . "</td>";
					echo "</tr>";
				} else {
					echo "<tr>";
					echo "<td style='font-size: 10px'>" . $value . "</td>";
					echo "<td style='font-size: 10px'>" . $retorno_api->schedule->nsus[$key] . "</td>";
					echo "<td style='text-align: center'>" . $nok . "</td>";
					echo "</tr>";
				}
			}
			echo "</tbody>";
			echo "</table>";
			?>
		<?php endif; ?>
	</div>
	<script>
		function left() {
			document.getElementById("via-cliente").style.display = "block";
			document.getElementById("via-estabelecimento").style.display = "none";
			document.getElementById("ticket").innerHTML = "Via do cliente";
		}

		function right() {
			document.getElementById("via-cliente").style.display = "none";
			document.getElementById("via-estabelecimento").style.display = "block";
			document.getElementById("ticket").innerHTML = "Via do estabelecimento";
		}

		function copiarTexto() {
			let textoCopiado = document.getElementById("nit");
			textoCopiado.select();
			textoCopiado.setSelectionRange(0, 99999)
			document.execCommand("copy");
			let copiado = document.getElementById("copiado");
			copiado.innerHTML = "Copiado!";
			setInterval(function() {
				copiado.innerHTML = "";
			}, 2000);
		}
	</script>
</div>