var lock_ajax = false;

jQuery(document).ready(function()
{
	console.log("Carregou o arquivo verifica_cartao_edita_venda.js");

	/*evento de click*/
	jQuery(document).on("click", "#consulta_cartao_credito_api", function(event) 
	{
		/*evita propagação do event, por exemplo enviar um formulário*/
        event.preventDefault();

        // verificar se existe alguma transação no status VER que não esteja vinculada à esta venda.
        // Se tiver, vincular esta transação na venda e retornar com verificado.
        buscarVinculoTransacaoVER(jQuery(this));


        //somente se não houver transação VER desvinculada, consultar o cartão via API
        
    });
	
	jQuery(document).on("click", "#consulta_cartao_atrasadas_api", function(event) 
	{
		
        event.preventDefault();
        cobraCartaoCreditoApi(jQuery(this));
    });
});

function buscarVinculoTransacaoVER(element)
{
	var vendas_id = element.attr('venda_id');
	var cliente_cpf = jQuery('#cpf').text();
	var vendas_valor = jQuery('select[name=vendas_apolice] :selected').attr('apolice_valor');	
 
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4)
		{
			if(this.status == 200)
			{
				response = JSON.parse(this.responseText);
				if(response.erro == 0)
				{
					if(response.transacao_id)
					{
						vincularTransacaoVER(vendas_id, response.transacao_id);
						console.log(response);
						console.log("vincular transação para a venda.");						
					}else{
						consultaCartaoCreditoApi( element );
						console.log(response.mensagem);
					}
				}else{
					custom_alert(response.mensagem);
				}				
			}else{
				custom_alert("Erro: "+this.status);
			}
		}
	};
	xhttp.open("GET","sistema/vendas/blocos_seguros/buscar_vinculo_transacao.php?vendas_id="+vendas_id+"&cliente_cpf="+cliente_cpf+"&vendas_valor="+vendas_valor, true);
	xhttp.send();	
}

function vincularTransacaoVER(venda_id, transacao_id){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if (this.readyState == 4)
        {
            if(this.status == 200){
                json = JSON.parse(this.response);
                if(json.erro==0)
                {
                    console.log(json.mensagem);
                    jQuery('#result_cartao_credito_api').show();
					jQuery('#result_cartao_credito_api').empty();
					jQuery('#result_cartao_credito_api').append("<span style='color: green; font-weight: bolder;'> Transação vinculada. </span>");
					location.reload();
					lock_ajax = false;
                }else{
                    custom_alert(json.mensagem);
                }                                    
            }else{ 
                custom_alert("Erro:" + this.status);
            }                
        }
    };
    xhttp.open("GET", "sistema/vendas/blocos_seguros/vincular_transacao.php?venda_id="+venda_id+"&transacao_id="+transacao_id, true);
    xhttp.send();
}


function consultaCartaoCreditoApi(thiselement)
{
   console.log("thiselement: ",thiselement);

	 if(!valida_campos_cartao()){
        	return 0; //para execução do código
        }
        /*urls*/
        var url_request_transaction = "https://sistema.apobem.com.br/integracao/softwareexpress/payment_request_transaction.php?token=EsearR31234fpssa0vfc9o";
        var url_check_card = "https://sistema.apobem.com.br/integracao/softwareexpress/payment_check_card_transaction.php?token=EsearR31234fpssa0vfc9o";

        /*dados cartão*/
        var card = {
        	adm: jQuery( "#vendas_cartao_adm" ).val(),
			band: jQuery( "input[name='vendas_cartao_band']" ).val(),
			cvv: jQuery( "input[name='cartao_cvv']" ).val(),
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
        var valor = jQuery('select[name=vendas_apolice] :selected').attr('apolice_valor');

        if(valor == '0' || valor == 0) {
     		valor = jQuery('input[name=vendas_valor]').val().replace(".", "").replace(",", ".");
        }
        /*tipo plano*/
        var plano = jQuery('select[name=vendas_apolice] :selected').attr('apolice_tipo');
        /*cliente*/
        var cpf = jQuery('#cpf').text();

        var username = jQuery('#username').val();

        var user_id = jQuery('#user_id').val();

        var venda_id = thiselement.attr('venda_id');

        /*dados para iniciar a transação*/
        var dados = {
        	venda_id: venda_id,
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
        thiselement.text('');
        thiselement.append('<img style="height: 23px;" src="https://grupofortune.com.br/sistema/sistema/imagens/loading.gif"/>');
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
						card: card,
						nit: transaction_nit,
						token: token,
						user_id: user_id,
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
							jQuery( "input[name='vendas_cartao_band']" ).prop( "readonly", true );
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
							jQuery('#result_cartao_credito_api').append(data.message);
							location.reload();
							lock_ajax = false;
						} else {
							jQuery('#consulta_cartao_credito_api').empty();
							jQuery('#consulta_cartao_credito_api').text('Verificar disponibilidade do Cartão');

							/*mostra a mensagem do servidor*/
							jQuery('#result_cartao_credito_api').show();
							jQuery('#result_cartao_credito_api').empty();
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
}

// Função para salvar log em arquivo
function salvaLog(mensagem) {
    jQuery.ajax({
        url: '/var/www/html/sistema/sistema/vendas/blocos_seguros/salva_log_js.php',
        type: 'POST',
        data: {
            mensagem: mensagem
        },
        success: function(response) {
            console.log('Log salvo com sucesso');
        },
        error: function(xhr, status, error) {
            console.error('Erro ao salvar log:', error);
        }
    });
}

function cobraCartaoCreditoApi(thiselement)
{
    salvaLog("Iniciando cobrança por cartão de crédito");
    
    // Coleta os IDs das parcelas marcadas
    var markedCheckbox = document.getElementsByName('cobrar');
    salvaLog("Checkboxes encontrados: " + markedCheckbox.length);
    
    var i = 1;
    var transacao_ids = '';
    for (var checkbox of markedCheckbox) {
        salvaLog("Verificando checkbox - ID: " + checkbox.id + " - Checked: " + checkbox.checked);
        
        if (checkbox.checked){
            if (i == 1){
                transacao_ids = checkbox.id;
            }else{
                transacao_ids = transacao_ids+','+checkbox.id;
            }
            i++;
        }
    }
    
    salvaLog("IDs das parcelas a serem cobradas: " + transacao_ids);
    
    if(!valida_campos_atrasadas()){
        salvaLog("Validação dos campos falhou");
        return 0; //para execução do código
    }
    /*urls*/
    // var url_request_transaction = "https://sistema.apobem.com.br/integracao/softwareexpress/transacao_individual/request_transacao_individual.php?token=EsearR31234fpssa0vfc9o";
    // var url_do_transaction = "https://sistema.apobem.com.br/integracao/softwareexpress/transacao_individual/do_transacao_individual.php?token=EsearR31234fpssa0vfc9o";

    var url_request_transaction = "https://sistema.apobem.com.br/integracao/softwareexpress/transacao_individual/request_transacao_individual.php?token=EsearR31234fpssa0vfc9o";
    var url_do_transaction = "https://sistema.apobem.com.br/integracao/softwareexpress/transacao_individual/do_transacao_individual.php?token=EsearR31234fpssa0vfc9o";
    
    /*dados cartão*/
    var card = {
        adm: jQuery( "#atrasadas_cartao_adm" ).val(),
        band: jQuery( "input[name='atrasadas_cartao_band']" ).val(),
        cvv: jQuery( "input[name='cartao_cvv']" ).val(),
        num: jQuery( "input[name='atrasadas_cartao_num']" ).val(),
        validade_mes: jQuery( "input[name='atrasadas_cartao_validade_mes']" ).val(),
        validade_ano: jQuery( "input[name='atrasadas_cartao_validade_ano']" ).val()
    }
    /*Próximo mês desconto data:*/
    var data = new Date();
    data.setDate(data.getDate()+30);
    /*data.setDate(jQuery('#atrasadas_dia_desconto').val());*/
    var day = ("0" + data.getDate()).slice(-2);
    var month = ("0" + (data.getMonth()+1)).slice(-2);
    var year = data.getFullYear();
    data = day + '/' +month+ '/' + year;
    data_do = data;
    var dia_debito = day;

    console.log(data);
    /*Próximo mês desconto data:*/

    /*valor do plano*/
    var valor = jQuery('input[name=total_cobrar]').val();
    /*tipo plano*/
    var plano = jQuery('select[name=vendas_apolice] :selected').attr('apolice_tipo');
    /*cliente*/
    var cpf = jQuery('#cpf').text();

    var username = jQuery('#username').val();

    var user_id = jQuery('#user_id').val();

    var venda_id = thiselement.attr('venda_id');

    /*dados para iniciar a transação*/
    var dados = {
        venda_id: venda_id,
        data_prox_pgt: data,
        transacao_valor: valor,
        plano: plano,
        cpf: cpf,
        card_num: jQuery( "input[name='atrasadas_cartao_num']" ).val(),
        card_adm: jQuery( "#atrasadas_cartao_adm" ).val(),
        token: "EsearR31234fpssa0vfc9o",
        username: username,
        user_id: user_id,
        dia_debito: dia_debito,
        card_validade_mes: jQuery( "input[name='atrasadas_cartao_validade_mes']" ).val(),
        card_validade_ano: jQuery( "input[name='atrasadas_cartao_validade_ano']" ).val()
    }
    console.log(dados);

    /*Mostra o loading*/
    thiselement.text('');
    thiselement.append('<img style="height: 23px;" src="https://grupofortune.com.br/sistema/sistema/imagens/loading.gif"/>');
     /*trava ajax evita pilha de requisições*/
    if (lock_ajax == false) {

        lock_ajax = true;
        /*primeiro inicia a transação*/
        jQuery.post( url_request_transaction, dados, function( data ) {
            salvaLog("Retorno do REQUEST transaction:");
            salvaLog(JSON.stringify(data));
            /*retorno do ajax*/
            console.log("# DATA CRU DO REQUEST #");
            console.log(data);
            data = JSON.parse(data);
            
            console.log("# DATA COM PARSE #");
            console.log(data);
            
            if(data.code  == 0) {
                
                /*transação registrada guarda o código*/
                transaction_nit = data.payment.nit;
                console.log(transaction_nit);
                transaction_id = data.payment.order_id;
                console.log(transaction_id);

                /*guarda o authorizer id para utilizar no terceiro passo que efetua o pagamento*/
                authorizer_id = data.authorizer_id;
                /*desabilita campos do cartão*/
                jQuery( "input[name='atrasadas_cartao_adm']" ).prop( "readonly", true );
                jQuery( "input[name='atrasadas_cartao_band']" ).prop( "readonly", true );
                jQuery( "input[name='atrasadas_cartao_cvv']" ).prop( "readonly", true );
                jQuery( "input[name='atrasadas_cartao_num']" ).prop( "readonly", true );
                jQuery( "input[name='atrasadas_cartao_validade_mes']" ).prop( "readonly", true );
                jQuery( "input[name='atrasadas_cartao_validade_ano']" ).prop( "readonly", true );
                jQuery('#consulta_cartao_atrasadas_api').prop( "disabled", true );
                /*mostra a mensagem do servidor*/
                jQuery('#consulta_cartao_atrasadas_api').empty();
                jQuery('#consulta_cartao_atrasadas_api').hide();

                jQuery('#result_cartao_atrasadas_api').show();
                jQuery('#result_cartao_atrasadas_api').empty();
                if(data.message == "OK. Transaction successful."){
                    var retorno_tela = "Transação iniciada com sucesso. AGUARDANDO CONFIRMAÇÃO!";
                }else{
                    var retorno_tela = data.message;
                }
                jQuery('#result_cartao_atrasadas_api').append(retorno_tela);
                //location.reload();
                lock_ajax = false;
                
                /* ##### request OK, inicia o DO TRANSACTION: ##### */
                /*dados para confirmar a transação*/
                var dados_do = {
                    venda_id: venda_id,
                    data_prox_pgt: data_do,
                    transacao_valor: valor,
                    plano: plano,
                    cpf: cpf,
                    card_num: jQuery( "input[name='atrasadas_cartao_num']" ).val(),
                    card_adm: jQuery( "#atrasadas_cartao_adm" ).val(),
                    token: "EsearR31234fpssa0vfc9o",
                    username: username,
                    user_id: user_id,
                    dia_debito: dia_debito,
                    card_validade_mes: jQuery( "input[name='atrasadas_cartao_validade_mes']" ).val(),
                    card_validade_ano: jQuery( "input[name='atrasadas_cartao_validade_ano']" ).val(),
                    transaction_nit: transaction_nit,
                    transaction_id: transaction_id
                }
                
                console.log("# dados_do #");
                console.log(dados_do);
                jQuery.post( url_do_transaction, dados_do, function( data ) {
                    salvaLog("Retorno do DO transaction:");
                    salvaLog(JSON.stringify(data));
                    console.log("RETORNO AJAX CURL DO:");
                    //console.log(data);
                    data = JSON.parse(data);
                    console.log("# DATA do DO COM PARSE #");
                    console.log(data);
                                
                    if(data.code == 0) {
                        salvaLog("Transação aprovada - NIT: " + data.payment.nit + " - Status: " + data.payment.status);
                        /*transação registrada guarda o código*/
                        transaction_nit = data.payment.nit;
                        console.log("NIT: ");
                        console.log(transaction_nit);
                        console.log("STATUS: ");
                        transacao_status = data.payment.status;
                        console.log(transacao_status);

                        /*guarda o authorizer id para utilizar no terceiro passo que efetua o pagamento*/
                        authorizer_id = data.authorizer_id;
                        jQuery('#result_cartao_atrasadas_api').append(data.payment.authorizer_message);
                    } else {
                        salvaLog("Transação não aprovada - NIT: " + data.payment.nit + " - Status: " + data.payment.status);
                        transaction_nit = data.payment.nit;
                        console.log("NIT: ");
                        console.log(transaction_nit);
                        console.log("STATUS: ");
                        transacao_status = data.payment.status;
                        console.log(transacao_status);
                        jQuery('#consulta_cartao_atrasadas_api').empty();
                        jQuery('#consulta_cartao_atrasadas_api').text('Cobrar Parcelas');

                        /*mostra a mensagem do servidor*/
                        jQuery('#result_cartao_atrasadas_api').show();
                        jQuery('#result_cartao_atrasadas_api').empty();
                        jQuery('#result_cartao_atrasadas_api').append(data.message);

                        /*destrava ajax para novas requisições caso ocorra um erro*/
                        lock_ajax = false;
                    }
                    
                    var markedCheckbox = document.getElementsByName('cobrar');
                    var i = 1;
                    for (var checkbox of markedCheckbox) {
                        if (checkbox.checked){
                            if (i == 1){
                                transacao_ids = checkbox.id;
                            }else{
                                transacao_ids = transacao_ids+','+checkbox.id;
                            }
                        }
                        i++;
                    }
                    document.body.append(transacao_ids);
                    console.log("transacao_ids");
                    console.log(transacao_ids);
                    
                    var dados_atualiza = {
                        transacao_status: data.payment.status,
                        transaction_nit: transaction_nit,
                        transaction_id: transaction_id,
                        transacao_ids: transacao_ids
                    }
                    console.log("DADOS ATUALIZA");
                    console.log(dados_atualiza);
                    
                    jQuery.post( "sistema/vendas/blocos_seguros/atualiza_transacao.php", dados_atualiza, function( data ) {
                        console.log("# RETORNO ATUALIZA TRANSACAO #");
                        console.log(data);
                        if(data.indexOf("Sucesso") !== -1) {
                            console.log("# TRANSACAO ATUALIZADA OK #");
                        } else {
                            console.log("# ERRO AO ATUALIZAR TRANSACAO #");
                        }
                    });
                    carregaControleParcelas(venda_id);
                });
                
            } else {
                
                jQuery('#consulta_cartao_atrasadas_api').empty();
                jQuery('#consulta_cartao_atrasadas_api').text('Verificar disponibilidade do Cartão');

                /*mostra a mensagem do servidor*/
                jQuery('#result_cartao_atrasadas_api').show();
                jQuery('#result_cartao_atrasadas_api').empty();
                jQuery('#result_cartao_atrasadas_api').append(data.message);

                /*destrava ajax para novas requisições caso ocorra um erro*/
                lock_ajax = false;
            }
        });
    }
}

function valida_campos_atrasadas()
{
	var atrasadas_cartao_adm = jQuery( "input[name='atrasadas_cartao_adm']" ).val();
	var atrasadas_cartao_band = jQuery( "input[name='atrasadas_cartao_band']" ).val();
	var atrasadas_cartao_cvv =	jQuery( "input[name='cartao_cvv']" ).val();
	var atrasadas_cartao_num =	jQuery( "input[name='atrasadas_cartao_num']" ).val();
	var atrasadas_cartao_validade_mes = jQuery( "input[name='atrasadas_cartao_validade_mes']" ).val();
	var atrasadas_cartao_validade_ano = jQuery( "input[name='atrasadas_cartao_validade_ano']" ).val();
	var data_desconto = jQuery( "select[name='atrasadas_dia_desconto']" );

	var valido = true;

	if (atrasadas_cartao_adm == '' || atrasadas_cartao_adm == ' ') {
		alert('Preencha o campo administrador do cartão');
		valido = false;
	}
	if (atrasadas_cartao_band == '' || atrasadas_cartao_band == ' ') {
		alert('Preencha o campo bandeira');
		valido = false;
	}
	if (atrasadas_cartao_cvv == '' || atrasadas_cartao_cvv == ' ' || atrasadas_cartao_cvv.length < 3) {
		alert('Preencha o campo CVV');
		valido = false;
	}
	if (atrasadas_cartao_num == '' || atrasadas_cartao_num == ' ' || atrasadas_cartao_num.length < 15) {
		alert('Preencha o campo Nº do Cartão');
		valido = false;
	}
	if (atrasadas_cartao_validade_mes == '' || atrasadas_cartao_validade_mes == ' ') {
		alert('Preencha o campo Validade do cartão');
		valido = false;
	}
	if (atrasadas_cartao_validade_ano == '' || atrasadas_cartao_validade_ano == ' ' || atrasadas_cartao_validade_ano.length < 4) {
		alert('Preencha o campo Validade do cartão');
		valido = false;
	}
/*	if (!data_desconto) {
		alert('Preencha a data de desconto');
		valido = false;
	}*/
	return valido;
}

function valida_campos_cartao()
{
	var vendas_cartao_adm = jQuery( "input[name='vendas_cartao_adm']" ).val();
	var vendas_cartao_band = jQuery( "input[name='vendas_cartao_band']" ).val();
	var vendas_cartao_cvv =	jQuery( "input[name='cartao_cvv']" ).val();
	var vendas_cartao_num =	jQuery( "input[name='vendas_cartao_num']" ).val();
	var vendas_cartao_validade_mes = jQuery( "input[name='vendas_cartao_validade_mes']" ).val();
	var vendas_cartao_validade_ano = jQuery( "input[name='vendas_cartao_validade_ano']" ).val();
	var data_desconto = jQuery( "select[name='vendas_dia_desconto']" );

	var valido = true;

	if (vendas_cartao_adm == '' || vendas_cartao_adm == ' ') {
		alert('Preencha o campo administrador do cartão');
		valido = false;
	}
	if (vendas_cartao_band == '' || vendas_cartao_band == ' ') {
		alert('Preencha o campo bandeira');
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

