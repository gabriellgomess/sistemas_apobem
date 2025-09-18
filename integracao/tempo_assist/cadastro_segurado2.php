<?php
echo "aqui3";
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tempoassist.com.br/sandbox/oauth2/access-token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"grant_type": "client_credentials"
}
',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Basic ZmZhZTQyMTUtYjM0Yi0zYmY5LTk1MjgtOTg0YjQ0NDNmNDQ1OmQ2YmYzZGU1LTk4ZTItM2E4ZS1iOGIwLTUyZTI5OGNhNTM2Yg==',
    'client_id: ffae4215-b34b-3bf9-9528-984b4443f445'
  ),
));

$response = curl_exec($curl);
$json = json_decode($response);
$ACESS_TOKEM = $json->access_token;

echo "toaqui";

$vendas_id = $_POST["vendas_id"];
$vendas_apolice = $_POST["vendas_apolice"];
$nomeNm = $_POST['clients_nm'];
$nomeNome = $_POST['cliente_nome'];
$nasc = $_POST['cliente_nascimento'];
$cpf = $_POST['cliente_cpf'];
$email = $_POST['cliente_email'];

$sexo = $_POST['cliente_sexo'];
$estadoCivil = $_POST['cliente_est_civil'];
$rg = $_POST['cliente_rg'];

if($sexo == "F"){
  $sexo = 2;
}elseif($sexo == "M"){
  $sexo = 1;
}else{
  $sexo = 1;
}


//1 – casado; 2 – solteiro; 3 – divorciado; 4 – viúvo; 5 – outros

if($estadoCivil == "casado"){
  $estadoCivil = 1;
}elseif($estadoCivil == "solteiro"){
  $estadoCivil = 2;
}elseif($estadoCivil == "divorciado"){
  $estadoCivil = 3;
}elseif($estadoCivil == "VIUVO"){
  $estadoCivil = 4;
}elseif($estadoCivil == "outros"){
  $estadoCivil = 5;
}else{
  $estadoCivil = 2;
}


if($rg == ""){
  $rg == "";
}

if($_POST['cliente_email'] == ""){

  $email = "";
}

if($_POST['cliente_nascimento'] == ""){

  $nasc = "";
}

if($nomeNm  == ""){
  $nome =  $_POST['cliente_nome'];
}
if($nomeNome == ""){
  $nome =  $_POST['clients_nm'];
}

if($vendas_apolice == 113 || $vendas_apolice == 121 || $vendas_apolice == 112  || $vendas_apolice == 120  || $vendas_apolice == 119  || $vendas_apolice == 111){

  $idPlanoUss = 536395;
}
if($vendas_apolice == 110 || $vendas_apolice == 118){
  $idPlanoUss = 536399;
}
if($vendas_apolice == 130 || $vendas_apolice == 129 || $vendas_apolice == 128){
  $idPlanoUss = 536401;
}

echo "<br>vendasid - " . $vendas_id;
echo "<br>apolice - " .$vendas_apolice;
echo "<br>nome - " .$nome;
echo "<br>nasci - " .$nasc;
echo "<br>cpf - " .$cpf;
echo "<br>email - " .$email;
echo "<br>sexo - " . $sexo;
echo "<br>estadoCivil - " .$estadoCivil;
echo "<br>rg - " .$rg;


$agora = round(microtime(true) * 1000);
echo "data ativação - " . $agora;



curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.tempoassist.com.br/sandbox/segurado/itemCoberto',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "segmento" : 3,
  "apolice" : "'. $vendas_id .'",
  "idPlanoUss" : "' . $idPlanoUss . '",
  "inicioVig" : "'.$agora . '",
  "fimVig" : 29032060100000,
  "segurado" : "Inclusão do segurado - ' . $nome . '",
  "cpfCnpj" : "'. $cpf .'",
  "email" : "' . $email . '",
  "vip" : "N",
  "idClienteCorporativo" : 10592,
  "pessoa":{  
      "nome":" ' . $nome . '",
      "sexo":"'. $sexo . '",
      "dataNasc":"' . $nasc . '",
      "cpf":"'. $cpf .'",
      "rg":"'. $rg . '",
      "estadoCivil":"'.$estadoCivil.'",
      "profissao":"",
      "qtdVidas":"1"
   }
}',
  CURLOPT_HTTPHEADER => array(
    'client_id: ffae4215-b34b-3bf9-9528-984b4443f445',
    'access_token: ' . $ACESS_TOKEM . '',
    'token: 467421A11DB71997DA38C17BD2F57D0',
    'chave: APOBEM_HML',
    'idClienteCorporativo: 10592',
    'idTipoCarteira: 3',
    'idPlanoUss: '.$idPlanoUss.'',
    'Content-Type: application/json'
  ),
));



$response = curl_exec($curl);

curl_close($curl);
echo " <BR> resposta json -" . $response;

?>


