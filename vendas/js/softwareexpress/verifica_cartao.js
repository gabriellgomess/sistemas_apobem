/*nit é o código da criação da transação necessário criar
um transação para consultar o cartão e futuramente efetuar o pagamento*/
var transaction_nit = null;

/*authorizer_id é o valor que a consulta do cartão retorno, exemplo mastercard = 2,
futuramente no pagamento será necessário utilizar o mesmo para identificar o mesmo */
var authorizer_id = null;

/*mais dúvidas ler a documentação para entender o fluxo de pagamento que é muito importante*/

/*
Essa merda é uma tentativa de fazer o navegador lixo ler o valor do select do cartão depois de carregado via ajax...
*/
jQuery(document).on('change', '#vendas_cartao_adm', function(){
	console.log(jQuery( "#vendas_cartao_adm" ).val());
});

jQuery(document).ready(function(){
	var lock_ajax = false;
	/*evento de click*/
	jQuery(document).on("click", "#consulta_cartao_credito_api", function(event) {

		/*evita propagação do event, por exemplo enviar um formulário*/
        event.preventDefault();

        if(!valida_campos_cartao()){
        	return 0;
        }
        /*urls*/
        var url_request_transaction = "https://sistema.apobem.com.br/integracao/softwareexpress/payment_request_transaction.php?token=EsearR31234fpssa0vfc9o";
        var url_check_card = "https://sistema.apobem.com.br/integracao/softwareexpress/payment_check_card_transaction.php?token=EsearR31234fpssa0vfc9o";

        /*dados cartão*/
        var card = {
        	adm: jQuery( "#vendas_cartao_adm" ).val(),
			cvv: jQuery( "input[name='vendas_cartao_cvv']" ).val(),
			num: jQuery( "input[name='vendas_cartao_num']" ).val(),
			validade_mes: jQuery( "input[name='vendas_cartao_validade_mes']" ).val(),
			validade_ano: jQuery( "input[name='vendas_cartao_validade_ano']" ).val()
        }
        /*Próximo mês desconto data:*/
        var data = new Date();
        data.setDate(data.getDate()+30);
        /*data.setDate(jQuery('#vendas_dia_desconto').val());*/
        var day = ("0" + data.getDate()).slice(-2);
  		var month = ("0" + (data.getMonth()+1)).slice(-2);
  		var year = data.getFullYear();
        data = day + '/' +month+ '/' + year;
        var dia_debito = day;

        console.log(data);
        /*Próximo mês desconto data:*/

        /*valor do plano*/
        var valor = jQuery('input[name=vendas_apolice]:checked').attr( "valor" );

        if(valor == '0' || valor == 0) {
     		valor = jQuery('#apolice_valor').val().replace(".", "").replace(",", ".");
        }
        /*tipo plano*/
        var plano = jQuery('input[name=vendas_apolice]:checked').next().text();
        /*cliente*/
        var cpf = jQuery('#clients_cpf').val();
        /*token*/
        var token = jQuery('#token_transactions').val();

        var username = jQuery('#username').val();

        var user_id = jQuery('#user_id').val();

        /*dados para iniciar a transação*/
        var dados = {
        	data_prox_pgt: data,
        	valor: valor,
        	plano: plano,
        	cpf: cpf,
        	card: card,
        	token: token,
			username: username,
			user_id: user_id,
			dia_debito: dia_debito
        }

        /*Mostra o loading*/
        jQuery(this).text('');
        jQuery(this).append('<img style="height: 23px;" src="sistema/imagens/loading.gif"/>');

        /*trava ajax evita pilha de requisições*/
        if (lock_ajax == false) {

        	lock_ajax = true;
        	/*primeiro inicia a transação*/
	        jQuery.post( url_request_transaction, dados, function( data ) {
	        	/*retorno do ajax*/
	        	data = JSON.parse(data);

				if(data.valid  == 'success') {
					/*transação registrada guarda o código*/
					transaction_nit = data.nit;
					/*dados necessários para validar o cartão*/
					var dados_check_card = {
						user_id: user_id,
						card: card,
						cpf: cpf,
						nit: transaction_nit,
						token: token,
						validade_mes: jQuery( "input[name='vendas_cartao_validade_mes']" ).val(),
						validade_ano: jQuery( "input[name='vendas_cartao_validade_ano']" ).val()
					}
					/*segundo consulta cartão*/
					jQuery.post( url_check_card, dados_check_card, function( data ) {
						/*retorno do ajax*/
						data = JSON.parse(data);
						if(data.valid  == 'success') {
							/*guarda o authorizer id para utilizar no terceiro passo que efetua o pagamento*/
							authorizer_id = data.authorizer_id;
							/*desabilita campos do cartão*/
							jQuery( "input[name='vendas_cartao_adm']" ).prop( "readonly", true );
							jQuery( "input[name='vendas_cartao_cvv']" ).prop( "readonly", true );
							jQuery( "input[name='vendas_cartao_num']" ).prop( "readonly", true );
							jQuery( "input[name='vendas_cartao_validade_mes']" ).prop( "readonly", true );
							jQuery( "input[name='vendas_cartao_validade_ano']" ).prop( "readonly", true );
							jQuery('#consulta_cartao_credito_api').prop( "disabled", true );
							/*mostra a mensagem do servidor*/
							jQuery('#consulta_cartao_credito_api').empty();							
							jQuery('#consulta_cartao_credito_api').text('Verificar disponibilidade do Cartão');

							jQuery('#result_cartao_credito_api').show();
							jQuery('#result_cartao_credito_api').empty();
							jQuery('#result_cartao_credito_api').append("<input id='check_card_code' type='hidden' value='"+data.data.pre_authorization.authorizer_code+"'>");
							jQuery('#result_cartao_credito_api').append(data.message);
							lock_ajax = false;
						} else {
							jQuery('#consulta_cartao_credito_api').empty();
							jQuery('#consulta_cartao_credito_api').text('Verificar disponibilidade do Cartão');

							/*mostra a mensagem do servidor*/
							jQuery('#result_cartao_credito_api').show();
							jQuery('#result_cartao_credito_api').empty();
							jQuery('#result_cartao_credito_api').append("<input id='check_card_code' type='hidden' value='"+data.data.pre_authorization.authorizer_code+"'>");
							jQuery('#result_cartao_credito_api').append(data.message);

							/*destrava ajax para novas requisições caso ocorra um erro*/
							lock_ajax = false;
						}
					});

				} else {

					/*mostra a mensagem do servidor*/
					jQuery('#consulta_cartao_credito_api').empty();
					jQuery('#consulta_cartao_credito_api').text('Verificar disponibilidade do Cartão');
					jQuery('#result_cartao_credito_api').show();
					jQuery('#result_cartao_credito_api').empty();
					jQuery('#result_cartao_credito_api').append(data.message);

					/*destrava ajax para novas requisições caso ocorra um erro*/
					lock_ajax = false;

				}
			});
    	}
    });
});


function valida_campos_cartao() {
	var vendas_cartao_adm = jQuery( "input[name='vendas_cartao_adm']" ).val();
	var vendas_cartao_cvv =	jQuery( "input[name='vendas_cartao_cvv']" ).val();
	var vendas_cartao_num =	jQuery( "input[name='vendas_cartao_num']" ).val();
	var vendas_cartao_validade_mes = jQuery( "input[name='vendas_cartao_validade_mes']" ).val();
	var vendas_cartao_validade_ano = jQuery( "input[name='vendas_cartao_validade_ano']" ).val();
	var data_desconto = jQuery("#vendas_dia_desconto").val();

	var valido = true;

	if (vendas_cartao_adm == '' || vendas_cartao_adm == ' ') {
		alert('Preencha o campo administrador do cartão');
		valido = false;
	}
	if (vendas_cartao_cvv == '' || vendas_cartao_cvv == ' ' || vendas_cartao_cvv.length < 3) {
		alert('Preencha o campo CVV');
		valido = false;
	}
	if (vendas_cartao_num == '' || vendas_cartao_num == ' ' || vendas_cartao_num.length < 16) {
		alert('Preencha o campo Nº do Cartão');
		valido = false;
	}
	if (vendas_cartao_validade_mes == '' || vendas_cartao_validade_mes == ' ') {
		alert('Preencha o campo Validade do cartão');
		valido = false;
	}
	if (vendas_cartao_validade_ano == '' || vendas_cartao_validade_ano == ' ' || vendas_cartao_validade_ano.length < 4) {
		alert('Preencha o campo Validade do cartão');
		valido = false;
	}
/*	if (!data_desconto) {
		alert('Preencha a data de desconto');
		valido = false;
	}*/
	return valido;
}