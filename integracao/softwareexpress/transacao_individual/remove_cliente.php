<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        $cliente_cpf = $_GET['cpf'];

        $sql = "DELETE FROM sys_inss_clientes WHERE cliente_cpf = '$cliente_cpf'";
        $query = mysqli_query($con, $sql);

        if ($query) {
            $results[] = array(
                'ip' => $value['ip'],
                'status' => 'Cliente '. $cliente_cpf .' removido com sucesso!'
            );
        } else {
            $results[] = array(
                'ip' => $value['ip'],
                'status' => 'error',
                'error' => mysqli_error($con)
            );
        }
    } else {
        $results[] = array(
            'ip' => $value['ip'],
            'status' => 'error',
            'error' => mysqli_error($con)
        );
    }
}

echo json_encode($results);





?>