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

$path_includes = "../var/www/html/sistema/sistema/";
//$path_includes = "../../../sistema/sistema/";
$Arquivo_conect = "connect_seguro.php";

//include($path_includes.$Arquivo_conect);      
$data = date('d/m/Y', strtotime('+3 days'));

$url = $link_prefixo.'/e-sitef/api/v1/schedules?start_date='.$data.'&end_date='.$data;
//$url = $link_prefixo.'/e-sitef/api/v1/transactions?start_date=05/02/2022&end_date=05/02/2022';

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
?>