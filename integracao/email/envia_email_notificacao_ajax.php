<?php
$link = "https://conecta.auxiliadorapredial.com.br/";
// Variável que junta os valores acima e monta o corpo do email
// $email_destinatario_email = "mauricio@update.net.br";

require_once("../email/phpmailer/class.phpmailer.php");

define('GUSER', 'intranet.conecta@auxiliadorapredial.com.br');	// <-- Insira aqui o seu GMail
define('GPWD', 'aux!@2022');		// <-- Insira aqui a senha do seu GMail

function smtpmailer_notifica($para, $de, $de_nome, $email_assunto, $corpo) {
	global $error;
	$mail = new PHPMailer();
	$mail->CharSet = 'UTF-8';
	$mail->IsSMTP();		// Ativar SMTP
	$mail->SMTPDebug = 0;		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
	$mail->SMTPAuth = true;		// Autenticação ativada
	$mail->SMTPSecure = 'tls';	// SSL REQUERIDO pelo GMail
	$mail->Host = 'smtp-relay.gmail.com';	// SMTP utilizado
	$mail->Port = 587;  		// A porta 587 deverá estar aberta em seu servidor
	$mail->Username = GUSER;
	$mail->Password = GPWD;
	$mail->SetFrom($de, $de_nome);
	$mail->Subject = $email_assunto;
	$mail->Body = $corpo;
	$mail->IsHTML(true);       // <=== call IsHTML() after $mail->Body has been set.
	$mail->AddAddress($para);
	if($mail->Send()) {		
		//$error = 'Mensagem enviada!';
		return true;
	} else {
		$error = 'Mail error: '.$mail->ErrorInfo;
		return false;
	}
}

if (smtpmailer_notifica($email_destinatario_email, 'intranet.conecta@auxiliadorapredial.com.br', 'Conecta', $email_assunto, $email_corpo)) {
	echo "<br>Notificação enviada para o e-mail: ".$email_destinatario_email."!";
}

if (!empty($error)) {
	$alerta_json = "{
		email_destinatario_email: \"$email_destinatario_email\",
		email_assunto: \"$email_assunto\",
		email_corpo: \"$email_corpo\"
	}";
	
	echo "<br>Mensagem de notificação não enviada!";
	$sql_insert = "INSERT INTO sis_alertas (
		alerta_criador,
		alerta_tipo,
		alerta_data,
		alerta_acao,
		alerta_url_origem,
		alerta_url_acao,
		alerta_json,
		alerta_erro) 
	VALUES (
		'".$_POST['user_id']."',
		'1',
		NOW(),
		'Reenviar email',
		'".$_SERVER['PHP_SELF']."',
		'envia_email_notificacao.php',
		'".$alerta_json."',
		'".$error."'
	);";
	if (mysqli_query($con,$sql_insert)){
		$alerta_id  = mysqli_insert_id($con);
		echo "<br>Alerta registrado.";
	} else {
		echo $sql_insert."<br>";
		die('Error: ' . mysqli_error($con));
	}
}
//echo $Vai; 
?>