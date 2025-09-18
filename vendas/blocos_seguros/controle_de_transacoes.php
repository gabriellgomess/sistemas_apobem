<?php
    $result_transactions = mysql_query("SELECT *, DATE_FORMAT(transacao_data,'%d/%m/%Y %H:%i:%s') AS transacao_data_br FROM sys_vendas_transacoes_tef WHERE transacao_venda_id = '" . $vendas_id . "' OR (transacao_cliente_cpf = '".$row['cliente_cpf']."' AND transacao_valor = '".$row['vendas_valor']."') ORDER BY transacao_data ASC;")
    or die(mysql_error());

    $result_transactions_boleto = mysql_query("SELECT *, DATE_FORMAT(dateCreated,'%d/%m/%Y %H:%i:%s') AS transacao_data_br FROM sys_vendas_transacoes_boleto WHERE vendas_id  = " . $vendas_id .";")
    or die(mysql_error());
$query = "SELECT *, DATE_FORMAT(transacao_data,'%d/%m/%Y %H:%i:%s') AS transacao_data
FROM sys_vendas_transacoes_tef
WHERE transacao_venda_id = '" . $vendas_id . "' OR (transacao_cliente_cpf = '".$row['cliente_cpf']."' AND transacao_valor = '".$row['vendas_valor']."')";


$queryBoleto = "SELECT *, DATE_FORMAT(dateCreated,'%d/%m/%Y %H:%i:%s') AS transacao_data_br
FROM sys_vendas_transacoes_boleto
WHERE vendas_id  = " . $vendas_id .";";

?>
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>

<script type="text/javascript">
    var token = "<?php echo time().$row['cliente_cpf'];?>";
    var transaction_nit = null;

    jQuery(document).ready(function () {

        var lock_ajax = false;

        jQuery(document).on('click', '.verificar_novamente', function (event) {
            event.preventDefault();

            var url_request_transaction =
                "https://sistema.apobem.com.br/integracao/softwareexpress/payment_request_transaction.php?token=EsearR31234fpssa0vfc9o&verificar_novamente=1";
            var url_check_card =
                "https://sistema.apobem.com.br/integracao/softwareexpress/payment_check_card_transaction.php?token=EsearR31234fpssa0vfc9o&verificar_novamente=1";
            var transacao_id = jQuery(this).attr('id');

            var username = jQuery('#username').val();

            var user_id = jQuery('#user_id').val();

            var data = new Date();
            data.setMonth(data.getMonth() + 2);
            var day = ("0" + data.getDate()).slice(-2);
            var month = ("0" + data.getMonth()).slice(-2);
            var year = data.getFullYear();
            data = /* jQuery(this).attr('prox_dia')*/ day + '/' + month + '/' + year;

            var dia_debito = day;

            var dados = {
                data_prox_pgt: data,
                token: token,
                transacao_id: transacao_id,
                user_id: user_id,
                username: username,
                dia_debito: dia_debito
            };

            jQuery(this).text('');
            jQuery(this).append('<img style="height: 14px;" src="sistema/imagens/loading.gif"/>');

            if (lock_ajax == false) {
                jQuery.post(url_request_transaction, dados, function (data) {
                    /*retorno do ajax*/
                    data = JSON.parse(data);
                    console.log(data);

                    transaction_nit = data.nit;
                    
                    console.log("valid 1: "+data.valid);
                    if (data.valid == 'success') {

                        var dados_check_card = {
                            transacao_id: transacao_id,
                            nit: transaction_nit,
                            token: token,
                            user_id: user_id
                        }

                        /*segundo consulta cartão*/
                        jQuery.post(url_check_card, dados_check_card, function (data) {
                            data = JSON.parse(data);
                            console.log(data);

                            console.log("valid 2: "+data.valid);
                            if (data.valid == 'success') {
                                alert("Verificado com sucesso");
                                location.reload();
                            } else {
                                //console.log("data.message: "+data.message);
                                alert("Verificado, cartão inválido");
                                location.reload();
                            }
                        });
                    } else {
                        alert('Erro ao verificar, tente novamente');
                        location.reload();
                    }
                });
            }
        });
    });
</script>



<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">

<div class="linha">
    <h3 class="mypets2">Controle de Transações:</h3>
    <div class="thepet2">
        <div class="linha">

            <?php if($super_user || $diretoria || $edita_transacao): ?>
            <script type="text/javascript">
                $(document).on("click", ".vincular_transacao", function () {
                    vincularTransacao($(this));
                });

                function vincularTransacao(element) {
                    var venda_id = element.attr("venda_id");
                    var transacao_id = element.attr("transacao_id");

                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4) {
                            if (this.status == 200) {
                                json = JSON.parse(this.response);
                                if (json.erro == 0) {
                                    custom_alert(json.mensagem);
                                    element.closest("td").html(
                                        "<img src='sistema/imagens/contrato_1.png' title='A transação pertence a esta venda.'/>"
                                        );
                                } else {
                                    custom_alert(json.mensagem);
                                }
                            } else {
                                custom_alert("Erro:" + this.status);
                            }
                        }
                    };
                    xhttp.open("GET", "sistema/vendas/blocos_seguros/vincular_transacao.php?venda_id=" + venda_id +
                        "&transacao_id=" + transacao_id, true);
                    xhttp.send();
                }
            </script>
            <table style="line-height: .8; margin: 0;">
                <tr>
                    <td colspan="4">Legenda:</td>
                </tr>
                <tr>
                    <td><img src='sistema/imagens/contrato_1.png' /> <span>Transação pertence a esta venda.</span></td>
                    <td><img src='sistema/imagens/contrato_3.png' /> <span>Transação pertence a outra venda.</span></td>
                    <td><img src='sistema/imagens/contrato_0.png' /> <span>Transação não possui vínculo com nenhuma
                            venda apenas com cpf do cliente.</span></td>
                    <td><img src='sistema/imagens/link.png' /> <span>Vincular a transação a esta venda.</span></td>
                </tr>
            </table>
            <?php endif; ?>

            <table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2" id="Teste">
                <thead>
                    <th id="alinhoCabessorro"></th>
                    <th>
                        <div align="left" style="font-size: 10px;">Código:</div>
                    </th>
                    <th>
                        <div align="left" style="font-size: 10px;">Tipo:</div>
                    </th>
                    <th>
                        <div align="left" style="font-size: 10px;">Dia débito:</div>
                    </th>
                    <th>
                        <div align="left" style="font-size: 10px;">Status:</div>
                    </th>
                    <th>
                        <div align="left" style="font-size: 10px;">Usuário:</div>
                    </th>
                    <th>
                        <div align="left" style="font-size: 10px;">Cartão válidade:</div>
                    </th>
                    <th>
                        <div align="left" style="font-size: 10px;">Cartão:</div>
                    </th>
                    <th class="" id="">Data:</th>
                    <th  style="display: none" class="DataColuna sorting_asc" id="clickData">Data Hidden:</th>
                    <th style="width :130px;"></th>
                </thead>
                <tbody>
                    <?php while($row_transactions = mysql_fetch_array( $result_transactions )){ ?>
                    <?php                       
                        $transactions_status = '';
                        if($row_transactions["transacao_status"] == 'VER') {
                            $transactions_status = 'Verificado';
                        }
                        if($row_transactions["transacao_status"] == 'NOV') {
                            $transactions_status = 'Novo';
                        }
                        if($row_transactions["transacao_status"] == 'INV') {
                            $transactions_status = 'Inválido';
                        }
                        if($row_transactions["transacao_status"] == 'INA') {
                            $transactions_status = 'Recorrência Inativada';
                        }
                        if($row_transactions["transacao_status"] == 'EST') {
                            $transactions_status = 'Pagamento Estornado';
                        }
                        if($row_transactions["transacao_status"] == 'PPC') {
                            $transactions_status = 'Pendente de confirmação';
                        }
                        if($row_transactions["transacao_status"] == 'PPN') {
                            $transactions_status = 'Desfeita';
                        }
                        if($row_transactions["transacao_status"] == 'EXP') {
                            $transactions_status = 'Expirado';
                        }
                        if($row_transactions["transacao_status"] == 'PEN') {
                            $transactions_status = 'Aguardando Pagamento';
                        }
                        if($row_transactions["transacao_status"] == 'CON') {
                            $transactions_status = 'Confirmado';
                        }
                        if($row_transactions["transacao_status"] == 'RET') {
                            $transactions_status = 'Retentativa';
                        }
                    ?>
                    <tr>
                        <td style="width: 19px;">
                            <?php if($super_user || $diretoria || $edita_transacao): ?>
                            <?php 
                                if($row_transactions['transacao_venda_id']==$vendas_id)
                                {                                
                                    echo "<img src='sistema/imagens/contrato_1.png' title='A transação pertence a esta venda.'/>";
                                }else{
                                    if($row_transactions['transacao_venda_id']!="0")
                                    {
                                        echo "<img src='sistema/imagens/contrato_3.png' title='A transação pertence a venda ".$row_transactions['transacao_venda_id'].".'/>";
                                    }else{
                                        echo "<img src='sistema/imagens/contrato_0.png' title='A transação não possui vínculo com nenhuma venda.'/>";
                                        echo "<img class='vincular_transacao' src='sistema/imagens/link.png' venda_id='".$vendas_id."' transacao_id='".$row_transactions["transacao_id"]."' title='Vincular a transação a esta venda.' style='cursor: pointer;'/>";
                                    }
                                }
                            ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $row_transactions["transacao_id"]; ?></td>
                        <td>
                            <div align="left" style="font-size: 10px;"><?php $tipo = $row_transactions["transacao_tipo_plano"]; echo  $tipo; ?></div>
                        </td>
                        <td><?php echo $row_transactions["transacao_dia_debito"] ;?></td>
                        <td><?php echo '('.$row_transactions["transacao_status"].') '.$transactions_status; ?></td>
                        <td><?php echo $row_transactions["transacao_username"] ?></td>
                        <td><?php echo $row_transactions["transacao_cartao_validade_mes"].'/'.$row_transactions["transacao_cartao_validade_ano"]; ?>
                        </td>
                        <td>
                            <div align="left" style="font-size: 10px;">
                                <?php echo substr($row_transactions["transacao_cartao_num"], 0, 4); ?> **** ****
                                <?php echo substr($row_transactions["transacao_cartao_num"], -4); ?></div>
                        </td>
                        <td><?php echo $row_transactions["transacao_data_br"]; ?></td>
                        <td style="display: none"><?php echo $row_transactions["transacao_data"]; ?></td>
                        <td style="text-align: center;">
                            <?php if($row_transactions["transacao_status"] != 'VER' && $row_transactions["transacao_status"] != 'CON') :?>
                            <button class="button verificar_novamente"
                                id="<?php echo $row_transactions['transacao_id']; ?>"
                                prox_dia="<?php echo $row_transactions['transacao_dia_debito']; ?>"
                                style="height: 20px; width: 160px; padding: 0px 10px 0px 10px !important; display: none;">
                                Verificar Novamente
                            </button>
                            <?php endif; ?>
                            <input id="nit-<?php echo $row_transactions['transacao_id'];?>" type="hidden"
                                value="<?php echo $row_transactions['transacao_nit'];?>">
                            
                            <?php if($row_transactions['transacao_agendamento_sid']):?>
                            <svg xmlns="http://www.w3.org/2000/svg" style="margin: 5px auto;" class="re-search-icon"
                                id="re-search"
                                onclick="novaBuscaSid('<?php echo $row_transactions['transacao_id']; ?>','<?php echo $row_transactions['transacao_agendamento_sid'];?>','<?php echo $row_transactions["transacao_status"]; ?>','<?php echo $row['cliente_cpf']; ?>')"
                                height="24px" viewBox="0 0 24 24" width="24px" fill="">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M11 6c1.38 0 2.63.56 3.54 1.46L12 10h6V4l-2.05 2.05C14.68 4.78 12.93 4 11 4c-3.53 0-6.43 2.61-6.92 6H6.1c.46-2.28 2.48-4 4.9-4zm5.64 9.14c.66-.9 1.12-1.97 1.28-3.14H15.9c-.46 2.28-2.48 4-4.9 4-1.38 0-2.63-.56-3.54-1.46L10 12H4v6l2.05-2.05C7.32 17.22 9.07 18 11 18c1.55 0 2.98-.51 4.14-1.36L20 21.49 21.49 20l-4.85-4.86z" />
                            </svg>
                            <?php endif;?>
                            <?php if($row_transactions['transacao_nit'] && $row_transactions["transacao_status"] != ''):?>                        
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin: 5px auto;" class="re-search-icon"
                                id="re-search-<?php echo $row_transactions['transacao_id']; ?>"
                                onclick="novaBusca('<?php echo $row_transactions['transacao_id']; ?>','<?php echo $row_transactions['transacao_authorizer_id'] ?>','<?php echo $row_transactions["transacao_status"]; ?>')"
                                height="24px" viewBox="0 0 24 24" width="24px" fill="">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M11 6c1.38 0 2.63.56 3.54 1.46L12 10h6V4l-2.05 2.05C14.68 4.78 12.93 4 11 4c-3.53 0-6.43 2.61-6.92 6H6.1c.46-2.28 2.48-4 4.9-4zm5.64 9.14c.66-.9 1.12-1.97 1.28-3.14H15.9c-.46 2.28-2.48 4-4.9 4-1.38 0-2.63-.56-3.54-1.46L10 12H4v6l2.05-2.05C7.32 17.22 9.07 18 11 18c1.55 0 2.98-.51 4.14-1.36L20 21.49 21.49 20l-4.85-4.86z" />
                                </svg>
                             <?php endif;?>                                  
                            <?php if($row_transactions["transacao_status"] == ''):?>                                                    
                                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000" class="update-icon" onclick="updateData('<?php echo $row_transactions['transacao_id']; ?>','<?php echo $row_transactions["transacao_data"]; ?>','<?php echo $row['cliente_cpf']; ?>',<?php echo $v; ?>)">
                                    <g>
                                        <rect fill="none" height="24" width="24"/>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M11,8.75v3.68c0,0.35,0.19,0.68,0.49,0.86l3.12,1.85c0.36,0.21,0.82,0.09,1.03-0.26c0.21-0.36,0.1-0.82-0.26-1.03 l-2.87-1.71v-3.4C12.5,8.34,12.16,8,11.75,8S11,8.34,11,8.75z M21,9.5V4.21c0-0.45-0.54-0.67-0.85-0.35l-1.78,1.78 c-1.81-1.81-4.39-2.85-7.21-2.6c-4.19,0.38-7.64,3.75-8.1,7.94C2.46,16.4,6.69,21,12,21c4.59,0,8.38-3.44,8.93-7.88 c0.07-0.6-0.4-1.12-1-1.12c-0.5,0-0.92,0.37-0.98,0.86c-0.43,3.49-3.44,6.19-7.05,6.14c-3.71-0.05-6.84-3.18-6.9-6.9 C4.94,8.2,8.11,5,12,5c1.93,0,3.68,0.79,4.95,2.05l-2.09,2.09C14.54,9.46,14.76,10,15.21,10h5.29C20.78,10,21,9.78,21,9.5z"/>
                                        </g>
                                    </g>
                                </svg>
                            <?php endif;?>                        
                            
                        </td>
                        
                    </tr>
                    <?php } ?>


                    <?php while($row_transactions_boleto = mysql_fetch_array( $result_transactions_boleto )){ ?>
                    <?php
                        $transactions_status = '';

                    ?>
                    <tr>
                        <td>
                            <?php if($super_user || $diretoria || $edita_transacao): ?>
                            <?php 
                                if($row_transactions_boleto['vendas_id']==$vendas_id){                                
                                    echo "<img src='sistema/imagens/contrato_1.png' title='A transação pertence a esta venda.'/>";
                                }else{
                                    if($row_transactions_boleto['vendas_id']!="0")
                                    {
                                        echo "<img src='sistema/imagens/contrato_3.png' title='A transação pertence a venda ".$row_transactions_boleto['vendas_id'].".'/>";
                                    }else{
                                        echo "<img src='sistema/imagens/contrato_0.png' title='A transação não possui vínculo com nenhuma venda.'/>";
                                        echo "<img class='vincular_transacao' src='sistema/imagens/link.png' venda_id='".$vendas_id."' transacao_id='".$row_transactions_boleto["transacao_id"]."' title='Vincular a transação a esta venda.' style='cursor: pointer;'/>";
                                    }
                                }
                            ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $row_transactions_boleto["transacao_id"]; ?></td>
                        <td>
                            <div align="left" style="font-size: 10px;"><?php echo $row_transactions_boleto["description"]; ?></div>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($row_transactions_boleto["dueDate"]));?></td>
                        <td><?php echo $row_transactions_boleto["status"];?></td>
                        <td><?php echo $row_transactions_boleto["username"]; ?></td>
                        <td><?php //echo $row_transactions["transacao_cartao_validade_mes"].'/'.$row_transactions["transacao_cartao_validade_ano"]; ?>
                        </td>
                        <td>
                            <div align="left" style="font-size: 10px;">
                                <?php //echo substr($row_transactions["transacao_cartao_num"], 0, 4); ?> **** ****
                                <?php //echo substr($row_transactions["transacao_cartao_num"], -4); ?></div>
                        </td>
                        <td id=""><?php echo $row_transactions_boleto["transacao_data_br"]; ?></td>
                        <td id="data_id">
                            <p style="visibility: hidden; height: 1px; margin: 0"><?php echo $row_transactions_boleto["dateCreated"]; ?></p>
                            <div hidden class="link_hidden"> <?php echo $row_transactions_boleto['invoiceUrl'];?> </div>
                            <?php //if($row_transactions["transacao_status"] != 'VER' && $row_transactions["transacao_status"] != 'CON' ):?>
                            <div style="display: flex; justify-content: center; align-items: center;">
                                 <?php if ($row_transactions_boleto['invoiceUrl']) : ?>
                            <a href="<?php echo $row_transactions_boleto['invoiceUrl'];?>" target="_blank" rel="noopener noreferrer">
                                <img style="margin: 0;" class="img_boleto" src="http://seguros.grupofortune.com.br/sistema/sistema/vendas/blocos_seguros/boleto-logo.jpg">
                            </a>
                            <?php else: ?>
                                <p>N/A</p>

                            <?php endif; ?>
                            
                            <?php //endif; ?>                            
                                <svg fill="grey" style="margin-left: 15px; cursor: pointer" onclick="atualizaBoleto('<?php echo $row_transactions_boleto['id_boleto']; ?>', '<?php echo $row_transactions_boleto['customer']; ?>')" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" width="18" height="18"><path d="M16.58 0.763c-0.77 -0.77 -2.014 -0.77 -2.784 0L12.737 1.818l3.442 3.442 1.058 -1.058c0.77 -0.77 0.77 -2.014 0 -2.784L16.58 0.763zm-10.519 7.734c-0.214 0.214 -0.38 0.478 -0.475 0.77l-1.041 3.122c-0.102 0.302 -0.021 0.636 0.204 0.865s0.559 0.306 0.865 0.204l3.122 -1.041c0.288 -0.098 0.552 -0.26 0.77 -0.475L15.388 6.057 11.943 2.612 6.061 8.497zM3.375 2.25C1.512 2.25 0 3.762 0 5.625V14.625c0 1.863 1.512 3.375 3.375 3.375H12.375c1.863 0 3.375 -1.512 3.375 -3.375V11.25c0 -0.622 -0.503 -1.125 -1.125 -1.125s-1.125 0.503 -1.125 1.125v3.375c0 0.622 -0.503 1.125 -1.125 1.125H3.375c-0.622 0 -1.125 -0.503 -1.125 -1.125V5.625c0 -0.622 0.503 -1.125 1.125 -1.125h3.375c0.622 0 1.125 -0.503 1.125 -1.125s-0.503 -1.125 -1.125 -1.125H3.375z"/></svg>
                            </div>
                                                       
                        </td>                        
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="cortina-modal">
        <div class="container-close-modal">
            <div class="close-modal-atualizador">
                <span class="text">Fechar</span><span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                            d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z">
                        </path>
                    </svg>
                </span>
            </div>
        </div>
        <div class="modal-atualizador">
            <h4>Atualizador de Dados</h4>
            <p>Nome</p>
            <p>Idade</p>
            <p>CPF</p>
        </div>                         
    </div>
</div>
<script type="text/javascript">
    function atualizaBoleto(id_boleto, customer){
        // var customer = customer.split("_");
        window.open("https://sistema.apobem.com.br/integracao/asaas/modal.php?id_boleto="+id_boleto+"&customer="+customer, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,left=" + (screen.width - 1000) / 2 + ",top=" + (screen.height - 800) / 2 + ",width=1000,height=800"); 
       
    }
    function novaBusca(id, auth_id, status) {
        var user = <?php echo $super_user == "1" || $diretoria == "1" ? "1" : "0"; ?> 
        var nit = jQuery('#nit-' + id).val();
        jQuery(".cortina-modal").addClass("cortina-modal-show");
        jQuery(".modal-atualizador").addClass("modal-atualizador-show");
        jQuery.ajax({
            url: "https://sistema.apobem.com.br/integracao/softwareexpress/consulta_transaction.php", 
            type: "POST",
            data: {
                id: id,
                nit: nit,
                auth_id: auth_id,
                status: status,
                user: user
            },
            beforeSend: function(){
                jQuery("body").css("cursor", "progress");
                jQuery("body").css("overflow", "none");
                jQuery(".modal-atualizador").html(`<div class="scaling-dots">
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                    </div>`);
                jQuery(".close-modal-atualizador").click(function(){
                    jQuery("body").css("cursor", "default");
                    jQuery("body").css("overflow", "auto");               
                    jQuery(".cortina-modal").removeClass("cortina-modal-show");
                    jQuery(".modal-atualizador").removeClass("modal-atualizador-show");
                });

            },
            success: function(data){
                jQuery.ajax({

                })
                jQuery("body").css("overflow", "none");
                jQuery("body").css("cursor", "default");
                jQuery(".modal-atualizador").html(data);
                
                jQuery(".close-modal-atualizador").click(function(){    
                    jQuery("body").css("cursor", "default");
                    jQuery("body").css("overflow", "auto");                  
                    jQuery(".cortina-modal").removeClass("cortina-modal-show");
                    jQuery(".modal-atualizador").removeClass("modal-atualizador-show");
                });
            },
            error: function(erro){
                jQuery(".modal-atualizador").html(`<p>${erro}</p>`);
                jQuery(".close-modal-atualizador").click(function(){                    
                    jQuery(".cortina-modal").removeClass("cortina-modal-show");
                    jQuery(".modal-atualizador").removeClass("modal-atualizador-show");
                });
            }
        });

    }

    function novaBuscaSid(id,sid,status, cpf) {      
        var user = <?php echo $super_user == "1" || $diretoria == "1" ? "1" : "0"; ?> 
        jQuery(".cortina-modal").addClass("cortina-modal-show");
        jQuery(".modal-atualizador").addClass("modal-atualizador-show");
        jQuery.ajax({
            url: "https://sistema.apobem.com.br/integracao/softwareexpress/consulta_schedule.php", 
            type: "POST",
            data: {
                id: id,
                sid: sid,
                status: status,
                user: user,
                cpf: cpf
            },
            beforeSend: function(){
                jQuery("body").css("cursor", "progress");
                jQuery("body").css("overflow", "none");
                jQuery(".modal-atualizador").html(`<div class="scaling-dots">
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                    </div>`);
                jQuery(".close-modal-atualizador").click(function(){   
                    jQuery("body").css("cursor", "default");
                    jQuery("body").css("overflow", "auto");                     
                    jQuery(".cortina-modal").removeClass("cortina-modal-show");
                    jQuery(".modal-atualizador").removeClass("modal-atualizador-show");
                });

            },
            success: function(data){

                jQuery("body").css("overflow", "none");
                jQuery("body").css("cursor", "default");
                jQuery(".modal-atualizador").html(data);
                
                jQuery(".close-modal-atualizador").click(function(){  
                    jQuery("body").css("cursor", "default");
                    jQuery("body").css("overflow", "auto");                    
                    jQuery(".cortina-modal").removeClass("cortina-modal-show");
                    jQuery(".modal-atualizador").removeClass("modal-atualizador-show");
                });
            },
            error: function(erro){
                jQuery(".modal-atualizador").html(`<p>${erro}</p>`);
                jQuery(".close-modal-atualizador").click(function(){                    
                    jQuery(".cortina-modal").removeClass("cortina-modal-show");
                    jQuery(".modal-atualizador").removeClass("modal-atualizador-show");
                });
            }
        });

    }

    function updateData(id, date, cpf, id_venda){
        
        var user = <?php echo $super_user == "1" || $diretoria == "1" ? "1" : "0"; ?> ;
        var order_id = id;
        var datefull = date.split(" ")
        var date = datefull[0].split("-").reverse().join("/");
        var cpf = cpf;
        jQuery(".cortina-modal").addClass("cortina-modal-show");
        jQuery(".modal-atualizador").addClass("modal-atualizador-show");
        jQuery.ajax({
            url: "https://sistema.apobem.com.br/integracao/softwareexpress/consulta_pedido.php", 
            type: "POST",
            data: {
                order_id: order_id,
                data: date,
                cpf: cpf,
                user: user,
                id_venda: id_venda
            },
            beforeSend: function(){
                jQuery("body").css("cursor", "progress");
                jQuery("body").css("overflow", "none");
                jQuery(".modal-atualizador").html(`<div class="scaling-dots">
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                        <div></div>
                                                    </div>`);
                jQuery(".close-modal-atualizador").click(function(){   
                    jQuery("body").css("cursor", "default");
                    jQuery("body").css("overflow", "auto");                     
                    jQuery(".cortina-modal").removeClass("cortina-modal-show");
                    jQuery(".modal-atualizador").removeClass("modal-atualizador-show");                    
                });
            },
            success: function(data){
            
                jQuery("body").css("overflow", "none");
                jQuery("body").css("cursor", "default");
                jQuery(".modal-atualizador").html(data);
               
               
                jQuery(".close-modal-atualizador").click(function(){  
                    jQuery("body").css("cursor", "default");
                    jQuery("body").css("overflow", "auto");                    
                    jQuery(".cortina-modal").removeClass("cortina-modal-show");
                    jQuery(".modal-atualizador").removeClass("modal-atualizador-show");
                    window.location.reload();
                });
            },
            error: function(erro){
                jQuery(".modal-atualizador").html(`<p>${erro}</p>`);
                jQuery(".close-modal-atualizador").click(function(){                    
                    jQuery(".cortina-modal").removeClass("cortina-modal-show");
                    jQuery(".modal-atualizador").removeClass("modal-atualizador-show");
                });
            }
        });
    }

    var venda_status = jQuery("select[name='vendas_status']").val();
    /* STATUS QUE PODEM EFETUAR A VERIFICAÇÃO NOVAMENTE */
    /* Aguardando Auditor, Em Auditoria, Não Debitada, Enviado para Cobrança, Enviado para RETENÇÃO, Venda Retida, Inadimplente */
    if (venda_status == '1' || venda_status == '2' || venda_status == '8' || venda_status == '15' || venda_status ==
        '19' || venda_status == '58' || venda_status == '45' || venda_status == '88') {
        jQuery('.verificar_novamente').show();
        jQuery('.container_consulta_cartao_credito_api').show();
    }
    /* STATUS QUE PODEM EFETUAR A VERIFICAÇÃO NOVAMENTE */

    jQuery(document).ready(function () {
        jQuery.fn.dataTable.moment('DD-MM-YYYY');

        jQuery(".img_boleto").click(function () {
            var link = jQuery(this).parent().find('[class="link_hidden"]').text()
            window.open(link, '_blank');
        });


        //jQuery.fn.dataTable.moment( 'DD/MM/YY HH:mm:ss' );    //Formatação
        var rows_selected = [];
        var bookid_value = [];

        jQuery.fn.dataTable.moment('DD-MM-YYYY');

        // var table = jQuery('#Teste').DataTable({
        //     "language": {
        //         "search": ' ',
        //         "searchPlaceholder": "Search",
        //     },
        //     lengthChange: false,
        //     "scrollY": "1000px",
        //     "scrollCollapse": true,
        //     "paging": false,
        //     'columnDefs': [{
        //         'targets': 9,
        //         'searchable': true,
        //         'orderable': true,
        //         'width': '1%',
        //         'bSort': false,
        //         "type": 'date'
        //     }],
        //     'order': [
        //         [9, 'desc']
        //     ],

        //     'rowCallback': function (row, data, dataIndex) {
        //         var rowId = data[0];
        //         if (jQuery.inArray(rowId, rows_selected) !== -1) {

        //             jQuery(row).find('input[type="checkbox"]').prop('checked', true);
        //             jQuery(row).addClass('selected');
        //         }
        //     }
        // });


        jQuery("#clickData").trigger("click");

        jQuery(".mypets2").click(function () {
            jQuery("#alinhoCabessorro").trigger("click");
        });

    });

</script>
<style>
    .dataTables_filter,
    .dataTables_length,
    .dataTables_info,
    .dataTables_paginate,
    .paging_simple_numbers {
        display: none;
    }

    .img_boleto {
        width: 40px;
        text-align: center;
        align-items: center !important;
        float: center !important;
        margin-left: 34px;
    }
</style>