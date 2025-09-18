<?php

// Configurações iniciais do CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Ativar logs de erro para desenvolvimento
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

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método de requisição não suportado']);
    exit;
}

// Classe para envio de SMS
class SMSSender {
    private $apiKey;
    private $serviceUrl;

    public function __construct($apiKey, $serviceUrl = "https://sms.comtele.com.br/api/v2/send") {
        $this->apiKey = $apiKey;
        $this->serviceUrl = $serviceUrl;
    }

    public function enviarSMS($telefone, $link, $nomeFull) {
        $nome = explode(' ', $nomeFull)[0];
        $url = $this->serviceUrl;
        $telefone = preg_replace('/\D/', '', $telefone); // Remove tudo que não for número
        $dados = array(
            'Receivers' => $telefone,
            'Content' => $nome . ' Bem-vindo(a) a APOBEM! Baixe o seu cartão virtual e dê um passo importante em direção a um futuro mais tranquilo: ' . $link
        );
        $dadosJson = json_encode($dados);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosJson);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'auth-key: ' . $this->apiKey,
            'content-type: application/json'
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}

// Classe para envio de Email
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
                <h2><img width='20px' src='https://apobem.com.br/portal/party-popper.png'> " . $nome . ", bem-vindo(a) a APOBEM! <img width='20px' src='https://apobem.com.br/portal/party-popper.png'></h2>
                <br>
                <p>É com grande alegria que lhe damos as boas-vindas à família APOBEM! Parabéns por se tornar parte da nossa associação, onde o cuidado e o bem-estar são prioridades absolutas.</p>
                <p>Ao adquirir o seu cartão virtual APOBEM, você deu um passo significativo em direção a um futuro mais estável e tranquilo. Agora, você faz parte de uma comunidade dedicada a oferecer suporte e assistência em momentos de necessidade, garantindo que você e seus entes queridos estejam sempre amparados.</p>
                <p>Conte conosco para tudo o que precisar. Juntos, vamos construir um futuro mais seguro e próspero.</p>
                <h3>Acesse seu kit boas-vindas <a href='" . $link . "'>Clicando aqui!</a></h3>
                <br>
                <img src='https://apobem.com.br/portal/rodape.png'>
            </div>";
            $this->mailer->send();
            return array('success' => true, 'message' => 'E-mail enviado com sucesso');
        } catch (Exception $e) {
            return array('success' => false, 'message' => 'Erro ao enviar e-mail: ' . $e->getMessage());
        }
    }
}

// Função para encurtar URL
function urlShorted($urlFull, $vendas_id, $conn) {
    $url = 'https://sms.comtele.com.br/api/v2/accounturls';
    $dados = array('Url' => $urlFull);
    $dadosJson = json_encode($dados);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosJson);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'auth-key: cf5a54ea-a68f-4dcd-bce1-8474dcf71270',
        'content-type: application/json'
    ));
    $response = curl_exec($ch);
    $responseArray = json_decode($response, true);
    curl_close($ch);

    $shorterUrl = isset($responseArray['Object']['ShorterUrl']) ? $responseArray['Object']['ShorterUrl'] : null;

    if ($shorterUrl) {
        $count = 0;
        $stmt = $conn->prepare("INSERT INTO sys_url_shortner (vendas_id, shorted_url, original_url, access_count) VALUES (:vendas_id, :shorted_url, :original_url, :access_count)");
        $stmt->bindParam(':vendas_id', $vendas_id);
        $stmt->bindParam(':shorted_url', $shorterUrl);
        $stmt->bindParam(':original_url', $urlFull);
        $stmt->bindParam(':access_count', $count);
        $stmt->execute();
    }

    return $shorterUrl;
}

// Função para criar hash
function criaHash($cpf, $vendas_id, $conn) {
    $secret_key = "u";
    $data = array("code" => $secret_key . "-" . $cpf . "-" . $vendas_id);
    $data_string = json_encode($data);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));
    $encrypted_data = openssl_encrypt($data_string, 'aes-128-cbc', $secret_key, 0, $iv);
    $encrypted_data = base64_encode($encrypted_data . '::' . $iv);
    $link = "https://apobem.com.br/portal/?schdl=1&" . http_build_query(array("data" => $encrypted_data));
    return urlShorted($link, $vendas_id, $conn);
}

// Recebendo dados do POST
$vendas_id = isset($_POST['vendas_id']) ? $_POST['vendas_id'] : null;
$telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$sendSms = isset($_POST['sendSms']) ? $_POST['sendSms'] : null;
$sendEmail = isset($_POST['sendEmail']) ? $_POST['sendEmail'] : null;

if (!$vendas_id || (!$sendSms && !$sendEmail)) {
    echo json_encode(array('error' => 'Dados incompletos.'));
    exit;
}

$sql = "SELECT s.vendas_id, s.cliente_cpf, s.vendas_apolice, s.vendas_dia_ativacao, s.vendas_telefone, 
        s.vendas_telefone2, s.forma_envio_kitcert, c.cliente_celular, c.cliente_email, c.cliente_nome,
        s.vendas_status
        FROM sys_vendas_seguros s
        INNER JOIN sys_inss_clientes c ON s.cliente_cpf = c.cliente_cpf
        WHERE s.vendas_id = :vendas_id";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':vendas_id', $vendas_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 0) {
        echo json_encode(array('message' => 'Nenhum registro encontrado para o ID fornecido.'));
        exit;
    }

    $emailSender = new EmailSender('smtp.gmail.com', 'relacionamento@apobem.com.br', 'bnbw uwak mius tguo', 'relacionamento@apobem.com.br', 'Kit Boas-vindas APOBEM');
    $smsSender = new SMSSender("cf5a54ea-a68f-4dcd-bce1-8474dcf71270");

    $mensagem = array();
    $totalRegistrosAtualizados = 0;

    foreach ($results as $row) {
        $linkShorted = criaHash($row['cliente_cpf'], $row['vendas_id'], $conn);
        $envioSMS = 0;
        $envioEmail = 0;
        $statusSendSMS = 0;
        $statusSendEmail = 0;
        $messageSMS = '';
        $messageEmail = '';

        if ($sendSms === 'true' ) {
            $retornoSMS = $smsSender->enviarSMS($telefone, $linkShorted, $row['cliente_nome']);
            $mensagem['sms'][] = array(
                'telefone' => $telefone,
                'link' => $linkShorted,
                'nome' => $row['cliente_nome'],
                'retorno' => $retornoSMS
            );
            $envioSMS = 1;
            $statusSendSMS = isset($retornoSMS['Success']) && $retornoSMS['Success'] ? 1 : 0;
            $messageSMS = isset($retornoSMS['Message']) ? $retornoSMS['Message'] : 'Erro desconhecido';
        }

        if ($sendEmail === 'true') {
            $retornoEmail = $emailSender->enviarEmail($email, $row['cliente_nome'], 'Kit boas-vindas APOBEM', $linkShorted);
            $mensagem['email'][] = array(
                'email' => $email,
                'link' => $linkShorted,
                'nome' => $row['cliente_nome'],
                'retorno' => $retornoEmail
            );
            $envioEmail = 1;
            $statusSendEmail = isset($retornoEmail['success']) && $retornoEmail['success'] ? 1 : 0;
            $messageEmail = isset($retornoEmail['message']) ? $retornoEmail['message'] : 'Erro desconhecido';
        }

        $stmt = $conn->prepare("INSERT INTO sys_logs_disparos_kit (venda_id, envio_sms, status_send_sms, message_sms, telefone, envio_email, status_send_email, message_email, email, url, created_at) VALUES (:venda_id, :envio_sms, :status_send_sms, :message_sms, :telefone, :envio_email, :status_send_email, :message_email, :email, :url, NOW())");
        $stmt->bindParam(':venda_id', $row['vendas_id']);
        $stmt->bindParam(':envio_sms', $envioSMS);
        $stmt->bindParam(':status_send_sms', $statusSendSMS);
        $stmt->bindParam(':message_sms', $messageSMS);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':envio_email', $envioEmail);
        $stmt->bindParam(':status_send_email', $statusSendEmail);
        $stmt->bindParam(':message_email', $messageEmail);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':url', $linkShorted);
        $stmt->execute();

        $totalRegistrosAtualizados++;
    }

    $stmt = $conn->prepare("INSERT INTO sys_url_logexec (mensagem, data_execucao) VALUES (:mensagem, NOW())");
    $mensagemLog = "Total de registros atualizados: " . $totalRegistrosAtualizados . " executado manualmente pelo operador.";
    $stmt->bindParam(':mensagem', $mensagemLog);
    $stmt->execute();

    echo json_encode($mensagem);
    $conn = null; // Fechar conexão
} catch (PDOException $e) {
    echo json_encode(array('error' => 'Erro na execução da query: ' . $e->getMessage()));
}

?>
