<?php
ob_start(); // Capturar qualquer output indesejado
date_default_timezone_set('America/Sao_Paulo');
include("../../connect_seguro.php");

// Debug: Verificar se o script está sendo executado
error_log("DEBUG: insere_venda_novo_cliente.php iniciado - " . date('Y-m-d H:i:s'));
error_log("DEBUG: Dados GET recebidos: " . print_r($_GET, true));

$cliente_cpf = $_GET["clients_cpf"];
$clients_cpf = $_GET["clients_cpf"];
include("../../cliente/espelha_confere.php");
if ($row_espelha_confere["total"]) {

   include("../../connect_db02.php");
   include("../../utf8.php");

   include("../../cliente/espelha_existente.php");

   include("../../connect_seguro.php");
   include("../../utf8.php");

   include("../../cliente/espelha_atualiza.php");
} else {

   include("../../connect_db02.php");
   include("../../utf8.php");

   include("../../cliente/espelha.php");

   include("../../connect_seguro.php");
   include("../../utf8.php");

   include("../../cliente/espelha_insere.php");
}


if ($_GET['vendas_consultor']) {
   $vendas_consultor = $_GET['vendas_consultor'];
} else {
   $vendas_consultor = $_GET['user_id'];
}

$vendas_user = $_GET['username'];
$vendas_apolice = $_GET["vendas_apolice"];
$forma_envio_kitcert = $_GET["forma_envio_kitcert"];

$possui_instagram = $_GET['possui_instagram'];
if ($possui_instagram != "") {
   $sql_insert_instagram = "INSERT INTO sys_instagram (cliente_cpf, possui_instagram) 
								 VALUES ('$cliente_cpf', '$possui_instagram') 
								 ON DUPLICATE KEY UPDATE possui_instagram = '$possui_instagram';";
   mysqli_query($con, $sql_insert_instagram);
}

$sql_apolice = "SELECT apolice_valor, apolice_tipo, apolice_status, apolice_cms_vendedor
					FROM sys_vendas_apolices
					WHERE apolice_id = '" . $vendas_apolice . "';";

$result_apolice = mysqli_query($con, $sql_apolice) or die(mysqli_error($con));
$row_apolice = mysqli_fetch_array($result_apolice);

// if ($_GET["apolice_valor"]) {
// 	$vendas_valor = moedaBR_to_modaDB($_GET["apolice_valor"]);
// } else {
// 	$vendas_valor = $row_apolice['apolice_valor'];
// }

$vendas_valor = $_GET["vendas_valor"];

$vendas_comissao_vendedor = (($vendas_valor * $row_apolice['apolice_cms_vendedor']) / 100);

if ($_GET["vendas_dia_desconto"]) {
   $vendas_dia_desconto = $_GET["vendas_dia_desconto"];
} else {
   $vendas_dia_desconto = "01";
}

$vendas_pgto = $_GET["vendas_pgto"];
$vendas_cartao_adm = $_GET["vendas_cartao_adm"];
$vendas_cartao_num = $_GET["vendas_cartao_num"];
$vendas_cartao_validade_mes = $_GET["vendas_cartao_validade_mes"];
$vendas_cartao_validade_ano = $_GET["vendas_cartao_validade_ano"];
$vendas_debito_banco = $_GET["vendas_debito_banco"];
$vendas_debito_ag_dig = $_GET["vendas_debito_ag_dig"];
$vendas_debito_ag = $_GET["vendas_debito_ag"];
$vendas_debito_cc = $_GET["vendas_debito_cc"];
$vendas_debito_cc_dig = $_GET["vendas_debito_cc_dig"];
$vendas_debito_banco_2 = $_GET["vendas_debito_banco_2"];
$vendas_debito_ag_2 = $_GET["vendas_debito_ag_2"];
$vendas_debito_cc_2 = $_GET["vendas_debito_cc_2"];
$vendas_debito_banco_3 = $_GET["vendas_debito_banco_3"];
$vendas_debito_ag_3 = $_GET["vendas_debito_ag_3"];
$vendas_debito_cc_3 = $_GET["vendas_debito_cc_3"];
$vendas_banco = $_GET["vendas_banco"];
$vendas_orgao = $_GET["vendas_orgao"];

$sql_busca_orgao = "SELECT orgao_nome FROM sys_orgaos WHERE orgao_id = $vendas_orgao";
$result_busca_orgao = mysqli_query($con, $sql_busca_orgao) or die(mysqli_error($con));
$row_busca_orgao = mysqli_fetch_assoc($result_busca_orgao);
$vendas_orgao = $row_busca_orgao['orgao_nome'];

$vendas_cartao_band = $_GET["vendas_cartao_band"];
$vendas_inicio_auditoria = $_GET["tempo_inicio_auditoria"];
$vendas_email = $_GET["vendas_email"];

if ($_GET["vendas_vencimento_fatura"]) {
   $vendas_vencimento_fatura = $_GET["vendas_vencimento_fatura"];
}

if ($_GET["vendas_status"]) {
   $vendas_status = $_GET["vendas_status"];
} else {
   $vendas_status = $row_apolice['apolice_status'];
}

if (date('l') == "Saturday") {
   if (date("H:i:s") <= "13:05") {
      $vendas_turno = "3";
   } else {
      $vendas_turno = "4";
   }
} else {
   if (date("H:i:s") <= "15:05") {
      $vendas_turno = "1";
   } else {
      $vendas_turno = "2";
   }
}

$vendas_obs = mysqli_real_escape_string($con, $_GET["vendas_obs"]);

if ($_GET['vendas_dia_venda']) {
   $vendas_dia_venda = $_GET['vendas_dia_venda'] . " " . date("H:i:s");
} else {
   $vendas_dia_venda = date("Y-m-d H:i:s");
}

$vendas_telefone = $_GET["vendas_telefone"]; //celular
$vendas_telefone2 = $_GET["vendas_telefone2"]; //telefone

//para o cadastro se celular do cliente estiver vazio
if (empty($vendas_telefone)) {
   $response_data = [
      "status" => "error",
      "msg" => "Favor preencher celular do cliente"
   ];

   // Limpar qualquer output anterior e enviar apenas o JSON de erro
   ob_clean();
   header('Content-Type: application/json');
   http_response_code(422); //unprocessable entity
   echo json_encode($response_data);
   return;
}


$sql = "INSERT INTO `sistema`.`sys_vendas_seguros` (`vendas_id`, 
      `cliente_cpf`, 
      `vendas_consultor`, 
      `vendas_apolice`, 
      `vendas_valor`, 
      `vendas_comissao_vendedor`, 
      `vendas_dia_desconto`, 
      `vendas_pgto`, 
      `vendas_cartao_adm`,
      `vendas_cartao_band`,
      `vendas_cartao_num`, 
      `vendas_cartao_validade_mes`, 
      `vendas_cartao_validade_ano`, 
      `vendas_vencimento_fatura`,
      `vendas_ben`, 
      `vendas_parent`, 
      `vendas_debito_banco`, 
      `vendas_debito_ag`, 
      `vendas_debito_ag_dig`,
      `vendas_debito_cc`, 
      `vendas_debito_cc_dig`, 
      `vendas_debito_banco_2`, 
      `vendas_debito_ag_2`, 
      `vendas_debito_cc_2`, 
      `vendas_debito_banco_3`, 
      `vendas_debito_ag_3`, 
      `vendas_debito_cc_3`, 
      `vendas_banco`, 
      `vendas_dia_venda`,
      `vendas_status`, 
      `vendas_turno`, 
      `vendas_user`, 
      `vendas_orgao`, 
      `vendas_telefone`, 
      `vendas_telefone2`, 
      `vendas_obs`,
      `vendas_inicio_auditoria`,
      `forma_envio_kitcert`) 
      VALUES (NULL, 
      '$cliente_cpf',
      '$vendas_consultor',
      '$vendas_apolice',
      '$vendas_valor',
      '$vendas_comissao_vendedor',
      '$vendas_dia_desconto',
      '$vendas_pgto',
      '$vendas_cartao_adm',
      '$vendas_cartao_band',
      '$vendas_cartao_num',
      '$vendas_cartao_validade_mes',
      '$vendas_cartao_validade_ano',
      '$vendas_vencimento_fatura',
      '$vendas_ben',
      '$vendas_parent',
      '$vendas_debito_banco',
      '$vendas_debito_ag',
      '$vendas_debito_ag_dig',
      '$vendas_debito_cc',
      '$vendas_debito_cc_dig',
      '$vendas_debito_banco_2',
      '$vendas_debito_ag_2',
      '$vendas_debito_cc_2',
      '$vendas_debito_banco_3',
      '$vendas_debito_ag_3',
      '$vendas_debito_cc_3',
      '$vendas_banco',
      '$vendas_dia_venda',
      '$vendas_status',
      '$vendas_turno',
      '$vendas_user',
      '$vendas_orgao',
      '$vendas_telefone',
      '$vendas_telefone2',
      '$vendas_obs',
      '$vendas_inicio_auditoria,',
      '$forma_envio_kitcert');";

if (mysqli_query($con, $sql)) {
   $vendas_id = mysqli_insert_id($con);

   // if ($vendas_banco == 11) {
   // 	$update_proposta_query = "UPDATE sys_vendas_transacoes_tef
   //       SET vendas_proposta=vendas_id 
   //       WHERE vendas_id =" . $vendas_id;

   //    $result_proposta = mysqli_query($con, $update_proposta_query);
   // }

   $order_id = $_GET['order_id'];

   // Debug: Verificar se chegou até aqui
   error_log("DEBUG: Chegou na vinculação - vendas_id: $vendas_id, order_id: $order_id");

   // Verificar se existe transação com este NIT
   $check_query = "SELECT * FROM sys_vendas_transacoes_tef WHERE transacao_nit = '" . $order_id . "'";
   $check_result = mysqli_query($con, $check_query);
   error_log("DEBUG: Query de verificação: " . $check_query);
   error_log("DEBUG: Transações encontradas: " . mysqli_num_rows($check_result));

   if (mysqli_num_rows($check_result) > 0) {
      $row_check = mysqli_fetch_assoc($check_result);
      error_log("DEBUG: Transação atual - ID: " . $row_check['transacao_id'] . ", Status: " . $row_check['transacao_status'] . ", Venda_ID: " . $row_check['transacao_venda_id']);
   }

   // if ($_GET['token_transaction']) {
   if (!empty($order_id)) {
      $update_transaction_query = "UPDATE sys_vendas_transacoes_tef
         SET transacao_venda_id = '" . $vendas_id . "'
         WHERE transacao_nit = '" . $order_id . "'";

      error_log("DEBUG: Query de atualização: " . $update_transaction_query);
      $result_transaction = mysqli_query($con, $update_transaction_query);

      // Debug: mostrar resultado da vinculação
      if ($result_transaction) {
         $affected_rows = mysqli_affected_rows($con);
         error_log("DEBUG: Query executada com sucesso! Linhas afetadas: " . $affected_rows);

         if ($affected_rows == 0) {
            error_log("DEBUG: ATENÇÃO: Nenhuma linha foi atualizada! Verifique se existe transação com NIT: " . $order_id);
         }
      } else {
         error_log("DEBUG: Erro ao executar query: " . mysqli_error($con));
      }
   } else {
      error_log("DEBUG: order_id está vazio, não executando vinculação");
   }
   // }

   if ($_GET['check_card_code'] == '51') {
      $vendas_obs .= " (Cartao com retorno 51, saldo insuficiente)";
      $sql_historico = "INSERT INTO sys_vendas_registros_seg (
      `vendas_id`, 
      `registro_usuario`, 
      `registro_obs`, 
      `registro_status`, 
      `registro_data`, 
      `registro_contrato_fisico`) 
      VALUES (
      '$vendas_id',
      '$vendas_user',
      '$vendas_obs',
      '$vendas_status',
      '$vendas_dia_venda',
      '0');";

      if (mysqli_query($con, $sql_historico)) {
         $registro_id = mysqli_insert_id($con);
      } else {
         die('Error: ' . mysqli_error($con));
      }
   }

   $success = "success";
} else {
   die('Error: ' . mysqli_error($con));
}
if ($_GET["ben_nome"]) {
   $values = "";
   for ($i = 0; $i < sizeof($_GET['ben_nome']); $i++) {
      // $ben_nasc = implode(preg_match("~\/~", $_GET["ben_nasc"][$i]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["ben_nasc"][$i]) == 0 ? "-" : "/", $_GET["ben_nasc"][$i])));

      $ben_nasc = $_GET["ben_nasc"][$i];

      if (strpos($_GET["ben_nasc"][$i], "/") !== false) {
         $ben_nasc = implode("-", array_reverse(explode("/", $_GET["ben_nasc"][$i])));
      }

      $ben_perc = $_GET["ben_perc"][$i];
      if (strpos($ben_perc, ".")) {
         $ben_perc = substr_replace($ben_perc, '', strpos($ben_perc, "."), 1);
      }
      if (!strpos($ben_perc, ".") && (strpos($ben_perc, ","))) {
         $ben_perc = substr_replace($ben_perc, '.', strpos($ben_perc, ","), 1);
      }

      $values .= "(NULL,";
      $values .= "'" . $vendas_id . "',";
      $values .= "'" . $_GET['ben_nome'][$i] . "',";
      $values .= "'" . $ben_nasc . "',";
      $values .= "'" . $_GET["ben_parent"][$i] . "',";
      $values .= "'" . $ben_perc . "')";

      if ($i != sizeof($_GET['ben_nome']) - 1) {
         $values .= ",";
      }
   }

   $sql = "INSERT INTO `sistema`.`sys_vendas_ben` (`ben_id`, 
      `vendas_id`, 
      `ben_nome`, 
      `ben_nasc`, 
      `ben_parent`, 
      `ben_perc`)
      VALUES " . $values . ";";

   if (mysqli_query($con, $sql)) {
      $success = "success";
   } else {
      die('Error: ' . mysqli_error($con));
   }
}
if ($_GET["dependente_nome"]) {
   $values = "";
   for ($i = 0; $i < sizeof($_GET['dependente_nome']); $i++) {
      $dependente_cpf = str_replace(".", "", str_replace("-", "", $_GET['dependente_cpf'][$i]));
      $dependente_celular = str_replace("(", "", str_replace(")", "", str_replace("-", "", str_replace(" ", "", $_GET['dependente_celular'][$i]))));

      $values .= "(NULL,";
      $values .= "'$vendas_id',";
      $values .= "'" . $_GET['dependente_nome'][$i] . "',";
      $values .= "'" . $dependente_cpf . "',";
      $values .= "'" . $_GET['dependente_nascimento'][$i] . "',";
      $values .= "'" . $dependente_celular . "',";
      $values .= "'" . $_GET['dependente_sexo'][$i] . "',";
      $values .= "'" . $_GET['dependente_email'][$i] . "',";
      $values .= "'" . $_GET['dependente_parentesco'][$i] . "')";

      if ($i != sizeof($_GET['dependente_nome']) - 1) {
         $values .= ",";
      }
   }

   $sql = "INSERT INTO sys_vendas_dependentes 
         (dependente_id, 
         vendas_id, 
         dependente_nome, 
         dependente_cpf, 
         dependente_nascimento, 
         dependente_celular,
         dependente_sexo,
         dependente_email,
         dependente_parentesco)
         VALUES $values;";

   if (mysqli_query($con, $sql)) {
      $success = "success";
   } else {
      die('Error: ' . mysqli_error($con));
   }
}

if ($vendas_orgao != "Exercito") {
   // if($vendas_orgao != "2"){
   $query = mysqli_query($con, "UPDATE sys_inss_clientes SET cliente_venda='1', cliente_email = '$vendas_email' WHERE cliente_cpf = '$cliente_cpf' ") or die(mysqli_error($con));
   $success = "success";
}

//echo $vendas_id;
// $new_location = "https://zyonsistemas.com.br/sistema/sistema/cliente/novo_cliente/response_cad.php?&vendas_id=$vendas_id&cliente_cpf=$cliente_cpf";
$response_data = array(
   "status" => "success",
   'cpf' => $cliente_cpf,
   'vendas_id' => $vendas_id,
   "msg" => "Venda de seguro cadastrada com sucesso!",
);

// Debug: Verificar resposta antes de enviar
error_log("DEBUG: Resposta que será enviada: " . print_r($response_data, true));

// Converta o array em JSON
$response_json = json_encode($response_data);

// Debug: Verificar JSON final
error_log("DEBUG: JSON final: " . $response_json);

// Limpar qualquer output anterior e enviar apenas o JSON
ob_clean();
header('Content-Type: application/json');
echo $response_json;
?>


<?php
// FUNÇÃO AJUSTA DATA DO FORMULÁRIO PARA O FORMATO DO BANCO DE DADOS
function dataBR_to_dataDB($dataBr)
{
   return implode("-", array_reverse(explode("-", str_replace("/", "-", $dataBr))));
}

// FUNÇÃO AJUSTA VALOR MOEDA DO FORMULÁRIO PARA O FORMATO DO BANCO DE DADOS
function moedaBR_to_modaDB($moedaBr)
{
   return str_replace(",", ".", str_replace(".", "", $moedaBr));
}

?>
