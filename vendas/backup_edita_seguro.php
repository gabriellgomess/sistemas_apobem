<link href="templates/gk_music/css/template.portal.css" rel="stylesheet" type="text/css" />
<link href="sistema/vendas/css/edita_seguros.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<script src="/sistema/sistema/utils/brazilian-values.js"></script>
<script src="/sistema/sistema/utils/valida_form.js"></script>
<script src="/sistema/sistema/utils/formdata_to_json.js"></script>
<script type="text/javascript" src="sistema/vendas/js/softwareexpress/verifica_cartao_edita_venda.js"></script>


<?php

// "$user_id" já vem do includes mas nesse arquivo é utilizado como "$userid"
$userid = $user_id;
$result_url = mysql_query("SELECT url_consulta_clientes FROM jos_users WHERE id = " . $userid . ";")
	or die(mysql_error());
$row_url = mysql_fetch_array($result_url);
$link_consulta = $row_url["url_consulta_clientes"];
?>
<?php if ($_GET["fechar"] == "1") : ?>
	<meta http-equiv="Refresh" content="0; url=<?php echo $link_consulta; ?>">
<?php else : ?>
	<script type="text/javascript" src="sistema/vendas/js/datepicker.js"></script>
	<link href="sistema/vendas/css/datepicker.css" rel="stylesheet" type="text/css" />
	<script language="javascript">
		window.addEventListener("load", function() {
			listaProfissoes();
			verificaEnderecoGravacao();
		});


		//-----------------------------------------------------
		//Funcao: MascaraMoeda
		//Sinopse: Mascara de preenchimento de moeda
		//Parametro:
		//   objTextBox : Objeto (TextBox)
		//   SeparadorMilesimo : Caracter separador de milésimos
		//   SeparadorDecimal : Caracter separador de decimais
		//   e : Evento
		//Retorno: Booleano
		//Autor: Gabriel Fróes - www.codigofonte.com.br
		//-----------------------------------------------------
		function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e) {
			var sep = 0;
			var key = '';
			var i = j = 0;
			var len = len2 = 0;
			var strCheck = '0123456789';
			var aux = aux2 = '';
			var whichCode = (window.Event) ? e.which : e.keyCode;
			if (whichCode == 13) return true;
			var t = new String(objTextBox.value);
			if (whichCode == 8) {
				objTextBox.value = t.substring(0, t.length - 1);
			}
			key = String.fromCharCode(whichCode); // Valor para o código da Chave
			if (strCheck.indexOf(key) == -1) return false; // Chave inválida
			len = objTextBox.value.length;
			for (i = 0; i < len; i++)
				if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
			aux = '';
			for (; i < len; i++)
				if (strCheck.indexOf(objTextBox.value.charAt(i)) != -1) aux += objTextBox.value.charAt(i);
			aux += key;
			len = aux.length;
			if (len == 0) objTextBox.value = '';
			if (len == 1) objTextBox.value = '0' + SeparadorDecimal + '0' + aux;
			if (len == 2) objTextBox.value = '0' + SeparadorDecimal + aux;
			if (len > 2) {
				aux2 = '';
				for (j = 0, i = len - 3; i >= 0; i--) {
					if (j == 3) {
						aux2 += SeparadorMilesimo;
						j = 0;
					}
					aux2 += aux.charAt(i);
					j++;
				}
				objTextBox.value = '';
				len2 = aux2.length;
				for (i = len2 - 1; i >= 0; i--)
					objTextBox.value += aux2.charAt(i);
				objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
			}
			return false;
		}
	</script>
	<script language='JavaScript'>
		function SomenteNumero(e) {
			var tecla = (window.event) ? event.keyCode : e.which;
			if ((tecla > 47 && tecla < 58)) return true;
			else {
				if (tecla == 8 || tecla == 0) return true;
				else return false;
			}
		}

		function salvando_modal() {
			svng = document.createElement('div');
			svng.style.position = 'fixed';
			svng.style.width = '100%';
			svng.style.height = '100vh';
			svng.style.top = '0';
			svng.style.left = '0';
			svng.style.background = "rgba(0,0,0,0.5)";
			svng.style.textAlign = "center";
			svng.style.zIndex = "99999";

			gif = document.createElement('img');
			gif.src = 'sistema/imagens/loading_gif.gif';
			gif.style.width = "5%";
			gif.style.display = "inline-block";
			gif.style.transform = "translateY( calc(50vh - 100%) )";

			svng.appendChild(gif);

			document.body.appendChild(svng);
		}
	</script>
	<script language='JavaScript'>
		function ContaCorrente(e) {
			var tecla = (window.event) ? event.keyCode : e.which;
			if ((tecla > 47 && tecla < 58) || (tecla == 8 || tecla == 0 || tecla == 120 || tecla == 45)) return true;
			else {
				return false;
			}
		}
	</script>
	<script type="text/javascript">
		//Initialize first demo:


		ddaccordion.init({
			headerclass: "mypets2", //Shared CSS class name of headers group
			contentclass: "thepet2", //Shared CSS class name of contents group
			revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
			mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
			collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
			defaultexpanded: [], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
			onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
			animatedefault: false, //Should contents open by default be animated into view?
			scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
			persiststate: false, //persist state of opened contents within browser session?
			toggleclass: ["", "openpet2"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
			togglehtml: ["none", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
			animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
			oninit: function(expandedindices) { //custom code to run when headers have initalized
				//do nothing
			},
			onopenclose: function(header, index, state, isuseractivated) { //custom code to run whenever a header is opened or closed
				//do nothing
			}
		})
	</script>
	<script type="text/javascript">
		/* Máscaras ER */
		function mascara(o, f) {
			v_obj = o
			v_fun = f
			setTimeout("execmascara()", 1)
		}

		function execmascara() {
			v_obj.value = v_fun(v_obj.value)
		}

		function mcc(v) {
			v = v.replace(/\D/g, "");
			v = v.replace(/^(\d{4})(\d)/g, "$1 $2");
			v = v.replace(/^(\d{4})\s(\d{4})(\d)/g, "$1 $2 $3");
			v = v.replace(/^(\d{4})\s(\d{4})\s(\d{4})(\d)/g, "$1 $2 $3 $4");
			return v;
		}

		function id(el) {
			return document.getElementById(el);
		}
	</script>
	<script LANGUAGE="JavaScript">
		function ClipBoard() {
			holdtext.innerText = copytext.innerText;
			Copied = holdtext.createTextRange();
			Copied.execCommand("Copy");
		}
	</SCRIPT>
	<style type="text/css">
		.idLink {
			cursor: pointer;
		}

		table.split-date-wrap {
			width: auto;
			margin-bottom: 0;
		}

		table.split-date-wrap td {
			padding: 0 0.2em 0.4em 0;
			border-bottom: 0 none;
		}

		table.split-date-wrap td input {
			margin-right: 0.3em;
		}

		table.split-date-wrap td label {
			font-size: 10px;
			font-weight: normal;
			display: block;
		}

		.campo-titulo-cartao {
			width: 60%;
			text-align: right;
		}

		.modalEdit {
			margin-left: 399px;
			background: white;
			z-index: 99999999999999 !important;
			position: fixed;
			width: 600px;
			height: 412px;
			margin: left;
			box-shadow: 0px 0px 12px 0px #666;
			margin-top: -646px;
			border-radius: 5px;
			left: 0;
		}

		.postDelete {
			margin-left: 220px;
			margin-top: 29px;
		}

		.TextoDelete {
			margin-top: 69px;
			font-weight: bold;
		}

		.Delete {
			margin-left: 399px;
			background: white;
			z-index: 99999999999999 !important;
			position: fixed;
			width: 600px;
			height: 200px;
			margin: left;
			box-shadow: 0px 0px 12px 0px #666;
			margin-top: -646px;
			border-radius: 5px;
		}
	</style>

	<?php
	$vendas_id = $_GET["vendas_id"];

	$administracao = 0;
	$franquiado = 0;
	$agora = date("Y-m-d H:i:s");
	echo "Horário Agora: " . $agora . "<br />";
	?>

	<?php
	$result = mysql_query("SELECT * FROM sys_vendas_seguros 
INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
WHERE vendas_id = '" . $vendas_id . "';")
		or die(mysql_error());
	$row = mysql_fetch_array($result);

	//echo "Apólice Beneficiário: ".$row['apolice_dep_ben']."<br>";

	$query = "SELECT * FROM sys_vendas_seguros 
	INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
	WHERE vendas_id = '" . $vendas_id . "';";

	//echo $query;
	?>

	<?php
	$result_apolice_tipo = mysql_query("SELECT apolice_tipo FROM sys_vendas_apolices WHERE apolice_id='" . $row["vendas_apolice"] . "'")
		or die(mysql_error());
	$row_apolice_tipo = mysql_fetch_array($result_apolice_tipo);
	echo "<pre style='display: none'>" . $row_apolice_tipo['apolice_tipo'] . "</pre>";
	?>

	<?php
	/*
Chamado#1034248
	"preciso que...
	sempre que a forma de pegamento for Boleto,
	e o Status seja alterado para PAGO,
	pelo usuário Rafael Pinzon,
	o sistema automaticamente gere a data de ATIVAÇÃO no campo indicado. (campo data de ativação)
	> isso se aplica somente a operação APOBEM;"
*/
	// vendas_banco == 11 (11 == APOBEM)
	// Se vendas_pgto == 'boleto' ( 4 == boleto )
	// $user_id do Rafael Pinzon == 129
	if ($row['vendas_banco'] == 11 && $row['vendas_pgto'] == 4 && $user_id == 129) :
	?>
		<script>
			jQuery(document).on("change", "#select_venda_status", function() {
				// vendas_status 9 == pago
				if (jQuery(this).val() == 9) {
					jQuery("#dp-normal-2").val("<?php echo date("d/m/Y"); ?>")
				}
			})
		</script>
	<?php endif; ?>


	<?php if ($row_apolice_tipo['apolice_tipo'] == '1' && $supervisor_equipe_vendas != 1) : ?>
		<script type="text/javascript">
			jQuery(document).ready(function() {

				jQuery(".transacaoData").each(function() {
					var dataBr = jQuery(this).text();

					jQuery(this).html(dataBr.split('-').reverse().join('/'));

				})

				jQuery('#parcelas > tbody  > tr').each(function(index, trItem) {
					//console.log(trItem)

					if (jQuery(this).find(".transacao_usuario").html() == "integrador.automatico") {
						if (jQuery("#includes_user_id").val() != 129) {

							jQuery(this).find(".idLink").click("off");
							jQuery(this).find(".btn_excluir_parcela_post").hide();
						}
					}
				});
				jQuery("#forma_pagamento").on("change", function() {
					if (jQuery("#forma_pagamento").val() == 1) {
						document.getElementById("cobrar_parcelas").style.display = "";
						document.getElementById("boleto_asas").style.display = "none";
						jQuery("#cobrar_envio_boleto").prop("checked", false);
					} else {
						document.getElementById("boleto_asas").style.display = "";
						document.getElementById("cobrar_parcelas").style.display = "none";
						jQuery("#cobrar_envio_boleto").prop("checked", true);
					}
				})

				jQuery("#frClose").click(function() {
					window.location.reload();
				});

				jQuery(".btn_excluir_parcela_post").click(function(e) {
					var idTransacao = jQuery(this).attr("val");
					jQuery(".idTrasacao").html(idTransacao);

					e.preventDefault();
					e.stopPropagation();

					if (jQuery("#includes_user_id").val() == 1004 || jQuery("#includes_user_id").val() == 129 || jQuery("#includes_user_id").val() == 4177 || jQuery("#includes_user_id").val() == 42) {
						jQuery(".Delete").show();
					}
				});


				jQuery(".cancelarDel").click(function(e) {
					e.preventDefault();
					e.stopPropagation();

					jQuery(".Delete").hide();

				});


				jQuery(".DeleteButton").click(function(e) {
					e.stopPropagation();
					e.preventDefault();

					if (jQuery("#includes_user_id").val() == 1004 || jQuery("#includes_user_id").val() == 129 || jQuery("#includes_user_id").val() == 42) {

						var id = jQuery(".idTrasacao").text();

						var dados = {
							id: id
						}

						jQuery.ajax({

							type: "POST",
							url: "/sistema/sistema/recebimentos/delete_transacao.php",
							data: dados,
							success: function(retorno) {
								location.reload();

							}
						});

					} else {
						alert("Usuario sem permissao para deletar transacao")
					}


				});


				jQuery(".idLink").click(function() {

					if (jQuery("#includes_user_id").val() == 1004 || jQuery("#includes_user_id").val() == 129 || jQuery("#includes_user_id").val() == 4177 || jQuery("#includes_user_id").val() == 42) {

						var id = jQuery(this).text();
						var vendas_proposta = jQuery("#vendas_id").val();

						var Dados = {
							id: id,
							vendas_proposta: vendas_proposta
						};

						jQuery.ajax({

							type: "POST",
							url: "/sistema/sistema/recebimentos/edit_trasacao_post.php",
							data: Dados,
							success: function(retorno) {
								jQuery("#modalEdit").html(retorno)
							}
						});

						jQuery("#modalEdit").show();
					} else {
						alert("usuario nao permitido para edição")
					}

				})

			});






			function verificaEnderecoGravacao() {

				if (document.getElementsByName('vendas_status')[0] && document.getElementsByName('vendas_gravacao')[0]) {
					var status = document.getElementsByName('vendas_status')[0].value;
					/*
					STATUS LIBERADOS
					1	Aguardando Auditor
					2	Em Auditoria
						9	PAGO
					13	Reprovada na Auditoria
					19	Cancelado
					26	Pendente de Auditoria
					28	Dados bancários não conferem
					29	Reprovada
						45	VENDA RETIDA
						58	ENVIADO PARA RETENÇÃO
					62	Aguardando recuperação
					64	Não Recuperada
					65	Em Recuperação
						66	Pendente de Gravação
					68	Pendente de Seguradora
					*/
					if (status != 1 && status != 2 && status != 9 && status != 13 && status != 19 && status != 26 && status != 28 && status != 29 && status != 45 && status != 58 && status != 62 && status != 64 && status != 65 && status != 66 && status != 68) {
						if (document.getElementsByName('vendas_gravacao')[0].value.length < 10) {
							if (document.getElementById('opvenda').classList.contains("openpet2") == false) {
								document.getElementById('opvenda').click();
							}
							document.getElementById('msg-addendereco').innerHTML = "*É necessário preencher corretamente este campo para salvar.";
							document.getElementById('botao-salvar').style.display = "none";
						} else {
							document.getElementById('msg-addendereco').innerHTML = "";
							document.getElementById('botao-salvar').style.display = "unset";
						}
					} else {
						document.getElementById('msg-addendereco').innerHTML = "";
						document.getElementById('botao-salvar').style.display = "unset";
					}
				}
			}

			function verificaConta() {
				var vendas_debito_banco = document.getElementsByName("vendas_debito_banco")[0].value;
				var vendas_debito_ag = document.getElementsByName("vendas_debito_ag")[0].value;
				var vendas_debito_cc = document.getElementsByName("vendas_debito_cc")[0].value;

				document.getElementById("verifica_conta").innerHTML = "<img src='sistema/imagens/loading.gif'>";

				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("verifica_conta").innerHTML = xmlhttp.responseText;
						check_selects();
					}
				};
				xmlhttp.open("GET", "sistema/integracao/mbm/verifica_conta.php?vendas_debito_banco=" + vendas_debito_banco + "&vendas_debito_ag=" + vendas_debito_ag + "&vendas_debito_cc=" + vendas_debito_cc, true);
				xmlhttp.send();
			}




			function cobrarParcelas() {
				console.log("cobrarParcelas");
				calculaTotal();
				if (document.getElementById("total_cobrar").value > 0) {
					debugger
					//document.getElementById("cobrar_parcelas").style.display = "";
					document.getElementById("cobrar_parcelas_dropdown").style.display = "";

					//document.getElementById("boleto_asas").style.display = "";

				} else {
					//document.getElementById("cobrar_parcelas").style.display = "none";
					//document.getElementById("boleto_asas").style.display = "none";
					document.getElementById("cobrar_parcelas_dropdown").style.display = "none";
					document.getElementById("boleto_asas").style.display = "none";
					document.getElementById("cobrar_parcelas").style.display = "none";
				}

			}

			function calculaTotal() {
				var total = 0;
				document.querySelectorAll("input:checked").forEach((e) => {
					total += Number(e.value);
				});
				document.getElementById("total_cobrar").value = total;
				total_rs = float2moeda(total);
				document.getElementById('total_cobrar_rs').innerHTML = 'R$ ' + total_rs;
			}
		</script>
	<?php endif; ?>

	<?php if (($row["vendas_status"] != 1) && ($_GET["bloquear"] == "bloquear")) : ?>
		<meta http-equiv="Refresh" content="2; url=index.php?option=com_k2&view=item&layout=item&id=101&Itemid=477">
		<div align="center"></br>
			VENDA JÁ EM AUDITORIA! </br>
			Auditor: <?php echo $row['vendas_user']; ?>.</br></br>
			Retornando a Fila...
		</div>
	<?php else : ?>

		<?php
		if ($_GET["bloquear"] == "bloquear") {
			$row["vendas_status"] = 2;
			$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_status='2', vendas_user='" . $username . "' WHERE vendas_id='$vendas_id' ") or die(mysql_error());
			echo "Venda colocada em auditoria Sucesso!<br/>";

			$vendas_obs = "Venda colocada em auditoria.";

			$sql = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, 
	vendas_id, 
	registro_usuario, 
	registro_obs, 
	registro_status, 
	registro_data, 
	registro_contrato_fisico, 
	registro_cobranca, 
	registro_retencao) 
	VALUES (NULL, 
	'" . $vendas_id . "',
	'" . $username . "',
	'" . $vendas_obs . "',
	'2',
	NOW(),
	'0',
	'1',
	'1');";

			if (mysql_query($sql)) {
				echo "Histórico Registrado com Sucesso. </br>";
			} else {
				die('Error: ' . mysql_error());
			}
		}
		include("sistema/utf8.php");
		$result_client = mysql_query("SELECT cliente_nome, cliente_sexo, cliente_nascimento, cliente_pagamento, cliente_rg, cliente_beneficio, cliente_cargo_cod, cliente_endereco, cliente_bairro, cliente_cidade, cliente_cep, cliente_uf, cliente_telefone, cliente_celular, cliente_empregador, cliente_email FROM sys_inss_clientes WHERE cliente_cpf = '" . $row['cliente_cpf'] . "';")
			or die(mysql_error());
		$row_client = mysql_fetch_array($result_client);


		$result_Asaaz = mysql_query("SELECT customer FROM sys_vendas_transacoes_boleto WHERE cliente_cpf = " . $row['cliente_cpf'] . ";")
			or die(mysql_error());
		$row_Asaaz = mysql_fetch_array($result_Asaaz);

		$idCustumersAsaaz = $row_Asaaz["customer"];


		$link_cliente = "index.php?option=com_k2&view=item&layout=item&id=62&Itemid=272&acao=edita_cliente_inss&cpf=" . $row['cliente_cpf'];
		if (!$row_client["cliente_nome"]) {
			$result_client = mysql_query("SELECT clients_nm AS cliente_nome, clients_birth AS cliente_nascimento, clients_rg AS cliente_rg, clients_street_complet AS cliente_endereco, clients_district AS cliente_bairro, clients_city AS cliente_cidade, clients_postalcode AS cliente_cep, clients_state AS cliente_uf, clients_contact_phone1 AS cliente_telefone, clients_contact_phone2 AS cliente_celular FROM sys_clients WHERE clients_cpf = '" . $row['cliente_cpf'] . "';")
				or die(mysql_error());
			$row_client = mysql_fetch_array($result_client);
			$link_cliente = "index.php?option=com_k2&view=item&layout=item&id=62&Itemid=272&acao=edita_cliente&cpf=" . $row['cliente_cpf'];
		}

		if (!$row_client['cliente_nome']) {
			$clients_cpf = $row['cliente_cpf'];
			include("sistema/cliente/espelha_confere.php");
			if ($row_espelha_confere["total"]) {

				include("sistema/connect_db02.php");
				include("sistema/utf8.php");

				include("sistema/cliente/espelha_existente.php");

				include("sistema/connect.php");
				include("sistema/utf8.php");

				include("sistema/cliente/espelha_atualiza.php");
			} else {

				include("sistema/connect_db02.php");
				include("sistema/utf8.php");

				include("sistema/cliente/espelha.php");

				include("sistema/connect.php");
				include("sistema/utf8.php");

				include("sistema/cliente/espelha_insere.php");
			}
		}

		$result_user = mysql_query("SELECT username, name, situacao, nivel FROM jos_users WHERE id = '" . $row['vendas_consultor'] . "';")
			or die(mysql_error());
		$row_user = mysql_fetch_array($result_user);

		$result_grupo = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $row['vendas_consultor'] . ";")
			or die(mysql_error());

		$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $userid . ";")
			or die(mysql_error());
		while ($row_grupo_user = mysql_fetch_array($result_grupo_user)) {
			if (($row_grupo_user['id'] == '10') || ($row_grupo_user['id'] == '26')) {
				$administracao = 1;
			}
			if ($row_grupo_usser['id'] == '18') {
				$diretoria = 1;
			}
			if ($row_grupo_user['id'] == '19') {
				$financeiro = 1;
			}
			if ($row_grupo_user['id'] == '21') {
				$franquiado = 1;
			}
			if ($row_grupo_user['id'] == '36') {
				$sup_operacional = 1;
			}
			if ($row_grupo_user['id'] == '51') {
				$operacional_seguros = 1;
			}
			if ($row_grupo_user['id'] == '59') {
				$auditores = 1;
			}
			if ($row_grupo_user['id'] == '55') {
				$recuperacao_seguros = 1;
			}
			if ($row_grupo_user['id'] == '32') {
				$vendedores_seguros = 1;
			}
			if ($row_grupo_user['id'] == '37') {
				$supervisor_equipe_vendas = 1;
			}
			if ($row_grupo_user['id'] == '48') {
				$supervisor_unidade = 1;
			}
			if ($row_grupo_user['id'] == '76') {
				$gerente_plataformas = 1;
			}
			if ($row_grupo_user['id'] == '78') {
				$homologacao = 1;
			}
			if ($row_grupo_user['id'] == '95') {
				$auditores_seguros = 1;
			}
			if ($row_grupo_user['id'] == '100') {
				$ouvidoria_seguros = 1;
			}
			if ($row_grupo_user['id'] == '1044') {
				$edita_transacao = 1;
			}
			if ($row_grupo_user['id'] == '1045') {
				$retencao = 1;
			}
			if ($row_grupo_user['id'] == '103') {
				$cobranca = 1;
			}
		}
		if ($gerente_comercial_seguros) {
			$administracao = 1;
		}
		if ($userid == 1055) {
			$auditores_seguros;
		}

		$result_anexos = mysql_query("SELECT * FROM sys_vendas_anexos_seg WHERE vendas_id = " . $row['vendas_id'] . ";")
			or die(mysql_error());

		$result_registros = mysql_query("SELECT * FROM sys_vendas_registros_seg INNER JOIN sys_vendas_status_seg ON registro_status = status_id WHERE vendas_id = '" . $vendas_id . "' ORDER BY registro_id DESC;")
			or die(mysql_error());

		$QUERYINTEGRADOR = "SELECT * FROM sys_vendas_registros_seg INNER JOIN sys_vendas_status_seg ON registro_status = status_id WHERE vendas_id = '" . $vendas_id . "' ORDER BY registro_id DESC;";
		//ECHO $QUERYINTEGRADOR;

		if (($row["vendas_proposta"]) && ($row["vendas_proposta"] > 0)) {

			$result_transacoes = mysql_query("SELECT sys_vendas_transacoes_seg.transacao_id, 
													transacao_valor, 
													transacao_data_importacao, 
													transacao_mes, transacao_parcela, 
													transacao_recebido, 
													transacao_motivo, 
													transacao_usuario, 
													transacao_data, 
													dateCreated, 
													date_format(str_to_date(dateCreated, '%Y-%m-%d'), '%d/%m/%Y') AS data_boleto 
													FROM sys_vendas_transacoes_seg 
													LEFT JOIN sys_vendas_transacoes_boleto ON sys_vendas_transacoes_seg.id_boleto = sys_vendas_transacoes_boleto.transacao_id 
													WHERE transacao_proposta = '" . $row['vendas_proposta'] . "' OR transacao_proposta = '" . $row['vendas_id'] . "' ORDER BY transacao_parcela DESC;")
				or die(mysql_error());
			$teste = "SELECT transacao_id, transacao_valor, transacao_data_importacao, transacao_mes, transacao_parcela, transacao_recebido, transacao_motivo, transacao_usuario, transacao_data FROM sys_vendas_transacoes_seg WHERE transacao_proposta = '" . $row['vendas_proposta'] . "' OR transacao_proposta = '" . $row['vendas_id'] . "' ORDER BY transacao_parcela DESC;";
			//echo $teste;
		}
		echo "<div style='display:none' id='teste_repeticao_parcelas'>SELECT sys_vendas_transacoes_seg.transacao_id, 
transacao_valor, 
transacao_data_importacao, 
transacao_mes, transacao_parcela, 
transacao_recebido, 
transacao_motivo, 
transacao_usuario, 
transacao_data, 
dateCreated, 
date_format(str_to_date(dateCreated, '%Y-%m-%d'), '%d/%m/%Y') AS data_boleto 
FROM sys_vendas_transacoes_seg 
LEFT JOIN sys_vendas_transacoes_boleto ON sys_vendas_transacoes_seg.id_boleto = sys_vendas_transacoes_boleto.transacao_id 
WHERE transacao_proposta = '" . $row['vendas_proposta'] . "' OR transacao_proposta = '" . $row['vendas_id'] . "' ORDER BY transacao_parcela DESC;</div>";

		$queryTeste = "SELECT transacao_id, transacao_valor, transacao_data_importacao, transacao_mes, transacao_parcela, transacao_recebido, transacao_motivo, transacao_usuario FROM sys_vendas_transacoes_seg WHERE transacao_proposta = '" . $row['vendas_proposta'] . "' OR transacao_proposta = '" . $row['vendas_id'] . "' ORDER BY transacao_parcela DESC;";
		//ECHO $queryTeste;
		$result_transacoes_tef_dia = mysql_query("SELECT COUNT(transacao_id) AS total FROM sys_vendas_transacoes_tef WHERE transacao_cliente_cpf = '" . $row['cliente_cpf'] . "' AND transacao_data > '" . date('Y-m-d') . " 00:00:00';")
			or die(mysql_error());
		$row_tef_dia = mysql_fetch_array($result_transacoes_tef_dia);
		//echo "tef: ".$row_tef_dia['total']."<br>";

		$result_status_nm = mysql_query("SELECT status_nm, status_liberado, status_img, status_pai FROM sys_vendas_status_seg WHERE status_id = " . $row['vendas_status'] . ";")
			or die(mysql_error());
		$row_status_nm = mysql_fetch_array($result_status_nm);
		$vendas_status_nm = $row_status_nm["status_nm"];
		$vendas_status_img = $row_status_nm["status_img"];

		if ((($administracao == 0) && ($row_status_nm["status_liberado"] == 0)) || ($row['vendas_status'] == '85')) {
			$edicao = 0;
		} else {
			$edicao = 1;
		}
		if (($cobranca_seguros) || ($retencao)) {
			$edicao = 1;
		}
		?>

		<?php

		// debug
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);

		$host = "10.100.0.22";
		$usernamebd = "root";
		$password = "Theredpil2001";
		$database = "sistema";

		// Substitua '$conn' pela sua conexão de banco de dados real
		$conn = new mysqli($host, $usernamebd, $password, $database);

		// define utf-8
		$conn->set_charset("utf8");

		// Checar conexão
		if ($conn->connect_error) {
			die("Falha na conexão: " . $conn->connect_error);
		}

		// Sanitização da entrada para evitar injeção de SQL
		$vendas_id = $conn->real_escape_string($vendas_id);

		// Montando a consulta SQL
		$sql_query_kit = "SELECT * FROM sys_logs_disparos_kit WHERE venda_id = $vendas_id ORDER BY id DESC";

		// Executando a consulta
		$result_kit = $conn->query($sql_query_kit);

		if (!$result_kit) {
			die("Erro na consulta: " . $conn->error);
		}

		// Armazenando os resultados em um array
		$kits = [];
		while ($row_kit = $result_kit->fetch_assoc()) {
			$kits[] = $row_kit;
		}

		

		// Pegando os dados de acesso do kit
		$sql_query_kit_acesso = "SELECT * FROM sys_logs_acessos_kit WHERE vendas_id = $vendas_id ORDER BY id DESC";

		// Executando a consulta
		$result_kit_acesso = $conn->query($sql_query_kit_acesso);

		if (!$result_kit_acesso) {
			die("Erro na consulta: " . $conn->error);
		}

		// Armazenando os resultados em um array
		$kits_acesso = [];
		while ($row_kit_acesso = $result_kit_acesso->fetch_assoc()) {
			$kits_acesso[] = $row_kit_acesso;
		}		

		

		$sql_libera_cobranca = "SELECT libera_cobranca FROM sys_libera_cobranca WHERE venda_id = $vendas_id ORDER BY id DESC LIMIT 1";

		$result_libera_cobranca = mysql_query($sql_libera_cobranca);

		$row_libera_cobranca = mysql_fetch_array($result_libera_cobranca);

		$libera_cobranca = $row_libera_cobranca['libera_cobranca'];

		// Fechando conexão
		$conn->close();

		?>


		<?php if ((($userid == $row["vendas_consultor"]) && (($row['vendas_status'] != 13) && ($row['vendas_status'] != 19) && ($row['vendas_status'] != 26) && ($row['vendas_status'] != 28) && ($row['vendas_status'] != 58) && ($row['vendas_status'] != 66) || ($row['apolice_tipo'] == 2))) ||
			($administracao == 1) || ($auditores == 1) || ($recuperacao_seguros == 1) || ($supervisor_equipe_vendas == 1) || ($supervisor_unidade == 1) || ($gerente_plataformas == 1)
		) : ?>


			<style type="text/css">
				/*
.style1 {
	color: #CCCCCC;
	font-weight: bold;
}
.style2 {color: #CCCCCC}
*/
			</style>

			<form id="form_principal" action="index.php" method="GET">
				<input name="option" type="hidden" id="option" value="com_k2" />
				<input name="view" type="hidden" id="view" value="item" />
				<input name="id" type="hidden" id="id" value="<?php echo $_GET["id"]; ?>" />
				<input name="Itemid" type="hidden" id="Itemid" value="<?php echo $_GET["Itemid"]; ?>" />
				<input name="username" type="hidden" id="username" value="<?php echo $username; ?>" />
				<input name="vendas_id" type="hidden" id="vendas_id" value="<?php echo $row["vendas_id"]; ?>" />
				<input name="user_situacao" type="hidden" id="user_situacao" value="<?php echo $row_user["situacao"]; ?>" />
				<input name="user_nivel" type="hidden" id="user_nivel" value="<?php echo $row_user["nivel"]; ?>" />
				<input name="vendas_status_old" type="hidden" id="vendas_status_old" value="<?php echo $row['vendas_status']; ?>" />
				<input name="vendas_orgao" type="hidden" id="vendas_orgao" value="<?php echo $row['vendas_orgao']; ?>" />
				<input name="transacao_id" type="hidden" id="transacao_id" value="" />
				<input name="id_custumores_asaaz" type="hidden" id="id_custumores_asaaz" value="<?php echo $idCustumersAsaaz ?>" />
				<div align="center">


					<div id="bloco_container">
						<div class="bloco_bloco">
							<?php include("sistema/vendas/blocos_seguros/ficha_cliente.php"); ?>
						</div>
					</div>
					<div id="bloco_container">
						<div class="bloco_bloco">
							<?php include("sistema/vendas/blocos_seguros/dados_proposta.php"); ?>
						</div>
					</div>

					<?php if ($row['apolice_dep_ben']) : ?>
						<div id="bloco_container">
							<div class="bloco_bloco">
								<?php include("sistema/vendas/blocos_seguros/beneficiarios.php"); ?>
							</div>
						</div>
					<?php endif; ?>

					<div id="bloco_container">
						<div class="bloco_bloco">
							<?php include("sistema/vendas/blocos_seguros/dados_financeiro.php"); ?>
						</div>
					</div>

					<div id="bloco_container">
						<div class="bloco_bloco">
							<?php include("sistema/vendas/blocos_seguros/operacional_da_venda.php"); ?>
						</div>

					</div>

					<?php if ($super_user || $administracao || $ouvidoria_seguros || $operacional_seguros) : ?>
						<div id="bloco_container">
							<div class="bloco_bloco">
								<?php include("sistema/vendas/blocos_seguros/controle_de_transacoes.php"); ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ($super_user || $administracao) : ?>
						<div id="bloco_container">
							<div class="bloco_bloco">
								<?php include("sistema/vendas/blocos_seguros/historico_transacoes.php"); ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ($sup_operacional == 1 || $user_id == 3236 || $retencao == 1 || $cobranca == 1 || $operacional_seguros) { ?>
						<div id="bloco_container">
							<div class="bloco_bloco" id="controle_parcelas">

								
								<?php include("sistema/vendas/blocos_seguros/controle_de_parcelas.php"); ?>
							</div>
						</div>
					<?php } ?>

					<div id="bloco_container">
						<div class="bloco_bloco">
							<?php include("sistema/vendas/blocos_seguros/anexos.php"); ?>
						</div>
					</div>

					<div id="bloco_container">
						<div class="bloco_bloco">
							<?php include("sistema/vendas/blocos_seguros/historico_envio_kit.php"); ?>
						</div>
					</div>

					<div id="bloco_container">
						<div class="bloco_bloco">
							<?php include("sistema/vendas/blocos_seguros/historico_venda.php"); ?>
						</div>
					</div>
					<div id="bloco_container">
						<div class="bloco_bloco">
							<?php include("sistema/vendas/blocos_seguros/outras_informacoes.php"); ?>
						</div>
					</div>
				</div>

				<div id="bloco_container">
					<div class="bloco_bloco">
						<div class="linha">
							<?php if ($edicao == 1 || $auditores_seguros = 1) : ?>
								<span id="botao-salvar">
									<button class="button validate png" name="salvar" type="submit" value="salvar" onclick='salvando_modal()'>Salvar Venda</button>
									<button class="button validate png" name="salvar" type="submit" value="salvar_fechar" onclick='salvando_modal()'>Salvar Venda & Fechar</button>
								</span>
							<?php endif; ?>
							<a href="<?php echo $link_consulta; ?>"><button class="button validate png" type="button">Fechar Venda</button></a>
							<input name="ordemi" type="hidden" id="ordem" value="ACESSOS" />
							<input name="acao" type="hidden" id="acao" value="atualiza_venda_seguro" />
						</div>
					</div>
				</div>
			<?php else : ?>
				<div align="center">
					VOCÊ NÃO POSSUI ACESSO A ESTA PÁGINA! </br>
					Entre em contato com a sua supervisão, para solicitar este acesso.
				</div>
			<?php endif; ?>
			</form>

		<?php endif; ?>

	<?php
endif;
include("sistema/utf8.php");
	?>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			
			jQuery(".transacaoData").each(function() {
				var dataBr = jQuery(this).text();

				jQuery(this).html(dataBr.split('-').reverse().join('/'));

			})


			jQuery('#parcelas > tbody  > tr').each(function(index, trItem) {
				//console.log(trItem)

				if (jQuery(this).find(".transacao_usuario").html() == "integrador.automatico") {

					if (jQuery("#includes_user_id").val() != 129) {

						jQuery(this).find(".idLink").click("off");
						jQuery(this).find(".btn_excluir_parcela_post").hide();
					}
				}


			});

			jQuery("#frClose").click(function() {


				window.location.reload();
			});

			jQuery(".btn_excluir_parcela_post").click(function(e) {
				var idTransacao = jQuery(this).attr("val");
				jQuery(".idTrasacao").html(idTransacao);

				e.preventDefault();
				e.stopPropagation();

				if (jQuery("#includes_user_id").val() == 1004 || jQuery("#includes_user_id").val() == 129 || jQuery("#includes_user_id").val() == 42) {
					jQuery(".Delete").show();
				}
			});


			jQuery(".cancelarDel").click(function(e) {
				e.preventDefault();
				e.stopPropagation();

				jQuery(".Delete").hide();

			});


			jQuery(".DeleteButton").click(function(e) {
				e.stopPropagation();
				e.preventDefault();

				if (jQuery("#includes_user_id").val() == 1004 || jQuery("#includes_user_id").val() == 129 || jQuery("#includes_user_id").val() == 4177 || jQuery("#includes_user_id").val() == 42) {

					var id = jQuery(".idTrasacao").text();

					var dados = {
						id: id
					}

					jQuery.ajax({

						type: "POST",
						url: "/sistema/sistema/recebimentos/delete_transacao.php",
						data: dados,
						success: function(retorno) {
							location.reload();

						}
					});

				} else {
					alert("Usuario sem permissao para deletar transacao")
				}


			});

			jQuery("#forma_pagamento").on("change", function() {
				console.log("Entrou na função")

				if (jQuery("#forma_pagamento").val() == 1) {
					console.log("Forma de pagamento cartão de credito")
					document.getElementById("cobrar_parcelas").style.display = "";
					document.getElementById("boleto_asas").style.display = "none";
				} else {
					console.log("Forma de pagamento boleto")
					document.getElementById("boleto_asas").style.display = "";
					document.getElementById("cobrar_parcelas").style.display = "none";
				}
			})

			jQuery(".idLink").click(function() {
				if (jQuery("#includes_user_id").val() == 1004 || jQuery("#includes_user_id").val() == 129 || jQuery("#includes_user_id").val() == 4177 || jQuery("#includes_user_id").val() == 42) {

					var id = jQuery(this).text();
					var vendas_proposta = jQuery("#vendas_id").val();

					var Dados = {
						id: id,
						vendas_proposta: vendas_proposta
					};

					jQuery.ajax({

						type: "POST",
						url: "/sistema/sistema/recebimentos/edit_trasacao_post.php",
						data: Dados,
						success: function(retorno) {
							jQuery("#modalEdit").html(retorno)



						}
					});

					jQuery("#modalEdit").show();


				} else {
					alert("usuario nao permitido para edição")
				}

			})







			// jQuery.ajax({

			// 			url: '/sistema/sistema/vendas/blocos_seguros/controle_de_parcelas_post.php',
			// 			type: 'POST',
			// 			async: true,
			// 			data:array, 
			// 			success: function(response) {
			// 				//jQuery("#controle_parcelas").append(response);

			// 			}
			// })
		})



		var userid = "<?php echo $userid; ?>";
		var vendas_id = "<?php echo $vendas_id; ?>";
		var cpfCnpj = "<?php echo $row["cliente_cpf"]; ?>";
		var name = '<?php echo $row_client["cliente_nome"]; ?>';
		var email = '<?php echo $row_client["cliente_email"]; ?>';
		//var email = 'financeiro@apobem.com.br';
		var phone = jQuery("#vendas_telefone").val();
		var mobilePhone = jQuery("#vendas_telefone").val();
		var postalCode = '<?php echo $row_client["cliente_cep"]; ?>';
		var address = '<?php echo $row_client["cliente_endereco"]; ?>';
		var addressNumber = '<?php echo $row_client["cliente_endereco"]; ?>';
		var complement = '<?php echo $row_client["cliente_endereco"]; ?>';
		var province = '<?php echo $row_client["cliente_bairro"]; ?>';
		var externalReference = '<?php echo $row["cliente_cpf"]; ?>';
		var notificationDisabled = false;
		var additionalEmails = '<?php echo $row_client["cliente_email"]; ?>';
		var municipalInscription = "";
		var stateInscription = "";
		var observations = "";
		var d = new Date();

		function cobrarParcelas() {
			calculaTotal();
			if (document.getElementById("total_cobrar").value > 0) {
				document.getElementById("cobrar_parcelas").style.display = "";
				document.getElementById("cobrar_parcelas_dropdown").style.display = "";

				//document.getElementById("boleto_asas").style.display = "";

			} else {
				//document.getElementById("cobrar_parcelas").style.display = "none";
				//document.getElementById("boleto_asas").style.display = "none";
				document.getElementById("cobrar_parcelas_dropdown").style.display = "none";
				document.getElementById("boleto_asas").style.display = "none";
				document.getElementById("cobrar_parcelas").style.display = "none";
			}


		}


		function calculaTotal() {
			var total = 0;
			document.querySelectorAll("input:checked").forEach((e) => {
				total += Number(e.value);
			});
			document.getElementById("total_cobrar").value = total;
			total_rs = float2moeda(total);
			document.getElementById('total_cobrar_rs').innerHTML = 'R$ ' + total_rs;
		}



		jQuery.ajax({
			url: 'https://sistema.apobem.com.br/integracao/asaas/busca_cliente.php',
			type: 'POST',
			async: true,
			data: {
				cpfCnpj: cpfCnpj,

			},
			success: function(response) {
				var jsonReponse = jQuery.parseJSON(response);
				var converse = JSON.parse(jsonReponse);
				console.log(converse.totalCount);
				if (converse.totalCount > 0) {
					jQuery("#emailTitulo").hide();
					jQuery("#cliente_email_editar_div").hide();
					jQuery("#tituloFone").hide();
					jQuery("#cliente_fone_editar").hide();

				} else {
					jQuery("#emailTitulo").show();
					jQuery("#cliente_email_editar_div").show();
					jQuery("#tituloFone").show();
					jQuery("#cliente_fone_editar").show();

				}
			}

		});


		jQuery("#cliente_email_editar").val(email);
		jQuery("#cliente_fone_editar").val(phone);



		var username = jQuery("#includes_username").val();
		var dueDate = (d.getFullYear()) + '-' + (d.getMonth() + 1) + '-' + (d.getDate() + 5);

		var dueDate = jQuery(".dataVencimentoBoleto").val();

		var id_custumores_asaaz = $("#id_custumores_asaaz").val();
		// var value = 

		jQuery(".postAsaas").click(function() {
			var parcelas_a_pagar = jQuery("#parcelas_a_pagar").val();

			var email = jQuery("#cliente_email_editar").val();
			var phone = jQuery("#cliente_fone_editar").val();
			var mobilePhone = jQuery("#cliente_fone_editar").val();

			var dueDate = jQuery(".dataVencimentoBoleto").val();

			var dueDateInput = new Date(jQuery(".dataVencimentoBoleto").val());

			var d = new Date();



			if (dueDateInput > d) {
				
				jQuery.ajax({
					url: 'https://sistema.apobem.com.br/integracao/asaas/busca_cliente.php',
					type: 'POST',
					async: true,
					data: {
						cpfCnpj: cpfCnpj,


					},
					success: function(response) {
						var jsonReponse = jQuery.parseJSON(response);
						var converse = JSON.parse(jsonReponse);
						console.log(converse.totalCount);

						if (converse.totalCount > 0) {
							alert("tem cpf cadastrado");
							//var id = Object.keys(converse);
							var lista = converse.data;
							lista.forEach(function(item) {
								id_custumores_asaaz = item.id;
							})



							alert(id_custumores_asaaz);

							jQuery.ajax({
								url: '/sistema/sistema/vendas/primeiro_insert_transacao_log_boleto.php',
								type: 'POST',
								data: {
									cpfCnpj: cpfCnpj,
									vendas_id: vendas_id,
									userid: userid,
									parcelas_a_pagar: parcelas_a_pagar,
									jsonReponse: jsonReponse
								},
								success: function(resposta) {
									var idTransacao = resposta;
									
									//CriaUsuario(idTransacao);
									CriaBoleto(id_custumores_asaaz, idTransacao, parcelas_a_pagar);


								}

							});


						} else {

							jQuery.ajax({
								url: '/sistema/sistema/vendas/primeiro_insert_transacao_log_boleto.php',
								type: 'POST',
								data: {
									cpfCnpj: cpfCnpj,
									vendas_id: vendas_id,
									userid: userid,
									parcelas_a_pagar: parcelas_a_pagar,
									jsonReponse: jsonReponse
								},
								success: function(resposta) {
									var idTransacao = resposta;
									
									CriaUsuario(idTransacao);


								}

							});

						}

					}
				});
			} else {
				alert("data invalida");



			}

		});



		function listaProfissoes() {

			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					if (document.getElementById("cliente_cargo_cod")) {
						document.getElementById("cliente_cargo_cod").innerHTML = xmlhttp.responseText;
					}
					//check_selects();
				}
			};
			xmlhttp.open("GET", "sistema/integracao/mbm/lista_profissoes.php?cliente_cargo_cod=<?php echo $row_client['cliente_cargo_cod']; ?>", true);
			xmlhttp.send();
		}

		function carregaControleParcelas(vendas_id) {
			var container = document.getElementById("lista_parcelas");
			container.innerHTML = '<img src="sistema/imagens/loading.gif">';

			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4) {
					if (this.status == 200) {
						container.innerHTML = this.responseText;
					}
				}
			};
			xhttp.open("GET", "sistema/vendas/blocos_seguros/lista_parcelas_ajax.php?vendas_id=" + vendas_id + "&financeiro=<?php echo $financeiro . $diretoria; ?>", true);
			xhttp.send();


		}

		function CriaBoleto(idAsaas, idTransacao) {
			if (jQuery("#cobrar_envio_boleto").is(":checked")) {
				var value = parseFloat(jQuery("#total_cobrar").val()) + 3.50;
			} else {
				var value = parseFloat(jQuery("#total_cobrar").val());
			}
			var description = "Cobrança referente ao seguro";
			var externalReference = vendas_id;
			alert(value);

			jQuery.ajax({
				url: 'https://sistema.apobem.com.br/integracao/asaas/novo_boleto.php',
				type: 'POST',
				data: {
					idAsaas: idAsaas,
					dueDate: jQuery(".dataVencimentoBoleto").val(),
					value: value,
					externalReference: externalReference,
				},
				success: function(resposta) {
					var jsonReponse = jQuery.parseJSON(resposta);
					var converse = JSON.parse(jsonReponse);
					alert(converse);

					var id_boleto = converse.id;
					var invoiceUrl = converse.invoiceUrl;
					var bankSlipUrl = converse.bankSlipUrl;
					var invoiceNumber = converse.invoiceNumber;

					jQuery.ajax({
						url: '/sistema/sistema/vendas/terceiro_update_transacao_log_boleto_cadastro_asaas_EMISSAO.php',
						type: 'POST',
						data: {
							id_boleto: id_boleto,
							invoiceUrl: invoiceUrl,
							bankSlipUrl: bankSlipUrl,
							invoiceNumber: invoiceNumber,
							idAsaas: idAsaas,
							value: value,
							jsonReponse: jsonReponse,
							vendas_id: vendas_id,
							userid: userid,
							cpfCnpj: cpfCnpj,
							idTransacao: idTransacao,
							username: username,
							dueDate: jQuery(".dataVencimentoBoleto").val()

						},
						success: function(resposta) {
							alert("criado boleto e atualizado transacao com os links dos boleto");
							//jQuery("#linkBoleto").html(invoiceUrl);
							jQuery(".link").html(invoiceUrl);
							jQuery(".link").attr("href", invoiceUrl);
							jQuery("#linkBoleto").show();
							jQuery("#linkBoleto2").show();

						}
					});

				}

			})

		}

		function CriaUsuario(idTransacao) {

			var email = jQuery("#cliente_email_editar").val();
			var phone = jQuery("#cliente_fone_editar").val();
			var mobilePhone = jQuery("#cliente_fone_editar").val();

			jQuery.ajax({
				url: 'https://sistema.apobem.com.br/integracao/asaas/novo_cliente.php',
				type: 'POST',
				data: {
					cpfCnpj: cpfCnpj,
					name: name,
					email: email,
					phone: phone,
					mobilePhone: mobilePhone,
					postalCode: postalCode,
					address: address,
					addressNumber: addressNumber,
					complement: complement,
					province: province,
					externalReference: externalReference,
					notificationDisabled: notificationDisabled,
					additionalEmails: additionalEmails,
					municipalInscription: municipalInscription,
					stateInscription: stateInscription,
					observations: observations

				},
				success: function(response) {


					var jsonReponse = jQuery.parseJSON(response);
					var converse = JSON.parse(jsonReponse);
					var idAsaas = converse.id;

					

					jQuery.ajax({
						url: '/sistema/sistema/vendas/segundo_update_transacao_log_boleto_cadastro_asaas.php',
						type: 'POST',
						data: {
							idAsaas: idAsaas,
							jsonReponse: jsonReponse,
							idTransacao: idTransacao,
							vendas_id: vendas_id,
							userid: userid,
							cpfCnpj: cpfCnpj
						},
						success: function(response) {
							

							CriaBoleto(idAsaas, idTransacao);


						}


					});

				}

			});
		}

		jQuery("#form_principal").on("submit", function() {
			console.log("submit form_principal");
		});
	</script>
<script>
    jQuery(document).ready(function() {	
		

        var venda_id = <?php echo $row['vendas_id']; ?>;
		var userid = <?php echo $userid; ?>;

        // Carregar o estado inicial do switch
        jQuery.ajax({
            url: '/sistema/sistema/vendas/blocos_seguros/get_switch_state.php',
            type: 'POST',
            data: { venda_id: venda_id},
            dataType: 'json',
            success: function(response) {
				console.log(response);
                if (response.libera_cobranca == '1') {
                    jQuery('#toggleSwitch').prop('checked', true);
					console.log('checked');
                } else {
                    jQuery('#toggleSwitch').prop('checked', false);
					console.log('unchecked');
                }
            }
        });

        // Atualizar o estado do switch no banco de dados quando mudar
        jQuery('.switch input').on('change', function() {
            var libera_cobranca = jQuery(this).is(':checked') ? 1 : 0;
            jQuery.ajax({
                url: '/sistema/sistema/vendas/blocos_seguros/save_switch_state.php',
                type: 'POST',
                data: {
                    venda_id: venda_id,
                    libera_cobranca: libera_cobranca,
					userid: userid
                },
                success: function(response) {
                    console.log(response);
                }
            });
        });
    });
</script>


	<?php
	if ($_GET['vendas_id'] == 108480) {
		include("blocos_seguros/debito_cartao.php");
	}
	?>