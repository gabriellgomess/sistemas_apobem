<!doctype html>
<html lang="pt-BR">
    <?php ob_start(); ?>
<head>
    <title>Script atualizador de Boleto</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
</head>
<body>
    <div class="container mt-5">
        <?php
        // $data_inicial = $_POST['data_inicial'];
        // $data_final = $_POST['data_final'];

        include('asaas_api.php');
        $path_includes = "/var/www/html/sistema/sistema/";
        // $path_includes = "../var/www/html/sistema/sistema/";
        $arquivo_conect = $path_includes . "connect_seguro.php";
        include($arquivo_conect);
        $today = date('Y-m-d');

        // ************* AMBIENTE DE PRODUÇÃO OU HOMOLOGAÇÃO *************
        // Quando em ambiente de produção, as queries de atualização (update) e inserção (insert), serão executadas.
        // Quando em ambiente de homologação, as queries de atualização (update) e inserção (insert), serão apenas exibidas na tela.
        // Para alterar o ambiente, basta alterar o valor da variável $ambiente para "producao" ou "homologacao"

        $ambiente = "producao";

        // ****************************************************************

        // $endDate = $data_final;
        // $startDate = $data_inicial;

        $asaasApi = new ASAASAPI("https://www.asaas.com/api/v3", "49188e8a6a676a2c5d5c553225d856040497580ae7552b34e260489cdab4da2c", $con, $ambiente);

        $endDate2 = date('Y-m-d', strtotime('-1 day', strtotime($today)));
        $startDate2 = date('Y-m-d', strtotime('-1 day', strtotime($today)));

        $endDate = date('Y-m-d', strtotime('-3 day', strtotime($today)));
        $startDate = date('Y-m-d', strtotime('-3 day', strtotime($today)));

        $receiveds = $asaasApi->getReceiveds($startDate2, $endDate2);

        $payments = $asaasApi->getPayments($startDate, $endDate);

        $inicio = date('d/m/Y', strtotime($startDate));
        $fim = date('d/m/Y', strtotime($endDate));

        $inicio2 = date('d/m/Y', strtotime($startDate2));
        $fim2 = date('d/m/Y', strtotime($endDate2));

        $saida = '';

        $saida .= "<?php header('Access-Control-Allow-Origin: *');?>";

        if ($ambiente == "homologacao") {
            $saida .= "<p style='color: red; font-weight: bold'>Sistema operando em modo de homologação, nenhuma alteração será feita, todoas as informações mostradas são apenas em caráter informativo. Para alterar o ambiente entre produção e homologação, contate a área de tecnologia.</p><br>";
        } else {
            $saida .= "<p style='color: green; font-weight: bold'>Sistema em modo PRODUÇÃO, todas as informações mostradas foram alteradas na base de dados. Para alterar o ambiente entre produção e homologação, contate a área de tecnologia.</p><br>";
        }

        $content = '';
        $content2 = '';

        $saida .= "<h3>Boletos pagos entre " . $inicio2 . " até " . $fim2 . "</h3><br>";
        $contPagos = 0;
        $totalPagos = 0;
        foreach ($receiveds as $received) {
            if ($received->status == 'RECEIVED') {                            
                $vendas_id = $asaasApi->buscaVendaId($received->id);
                $ver_parcelas = $asaasApi->verificaParcelasBoletoRecebido($received->paymentDate, $received->id, $vendas_id);
                if ($ver_parcelas) {                    
                    $saida .= $ver_parcelas . "<br>";
                    $content2 = '1';
                    $contPagos++;
                    $totalPagos += $received->value;
                }
                
            }
        }
        if ($contPagos == 0) {
            $saida .= "<h1 style='color: lightgrey; font-weight: bold'>Não há boletos pagos para o período estipulado</h1><br>";
        }else{
            $saida .= "<h1 style='color: lightgrey; font-weight: bold'>Total de boletos pagos: ".$contPagos."</h1><br>";
            $saida .= "<h1 style='color: lightgrey; font-weight: bold'>Total de boletos pagos: R$ ".number_format($totalPagos, 2, ',', '.')."</h1><br>";
        }

        if ($content2 == '') {
            $saida .= "<h1 style='color: lightgrey; font-weight: bold'>Não há boletos com vencimento para o período estipulado</h1><br>";
        }

        $saida .= "<h3>Boletos com vencimento de " . $inicio . " até " . $fim . "</h3><br>";
        $contVencidos = 0;
        $totalVencidos = 0;
        foreach ($payments as $payment) {

            if ($payment->status == 'PENDING') {
                $vendas_id = $asaasApi->buscaVendaId($payment->id);
                $ver_boletos_pendentes = $asaasApi->verificaParcelasBoletoPendente($payment->id, $vendas_id);

                if ($ver_parcelas) {
                    $saida .= $ver_boletos_pendentes . "<br>";
                    $content = '1';
                }
            } elseif ($payment->status == 'OVERDUE') {
                $vendas_id = $asaasApi->buscaVendaId($payment->id);
                $ver_parcelas = $asaasApi->verificaParcelasBoletoAtrasado($payment->id, $vendas_id);
                if ($ver_parcelas) {

                    if ($ver_parcelas) {
                        $saida .= $ver_parcelas . "<br>";
                        $content = '1';
                    }
                    $contVencidos++;
                    $totalVencidos += $payment->value;
                }
            }
        }

        if ($contVencidos !== 0) {
            $saida .= "<h1 style='color: lightgrey; font-weight: bold'>Total de boletos vencidos: ".$contVencidos."</h1><br>";
            $saida .= "<h1 style='color: lightgrey; font-weight: bold'>Total de boletos vencidos: R$ ".number_format($totalVencidos, 2, ',', '.')."</h1><br>";
        }
        if ($content == '') {
            $saida .= "<h1 style='color: lightgrey; font-weight: bold'>Não há boletos com vencimento para o período estipulado</h1>";
        }
        ?>
    </div>

    <!-- Salvar a saída deste arquivo em um arquivo .html -->
    <?php
$arquivo_conect = $path_includes . "connect_seguro.php";
include($arquivo_conect);
echo $saida;
$nome = date('Y-m-d')."_".date('H:i:s');
$fp = fopen("/var/www/html/integracao/softwareexpress/atualizacao/logs/boletos/".$nome.".php", "a");
fwrite($fp, $saida);
fclose($fp);

// Define a data e hora atual no formato do banco de dados
$data_hora_atual = date('Y-m-d H:i:s');
$data_atual = date('Y-m-d');

// Consulta para verificar se já existe um registro com a data e hora atual
$sql_verifica_registro = "SELECT data FROM sys_historico_script_atualizacao WHERE data = '".$data_atual."'";
$resultado = mysqli_query($con, $sql_verifica_registro) or die("Erro... " . $mysqli->error);
$num_rows = mysqli_num_rows($resultado);

// Pega o dia da semana de hoje
$dia = date('D');

$semana = array(
    'Sun' => 'domingo', 
    'Mon' => 'segunda-feira',
    'Tue' => 'terça-feira',
    'Wed' => 'quarta-feira',
    'Thu' => 'quinta-feira',
    'Fri' => 'sexta-feira',
    'Sat' => 'sabado'
);

$dia_semana = $semana[$dia];

if($num_rows == 0){
    $sql_insere_registro = "INSERT INTO `sys_historico_script_atualizacao` (`historico_nome`, `historico_data`, `historico_log`, `historico_cont_pagos`, `historico_total_pagos`, `historico_cont_naopagos`, `historico_total_naopagos`, `data`, `dia_semana`, `ambiente`) 
         VALUES ('".$nome.".php', '".$data_hora_atual."' , 'https://grupofortune.com.br/integracao/softwareexpress/atualizacao/logs/boletos/".$nome.".php', ".$contPagos.", ".$totalPagos.", ".$contVencidos.", ".$totalVencidos.", '".$data_atual."', '".$dia_semana."' ,'".$ambiente."')";
    mysqli_query($con, $sql_insere_registro);
}


?>
</body>


</html>