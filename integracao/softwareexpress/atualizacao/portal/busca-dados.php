<?php
//Permitir acesso de origens, métodos e cabeçalhos específicos
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json; charset=utf-8');

//Define o caminho para o arquivo de conexão
$path_includes = "/var/www/html/sistema/sistema/";
$arquivo_conect = $path_includes."connect_seguro.php";
include($arquivo_conect);

//Recebe os dados enviados como parâmetro GET chamado "data"
$encrypted_data = $_GET['data'];


//Chave secreta utilizada para descriptografar os dados
$secret_key = "u";

$parts = explode('::', base64_decode($encrypted_data));
$decrypted = openssl_decrypt($parts[0], 'aes-128-cbc', $secret_key, 0, $parts[1]);

$decrypted_data = json_decode($decrypted, true);

$dados = explode("-", $decrypted_data['code']);

$data = array(
    'sale_id' => $dados[2],
    'cpf' => $dados[1],
);
$sale_id = $data['sale_id'];
$cpf = $data['cpf'];

//Incluir arquivo api-portal.php
include('api-portal.php');

//Cria objeto PortalCobranca e chama a função consultar
$portalCobranca = new PortalCobranca($con);
$busca_dados_cliente = $portalCobranca->consultar($sale_id, $cpf);





//Exibe a saída da função consultar
echo $busca_dados_cliente;

?>