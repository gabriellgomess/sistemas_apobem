<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$server = array(
    array(
        'ip' => '10.100.0.16',
        'user' => 'root',
        'pass' => 'Theredpil2001',
        'db' => 'sistema'
    ),
    array(
        'ip' => '10.100.0.22',
        'user' => 'root',
        'pass' => 'Theredpil2001',
        'db' => 'sistema'
    ),
    array(
        'ip' => '10.100.0.6',
        'user' => 'root',
        'pass' => 'Theredpil2001',
        'db' => 'sistema'
    )
);

$results = array();

foreach ($server as $value) {
    $con = mysqli_connect($value['ip'], $value['user'], $value['pass'], $value['db']);
    if ($con) {
        $cliente_cpf = $_POST['cpf'];
        $removidos = array(".", "-");
        $cliente_cpf = str_replace($removidos, "", $cliente_cpf);
        $cliente_nome = $_POST['nome'];
        $cliente_nascimento = $_POST['dataNascimento'];
        $cliente_sexo = $_POST['sexo'];
        $cliente_endereco = $_POST['logradouro'];
        $cliente_endereco_complemento = $_POST['complemento'];
        $cliente_bairro = $_POST['bairro'];
        $cliente_cidade = $_POST['cidade'];
        $cliente_uf = $_POST['estado'];
        $cliente_cep = $_POST['cep'];
        $cliente_celular = $_POST['celular'];
        $cliente_email = $_POST['email'];

        $sql = "INSERT INTO sys_inss_clientes (cliente_cpf, cliente_nome, cliente_nascimento, cliente_sexo, cliente_endereco, cliente_endereco_complemento, cliente_bairro, cliente_cidade, cliente_uf, cliente_cep, cliente_celular, cliente_email) 
        VALUES ('$cliente_cpf', '$cliente_nome', '$cliente_nascimento', '$cliente_sexo', '$cliente_endereco', '$cliente_endereco_complemento', '$cliente_bairro', '$cliente_cidade', '$cliente_uf', '$cliente_cep', '$cliente_celular', '$cliente_email')
        ON DUPLICATE KEY UPDATE
        cliente_nome = VALUES(cliente_nome),
        cliente_nascimento = VALUES(cliente_nascimento),
        cliente_sexo = VALUES(cliente_sexo),
        cliente_endereco = VALUES(cliente_endereco),
        cliente_endereco_complemento = VALUES(cliente_endereco_complemento),
        cliente_bairro = VALUES(cliente_bairro),
        cliente_cidade = VALUES(cliente_cidade),
        cliente_uf = VALUES(cliente_uf),
        cliente_cep = VALUES(cliente_cep),
        cliente_celular = VALUES(cliente_celular),
        cliente_email = VALUES(cliente_email)";

        $result = mysqli_query($con, $sql);

        if (!$result) {
            $results[] = array(
                'ip' => $value['ip'],
                'status' => 'Erro MySQL: ' . mysqli_error($con)
            );
        } else {
            $results[] = array(
                'ip' => $value['ip'],
                'status' => 'Inserido com sucesso'
            );
        }
    } else {
        $results[] = array(
            'ip' => $value['ip'],
            'status' => 'Erro ao conectar ao banco de dados'
        );
    }
}

// Convertendo o array de resultados em formato JSON
$json_results = json_encode($results);

// Exibindo o JSON de resultados
echo $json_results;
?>
