<?php

$data = array("matricula" => '', "cpf" => '');
$data_string = json_encode($data);

// chave secreta
$secret_key = "casadomenino";

// criptografia
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
$encrypted_data = openssl_encrypt($data_string, 'aes-256-cbc', $secret_key, 0, $iv);
$encrypted_data = base64_encode($encrypted_data . '::' . $iv);

// enviando os dados criptografados
$link = "https://www.gabriellgomess.com/colaborador?" . http_build_query(array("data" => $encrypted_data));

echo $link;

?>
