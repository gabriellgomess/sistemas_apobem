<?php
include("../../connect_db02.php");

$cpf = $_GET["cliente_cpf"];

$sql = "SELECT * FROM sys_inss_clientes WHERE cliente_cpf LIKE '$cpf'";

$result = mysqli_query($con, $sql);

$row = mysqli_fetch_array($result);

include("../../connect_seguro.php");

//busca de vendas pelo id 
$vendas_id = $_GET["vendas_id"];

$sql = "SELECT * FROM `sys_vendas_seguros` WHERE `vendas_id` = $vendas_id";

$result_venda = mysqli_query($con, $sql);

$row_venda = mysqli_fetch_array($result_venda);

//busca apolice pelo vendas apolice
$sql = "SELECT * FROM `sys_vendas_apolices` WHERE `apolice_id` = " . $row_venda["vendas_apolice"];

$result_apolice = mysqli_query($con, $sql);

$row_apolice = mysqli_fetch_array($result_apolice);

//busca da seguradora pelo vendas banco
$sql = "SELECT * FROM `sys_vendas_banco_seg` WHERE `banco_id` =" . $row_venda["vendas_banco"];

$result_seguradora = mysqli_query($con, $sql);

$row_seguradora = mysqli_fetch_array($result_seguradora);

?>
<style>
    html {
        width: calc(100vw - 25px);
        height: 100vh;
        box-sizing: border-box;
        padding: 10px;
        background: #fbfaf6;
    }

    body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    button {
        border-radius: 20px;
        border: 1px solid #cccc;
        cursor: pointer;
    }

    button:hover {
        box-shadow: 3px 1px 2px #bbb;
    }

    .container-response-cad-venda {
        background: #fbfaf6;
        padding: 10px;
        width: 100%;
        min-height: 500px;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        box-shadow: 0px 1px 8px 1px #ccc;
        transition: all 0.3s ease-out;
        gap: 5px;
    }

    .space-divider-venda {
        margin-top: 25px;
        margin-bottom: 25px;
        border-bottom: 2px solid #cccccc;
    }

    .divprop {
        flex-grow: 1;
        background-color: #FFF;
        height: auto;
        float: left;
        margin-left: 0px;
        margin-bottom: 0px;
        border-radius: 3px;
        transition: box-shadow 0.2s linear;
        padding: 20px;
        background: #fbfaf6;
    }

    .container-response {
        display: flex;
        width: 100%;
        gap: 15px;
        background: #fbfaf6;
        flex-direction: column;
    }

    @media screen and (max-width: 1253px) {
        .container-response {
            flex-direction: column;
        }
    }

    .container-nav {
        display: flex;
        gap: 20px;
        width: 100%;
        justify-content: start;
    }

    @-webkit-keyframes scale-in-tl {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
            -webkit-transform-origin: 0% 0%;
            transform-origin: 0% 0%;
            opacity: 1;
        }

        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
            -webkit-transform-origin: 0% 0%;
            transform-origin: 0% 0%;
            opacity: 1;
        }
    }

    @keyframes scale-in-tl {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
            -webkit-transform-origin: 0% 0%;
            transform-origin: 0% 0%;
            opacity: 1;
        }

        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
            -webkit-transform-origin: 0% 0%;
            transform-origin: 0% 0%;
            opacity: 1;
        }
    }

    .title {
        color: green;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    h3 {
        font-weight: 500;
        font-size: 22px;
        line-height: 22px;
    }

    .icon-title {
        background: #ceeece;
        display: flex;
        height: 50px;
        width: 50px;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
    }

    .m-30 {
        margin-top: 30px;
    }

    * {
        font-family: sans-serif;
    }

    a {
        text-decoration: none;
    }


    button {
        height: 33px;
        border-radius: 5px;
    }

    .linha {
        margin: 10px;
    }

    .linha-botton {
        border-bottom: 1px solid #cccccc;
    }

    .margin-left-and_right {
        margin-right: 10px;
        margin-right: 10px;
    }

    .container-info {
        display: flex;
        gap: 30px;
    }

    .container-novo-cadastro {
        display: flex;
        width: 100%;
        justify-content: end;
    }

    .btn-novo-cadastro {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        /* width: 250px; */
        padding: 20px;
    }

    .icon-novo-cadastro {
        font-size: 40px !important;
    }

    @media (max-width : 560px) {
        .container-info {
            flex-direction: column;
            gap: 10px;
        }

        nav {
            flex-direction: column;
        }

        .btn-novo-cadastro {
            width: 100%;
        }
    }
</style>

<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<div class="container-response">

    <div class="divprop">

        <div class="title">
            <h3>Cliente cadastrado com sucesso!</h3>
            <div class="icon-title">
                <span style="font-size: 40px;" class="material-symbols-outlined">
                    person_check
                </span>
            </div>
        </div>

        <div class="container-info">
            <div>
                <label style="font-size: 12px; font-weight: bold;">Nome: </label>
                <span><?php echo $row['cliente_nome']; ?></span>
            </div>

            <div class="m-15" style="">
                <label style="font-size: 12px; font-weight: bold;">Cpf: </label>
                <span><?php echo $row['cliente_cpf']; ?></span>
            </div>
        </div>

        <nav class="m-30 container-nav">

            <button style="display: flex; align-items: center;justify-content: center; gap: 6px;" onclick="window.location.href=`/sistema/index.php?option=com_k2&amp;view=item&amp;id=4&amp;Itemid=117&amp;cpf=<?php echo $cpf; ?>`">Ficha do cliente <span class="material-symbols-outlined">demography</span></button>
            <button style="display: flex; align-items: center;justify-content: center; gap: 6px;" onclick="window.location.href=`/sistema/index.php/campanha/consultar-clientes`">Consultar clientes <span class="material-symbols-outlined">person_search</span></button>

        </nav>

    </div>

    <div class="linha-botton"></div>

    <div class="divprop">

        <div class="title">
            <h3>Venda cadastrada com sucesso!</h3>
            <div class="icon-title">
                <span style="font-size: 40px;" class="material-symbols-outlined">
                    payments
                </span>
            </div>
        </div>

        <div class="container-info">

            <div>
                <label style="font-size: 12px; font-weight: bold;">Codigo: </label>
                <span><?php echo $vendas_id; ?></span>
            </div>
            <div>
                <label style="font-size: 12px; font-weight: bold;">Tipo: </label>
                <span><?php echo $row_apolice['apolice_tipo'] == 1 ? "Seguro" : "Consignado"; ?></span>
            </div>
            <div>
                <label style="font-size: 12px; font-weight: bold;">Seguradora: </label>
                <span><?php echo $row_seguradora['banco_nm']; ?></span>
            </div>
            <div>
                <label style="font-size: 12px; font-weight: bold;">Apolice: </label>
                <span><?php echo $row_apolice['apolice_nome']; ?></span>
            </div>

        </div>

        <nav class="m-30" style="display: flex; gap: 20px; width: 100%; justify-content: start">

            <button style="display: flex; align-items: center;justify-content: center; gap: 6px;" onclick="window.location.href=`/sistema/index.php?option=com_k2&amp;view=item&amp;layout=item&amp;id=16&amp;Itemid=398&amp;acao=edita_venda_seguro&amp;vendas_id=<?php echo $vendas_id; ?>`">
                Vizualizar venda <span class="material-symbols-outlined">card_travel</span>
            </button>
            <button style="display: flex; align-items: center;justify-content: center; gap: 6px;" onclick="window.location.href=`/sistema/index.php/vendas/consulta-de-vendas-seguros`">
                Consultar vendas <span class="material-symbols-outlined">account_balance_wallet</span>
            </button>

        </nav><br>

        <div class="linha-botton "></div><br>

        <div class="container-novo-cadastro">
            <button class="btn-novo-cadastro" onclick="window.location.href=`/sistema/index.php/campanha/cadastrar-cliente-dev`">Novo <br> cadastro <span class="material-symbols-outlined icon-novo-cadastro">person_add</span></button>
        </div>

    </div>

</div>