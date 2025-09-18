function time() {
    var timestamp = Math.floor(new Date().getTime() / 1000);
    return timestamp;
}

var token_transaction = '';

jQuery(document).ready(function() {

    var lock_ajax = false;

    /*nit é o código da criação da transação necessário criar
    um transação para consultar o cartão e futuramente efetuar o pagamento*/
    var transaction_nit = null;

    /*authorizer_id é o valor que a consulta do cartão retorno, exemplo mastercard = 2,
    futuramente no pagamento será necessário utilizar o mesmo para identificar o mesmo */
    var authorizer_id = null;
    jQuery(document).on("click", "#consulta_cartao_credito_api", function(event) {
        /*evita propagação do event, por exemplo enviar um formulário*/
        event.preventDefault();

        jQuery("#solicita_cancela").attr("transacao_id", "");
        jQuery("#solicita_cancela").attr("esitef_usn", "");
        jQuery("#solicita_cancela").css("display", "none");

        jQuery("#efetiva_cancela").attr("transacao_id", "");
        jQuery("#efetiva_cancela").css("display", "none");

        if (!valida_campos_cartao()) {
            return 0;
        }

        /*urls*/
        var url_request_transaction = "https://sistema.apobem.com.br/integracao/softwareexpress/payment_request_transaction.php?token=EsearR31234fpssa0vfc9o";
        var url_check_card = "https://sistema.apobem.com.br/integracao/softwareexpress/payment_check_card_transaction.php?token=EsearR31234fpssa0vfc9o";
        var num_cartao = "";
        
        if (jQuery("#cliente-cartao").is(":checked")) {
            num_cartao = jQuery("#cliente-cartao").val();
        } else {
            num_cartao = jQuery("#vendas_cartao_num").val();
        }

        /*dados cartão*/
        var card = {
         adm: jQuery("#vendas_cartao_adm").val(),
         cvv: jQuery("#vendas_cartao_cvv").val(),
         num: num_cartao,
         validade_mes: jQuery("#vendas_cartao_validade_mes").val(),
         validade_ano: jQuery("#vendas_cartao_validade_ano").val()
        }
      

        /*Próximo mês desconto data:*/
        var data = new Date();
        data.setDate(data.getDate() + 30);
        /*data.setDate(jQuery('#vendas_dia_desconto').val());*/
        var day = ("0" + data.getDate()).slice(-2);
        var month = ("0" + (data.getMonth() + 1)).slice(-2);
        var year = data.getFullYear();
        data = day + '/' + month + '/' + year;
        var dia_debito = day;        
        /*Próximo mês desconto data:*/

      //   var selectedApolice = jQuery("#vendas_apolice").val();
        
        // Obtém os valores do JSON armazenado no value da opção selecionada
      //   var valoresApoliceSelecionada = JSON.parse(selectedApolice);

        /*valor do plano*/
      //   var valor = valoresApoliceSelecionada.valor;
      var valor = jQuery("#vendas_apolice option:selected").attr("apolice_valor");

        if (valor == '0' || valor == 0) {
            // valor = valoresApoliceSelecionada.valor.replace(".", "").replace(",", ".");
            valor = valor.replace(".", "").replace(",", ".");
        }
        /*tipo plano*/
      //   var plano = valoresApoliceSelecionada.apolice_nome;
      var plano = jQuery("#vendas_apolice option:selected").text();

        /*cliente*/
        var cpf = jQuery("#cliente_cpf").val();

        /*token*/
        token_transaction = (time().toString()) + cpf;

        var username = jQuery('#username').val();

        var user_id = jQuery('#user_id').val();

        /*dados para iniciar a transação*/
        var dados = {
            data_prox_pgt: data,
            valor: valor,
            plano: plano,
            cpf: cpf,
            card: card,
            token: token_transaction,
            username: username,
            user_id: user_id,
            dia_debito: dia_debito
        }
      //   debugger
        /*Mostra o loading*/
        jQuery(this).text('');
        jQuery(this).append('<img style="height: 23px;" src="https://zyonsistemas.com.br/sistema/sistema/imagens/loading.gif"/>');

        if (lock_ajax == false) {

            lock_ajax = true;
            /*primeiro inicia a transação*/
            jQuery.post(url_request_transaction, dados, function(data) {
                /*retorno do ajax*/
                data = JSON.parse(data);
                jQuery('#order_id').val(data.data.pre_authorization.order_id);
                if (data.valid == 'success') {
                  //   debugger
                    /*transação registrada guarda o código*/
                    transaction_nit = data.nit;
                    let order_id = data.data.pre_authorization.order_id;
                    /*dados necessários para validar o cartão*/
                    var dados_check_card = {
                            user_id: user_id,
                            card: card,
                            cpf: cpf,
                            nit: transaction_nit,
                            token: token_transaction,
                            validade_mes: jQuery("#vendas_cartao_validade_mes").val(),
                            validade_ano: jQuery("#vendas_cartao_validade_ano").val()
                        }
                        /*segundo consulta cartão*/
                    jQuery.post(url_check_card, dados_check_card, function(data) {
                        /*retorno do ajax*/
                        data = JSON.parse(data);
                        if (data.valid == 'success') {

                            ativaBtnCancelamento(data);

                            /*guarda o authorizer id para utilizar no terceiro passo que efetua o pagamento*/
                            authorizer_id = data.authorizer_id;
                            /*desabilita campos do cartão*/
                            jQuery("#vendas_cartao_adm").prop("readonly", true);
                            jQuery("#vendas_cartao_cvv").prop("readonly", true);
                            jQuery("#vendas_cartao_num").prop("readonly", true);
                            jQuery("#vendas_cartao_validade_mes").prop("readonly", true);
                            jQuery("#vendas_cartao_validade_ano").prop("readonly", true);
                            jQuery('#consulta_cartao_credito_api').prop("disabled", true);
                            jQuery('#order_id').val(order_id);
                            /*mostra a mensagem do servidor*/
                            jQuery('#consulta_cartao_credito_api').empty();
                           //  jQuery('#consulta_cartao_credito_api').text('Verificar disponibilidade do Cartão');
                           jQuery('#consulta_cartao_credito_api').text('Verificar o Cartão');

                            jQuery('#result_cartao_credito_api').show();
                            jQuery('#result_cartao_credito_api').empty();
                            jQuery('#result_cartao_credito_api').append("<input id='check_card_code' type='hidden' value='" + data.data.pre_authorization.authorizer_code + "'>");
                            jQuery('#result_cartao_credito_api').append(data.message);
                           //  debugger
                            lock_ajax = false;
                        } else {
                            //ativaBtnCancelamento(data);

                            jQuery('#consulta_cartao_credito_api').empty();
                           //  jQuery('#consulta_cartao_credito_api').text('Verificar disponibilidade do Cartão');
                            jQuery('#consulta_cartao_credito_api').text('Verificar o Cartão');

                            /*mostra a mensagem do servidor*/
                            jQuery('#result_cartao_credito_api').show();
                            jQuery('#result_cartao_credito_api').empty();
                            jQuery('#result_cartao_credito_api').append("<input id='check_card_code' type='hidden' value='" + data.data.pre_authorization.authorizer_code + "'>");
                            jQuery('#result_cartao_credito_api').append(data.message);

                            /*destrava ajax para novas requisições caso ocorra um erro*/
                            lock_ajax = false;
                           //  debugger
                        }
                    });

                } else {

                    /*mostra a mensagem do servidor*/
                    jQuery('#consulta_cartao_credito_api').empty();
                  //   jQuery('#consulta_cartao_credito_api').text('Verificar disponibilidade do Cartão');
                  jQuery('#consulta_cartao_credito_api').text('Verificar o Cartão');
                    jQuery('#result_cartao_credito_api').show();
                    jQuery('#result_cartao_credito_api').empty();
                    jQuery('#result_cartao_credito_api').append(data.message);

                    /*destrava ajax para novas requisições caso ocorra um erro*/
                    lock_ajax = false;

                }
            });
        }
    });

    function ativaBtnCancelamento(data) {
        console.log("carregando btn cancelamento.");
        console.log(data.data);
        if (typeof cancelar_preautorizacao === "function") {
            if (data.data.pre_authorization.order_id && data.data.pre_authorization.esitef_usn) {
                jQuery("#solicita_cancela").attr("transacao_id", data.data.pre_authorization.order_id);
                jQuery("#solicita_cancela").attr("esitef_usn", data.data.pre_authorization.esitef_usn);
                jQuery("#solicita_cancela").css("display", "inline-block");

                jQuery("#efetiva_cancela").attr("transacao_id", data.data.pre_authorization.order_id);
            }
        }
    }

    function valida_campos_cartao() {
        var vendas_cartao_adm = jQuery("#vendas_cartao_adm").val();
        var vendas_cartao_cvv = jQuery("#vendas_cartao_cvv").val();
        var vendas_cartao_num = jQuery("#vendas_cartao_num").val();
        var vendas_cartao_validade_mes = jQuery("#vendas_cartao_validade_mes").val();
        var vendas_cartao_validade_ano = jQuery("#vendas_cartao_validade_ano").val();
        var data_desconto = jQuery("#vendas_dia_desconto").val();

        var valido = true;
        var cpf_cartao = jQuery("#cliente_cpf").val();

        if (!cpf_cartao) {
            alert('Necessário um cliente para a verificação');
            valido = false;
        }
        if (vendas_cartao_adm == '' || vendas_cartao_adm == ' ') {
            alert('Preencha o campo administrador do cartão');
            valido = false;
        }
        // if (vendas_cartao_cvv == '' || vendas_cartao_cvv == ' ' || vendas_cartao_cvv.length < 3) {
        // 	alert('Preencha o campo CVV');
        // 	valido = false;
        // }
        if (vendas_cartao_num == '' || vendas_cartao_num == ' ' || vendas_cartao_num.length < 14) {
            alert('Preencha o campo Nº do Cartão');
            valido = false;
        }
        if (vendas_cartao_validade_mes == '' || vendas_cartao_validade_mes == ' ') {
            alert('Preencha o campo Validade do cartão (MÊS)');
            valido = false;
        }
        if (vendas_cartao_validade_ano == '' || vendas_cartao_validade_ano == ' ' || vendas_cartao_validade_ano.length < 4) {
            alert('Preencha o campo Validade do cartão (ANO)');
            valido = false;
        }
        /*		if (!data_desconto) {
        			alert('Preencha a data de desconto');
        			valido = false;
        		}*/
        return valido;
    }
});