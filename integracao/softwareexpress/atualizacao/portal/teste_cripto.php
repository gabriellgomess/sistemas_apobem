<?php


$secret_key = "u";
$cpf = 83029052087;
$id_venda = 123456;


// dados a serem enviados
$data = array("code" => $secret_key."-".$cpf."-".$id_venda);
$data_string = json_encode($data);



// criptografia
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));
$encrypted_data = openssl_encrypt($data_string, 'aes-128-cbc', $secret_key, 0, $iv);
$encrypted_data = base64_encode($encrypted_data . '::' . $iv);

// enviando os dados criptografados
$link = "http://localhost:3000/portal/?schdl=1&" . http_build_query(array("data" => $encrypted_data));

echo $link;
?>
