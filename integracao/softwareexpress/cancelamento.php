<?php 
// $url = 'https://www.grupofortune.com.br/sitef/autenticidade.php';
// $data = array(
// 	'sid' => '3333333333333333333333333333',
// 	'seid' => '444444444444444444444444444'
// 	);
// $options = array(
//     'http' => array(
//         'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
//         'method'  => 'POST',
//         'content' => http_build_query($data)
//     )
// );

// $context = stream_context_create($options);
// $result = file_get_contents($url, false, $context);
// $result = json_decode($result, true);
// if($result===false)
// {
// 	echo "Requisição com erro.";
// }
// print_r($result);


	header("Access-Control-Allow-Origin: *");
	$url = 'https://grupofortune.com.br/sitef/autenticidade.php';
	
	$seid = $_GET['seid'];
	$merchant_data = '190514014336300';
	$sid = $_GET['sid'];

	$metodo = 'POST';

	$data_corpo = [
		'seid' => $seid,
		'merchant_data' => $merchant_data,
		'sid' => $sid
	];

	$orignal_parse = parse_url($url, PHP_URL_HOST);
	$get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
	$read = stream_socket_client(
		"ssl://".$orignal_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get
	);
	$cont = stream_context_get_params($read);
	openssl_x509_export($cont["options"]["ssl"]["peer_certificate"],$certificado);

	// $CURLOPT_HTTPHEADER = array(
	// 	"Accept: application/json",
	// 	"Content-Type: application/json",
	// 	"cache-control: no-cache"
	// );

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				//CURLOPT_USERPWD => 'usuario:senha',
		CURLOPT_CUSTOMREQUEST => $metodo,
		//CURLOPT_POSTFIELDS => (http_build_query($data_url) != '') ? http_build_query($data_url) : '', // url
		CURLOPT_POSTFIELDS => $data_corpo, // corpo do envio
		//CURLINFO_CONTENT_TYPE => 'application/x-www-form-urlencoded"',
				//===========================
				// Certificado SSL do servidor remoto
				//=========================== 
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 0
		//CURLOPT_CAINFO => $certificado,
			    //===========================
		//CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER
	));

	$resposta = curl_exec($curl);
	$erro = curl_error($curl);
	curl_close($curl);
	
	echo $resposta;

	$respostaJson = json_decode($resposta);
?>