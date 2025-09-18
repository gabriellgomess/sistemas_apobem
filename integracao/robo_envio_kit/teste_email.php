<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('phpmailer/src/PHPMailer.php');
require_once('phpmailer/src/SMTP.php');
require_once('phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class EmailSender
{
    private $mailer;

    public function __construct($host, $username, $password, $fromEmail, $fromName, $port = 465, $smtpSecure = 'ssl')
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->SMTPDebug = 2;
        $this->mailer->Host = $host;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $username;
        $this->mailer->Password = $password;
        $this->mailer->SMTPSecure = $smtpSecure;
        $this->mailer->Port = $port;
        $this->mailer->setFrom($fromEmail, $fromName);
        $this->mailer->CharSet = 'UTF-8';
    }

    public function enviarEmail($paraEmail, $paraNome, $assunto, $link)
    {
        // Pegar somente o primeiro nome do cliente
        $nome = explode(' ', $paraNome)[0];

        try {
            $this->mailer->addAddress($paraEmail, $paraNome);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $assunto;
            $this->mailer->Body = "<div style='color: black'>";
            $this->mailer->Body .= "<img src='https://apobem.com.br/portal/cabecalho.png'>";
            $this->mailer->Body .= "<h2><img width='20px' src='https://apobem.com.br/portal/party-popper.png'> " . $nome . ", bem-vindo(a) a APOBEM! <img width='20px' src='https://apobem.com.br/portal/party-popper.png'></h2>";
            $this->mailer->Body .= "<br>";
            $this->mailer->Body .= "<p>É com grande alegria que lhe damos as boas-vindas à família APOBEM! Parabéns por se tornar parte da nossa associação, onde o cuidado e o bem-estar são prioridades absolutas.</p>";
            $this->mailer->Body .= "<p>Ao adquirir o seu cartão virtual APOBEM, você deu um passo significativo em direção a um futuro mais estável e tranquilo. Agora, você faz parte de uma comunidade dedicada a oferecer suporte e assistência em momentos de necessidade, garantindo que você e seus entes queridos estejam sempre amparados.</p>";
            $this->mailer->Body .= "<p>Conte conosco para tudo o que precisar. Juntos, vamos construir um futuro mais seguro e próspero.</p>";
            $this->mailer->Body .= "<h3>Acesse seu kit boas-vindas <a href='" . $link . "'>Clicando aqui!</a></h3>";
            $this->mailer->Body .= "<br>";
            $this->mailer->Body .= "<img src='https://apobem.com.br/portal/rodape.png'>";
            $this->mailer->Body .= "</div>";

            $this->mailer->send();
            return json_encode(['success' => true, 'message' => 'E-mail enviado com sucesso']);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Erro ao enviar e-mail: ' . $this->mailer->ErrorInfo]);
        }
    }
}

$emailSender = new EmailSender('smtp.gmail.com', 'relacionamento@apobem.com.br', 'Equador452', 'relacionamento@apobem.com.br', 'Kit Boas-vindas APOBEM');

echo $emailSender->enviarEmail('gabriel.gomes@outlook.com', 'Gabriel Gomes', 'Kit boas-vindas APOBEM', 'https://apobem.com.br/kit-boas-vindas');
