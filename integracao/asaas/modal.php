<?php

$customer_get = $_GET['customer'];
$id_boleto = $_GET['id_boleto'];
$customer = explode("_", $customer_get);
$id_boletos = explode("_", $id_boleto);

$url = "https://www.asaas.com/api/v3/payments/".$id_boleto;
$metodo = "GET";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "access_token: 49188e8a6a676a2c5d5c553225d856040497580ae7552b34e260489cdab4da2c",
    "Content-Type: application/json"
));

$response = json_decode(curl_exec($ch));
$retorno = $response;
curl_close($ch);
// echo $retorno->dueDate;
?>
<style>
    
    .modal--container {
        margin: 80px auto;
        width: 770px;
        height: 675px;
        border: 1px solid grey;
        border-radius: 10px;
        padding: 40px;
        font-family: Arial, Helvetica, sans-serif;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
    }

    .container--main {
        width: 100%;
    }

    .container-title {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .container-title>h3 {
        margin: 0 10px 0 0;

    }

    .container--dados {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .container--check {
        width: 70%;
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    textarea {
        resize: none !important;
    }

    .input-width {
        width: 221px !important;
    }

    .input-width-tx {
        width: 150px !important;
        border-top-right-radius: 5px 5px !important;
        border-bottom-right-radius: 5px 5px !important;
    }

    .input-group-tx {
        width: 49% !important;
    }

    .input-group-percent {
        width: 32% !important;
    }

    .container--juros {
        display: flex;
        justify-content: space-between;
        width: 66%;
    }

    .container--descontos {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .container--parcelamento {
        display: flex;
        width: 66%;
        justify-content: space-between;
    }

    .container--textarea {
        width: 100%;

    }

    .hide {
        display: none;
    }

    .show {
        display: flex;
    }

    #count-words {
        font-size: 12px;
        margin-top: 5px;
    }

    #envio-correios {
        font-size: 12px;
        color: green;
        fill: green;
    }

    #descricao {
        margin-bottom: 6px;
    }

    .form-floating>.form-control:focus~.tx-porcentagem {
        opacity: .65;
        transform: scale(.85) translateY(-0.5rem) translateX(0.15rem) !important;
        z-index: 20 !important;
    }

    .form-floating>.tx-porcentagem {
        left: 39px !important;
    }

    .modal-auditoria-hidden {
        display: none;
    }

    .modal-auditoria-show {
        margin-left: calc(50% - 385px);
        width: 770px;
        height: 675px;
        border: 1px solid grey;
        border-radius: 10px;
        padding: 40px;
        font-family: Arial, Helvetica, sans-serif;
        display: flex;
        flex-direction: column;
        justify-content: start;
        position: absolute;
        z-index: 10;
        background: #f9f6f6;
        -webkit-animation: slide-in-top 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
        animation: slide-in-top 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
    }

    @-webkit-keyframes slide-in-top {
        0% {
            -webkit-transform: translateY(-1000px);
            transform: translateY(-1000px);
            opacity: 0;
        }

        100% {
            -webkit-transform: translateY(0);
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slide-in-top {
        0% {
            -webkit-transform: translateY(-1000px);
            transform: translateY(-1000px);
            opacity: 0;
        }

        100% {
            -webkit-transform: translateY(0);
            transform: translateY(0);
            opacity: 1;
        }
    }

    .label-res {
        font-size: 12px;
        font-weight: bold;
        margin-right: 20px;
    }
    .label-res>p, #description, #postalService, #customer{
        font-weight: normal;
        color: grey;
        font-size: 12px;
    }

    .wrapper {
        display: flex;

    }
    .instrucoes-text{
        font-size: 12px;
        margin-top: 10px;
    }
    .basic:before {
        content: '';
        display: block;
        height: 50px;
        width: 50px;
        -webkit-animation: spin .5s infinite linear;
                animation: spin .5s infinite linear;
        border: 6px #222 solid;
        border-left-color: #c9485a;
        border-radius: 100%; 
    }

    @-webkit-keyframes spin {
    to {
        -webkit-transform: rotate(360deg);
                transform: rotate(360deg); 
        }
    }

    @keyframes spin {
    to {
        -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
        } 
    }
.container-spinner{
    position: absolute;
    width: 100%;
    height: 100%;
    background: #8080808a;
    z-index: 10;
    top: 0;
    left: 0;
    border-radius: 8px;
    display: none;
    justify-content: center;
    align-items: center;
}
.container--butons{
    width: 60%;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
}
.btn-pdf:hover{
    color: #fff;
    fill: #fff;
}
.btn-conf{
    color: #fff;
}
.btn-view{
    height: 100%;
}
.btn-pdf, .btn-conf, .btn-view{
    width: 130px;
}
.print-pdf{
    fill: #fff;
}
#message{
    font-size: 12px;
    color: grey;
    margin-top: 10px;
}
.lista-parcelas{
    font-size: 12px;
}
.container--parcelas{
    max-height: 117px;
    overflow: auto;
}
.bi-info-circle{
    cursor: pointer;
    margin-left: 20px;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src='moment.js'></script>

<div id="modal-auditoria" class="modal-auditoria-hidden">
    <div class="container-spinner">
        <div class="basic"></div>
    </div>
    <label class="label-res" for="">ID do cliente (Customer ID)</label>
    <p id="customer"></p>
    <div class="wrapper">
        <label class="label-res" for="">Forma de pagamento
        <p id="billingType"></p>
        </label>
        <label class="label-res" for="">Valor
        <p id="value"></p>
        </label>
        <label class="label-res" for="">Data de vencimento
        <p id="dueDate"></p>
        </label>
    </div>
    
    
    <label class="label-res" for="">Descrição</label>
    <p id="description"></p>
    <!-- <div class="wrapper">
        <label class="label-res" for="">Parcelamento
            <p id="installment"></p>
        </label>
        <label class="label-res" for="">Nº de parcelas
            <p id="installmentCount"></p>
        </label>
        <label class="label-res" for="">Valor de cada parcela
            <p id="installmentValue"></p>
        </label>
    </div> -->
    <div class="wrapper">
        <label class="label-res" for="">Desconto
            <p id="discount"></p>
        </label>
        <label class="label-res" for="">Valor do desconto
            <p id="discountValue"></p>
        </label>
        <label class="label-res" for="">Dias antes do vencimento para aplicar desconto
            <p id="dueDateLimitDays"></p>
        </label>
        <label class="label-res" for="">Tipo do desconto
            <p id="discountType"></p>
        </label>
    </div>
    <!-- <div class="wrapper">
        <label class="label-res" for="">Juros
            <p id="interest"></p>
        </label>
        <label class="label-res" for="">Valor dos Juros
            <p id="interestValue"></p>
        </label>
    </div>
    <div class="wrapper">
        <label class="label-res" for="">Multa
            <p id="fine"></p>
        </label>
        <label class="label-res" for="">Valor da Multa
            <p id="fineValue"></p>
        </label>
    </div>
    <label class="label-res" for="">Envio do boleto pelo correio?</label>
    <p id="postalService"></p> -->
    <hr>
    <p class="instrucoes-text">Confira se os dados estão corretos para a atualização do boleto, após clique em <span class="text-success">Atualizar Cobrança</span>  ou <span class="text-danger">Corrigir</span> .</p>
    <div class="d-flex">
        <button type="button" class="btn btn-success me-2" onclick="enviarApi()">Atualizar Cobrança</button>
        <button type="button" class="btn btn-outline-danger" onclick="fecharModal()">Corrigir</button>
    </div>

</div>

<div class="modal--container">
    <div class="container--main">
        <div class="container-title">
            <h3>Alterar dados do Boleto</h3>
            <svg id="icon-barcode" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30">
                <path
                    d="M1.406 1.875C0.627 1.875 0 2.502 0 3.281V26.719c0 0.779 0.627 1.406 1.406 1.406H2.344c0.779 0 1.406 -0.627 1.406 -1.406V3.281c0 -0.779 -0.627 -1.406 -1.406 -1.406H1.406zm5.156 0c-0.516 0 -0.938 0.423 -0.938 0.938V27.188c0 0.516 0.423 0.938 0.938 0.938s0.938 -0.423 0.938 -0.938V2.813c0 -0.516 -0.423 -0.938 -0.938 -0.938zm4.219 0c-0.779 0 -1.406 0.627 -1.406 1.406V26.719c0 0.779 0.627 1.406 1.406 1.406h0.938c0.779 0 1.406 -0.627 1.406 -1.406V3.281c0 -0.779 -0.627 -1.406 -1.406 -1.406H10.781zm5.625 0c-0.779 0 -1.406 0.627 -1.406 1.406V26.719c0 0.779 0.627 1.406 1.406 1.406h0.938c0.779 0 1.406 -0.627 1.406 -1.406V3.281c0 -0.779 -0.627 -1.406 -1.406 -1.406H16.406zM26.25 3.281V26.719c0 0.779 0.627 1.406 1.406 1.406h0.938c0.779 0 1.406 -0.627 1.406 -1.406V3.281c0 -0.779 -0.627 -1.406 -1.406 -1.406H27.656c-0.779 0 -1.406 0.627 -1.406 1.406zm-3.75 -0.469V27.188c0 0.516 0.423 0.938 0.938 0.938s0.938 -0.423 0.938 -0.938V2.813c0 -0.516 -0.423 -0.938 -0.938 -0.938s-0.938 0.423 -0.938 0.938z" />
                </svg>                
        </div>
        
        <?php
            switch ($retorno->status) {
                case 'PENDING':
                    $status = "<small style='color: #ff8100; font-weight: bold'>Aguardando pagamento</small>";
                    $message = 'Este formulário destina-se à alteração de dados do boleto, como, data de vencimento, valor, descrição e desconto. Após realizar a alteração clique em "Conferência" para verificar os dados e após clique em "Atualizar Cobrança" para efetivar a alteração.';
                    break;
                case 'RECEIVED':
                    $status = "<small style='color: #21a900; font-weight: bold'>Boleto Recebido</small>";
                    break;
                case 'CONFIRMED':
                    $status = "<small style='color: #21a900; font-weight: bold'>Pagamento confirmado</small>";
                break;
                case 'OVERDUE':
                    $status = "<small style='color: tomato; font-weight: bold'>Boleto vencido <span style='font-weight: normal; font-size: 12px'>(Para atualizar qualquer dado, a data de vencimento DEVE ser maior que a data atual!)</span></small>";
                    break;
                case 'REFUNDED':
                    $status = "<small style='color: #21a900; font-weight: bold'>Pagamento estornado</small>";
                    break;               
                default:
                    $status = "<small style='color: #21a900; font-weight: bold'>Não foi possível verificar o status</small>";
                    break;
            }
         ?>
        <?php echo $status ?>
        <p id="message"><?php echo $message; ?></p>
        <?php if($retorno->status === 'PENDING' || $retorno->status === 'OVERDUE'){ ?>        
        <div class="container--dados cb">
            <div class="form-floating mb-3">
                <input id="tipo" type="text" class="form-control input-width" placeholder="Tipo" value="BOLETO"
                    disabled>
                <label for="tipo">Tipo</label>
            </div>                  
            <div class="form-floating mb-3">
                <input id="data-vencimento" class="form-control input-width" placeholder="Data de Vencimento" value="<?php echo $retorno->dueDate; ?>"
                type="date">
                <label for="floatingInput">Data de Vencimento</label>
            </div>
            <div class="form-floating mb-3">
                <input id="valor-boleto" type="text" class="form-control input-width" id="floatingInput" value="<?php echo "R$ " . number_format($retorno->value, 2, ",",".") ; ?>"
                    placeholder="Valor do Boleto" onkeyup="handleFormatCurrency(this, event)" onload="handleFormatCurrency(this, event)">
                <label for="floatingInput">Valor do Boleto</label>
            </div>
        </div>
        <div class="container--textarea">
            <div class="form-floating mb-3" style="text-align: end;">
                <textarea class="form-control" placeholder="Descrição" id="descricao" onkeyup="countWords()"><?php echo $retorno->description; ?></textarea>
                <label for="descricao">Descrição</label>
                <small id="count-words">Caracteres restantes: 500</small>
            </div>
        </div>
        <div class="container--parcelas">            
            <?php 
                $infos = explode("|", $retorno->externalReference);
                $parcelas = explode(",", $infos[1]);
            ?>
            <p class="lista-parcelas">ID da Venda: <?php echo $infos[0]; ?></p>
            <?php if($infos[1]):?>
            <p class="lista-parcelas">Parcelas que este boleto quita: <span><?php echo count($parcelas) ?></span><i class="bi bi-info-circle"  data-bs-toggle="tooltip" data-bs-placement="right" title="Parcelas que foram marcadas na geração do boleto"></i></p>
            <ul>                
                <?php while($parcelas){ ?>
                    <li class="lista-parcelas">Parcela: <?php echo array_shift($parcelas); ?></li>
                <?php } ?>
            </ul>
            <?php endif; ?>
                
        </div>
        <div style="display: flex; justify-content: space-between">
            <div class="container--check">
                <!-- <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="juros" onchange="showField()">
                    <label class="form-check-label label-check" for="juros">Juros</label>
                </div> -->

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="desconto" onchange="showField()" <?php echo $retorno->discount->value == '' ? '' : 'checked' ?>>
                    <label class="form-check-label label-check" for="desconto">Desconto</label>
                </div>
                <!-- <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="parcelamento" onchange="showField()">
                    <label class="form-check-label label-check" for="parcelamento">Parcelamento</label>
                </div> -->
                <!-- <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="correios" onchange="envioBoleto()">
                    <label class="form-check-label label-check" for="correios">Correios</label>
                </div> -->

            </div>
            <div style="font-size: 12px;" id="envio-correios"></div>
        </div>

        <div class="container--juros hide">
            <div class="form-floating input-group input-group-tx mb-3">
                <span class="input-group-text" id="basic-addon1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 21.333" width="16" height="21.333">
                        <path
                            d="M15.609 4.942c0.522 -0.522 0.522 -1.366 0 -1.887s-1.366 -0.522 -1.887 0l-13.333 13.333c-0.522 0.522 -0.522 1.366 0 1.887s1.366 0.522 1.887 0l13.333 -13.333zM5.333 5.333c0 -1.471 -1.196 -2.667 -2.667 -2.667S0 3.862 0 5.333s1.196 2.667 2.667 2.667s2.667 -1.196 2.667 -2.667zM16 16c0 -1.471 -1.196 -2.667 -2.667 -2.667s-2.667 1.196 -2.667 2.667s1.196 2.667 2.667 2.667s2.667 -1.196 2.667 -2.667z" />
                        </svg>
                </span>
                <input type="number" class="form-control input-width-tx" id="valor-juros" placeholder="Valor dos juros">
                <label class="tx-porcentagem" for="valor-juros">Juros</label>
            </div>
            <div class="form-floating input-group input-group-tx mb-3">
                <span class="input-group-text" id="basic-addon1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 21.333" width="16" height="21.333">
                        <path
                            d="M15.609 4.942c0.522 -0.522 0.522 -1.366 0 -1.887s-1.366 -0.522 -1.887 0l-13.333 13.333c-0.522 0.522 -0.522 1.366 0 1.887s1.366 0.522 1.887 0l13.333 -13.333zM5.333 5.333c0 -1.471 -1.196 -2.667 -2.667 -2.667S0 3.862 0 5.333s1.196 2.667 2.667 2.667s2.667 -1.196 2.667 -2.667zM16 16c0 -1.471 -1.196 -2.667 -2.667 -2.667s-2.667 1.196 -2.667 2.667s1.196 2.667 2.667 2.667s2.667 -1.196 2.667 -2.667z" />
                        </svg>
                </span>
                <input type="number" class="form-control input-width-tx" id="valor-multa" placeholder="Valor da multa">
                <label class="tx-porcentagem" for="valor-multa">Multa</label>
            </div>
        </div>

        <div class="container--descontos hide">
            <div class="form-floating input-group input-group-percent mb-3">
                <span class="input-group-text icon-discount" id="basic-addon1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path
                            d="M12.5 0c0.553 0 1 0.447 1 1V1.569c0.391 0.072 0.772 0.2 1.131 0.378l0.316 0.159c0.494 0.247 0.694 0.847 0.447 1.341s-0.847 0.694 -1.341 0.447l-0.319 -0.159c-0.309 -0.156 -0.653 -0.234 -1 -0.234h-0.053c-0.931 0 -1.684 0.753 -1.684 1.684c0 0.688 0.419 1.306 1.059 1.563l1.625 0.65c1.397 0.559 2.316 1.913 2.316 3.419v0.106c0 1.6 -1.05 2.956 -2.5 3.413V15c0 0.553 -0.447 1 -1 1s-1 -0.447 -1 -1V14.394c-0.469 -0.109 -0.919 -0.303 -1.322 -0.572l-0.731 -0.487c-0.459 -0.306 -0.584 -0.928 -0.278 -1.387s0.928 -0.584 1.387 -0.278L11.287 12.156c0.338 0.225 0.731 0.344 1.134 0.344c0.872 0 1.578 -0.706 1.578 -1.578v-0.106c0 -0.688 -0.419 -1.306 -1.059 -1.563l-1.625 -0.65C9.916 8.044 9 6.691 9 5.184C9 3.563 10.047 2.188 11.5 1.694V1c0 -0.553 0.447 -1 1 -1zM0 2C0 1.447 0.447 1 1 1h2.5c2.484 0 4.5 2.016 4.5 4.5c0 1.837 -1.1 3.416 -2.678 4.116l1.606 4.013c0.206 0.512 -0.044 1.094 -0.556 1.3s-1.094 -0.044 -1.3 -0.556L3.322 10H2V14c0 0.553 -0.447 1 -1 1s-1 -0.447 -1 -1V9zM2 8h1.5c1.381 0 2.5 -1.119 2.5 -2.5s-1.119 -2.5 -2.5 -2.5H2V8z" />
                        </svg>
                </span>
                <input id="valor-desconto" type="text" class="form-control input-width-tx"
                    placeholder="Valor do desconto" value="<?php echo $retorno->discount->value? "R$ " . number_format($retorno->discount->value, 2, ",","."):'' ; ?>" onkeyup="handleFormatCurrency(this, event)">
                <label class="tx-porcentagem" for="valor-desconto">Valor do Desconto</label>
            </div>
            <div class="form-floating mb-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                title="Dias antes do vencimento para aplicar desconto">
                <input type="number" class="form-control input-width" id="d-vencimento"
                    placeholder="Dias antes do venc." value="<?php echo $retorno->discount->dueDateLimitDays == 0 ? '' : $retorno->discount->dueDateLimitDays; ?>">
                <label for="d-vencimento">Dias antes do venc.</label>
            </div>
            
            <div class="form-floating mb-3">
                <select class="form-select input-width" id="fixo-percentual" onchange="fixoPercentual()"
                    aria-label="Floating label select example" disabled>
                    <option value="FIXED">Fixo</option>
                    <option value="PERCENTAGE">Percentual</option>
                </select>
                <label for="fixo-percentual">Fixo ou percentual</label>
            </div>
        </div>
        <div class="container--parcelamento hide">
            <div class="form-floating mb-3">
                <input type="number" class="form-control input-width" id="q-parcelas" placeholder="Nº de parcelas">
                <label for="q-parcelas">Nº de parcelas</label>
            </div>
            <div class="form-floating mb-3">
                <input id="valor-parcela" type="text" class="form-control input-width" placeholder="Valor da parcela"
                    onkeyup="handleFormatCurrency(this, event)">
                <label for="valor-parcela">Valor da parcela</label>
            </div>
            
        </div>


    </div>
    <div class="container--butons">
        <button id="btn-conferencia" type="button" onclick="enviarDados()" class="btn btn-warning btn-conf"<?php echo $retorno->status === 'OVERDUE'?'disabled':'' ?> >Conferência</button>
        
        <!-- <a href="<?php echo $retorno->invoiceUrl ?>" target="blank">
        <button class="btn btn-info btn-view">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 23.111" width="26" height="23.111"><path d="M2.889 0C1.295 0 0 1.295 0 2.889V15.889c0 1.593 1.295 2.889 2.889 2.889H10.833l-0.483 1.444H7.222c-0.799 0 -1.444 0.645 -1.444 1.444s0.645 1.444 1.444 1.444H18.778c0.799 0 1.444 -0.645 1.444 -1.444s-0.645 -1.444 -1.444 -1.444H15.65L15.167 18.778H23.111c1.593 0 2.889 -1.295 2.889 -2.889V2.889c0 -1.593 -1.295 -2.889 -2.889 -2.889H2.889zM23.111 2.889V15.889H2.889V2.889H23.111z"/></svg>
        Visualizar
        </button>
        </a> -->
        <a href="<?php echo $retorno->bankSlipUrl ?>" target="blank">
        <button type="button" class="btn btn-secondary btn-pdf" data-bs-toggle="tooltip" data-bs-placement="right" title="Visualizar boleto atual">
        <svg class="print-pdf" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 34.667" width="26" height="34.667"><path d="M21.667 31.417C22.262 31.417 22.75 30.929 22.75 30.333V28.167H26V30.333C26 32.723 24.057 34.667 21.667 34.667H4.333C1.94 34.667 0 32.723 0 30.333V28.167H3.25V30.333C3.25 30.929 3.735 31.417 4.333 31.417H21.667zM17.333 10.833C16.135 10.833 15.167 9.865 15.167 8.667V3.25H4.333C3.735 3.25 3.25 3.735 3.25 4.333V13H0V4.333C0 1.94 1.94 0 4.333 0H15.539C16.69 0 17.787 0.457 18.599 1.27L24.734 7.401C25.546 8.213 26 9.31 26 10.461V13H22.75V10.833H17.333zM5.958 15.167C8.051 15.167 9.75 16.866 9.75 18.958C9.75 21.051 8.051 22.75 5.958 22.75H5.417V24.917C5.417 25.512 4.932 26 4.333 26C3.735 26 3.25 25.512 3.25 24.917V16.25C3.25 15.654 3.735 15.167 4.333 15.167H5.958zM7.583 18.958C7.583 18.058 6.859 17.333 5.958 17.333H5.417V20.583H5.958C6.859 20.583 7.583 19.859 7.583 18.958zM10.833 16.25C10.833 15.654 11.321 15.167 11.917 15.167H13.542C15.336 15.167 16.792 16.622 16.792 18.417V22.75C16.792 24.544 15.336 26 13.542 26H11.917C11.321 26 10.833 25.512 10.833 24.917V16.25zM13 23.833H13.542C14.137 23.833 14.625 23.346 14.625 22.75V18.417C14.625 17.821 14.137 17.333 13.542 17.333H13V23.833zM22.75 15.167C23.346 15.167 23.833 15.654 23.833 16.25C23.833 16.846 23.346 17.333 22.75 17.333H20.583V19.5H22.75C23.346 19.5 23.833 19.987 23.833 20.583C23.833 21.179 23.346 21.667 22.75 21.667H20.583V24.917C20.583 25.512 20.096 26 19.5 26C18.904 26 18.417 25.512 18.417 24.917V16.25C18.417 15.654 18.904 15.167 19.5 15.167H22.75z"/></svg>
        Boleto
        </button>
        </a>
    </div>    
    
    <?php }else{ ?>
        <div class="alert alert-warning" role="alert">
            Somente é possível atualizar boleto com pagamento pendente ou em atraso.
        </div>
        <button onclick="closeWindow()" type="button" class="btn btn-danger btn-sm">Fechar</button>
    <?php } ?>  
</div>
<script>
    $("#data-vencimento").on("change", function(){
        var data = moment($(this).val()).format('YYYY-MM-DD');
        var dataAtual = moment().format('YYYY-MM-DD');      

        if(moment(data).isAfter(dataAtual)){      
            
            console.log("Data válida");
            $("#btn-conferencia").removeAttr("disabled");
        }else{
           console.log("Data inválida");
            $("#btn-conferencia").attr("disabled", true);
        }
       
    });
    $("#btn-conferência").on("mouseover", function(){
        console.log("Atenção na data")
    })

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    
    function fixoPercentual() {
        let fixoPercentual = document.getElementById('fixo-percentual').value;
        let valorDesconto = document.getElementById('valor-desconto');
        let iconDiscount = document.querySelector('.icon-discount');
        if (fixoPercentual == 'FIXED') {
            valorDesconto.setAttribute('onkeyup', 'handleFormatCurrency(this, event)');
            valorDesconto.setAttribute('type', 'text');
            valorDesconto.value = '';
            iconDiscount.innerHTML =
                "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' width='16' height='16'><path d='M12.5 0c0.553 0 1 0.447 1 1V1.569c0.391 0.072 0.772 0.2 1.131 0.378l0.316 0.159c0.494 0.247 0.694 0.847 0.447 1.341s-0.847 0.694 -1.341 0.447l-0.319 -0.159c-0.309 -0.156 -0.653 -0.234 -1 -0.234h-0.053c-0.931 0 -1.684 0.753 -1.684 1.684c0 0.688 0.419 1.306 1.059 1.563l1.625 0.65c1.397 0.559 2.316 1.913 2.316 3.419v0.106c0 1.6 -1.05 2.956 -2.5 3.413V15c0 0.553 -0.447 1 -1 1s-1 -0.447 -1 -1V14.394c-0.469 -0.109 -0.919 -0.303 -1.322 -0.572l-0.731 -0.487c-0.459 -0.306 -0.584 -0.928 -0.278 -1.387s0.928 -0.584 1.387 -0.278L11.287 12.156c0.338 0.225 0.731 0.344 1.134 0.344c0.872 0 1.578 -0.706 1.578 -1.578v-0.106c0 -0.688 -0.419 -1.306 -1.059 -1.563l-1.625 -0.65C9.916 8.044 9 6.691 9 5.184C9 3.563 10.047 2.188 11.5 1.694V1c0 -0.553 0.447 -1 1 -1zM0 2C0 1.447 0.447 1 1 1h2.5c2.484 0 4.5 2.016 4.5 4.5c0 1.837 -1.1 3.416 -2.678 4.116l1.606 4.013c0.206 0.512 -0.044 1.094 -0.556 1.3s-1.094 -0.044 -1.3 -0.556L3.322 10H2V14c0 0.553 -0.447 1 -1 1s-1 -0.447 -1 -1V9zM2 8h1.5c1.381 0 2.5 -1.119 2.5 -2.5s-1.119 -2.5 -2.5 -2.5H2V8z'/></svg>";
        } else {
            valorDesconto.setAttribute('onkeyup', '');
            valorDesconto.setAttribute('type', 'number');
            valorDesconto.value = '';
            iconDiscount.innerHTML =
                "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 21.333' width='16' height='21.333'><path d='M15.609 4.942c0.522 -0.522 0.522 -1.366 0 -1.887s-1.366 -0.522 -1.887 0l-13.333 13.333c-0.522 0.522 -0.522 1.366 0 1.887s1.366 0.522 1.887 0l13.333 -13.333zM5.333 5.333c0 -1.471 -1.196 -2.667 -2.667 -2.667S0 3.862 0 5.333s1.196 2.667 2.667 2.667s2.667 -1.196 2.667 -2.667zM16 16c0 -1.471 -1.196 -2.667 -2.667 -2.667s-2.667 1.196 -2.667 2.667s1.196 2.667 2.667 2.667s2.667 -1.196 2.667 -2.667z'/></svg>";
        }
    }
    // FORMATADOR DO CAMPO VALOR (MOEDA)
    function handleFormatCurrency(target, event) {
        var valor = event.target.value.replace(/\D/g, "");
        valor = (valor / 100).toFixed(2) + "";
        valor = valor.replace(".", ",");
        valor = valor.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
        valor = valor.replace(/(\d)(\d{3}),/g, "$1.$2,");
        event.target.value = valor === "0,00" ? "" : "R$ " + valor;
    }
    // FUNÇÃO RESPONSÁVEL MOSTRAR/OCULTAR OS CAMPOS DE DESCONTO E JUROS
    function showField() {
        var toggle_desconto = document.getElementById('desconto');
        // var toggle_juros = document.getElementById('juros');
        // var toggle_parcelamento = document.getElementById('parcelamento');
        // if (toggle_parcelamento.checked) {
        //     document.querySelector('.container--parcelamento').classList.remove('hide');
        // } else {
        //     document.getElementById('q-parcelas').value = '';
        //     document.getElementById('valor-parcela').value = '';
        //     document.querySelector('.container--parcelamento').classList.add('hide');
        // }
        if (toggle_desconto.checked) {
            document.querySelector('.container--descontos').classList.remove('hide');
        } else {
            document.getElementById('valor-desconto').value = '';
            document.getElementById('d-vencimento').value = '';
            document.querySelector('.container--descontos').classList.add('hide');
        }
        // if (toggle_juros.checked) {
        //     document.querySelector('.container--juros').classList.remove('hide');            
        // } else {
        //     document.getElementById('valor-juros').value = '';
        //     document.getElementById('valor-multa').value = '';
        //     document.querySelector('.container--juros').classList.add('hide');            
        // }
    }

    // function envioBoleto() {
    //     var toggle_correios = document.getElementById('correios');
    //     var icon =
    //         "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 18 16' width='18' height='16'><path d='M3 0C2.447 0 2 0.447 2 1V7h3V6c0 -1.103 0.897 -2 2 -2H14V1c0 -0.553 -0.447 -1 -1 -1H3zM7 5c-0.553 0 -1 0.447 -1 1v1h3c1.103 0 2 0.897 2 2V13H17c0.553 0 1 -0.447 1 -1V6c0 -0.553 -0.447 -1 -1 -1H7zm7.5 2h1c0.275 0 0.5 0.225 0.5 0.5v1c0 0.275 -0.225 0.5 -0.5 0.5H14.5c-0.275 0 -0.5 -0.225 -0.5 -0.5V7.5c0 -0.275 0.225 -0.5 0.5 -0.5zM1 8c-0.553 0 -1 0.447 -1 1v0.406L4.847 12.997c0.044 0.031 0.097 0.05 0.153 0.05s0.109 -0.019 0.153 -0.05L10 9.406V9c0 -0.553 -0.447 -1 -1 -1H1zm9 2.65L5.75 13.8c-0.216 0.159 -0.478 0.247 -0.75 0.247s-0.531 -0.087 -0.75 -0.247L0 10.65V15c0 0.553 0.447 1 1 1H9c0.553 0 1 -0.447 1 -1V10.65z'/></svg>"
    //     if (toggle_correios.checked) {
    //         var envio_correios = document.getElementById('envio-correios');
    //         envio_correios.innerHTML = `${icon} Será enviado pelos correios`;
    //     } else {
    //         var envio_correios = document.getElementById('envio-correios');
    //         envio_correios.innerHTML = ``;
    //     }
    // }
    
    function countWords() {
        var text = document.getElementById('descricao').value;
        var words = text.length;
        var wordCount = document.getElementById('count-words');
        wordCount.innerHTML = `Caracteres restantes: ${500 - words}`;
    }
    window.onload = function(){
        var text = document.getElementById('descricao').value;
        var words = text.length;
        var wordCount = document.getElementById('count-words');
        wordCount.innerHTML = `Caracteres restantes: ${500 - words}`;
    }
    function enviarDados() {
        var tipo = document.getElementById('tipo').value;
        var data_vencimento = document.getElementById('data-vencimento').value;
        var data_vencimento_br = data_vencimento.split('-').reverse().join('/');

        var valor_boleto_full = document.getElementById('valor-boleto').value;
        var valor_boleto = valor_boleto_full.replace('R$ ', '');
        var valor_boleto = valor_boleto.replace('.', '');
        var valor_boleto = valor_boleto.replace('.', '');
        var valor_boleto = valor_boleto.replace(',', '.');
        var valor_boleto = parseFloat(valor_boleto);

        var descricao = document.getElementById('descricao').value;
        var quantidade_parcelas = document.getElementById('q-parcelas').value;

        // var valor_parcela_full = document.getElementById('valor-parcela').value;
        // var valor_parcela = valor_parcela_full.replace('R$ ', '');
        // var valor_parcela = valor_parcela.replace('.', '');
        // var valor_parcela = valor_parcela.replace('.', '');
        // var valor_parcela = valor_parcela.replace(',', '.');
        // var valor_parcela = parseFloat(valor_parcela);

        var valor_desconto_full = document.getElementById('valor-desconto').value;
        var valor_desconto = valor_desconto_full.replace('R$ ', '');
        var valor_desconto = valor_desconto.replace('.', '');
        var valor_desconto = valor_desconto.replace('.', '');
        var valor_desconto = valor_desconto.replace(',', '.');
        var valor_desconto = parseFloat(valor_desconto);
        var dias_vencimento = document.getElementById('d-vencimento').value;
        var fixo_percentual = document.getElementById('fixo-percentual').value;

        var valor_juros = document.getElementById('valor-juros').value;
        var valor_multa = document.getElementById('valor-multa').value;
        // var envio_correios = document.getElementById('correios').checked ? true : false;
        
        // MODAL DE CONFIRMAÇÃO
        var customer = '<?php echo $retorno->customer; ?>';
        document.getElementById('customer').innerHTML = customer;
        document.getElementById('billingType').innerHTML = tipo;        
        document.getElementById('value').innerHTML = valor_boleto_full;
        document.getElementById('dueDate').innerHTML = data_vencimento_br;
        document.getElementById('description').innerHTML = descricao;
        // document.getElementById('parcelamento').checked ? document.getElementById('installment').innerHTML = "Sim" : document.getElementById('installment').innerHTML = "Não aplicado";
        // document.getElementById('installmentCount').innerHTML = quantidade_parcelas == ''? "-" : quantidade_parcelas;
        // document.getElementById('installmentValue').innerHTML = isNaN(valor_parcela)? "-" : valor_parcela_full;
        document.getElementById('desconto').checked ? document.getElementById('discount').innerHTML = "Sim" : document.getElementById('discount').innerHTML = "Não aplicado";
        if(fixo_percentual == 'FIXED'){
            document.getElementById('discountValue').innerHTML = isNaN(valor_desconto)? "-" : valor_desconto_full;
        }else{
            valor_desconto_full = valor_desconto_full.replace('R$ ', '');
            document.getElementById('discountValue').innerHTML = isNaN(valor_desconto)? "-" : valor_desconto_full+"%";
        }

        document.getElementById('dueDateLimitDays').innerHTML = dias_vencimento == ''? "-" : dias_vencimento;

        if(valor_desconto_full == ""){
            document.getElementById('discountType').innerHTML = "-";
        }else{
            document.getElementById('discountType').innerHTML = fixo_percentual == "FIXED" ? "Fixo" : "Percentual";
        }
        
        // document.getElementById('juros').checked ? document.getElementById('interest').innerHTML = "Sim" : document.getElementById('interest').innerHTML = "Não aplicado";
        // document.getElementById('interestValue').innerHTML = valor_juros == ''? "-" : valor_juros+"%";
        // document.getElementById('juros').checked ? document.getElementById('fine').innerHTML = "Sim" : document.getElementById('fine').innerHTML = "Não aplicado";
        // document.getElementById('fineValue').innerHTML = valor_multa == ''? "-" : valor_multa+"%";
        // document.getElementById('correios').checked ? document.getElementById('postalService').innerHTML = "Sim" : document.getElementById('postalService').innerHTML = "Não";

        
        var modal_auditoria = document.getElementById('modal-auditoria');
        modal_auditoria.classList.toggle('modal-auditoria-show');
        

        
        //     var data = [{  
        //         "customer": "?",
        //         "billingType": tipo,
        //         "dueDate": data_vencimento,
        //         "value": valor_boleto,
        //         "description": descricao,
        //         "externalReference": "",
        //         "installmentCount": quantidade_parcelas,
        //         "installmentValue": valor_parcela,
        //         "discount": {
        //             "value": valor_desconto,
        //             "dueDateLimitDays": dias_vencimento,
        //             "type": fixo_percentual
        //         },
        //         "fine": {
        //             "value": valor_multa
        //         },
        //         "interest": {
        //             "value": valor_juros
        //         },
        //         "postalService": envio_correios

        //     }]

        //     console.log(data);        

    }
    function fecharModal(){
        var modal_auditoria = document.getElementById('modal-auditoria');
        modal_auditoria.classList.toggle('modal-auditoria-show');
    }
    function enviarApi(){
        var tipo = document.getElementById('tipo').value;
        var data_vencimento = document.getElementById('data-vencimento').value;
        var data_vencimento_br = data_vencimento.split('-').reverse().join('/');

        var valor_boleto_full = document.getElementById('valor-boleto').value;
        var valor_boleto = valor_boleto_full.replace('R$ ', '');
        var valor_boleto = valor_boleto.replace('.', '');
        var valor_boleto = valor_boleto.replace('.', '');
        var valor_boleto = valor_boleto.replace(',', '.');
        var valor_boleto = parseFloat(valor_boleto);

        var descricao = document.getElementById('descricao').value;
        var quantidade_parcelas = document.getElementById('q-parcelas').value;

        // var valor_parcela_full = document.getElementById('valor-parcela').value;
        // var valor_parcela = valor_parcela_full.replace('R$ ', '');
        // var valor_parcela = valor_parcela.replace('.', '');
        // var valor_parcela = valor_parcela.replace('.', '');
        // var valor_parcela = valor_parcela.replace(',', '.');
        // var valor_parcela = parseFloat(valor_parcela);

        var valor_desconto_full = document.getElementById('valor-desconto').value;
        var valor_desconto = valor_desconto_full.replace('R$ ', '');
        var valor_desconto = valor_desconto.replace('.', '');
        var valor_desconto = valor_desconto.replace('.', '');
        var valor_desconto = valor_desconto.replace(',', '.');
        var valor_desconto = isNaN(parseFloat(valor_desconto))?0:parseFloat(valor_desconto); ;
        var dias_vencimento = document.getElementById('d-vencimento').value == 0 ? 0 : document.getElementById('d-vencimento').value;
        var fixo_percentual = document.getElementById('fixo-percentual').value;
        
        // var valor_juros = document.getElementById('valor-juros').value;
        // var valor_multa = document.getElementById('valor-multa').value;
        // var envio_correios = document.getElementById('correios').checked ? true : false;
        
        $.ajax({            
            url: 'https://www.grupofortune.com.br/integracao/asaas/atualizador_boleto.php',
            type: 'POST',
            data: {
                "id_boleto": '<?php echo $id_boleto; ?>',
                "customer": '<?php echo $retorno->customer; ?>',
                "billingType": tipo,
                "dueDate": data_vencimento,
                "value": isNaN(valor_boleto)? '' : valor_boleto,
                "description": descricao,
                "externalReference": "",
                "installmentCount": 0,
                "installmentValue": 0,                
                "discountValue": isNaN(valor_desconto)? '' : valor_desconto,
                "discountDays": dias_vencimento,
                "discountType": fixo_percentual,
                "fineValue": 0,
                "interestValue": 0,
                "postalService": false
            },
            beforeSend: function(){
                document.getElementsByClassName('container-spinner')[0].style.display = 'flex';
            },       
            success: function (response) {
                document.getElementsByClassName('container-spinner')[0].style.display = 'none';
                console.log(response);
                swal("Boleto Atualizado!", "Você atualizou o boleto", "success")
                .then((value) => {
                    window.close();
                });
            },
            error: function (response) {
                console.log(response);
            }
        });
        
    }
    function closeWindow(){
        window.close();
    }
    $(document).ready(function(){
        if( $("#desconto").is(":checked")){
            $(".container--descontos").removeClass('hide');
        }
     
    });
</script>