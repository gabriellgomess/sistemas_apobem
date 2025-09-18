<?php
header("Access-Control-Allow-Origin: *");
include("variaveis_fixas.php");

$sid = $_POST['sid'];
// $sid = "24f18c808c8d8538feeafccfa66796315cf76d376e9d5c1749ebc8fd8b59060a";
$id = $_POST['id'];
$user = $_POST['user'];
$cpf = $_POST['cpf'];

$Arquivo_conect = "../connect_seguro.php";

include($Arquivo_conect);

$url = $link_prefixo.'/e-sitef/api/v1/schedules/'.$sid;
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
	//return json_encode($response);
	// print_r( $response ) ;

//echo "retorno_api: ".$response;

// if transação == captura
// $resposta = $response->resposta->capture;

$ok = "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#378805'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M9 16.2l-3.5-3.5c-.39-.39-1.01-.39-1.4 0-.39.39-.39 1.01 0 1.4l4.19 4.19c.39.39 1.02.39 1.41 0L20.3 7.7c.39-.39.39-1.01 0-1.4-.39-.39-1.01-.39-1.4 0L9 16.2z'/></svg>";
$nok = "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#c41b1b'><path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm6.31-3.1L7.1 5.69C8.45 4.63 10.15 4 12 4c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z'/></svg>";
$copy = "<svg xmlns='http://www.w3.org/2000/svg' height='18px' viewBox='0 0 24 24' width='24px' fill='grey'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z'/></svg>";
$calendar = "<svg xmlns='http://www.w3.org/2000/svg' enable-background='new 0 0 24 24' height='24px' viewBox='0 0 24 24' width='24px' fill='#757575'><g><rect fill='none' height='24' width='24'/><rect fill='none' height='24' width='24'/></g><g><path d='M17,2c-0.55,0-1,0.45-1,1v1H8V3c0-0.55-0.45-1-1-1S6,2.45,6,3v1H5C3.89,4,3.01,4.9,3.01,6L3,20c0,1.1,0.89,2,2,2h14 c1.1,0,2-0.9,2-2V6c0-1.1-0.9-2-2-2h-1V3C18,2.45,17.55,2,17,2z M19,20H5V10h14V20z M11,13c0-0.55,0.45-1,1-1s1,0.45,1,1 s-0.45,1-1,1S11,13.55,11,13z M7,13c0-0.55,0.45-1,1-1s1,0.45,1,1s-0.45,1-1,1S7,13.55,7,13z M15,13c0-0.55,0.45-1,1-1s1,0.45,1,1 s-0.45,1-1,1S15,13.55,15,13z M11,17c0-0.55,0.45-1,1-1s1,0.45,1,1s-0.45,1-1,1S11,17.55,11,17z M7,17c0-0.55,0.45-1,1-1 s1,0.45,1,1s-0.45,1-1,1S7,17.55,7,17z M15,17c0-0.55,0.45-1,1-1s1,0.45,1,1s-0.45,1-1,1S15,17.55,15,17z'/></g></svg>";
$hour = "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='#757575'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-.22-13h-.06c-.4 0-.72.32-.72.72v4.72c0 .35.18.68.49.86l4.15 2.49c.34.2.78.1.98-.24.21-.34.1-.79-.25-.99l-3.87-2.3V7.72c0-.4-.32-.72-.72-.72z'/></svg>";

$log = mysqli_query($con, "SELECT `response_json` FROM `sys_vendas_transacoes_tef_log` WHERE `transacao_id` = $id ORDER BY `log_id` DESC LIMIT 0, 1;") or die(mysql_error());
$row_log = mysqli_fetch_array( $log );
$retorno_bd = json_decode($row_log['response_json']);
$retorno_api = $response->resposta;

// echo "Consulta Schedule<br>";
// foreach($retorno_api->schedule->nits as $key => $value){
// 	$nits = mysqli_query($con, "SELECT `transacao_nit` FROM `sys_vendas_transacoes_tef` WHERE `transacao_nit` = '".$value."';") or die(mysql_error());
// 	$row_nits = mysqli_fetch_array( $nits );
// 	if($row_nits['transacao_nit'] == $value){
// 		echo "NIT: ".$value." - <b>Existe transação no banco de dados</b><br>";
// 	}else{
// 		echo "NIT: ".$value." - <b>Não existe transação no banco de dados</b><br>";
// 	}
// }
// $busca_campos = mysqli_query($con, "SELECT * FROM `sys_vendas_transacoes_campos` WHERE `tipo` = 'agendamento';") or die(mysql_error());
// echo "TIPO: agendamento<br>";
// while($row_campos = mysqli_fetch_array( $busca_campos )){	
// 	if($retorno_api->schedule->$row_campos['nm_api'] == $retorno_bd->schedule->$row_campos['nm_api']){
// 		echo "<p style='font-size: 12px'>".$row_campos['nm_api']. "( ".$retorno_api->schedule->$row_campos['nm_api']." ) <b> IGUAIS</b></p>";
// 	}else{
// 		echo "<p style='font-size: 12px'>".$row_campos['nm_api']." <b>DIFERENTES</b></p>";
// 	}
// }

?>


<div style="display: flex; padding: 15px">
	<div class="modal-left">
		<h2>Transação Agendada</h2>
		<label for="">Mensagem</label>
		<p><?php echo $response->resposta->message; ?></p>
		<label for="">SID</label>
		<input style="height: 0; border: none" id="sid" type="text" name=""
			value="<?php echo $response->resposta->schedule->sid; ?>">
		<p style="font-size: 10px; display: flex; align-items:center"><?php echo $response->resposta->schedule->sid; ?>
			<span id="copiar_sid" onclick="copiarTexto()" style="cursor: pointer"><?php echo $copy; ?></span><span
				style="color: green;" id="copiado"></span></p>
		<p style="font-size: 10px;"></p>
		<label for="">Status</label>
		<p><?php echo $response->resposta->schedule->status;  ?></p>
		<label for="">Authorizer ID</label>
		<p><?php echo $response->resposta->schedule->authorizer_id; ?></p>
		<label for="">Número de tentativas de cobrança por agendamento</label>
		<p><?php echo $response->resposta->schedule->current_times; ?></p>
		<label for="">Data Inicial</label>
		<p><?php echo $response->resposta->schedule->initial_date; ?></p>
		<label for="">Próxima Data</label>
		<p><?php echo $response->resposta->schedule->next_date; ?></p>
		<label for="">Valor</label>
		<p>
			<?php
			$amount1 = substr($response->resposta->schedule->amount, 0, -2) ;
			$amount2 = substr($response->resposta->schedule->amount, -2) ;
			echo "R$ ".$amount1.",".$amount2;
			?>
		</p>
	</div>
	<div class="modal-right">

		<div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; height: 453px;">
			<?php
			foreach($retorno_api->schedule->nits as $key => $value){ ?>
			<?php
				$nits = mysqli_query($con, "SELECT `transacao_nit` FROM `sys_vendas_transacoes_tef` WHERE `transacao_nit` = '".$value."';") or die(mysql_error());
				$row_nits = mysqli_fetch_array( $nits );
				if($row_nits['transacao_nit'] == $value){
				
			?>			
			<script>
				var id = '<?php echo $id; ?>';
				var nit = '<?php echo $value; ?>';
				var nit_api = '<?php echo $row_nits['transacao_nit'] ?>';
				var atualizar = 'não';
				var cpf = '<?php echo $cpf; ?>';
				var json = '<?php echo json_encode($retorno_api) ; ?>'
				
				jQuery.ajax({
					url: "https://www.grupofortune.com.br/integracao/softwareexpress/consulta_transaction_V2.php", 
					type: "POST",
					data: {
						id: id,
						nit: nit,
						nit_api: nit_api,
						atualizar: atualizar,
						user: '<?php echo $user; ?>',
						cpf: cpf,
						json: json
					},
					beforeSend: function(){
						jQuery("#nitret-<?php echo $key; ?>").html(`<div class="lds-facebook"><div></div><div></div><div></div></div>`);
					},
					success: function(result){
						jQuery("#nitret-<?php echo $key; ?>").html(result)
					},
					error: function(result){
						console.log("Erro")
						console.log(result)
					}					
				})
			</script>			
				<div class="container-card" id="nitret-<?php echo $key; ?>"></div>	

			<?php }else{ 
				?>
				<script>
				var id = '<?php echo $id; ?>';
				var nit = '<?php echo $value; ?>';
				var nit_api = '<?php echo $row_nits['transacao_nit'] ?>';
				var atualizar = 'sim';
				var cpf = '<?php echo $cpf; ?>';
				var json = '<?php echo json_encode($retorno_api) ; ?>';
				console.log("Teste: "+json)
				jQuery.ajax({
					url: "https://www.grupofortune.com.br/integracao/softwareexpress/consulta_transaction_V2.php", 
					type: "POST",
					data: {
						id: id,
						nit: nit,
						nit_api: nit_api,
						atualizar: atualizar,
						user: '<?php echo $user; ?>',
						cpf: cpf,
						json: json
					},
					beforeSend: function(){
						jQuery("#nitret-<?php echo $key; ?>").html(`<div class="lds-facebook"><div></div><div></div><div></div></div>`);
					},
					success: function(result){						
						jQuery("#nitret-<?php echo $key; ?>").html(result)
					},
					error: function(result){
						console.log("Erro")
						console.log(result)
					}					
				})
			</script>
						
			
			<div class="container-card" id="nitret-<?php echo $key; ?>"></div>
			
			

			<?php			
				}
			}
			?>

		</div>


	</div>
	<script>

		function copiarTexto() {

			let textoCopiado = document.getElementById("sid");
			textoCopiado.select();
			textoCopiado.setSelectionRange(0, 99999)
			document.execCommand("copy");
			let copiado = document.getElementById("copiado");
			copiado.innerHTML = "Copiado!";
			setInterval(function () {
				copiado.innerHTML = "";
			}, 2000);
		}


	</script>
</div>