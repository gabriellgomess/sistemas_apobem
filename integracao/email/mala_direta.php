<?php
$con = mysqli_connect("localhost", "root", "Theredpil2001", "sistema");
if (!$con) {
	die('*Could not connect: ' . mysqli_error($con));
}
mysqli_query($con, "SET NAMES 'utf8'");
mysqli_query($con, "SET CHARACTER SET utf8");
mysqli_query($con, 'SET character_set_connection=utf8');
mysqli_query($con, 'SET character_set_client=utf8');
mysqli_query($con, 'SET character_set_results=utf8');

$data = date('Y-m-d', strtotime('-7 days'));

if ($_POST["duplicata_cnpj"]) {
	$filter_sql = " AND duplicata_cnpj = " . $_POST["duplicata_cnpj"] . " ";
} else {
	$filter_sql = " AND duplicata_email_enviado = 0 AND duplicata_email_fornecedor IS NOT NULL AND duplicata_email_fornecedor != '' ";
}

$result = mysqli_query($con, "SELECT duplicata_id, duplicata_email_fornecedor, duplicata_fornecedor, name, duplicata_sacado_ancora_cod, duplicata_cnpj 
								FROM sis_duplicatas 
								INNER JOIN igw31_users ON sis_duplicatas.duplicata_sacado_ancora_cod = igw31_users.id 
								WHERE 1 $filter_sql ORDER BY duplicata_id DESC LIMIT 0, 1;") or die(mysqli_error($con));
							
$num_rows = mysqli_num_rows($result);




if ($num_rows > 0) {
	$row = mysqli_fetch_array($result);

	$duplicata_cnpj_cedente = $row['duplicata_cnpj'];


	$duplicata_id = $row["duplicata_id"];
	$email_destinatario_email = $row["duplicata_email_fornecedor"];
	// $email_destinatario_email =  "murilo.bayer@monbank.net";
	$email_destinatario_nome = $row['duplicata_fornecedor'];
	$duplicata_sacado_ancora_cod = $row['duplicata_sacado_ancora_cod'];
	$duplicata_cnpj = $row['duplicata_cnpj'];
	$url_key = base64_encode($duplicata_cnpj . "_" . $duplicata_sacado_ancora_cod);
	$email_assunto = "Antecipação de Recebíveis";
	$email_sacado_nome = $row["name"];

	$sql_valores_disponiveis = "SELECT users.name, users.id as sacado_id, cedente.id, SUM(duplicata_valor) AS TOTAL FROM sis_duplicatas as duplicatas INNER JOIN igw31_users as users ON duplicatas.duplicata_sacado_ancora_cod = users.id LEFT OUTER JOIN igw31_users as cedente ON duplicatas.duplicata_cnpj = cedente.cliente_cpf WHERE 1 AND duplicatas.duplicata_cnpj = $duplicata_cnpj_cedente AND `duplicata_vencimento` > CURDATE() GROUP BY duplicata_sacado_ancora_cod";

	$result_sql_valores_disponiveis = mysqli_query($con, $sql_valores_disponiveis);

	$frame_valores_disponiveis = "<div style= 'color: black'><h2>Valores disponíveis para antecipar</h2> <div class='container-deshboard-valores-a-antecipar' style='flex-direction: column; justify-content: space-between;'>

	<div>

		<div class='deshboard_container_propostas_andamento' style='width: 500px;'>

			<div class='header-table-propostas-andamento' style='background-color: #fff5e9; box-shadow: 0px 4px 4px -4px transparent !important; border-bottom: 1px solid transparent !important;  position: sticky; top: 0;'>

				<div class='linha-valores' style='min-width: 340px; display: flex'>

					<div class='coluna-valores' style='width: 79%; padding-left: 10px; font-size: 12px;'>

						<div style='cursor: inherit; color: #9e8a42;'>Sacado</div>

					</div>

					<div class='coluna-valores' style='width: 24%; font-size: 12px; padding-left: 10px; text-align: start;'>

						<div style='cursor: inherit; color: #9e8a42;'>Valor</div>

					</div>

				</div>

			</div>
	";
	$valor_total_duplicatas = 0;

	while ($row = mysqli_fetch_array($result_sql_valores_disponiveis, MYSQLI_ASSOC)) {

		$valor_total_duplicatas = $valor_total_duplicatas + $row['TOTAL'];

		$name_sacado = $row['name'];

		$total_duplicatas =  number_format($row['TOTAL'], 2, ',', '.');

		$frame_valores_disponiveis = $frame_valores_disponiveis .
			"<div class='header-table-propostas-andamento' style='display: flex; justify-content: space-between; min-width: 340px; border-bottom: 1px solid #e5dec4; width: 500px;'>

		<div style='display: flex; align-items: center; font-size: 12px; width: 79%;'>

			<span style='margin-left: 5px;'> $name_sacado </span>

		</div>

		<div style='display: flex; align-items: center; font-size: 12px; width: 24%; '>

			<span style='margin-left: 5px;'>R$ $total_duplicatas </span>

		</div>

	</div>";

		//<?php $valor_total_duplicatas = $valor_total_duplicatas + $row['TOTAL']; 

	}
	$valor_total_duplicatas = number_format($valor_total_duplicatas, 2, ',', '.');

	$frame_valores_disponiveis = $frame_valores_disponiveis .
		" </div>

		</div>

		<div style='display: flex; justify-content: end; font-size: 12px; font-weight: bold; cursor: pointer;' >

			Total: R$ $valor_total_duplicatas

		</div>

		</div><div> <br>";
	// echo $frame_valores_disponiveis;

	$email_corpo = "
					Prezado " . $email_destinatario_nome . ",<br><br>
					$frame_valores_disponiveis
					Acesse o nosso site <a href=\"http://monfor.com.br/sistema/?key=" . $url_key . "\">monfor.com.br</a> e peça agora a sua proposta de antecipação.<br><br>
					<img src='http://monfor.com.br/sistema/images/monfor.png' alt='Logotipo' style='display:block;width:auto;max-width:200px;height:auto;max-height:100px;outline:none;text-decoration:none' width='200' height='85' data-bit='iit' tabindex='0'><br>
					MONFOR do MONBANK<br>
					Antecipando recebíveis para resolver a vida de todos.<br>
					";

	require_once("phpmailer/class.phpmailer.php");

	define('GUSER', 'sistema@monbank.net');	// <-- Insira aqui o seu GMail
	define('GPWD', 'Sis@mon2023');		// <-- Insira aqui a senha do seu GMail

	function smtpmailer_notifica($para, $de, $de_nome, $email_assunto, $corpo)
	{
		global $error;
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->IsSMTP();		// Ativar SMTP
		$mail->SMTPDebug = 1;		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
		$mail->SMTPAuth = true;		// Autenticação ativada
		$mail->SMTPSecure = 'tls';	// SSL REQUERIDO pelo GMail
		$mail->Host = 'smtpi.kinghost.net';	// SMTP utilizado
		$mail->Port = 587;  		// A porta 587 deverá estar aberta em seu servidor
		$mail->Username = GUSER;
		$mail->Password = GPWD;
		$mail->SetFrom($de, $de_nome);
		$mail->Subject = $email_assunto;
		$mail->Body = $corpo;
		$mail->IsHTML(true);       // <=== call IsHTML() after $mail->Body has been set.
		$mail->AddAddress($para);
		if ($mail->Send()) {
			$error = 'Mensagem enviada!';
			return true;
		} else {
			$error = 'Mail error: ' . $mail->ErrorInfo;
			return false;
		}
	}

	if (smtpmailer_notifica($email_destinatario_email, 'sistema@monbank.net', 'MonFor do MonBank', $email_assunto, $email_corpo)) {
		// echo "<br>Notificação enviada para o e-mail: " . $email_destinatario_email . "!";
		echo "E-mail enviado com sucesso.";
	} else {
		echo "Falha ao enviar e-mail.";
	}

	if ($error) {
		// echo "Erro no envio do e-mail: " . $error . "<br>";
	}

	$update_solicitacao = "UPDATE sis_duplicatas SET duplicata_email_enviado = 1, duplicata_email_data = NOW() WHERE duplicata_sacado_ancora_cod = '" . $duplicata_sacado_ancora_cod . "' AND duplicata_cnpj = '" . $duplicata_cnpj . "';";
	if (mysqli_query($con, $update_solicitacao)) {
		// echo "<br>Duplicata atualizada com sucesso.";
	} else {
		die('Error: ' . mysqli_error($con));
	}
	// echo "<br>";
	// echo "duplicata_id: " . $duplicata_id . ", ";
	// echo "email_destinatario_email: " . $email_destinatario_email . ", ";
	// echo "email_assunto: " . $email_assunto . ", ";
	// echo "email_sacado_nome: " . $email_sacado_nome . ", ";
	// echo "email_destinatario_nome: " . $email_destinatario_nome . "<br>";
	sleep(10);
} else {
	// echo "Nenhuma Duplicata Pendente!<br>";
}
