<?php
// debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header("Access-Control-Allow-Origin: *");
include("variaveis_fixas.php");

// $nit = "d5d62749d3d7b0a524282618cf3011a58858926810e7b2370ef6e573298844fa";
// $status = "";
$id = isset($_POST['id']) ? $_POST['id'] : '';
$transacao_id = isset($_POST['nit']) ? $_POST['nit'] : '';
$status = isset($_POST["status"]) ? $_POST["status"] : '';
$authTipo = isset($_POST["auth_id"]) ? $_POST["auth_id"] : '';
$data = isset($_POST["data"]) ? $_POST["data"] : '';
$order_id = isset($_POST["order_id"]) ? $_POST["order_id"] . '_C' : '';
$user = isset($_POST["user"]) ? $_POST["user"] : '';
$user_id = isset($_POST["user"]) ? $_POST["user"] : ''; // Definindo user_id baseado no user
$cpf = isset($_POST["cpf"]) ? $_POST["cpf"] : '';
$id_venda = isset($_POST["id_venda"]) ? $_POST["id_venda"] : '';
// $data = "21/09/2022";
// $order_id = "2022121180_C";

$Arquivo_conect = "../connect_seguro.php";

include($Arquivo_conect);

$url = $link_prefixo . '/e-sitef/api/v1/transactions?start_date=' . $data . '&end_date=' . $data;
//$url = $link_prefixo.'/e-sitef/api/v1/transactions?start_date=07/05/2021&end_date=11/05/2021';

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

// Inicializar a variável $response como objeto
$response = new stdClass();

$response->resposta = json_decode(curl_exec($curl));

$response->erro = curl_error($curl);
$response->transaction_error_number = curl_errno($curl);

curl_close($curl);

$update_icon = "<svg xmlns='http://www.w3.org/2000/svg' enable-background='new 0 0 24 24' height='24px' viewBox='0 0 24 24' width='24px' fill='#000000'><g><rect fill='none' height='24' width='24'/></g><g><g><path d='M11,8.75v3.68c0,0.35,0.19,0.68,0.49,0.86l3.12,1.85c0.36,0.21,0.82,0.09,1.03-0.26c0.21-0.36,0.1-0.82-0.26-1.03 l-2.87-1.71v-3.4C12.5,8.34,12.16,8,11.75,8S11,8.34,11,8.75z M21,9.5V4.21c0-0.45-0.54-0.67-0.85-0.35l-1.78,1.78 c-1.81-1.81-4.39-2.85-7.21-2.6c-4.19,0.38-7.64,3.75-8.1,7.94C2.46,16.4,6.69,21,12,21c4.59,0,8.38-3.44,8.93-7.88 c0.07-0.6-0.4-1.12-1-1.12c-0.5,0-0.92,0.37-0.98,0.86c-0.43,3.49-3.44,6.19-7.05,6.14c-3.71-0.05-6.84-3.18-6.9-6.9 C4.94,8.2,8.11,5,12,5c1.93,0,3.68,0.79,4.95,2.05l-2.09,2.09C14.54,9.46,14.76,10,15.21,10h5.29C20.78,10,21,9.78,21,9.5z'/></g></g></svg>";
$updated_icon = "<svg xmlns='http://www.w3.org/2000/svg' enable-background='new 0 0 24 24' height='24px' viewBox='0 0 24 24' width='24px' fill='#FFFFFF'><rect fill='none' height='24' width='24'/><path d='M21.29,5.89l-10,10c-0.39,0.39-1.02,0.39-1.41,0l-2.83-2.83c-0.39-0.39-0.39-1.02,0-1.41l0,0c0.39-0.39,1.02-0.39,1.41,0 l2.12,2.12l9.29-9.29c0.39-0.39,1.02-0.39,1.41,0l0,0C21.68,4.87,21.68,5.5,21.29,5.89z M15.77,2.74c-1.69-0.69-3.61-0.93-5.61-0.57 C6.09,2.9,2.84,6.18,2.15,10.25C1.01,17,6.63,22.78,13.34,21.91c3.96-0.51,7.28-3.46,8.32-7.31c0.4-1.47,0.44-2.89,0.21-4.22 c-0.13-0.8-1.12-1.11-1.7-0.54v0c-0.23,0.23-0.33,0.57-0.27,0.89c0.22,1.33,0.12,2.75-0.52,4.26c-1.16,2.71-3.68,4.7-6.61,4.97 c-5.1,0.47-9.33-3.85-8.7-8.98c0.43-3.54,3.28-6.42,6.81-6.91c1.73-0.24,3.37,0.09,4.77,0.81c0.39,0.2,0.86,0.13,1.17-0.18l0,0 c0.48-0.48,0.36-1.29-0.24-1.6C16.31,2.98,16.04,2.85,15.77,2.74z'/></svg>";


if ($response->resposta && $response->resposta->code == 0) {
	$cont = 1;
	foreach ($response->resposta->transactions as $transacao) {
		$update_valores = "";
		if ($transacao->order_id == $order_id) {
?>
			<script>
				jQuery("#atualizador").on("click", function() {
					console.log("Atualizando...");
					var order_id = document.getElementById('order_id').value;
					var nit = document.getElementById('nit').value;
					var authorizer_code = document.getElementById('authorizer_code').value;
					var authorizer_message = document.getElementById('authorizer_message').value;
					var status = document.getElementById('status').value;
					var authorizer_id = document.getElementById('authorizer_id').value;
					var acquirer_id = document.getElementById('acquirer_id').value;
					var creation_date = document.getElementById('creation_date').value;
					var authorization_number = document.getElementById('authorization_number').value;
					var esitef_usn = document.getElementById('esitef_usn').value;
					var host_usn = document.getElementById('host_usn').value;
					var tid = document.getElementById('tid').value;
					var amount = document.getElementById('amount').value;
					var payment_type = document.getElementById('payment_type').value;
					var authorizer_merchant_id = document.getElementById('authorizer_merchant_id').value;
					var type = document.getElementById('type').value;
					var merchant_id = document.getElementById('merchant_id').value;
					var installments = document.getElementById('installments').value;



					var dados_json = {
						"authorizer_code": authorizer_code,
						"authorizer_message": authorizer_message,
						"status": status,
						"nit": nit,
						"order_id": order_id,
						"authorizer_id": authorizer_id,
						"acquirer_id": acquirer_id,
						// "acquirer_name": acquirer_name????,
						"authorizer_date": authorizer_date,
						"authorization_number": authorization_number,
						"esitef_usn": esitef_usn,
						"host_usn": host_usn,
						"tid": tid,
						"amount": amount,
						"payment_type": payment_type,
						"authorizer_merchant_id": authorizer_merchant_id,
						"type": type,
						"merchant_id": merchant_id,
						"creation_date": creation_date,
						"installments": installments
					}
					var dados = JSON.stringify(dados_json);
					var date_bd = "<?php echo $dataAuth[0]; ?>"
					var date_bd = date_bd.split("/").reverse().join("-");
					var authorizer_date = date_bd + "T" + "<?php echo $dataAuth[1]; ?>";
					console.log(dados_json)
					jQuery.ajax({
						url: "https://www.grupofortune.com.br/integracao/softwareexpress/atualiza_transacao.php",
						type: "POST",
						data: {
							data: dados,
							id_venda: "<?php echo $id_venda; ?>",
							order_number: order_id,
							nit: nit,
							esitef_usn: esitef_usn,
							authorizer_id: authorizer_id,
							status: status,
							authorizer_date: authorizer_date,
							amount: amount,
							user_id: "<?php echo $user_id; ?>",
							cpf: "<?php echo $cpf; ?>"
						},

						success: function(result) {
							$(".retorno").html(result);
							console.log(result);
							setInterval(function() {
								jQuery(".container-modal").children().children("p").each(function() {
									jQuery(this).addClass("colorGreen");
								});
							}, 1000);
						}
					});
					setTimeout(function() {
						jQuery(".container-modal").children().children("p").each(function() {
							jQuery(this).addClass("colorGreen");
							jQuery(".retorno-ajax").html("Dados atualizados com sucesso!");
							jQuery(".button-update").removeClass("button-update-warning");
							jQuery(".button-update").addClass("button-update-success");
							jQuery(".button-update").html("<?php echo $updated_icon; ?> Atualizado");
						});
					}, 1000);

				})
			</script>
			<div class="container-modal" style="display: flex; padding: 15px">
				<div class="modal-left modal-update">
					<h2 style="margin: 6px;">Atualizar transação</h2>

					<label for="">Order ID</label>
					<p><?php echo $transacao->order_id; ?></p>
					<input id="order_id" type="hidden" value="<?php echo $transacao->order_id; ?>">

					<label for="">NIT</label>
					<p style="font-size: 11px;"><?php echo $transacao->nit; ?></p>
					<input id="nit" type="hidden" value="<?php echo $transacao->nit; ?>">

					<label for="">Código de Autorização</label>
					<p><?php echo $transacao->authorizer_code; ?></p>
					<input id="authorizer_code" type="hidden" value="<?php echo $transacao->authorizer_code; ?>">

					<label for="">Mensagem</label>
					<p><?php echo $transacao->authorizer_message; ?></p>
					<input id="authorizer_message" type="hidden" value="<?php echo $transacao->authorizer_message; ?>">

					<label for="">Status</label>
					<p><?php echo $transacao->status; ?></p>
					<input id="status" type="hidden" value="<?php echo $transacao->status; ?>">

					<label for="">ID de Autorização</label>
					<p><?php echo $transacao->authorizer_id; ?></p>
					<input id="authorizer_id" type="hidden" value="<?php echo $transacao->authorizer_id; ?>">

					<label for="">Adquirente</label>
					<p><?php echo $transacao->acquirer_id; ?> - <?php echo $transacao->acquirer_name; ?></p>
					<input id="acquirer_id" type="hidden" value="<?php echo $transacao->acquirer_id; ?>">

					<label for="">Data da Criação | Autorização | Pagamento</label>
					<?php $dataCria = explode("T", $transacao->creation_date); ?>
					<?php $dataAuth = explode("T", $transacao->authorizer_date); ?>
					<?php $dataPay = explode("T", $transacao->payment_date); ?>
					<p><?php echo $dataCria[0] . " - " . $dataCria[1]; ?> | <?php echo $dataAuth[0] . " - " . $dataAuth[1]; ?> | <?php echo $dataPay[0] . " - " . $dataPay[1]; ?></p>
					<input id="creation_date" type="hidden" value="<?php echo $transacao->creation_date; ?>">

					<label for="">Número da Autorização</label>
					<p><?php echo $transacao->authorization_number; ?></p>
					<input id="authorization_number" type="hidden" value="<?php echo $transacao->authorization_number; ?>">

					<label for="">Número sequêncial único da transação</label>
					<p><?php echo $transacao->esitef_usn; ?></p>
					<input id="esitef_usn" type="hidden" value="<?php echo $transacao->esitef_usn; ?>">
				</div>
				<div class="modal-right modal-update">
					<label for="">NSU da autorizadora</label>
					<p><?php echo $transacao->host_usn; ?></p>
					<input id="host_usn" type="hidden" value="<?php echo $transacao->host_usn; ?>">

					<label for="">ID da transação no adquirente</label>
					<p><?php echo $transacao->tid; ?></p>
					<input id="tid" type="hidden" value="<?php echo $transacao->tid; ?>">

					<label for="">Valor</label>
					<p><?php
						$amount1 = substr($transacao->amount, 0, -2);
						$amount2 = substr($transacao->amount, -2);
						echo "R$ " . $amount1 . "," . $amount2;
						?></p>
					<input id="amount" type="hidden" value="<?php echo $transacao->amount; ?>">

					<label for="">Tipo de Pagamento</label>
					<?php
					switch ($transacao->payment_type) {
						case 'B':
							$tipo = "Boleto";
							break;
						case 'C':
							$tipo = "Cartão de Crédito";
							break;
						case 'D':
							$tipo = "Débito";
							break;
						case 'P':
							$tipo = "cartão crédito Private Label puro";
							break;
						case 'T':
							$tipo = "Tranferência bancária";
							break;
						case 'G':
							$tipo = "cartão gift";
							break;
						case 'O':
							$tipo = "Outros meios de pagamentos";
							break;
						case 'W':
							$tipo = "Boleto NR via Web Service";
							break;
						default:
							$tipo = "Não identificado";
							break;
					}
					?>
					<p><?php echo "(" . $transacao->payment_type . ") - " . $tipo; ?></p>
					<input id="payment_type" type="hidden" value="<?php echo $transacao->payment_type; ?>">

					<label for="">Código de afiliação do lojista</label>
					<p><?php echo $transacao->authorizer_merchant_id; ?></p>
					<input id="authorizer_merchant_id" type="hidden" value="<?php echo $transacao->authorizer_merchant_id; ?>">

					<label for="">Type</label>
					<p><?php echo $transacao->type; ?></p>
					<input id="type" type="hidden" value="<?php echo $transacao->type; ?>">

					<label for="">ID Loja</label>
					<p><?php echo $transacao->merchant_id; ?></p>
					<input id="merchant_id" type="hidden" value="<?php echo $transacao->merchant_id; ?>">

					<label for="">Número de parcelas usado no pagamento agendado</label>
					<p><?php echo $transacao->installments; ?></p>
					<input id="installments" type="hidden" value="<?php echo $transacao->installments; ?>">

					<hr>
					<span class="retorno-ajax" style="font-size: 12px; text-align: center; display: block">Estes dados não estão disponíveis no sistema, clique no botão abaixo para realizar a atualização.</span>
					<div style="position: absolute; width: 460px; bottom: 35px">
						<div style="margin: 0 auto;" id="atualizador" class="button-update button-update-warning noselect">
							<?php echo $update_icon; ?>
							Atualizar
						</div>
					</div>
				</div>
	<?php
		}
	}
}

if (!empty($error)) {
	echo $error;
}

	?>
	<script>

	</script>


























	<!-- if($response->resposta->code == 0){
	$cont = 1;
	foreach ($response->resposta->transactions as $transacao){
		$update_valores = "";
		if($transacao->order_id == $order_id){
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
			print_r( $transacao );
			
			if($transacao->esitef_usn){$esitef_usn = $transacao->esitef_usn;}else{$esitef_usn = $transacao->payment->esitef_usn;}
			if($transacao->nit){$transacao_nit = $transacao->nit;}else{$transacao_nit = $transacao->payment->nit;}
			if($transacao->authorizer_code){$authorizer_code = $transacao->authorizer_code;}else{$authorizer_code = $transacao->payment->authorizer_code;}
			
			echo "<br>";
			$cont++;
		}
	}
} -->