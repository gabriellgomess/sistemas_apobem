<?php
$link = "http://portal.grupofortune.com.br/";
// Variável que junta os valores acima e monta o corpo do email

$result_destinatario = mysql_query("SELECT email, name FROM jos_users WHERE id = " . $vendas_consultor . ";")
or die(mysql_error());
$row_destinatario = mysql_fetch_array( $result_destinatario );

$destinatario = $row_destinatario['email'];
$destinatario_nome = $row_destinatario['nome'];
$assunto = "Proposta ".$vendas_id." Atualizada";

//$destinatario = "lucas.pinzon@grupofortune.com.br";

$Vai 		= "
<div marginwidth='0' marginheight='0'>
    <center>
        <table border='0' cellpadding='0' cellspacing='0' height='100%' width='100%' style='background-color:#fafafa'>
            <tbody>
                <tr>
                    <td align='center' valign='top'>
                        <br>
                        <table border='0' cellpadding='0' cellspacing='0' style='border:1px solid #dddddd'>
                            <tbody>
                                <tr>
                                    <td align='center' valign='top'>
                                        <table border='0' cellpadding='0' cellspacing='0'>
                                            <tbody>
                                                <tr>
                                                    <td valign='top'>
                                                        <h1 style='background:#5998ca;border-bottom:10px solid #607d8b;padding:20px;line-height:130%;color:#ddd;margin:0px;font-size:20px'>
                                                            Proposta Atualizada
                                                        </h1>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign='top' style='border-bottom:1px solid #eee;background-color:#fff;padding:15px;font-size:13px'>
                                                        Olá ".$destinatario_nome.",<br><br>Sua Proposta ".$vendas_id." foi atualizada, no Sistema da Fortune.<br><br><br>
														Dados da Proposta: 
														<div type='cite' style='background:#f5f5f5;border:0px!important;color:#555;margin:10px 0px 0px 0px;padding:10px'>
														Proposta: ".$vendas_id."<br>
														Gerente: ".$vendas_user."<br>
														Observação: ".$vendas_obs."<br>
														</div><br>
														Veja mais detalhes em: <a href='http://portal.grupofortune.com.br/' title='Portal do Agente Fortune' target='_blank'>http://portal.grupofortune.com.br/</a><br><br>
														<br>
														Atenciosamente,<br>Equipe Operacional Fortune.
                                                    </span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='center' valign='top'>
                                        <table border='0' cellpadding='10' cellspacing='0'>
                                            <tbody>
                                                <tr>
                                                    <td valign='top'>
                                                        <table border='0' cellpadding='10' cellspacing='0' width='100%'>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan='2' valign='middle'>
                                                                        <div style='color:#999;font-size:11px;text-align:center'>
                                                                            Desenvolvido por Update
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                    </td>
                </tr>
            </tbody>
        </table>
    </center>
</div>
";

require_once("sistema/email/phpmailer/class.phpmailer.php");

define('GUSER', 'portais@update.net.br');	// <-- Insira aqui o seu GMail
define('GPWD', 'Cliente2012');		// <-- Insira aqui a senha do seu GMail

function smtpmailer($para, $de, $de_nome, $assunto, $corpo) {
	global $error;
	$mail = new PHPMailer();
	$mail->CharSet = 'UTF-8';
	$mail->IsSMTP();		// Ativar SMTP
	$mail->SMTPDebug = 1;		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
	$mail->SMTPAuth = true;		// Autenticação ativada
	$mail->SMTPSecure = 'ssl';	// SSL REQUERIDO pelo GMail
	$mail->Host = 'smtp.gmail.com';	// SMTP utilizado
	$mail->Port = 465;  		// A porta 587 deverá estar aberta em seu servidor
	$mail->Username = GUSER;
	$mail->Password = GPWD;
	$mail->SetFrom($de, $de_nome);
	$mail->Subject = $assunto;
	$mail->Body = $corpo;
	$mail->IsHTML(true);       // <=== call IsHTML() after $mail->Body has been set.
	$mail->AddAddress($para);
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		return false;
	} else {
		$error = 'Mensagem enviada!';
		return true;
	}
}

 if (smtpmailer($destinatario, 'ascom@grupofortune.com.br', 'Portal do Agente Fortune', $assunto, $Vai)) {

	echo "Mensagem enviada ao Agente com sucesso, no e-mail: ".$destinatario."!";

}
if (!empty($error)) {echo $error;}
//echo $Vai; 
?>