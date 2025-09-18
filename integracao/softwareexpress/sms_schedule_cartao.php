<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


header("Access-Control-Allow-Origin: *");
$usuario_api = 'defato.smsonline';
$senha_api = 'nHwCQBAfFu';


//$path_includes = "../../sistema/sistema/";
//$Arquivo_conect = "connect_seguro.php";
//include($path_includes.$Arquivo_conect);

$con = mysqli_connect("10.100.0.22","root","Theredpil2001","sistema");
if (!$con)
  {
  die('Could not connect: ' . mysqli_error());
  }

mysqli_query($con, "SET character_set_results=utf8");
mb_language('uni'); 
mb_internal_encoding('UTF-8');
mysqli_query($con, "set names 'utf8'");

date_default_timezone_set('America/Sao_Paulo');

$sql = "SELECT * FROM sys_notificacoes_cobranca WHERE notificacao_envio_sms IS NULL LIMIT 1";
$result = mysqli_query($con, $sql);

// Verificar se a consulta retornou resultados
if (mysqli_num_rows($result) == 0) {
    echo "Não há notificações para enviar";
} else {
    $row = mysqli_fetch_array($result);



$from = (isset($_GET['from'])) ? $_GET['from'] : '';
$to = $row['notificacao_telefone'];
//$to = "5551984093389";

$schedule = (isset($_GET['schedule'])) ? $_GET['schedule'] : '';
$id = (isset($_GET['id'])) ? $_GET['id'] : '';
$aggregateId = (isset($_GET['aggregateId'])) ? $_GET['aggregateId'] : '';
$flashSms = (isset($_GET['flashSms'])) ? $_GET['flashSms'] : '';

$msg = $row['notificacao_mensagem'];

$url_sms = 'https://sms.comtele.com.br/api/v2/send';
$metodo = 'POST';
@$data_corpo = "{
		\"Receivers\" : \"{$to}\",
		\"Content\" : \"{$msg}\"
		}
	}"; 

	$orignal_parse = parse_url($url_sms, PHP_URL_HOST);
	$get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
	$read = stream_socket_client(
		"ssl://".$orignal_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get
	);
	$cont = stream_context_get_params($read);
	openssl_x509_export($cont["options"]["ssl"]["peer_certificate"],$certificado);

	//echo $certificado . '<br><br>';

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url_sms,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				//CURLOPT_USERPWD => 'usuario:senha',
		CURLOPT_CUSTOMREQUEST => $metodo,
		//CURLOPT_GETFIELDS => (http_build_query($data_url) != '') ? http_build_query($data_url) : '', // url
		CURLOPT_POSTFIELDS => ($data_corpo != '') ? $data_corpo : '', // corpo do envio
		//CURLINFO_CONTENT_TYPE => 'application/x-www-form-urlencoded"',
				//===========================
				// Certificado SSL do servidor remoto
				//=========================== 
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 0,
		//CURLOPT_CAINFO => $certificado,
			    //===========================
		CURLOPT_HTTPHEADER => array(
			"Accept: application/json",
			"auth-key: 18fff0a5-ff86-4195-afdd-b9cadd411c72",
			"Content-Type: application/json",
			"cache-control: no-cache"
		),
	));

	$resposta = curl_exec($curl);
	$erro = curl_error($curl);
	curl_close($curl);
	
	print_r($resposta);
	
	$respostaJson = json_decode($resposta);
	$Success = $respostaJson->Success;
	if ($erro) {
		echo json_encode($erro, true);
	} else {
		if ($Success == true) {
            echo "Notificação SMS enviada com sucesso!";
            $atualiza_tabela_notificacao = "UPDATE sys_notificacoes_cobranca SET notificacao_envio_sms = NOW() WHERE notificacao_id = ".$row['notificacao_id']."";
            $result_atualiza_tabela_notificacao = mysqli_query($con, $atualiza_tabela_notificacao);
        } else {
            echo "Mensagem não enviada. ".$respostaJson->Message;
            
        }
        
	}

}
?>