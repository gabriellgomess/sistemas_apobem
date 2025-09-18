<?php 
function getError($cod_erro)
{
	switch($cod_erro)
	{
		case "0":
			return "Transação OK";
		case "1":
			return "Campo NIT nulo";
		case "2":
			return "Request enviado é nulo";
		case "3":
			return "Campo NIT inválido";
		case "4":
			return "Campo authorizerId nulo";
		case "5":
			return "Campo authorizerId inválido";
		case "6":
			return "Campo autoConfirmation nulo";
		case "7":
			return "Campo autoConfirmation vazio";
		case "8":
			return "Campo cardExpireDate nulo";
		case "9":
			return "Campo cardExpireDate inválido";
		case "10":
			return "Campo cardNumber nulo ";
		case "11":
			return "Campo cardNumber inválido ";
		case "12":
			return "Campo cardSecurityCode nulo ";
		case "13":
			return "Campo cardSecurityCode inválido ";
		case "14":
			return "Campo installments nulo ";
		case "15":
			return "Campo installments inválido ";
		case "16":
			return "Campo installmentType nulo ";
		case "17":
			return "Campo installmentType inválido ";
		case "18":
			return "Campo customerId nulo ";
		case "19":
			return "Campo customerId inválido ";
		case "20":
			return "Campo departureTax nulo ";
		case "21":
			return "Campo departureTax inválido ";
		case "22":
			return "Campo firstInstallment nulo ";
		case "23":
			return "Campo firstInstallment inválido ";
		case "24":
			return "Campo softDescriptor nulo ";
		case "25":
			return "Campo softDescriptor inválido ";
		case "26":
			return "Campo merchantKey nulo ";
		case "27":
			return "Campo merchantKey inválido ";
		case "28":
			return "Campo nsuSitef nulo ";
		case "29":
			return "Campo nsuSitef inválido ";
		case "30":
			return "Campo merchantId nulo ";
		case "31":
			return "Campo merchantId inválido ";
		case "32":
			return "Campo amount nulo ";
		case "33":
			return "Campo amount inválido ";
		case "34":
			return "Campo esitefUSN nulo ";
		case "35":
			return "Campo esitefUSN invalid ";
		case "36":
			return "Campo merchantUSN nulo ";
		case "37":
			return "Campo merchantUSN inválido ";
		case "38":
			return "Campo ordered inválido ";
		case "39":
			return "Campo parameter inválido ";
		case "40":
			return "Campo parameter nulo ";
		case "41":
			return "Campo parameterNumber inválido ";
		case "42":
			return "Campo jsonXml nulo ";
		case "43":
			return "Campo jsonXml inválido ";
		case "44":
			return "Método nulo ";
		case "45":
			return "Método inválido ";
		case "46":
			return "Campo url inválido ";
		case "47":
			return "Campo redirect inválido ";
		case "48":
			return "Campo card_hash nulo ";
		case "49":
			return "Campo card_hash inválido ";
		case "50":
			return "Campo financing_type nulo ";
		case "51":
			return "Campo financing_type inválido ";
		case "52":
			return "Campo sitef_ip inválido ";
		case "53":
			return "Campo sitef_port inválido ";
		case "54":
			return "Campo sitef_terminal inválido ";
		case "55":
			return "Campo sitef_merchant_id inválido ";
		case "56":
			return "Campo confirmation_data inválido ";
		case "57":
			return "Campo confirmation_type inválido ";
		case "58":
			return "Campo inner_transactions vazio ou nulo ";
		case "59":
			return "Campo extra_field inválido ";
		case "60":
			return "Campo prefixes / key vazio ";
		case "61":
			return "Campo prefixes / value vazio ";
		case "62":
			return "Campo additional_data inválido ";
		case "63":
			return "Campo prefixes inválido ";
		case "64":
			return "Campo financing_type com juros não permitido ";
		case "68":
			return "Campo dealer_code nulo ";
		case "69":
			return "Campo dealer_code inválido ";
		case "70":
			return "Campo phone_ddd nulo ";
		case "71":
			return "Campo phone_number nulo ";
		case "72":
			return "Campo amount_key inválido ";
		case "73":
			return "Campos card_number e card_token não podem ser utilizados simultaneamente ";
		case "74":
			return "Campo amount não pode ser pré-fixado para recarga ";
		case "75":
			return "Campo phone_ddd inválido ";
		case "76":
			return "Campo phone_number inválido ";
		case "77":
			return "Campo store_card inválido ";
		case "78":
			return "Campo store_identification nulo ";
		case "79":
			return "Campo recharge_included inválido ";
		case "80":
			return "Autorizadora não implementada para mobile ";
		case "81":
			return "Operação não permitida ";
		case "82":
			return "Transação não encontrada ";
		case "83":
			return "Campo store_identification inválido ";
		case "84":
			return "Armazenamento não permitido para a autozadora escolhida ";
		case "85":
			return "Campo purchase_summary invalido  ";
		case "86":
			return "Lista de itens vazia ";
		case "87":
			return "Campo authenticate inválido ";
		case "88":
			return "Cartão inválido. Verifique o número do cartão ou a forma de pagamento escolhida. ";
		case "89":
			return "Erro ao recuperar a transação ";
		case "100":
			return "Falha ao efetuar transação de pagamento ";
		case "101":
			return "Falha ao confirmar transação de pagamento ";
		case "102":
			return "Transação já finalizada ou em andamento ";
		case "103":
			return "Tipo de transação inválido ";
		case "104":
			return "Autorizadora para a transação inválida ";
		case "105":
			return "Estilo da transação inválido ";
		case "106":
			return "Loja sem permissão para a operação ";
		case "107":
			return "Operação não permitida para o status da transação ";
		case "108":
			return "Autorizadora inativa ou não habilitada para a loja. ";
		case "109":
			return "Parcelas excedem o limite configurado na autorizadora ";
		case "110":
			return "Loja inativa ";
		case "111":
			return "Tipo de Autorizadora Inválida ";
		case "112":
			return "Autorizadora com configuração inválida ";
		case "113":
			return "Autorizadora não habilitada ou inativa para a Loja. ";
		case "114":
			return "Transação Bloqueada. ";
		case "115":
			return "Erro de autenticidade ";
		case "116":
			return "Erro na consulta de transação. Por favor tente novamente mais tarde. ";
		case "131":
			return "Falha de comunicação com o SiTef ";
		case "132":
			return "Request mal formatado ";
		case "134":
			return "Erro na consulta de cartão (card query) ";
		case "135":
			return "Loja não possui permissão para pagamento Split. ";
		case "136":
			return "Falha no rollback ";
		case "137":
			return "Operação inválida. ";
		case "138":
			return "Falha na escolha de autorizadora ";
		case "139":
			return "Não existe autorizadora configurada ";
		case "140":
			return "Consulta devolveu muitas autorizadoras, não foi possível identificar unicamente ";
		case "141":
			return "A autorizadora passada não é roteada via SiTef ";
		case "142":
			return "Erro ao consultar cartão: resposta inesperada da autorizadora ";
		case "143":
			return "O número de cartão enviado não corresponde à forma de pagamento escolhida. ";
		case "144":
			return "Loja sem permissão para Recarga V5 HTML ";
		case "145":
			return "Loja sem permissão para Recarga V5 WebService ";
		case "146":
			return "Erro na autenticação ";
		case "147":
			return "Autenticação Negada ";
		case "148":
			return "Autenticação não permitida para esta autorizadora ";
		case "149":
			return "Loja sem dados cadastrais de autenticação ISA ";
		case "150":
			return "Request inválido ";
		case "157":
			return "Campo showTimesInvoice inválido ";
		case "160":
			return "Loja sem permissão para transações HTML ";
		case "161":
			return "Loja sem permissão para transações de pagamento ";
		case "162":
			return "Loja sem permissão para transações IATA ";
		case "163":
			return "Cadastro da Autorizadora com erros. ";
		case "215":
			return "Venda com cartão tipo Gift não habilitada ";
		case "216":
			return "Recarga de cartão tipo Gift não habilitada ";
		case "217":
			return "Cancelamento de pagamento via cartão tipo Gift não habilitada ";
		case "230":
			return "campo payer neighborhood inválido ";
		case "231":
			return "campo payer uf inválido ";
		case "232":
			return "campo payer name inválido  ";
		case "233":
			return "campo payer address_street_name / address_street_number inválido ";
		case "234":
			return "campo payer identification_number inválido ";
		case "235":
			return "campo payer city inválido ";
		case "236":
			return "campo payer zipcode inválido ";
		case "255":
			return "Pagamento negado ";
		case "256":
			return "Aguardando resposta do cancelamento ";
		case "257":
			return "Estorno negado ";
		case "258":
			return "Recarga negada ";
		case "259":
			return "Transação negada ";
		case "260":
			return "Recarga desfeita por falha no pagamento ";
		case "300":
			return "Erro ao criar transação de refund ";
		case "301":
			return "Campo card_number diferente ";
		case "302":
			return "Falha de comunicação ";
		case "304":
			return "Campo extra_info inválido ";
		case "305":
			return "Campo currency inválido ";
		case "306":
			return "Campo insurance_amt inválido ";
		case "307":
			return "Campo handling_amt inválido ";
		case "308":
			return "Campo tax_amount inválido ";
		case "309":
			return "Campo inner_transaction order_id inválido ";
		case "310":
			return "Campo inner_transaction merchant_id inválido ";
		case "311":
			return "Campo inner_transaction merchant_usn inválido ";
		case "312":
			return "Campo inner_transaction amount inválido ";
		case "313":
			return "campo shipment type inválido ";
		case "314":
			return "Campo shipment cost inválido ";
		case "315":
			return "Campo shipment discount_amount inválido ";
		case "316":
			return "Campo shipment receiver_address_apartment inválido ";
		case "317":
			return "Campo shipment receiver_address_floor inválido ";
		case "318":
			return "Campo shipment receiver_address_street_name inválido ";
		case "319":
			return "Campo shipment receiver_address_street_number inválido ";
		case "320":
			return "Campo shipment receiver_address_zip_code inválido ";
		case "321":
			return "Campo shipment receiver_address_complement inválido ";
		case "322":
			return "Campo shipment receiver_address_district inválido ";
		case "323":
			return "Campo shipment receiver_address_city inválido ";
		case "324":
			return "Campo shipment receiver_address_state inválido ";
		case "325":
			return "Campo shipment receiver_address_country inválido ";
		case "326":
			return "Campo shipment receiver_address_name inválido ";
		case "327":
			return "Campo shipment receiver_address_phone_area_code inválido ";
		case "328":
			return "Campo shipment receiver_address_phone_number inválido ";
		case "329":
			return "Campo payment_method installments inválido ";
		case "330":
			return "Campo payment_method excluded_payment_method id inválido ";
		case "331":
			return "Campo payment_method excluded_payment_method name inválido ";
		case "332":
			return "Campo payment_method excluded_payment_method payment_type_id inválido ";
		case "333":
			return "Campo payment_method excluded_payment_method thumbnail inválido ";
		case "334":
			return "Campo payment_method excluded_payment_method secure_thumbnail inválido ";
		case "335":
			return "Campo payment_method excluded_payment_type id inválido ";
		case "336":
			return "Campo payment_method excluded_payment_type name inválido ";
		case "337":
			return "Campo payer phone_number inválido ";
		case "338":
			return "Campo payer phone_area_code inválido ";
		case "339":
			return "Campo payer date_created inválido ";
		case "340":
			return "Campo payer email inválido ";
		case "341":
			return "Campo payer born_date inválido ";
		case "342":
			return "Campo payer mother_name inválido ";
		case "343":
			return "Campo payer father_name inválido ";
		case "344":
			return "Campo payer sex inválido ";
		case "345":
			return "Campo payer phone_country_code inválido ";
		case "346":
			return "Campo payer phone_extension inválido ";
		case "347":
			return "Campo payer phone_extension_type inválido ";
		case "348":
			return "Campo payer tax_type inválido ";
		case "349":
			return "Campo payer address_type inválido ";
		case "350":
			return "Campo item id inválido ";
		case "351":
			return "Campo item description inválido ";
		case "352":
			return "Campo item category_id inválido ";
		case "353":
			return "Campo item picture_url inválido ";
		case "354":
			return "Campo item unit_price inválido ";
		case "355":
			return "Campo item quantity inválido ";
		case "356":
			return "Campo item title inválido ";
		case "357":
			return "Campo item weight inválido ";
		case "358":
			return "Campo item shipping_cost inválido ";
		case "359":
			return "Campo item quantity_itens_sum inválido ";
		case "360":
			return "Campo item tax_amount inválido ";
		case "361":
			return "Campo item weight_unit inválido ";
		case "362":
			return "Campo item length inválido ";
		case "363":
			return "Campo item length_unit inválido ";
		case "364":
			return "Campo item width inválido ";
		case "365":
			return "Campo item width_unit inválido ";
		case "366":
			return "Campo item height inválido ";
		case "367":
			return "Campo item height_unit inválido ";
		case "368":
			return "Campo item url inválido ";
		case "369":
			return "Campo item type inválido ";
		case "370":
			return "Campo extra_param metadata_item key inválido ";
		case "371":
			return "Campo extra_param metadata_item inválido inválido ";
		case "372":
			return "Campo extra_param metadata_item group inválido ";
		case "373":
			return "Campo extra_param prefix key inválido ";
		case "374":
			return "Campo extra_param prefix value inválido ";
		case "375":
			return "Campo extra_param acquirer_param key inválido ";
		case "376":
			return "Campo extra_param acquirer_param value inválido ";
		case "400":
			return "Transação abortada ";
		case "402":
			return "Campo reqConfirmShipping inválido ";
		case "403":
			return "Valor do campo reqConfirmShipping excede tamanho limite ";
		case "404":
			return "Campo noShipping inválido ";
		case "405":
			return "Valor do campo noShipping excede tamanho limite ";
		case "406":
			return "Campo allowNote inválido ";
		case "407":
			return "Valor do campo allowNote excede tamanho limite ";
		case "408":
			return "Campo addrOverride inválido ";
		case "409":
			return "Valor do campo addrOverride excede tamanho limite ";
		case "410":
			return "Valor do campo localeCode excede tamanho limite ";
		case "411":
			return "Valor do campo pageStyle excede tamanho limite ";
		case "412":
			return "Valor do campo hdrImg excede tamanho limite ";
		case "413":
			return "Valor do campo hdrBackColor excede tamanho limite ";
		case "414":
			return "Valor do campo hdrBorderColor excede tamanho limite ";
		case "415":
			return "Valor do campo payFlowColor excede tamanho limite ";
		case "416":
			return "Valor do campo cartBorderColor excede tamanho limite ";
		case "417":
			return "Valor do campo logoImg excede tamanho limite ";
		case "418":
			return "Campo email inválido ";
		case "419":
			return "Valor do campo email excede tamanho limite ";
		case "420":
			return "Valor do campo solutionType excede tamanho limite ";
		case "421":
			return "Valor do campo landingPage excede tamanho limite ";
		case "422":
			return "Valor do campo channelType excede tamanho limite ";
		case "423":
			return "Valor do campo giroPaySuccessUrl excede tamanho limite ";
		case "424":
			return "Valor do campo giroPayCancelUrl excede tamanho limite ";
		case "425":
			return "Valor do campo bankTxnPendingUrl excede tamanho limite ";
		case "426":
			return "Valor do campo brandName excede tamanho limite ";
		case "427":
			return "Valor do campo customerServiceNumber excede tamanho limite ";
		case "436":
			return "Campo buyerEmailOptinenable inválido ";
		case "437":
			return "Valor do campo buyerEmailOptinenable excede tamanho limite ";
		case "441":
			return "Valor do campo buyerId excede tamanho limite ";
		case "442":
			return "Valor do campo buyerUsername excede tamanho limite ";
		case "443":
			return "Valor do campo buyerRegistrationDate excede tamanho limite ";
		case "444":
			return "Valor do campo allowPushFunding excede tamanho limite ";
		case "445":
			return "Valor do campo taxIdType excede tamanho limite ";
		case "446":
			return "Valor do campo taxId excede tamanho limite ";
		case "448":
			return "Valor do campo L_billingType excede tamanho limite ";
		case "449":
			return "Valor do campo L_billingAgreementDescription excede tamanho limite ";
		case "450":
			return "Valor do campo L_billingAgreementCustom excede tamanho limite ";
		case "451":
			return "Valor do campo L_paymentType excede tamanho limite ";
		case "452":
			return "Valor do campo paymentRequest_n_paymentReason excede tamanho limite ";
		case "453":
			return "Campo paymentRequest_n_amt inválido ";
		case "454":
			return "Valor do campo paymentRequest_n_amt excede valor limite ";
		case "455":
			return "Valor do campo paymentRequest_n_currencyCode excede tamanho limite ";
		case "456":
			return "Campo paymentRequest_n_itemAmt inválido ";
		case "457":
			return "Campo paymentRequest_n_shippingAmt inválido ";
		case "458":
			return "Campo paymentRequest_n_insuranceAmt inválido ";
		case "459":
			return "Campo paymentRequest_n_shipDiscAmt inválido ";
		case "460":
			return "Valor do campo paymentRequest_n_insuranceOptionOffered excede tamanho limite ";
		case "461":
			return "Campo paymentRequest_n_handlingAmt inválido ";
		case "462":
			return "Campo paymentRequest_n_taxAmt inválido ";
		case "463":
			return "Valor do campo paymentRequest_n_desc excede tamanho limite ";
		case "464":
			return "Valor do campo paymentRequest_n_custom excede tamanho limite ";
		case "465":
			return "Valor do campo paymentRequest_n_invNum excede tamanho limite ";
		case "466":
			return "Valor do campo paymentRequest_n_noteText excede tamanho limite ";
		case "467":
			return "Valor do campo paymentRequest_n_allOwedPaymentMethod excede tamanho limite ";
		case "468":
			return "Valor do campo paymentRequest_n_paymentAction excede tamanho limite ";
		case "469":
			return "Valor do campo paymentRequest_n_paymentRequestId excede tamanho limite ";
		case "470":
			return "Valor do campo paymentRequest_n_sellerPaypalAccountId excede tamanho limite ";
		case "471":
			return "Valor do campo paymentRequest_n_shipToName excede tamanho limite ";
		case "472":
			return "Valor do campo paymentRequest_n_shipToStreet excede tamanho limite ";
		case "473":
			return "Valor do campo paymentRequest_n_shipToStreet2 excede tamanho limite ";
		case "474":
			return "Valor do campo paymentRequest_n_shipToCity excede tamanho limite ";
		case "475":
			return "Valor do campo paymentRequest_n_shipToState excede tamanho limite ";
		case "476":
			return "Valor do campo paymentRequest_n_shipToZip excede tamanho limite ";
		case "477":
			return "Valor do campo paymentRequest_n_shipToCountryCode excede tamanho limite ";
		case "478":
			return "Valor do campo paymentRequest_n_shipToPhoneNum excede tamanho limite ";
		case "479":
			return "Valor do campo L_paymentRequest_n_name excede tamanho limite ";
		case "480":
			return "Valor do campo L_paymentRequest_n_desc do item M excede tamanho limite ";
		case "481":
			return "Campo L_paymentRequest_n_amt do item M inválido ";
		case "482":
			return "Valor do campo L_paymentRequest_n_number excede tamanho limite ";
		case "483":
			return "Campo L_paymentRequest_n_qty inválido ";
		case "484":
			return "Valor do campo L_paymentRequest_n_qty excede tamanho limite ";
		case "485":
			return "Campo L_paymentRequest_n_taxAmt do item M inválido ";
		case "486":
			return "Campo L_paymentRequest_n_itemWeightValue inválido ";
		case "487":
			return "Valor do campo L_paymentRequest_n_itemWeightValue excede tamanho limite ";
		case "488":
			return "Campo L_paymentRequest_n_itemWeightUnit inválido ";
		case "489":
			return "Valor do campo L_paymentRequest_n_itemWeightUnit excede tamanho limite ";
		case "490":
			return "Campo L_paymentRequest_n_itemLenghtValue inválido ";
		case "491":
			return "Valor do campo L_paymentRequest_n_itemLenghtValue excede tamanho limite ";
		case "492":
			return "Campo L_paymentRequest_n_itemLenghtUnit inválido ";
		case "493":
			return "Valor do campo L_paymentRequest_n_itemLenghtUnit excede tamanho limite ";
		case "494":
			return "Campo L_paymentRequest_n_itemWidthValue inválido ";
		case "495":
			return "Valor do campo L_paymentRequest_n_itemWidthValue excede tamanho limite ";
		case "496":
			return "Campo L_paymentRequest_n_itemWidthUnit inválido ";
		case "497":
			return "Valor do campo L_paymentRequest_n_itemWidthUnit excede tamanho limite ";
		case "498":
			return "Campo L_paymentRequest_n_itemHeightValue inválido ";
		case "499":
			return "Valor do campo L_paymentRequest_n_itemHeightValue excede tamanho limite ";
		case "500":
			return "Campo L_paymentRequest_n_itemHeightUnit inválido ";
		case "501":
			return "Valor do campo L_paymentRequest_n_itemHeightUnit excede tamanho limite ";
		case "502":
			return "Valor do campo L_paymentRequest_n_itemUrl excede tamanho limite ";
		case "503":
			return "Valor do campo L_paymentRequest_n_itemCategory excede tamanho limite ";
		case "504":
			return "Falha na requisição para o PayPal ";
		case "505":
			return "Parâmetros do PayPal do lojista não cadastrados ";
		case "506":
			return "Erro de comunicação com o PayPal ";
		case "507":
			return "Erro na requisição para o PayPal ";
		case "508":
			return "Refund: campo INVOICEID inválido ";
		case "509":
			return "Refund: campo REFUNDTYPE inválido ";
		case "510":
			return "Refund: campo amount invalido ";
		case "511":
			return "Refund: campo CURRENCYCODE inválido ";
		case "512":
			return "Refund: campo NOTE inválido ";
		case "513":
			return "Refund: campo RETRYUNTIL inválido ";
		case "514":
			return "Refund: campo REFUNDSOURCE inválido ";
		case "515":
			return "Refund: campo MERCHANTSTOREDETAILS inválido ";
		case "516":
			return "Refund: campo REFUNDADVICE inválido ";
		case "517":
			return "Refund: campo MSGSUBID inválido ";
		case "518":
			return "Refund: campo STOREID inválido ";
		case "519":
			return "Refund: campo TERMINALID inválido ";
		case "520":
			return "Refund: campo PAYERID inválido ";
		case "555":
			return "Erro na comunicação com o Bcash ";
		case "560":
			return "Campo seller_mail nulo ";
		case "561":
			return "Campo seller_mail inválido ";
		case "562":
			return "Campo buyer_mail nulo ";
		case "563":
			return "Campo buyer _mail inválido ";
		case "564":
			return "Campo buyer_name nulo ";
		case "565":
			return "Campo buyer_name inválido ";
		case "566":
			return "Campo buyer_cpf nulo ";
		case "567":
			return "Campo buyer_cpf inválido ";
		case "568":
			return "Campo buyer_phone inválido ";
		case "569":
			return "Campo buyer_cell_phone inválido ";
		case "570":
			return "Campo buyer_address_address inválido ";
		case "571":
			return "Campo buyer_address_number inválido ";
		case "572":
			return "Campo buyer_address_neighborhood inválido ";
		case "573":
			return "Campo buyer_address_city inválido ";
		case "574":
			return "Campo buyer_address_state inválido ";
		case "575":
			return "Campo buyer_address_zip_code inválido ";
		case "576":
			return "Campo buyer_gender nulo ";
		case "577":
			return "Campo buyer_gender inválido ";
		case "578":
			return "Campo payment_method_code nulo ";
		case "579":
			return "Campo payment_method_code inválido ";
		case "580":
			return "Campo accepted_contract nulo ";
		case "581":
			return "Campo accepted_contract inválido ";
		case "582":
			return "Campo viewed_contract nulo ";
		case "583":
			return "Campo viewed_contract inválido ";
		case "584":
			return "Campo product_code nulo ";
		case "585":
			return "Campo product_code inválido ";
		case "586":
			return "Campo product_description nulo ";
		case "587":
			return "Campo product_description inválido ";
		case "588":
			return "Campo product_amount nulo ";
		case "589":
			return "Campo product_amount inválido ";
		case "590":
			return "Campo product_value nulo ";
		case "591":
			return "Campo buyer_address_complement inválido ";
		case "592":
			return "Campo buyer_birth_date inválido ";
		case "593":
			return "Campo buyer_rg inválido ";
		case "594":
			return "Campo buyer_issue_rg_date inválido ";
		case "595":
			return "Campo buyer_organ_consignor_rg inválido ";
		case "596":
			return "Campo buyer_state_consignor_rg inválido ";
		case "597":
			return "Campo buyer_company_name inválido ";
		case "598":
			return "Campo buyer_cnpj inválido ";
		case "599":
			return "Campo free inválido ";
		case "600":
			return "Campo freight inválido ";
		case "601":
			return "Campo freight_type inválido ";
		case "602":
			return "Campo discount inválido ";
		case "603":
			return "Campo addition inválido ";
		case "604":
			return "Campo installments inválido ";
		case "605":
			return "Campo currency inválido ";
		case "606":
			return "Campo campaign_id inválido ";
		case "607":
			return "Campo product_extended_warranty_month_warranty inválido ";
		case "608":
			return "Campo product_extended_warranty_amount inválido ";
		case "609":
			return "Campo product_extra_description inválido ";
		case "610":
			return "Campo dependent_transaction_email inválido ";
		case "611":
			return "Campo dependent_transaction_value inválido ";
		case "612":
			return "Campo credit_card_holder inválido ";
		case "650":
			return "Erro na comunicação com o MercadoPago ";
		case "659":
			return "Campo items / quantity nulo ";
		case "660":
			return "Campo items / quantity inválido ";
		case "679":
			return "Campo payer / phone_area_code nulo ";
		case "680":
			return "Campo payer / phone_area_code inválido ";
		case "681":
			return "Campo payer / phone_number nulo ";
		case "682":
			return "Campo payer / phone_number inválido ";
		case "683":
			return "Campo payer / identification_type nulo ";
		case "684":
			return "Campo payer / identification_type inválido ";
		case "685":
			return "Campo payer / identification_number nulo ";
		case "686":
			return "Campo payer / identification_number inválido ";
		case "687":
			return "Campo payer / address_street_name nulo ";
		case "688":
			return "Campo payer / address_street_name inválido ";
		case "689":
			return "Campo payer / address_street_number nulo ";
		case "690":
			return "Campo payer / address_street_number inválido ";
		case "691":
			return "Campo payer / address_zip_code nulo ";
		case "692":
			return "Campo payer / address_zip_code inválido ";
		case "701":
			return "Campo payment_method / excluded_payment_installments nulo ";
		case "702":
			return "Campo payment_method / excluded_payment_installments inválido ";
		case "703":
			return "Campo payment_method / excluded_payment_methods / id inválido ";
		case "704":
			return "Campo payment_method / excluded_payment_methods / name inválido ";
		case "705":
			return "Campo payment_method / excluded_payment_methods / payment_type_id inválido ";
		case "706":
			return "Campo payment_method / excluded_payment_methods / thumbnail inválido ";
		case "707":
			return "Campo payment_method / excluded_payment_methods / secure_thumbnail inválido ";
		case "708":
			return "Campo payment_method / excluded_payment_types / id inválido ";
		case "709":
			return "Campo payment_method / excluded_payment_types / name inválido ";
		case "810":
			return "Erro na comunicação com o PagSeguro. ";
		case "820":
			return "Campo currency nulo ";
		case "821":
			return "Campo currency inválido ";
		case "822":
			return "Campo items / id nulo ";
		case "823":
			return "Campo items / id inválido ";
		case "824":
			return "Campo items / description nulo ";
		case "825":
			return "Campo items / description inválido ";
		case "826":
			return "Campo items / unit_price nulo ";
		case "827":
			return "Campo items / unit_price inválido ";
		case "828":
			return "Campo items / quantity nulo ";
		case "829":
			return "Campo items / quantity inválido ";
		case "830":
			return "Campo items / shipping_cost inválido ";
		case "831":
			return "Campo extra_amount inválido ";
		case "850":
			return "Erro na comunicação com o Banco do Brasil. ";
		case "860":
			return "Campo CEP do endereço do pagador nulo. ";
		case "861":
			return "Campo CEP do endereço do pagador inválido. ";
		case "862":
			return "Campo nome da rua do pagador nulo. ";
		case "863":
			return "Campo número na rua do pagador nulo. ";
		case "864":
			return "Campo endereço do pagador inválido. ";
		case "865":
			return "Campo cidade do pagador nulo. ";
		case "866":
			return "Campo cidade do pagador inválido. ";
		case "867":
			return "Campo nome do pagador nulo. ";
		case "868":
			return "Campo nome do pagador inválido. ";
		case "869":
			return "Campo estado do pagador nulo. ";
		case "870":
			return "Campo estado do pagador inválido. ";
		case "871":
			return "Erro na comunicação com a Banrisul Vero. ";
		case "880":
			return "Transação negada pela Elavon. ";
		case "881":
			return "Campo id da transação nulo. ";
		case "882":
			return "Campo id da transação inválido. ";
		case "883":
			return "Ação de pagamento inválida. ";
		case "884":
			return "Campo endereço de IP inválido. ";
		case "885":
			return "Campo id de terminal inválido. ";
		case "886":
			return "Campo valor total inválido. ";
		case "887":
			return "Campo de data e hora inválido. ";
		case "888":
			return "Campo cartão inválido. ";
		case "889":
			return "Campo indicador de CVV inválido. ";
		case "890":
			return "Campo quantia inválido. ";
		case "891":
			return "Campo capacidade de entrada do POS inválido. ";
		case "892":
			return "Campo modo de entrada do cartão inválido. ";
		case "893":
			return "Campo número do cartão inválido. ";
		case "894":
			return "Campo chave de registro inválido. ";
		case "895":
			return "Campo autorizadora inválido. ";
		case "896":
			return "Campo tipo de financiamento inválido. ";
		case "897":
			return "Campo parcelas inválido. ";
		case "910":
			return "Transação negada pela e-Rede. ";
		case "920":
			return "Transação negada pela Cielo e-Commerce. ";
		case "930":
			return "Erro na comunicação com a Visa Checkout. ";
		case "931":
			return "Transação inválida. ";
		case "932":
			return "Pagamento já confirmado. ";
		case "933":
			return "Boleto Expirado. ";
		case "934":
			return "Erro na autorizadora Masterpass ao transacionar. ";
		case "940":
			return "Transação negada pela GetNet WS. ";
		case "941":
			return "Usuário não autenticado na GetNet WS. ";
		case "942":
			return "Nome de usuário inválido. ";
		case "943":
			return "Senha inválida. ";
		case "944":
			return "Id do comerciante inválido. ";
		case "945":
			return "Faixa de ID do comerciante. ";
		case "946":
			return "ID do terminal. ";
		case "947":
			return "Campo de cartão nulo. ";
		case "948":
			return "Id de pagamento nulo. ";
		case "949":
			return "PARes nulo. ";
		case "950":
			return "Transação negada pela Global Payments WS. ";
		case "951":
			return "Requisição nula. ";
		case "952":
			return "Código de comerciante inválido. ";
		case "953":
			return "Terminal inválido. ";
		case "954":
			return "Assinatura inválida. ";
		case "970":
			return "Transação negada pela SafetyPay. ";
		case "971":
			return "Motivo de reembolso nulo. ";
		case "972":
			return "Credencial nula. ";
		case "989":
			return "Instituição antifraude inválida para notificar. ";
		case "990":
			return "ID de comerciante nulo. ";
		case "991":
			return "Código de comerciante nulo. ";
		case "992":
			return "Tipo de pagamento inválido para antifraude. ";
		case "993":
			return "Autorizadora inválida para antifraude. ";
		case "994":
			return "Pagamento Split não permitido para antifraude. ";
		case "995":
			return "Transação do tipo recarga não permitida para antifraude. ";
		case "996":
			return "Entidade para análise antifraude não cadastrada. ";
		case "997":
			return "Loja sem permissão para antifraude. ";
		case "998":
			return "Análise antifraude rejeitada, inválida ou em revisão. ";
		case "999":
			return "Item id do antifraude nulo. ";
		case "980":
			return "Titular do cartão nulo. ";
		case "981":
			return "Chave de pedido nula. ";
		case "1000":
			return "Erro inesperado no e-SiTef. Entre em contato com o suporte. ";
		case "1003":
			return "Erro inesperado no e-SiTef. Entre em contato com o suporte. ";
		case "1004":
			return "Erro inesperado no e-SiTef. Entre em contato com o suporte. ";
		case "1010":
			return "Erro inesperado de acesso à base de dados do e-SiTef. Entre em contato com o suporte. ";
		case "2000":
			return "Erro inesperado no e-SiTef. Entre em contato com o suporte. ";
		case "5555":
			return "Erro inesperado no e-SiTef. Entre em contato com o suporte. ";
		default:
			return "Erro código: ".$cod_erro;
	}
}
?>