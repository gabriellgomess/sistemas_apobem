<?php
$val = '91968';
echo "<br>Venda: ".$val."<br>";
$encoded = (base64_encode($val));
var_dump($encoded);
echo "<br>Codificado: ".$encoded."<br>";
$decoded = (base64_decode($encoded));
echo "<br>Decodificado: ".$decoded."<br>";

?>