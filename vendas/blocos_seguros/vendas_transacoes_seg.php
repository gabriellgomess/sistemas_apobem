<?php
date_default_timezone_set('America/Sao_Paulo');
include("sistema/utf8.php");
include("../../../sistema/connect.php");


$vendas_id = (isset($_POST['vendas_id'])) ? $_POST['vendas_id'] : '';
$transacao_proposta = (isset($_POST['transacao_proposta'])) ? $_POST['transacao_proposta'] : '';
$transacao_valor = (isset($_POST['transacao_valor'])) ? $_POST['transacao_valor'] : '';
$transacao_data_importacao =  date("Y-m-d H:i:s");
$transacao_data =  date("Y-m-d");
$transacao_parcela = (isset($_POST['transacao_parcela'])) ? $_POST['transacao_parcela'] : '';
$transacao_tipo = (isset($_POST['transacao_tipo'])) ? $_POST['transacao_tipo'] : '';
$transacao_repasse = 0;
$api_id = (isset($_POST['api_id'])) ? $_POST['api_id'] : '';
$api_status = (isset($_POST['api_status'])) ? $_POST['api_status'] : '';
$api_tipo_cobranca = (isset($_POST['api_tipo_cobranca'])) ? $_POST['api_tipo_cobranca'] : '';
$api_cobranca_url = (isset($_POST['api_cobranca_url'])) ? $_POST['api_cobranca_url'] : '';
$api_operacao = (isset($_POST['api_operacao'])) ? $_POST['api_operacao'] : '';
$api_excluir = (isset($_POST['api_excluir'])) ? $_POST['api_excluir'] : '';
$api_id_cobranca = (isset($_POST['api_id_cobranca'])) ? $_POST['api_id_cobranca'] : '';
$api_descricao = (isset($_POST['api_descricao'])) ? $_POST['api_descricao'] : '';

if($api_excluir){
  mysql_query("UPDATE sys_vendas_transacoes_seg SET api_status = 'DELETED' WHERE api_id = '$api_id_cobranca'") or die (mysql_error());
}


/* http://ajuda.asaas.com/9-cobrancas-via-cartao-de-credito/quais-sao-as-taxas-de-cartao
As taxas nas cobranças de cartão de crédito, são de R$0,49 (taxa fixa) + 3,99% referente ao valor da cobrança. Caso seja uma cobrança parcelada, a taxa será aplicada no valor de cada parcela.
*/

//$total_transacao_valor = ($transacao_valor * $transacao_parcela);
//$total_juros_parcelas = 0.49 + ($transacao_valor / 100 * 3.99) * $transacao_parcela;
//$total_valor_recebido = $total_transacao_valor - $total_juros_parcelas;
//echo $total_valor_recebido = round($total_transacao_valor - $total_juros_parcelas, 2);

$select_id_venda = mysql_query("
	SELECT transacao_id 
	FROM sys_vendas_transacoes_seg 
	WHERE transacao_id LIKE '{$vendas_id}_%_{$transacao_parcela}_{$transacao_tipo}'") or die (mysql_error());

//var_dump(mysql_num_rows($select_id_venda));

if(mysql_num_rows($select_id_venda) > 0){

  echo 'ja existe';

}else{

  for($i = 0; $i < $transacao_parcela; $i++)
  {

    $date = date("Y-m-d");
    $date = strtotime(date("Y-m-d", strtotime($date))." +$i month" );
    $date_d = date("m/Y",$date);

    $values[] = "(
    '{$vendas_id}_{$date_d}_{$transacao_parcela}_{$transacao_tipo}', '$vendas_id', '$transacao_proposta','$transacao_valor[$i]', '$transacao_data_importacao', '$transacao_data', '$date_d', '".($i+1)."', '$transacao_tipo', '$transacao_repasse', '$api_id[$i]', '$api_status[$i]', '$api_tipo_cobranca[$i]', '$api_cobranca_url[$i]', '$api_operacao', '$api_descricao')";        

  }

  $values = implode(",",$values);

  //var_dump($values);
  //die();

  $insere_id_venda = mysql_query("INSERT INTO sys_vendas_transacoes_seg (
   transacao_id, 
   transacao_id_venda, 
   transacao_proposta, 
   transacao_valor, 
   transacao_data_importacao, 
   transacao_data, 
   transacao_mes, 
   transacao_parcela, 
   transacao_tipo, 
   transacao_repasse, 
   api_id, 
   api_status, 
   api_tipo_cobranca, 
   api_cobranca_url, 
   api_operacao, 
   api_descricao) VALUES {$values}") or die (mysql_error());

  

  if($insere_id_venda){
    echo 'Registro Inserido com Sucesso';
  }else{
    var_dump($insere_id_venda);
  }

}



?>