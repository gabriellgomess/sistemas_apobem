<?php

require_once('phpmailer/src/PHPMailer.php');
require_once('phpmailer/src/SMTP.php');
require_once('phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender {
    private $mailer;
    private $fromEmail;
    private $fromName;

    public function __construct($fromEmail, $fromName, $username, $password, $host, $port, $smtpSecure = 'ssl') {
        $this->mailer = new PHPMailer();
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;

        // Server settings
        $this->mailer->isSMTP();
        $this->mailer->Host = $host;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $username;
        $this->mailer->Password = $password;
        $this->mailer->SMTPSecure = $smtpSecure;
        $this->mailer->Port = $port;
        $this->mailer->CharSet = 'UTF-8';

        // Sender
        $this->mailer->setFrom($this->fromEmail, $this->fromName);
    }

    public function enviarEmail($para, $assunto, $corpo) {
        $this->mailer->addAddress($para);
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $assunto;
        $this->mailer->Body = $corpo;

        try {
            $this->mailer->send();
            return 'Mensagem enviada!';
        } catch (Exception $e) {
            return 'Erro ao enviar mensagem: ' . $this->mailer->ErrorInfo;
        }
    }
}

// Configurações
$fromEmail = 'relacionamento@apobem.com.br';
$fromName = 'Relacionamento Apobem';
$username = $fromEmail; // Normalmente o mesmo que o fromEmail
$password = 'bnbw uwak mius tguo'; // Senha do email
$host = 'smtp.gmail.com';
$port = 465; // Ou 587 se estiver usando TLS

// Criar instância e enviar email
$emailSender = new EmailSender($fromEmail, $fromName, $username, $password, $host, $port);
$para = 'gabriel.gomes@outlook.com';
$assunto = 'Teste de envio de email';
$corpo = 'Teste de envio de email com PHPMailer';

$resultado = $emailSender->enviarEmail($para, $assunto, $corpo);
echo $resultado;
