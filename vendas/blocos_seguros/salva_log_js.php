<?php
// Define o arquivo de log no mesmo diretório
$log_file = "/var/www/html/sistema/sistema/vendas/blocos_seguros/log_transacoes.txt";

// Função para gravar log
function gravaLog($mensagem)
{
    global $log_file;
    $data = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$data][JavaScript] " . $mensagem . "\n", FILE_APPEND);
}

// Recebe a mensagem via POST
if (isset($_POST['mensagem'])) {
    gravaLog($_POST['mensagem']);
    echo "Log salvo com sucesso";
} else {
    http_response_code(400);
    echo "Mensagem não fornecida";
}
