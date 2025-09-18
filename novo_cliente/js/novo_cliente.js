const form_cliente = jQuery("#form_cliente")[0];
const form_seguros = jQuery("#form_seguros")[0];
const form_consig = jQuery("#form_consig")[0];

// let cliente_orgao = "";

const hoje = new Date(Date.now()).toISOString().split('T')[0];
jQuery(".dia_venda").val(hoje);

const loading = jQuery("<img>", {
   src: "/sistema/sistema/imagens/loading.gif",
   css: {
      // position: 'absolute',
      // top: '50%',
      // left: '50%',
      // transform: 'translate(-50%, -50%)',
      width: '31px'
   }
});

jQuery(document).ready(function(){
   formValidation.loadIndividualValidation(form_cliente);
   formValidation.loadIndividualValidation(form_seguros);
   formValidation.loadIndividualValidation(form_consig);

   jQuery(form_cliente).on("submit", function(e){
      e.preventDefault();

      // Resetar validação visual antes de validar
      jQuery(form_cliente).find('input, select, textarea').removeClass('error').css('border', '');
      
      // Verificar campos obrigatórios manualmente
      const camposObrigatorios = jQuery(form_cliente).find('[required]');
      let todosPreenchidos = true;
      let camposVazios = [];

      camposObrigatorios.each(function() {
         if (!jQuery(this).val().trim()) {
            todosPreenchidos = false;
            camposVazios.push(jQuery(this).attr('name') || jQuery(this).attr('id'));
            jQuery(this).css('border', '1px solid red');
         }
      });

      // Debug da validação
      console.log('🔍 DEBUG VALIDAÇÃO:');
      console.log('- Campos obrigatórios encontrados:', camposObrigatorios.length);
      console.log('- Todos preenchidos:', todosPreenchidos);
      console.log('- Campos vazios:', camposVazios);
      console.log('- Validação formValidation:', formValidation.submitValidation(form_cliente));

      // Usar apenas nossa validação manual se todos os campos obrigatórios estão preenchidos
      if(todosPreenchidos){
         const data = data_to_JSON(form_cliente);
         const submit = jQuery(form_cliente).find("button[type='submit']")[0];

         if(jQuery(submit).html() == "Salvar"){
            insereNovoCliente(data);
            return;
         }
         
         atualizaCliente(data);
      }
      else{
         jQuery("#retorno_save_user").html("Os campos em vermelho devem ser preenchidos").css("color", "tomato");

         setTimeout(() => {
            jQuery("#retorno_save_user").html("").css("color", "");
         }, 1500);
      }
   });

   jQuery(form_seguros).on("submit", function(e){
      e.preventDefault();

      jQuery("#retorno_venda_seguro").html(loading).css("color", "");

      function verificarCamposObrigatorios(formId) {
         const form = document.getElementById(formId);
         const camposObrigatorios = form.querySelectorAll('[required]');
         let todosPreenchidos = true;
     
         camposObrigatorios.forEach((campo) => {
             if (!campo.disabled && !campo.value.trim()) {
                 console.log(`Campo obrigatório não preenchido: ${campo.name || campo.id}`);
                 campo.style.border = "1px solid tomato";
                 todosPreenchidos = false;
             }
         });
     
         return todosPreenchidos; // Retorna true se todos os campos obrigatórios estão preenchidos, false caso contrário
     }

     
     

     console.log("Função auxiliar", verificarCamposObrigatorios("form_seguros"));

      if(formValidation.submitValidation(form_seguros) || verificarCamposObrigatorios("form_seguros")){
         // const data = data_to_JSON(form_seguros);
         const apolice_dep_ben = jQuery(form_seguros).find("#vendas_apolice option:selected").attr("apolice_dep_ben");
         const num_de_dependentes = jQuery("#campo_dependentes .dep_form").length;

         // if(apolice_dep_ben == 2 && num_de_dependentes < 1){
         //    jQuery("#retorno_venda_seguro").html("Plano familiar selecionado! Cadastre ao menos um dependente").css("color", "tomato");

         //    setTimeout(() => {
         //       jQuery("#retorno_venda_seguro").html("").css("color", "");
         //    }, 2000);

         //    return;
         // }
         salvarVendaSeguro();
      }
      else{
         console.log("valid false");
         jQuery("#retorno_venda_seguro").html(`Os campos em vermelho devem ser preenchidos`).css("color", "tomato");

         setTimeout(() => {
            jQuery("#retorno_venda_seguro").html("").css("color", "");
         }, 2500);
      }
   });

   jQuery(form_consig).on("submit", function(e){
      e.preventDefault();

      jQuery("#retorno_venda_consig").html(loading).css("color", "");

      if(formValidation.submitValidation(form_consig)){
         const data = data_to_JSON(form_consig);
         salvarVendaConsig(data);
      }
      else{
         console.log("valid false");
         jQuery("#retorno_venda_consig").html("Os campos em vermelho devem ser preenchidos").css("color", "tomato");

         setTimeout(() => {
            jQuery("#retorno_venda_consig").html("").css("color", "");
         }, 1500);
      }
   });


   let cpfTimer;
   jQuery("#cliente_cpf").on("keyup input", function(){
      clearTimeout(cpfTimer);
      jQuery("#saveNewClient").attr("disabled", true); //desabilita temporariamente o salvamento quando um novo cpf for digitado

      jQuery(this).val( jQuery(this).val().replace(/[^\d.-]+/gm, '') ); //remove qualquer digito incompatível com campo de cpf

      const format = BrazilianValues.formatToCPF( jQuery(this).val() );
      jQuery(this).val(format);

      //busca cpf do cliente 500ms depois do user parar de digitar
      cpfTimer = setTimeout(() => {
         getUserByCpf(jQuery(this).val());

         jQuery("#clients_cpf").val(jQuery(this).val());
      }, 500);
   });

   //formata campo de cep
   jQuery("#cliente_cep").on("keyup input", function(){
      const formated = BrazilianValues.formatToCEP(jQuery(this).val());
      jQuery(this).val(formated);
   });

   //põe fundo preto no botão de venda selecionado
   jQuery("#consig_ou_seg button").on("click", function(){
      jQuery("#consig_ou_seg button").removeClass("button_active");
      jQuery(this).addClass("button_active");
   });

   jQuery("#vendas_banco").on("change", function(){
      getApolices('1');
      enableFormaPgto();
      getFormasPagamento(0,0);
      toggleBeneficiarioDependente();
      setValorVenda();
      getOrgaos();
	  enableValorApolice();
   });

   jQuery("#vendas_apolice").on("change", function(){
      const apolice_dep_ben = jQuery("#vendas_apolice option:selected").attr("apolice_dep_ben");
      toggleBeneficiarioDependente(apolice_dep_ben);
      getOrgaos();
      setValorVenda();
	  enableValorApolice();
   });

   jQuery("#vendas_pgto").on("change", function(){
      getFormasPagamento(0,0);
   });
   
   jQuery("#apolice_valor").on("keyup", function(){
      setValorVenda();
   })
});

function insereNovoCliente(data) {
   data["cliente_cpf"] = jQuery("#cliente_cpf").val().replaceAll(".", "").replaceAll("-", "");

   jQuery.ajax({
      url: '/sistema/sistema/cliente/novo_cliente/insere_novo_cliente_dev.php',
      method: 'POST',
      data: data,
      success: function (response) {
         jQuery("#retorno_save_user").html("Cliente criado com sucesso!").css("color", "green");

         setTimeout(() => {
            jQuery("#retorno_save_user").html("").css("color", "");
            toggleDisabled(0);
         }, 1500);
      }
   });

   jQuery("#message_save_cliente").css("display", "none")
}

function atualizaCliente(data) {
   data["cliente_cpf"] = jQuery("#cliente_cpf").val().replaceAll(".", "").replaceAll("-", "");

   jQuery.ajax({
      url: '/sistema/sistema/cliente/novo_cliente/update_cliente_dev.php',
      method: 'POST',
      data: data,
      success: function (response) {
         jQuery("#retorno_save_user").html(response).css("color", "green");

         setTimeout(() => {
            jQuery("#retorno_save_user").html("").css("color", "");
            toggleDisabled(0);
         }, 1500);
      }
   });
}

function toggleDisabled(disable){
   const inputs = jQuery("#form_seguros, #form_consig").find("input:not(.requires-input), select:not(.requires-input), textarea:not(.requires-input), button:not(.requires-input)");

   //force habilita os campos de venda
   if(disable == 0){
      jQuery(inputs).attr("disabled", false); 
      jQuery(".message_save_cliente").css("display", "none")
   }

   //desabilita em toggle ou force os campos de venda
   else if(jQuery(inputs).attr("disabled") != "disabled" || disable == 1){
      jQuery(inputs).attr("disabled", true)
      jQuery(".message_save_cliente").css("display", "inline-block");
   }

   //togle habilita
   else{
      jQuery(inputs).attr("disabled", false); 
      jQuery(".message_save_cliente").css("display", "none")
   }
}

jQuery(document).ready(function () {
   handleInputChanges();
});

//remove chars não numéricos
function somenteNumeros(element){
   jQuery(element).val( jQuery(element).val().replace(/[\D]/gm, '') );
}

function cpfCnpjMask(element) {
   const value = BrazilianValues.formatToCPFOrCNPJ(element.value);
   element.value = value

   if (BrazilianValues.isCNPJ(value)) jQuery("#cliente_cpf").attr("style", "border: 1px solid green; color: green") 
   else if (BrazilianValues.isCPF(value)) jQuery("#cliente_cpf").attr("style", "border: 1px solid green; color: green")
   else jQuery("#cliente_cpf").attr("style", "border: 1px solid red; color: red")
}

function cepMask(element){
   const value = BrazilianValues.formatToCEP(element.value);
   element.value = value;
}

function maskPhone(event) {
    if (jQuery(event).val() == "") {
        jQuery(event).attr("style", "border: none;");
        b = false;
    } else {
        jQuery(event).val(BrazilianValues.formatToPhone(jQuery(event).val()));
        if (BrazilianValues.isPhone(jQuery(event).val()) === true) {
            jQuery(event).attr("style", "border: 2px solid #4d398d; color: green; height: 30px; min-height: 30px;");
            b = true;
        } else {
            jQuery(event).attr("style", "border: 2px solid red; color: red; height: 30px; min-height: 30px;")
            b = false;
        }
    }
}

function getUserByCpf(cpf) {
   cpf = cpf.replaceAll(".", "").replaceAll("-", "")

   jQuery.ajax({
      url: '/sistema/sistema/cliente/novo_cliente/getUserByCpf.php',
      method: 'POST',
      data: { cpf: cpf },
      success: function (data) {
         const jsonData = JSON.parse(data);
         console.log(jsonData);

         const propriedades = [
            "cliente_rg",
            "cliente_nome",
            "cliente_pai",
            "cliente_mae",
            "cliente_nascimento",
            "cliente_sexo",
            "cliente_endereco",
            "cliente_bairro",
            "cliente_cidade",
            "cliente_uf",
            "cliente_cep",
            "cliente_celular",
            "cliente_email",
            "cliente_banco",
            "cliente_conta",
            "cliente_agencia",
            "cliente_categoria",
            "cliente_empregador",
            "cliente_situacao",
            "cliente_telefone",
            "cliente_beneficio",
            "cliente_especie",
            "cliente_orgao"
         ];
            

         for (const propriedade of propriedades) {
            jQuery(`#${propriedade}`).val(jsonData && jsonData[propriedade] ? jsonData[propriedade] : "");
         }

         
         if(jsonData){ //se houverem dados, mostrar botão atualizar e habilitar campos de venda
            jQuery("#saveNewClient").html("Atualizar");

            // Resetar validação visual quando cliente é encontrado
            jQuery(form_cliente).find('input, select, textarea').removeClass('error').css('border', '');

            // cliente_orgao = jsonData.cliente_orgao; //salva cliente_orgao pra usar no órgão da venda depois

            // if(!checkFields(jsonData, propriedades)) toggleDisabled(1);
            // else toggleDisabled(0);
            toggleDisabled(0);
         }
         else{ //se não, mostrar botão salvar e manter campos de venda desabilitados
            jQuery("#saveNewClient").html("Salvar");
            toggleDisabled(1);
         }

         jQuery("#saveNewClient").attr("disabled", false);
         // Remover chamada de validação aqui para evitar conflitos
         // formValidation.submitValidation(form_cliente);
      }
   });
}

function checkFields(obj, props) {
   for (const prop of props) {
      if (!obj.hasOwnProperty(prop)) {
         console.log("property not found")
         return false;
      }
   }

   for (const prop of props) {
      if (obj[prop] === "" || obj[prop] === null || obj[prop] === undefined) {
         console.log("null value")
         console.log(prop);
         return false;
      }
   }

   console.log("all ok")
   return true;
}

var atualizar_cliente = false;

function visibleButtonSaveClient() { atualizar_cliente = true; }

function getApolices(retencao) {
   // vendas_banco == SEGURADORA
   const vendas_banco = jQuery("#vendas_banco").val();
   const equipe_apolices = jQuery("#equipe_apolices").val();

   jQuery("#vendas_apolice").html(`<option value=''></option>`);
   jQuery("#vendas_apolice").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_apolice").attr("disabled", true);
   
   if(vendas_banco){
      jQuery("#vendas_apolice").parent().find("*").removeClass("button-disabled-venda");
      jQuery("#vendas_apolice").attr("disabled", false);
   }
   else{
      return;
   }

   jQuery.ajax({
      type: "GET",
      url: "/sistema/sistema/cliente/novo_cliente/getApolices.php",
      data: {
         vendas_banco: vendas_banco,
         equipe_apolices: equipe_apolices,
         retencao: retencao
      },
      success: function(response){
         // console.log(response);
         let options = `<option value=''></option>`;

         if(response){
            options += response.map(element => `<option apolice_valor='${element.apolice_valor}' apolice_dep_ben='${element.apolice_dep_ben}' value='${element.apolice_id}'>${element.apolice_nome}</option>`).join('');
         }

         jQuery("#vendas_apolice").html(options);
         // jQuery("#vendas_pgto").attr("disabled", true);
         // jQuery("#getApolices").html("");
      }
   })
}

function enableFormaPgto(){
   const seguradora = jQuery("#vendas_banco").val();
   const cliente_cpf = jQuery("#cliente_cpf").val().replaceAll(".", "").replaceAll("-", "");

   jQuery("#vendas_pgto").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_pgto").attr("disabled", true);

   if(seguradora && cliente_cpf){
      jQuery("#vendas_pgto").parent().find("*").removeClass("button-disabled-venda");
      jQuery("#vendas_pgto").attr("disabled", false);
   }
}

function getFormasPagamento() {
   const seguradora = jQuery("#vendas_banco").val();
   const vendas_pgto = jQuery("#vendas_pgto").val();
   const cliente_cpf = jQuery("#cliente_cpf").val().replaceAll(".", "").replaceAll("-", "");
   const apolice_pgto = 1;

   jQuery("#blocoCadastroVendasSeguradora").css({display: "none"});
   jQuery("#blocoCadastroVendasSeguradora").find("*").addClass("button-disabled-venda")
   jQuery("#blocoCadastroVendasSeguradora").attr("disabled", true);
   
   if(seguradora && vendas_pgto && cliente_cpf){
      jQuery("#blocoCadastroVendasSeguradora").css({display: "block"});
      jQuery("#blocoCadastroVendasSeguradora").find("*").removeClass("button-disabled-venda");
      jQuery("#blocoCadastroVendasSeguradora").attr("disabled", false);
   }

   jQuery.ajax({
      url: "/sistema/sistema/cliente/novo_cliente/consulta_forma_pagamento_ajax.php",
      method: "GET",
      data: {
         vendas_banco: seguradora,
         vendas_pgto: vendas_pgto,
         apolice_pgto: apolice_pgto,
         cliente_cpf: cliente_cpf
      },
      success: function (data) {

         jQuery("#blocoCadastroVendasSeguradora").html(data);

         jQuery(".info-inputs").on("input", function () {
            if (jQuery(this).val() !== "") {
               jQuery(this).addClass("filled");
               jQuery(this).prev(".input-label").addClass("filled");
            } else {
               jQuery(this).removeClass("filled");
               jQuery(this).prev(".input-label").removeClass("filled");
            }
         });
      }
   });
}

function addCampoBeneficiario() {
   if (jQuery(".benefic_form").length < 1) {
      jQuery("#remove_btn").html('<input type="button" value="Remover Beneficiário" onclick="removeCampoBeneficiario();" />');
   }

   const beneficiario = jQuery(`
   <span class='benefic_form' style='display: flex; align-items: center; flex-wrap: wrap; gap: 5px;'>
      ${
         (() => {
            if (jQuery(".benefic_form").length != 0) 
               return '<hr style="width: 100%; margin: 5px 0 14px 0;">';

            return '';
         })()
      }
      <div class='input-special'>
         <input type='text' class='ben_nome info-inputs cad-venda not-required' id='ben_nome' name='ben_nome[]' required>
         <label class='cad-venda' for='ben_nome'>Nome do Beneficiário</label>
      </div>
      <div class='input-special no-style-top'>
         <input type='date' class='ben_nasc info-inputs cad-venda not-required' id='ben_nasc' name='ben_nasc[]' required>
         <label class='cad-venda' for='ben_nasc'>Data de Nascimento</label>
      </div>
      <div class='input-special'>
         <input type='text' class='ben_parent info-inputs cad-venda not-required' id='ben_parent' name='ben_parent[]' required>
         <label class='cad-venda' for='ben_parent'>Parentesco</label>
      </div>
      <div class='input-special'>
         <input type='text' class='ben_perc info-inputs cad-venda not-required' id='ben_perc' name='ben_perc[]' maxlength='10' size='10' onkeypress="return(MascaraMoeda(this,'.',',',event))" required>
         <label class='cad-venda' for='ben_perc'>Percentual</label>
      </div>
   </span>`);

   jQuery("#campo_beneficiarios").append(beneficiario);
}
function handleInputChanges() {
    jQuery('.info-inputs').on('input', function () {
        if (jQuery(this).val() !== '') {
            jQuery(this).addClass('filled');
            jQuery(this).prev('.input-label').addClass('filled');
        } else {
            jQuery(this).removeClass('filled');
            jQuery(this).prev('.input-label').removeClass('filled');
        }
    });
}

function removeCampoBeneficiario() {
    var len = jQuery(".benefic_form").length;
    if (len > 0) {
        jQuery(".benefic_form:last").remove();
        if (len == 1) {
            jQuery("#remove_btn").empty();
        }
    }
    if (jQuery("#add_btn").html() == "" && len <= 10) {
        jQuery("#add_btn").html('<input type="button" value="Adicionar Beneficiário" onclick="addCampoBeneficiario();" />');
    }
}

function salvarVendaConsig(data){
   data["clients_cpf"] = jQuery("#cliente_cpf").val().replaceAll(".", "").replaceAll("-", "");
   data["vendas_user"] = jQuery("#username").val();
   data["user_id_local"] = jQuery("#user_id_local").val();
   data["vendas_consultor"] = jQuery("#user_id").val();
   
   jQuery.ajax({
      type: "GET",
      url: "/sistema/sistema/vendas/insere_dev.php",
      data: data,
      success: function(response){

         //busca pela string que confirma cadasto de venda
         if(response.search("Venda de CONSIGNADO Cadastrada com Sucesso") != -1){
            jQuery("#retorno_venda_consig").html("Venda de consignado cadastrada com sucesso!").css("color", "green");
         }
         else{
            jQuery("#retorno_venda_consig").html("Erro ao cadastrar venda<br>Olhar console para mais informações");
            console.log("Resposta do PHP: ", response);
         }

         setTimeout(() => {
            jQuery("#retorno_venda_consig").html("");
            form_consig.reset();
            jQuery(".dia_venda").val(hoje);
            jQuery(form_consig).find(".requires-input").parent().find("input, select, textarea, label, button").addClass("button-disabled-venda").attr("disabled", true)
            jQuery("#span_vendas_coeficiente").html("");
         }, 1500);
      }
   });
}

function salvarVendaSeguro() {
   const vendas_apolice = jQuery("#vendas_apolice").val()
   const consulta_cartao = jQuery("#consulta_cartao_credito_api")[0];

   if(consulta_cartao !== undefined && jQuery(consulta_cartao).attr("disabled") != "disabled"){
      jQuery("#retorno_venda_seguro").html("Verificar disponibilidade do cartão antes de prosseguir!").css("color", "tomato");

      setTimeout(() => {
         jQuery("#retorno_venda_seguro").html("");
      }, 2000);
      return;
   }
      

   const data = {
      user_id: getValor("#user_id"),
      username: getValor("#username"),
      clients_cpf: jQuery("#cliente_cpf").val().replaceAll(".", "").replaceAll("-", ""),
      vendas_telefone: getValor("#cliente_celular"),
      vendas_telefone2: getValor("#cliente_telefone"),
      vendas_banco: getValor("#vendas_banco"),
      forma_envio_kitcert: getValor("#forma_envio_kitcert"),
      possui_instagram: getValor("#possui_instagram"),
      vendas_dia_desconto: getValor("#vendas_dia_desconto"),
      vendas_pgto: getValor("#vendas_pgto"),
      salario_bruto: getValor("#salario_bruto"),
      vendas_cartao_adm: getValor("#vendas_cartao_adm"),
      vendas_cartao_num: getValor("#vendas_cartao_num"),
      vendas_cartao_cvv: getValor("#vendas_cartao_cvv"),
      vendas_cartao_validade_mes: getValor("#vendas_cartao_validade_mes"),
      vendas_cartao_validade_ano: getValor("#vendas_cartao_validade_ano"),
      vendas_vencimento_fatura: getValor("#vendas_vencimento_fatura"),
      vendas_debito_banco: getValor("#vendas_debito_banco"),
      vendas_debito_ag: getValor("#vendas_debito_ag"),
      vendas_debito_ag_dig: getValor("#vendas_debito_ag_dig"),
      vendas_debito_cc: getValor("#vendas_debito_cc"),
      vendas_debito_cc_dig: getValor("#vendas_debito_cc_dig"),
      vendas_debito_banco_2: getValor("#vendas_debito_banco_2"),
      vendas_debito_ag_2: getValor("#vendas_debito_ag_2"),
      vendas_debito_ag_dig_2: getValor("#vendas_debito_ag_dig_2"),
      vendas_debito_cc_2: getValor("#vendas_debito_cc_2"),
      vendas_debito_cc_dig_2: getValor("#vendas_debito_cc_dig_2"),
      vendas_debito_banco_3: getValor("#vendas_debito_banco_3"),
      vendas_debito_ag_3: getValor("#vendas_debito_ag_3"),
      vendas_debito_ag_dig_3: getValor("#vendas_debito_ag_dig_3"),
      vendas_debito_cc_3: getValor("#vendas_debito_cc_3"),
      vendas_debito_cc_dig_3: getValor("#vendas_debito_cc_dig_3"),
      check_card_code: getValor("#check_card_code"),
      vendas_obs: getValor("#vendas_obs"),
      tempo_inicio_auditoria: getValor("#tempo_inicio_auditoria"),
      vendas_consultor: getValor("#vendas_consultor"),
      vendas_status: getValor("#vendas_status"),
      vendas_orgao: getValor("#vendas_orgao"),
      vendas_dia_venda: getValor("#vendas_dia_venda"),
      // apolice_valor: apolice_valor,
      vendas_apolice: vendas_apolice,
      vendas_valor: jQuery("#vendas_valor_seguro").val()
   };

   // variavel com parametros get pata salvar a venda 
   parametros = "?" + Object.entries(data).map(([key, value]) => `${key}=${value}`).join("&");

   jQuery("#campo_beneficiarios .benefic_form").each(function () {
      ben_nome = jQuery(".ben_nome", this).val();
      ben_nasc = jQuery(".ben_nasc", this).val();
      ben_parent = jQuery(".ben_parent", this).val();
      ben_perc = jQuery(".ben_perc", this).val();
      parametros += "&ben_nome[]=" + ben_nome;
      parametros += "&ben_nasc[]=" + ben_nasc;
      parametros += "&ben_parent[]=" + ben_parent;
      parametros += "&ben_perc[]=" + ben_perc;
   });

   jQuery("#campo_dependentes .dep_form").each(function () {
      dependente_nome = jQuery(".dependente_nome", this).val();
      dependente_cpf = jQuery(".dependente_cpf", this).val();
      dependente_sexo = jQuery(".dependente_sexo", this).val();
      dependente_nascimento = jQuery(".dependente_nascimento", this).val();
      dependente_parentesco = jQuery(".dependente_parentesco", this).val();
      dependente_celular = jQuery(".dependente_celular", this).val();
      dependente_email = jQuery(".dependente_email", this).val();
      parametros += "&dependente_nome[]=" + dependente_nome;
      parametros += "&dependente_cpf[]=" + dependente_cpf;
      parametros += "&dependente_sexo[]=" + dependente_sexo;
      parametros += "&dependente_nascimento[]=" + dependente_nascimento;
      parametros += "&dependente_parentesco[]=" + dependente_parentesco;
      parametros += "&dependente_celular[]=" + dependente_celular;
      parametros += "&dependente_email[]=" + dependente_email;
   });
      var order_id_value = jQuery("#order_id").val();
      parametros += "&order_id=" + order_id_value;
      
      // Debug: verificar order_id antes de enviar
      console.log('🎯 DEBUG CRIAÇÃO VENDA: order_id que será enviado:', order_id_value);
      console.log('🎯 DEBUG CRIAÇÃO VENDA: parametros completos:', parametros);

   //  jQuery("#loaoding_nova_venda").css({ display: "block" })

    jQuery.ajax({
        url: "/sistema/sistema/cliente/novo_cliente/insere_venda_novo_cliente.php" + parametros,
        method: "GET",
        dataType: "text",
        timeout: 30000, // 30 segundos de timeout
        success: function (responseText) {
            console.log('🎯 DEBUG: Resposta recebida do servidor:', responseText);

            try {
                var jsonData = JSON.parse(responseText);
                console.log('🎯 DEBUG: JSON parseado:', jsonData);
                
                var cpf = jsonData.cpf;
                var vendas_id = jsonData.vendas_id;

                // Atualizar o campo hidden vendas_id no HTML
                jQuery('#vendas_id').val(vendas_id);
                console.log('🎯 DEBUG: Campo vendas_id atualizado para:', vendas_id);
            } catch (e) {
                console.error('❌ ERRO: Falha ao processar JSON:', e);
                console.error('❌ ERRO: Resposta bruta:', responseText);
                alert('Erro ao processar resposta do servidor. Verifique o console para mais detalhes.');
                return;
            }

            jQuery.ajax({
                url: "https://zyonsistemas.com.br/sistema/sistema/cliente/novo_cliente/response_cadastro_venda.php",
                type: "GET",
                data: {
                    vendas_id: vendas_id,
                    cliente_cpf: cpf
                },
                success: function (response) {
                    jQuery("#retorno_venda_seguro").html("Venda de seguro cadastrada com sucesso!").css("color", "green");
                  //   jQuery("#conteudo_cadastro").html("")

                    setTimeout(() => {
                     jQuery("#retorno_venda_seguro").html("");
                     form_seguros.reset();
                     jQuery(form_seguros).find("#vendas_orgao").html("");
                     jQuery(".dia_venda").val(hoje);
                     jQuery(form_seguros).find(".requires-input").parent().find("input, select, textarea, label, button").addClass("button-disabled-venda").attr("disabled", true)
                     jQuery("#blocoCadastroVendasSeguradora").html("");
                     jQuery("#beneficiario_ou_dependente").html("");
                     jQuery("#valor_venda_seguro_label").text("R$ 0,00");
                     jQuery("#vendas_valor_seguro").val("");
                  }, 1500);
                },
                error: function (xhr, status, error) {
                  console.error("Erro na requisição AJAX: " + error);
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('❌ ERRO AJAX: Status:', status, 'Error:', error);
            console.error('❌ ERRO AJAX: Response:', xhr.responseText);
            console.error('❌ ERRO AJAX: Status Code:', xhr.status);
            
            var errorMessage = "Erro ao salvar venda";
            
            try {
                if (xhr.responseText) {
                    const response = JSON.parse(xhr.responseText);
                    errorMessage = response.msg || errorMessage;
                }
            } catch (e) {
                console.error('❌ ERRO: Não foi possível processar resposta de erro:', e);
                if (status === 'timeout') {
                    errorMessage = "Tempo limite excedido. Tente novamente.";
                } else if (status === 'error') {
                    errorMessage = "Erro de conexão. Verifique sua internet.";
                }
            }

            jQuery("#retorno_venda_seguro").html(errorMessage).css("color", "tomato");

            setTimeout(() => {
               jQuery("#retorno_venda_seguro").html("").css("color", "");
            }, 5000);
        }
    });
}

// function verificarCamposObrigatorios() {
//     var errorMessage = "Preencha os campos em destaque!";
//     var fieldsToValidate = [
//         "cliente_telefone",
//         "cliente_celular",
//         "vendas_dia_venda",
//         "forma_envio_kitcert",
//         "cliente_orgao",
//         "vendas_banco",
//         "vendas_apolice",
//         "vendas_dia_desconto",
//         "vendas_pgto"
//     ];

//     var hasEmptyFields = false;

//     fieldsToValidate.forEach(function (fieldName) {
//         var fieldValue = jQuery("#" + fieldName).val();

//         if (fieldValue == "") {
//             jQuery("#" + fieldName).attr("style", "border: 1px solid red !important");
//             jQuery("#retorno_save_venda").html(errorMessage).css("color", "red");
//             hasEmptyFields = true;
//         } else {
//             jQuery("#" + fieldName).removeAttr("style");
//         }
//         if (hasEmptyFields) {
//             console.log(fieldName)
//         }
//     });

//     return hasEmptyFields;
// }

function getValor(elementId) {
    const element = jQuery(elementId);
    return element.length ? element.val() : "";
}

// function apolicesAjax(vendas_banco, retencao) {
//     equipe_apolices = jQuery("#equipe_apolices").val();
//     if (vendas_banco) {
//         jQuery("#response_ajax_apolices").css({
//             "display": "inherit"
//         });
//         jQuery("#response-ajax-apolice-pgto").css({
//             "display": "none"
//         });
//     } else {
//         jQuery("#response_ajax_apolices").css({
//             "display": "none"
//         });
//         jQuery("#response-ajax-apolice-pgto").css({
//             "display": "none"
//         });

//         jQuery("#vendas_pgto").val('');
//         jQuery("#response-ajax-formapagamento").html('');
//     }

//     jQuery.ajax({
//         url: "/sistema/sistema/discador_seguro/ajax/apolices_ajax.php",
//         type: "GET",
//         data: {
//             vendas_banco: vendas_banco,
//             retencao: retencao,
//             equipe_apolices: equipe_apolices
//         },
//         success: function (responseText) {
//             jQuery("#retorno_apolices").html(responseText);
//         },
//         error: function () { }
//     });
// }

function consultaFormaPagamentoAjax(apolice_pgto, apolice_dia_desconto) {

    var seguradora = jQuery("#vendas_banco").val();
    var vendas_pgto;

    if (apolice_pgto > 0) {

        vendas_pgto = apolice_pgto;

        jQuery("#response-ajax-apolice-pgto").css("display", "none");
        jQuery("#vendas_pgto option[value=" + apolice_pgto + "]").prop('selected', true);
        jQuery("#response-ajax-formapagamento").html('');

    } else {

        if (jQuery("#vendas_pgto").length) {
            vendas_pgto = jQuery("#vendas_pgto").val();
        } else {
            vendas_pgto = "";
        }

        jQuery("#response-ajax-apolice-pgto").css("display", "unset");
    }

    var cliente_cpf;

    if (jQuery("#cliente_cpf").length) {
        cliente_cpf = jQuery("#cliente_cpf").val();
    } else {
        cliente_cpf = "";
    }

    if (vendas_pgto != "" && cliente_cpf != "") {
        jQuery("#response-ajax-formapagamento").html("<div style='text-align: center;'><img src='sistema/imagens/loading.gif'></div>");
    }

    if (apolice_dia_desconto > 0) {

        jQuery("#response-ajax-apolice-dia-desconto").css("display", "none");

        if (apolice_dia_desconto < 10) {
            zero = "0";
        } else {
            zero = "";
        }

        jQuery("#vendas_dia_desconto option[value=" + zero + apolice_dia_desconto + "]").prop('selected', true);

    } else {

        jQuery("#response-ajax-apolice-dia-desconto").css("display", "unset");
    }

    jQuery.ajax({
        url: "/sistema/sistema/discador_seguro/ajax/consulta_forma_pagamento_ajax.php",
        type: "GET",
        data: {
            vendas_banco: seguradora,
            vendas_pgto: vendas_pgto,
            apolice_pgto: 1,
            cliente_cpf: cliente_cpf
        },
        success: function (data) {
            jQuery("#response-ajax-formapagamento").html(data);
        }
    });
}

// ============================================================================= //
// CONSIGNADO

jQuery(document).ready(function(){
   jQuery("#vendas_orgao").on("change", function(){
      getBancos();
      getContratos();
      getPrazos();
      getTabelas();
      getCoeficiente();
   });

   jQuery("#vendas_banco_id").on("change", function(){
      getContratos();
      getPrazos();
      getTabelas();
      getCoeficiente();
   });

   jQuery("#vendas_tipo_contrato").on("change", function(){
      getPrazos();
      getTabelas();
      getCoeficiente();
   });

   jQuery("#vendas_percelas").on("change", function(){
      getTabelas();
      getCoeficiente();
   });

   jQuery("#vendas_tabela").on("change", function(){
      getCoeficiente();
   });

   let typingTimer; //timer que espera meio seg. 
   jQuery("#vendas_valor_parcela").on("keyup", function(){
      // console.log(jQuery(this).val().length)

      clearTimeout(typingTimer);

      mascaraMoeda(jQuery(this)[0]);

      typingTimer = setTimeout(() => {
         getCoeficiente();
         const vendas_valor = jQuery("#vendas_valor")[0];
         formValidation.responsiveValidation(vendas_valor, form_consig);
      }, 500);
   })

   jQuery("#vendas_valor, #vendas_margem, #vendas_liquido, #vendas_applus_valor").on("input", function(e){
      mascaraMoeda(jQuery(this)[0]);
   });
});

function toggleTipoVenda(element){
   jQuery(element).css("display", "block");
   if(element.id == "campos_seguro") jQuery("#campos_consig").css("display", "none");
   else jQuery("#campos_seguro").css("display", "none");
}

let cont = 1;
function addCampoDivida(){
	if(cont == 1){
		document.getElementById('remove_btn_divida').innerHTML = '<button type="button" onclick="removeCampoDivida();">Remove Dívida</button>';
      jQuery("#campo_compra_divida").css("display", "");
	}
	
	if(cont <= 10){
      const conteudo_select = document.getElementById("conteudo_select").innerHTML;

		elem = document.createElement("SPAN");
		elem.className = "removivel";
      elem.style.display = "flex";
      elem.style.justifyContent = "center";
      elem.style.alignItems = "center";

      elem.innerHTML = 
      `<span>${cont}</span>
      <span id='linha_flex${cont}'>
         <div class="container-inputs-apb-seguros">
            <div class='input-special'>
               <select style='float:none;' name='compra_banco${cont}' class="info-inputs" required>${conteudo_select}</select>
               <label class="cad-venda" for="compra_banco${cont}">Banco a Ser Comprado</label>
            </div>
            <div class='input-special'>
               <input type='text' id='compra_contrato${cont}' name='compra_contrato${cont}' class="info-inputs" onkeyup='somenteNumeros(this)' required>
               <label class="cad-venda" for="compra_contrato${cont}">Nº do Contrato</label>
            </div>
            <div class='input-special'>
               <input type='text' id='compra_valor${cont}' name='compra_valor${cont}' class="info-inputs" maxlength='10' onkeyup="mascaraMoeda(this)" required>
               <label class="cad-venda" for="compra_valor${cont}">Valor da Parcela</label>
            </div>
            <div class='input-special'>
               <input type='text' id='compra_saldo${cont}' name='compra_saldo${cont}' class="info-inputs" onkeyup="mascaraMoeda(this)" required>
               <label class="cad-venda" for="compra_saldo${cont}">Saldo Devedor</label>
            </div>
            <div class='input-special'>
               <input type='text' id='compra_prazo${cont}' name='compra_prazo${cont}' class="info-inputs" maxlength='2' onkeyup='somenteNumeros(this)' required/>
               <label class="cad-venda" for="compra_prazo${cont}">Prazo do Contrato</label>
            </div>
            <div class='input-special'>
               <input type='text' id='compra_parcelas${cont}' name='compra_parcelas${cont}' class="info-inputs" maxlength='2' onkeyup='somenteNumeros(this)' required/>
               <label class="cad-venda" for="compra_parcelas${cont}">Parcelas em Aberto</label>
            </div>
            <div class='input-special'>
               <input type='date' id='compra_venc${cont}' name='compra_venc${cont}' class="info-inputs" required/>
               <label class="cad-venda" for="compra_venc${cont}">Vencimento</label>
            </div>
         </div>
      </span>` 
		document.getElementById("campo_compra_divida").appendChild(elem);

		cont++;
	}
	if(cont == 11){
		document.getElementById("add_btn_divida").innerHTML = "";
	}

   formValidation.loadIndividualValidation(form_consig);
}

function removeCampoDivida(){
	if(document.getElementsByClassName("removivel").length>0){
      len = document.getElementsByClassName("removivel").length;
      document.getElementsByClassName("removivel")[len-1].remove();
      cont--;

      if(cont==1){
         document.getElementById('remove_btn_divida').innerHTML = ''
         jQuery("#campo_compra_divida").css("display", "none");
      };
   };

	if(document.getElementById("add_btn_divida").innerHTML == "" && cont<11){
		document.getElementById("add_btn_divida").innerHTML = '<button type="button" onclick="addCampoDivida();">Adicionar Dívida</button>'
	}
}

async function getBancos(){
   const empresa = jQuery("#empresa").val();
   const vendas_orgao = jQuery("#vendas_orgao").val();

   jQuery("#vendas_banco_id").html(`<option value=''></option>`);
   jQuery("#vendas_banco_id").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_banco_id").attr("disabled", true);

   jQuery("#vendas_tipo_contrato").html(`<option value=''></option>`);
   jQuery("#vendas_tipo_contrato").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_tipo_contrato").attr("disabled", true);

   jQuery("#vendas_percelas").html(`<option value=''></option>`);
   jQuery("#vendas_percelas").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_percelas").attr("disabled", true);

   jQuery("#vendas_tabela").html(`<option value=''></option>`);
   jQuery("#vendas_tabela").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_tabela").attr("disabled", true);

   showCompraDivida("0");

   if(vendas_orgao){
      jQuery("#vendas_banco_id").parent().find("*").removeClass("button-disabled-venda");
      jQuery("#vendas_banco_id").attr("disabled", false);
   }
   else{
      return;
   }

   await fetch(`/sistema/sistema/cliente/novo_cliente/getBancosConsig.php?vendas_orgao=${vendas_orgao}&empresa=${empresa}`)
   .then(response => response.json())
   .then(data => {
      let options = `<option value=''></option>`;
      
      if(data){
         options += data.map(element => `<option value='${element.banco_nome}'>${element.banco_nome}</option>`).join('');
      }

      jQuery("#vendas_banco_id").html(options);
   });
}

async function getContratos(){
   const empresa = jQuery("#empresa").val();
   const vendas_orgao = jQuery("#vendas_orgao").val();
   const vendas_banco = jQuery("#vendas_banco_id").val();

   jQuery("#vendas_tipo_contrato").html(`<option value=''></option>`);
   jQuery("#vendas_tipo_contrato").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_tipo_contrato").attr("disabled", true);

   jQuery("#vendas_percelas").html(`<option value=''></option>`);
   jQuery("#vendas_percelas").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_percelas").attr("disabled", true);

   jQuery("#vendas_tabela").html(`<option value=''></option>`);
   jQuery("#vendas_tabela").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_tabela").attr("disabled", true);

   showCompraDivida("0");

   if(vendas_orgao && vendas_banco){
      jQuery("#vendas_tipo_contrato").parent().find("*").removeClass("button-disabled-venda");
      jQuery("#vendas_tipo_contrato").attr("disabled", false);
   }
   else{
      return;
   }

   await fetch(`/sistema/sistema/cliente/novo_cliente/getContratoConsig.php?vendas_banco=${vendas_banco}&vendas_orgao=${vendas_orgao}&empresa=${empresa}`)
   .then(response => response.json())
   .then(data => {
      let options = `<option value=''></option>`;

      if(data){
         options += data.map(element => `<option value='${element.tipo_id}'>${element.tipo_nome}</option>`).join('');
      }
      
      jQuery("#vendas_tipo_contrato").html(options);
   });
}

async function getPrazos(){
   const empresa = jQuery("#empresa").val();
   const vendas_orgao = jQuery("#vendas_orgao").val();
   const vendas_banco = jQuery("#vendas_banco_id").val();
   const vendas_tipo_contrato = jQuery("#vendas_tipo_contrato").val();

   jQuery("#vendas_percelas").html(`<option value=''></option>`);
   jQuery("#vendas_percelas").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_percelas").attr("disabled", true);

   jQuery("#vendas_tabela").html(`<option value=''></option>`);
   jQuery("#vendas_tabela").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_tabela").attr("disabled", true);

   showCompraDivida(vendas_tipo_contrato);

   if(vendas_orgao && vendas_banco && vendas_tipo_contrato){
      jQuery("#vendas_percelas").parent().find("*").removeClass("button-disabled-venda");
      jQuery("#vendas_percelas").attr("disabled", false);
   }
   else{
      return;
   }

   await fetch(`/sistema/sistema/cliente/novo_cliente/getPrazoConsig.php?vendas_tipo_contrato=${vendas_tipo_contrato}&vendas_banco=${vendas_banco}&vendas_orgao=${vendas_orgao}&empresa=${empresa}`)
   .then(response => response.json())
   .then(data => {
      let options = `<option value=''></option>`;

      if(data){
         options += data.map(element => `<option value='${element.tabela_prazo}'>${element.tabela_prazo}x</option>`).join('');
      }
      
      jQuery("#vendas_percelas").html(options);
   });
}

async function getTabelas(){
   // console.log("getTabelas");

   const empresa = jQuery("#empresa").val();
   const vendas_orgao = jQuery("#vendas_orgao").val();
   const vendas_banco = jQuery("#vendas_banco_id").val();
   const vendas_tipo_contrato = jQuery("#vendas_tipo_contrato").val();
   const vendas_percelas = jQuery("#vendas_percelas").val();

   jQuery("#vendas_tabela").html(`<option value=''></option>`);
   jQuery("#vendas_tabela").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_tabela").attr("disabled", true);

   showCompraDivida(vendas_tipo_contrato);

   if(vendas_orgao && vendas_banco && vendas_tipo_contrato && vendas_percelas){
      jQuery("#vendas_tabela").parent().find("*").removeClass("button-disabled-venda");
      jQuery("#vendas_tabela").attr("disabled", false);
   }
   else{
      return;
   }

   await fetch(`/sistema/sistema/cliente/novo_cliente/getTabelaConsig.php?vendas_percelas=${vendas_percelas}&vendas_tipo_contrato=${vendas_tipo_contrato}&vendas_banco=${vendas_banco}&vendas_orgao=${vendas_orgao}&empresa=${empresa}`)
   .then(response => response.json())
   .then(data => {
      let options = `<option value=''></option>`;

      if(data){
         options += (vendas_tipo_contrato == 6) ? 
            (data.map(element => `<option value='${element.tabela_id}'>${element.tabela_nome}</option>`).join('')) :
            (data.map(element => `<option value='${element.tabela_id}'>${element.tabela_nome}. - Tipo: ${element.tabela_tipo} - Prazo: ${element.tabela_prazo}x</option>`).join(''))
      }

      jQuery("#vendas_tabela").html(options);
   });
}

async function getCoeficiente(){
   // console.log("getCoeficiente");

   const empresa = jQuery("#empresa").val();
   const vendas_orgao = jQuery("#vendas_orgao").val();
   const vendas_banco = jQuery("#vendas_banco_id").val();
   const vendas_tipo_contrato = jQuery("#vendas_tipo_contrato").val();
   const vendas_percelas = jQuery("#vendas_percelas").val();
   const vendas_tabela = jQuery("#vendas_tabela").val();
   const vendas_valor_parcela = jQuery("#vendas_valor_parcela").val();

   jQuery("#span_vendas_coeficiente").text("");
   jQuery("#vendas_valor").val("");
   jQuery("#vendas_coeficiente").attr("disabled", true);

   showCompraDivida(vendas_tipo_contrato);

   if(vendas_orgao && vendas_banco && vendas_tipo_contrato && vendas_percelas && vendas_tabela && vendas_valor_parcela){
      jQuery("#vendas_tabela").parent().find("*").removeClass("button-disabled-venda");
      jQuery("#vendas_coeficiente").attr("disabled", false);
   }
   else{
      return;
   }

   await fetch(`/sistema/sistema/cliente/novo_cliente/getCoeficienteConsig.php?vendas_tabela=${vendas_tabela}&vendas_valor_parcela=${vendas_valor_parcela}&vendas_percelas=${vendas_percelas}&vendas_tipo_contrato=${vendas_tipo_contrato}&vendas_banco=${vendas_banco}&vendas_orgao=${vendas_orgao}&empresa=${empresa}`)
   .then(response => response.json())
   .then(data => {
      if(data){
         data.vendas_coeficiente != null ? jQuery("#span_vendas_coeficiente").text(`Coeficiente: ${data.vendas_coeficiente}`) : "";
         jQuery("#vendas_coeficiente").val(data.vendas_coeficiente);
         jQuery("#vendas_valor").val(data.vendas_valor);

         formValidation.submitValidation(form_consig);
      }
   });
}

function showCompraDivida(vendas_tipo_contrato){
   const compra_d = jQuery("#compra_d")[0];
   const campo_compra_divida = jQuery("#campo_compra_divida")[0];
   const remove_btn = jQuery("#remove_btn_divida")[0];

   if(vendas_tipo_contrato == "2" || vendas_tipo_contrato == "3" || vendas_tipo_contrato == "4" || vendas_tipo_contrato == "5" || 
   vendas_tipo_contrato == "9" || vendas_tipo_contrato == "13" || vendas_tipo_contrato == "14" || 
   vendas_tipo_contrato == "15" || vendas_tipo_contrato == "17" || vendas_tipo_contrato == "20" ){
      jQuery(compra_d).css("display", "");
   }
   else{
      jQuery(compra_d).css("display", "none");
      jQuery(campo_compra_divida).html("");
      jQuery(campo_compra_divida).css("display", "none");
      jQuery(remove_btn).html("");
   }
}

function addCampoDependente() {
   if (jQuery(".dep_form").length < 1) {
      jQuery("#remove_btn").html('<input type="button" value="Remover Dependentes" onclick="removeCampoDependente(); setValorVenda();" />');
   }

   const dependente = jQuery(
      `<span class='dep_form' style='display: flex; align-items: center; flex-wrap: wrap; gap: 12px 5px;'>
         ${
            (() => {
               if (jQuery(".dep_form").length != 0) 
                  return '<hr style="width: 100%; margin: 5px 0 5px 0;">';

               return '';
            })()
         }
         <div class='input-special'>
            <input type='text' class='dependente_nome info-inputs cad-venda' id='dependente_nome' name='dependente_nome[]' required>
            <label class='cad-venda' for='dependente_nome'>Nome do Dependente</label>
         </div>
         <div class='input-special'>
            <input type='text' class='dependente_cpf info-inputs cad-venda' id='dependente_cpf' name='dependente_cpf[]' maxlength="14" oninput='this.value = BrazilianValues.formatToCPF(this.value);' style='width: 174px;' required>
            <label class='cad-venda' for='dependente_cpf'>CPF do Dependente</label>
         </div>
         <div class="input-special">
            <select class="dependente_sexo info-inputs cad-venda" name="dependente_sexo[]" id="dependente_sexo" onchange='getParentesco(this)' required>
               <option></option>
               <option value="M">Masculino</option>
               <option value="F">Feminino</option>
            </select>
            <label class='cad-venda' for="dependente_sexo">Sexo</label>
         </div>
         <div class='input-special no-style-top'>
            <input type='date' class='dependente_nascimento info-inputs cad-venda' id='dependente_nascimento' name='dependente_nascimento[]' required>
            <label class='cad-venda' for='dependente_nascimento'>Data de Nascimento</label>
         </div>
         <div class='input-special'>
            <select class="dependente_parentesco info-inputs cad-venda requires-input" name="dependente_parentesco[]" id="dependente_parentesco" style='min-width: 111px;' disabled required></select>
            <label class='cad-venda' for='dependente_parentesco'>Parentesco</label>
         </div>
         <div class='input-special'>
            <input type='text' class='dependente_celular info-inputs cad-venda not-required' id='dependente_celular' name='dependente_celular[]' maxlength="16" size="12" onkeyup="maskPhone(this)" required>
            <label class='cad-venda' for='dependente_celular'>Telefone</label>
         </div>
         <div class='input-special'>
            <input type='text' class='dependente_email info-inputs cad-venda not-required' id='dependente_email' name='dependente_email[]' required>
            <label class='cad-venda' for='dependente_email'>Email</label>
         </div>
      </span>`);

   jQuery("#campo_dependentes").append(dependente);

   formValidation.loadIndividualValidation(form_seguros);
}

function removeCampoDependente() {
   var len = jQuery(".dep_form").length;
   if (len > 0) {
       jQuery(".dep_form:last").remove();
       if (len == 1) {
           jQuery("#remove_btn").empty();
       }
   }
   if (jQuery("#add_btn").html() == "" && len <= 10) {
       jQuery("#add_btn").html('<input type="button" value="Adicionar Dependente" onclick="addCampoDependente();" />');
   }
}

function toggleBeneficiarioDependente(apolice_dep_ben){
   let html = "";

   if(apolice_dep_ben == 1){
      html = 
      `<h5 class="">Beneficiários:</h5>

      <div id="campo_beneficiarios" style="display: flex; flex-direction: column; gap: 12px 0;"></div>

      <div class="linha_flex" style="margin-bottom: 10px;">
         <span id="add_btn" style="float: right;">
            <button type="button" class="cad-venda" onclick="addCampoBeneficiario();">Adicionar Beneficiário</button>
         </span>
         <span id="remove_btn"></span>
      </div>
      
      <hr style="width: 100%; margin: 0 0 8px 0; background: #ddd;">`;
   }
   else if(apolice_dep_ben == 2){
      html = 
      `<h5 class="">Dependentes:</h5>

      <div id="campo_dependentes" style="display: flex; flex-direction: column; gap: 12px 0;"></div>

      <div class="linha_flex" style="margin-bottom: 10px;">
         <span id="add_btn" style="float: right;">
            <button type="button" class="cad-venda" onclick="addCampoDependente(); setValorVenda();">Adicionar Dependente</button>
         </span>
         <span id="remove_btn"></span>
      </div>
      
      <hr style="width: 100%; margin: 0 0 8px 0; background: #ddd;">`;
   }
   else{
      html = "";
   }

   jQuery("#beneficiario_ou_dependente").html(html);
}

function getParentesco(element){
   const sexo = element.selectedOptions[0].value;
   const parentesco = jQuery(element).parent().parent().find("#dependente_parentesco");
   let options = "";

   jQuery(parentesco).attr("disabled", false);

   if(sexo == "M"){
      options = 
      `<option></option>
      <option value='PAI'>Pai</option>
      <option value='FILHO(A)'>Filho</option>
      <option value='CONJUGE'>Cônjuge</option>`;
   }
   else if(sexo == "F"){
      options = 
      `<option></option>
      <option value='MAE'>Mãe</option>
      <option value='FILHO(A)'>Filha</option>
      <option value='CONJUGE'>Cônjuge</option>`;
   }
   else{
      jQuery(parentesco).attr("disabled", true);
   }

   jQuery(parentesco).html(options);
}

function setValorVenda(){
   let apolice_valor = 0;

   if(jQuery("#apolice_valor").val() != ""){
      apolice_valor = parseFloat(jQuery("#apolice_valor").val().replace(".", "").replace(",", "."));
   }

   const valor_apolice = parseFloat(jQuery("#vendas_apolice option:selected").attr("apolice_valor")); //valor da apolice em float
   let valor_total = 0;
   
   valor_total += valor_apolice; //adiciona valor da apólice no total
   
   //para cada dependente a partir do 4º, adiciona 14,33 ao total
   if(jQuery(".dep_form").length > 3) valor_total += ((jQuery(".dep_form").length - 3) * 14.33);

   valor_total += apolice_valor; //adicionar o valor da apolice digitado, caso apólice tenha valor livre

   valor_total = valor_total.toFixed(2) //transforma em string e reduz casas decimais pra 2;

   jQuery("#valor_venda_seguro_label").text("R$ "+float2moeda(valor_total)); //converte total pra string e poe na label

   jQuery("#vendas_valor_seguro").val(valor_total); //poe total da venda no input hidden
}

function getOrgaos() {
   const vendas_apolice = jQuery("#vendas_apolice").val();

   jQuery("#vendas_orgao").html(`<option value=''></option>`);
   jQuery("#vendas_orgao").parent().find("*").addClass("button-disabled-venda")
   jQuery("#vendas_orgao").attr("disabled", true);
   
   if(vendas_apolice){
      jQuery("#vendas_orgao").parent().find("*").removeClass("button-disabled-venda");
      jQuery("#vendas_orgao").attr("disabled", false);
   }
   else{
      return;
   }

   jQuery.ajax({
      type: "GET",
      url: "/sistema/sistema/cliente/novo_cliente/getOrgaos.php",
      data: {
         vendas_apolice: vendas_apolice
      },
      success: function(response){
         // console.log(response);
         let options = `<option value=''></option>`;

         if(response){            
            options += response.map(element => 
               `<option ${
                  (() => {
                     if (element.orgao_nome.toUpperCase() == jQuery("#cliente_orgao").val().toUpperCase()) 
                        return 'selected';
                  })()
               } value='${element.orgao_id}'>${element.orgao_nome}</option>`
            ).join('');
         }

         jQuery("#vendas_orgao").html(options);
      }
   })
}

function enableValorApolice(){
   const seguradora = jQuery("#vendas_banco").val();
   const apolice_valor = parseFloat(jQuery("#vendas_apolice option:checked").attr("apolice_valor"));
   jQuery("#apolice_valor").attr("disabled", true);

   if(seguradora && apolice_valor == 0){
      jQuery("#apolice_valor").attr("disabled", false);
	  jQuery("#campo_apolice_valor").show();
	  jQuery(".valor_venda_seguro_label").hide();
   }else{
	  jQuery("#campo_apolice_valor").hide();
	  jQuery(".valor_venda_seguro_label").show();
   }
}