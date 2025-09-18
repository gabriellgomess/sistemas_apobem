<?php
$inicio = $_POST['data_inicio'];
$fim = $_POST['data_fim'];
// $url = "https://www.asaas.com/api/v3/payments?dueDate%255Bge%255D=".$_POST['data_inicio']."&dueDate%255Ble%255D=".$_POST['data_fim']."&limit=1000";
// $url = "https://www.asaas.com/api/v3/payments?dueDate%5Bge%5D=".$inicio."&dueDate%5Ble%5D=".$fim."&limit=100";
// $url = "https://www.asaas.com/api/v3/payments?customer=8356855963406010";
$url = "https://private-anon-7cf3473ae1-asaasv3.apiary-proxy.com/api/v3/payments?dueDate=2022-10-01&dueDate=2022-10-01&limit=1000";
$metodo = "GET";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "access_token: 49188e8a6a676a2c5d5c553225d856040497580ae7552b34e260489cdab4da2c",
    "Content-Type: application/json"
));

$response->data = json_decode(curl_exec($ch));

curl_close($ch);

$count = 0;

$link = "<svg xmlns='http://www.w3.org/2000/svg' enable-background='new 0 0 24 24' height='24px' viewBox='0 0 24 24' width='24px' fill='#000000'><g><rect fill='none' height='24' width='24'/></g><g><g><path d='M17,7h-3c-0.55,0-1,0.45-1,1s0.45,1,1,1h3c1.65,0,3,1.35,3,3s-1.35,3-3,3h-3c-0.55,0-1,0.45-1,1c0,0.55,0.45,1,1,1h3 c2.76,0,5-2.24,5-5S19.76,7,17,7z M8,12c0,0.55,0.45,1,1,1h6c0.55,0,1-0.45,1-1s-0.45-1-1-1H9C8.45,11,8,11.45,8,12z M10,15H7 c-1.65,0-3-1.35-3-3s1.35-3,3-3h3c0.55,0,1-0.45,1-1s-0.45-1-1-1H7c-2.76,0-5,2.24-5,5s2.24,5,5,5h3c0.55,0,1-0.45,1-1 C11,15.45,10.55,15,10,15z'/></g></g></svg>";

?>
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    td{
        padding: 5px;
    }
 
    #url_api{
        font-size: 12px;
        color: grey;
        font-weight: bold;
    }
</style>
<?php echo "<p id='url_api'>URL: ". $url."</p>"; ?>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">ID do Boleto</th>
            <th scope="col">Valor</th>
            <th scope="col">Criação</th>
            <th scope="col">Status</th>
            <th scope="col">Vencimento</th>
            <th scope="col">Venc. Original</th>
            <th scope="col">Link</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($response->data->data as $key => $value): ?>
            <tr>
                <th scope="row"><?php echo $count += 1; ?></th>
                <td><?php echo $value->id; ?></td>
                <td><?php echo "R$ " . number_format($value->value, 2, "," , ".") ; ?></td>
                <td><?php 
                    $datac = explode("-", $value->dateCreated);
                    echo $datac[2]."/".$datac[1]."/".$datac[0];
                    ?>
                </td>
                <td><?php 
                    if($value->status == "PENDING"){
                        echo "<p style='font-weight: bold; color: orange'>Aguardando Pagamento</p>";
                    }else if($value->status == "OVERDUE"){
                        echo "<p style='font-weight: bold; color: red'>Vencida</p>";
                    }else if($value->status == "CONFIRMED"){
                        echo "<p style='font-weight: bold; color: green'>Pagamento confirmado (saldo ainda não creditado)</p>";
                    }else if($value->status == "CANCELED"){
                        echo "<p style='font-weight: bold; color: red'>Cancelado</p>";
                    }else if($value->status == "REFUNDED"){
                        echo "<p style='font-weight: bold; color: red'>Estornada</p>";
                    }else if($value->status == "RECEIVED"){
                        echo "<p style='font-weight: bold; color: green'>Recebida (saldo já creditado na conta)</p>";
                    }else if($value->status == "PROCESSING"){
                        echo "<p style='font-weight: bold; color: yellow'>Processando</p>";
                    }else if($value->status == "REVERTED"){
                        echo "<p style='font-weight: bold; color: orange'>Revertido</p>";
                    }else if($value->status == "DISPUTED"){
                        echo "<p style='font-weight: bold; color: orange'>Em disputa</p>";
                    }else if($value->status == "CHARGEBACK"){
                        echo "<p style='font-weight: bold; color: orange'>Chargeback</p>";
                    }
                    ?>
                </td>
                <td><?php 
                    $data = explode("-", $value->dueDate);
                    echo $data[2]."/".$data[1]."/".$data[0];
                    ?>
                </td>
                <td><?php 
                    $datao = explode("-", $value->originalDueDate);
                    echo $datao[2]."/".$datao[1]."/".$datao[0];
                    ?>
                </td>
                <td style="text-align: center;"><a href="<?php echo $value->invoiceUrl; ?>" target="_blank"><?php echo $link; ?></a></td>
            </tr>
        <?php endforeach; ?>
</table>

