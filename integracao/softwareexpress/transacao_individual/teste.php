<?php

$host = "10.100.0.22";
$username = "root";
$password = "Theredpil2001";
$database = "sistema";

$con = new mysqli($host, $username, $password, $database);

$cliente_cpf = '49222287053';

$stmt = $con->prepare("SELECT cliente_nome, cliente_nascimento, cliente_email, cliente_sexo, cliente_est_civil, cliente_rg FROM `sys_inss_clientes`
WHERE `cliente_cpf` LIKE ? LIMIT 0 , 30");
    $stmt->bind_param('s', $cliente_cpf);
    $stmt->execute();
    $stmt->store_result();            
    $stmt->bind_result($nomeNome, $nasc, $email, $sexo, $estadoCivil, $rg);
    $stmt->fetch();
    $stmt->free_result();

    echo $nomeNome . '<br>';
    echo $nasc . '<br>';
    echo $email . '<br>';
    echo $sexo . '<br>';
    echo $estadoCivil . '<br>';
    echo $rg . '<br>';


