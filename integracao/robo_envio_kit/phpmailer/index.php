<?php
require_once('src/PHPMailer.php');
require_once('src/SMTP.php');
require_once('src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$assunto = $_POST['assunto'];
$mensagem = $_POST['mensagem'];

$mail = new PHPMailer;
$mail->setLanguage('br');                             // Habilita as saídas de erro em Português
$mail->CharSet='UTF-8';                               // Habilita o envio do email como 'UTF-8'

//$mail->SMTPDebug = 3;                               // Habilita a saída do tipo "verbose"

$mail->isSMTP();                                      // Configura o disparo como SMTP
$mail->Host = 'email-ssl.com.br';                     // Especifica o enderço do servidor SMTP da Locaweb
$mail->SMTPAuth = true;                               // Habilita a autenticação SMTP
$mail->Username = 'contato@gabriellgomess.com';       // Usuário do SMTP
$mail->Password = '@Isadopai1234';                    // Senha do SMTP
$mail->SMTPSecure = 'tls';                            // Habilita criptografia TLS | 'ssl' também é possível
$mail->Port = 587;                                    // Porta TCP para a conexão

$mail->From = 'contato@gabriellgomess.com';           // Endereço previamente verificado no painel do SMTP
$mail->FromName = $nome;                              // Nome no remetente
$mail->addAddress('contato@gabriellgomess.com');
// $mail->AddAttachment('../projeto/arquivos/1633697308.jpg');     // Acrescente um destinatário
// $mail->addAddress('sara.linhargomes@gmail.com');   // O nome é opcional
// $mail->addReplyTo('info@exemplo.com', 'Informação');
$mail->addCC($email);
// $mail->addBCC('bcc@exemplo.com');

$mail->isHTML(true);                                  // Configura o formato do email como HTML

$mail->Subject = $assunto;
$mail->Body    = "<strong>Nome: </strong>".$nome."<br>
                  <strong>Telefone: </strong>".$telefone."<br>
                  <strong>Email: </strong>".$email."<br>
                  <strong>Assunto: </strong>".$assunto."<br>
                  <strong>Mensagem: </strong>".$mensagem;
// $mail->AltBody = 'Esse é o corpo da mensagem em formato "plain text" para clientes de email não-HTML';

if(!$mail->send()) {
    echo 'A mensagem não pode ser enviada';
    echo '<br>';
    echo 'Mensagem de erro: ' . $mail->ErrorInfo;
} else {
    echo 'Mensagem enviada com sucesso!';
}
?>