<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$secret_key = "u";
$encrypted_data = "WkJKdEczdWZMYWdVZGc2QWhNcXM1RW5iVXc5NzlpcWxmVVV4Zm5SSi90RT06OhIpxlebKZJItbfa19ocayI%3D"; // Obtenha os dados criptografados da URL

$parts = explode('::', base64_decode($encrypted_data));
$decrypted = openssl_decrypt($parts[0], 'aes-128-cbc', $secret_key, 0, $parts[1]);

$decrypted_data = json_decode($decrypted, true);

$dados = explode("-", $decrypted_data['code']);

echo "CPF: ".$dados[1]."<br>";
echo "ID Venda: ".$dados[2]."<br>";


?>
