<?php
date_default_timezone_set('America/Sao_Paulo');

//Este arquivo está em um include do arquivo sistema/vendas/atualiza.php
//Informações advindas de atualiza.php
//$vendas_id
//$vendas_obs

include("sistema/connect.php");

$result_email = mysql_query("SELECT email FROM jos_users WHERE id = '" . $vendas_consultor . "';") or die(mysql_error());
if(mysql_num_rows($result_email))
{
	$row_email = mysql_fetch_assoc($result_email);
	$consultor_email = $row_email['email'];

	$result_dados = mysql_query("SELECT clients_cpf, tipo_nome, status_nm
							   FROM sys_vendas						   
							   INNER JOIN sys_vendas_tipos ON sys_vendas.vendas_tipo_contrato = sys_vendas_tipos.tipo_id
							   INNER JOIN sys_vendas_status ON sys_vendas.vendas_status = sys_vendas_status.status_id						   
							   WHERE vendas_id = '" . $vendas_id . "';") or die(mysql_error());
	$row_dados = mysql_fetch_assoc($result_dados);

	include("sistema/connect_db02.php");
	$result_cliente_nome = mysql_query("SELECT cliente_nome FROM sys_inss_clientes WHERE cliente_cpf = '" . $row_dados['clients_cpf'] . "';") or die(mysql_error());
	$row_cliente_nome = mysql_fetch_assoc($result_cliente_nome);

	
	$cliente_cpf = $row_dados['clients_cpf'];
	$cliente_nome = $row_cliente_nome['cliente_nome'];
	$tipo_contrato_nome = $row_dados['tipo_nome'];
	$status_nm = $row_dados['status_nm'];

	/*
	###########################################################
	Envio com autenticação.
	###########################################################
	*/

	$remetente_email = "acionamento@grupofortune.com.br";
	$remetente_senha = "Suporte2015";

	$de = $remetente_email;
	$de_nome = "Acionamento";
	$para = $consultor_email;
	$assunto = "Notificação de alteração na venda cód. ".$vendas_id;

	$corpo = "Uma alteração foi realizada na sua venda.<br>".
			 "Código da venda: ".$vendas_id."<br>".
			 "Nome do Cliente: ".$cliente_nome."<br>".
			 "CPF: ".$cliente_cpf."<br>".
			 "Tipo de Contrato: ".$tipo_contrato_nome."<br>".
			 "Status da venda: ".$status_nm."<br>".
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

	// Insira abaixo o email que irá receber a mensagem, o email que irá enviar (o mesmo da variável GUSER), 
	//o nome do email que envia a mensagem, o Assunto da mensagem e por último a variável com o corpo do email.
	//smtpmailer($para, $de, $de_nome, $assunto, $corpo)
	if ( smtpmailer($para, $de, $de_nome, $assunto, $corpo) )
	{	
		echo "<br>Notificação de alteração enviada ao consultor.<br>";
	}

	if (!empty($error)){ echo $error; }

}else{
	echo "<br>Notificação não enviada. Email do consultor não localizado.<br>";
}
?>