<?php

header("Access-Control-Allow-Origin: *");
include("variaveis_fixas.php");

$arquivo_conect = "/var/www/html/sistema/sistema/connect_seguro_homolog_22.php";

include($arquivo_conect);

$data_inicio = $_POST["data_inicio"];
$data_fim = $_POST["data_fim"];

$query = "SELECT * FROM `sys_vendas_transacoes_boleto` WHERE `dateCreated` BETWEEN '" . $data_inicio . "' AND '" . $data_fim . "' ORDER BY `dateCreated` DESC";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$row = mysqli_fetch_array($result);
?>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Transacao ID</th>
            <th scope="col">CPF</th>
            <th scope="col">ID do boleto</th>
            <th scope="col">Data Criação</th>
            <th scope="col">Data Vencimento</th>
            <th scope="col">Valor</th>
        </tr>
    </thead>
    <tbody>
        <?php if($row != ''){ ?>
        <?php while($row = mysqli_fetch_array($result)){ ?>
        <tr>
            <td><?php echo $row['transacao_id']; ?></td>
            <td><?php echo $row['cliente_cpf']; ?></td>
            <td><?php echo $row['id_boleto']; ?></td>
            <td><?php 
                $data_criacao = explode("-", $row['dateCreated']);
                echo $data_criacao[2] . "/" . $data_criacao[1] . "/" . $data_criacao[0];
                ?>
            </td>
            <td>
                <?php 
                    $data_venc = explode("-", $row['dueDate']);
                    echo $data_venc[2] . "/" . $data_venc[1] . "/" . $data_venc[0];
                ?>
            </td>
            <td><?php echo "R$ " . number_format($row['value'],2,",",".") ?></td>
        </tr>
        <?php } ?>
        <?php }else{ ?>
        <tr>
            <td style="font-size: 22px; font-weight: bold; color: lightgrey" colspan="6">Nenhum boleto encontrado para o período</td>
        </tr>
        <?php } ?>
    </tbody>
</table>
