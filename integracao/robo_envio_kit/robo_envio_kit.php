<?php
ob_start(); // Inicia o buffer de saída
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connect.php';

require_once('phpmailer/src/PHPMailer.php');
require_once('phpmailer/src/SMTP.php');
require_once('phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SMSSender {
    private $apiKey;
    private $serviceUrl;

    public function __construct($apiKey, $serviceUrl = "https://sms.comtele.com.br/api/v2/send") {
        $this->apiKey = $apiKey;
        $this->serviceUrl = $serviceUrl;
    }

    public function enviarSMS($telefone, $link, $nomeFull) {
        $nome = explode(' ', $nomeFull)[0];
        $telefone = preg_replace('/[^0-9]/', '', $telefone);

        $dados = array(
            'Receivers' => $telefone,
            'Content' => $nome . ' Bem-vindo(a) a APOBEM! Baixe o seu cartão virtual e dê um passo importante em direção a um futuro mais tranquilo: ' . $link
        );

        $ch = curl_init($this->serviceUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'auth-key: ' . $this->apiKey,
            'content-type: application/json'
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response);
    }
}

class EmailSender {
    private $mailer;

    public function __construct($host, $username, $password, $fromEmail, $fromName, $port = 465, $smtpSecure = 'ssl') {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = $host;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $username;
        $this->mailer->Password = $password;
        $this->mailer->SMTPSecure = $smtpSecure;
        $this->mailer->Port = $port;
        $this->mailer->setFrom($fromEmail, $fromName);
        $this->mailer->CharSet = 'UTF-8';
    }

    public function enviarEmail($paraEmail, $paraNome, $assunto, $link) {
        $nome = explode(' ', $paraNome)[0];
        try {
            $this->mailer->addAddress($paraEmail, $paraNome);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $assunto;
            $this->mailer->Body = "<div style='color: black'>
                <img src='https://apobem.com.br/portal/cabecalho.png'>
                <h2><img width='20px' src='https://apobem.com.br/portal/party-popper.png'> {$nome}, bem-vindo(a) à APOBEM! <img width='20px' src='https://apobem.com.br/portal/party-popper.png'></h2>
                <p>É com grande alegria que lhe damos as boas-vindas...</p>
                <h3>Acesse seu kit boas-vindas <a href='{$link}'>Clicando aqui!</a></h3>
                <img src='https://apobem.com.br/portal/rodape.png'>
            </div>";
            $this->mailer->send();
            return json_encode(['success' => true, 'message' => 'E-mail enviado com sucesso']);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Erro ao enviar e-mail: ' . $this->mailer->ErrorInfo]);
        }
    }
}

function urlShorted($urlFull, $vendas_id, $conn) {
    $dados = ['Url' => $urlFull];
    $ch = curl_init('https://sms.comtele.com.br/api/v2/accounturls');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'auth-key: cf5a54ea-a68f-4dcd-bce1-8474dcf71270',
        'content-type: application/json'
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $shorterUrl = json_decode($response, true)['Object']['ShorterUrl'];

    $stmt = $conn->prepare("INSERT INTO sys_url_shortner (vendas_id, shorted_url, original_url, access_count) VALUES (:vendas_id, :shorted_url, :original_url, 0)");
    $stmt->bindParam(':vendas_id', $vendas_id);
    $stmt->bindParam(':shorted_url', $shorterUrl);
    $stmt->bindParam(':original_url', $urlFull);
    $stmt->execute();

    return $shorterUrl;
}

function criaHash($cpf, $vendas_id, $conn) {
    $secret_key = "u";
    $data = json_encode(["code" => $secret_key . "-" . $cpf . "-" . $vendas_id]);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));
    $encrypted_data = base64_encode(openssl_encrypt($data, 'aes-128-cbc', $secret_key, 0, $iv) . '::' . $iv);
    $link = "https://apobem.com.br/portal/?schdl=1&" . http_build_query(["data" => $encrypted_data]);
    return urlShorted($link, $vendas_id, $conn);
}

$diasAtras = 30;
$dataAtras = date('Y-m-d', strtotime("-$diasAtras days"));

$sql = "SELECT s.vendas_id, s.cliente_cpf, s.vendas_apolice, s.vendas_dia_ativacao, s.vendas_telefone, 
        s.vendas_telefone2, s.forma_envio_kitcert, c.cliente_celular, c.cliente_email, c.cliente_nome,
        s.vendas_status
        FROM sys_vendas_seguros s
        INNER JOIN sys_inss_clientes c ON s.cliente_cpf = c.cliente_cpf
        WHERE s.vendas_status IN (67, 45)
        AND s.vendas_apolice IN (176, 175, 172, 171, 174, 173)
        AND s.vendas_dia_ativacao = '2024-03-28'";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':dataAtras', $dataAtras, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Resultados encontrados: " . count($results) . "</h2>";
    echo "<pre>"; print_r($results); echo "</pre>";
    flush(); ob_flush();

    if (count($results) == 0) {
        echo "<p>Nenhum registro encontrado para a data: $dataAtras</p>";
        flush(); ob_flush();
        exit;
    }

    $emailSender = new EmailSender('smtp.gmail.com', 'relacionamento@apobem.com.br', 'bnbw uwak mius tguo', 'relacionamento@apobem.com.br', 'Kit Boas-vindas APOBEM');
    $smsSender = new SMSSender("cf5a54ea-a68f-4dcd-bce1-8474dcf71270");
    $total = 0;

    foreach ($results as $row) {
        echo "<hr><p>Processando: <strong>{$row['cliente_nome']}</strong> (Venda ID: {$row['vendas_id']})</p>";
        $linkShorted = criaHash($row['cliente_cpf'], $row['vendas_id'], $conn);

        $envioSMS = $envioEmail = $statusSendSMS = $statusSendEmail = 0;
        $messageSMS = $messageEmail = '';
        $telefone = $row['vendas_telefone'];
        $email = $row['cliente_email'];

        switch ($row['forma_envio_kitcert']) {
            case 1:
                $retornoSMS = $smsSender->enviarSMS($telefone, $linkShorted, $row['cliente_nome']);
                echo "<pre>Retorno SMS:</pre>";
                print_r($retornoSMS);
                $envioSMS = 1;
                $statusSendSMS = $retornoSMS->Success ? 1 : 0;
                $messageSMS = $retornoSMS->Message;
                break;

            case 2:
                if (!empty($email)) {
                    $retornoEmail = json_decode($emailSender->enviarEmail($email, $row['cliente_nome'], 'Kit boas-vindas APOBEM', $linkShorted));
                    echo "<pre>Retorno Email:</pre>";
                    print_r($retornoEmail);
                    $envioEmail = 1;
                    $statusSendEmail = $retornoEmail->success ? 1 : 0;
                    $messageEmail = $retornoEmail->message;
                } else {
                    echo "<p>Email vazio, fallback para SMS</p>";
                    $retornoSMS = $smsSender->enviarSMS($telefone, $linkShorted, $row['cliente_nome']);
                    print_r($retornoSMS);
                    $envioSMS = 1;
                    $statusSendSMS = $retornoSMS->Success ? 1 : 0;
                    $messageSMS = $retornoSMS->Message;
                }
                break;

            case 3:
                echo "<p>Forma de envio: Correio</p>";
                break;
        }

        $stmtLog = $conn->prepare("INSERT INTO sys_logs_disparos_kit (venda_id, envio_sms, status_send_sms, message_sms, telefone, envio_email, status_send_email, message_email, email, url, created_at) 
        VALUES (:venda_id, :envio_sms, :status_send_sms, :message_sms, :telefone, :envio_email, :status_send_email, :message_email, :email, :url, NOW())");

        $stmtLog->execute([
            ':venda_id' => $row['vendas_id'],
            ':envio_sms' => $envioSMS,
            ':status_send_sms' => $statusSendSMS,
            ':message_sms' => $messageSMS,
            ':telefone' => $telefone,
            ':envio_email' => $envioEmail,
            ':status_send_email' => $statusSendEmail,
            ':message_email' => $messageEmail,
            ':email' => $email,
            ':url' => $linkShorted
        ]);

        $total++;
        flush(); ob_flush();
    }

    echo "<h3>Processamento concluído.</h3>";
    echo "<p>Total processado: $total</p>";

    $conn = null;
} catch (PDOException $e) {
    echo "<p>Erro: " . $e->getMessage() . "</p>";
}
?>
