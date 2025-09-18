<?php
$remetente_email = "acionamento@grupofortune.com.br";
$remetente_senha = "Suporte2015";

$de = $remetente_email;
$de_nome = "Integrador Automático";
$para = "financeiro.seguros@grupofortune.com.br";
$assunto = "Integrador. ".$cont." Transações processadas!";
$data_execucao = date('d/m/Y H:i:s');
$corpo = "Registro de execução do Intergrador automático (consulta_schedule).<br>".
		 "ID da importação: ".$importacao_id."<br>".
		 "Nome da importação: ".$importacao_nome."<br>".
		 "Data da execução: ".$data_execucao."<br>".
		 "Data consultada: ".$data."<br>".
		 "URL da consulta: ".$url."<br>".
		 "Transações: ".$cont."<br>".
		 "Observação da Venda: ".$vendas_obs;

define('GUSER', $remetente_email);	// <-- Insira aqui o seu GMail
define('GPWD', $remetente_senha);		// <-- Insira aqui a senha do seu GMail

require_once("sistema/utils/phpmailer/class.phpmailer.php");
function smtpmailer($para, $de, $de_nome, $assunto, $corpo)
{ 
	global $error;
	$mail = new PHPMailer();
	$mail->CharSet = 'UTF-8';
	$mail->IsSMTP();		// Ativar SMTP
	$mail->SMTPDebug = 0;		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
	$mail->SMTPAuth = true;		// Autenticação ativada
	$mail->SMTPSecure = 'ssl';	// SSL REQUERIDO pelo GMail
	$mail->Host = 'smtp.gmail.com';	// SMTP utilizado
	$mail->Port = 465;  		// A porta 465 deverá estar aberta em seu servidor (outra porta possível *587*)
	$mail->Username = GUSER;
	$mail->Password = GPWD;	
	$mail->SetFrom($de, $de_nome);
	$mail->AddReplyTo( $remetente_email, 'Grupo Fortune');
	$mail->Subject = $assunto;
	$mail->Body = $corpo;
	$mail->IsHTML(true); // Descomentar caso o email enviado seja escrito em html	
	//$mail->AddCC($value);
	$mail->AddAddress($para);


	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		return false;
	} else {
		$error = 'sucess';
		return true;
	}
}
if ( smtpmailer($para, $de, $de_nome, $assunto, $corpo) )
{	
	echo "<br>Notificação de email enviada.<br>";
}

if (!empty($error)){ echo $error; }
?>