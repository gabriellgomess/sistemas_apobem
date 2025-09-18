<?php

	$token = 'EsearR31234fpssa0vfc9o';

	if ($_GET['token'] == $token) {

		include("../../connect_seguro.php");

		if ($_POST['nit'] || $_POST['pedido'] || $_POST['nsu'] || $_POST['codigoLoja'] || $_POST['seid'] || $_POST['merchant_data'] || $_POST['sid']) {

			if ($_POST['sid']) {

				$update_transaction_query ="UPDATE sys_vendas_transacoes_tef
				SET transacao_agendamento_seid = '".$_POST['seid']."'
				WHERE transacao_agendamento_sid = '".$_POST['sid']."'";

				$result_transaction = mysql_query($update_transaction_query);

				if ($result_transaction) {
					echo json_encode('OK');
				} else {
					header("HTTP/1.1 403 Forbidden");
					echo json_encode('Pagamento não encontrado!');
				}
			}

		} else {
			header("HTTP/1.1 403 Forbidden");
    		echo json_encode('Pagamento não encontrado!');
    		exit;
		}
	} else {
		header("HTTP/1.1 401 Unauthorized");
		echo json_encode('Não autorizado');
		exit;
	}

?>