<?php

  date_default_timezone_set('America/Sao_Paulo');
$date1=date_create("2020-07-13");
$date2=date_create(date("2021-04-12"));
$diff=date_diff($date1,$date2);
$transacao_parcela = $diff->format("%a") / 30;
echo $transacao_parcela."<br>";
$pos = strrpos($transacao_parcela,'.');
if($pos !== false){
  $transacao_parcela = substr($transacao_parcela,0,$pos);
}

echo $transacao_parcela."<br>";

echo date("w")."<br>";

echo date('YmdHis')."<br>";
?>