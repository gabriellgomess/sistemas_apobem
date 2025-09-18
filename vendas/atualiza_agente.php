<?php
// Alguns gets...
	$vendas_id=$_GET["vendas_id"];
	$vendas_percelas=$_GET["vendas_percelas"];
	$vendas_status=$_GET["vendas_status"];
	$vendas_envio=$_GET["vendas_envio"];
	$vendas_envio_objeto=$_GET["vendas_envio_objeto"];
	$vendas_envio_empresa=$_GET["vendas_envio_empresa"];
	$vendas_envio_data = implode(preg_match("~\/~", $_GET["dp-normal-7"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-7"]) == 0 ? "-" : "/", $_GET["dp-normal-7"])));
	$vendas_retorno=$_GET["vendas_retorno"];
	$vendas_retorno_objeto=$_GET["vendas_retorno_objeto"];
	$vendas_retorno_empresa=$_GET["vendas_retorno_empresa"];
	$vendas_retorno_data = implode(preg_match("~\/~", $_GET["dp-normal-8"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-8"]) == 0 ? "-" : "/", $_GET["dp-normal-8"])));
	$vendas_consultor=$_GET["vendas_consultor"];

	if($_GET['vendas_produto']){
		$vendas_produto = $_GET['vendas_produto'];
		$vendas_produto_update = ",vendas_produto='".$vendas_produto."'";
	}

// Consulta o consultor
$result_consultor = mysql_query("SELECT username, name, situacao, nivel, perfil FROM jos_users WHERE id = '" . $vendas_consultor . "';") 
or die(mysql_error());
$row_consultor = mysql_fetch_array( $result_consultor );

// Define comissão e saldo de acordo com o perfil do vendedor.
if ($row_consultor["perfil"] == 2){
	$campo_comisao = "tabela_comissao_vendedor_perc_prata";
	$campo_comisao_saldo = "tabela_cms_agente_saldo_prata";
}elseif ($row_consultor["perfil"] == 3){
	$campo_comisao = "tabela_comissao_vendedor_perc_ouro";
	$campo_comisao_saldo = "tabela_cms_agente_saldo_ouro";
}elseif ($row_consultor["perfil"] == 4){
	$campo_comisao = "tabela_comissao_vendedor_perc_diamante";
	$campo_comisao_saldo = "tabela_cms_agente_saldo_diamante";
}else{
	$campo_comisao = "tabela_comissao_vendedor_perc";
	$campo_comisao_saldo = "tabela_cms_agente_saldo";
}

$user =& JFactory::getUser();
$vendas_user=$user->username;
$vendas_alteracao = date("Y-m-d H:i:s");

$result_old = mysql_query("SELECT vendas_proposta, 
								  vendas_status,
								  vendas_tipo_contrato, 
								  vendas_tabela,
								  vendas_banco,
								  vendas_envio_objeto,
								  vendas_cms_vendedor_plastico,
								  vendas_cms_vendedor_ativacao,
								  vendas_taxa,
								  vendas_cip,
								  vendas_contrato_fisico,
								  vendas_cms_vendedor_saldo_perc,
								  vendas_juros,
								  vendas_base,
								  vendas_consultor
								  FROM sys_vendas
								  WHERE vendas_id = '" . $vendas_id . "';") 
							or die(mysql_error());
$row_old = mysql_fetch_array( $result_old );

$vendas_status_old = $row_old["vendas_status"];
$vendas_tipo_contrato = $row_old["vendas_tipo_contrato"];
$vendas_juros = $row_old['vendas_juros'];

$result_user = mysql_query("SELECT username,
								   name,
								   situacao,
								   nivel,
								   unidade
								   FROM jos_users
								   WHERE id = '" . $row_old['vendas_consultor'] . "';")
							or die(mysql_error());

$row_user = mysql_fetch_array( $result_user );

if ($_GET["vendas_tabela"]){
	$vendas_tabela=$_GET["vendas_tabela"];
}else{
	$vendas_tabela=$row_old["vendas_tabela"];
}

if ( $row_old["vendas_banco"] != $_GET['vendas_banco'] && $_GET['vendas_banco'] ){
	$update_banco = ", vendas_banco='".$_GET['vendas_banco']."'";
}

$result_url = mysql_query("SELECT url_consulta_clientes
							   	  FROM jos_users
							   	  WHERE id = " . $userid . ";")
							or die(mysql_error());

$row_url = mysql_fetch_array( $result_url );
?>
<?php if( ($vendas_status_old != "7") && ($_GET["vendas_status"] == "7") && (strlen($_GET["vendas_obs"]) < 3)): ?>
	<div align="center">
		<br />
		* Favor preencher corretamente o campo Observação para Reprovar a venda!<br /><br />
		<button class="button validate png" onclick="history.go(-1)" type="button">Voltar</button>
	</div>
<?php elseif(($_GET["vendas_status"] == "8") && ((!$_GET["dp-normal-6"]) || ($_GET["dp-normal-6"] == "00/00/0000"))): ?>
	<div align="center">
		<br />
		* Favor informar o dia Pago, para atualizar o status para Pago!<br /><br />
		<button class="button validate png" onclick="history.go(-1)" type="button">Voltar</button>
	</div>
<?php else: ?>
	<?php
	$user =& JFactory::getUser();
	$username=$user->username;
	$userid=$user->id;

	$result_grupo_user = mysql_query("SELECT *
											 FROM jos_user_usergroup_map
									  		 INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id
									  	  	 WHERE user_id = " . $userid . ";") 
									or die(mysql_error());

	while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
		if (($row_grupo_user['id'] == '10')||($row_grupo_user['id'] == '30')){$administracao = 1;}
		if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
		if ($row_grupo_user['id'] == '28'){$financeiro = 1;}
		if ($row_grupo_user['id'] == '11'){$sup_operacional = 1;}
		if ($row_grupo_user['id'] == '73'){$gerente_regional = 1; $administracao = 1;}
		if ($row_grupo_user['id'] == '88'){$financeiro_jsr = 1; $administracao = 1; $sup_operacional = 1;}
		if ($row_grupo_user['id'] == '89'){$financeiro_csm = 1; $administracao = 1; $sup_operacional = 1;}
	}

	if ($row_old["vendas_proposta"] != $_GET["vendas_proposta"]){
		$result_proposta = mysql_query("SELECT vendas_id 
											   FROM sys_vendas
											   WHERE vendas_proposta = '" . $_GET['vendas_proposta'] . "' 
											   AND vendas_id != '".$_GET['vendas_id']."';")
										or die(mysql_error());

		$row_proposta = mysql_fetch_array( $result_proposta );

		if ( $row_proposta["vendas_id"] ){
			$erro_proposta = $row_proposta['vendas_id']; $update_proposta = "";
		}else{
			$update_proposta = ", vendas_proposta='".$_GET['vendas_proposta']."'";
		}
	}

	$vendas_portabilidade=$_GET["vendas_portabilidade"];
	$vendas_orgao=$_GET["vendas_orgao"];
	$vendas_valor=$_GET["vendas_valor"];
	if(strpos($vendas_valor,".")){
		$vendas_valor=substr_replace($vendas_valor, '', strpos($vendas_valor, "."), 1);
	}
	if(!strpos($vendas_valor,".") && ( strpos($vendas_valor,",") )){
		$vendas_valor=substr_replace($vendas_valor, '.', strpos($vendas_valor, ","), 1);
	}

	$vendas_valor_parcela=$_GET["vendas_valor_parcela"];
	$vendas_compra_prazo=$_GET["vendas_compra_prazo"];
	$vendas_compra_parcelas=$_GET["vendas_compra_parcelas"];

	if(strpos($vendas_valor_parcela,".")){
		$vendas_valor_parcela=substr_replace($vendas_valor_parcela, '', strpos($vendas_valor_parcela, "."), 1);
	}
	if(!strpos($vendas_valor_parcela,".")&&(strpos($vendas_valor_parcela,","))){
		$vendas_valor_parcela=substr_replace($vendas_valor_parcela, '.', strpos($vendas_valor_parcela, ","), 1);
	}

	$vendas_margem=$_GET["vendas_margem"];

	if(strpos($vendas_margem,".")){
		$vendas_margem=substr_replace($vendas_margem, '', strpos($vendas_margem, "."), 1);
	}
	if(!strpos($vendas_margem,".")&&(strpos($vendas_margem,","))){
		$vendas_margem=substr_replace($vendas_margem, '.', strpos($vendas_margem, ","), 1);
	}

	$vendas_liquido=$_GET["vendas_liquido"];

	if(strpos($vendas_liquido,".")){
		$vendas_liquido=substr_replace($vendas_liquido, '', strpos($vendas_liquido, "."), 1);
	}
	if(!strpos($vendas_liquido,".")&&(strpos($vendas_liquido,","))){
		$vendas_liquido=substr_replace($vendas_liquido, '.', strpos($vendas_liquido, ","), 1);
	}
	
	$vendas_base_contrato=$_GET["vendas_base_contrato"];
	
	if(strpos($vendas_base_contrato,".")){
		$vendas_base_contrato=substr_replace($vendas_base_contrato, '', strpos($vendas_base_contrato, "."), 1);
	}
	if(!strpos($vendas_base_contrato,".")&&(strpos($vendas_base_contrato,","))){
		$vendas_base_contrato=substr_replace($vendas_base_contrato, '.', strpos($vendas_base_contrato, ","), 1);
	}

	if($_GET["vendas_cms_vendedor_plastico"]){
		$vendas_cms_vendedor_plastico=$_GET["vendas_cms_vendedor_plastico"];
		if(strpos($vendas_cms_vendedor_plastico,".")){
			$vendas_cms_vendedor_plastico=substr_replace($vendas_cms_vendedor_plastico, '', strpos($vendas_cms_vendedor_plastico, "."), 1);
		}
		if(!strpos($vendas_cms_vendedor_plastico,".")&&(strpos($vendas_cms_vendedor_plastico,","))){
			$vendas_cms_vendedor_plastico=substr_replace($vendas_cms_vendedor_plastico, '.', strpos($vendas_cms_vendedor_plastico, ","), 1);
		}
	}else{
		$vendas_cms_vendedor_plastico = $row_old["vendas_cms_vendedor_plastico"];
	}

	if($_GET["vendas_cms_vendedor_ativacao"]){
		$vendas_cms_vendedor_ativacao=$_GET["vendas_cms_vendedor_ativacao"];
		if(strpos($vendas_cms_vendedor_ativacao,".")){
			$vendas_cms_vendedor_ativacao=substr_replace($vendas_cms_vendedor_ativacao, '', strpos($vendas_cms_vendedor_ativacao, "."), 1);
		}
		if(!strpos($vendas_cms_vendedor_ativacao,".")&&(strpos($vendas_cms_vendedor_ativacao,","))){
			$vendas_cms_vendedor_ativacao=substr_replace($vendas_cms_vendedor_ativacao, '.', strpos($vendas_cms_vendedor_ativacao, ","), 1);
		}
	}else{
		$vendas_cms_vendedor_ativacao = $row_old["vendas_cms_vendedor_ativacao"];
	}

	if($_GET["vendas_portabilidade_saldo"]){
		$vendas_portabilidade_saldo=$_GET["vendas_portabilidade_saldo"];
		$update_tabela = $update_tabela.", vendas_portabilidade_saldo='".$vendas_portabilidade_saldo."'";
	}

	if($_GET["vendas_portabilidade_saldo_outros"]){
		$vendas_portabilidade_saldo_outros=$_GET["vendas_portabilidade_saldo_outros"];
		$update_tabela = $update_tabela.", vendas_portabilidade_saldo_outros='".$vendas_portabilidade_saldo_outros."'";
	}

	$vendas_restituicao=$_GET["vendas_restituicao"];

	if(strpos($vendas_restituicao,".")){
		$vendas_restituicao=substr_replace($vendas_restituicao, '', strpos($vendas_restituicao, "."), 1);
	}
	if(!strpos($vendas_restituicao,".")&&(strpos($vendas_restituicao,","))){
		$vendas_restituicao=substr_replace($vendas_restituicao, '.', strpos($vendas_restituicao, ","), 1);
	}

	$vendas_coeficiente=$_GET["vendas_coeficiente"];

	if ($_GET["vendas_taxa"]){
		$vendas_taxa = $_GET["vendas_taxa"];
		if(strpos($vendas_taxa,".")){
			$vendas_taxa=substr_replace($vendas_taxa, '', strpos($vendas_taxa, "."), 1);
		}
		if(!strpos($vendas_taxa,".")&&(strpos($vendas_taxa,","))){
			$vendas_taxa=substr_replace($vendas_taxa, '.', strpos($vendas_taxa, ","), 1);
		}
	}else{
		$vendas_taxa = $row_old["vendas_taxa"];
	}

	if($_GET["vendas_cip"]){
		if(strpos($vendas_cip,".")){
			$vendas_cip=substr_replace($vendas_cip, '', strpos($vendas_cip, "."), 1);
		}
		if(!strpos($vendas_cip,".")&&(strpos($vendas_cip,","))){
			$vendas_cip=substr_replace($vendas_cip, '.', strpos($vendas_cip, ","), 1);
		}
	}else{
		$vendas_cip = $row_old["vendas_cip"];
	}

	$dia = date("d");
	$tabela_dia = "tabela_dia_".$dia;
	$result_tabela = mysql_query("SELECT tabela_id, 
										 tabela_juros, 
										 tabela_juros_fr, 
										 tabela_juros_liquido, 
										 tabela_cms_pmt, 
										 tabela_cms_saldo, 
										 tabela_bonus, 
										 tabela_cms_plastico, 
										 tabela_cms_ativacao, 
										 tabela_tipo, 
										 tabela_base_contrato, 
										 tabela_imposto, 
										 ".$campo_comisao.",".$campo_comisao_saldo.",".$tabela_dia."
										 FROM sys_vendas_tabelas
										 WHERE tabela_id = '".$vendas_tabela."';")
								 or die(mysql_error());
	$row_tabela= mysql_fetch_array( $result_tabela );
		
	// CONSULTA PERCENTUAL DE IMPOSTO DA EMPRESA RELACIONADA COM O BANCO DA VENDA:
	if($row_tabela["tabela_imposto"] == 1){
		$result_imposto = mysql_query("SELECT cnpj_imposto
									          FROM sys_cnpjs 
											  INNER JOIN sys_empresas ON sys_cnpjs.cnpj_id = sys_empresas.empresa_cnpj 
											  WHERE empresa_nome = '".$row_user['unidade']."';")
									  or die(mysql_error());
		$row_imposto = mysql_fetch_array( $result_imposto );

		$vendas_impostos_perc = $row_imposto["cnpj_imposto"];

		if (!$vendas_impostos_perc){
			$vendas_impostos_perc=$_GET["vendas_impostos_perc"];
			if(strpos($vendas_impostos_perc,".")){
				$vendas_impostos_perc=substr_replace($vendas_impostos_perc, '', strpos($vendas_impostos_perc, "."), 1);
			}
			if(!strpos($vendas_impostos_perc,".")&&(strpos($vendas_impostos_perc,","))){
				$vendas_impostos_perc=substr_replace($vendas_impostos_perc, '.', strpos($vendas_impostos_perc, ","), 1);
			}
		}
	}else{
		$vendas_impostos_perc = 0;
	}

	if ($_GET["vendas_bonus"]){
		$vendas_bonus = $_GET["vendas_bonus"];
		if(strpos($vendas_bonus,".")){
			$vendas_bonus=substr_replace($vendas_bonus, '', strpos($vendas_bonus, "."), 1);
		}
		if(!strpos($vendas_bonus,".")&&(strpos($vendas_bonus,","))){
			$vendas_bonus=substr_replace($vendas_bonus, '.', strpos($vendas_bonus, ","), 1);
		}
	}else{
		$vendas_bonus = 0;
	}

	if ($_GET["vendas_impostos_perc_bonus"]){
		$vendas_impostos_perc_bonus=$_GET["vendas_impostos_perc_bonus"];
		if(strpos($vendas_impostos_perc_bonus,".")){
			$vendas_impostos_perc_bonus=substr_replace($vendas_impostos_perc_bonus, '', strpos($vendas_impostos_perc_bonus, "."), 1);
		}
		if(!strpos($vendas_impostos_perc_bonus,".")&&(strpos($vendas_impostos_perc_bonus,","))){
			$vendas_impostos_perc_bonus=substr_replace($vendas_impostos_perc_bonus, '.', strpos($vendas_impostos_perc_bonus, ","), 1);
		}
	}

	if ($vendas_tipo_contrato == "3"){
		$vendas_cms_saldo=$_GET["vendas_cms_saldo"];
		if(strpos($vendas_cms_saldo,".")){
			$vendas_cms_saldo=substr_replace($vendas_cms_saldo, '', strpos($vendas_cms_saldo, "."), 1);
		}
		if(!strpos($vendas_cms_saldo,".")&&(strpos($vendas_cms_saldo,","))){
			$vendas_cms_saldo=substr_replace($vendas_cms_saldo, '.', strpos($vendas_cms_saldo, ","), 1);
		}
		
		$vendas_receita_saldo = (($vendas_portabilidade_saldo * $vendas_cms_saldo) / 100);
		
		$vendas_impostos_perc_saldo=$_GET["vendas_impostos_perc_saldo"];
		if(strpos($vendas_impostos_perc_saldo,".")){
			$vendas_impostos_perc_saldo=substr_replace($vendas_impostos_perc_saldo, '', strpos($vendas_impostos_perc_saldo, "."), 1);
		}
		if(!strpos($vendas_impostos_perc_saldo,".")&&(strpos($vendas_impostos_perc_saldo,","))){
			$vendas_impostos_perc_saldo=substr_replace($vendas_impostos_perc_saldo, '.', strpos($vendas_impostos_perc_saldo, ","), 1);
		}
		
		$vendas_impostos_saldo = (($vendas_receita_saldo * $vendas_impostos_perc_saldo) / 100);
	}

	if ($sup_operacional == 1){
		$vendas_juros=$_GET["vendas_juros"];
		if(strpos($vendas_juros,".")){
			$vendas_juros=substr_replace($vendas_juros, '', strpos($vendas_juros, "."), 1);
		}
		if(!strpos($vendas_juros,".")&&(strpos($vendas_juros,","))){
			$vendas_juros=substr_replace($vendas_juros, '.', strpos($vendas_juros, ","), 1);
		}
		
		$vendas_juros_fr=$_GET["vendas_juros_fr"];
		if(strpos($vendas_juros_fr,".")){
			$vendas_juros_fr=substr_replace($vendas_juros_fr, '', strpos($vendas_juros_fr, "."), 1);
		}
		if(!strpos($vendas_juros_fr,".")&&(strpos($vendas_juros_fr,","))){
			$vendas_juros_fr=substr_replace($vendas_juros_fr, '.', strpos($vendas_juros_fr, ","), 1);
		}
		
		$vendas_juros_liquido=$_GET["vendas_juros_liquido"];
		if(strpos($vendas_juros_liquido,".")){
			$vendas_juros_liquido=substr_replace($vendas_juros_liquido, '', strpos($vendas_juros_liquido, "."), 1);
		}
		if(!strpos($vendas_juros_liquido,".")&&(strpos($vendas_juros_liquido,","))){
			$vendas_juros_liquido=substr_replace($vendas_juros_liquido, '.', strpos($vendas_juros_liquido, ","), 1);
		}
		
		$vendas_comissao_vendedor_perc=$_GET["vendas_comissao_vendedor_perc"];
		if(strpos($vendas_comissao_vendedor_perc,".")){
			$vendas_comissao_vendedor_perc=substr_replace($vendas_comissao_vendedor_perc, '', strpos($vendas_comissao_vendedor_perc, "."), 1);
		}
		if(!strpos($vendas_comissao_vendedor_perc,".")&&(strpos($vendas_comissao_vendedor_perc,","))){
			$vendas_comissao_vendedor_perc=substr_replace($vendas_comissao_vendedor_perc, '.', strpos($vendas_comissao_vendedor_perc, ","), 1);
		}

		if ($_GET["vendas_taxa"]){
			$vendas_taxa = $_GET["vendas_taxa"];
			if(strpos($vendas_taxa,".")){
				$vendas_taxa=substr_replace($vendas_taxa, '', strpos($vendas_taxa, "."), 1);
			}
			if(!strpos($vendas_taxa,".")&&(strpos($vendas_taxa,","))){
				$vendas_taxa=substr_replace($vendas_taxa, '.', strpos($vendas_taxa, ","), 1);
			}
		}else{
			$vendas_taxa = 0;
		}
		
		if($_GET["vendas_cip"]){
			$vendas_cip=$_GET["vendas_cip"];
		}else{
			$vendas_cip=0;
		}
		if(strpos($vendas_cip,".")){
			$vendas_cip=substr_replace($vendas_cip, '', strpos($vendas_cip, "."), 1);
		}
		if(!strpos($vendas_cip,".")&&(strpos($vendas_cip,","))){
			$vendas_cip=substr_replace($vendas_cip, '.', strpos($vendas_cip, ","), 1);
		}
		
		if (($vendas_tipo_contrato == "6") || ($vendas_tipo_contrato == "10")){
			$vendas_receita_plastico=$_GET["vendas_receita_plastico"];
			if(strpos($vendas_receita_plastico,".")){
				$vendas_receita_plastico=substr_replace($vendas_receita_plastico, '', strpos($vendas_receita_plastico, "."), 1);
			}
			if(!strpos($vendas_receita_plastico,".")&&(strpos($vendas_receita_plastico,","))){
				$vendas_receita_plastico=substr_replace($vendas_receita_plastico, '.', strpos($vendas_receita_plastico, ","), 1);
			}

			$vendas_receita_ativacao=$_GET["vendas_receita_ativacao"];
			if(strpos($vendas_receita_ativacao,".")){
				$vendas_receita_ativacao=substr_replace($vendas_receita_ativacao, '', strpos($vendas_receita_ativacao, "."), 1);
			}
			if(!strpos($vendas_receita_ativacao,".")&&(strpos($vendas_receita_ativacao,","))){
				$vendas_receita_ativacao=substr_replace($vendas_receita_ativacao, '.', strpos($vendas_receita_ativacao, ","), 1);
			}
			
			$update_tabela = $update_tabela.", vendas_receita_plastico='".$vendas_receita_plastico."', vendas_receita_ativacao='".$vendas_receita_ativacao."', vendas_cms_vendedor_plastico='".$vendas_cms_vendedor_plastico."', vendas_cms_vendedor_ativacao='".$vendas_cms_vendedor_ativacao."'";
		}
		
		if ($_GET["vendas_pmt"]){
			$vendas_pmt = $_GET["vendas_pmt"];
			if(strpos($vendas_pmt,".")){
				$vendas_pmt=substr_replace($vendas_pmt, '', strpos($vendas_pmt, "."), 1);
			}
			if(!strpos($vendas_pmt,".")&&(strpos($vendas_pmt,","))){
				$vendas_pmt=substr_replace($vendas_pmt, '.', strpos($vendas_pmt, ","), 1);
			}
			$vendas_receita_pmt = (($vendas_valor_parcela * $vendas_pmt) / 100);
			$update_tabela = $update_tabela.", vendas_pmt='".$vendas_pmt."', vendas_receita_pmt='".$vendas_receita_pmt."'";
		}	
	}else{
		$vendas_base = $row_old["vendas_base"];
	}

	if ((($vendas_status_old != "6") && ($_GET["vendas_status"] == "6")) || 
		(($row_old["vendas_status"] != "8") && ($_GET["vendas_status"] == "8")) || 
		(($row_old["vendas_status"] != "9") && ($_GET["vendas_status"] == "9")) || 
		(($vendas_status_old != "17") && ($_GET["vendas_status"] == "17")) || 
		($_GET["update_tabela"] == "1"))
	{
		$update_tipo_contrato = ", vendas_tipo_contrato='".$_GET['vendas_tipo_contrato']."'";
		$update_percelas = ", vendas_percelas='".$_GET['vendas_percelas']."'";
		
		if ($_GET["atualiza_coeficiente"] == "1")
		{
			$update_coeficiente = ", vendas_coeficiente='".$row_tabela[$tabela_dia]."'";
		}
		
		if ($row_tabela["tabela_id"])
		{
			$update_tabela = $update_tabela.", vendas_juros='".$row_tabela['tabela_juros'].
			"', vendas_juros_liquido='".$row_tabela['tabela_juros_liquido'].
			"', vendas_juros_fr='".$row_tabela['tabela_juros_fr'].
			"', vendas_pmt='".$row_tabela['tabela_cms_pmt'].
			"', vendas_cms_saldo='".$row_tabela['tabela_cms_saldo'].
			"', vendas_bonus='".$row_tabela['tabela_bonus']."'";
			if (($vendas_tipo_contrato == "6") || ($vendas_tipo_contrato == "10")){
				$update_tabela =  $update_tabela.", vendas_receita_plastico='".$row_tabela['tabela_cms_plastico']."', vendas_receita_ativacao='".$row_tabela['tabela_cms_ativacao']."'";
			}

			$vendas_comissao_vendedor_perc = $row_tabela[$campo_comisao];
			$vendas_cms_vendedor_saldo_perc = $row_tabela[$campo_comisao_saldo];
			$vendas_juros = $row_tabela['tabela_juros'];
			$vendas_cms_saldo = $row_tabela['tabela_cms_saldo'];
			
			if (!$vendas_comissao_vendedor_perc){
				if ($vendas_juros >= 5.5){$vendas_comissao_vendedor_perc = ($vendas_juros * 75) / 100;}//REGRA DO 6:	
				if (($vendas_juros >= 4.5)&&($vendas_juros < 5.5)){$vendas_comissao_vendedor_perc = ($vendas_juros * 70) / 100;}//REGRA DO 5:
				if (($vendas_juros >= 3.5)&&($vendas_juros < 4.5)){$vendas_comissao_vendedor_perc = ($vendas_juros * 65) / 100;}//REGRA DO 4:
				if (($vendas_juros >= 2.5)&&($vendas_juros < 3.5)){$vendas_comissao_vendedor_perc = ($vendas_juros * 60) / 100;}//REGRA DO 3:
				if (($vendas_juros >= 1.5)&&($vendas_juros < 2.5)){$vendas_comissao_vendedor_perc = ($vendas_juros * 55) / 100;}//REGRA DO 2:
				if ($vendas_juros < 1.5){$vendas_comissao_vendedor_perc = ($vendas_juros * 50) / 100;}//REGRA DO 1:
			}
			if (!$vendas_cms_vendedor_saldo_perc){
				if ($vendas_cms_saldo >= 5.5){$vendas_cms_vendedor_saldo_perc = ($vendas_cms_saldo * 75) / 100;}//REGRA DO 6:	
				if (($vendas_cms_saldo >= 4.5)&&($vendas_cms_saldo < 5.5)){$vendas_cms_vendedor_saldo_perc = ($vendas_cms_saldo * 70) / 100;}//REGRA DO 5:
				if (($vendas_cms_saldo >= 3.5)&&($vendas_cms_saldo < 4.5)){$vendas_cms_vendedor_saldo_perc = ($vendas_cms_saldo * 65) / 100;}//REGRA DO 4:
				if (($vendas_cms_saldo >= 2.5)&&($vendas_cms_saldo < 3.5)){$vendas_cms_vendedor_saldo_perc = ($vendas_cms_saldo * 60) / 100;}//REGRA DO 3:
				if (($vendas_cms_saldo >= 1.5)&&($vendas_cms_saldo < 2.5)){$vendas_cms_vendedor_saldo_perc = ($vendas_cms_saldo * 55) / 100;}//REGRA DO 2:
				if ($vendas_cms_saldo < 1.5){$vendas_cms_vendedor_saldo_perc = ($vendas_cms_saldo * 50) / 100;}//REGRA DO 1:
			}
			$update_tabela = $update_tabela.", vendas_comissao_vendedor_perc='".$vendas_comissao_vendedor_perc."', vendas_cms_vendedor_saldo_perc='".$vendas_cms_vendedor_saldo_perc."'";
		}
		
		if ($row_tabela["tabela_base_contrato"] == "1"){
			$vendas_base_contrato = $vendas_valor;
		}elseif ($row_tabela["tabela_base_contrato"] == "2"){
			$vendas_base_contrato = $vendas_liquido;
		}else{
			if (($vendas_tipo_contrato == "2")||
			($vendas_tipo_contrato == "6")||
			($vendas_tipo_contrato == "7")||
			($vendas_tipo_contrato == "10")){$vendas_base_contrato = $vendas_liquido;}else{$vendas_base_contrato = $vendas_valor;}
		}
		
		//CALCULO DE RECEITAS:
		$cms_subtotal = 0;
		$cms_valor = 0;
		$cms_subtotal_geral = 0;
		$cms_total = 0;
		$cms_subtotal_dif = 0;
		$cms_dif = 0;
		$vendas_portabilidade_saldo = $_GET["vendas_portabilidade_saldo"];
		$vendas_portabilidade_saldo_outros = $_GET["vendas_portabilidade_saldo_outros"];
		$base_calculo_impostos = 0;

		if (!$venda_nova){
			$query = mysql_query("DELETE FROM sys_vendas_cms WHERE vendas_id=$vendas_id;") or die(mysql_error());
		}
		
		$result_cms = mysql_query("SELECT cms_id,
										  tipo_id,
										  cms_perc,
										  cms_valor,
										  cms_tipo_soma,
										  cms_imposto
										  FROM sys_vendas_tabelas_cms
										  INNER JOIN sys_vendas_cms_tipos ON sys_vendas_tabelas_cms.tipo_id = sys_vendas_cms_tipos.cms_tipo_id
										  WHERE tabela_id = '".$vendas_tabela."';")
								or die(mysql_error());
		
		if (mysql_num_rows($result_cms)){
			while($row_cms = mysql_fetch_array( $result_cms )) {
				$cms_subtotal = 0;
				$cms_subtotal_dif = 0;
				$cms_subtotal_geral = 0;
				$cms_ant_dif = 0;
				$cms_subtotal_ant_sem_dif = 0;
				if(($row_cms["tipo_id"] == 1)||($row_cms["tipo_id"] == 20)){$cms_subtotal = (($vendas_valor * $row_cms['cms_perc']) / 100);} //AF Flat
				if(($row_cms["tipo_id"] == 2)||($row_cms["tipo_id"] == 21)){$cms_subtotal = (($vendas_valor * $row_cms['cms_perc']) / 100);} // AF Bônus
				if(($row_cms["tipo_id"] == 3)||($row_cms["tipo_id"] == 22)){$cms_subtotal_dif = (($vendas_valor * $row_cms['cms_perc']) / 100);} // AF Diferido
				
				// AF Antecipação Diferido:
				if(($row_cms["tipo_id"] == 4)||($row_cms["tipo_id"] == 23)){
					if ((!$compara_antecipacao)||($vendas_tipo_contrato == "6")||($vendas_tipo_contrato == "7")||($vendas_tipo_contrato == "10")){
						$cms_subtotal_ant_sem_dif = (($vendas_valor * $row_cms['cms_perc']) / 100);
					}else{$cms_subtotal_ant = (($vendas_valor * $row_cms['cms_perc']) / 100);}
				} 
				if(($row_cms["tipo_id"] == 5)||($row_cms["tipo_id"] == 24)){$cms_subtotal = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);} // Saldo Devedor Flat
				if(($row_cms["tipo_id"] == 6)||($row_cms["tipo_id"] == 25)){$cms_subtotal = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);} // Saldo Devedor Bônus
				if(($row_cms["tipo_id"] == 7)||($row_cms["tipo_id"] == 26)){$cms_subtotal_dif = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);} // Saldo Devedor Diferido
				
				// Saldo Devedor Antecipação Diferido:
				if(($row_cms["tipo_id"] == 8)||($row_cms["tipo_id"] == 27)){
					if ((!$compara_antecipacao)||($vendas_tipo_contrato == "6")||($vendas_tipo_contrato == "7")||($vendas_tipo_contrato == "10")){
						$cms_subtotal_ant_sem_dif = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);
					}else{$cms_subtotal_ant = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);}
				} 
				if(($row_cms["tipo_id"] == 9)||($row_cms["tipo_id"] == 28)){$cms_subtotal = (($vendas_liquido * $row_cms['cms_perc']) / 100);} // Líquido Flat
				if(($row_cms["tipo_id"] == 10)||($row_cms["tipo_id"] == 29)){$cms_subtotal = (($vendas_liquido * $row_cms['cms_perc']) / 100);} // Líquido Bônus
				if(($row_cms["tipo_id"] == 11)||($row_cms["tipo_id"] == 30)){$cms_subtotal_dif = (($vendas_liquido * $row_cms['cms_perc']) / 100);} // Líquido Diferido
				
				// Líquido Antecipação Diferido:
				if(($row_cms["tipo_id"] == 12)||($row_cms["tipo_id"] == 31)){
					if ((!$compara_antecipacao)||($vendas_tipo_contrato == "6")||($vendas_tipo_contrato == "7")||($vendas_tipo_contrato == "10")){
						$cms_subtotal_ant_sem_dif = (($vendas_liquido * $row_cms['cms_perc']) / 100);
					}else{$cms_subtotal_ant = (($vendas_liquido * $row_cms['cms_perc']) / 100);}
				} 
				if(($row_cms["tipo_id"] == 13)||($row_cms["tipo_id"] == 32)){$cms_subtotal = (($vendas_valor_parcela * $row_cms['cms_perc']) / 100) * $vendas_percelas;} // PMT Flat
				if(($row_cms["tipo_id"] == 14)||($row_cms["tipo_id"] == 33)){$cms_subtotal = (($vendas_valor_parcela * $row_cms['cms_perc']) / 100) * $vendas_percelas;} // PMT Bônus
				if(($row_cms["tipo_id"] == 15)||($row_cms["tipo_id"] == 36)){$cms_subtotal_dif = (($vendas_valor_parcela * $row_cms['cms_perc']) / 100) * $vendas_percelas;} // PMT Diferido
				
				// PMT Antecipação Diferido:
				if(($row_cms["tipo_id"] == 16)||($row_cms["tipo_id"] == 37)){
					if ((!$compara_antecipacao)||($vendas_tipo_contrato == "6")||($vendas_tipo_contrato == "7")||($vendas_tipo_contrato == "10")){
						$cms_subtotal_ant_sem_dif = (($vendas_valor_parcela * $row_cms['cms_perc']) / 100);
					}else{$cms_subtotal_ant = (($vendas_valor_parcela * $row_cms['cms_perc']) / 100);}
				}
				
				// CMS Plastico (17) e CMS Ativacao (18), são calculados automaticamente, pois são em valor:
				
				// Saldo Devedor OUTROS BANCOS:
				if(($row_cms["tipo_id"] == 19)||($row_cms["tipo_id"] == 40)){
					$cms_subtotal = (($_GET["vendas_portabilidade_saldo_outros"] * $row_cms['cms_perc']) / 100);
					$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				}
				
				$cms_perc = $row_cms["cms_perc"];
				$cms_valor = $row_cms["cms_valor"];
				
				$cms_subtotal_geral = $cms_subtotal + $cms_valor + $cms_subtotal_dif + $cms_subtotal_ant + $cms_subtotal_ant_sem_dif;
				//$cms_total = $cms_total + $cms_subtotal + $cms_valor + $cms_subtotal_ant_sem_dif;
				//$cms_dif = $cms_dif + $cms_subtotal_dif;
				//$cms_ant_dif = $cms_ant_dif + $cms_subtotal_ant;
				
				if($row_cms["cms_tipo_soma"] == 1){
					$cms_total = $cms_total + $cms_subtotal + $cms_valor + $cms_subtotal_ant_sem_dif;
					$cms_dif = $cms_dif + $cms_subtotal_dif;
					$cms_ant_dif = $cms_ant_dif + $cms_subtotal_ant;
					if($row_cms["cms_imposto"] == 1){
						$base_calculo_impostos = $base_calculo_impostos + $cms_subtotal + $cms_valor + $cms_subtotal_ant_sem_dif;
					}
				}else{
					$vendas_comissao_vendedor = $vendas_comissao_vendedor + $cms_subtotal + $cms_valor + $cms_subtotal_ant_sem_dif;
					$repasse_novo = 1;
				}
				
				//echo "cms_perc: ".$cms_perc."<br>";
				//echo "cms_valor: ".$cms_valor."<br>";
				//echo "cms_subtotal_geral: ".$cms_subtotal_geral."<br>";
				//echo "cms_total: ".$cms_total."<br>";
				//echo "cms_dif: ".$cms_dif."<br><br>";			
				
				$tipo_id = $row_cms["tipo_id"];
				
				if (!$venda_nova){
					$sql = "INSERT INTO `sistema`.`sys_vendas_cms` (`cms_id`, 
					`vendas_id`, 
					`tipo_id`, 
					`cms_perc`, 
					`cms_valor`, 
					`cms_subtotal`) 
					VALUES (NULL,
					'$vendas_id',
					'$tipo_id',
					'$cms_perc',
					'$cms_valor',
					'$cms_subtotal_geral');"; 
					if (mysql_query($sql,$con)){
						$cms_id = mysql_insert_id();
					} else {
						die('Error: ' . mysql_error());
					}
				}
			}
			$vendas_receita_bruta = $cms_total;
			$vendas_receita_fr = $cms_dif;
			$vendas_receita_ant_dif = $cms_ant_dif;
			$update_tabela = $update_tabela."
			, vendas_receita_bruta='".$vendas_receita_bruta."'
			, vendas_receita_fr='".$vendas_receita_fr."'
			, vendas_receita_ant_dif='".$vendas_receita_ant_dif."'";
		}
		
		if(!$repasse_novo){
			$vendas_cms_vendedor_flat = (($vendas_base_contrato * $vendas_comissao_vendedor_perc) / 100) - $vendas_taxa - $vendas_cip + $vendas_cms_vendedor_plastico + $vendas_cms_vendedor_ativacao;
			$vendas_cms_vendedor_saldo = ($vendas_portabilidade_saldo * $vendas_cms_vendedor_saldo_perc) / 100;
			$vendas_comissao_vendedor = $vendas_cms_vendedor_flat + $vendas_cms_vendedor_saldo;
		}
		
		$update_tabela = $update_tabela.", vendas_base='".$vendas_base."', 
						 vendas_base_contrato='".$vendas_base_contrato."', 
						 vendas_comissao_vendedor='".$vendas_comissao_vendedor."', 
						 vendas_cms_perc_af='".$vendas_cms_perc_af."'";
		
		$vendas_impostos = (($base_calculo_impostos * $vendas_impostos_perc) / 100);
		$vendas_receita = $vendas_receita_bruta - $vendas_comissao_vendedor - $vendas_taxa - $vendas_cip - $vendas_impostos;
		
		$update_tabela = $update_tabela.",vendas_cms_vendedor_flat='".$vendas_cms_vendedor_flat."', 
						 vendas_cms_vendedor_saldo='".$vendas_cms_vendedor_saldo."', 
						 vendas_receita='".$vendas_receita."'";
		//echo " vendas_receita='".$vendas_receita."' <br>";
		
		if($_GET["update_tabela"] == "1"){
			$update_tipo_contrato = ", vendas_tipo_contrato='".$_GET['vendas_tipo_contrato']."'";
			$update_percelas = ", vendas_percelas='".$_GET['vendas_percelas']."'";
			$update_tabela = $update_tabela.", vendas_tabela='".$vendas_tabela."'";
		
			$query = mysql_query("UPDATE sys_vendas_tabelas SET tabela_venda='2' WHERE tabela_id='$vendas_tabela' ") or die(mysql_error());
			echo "Tabela Atualizada com Sucesso <br/>";

			$registro_contrato_fisico = $row_old["vendas_contrato_fisico"];
			$sql = "INSERT INTO `sistema`.`sys_vendas_registros` (`registro_id`, 
			`vendas_id`, 
			`registro_usuario`, 
			`registro_obs`, 
			`registro_status_old`,
			`registro_status`, 
			`registro_tabela`, 
			`registro_data`, 
			`registro_contrato_fisico`) 
			VALUES (NULL, 
			'$vendas_id',
			'$vendas_user',
			'Alterado o Prazo e/ou a Tabela da venda.',
			'$vendas_status_old',
			'$vendas_status',
			'$registro_tabela',
			'$vendas_alteracao',
			'$registro_contrato_fisico');"; 
			if (mysql_query($sql,$con)){
				$acionamento_id = mysql_insert_id();
				echo "Histórico Registrado com Sucesso. </br>";
			} else {
				die('Error: ' . mysql_error());
			}
		}
		
	}

	$vendas_receita_bonus = (($vendas_base_contrato * $vendas_bonus) / 100);
	$vendas_comissao_fortune = (($vendas_base_contrato * $vendas_juros) / 100);
	$update_tabela = $update_tabela.", vendas_receita_bonus='".$vendas_receita_bonus."', vendas_comissao_fortune='".$vendas_comissao_fortune."'";

	if (!$tipo_id){
		$vendas_impostos_plastico_ativacao = (($vendas_receita_plastico + $vendas_receita_ativacao) * $vendas_impostos_perc) / 100;
		$vendas_impostos_flat = (($vendas_comissao_fortune * $vendas_impostos_perc) / 100);
		$vendas_impostos_bonus = (($vendas_receita_bonus * $vendas_impostos_perc_bonus) / 100);
		$vendas_impostos = $vendas_impostos_flat + $vendas_impostos_saldo + $vendas_impostos_bonus + $vendas_impostos_plastico_ativacao;
	}

	if (($diretoria == 1) || ($financeiro == 1)){
		if ($_GET["vendas_pago_agente"]){
			$vendas_pago_agente=$_GET["vendas_pago_agente"];
		}else{
			$vendas_pago_agente="1";
		}
		$update_pago_agente = ", vendas_pago_agente='$vendas_pago_agente'";
	}else{
		$update_pago_agente = "";
	}

	if (($sup_operacional == 1)&&(!$tipo_id)){
		if ($_GET["calc_rec"] == "1"){
			$vendas_receita_bruta=$_GET["vendas_receita_bruta"];
			if(strpos($vendas_receita_bruta,".")){
				$vendas_receita_bruta=substr_replace($vendas_receita_bruta, '', strpos($vendas_receita_bruta, "."), 1);
			}
			if(!strpos($vendas_receita_bruta,".")&&(strpos($vendas_receita_bruta,","))){
				$vendas_receita_bruta=substr_replace($vendas_receita_bruta, '.', strpos($vendas_receita_bruta, ","), 1);
			}
			$vendas_receita=$_GET["vendas_receita"];
			if(strpos($vendas_receita,".")){
				$vendas_receita=substr_replace($vendas_receita, '', strpos($vendas_receita, "."), 1);
			}
			if(!strpos($vendas_receita,".")&&(strpos($vendas_receita,","))){
				$vendas_receita=substr_replace($vendas_receita, '.', strpos($vendas_receita, ","), 1);
			}
		}else{
			$vendas_receita_bruta = $vendas_comissao_fortune + $vendas_receita_saldo + $vendas_receita_bonus + $vendas_receita_plastico + $vendas_receita_ativacao;
			$vendas_receita_fr = (($vendas_base_contrato * $vendas_juros_fr) / 100) + ($vendas_receita_pmt * $vendas_percelas);
			$vendas_receita = $vendas_receita_bruta - $vendas_comissao_vendedor - $vendas_taxa - $vendas_cip;
		}
	}

	$vendas_applus_ben=$_GET["vendas_applus_ben"];
	$vendas_applus_parent=$_GET["vendas_applus_parent"];
	$vendas_applus_valor=$_GET["vendas_applus_valor"];
	if(strpos($vendas_applus_valor,".")){
		$vendas_applus_valor=substr_replace($vendas_applus_valor, '', strpos($vendas_applus_valor, "."), 1);
	}
	if(!strpos($vendas_applus_valor,".")&&(strpos($vendas_applus_valor,","))){
		$vendas_applus_valor=substr_replace($vendas_applus_valor, '.', strpos($vendas_applus_valor, ","), 1);
	}
	$vendas_dia_imp = implode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "-" : "/", $_GET["dp-normal-5"])));
	$vendas_dia_pago = implode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "-" : "/", $_GET["dp-normal-6"])));
	$vendas_envio_data = implode(preg_match("~\/~", $_GET["dp-normal-7"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-7"]) == 0 ? "-" : "/", $_GET["dp-normal-7"])));
	$vendas_retorno_data = implode(preg_match("~\/~", $_GET["dp-normal-8"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-8"]) == 0 ? "-" : "/", $_GET["dp-normal-8"])));
	$vendas_averbacao_data = implode(preg_match("~\/~", $_GET["vendas_averbacao_data"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["vendas_averbacao_data"]) == 0 ? "-" : "/", $_GET["vendas_averbacao_data"])));

	if (($_GET["dp-normal-6"])&&($_GET["dp-normal-6"] != "00/00/0000")){
		$vendas_mes = strval(substr($_GET["dp-normal-6"],3,7));
	}else{
		$vendas_mes = strval(substr($_GET["dp-normal-5"],3,7));
	}
	$result_mes = mysql_query("SELECT mes_nome FROM sys_vendas_mes WHERE mes_nome='".$vendas_mes."';") or die(mysql_error());
	$row_mes = mysql_fetch_array( $result_mes );
	if ($row_mes["mes_nome"] != $vendas_mes){
		$sql = "INSERT INTO `sistema`.`sys_vendas_mes` (`mes_id`, 
		`mes_nome`, 
		`mes_label`, 
		`mes_tipo`) 
		VALUES (NULL, 
		'$vendas_mes',
		'$vendas_mes',
		'1');"; 
		if (mysql_query($sql,$con)){
			echo "Novo Mês Registrado com Sucesso. </br>";
		} else {
			die('Error: ' . mysql_error());
		}
	}

	if ($_GET["vendas_status"]) {
		$vendas_status=$_GET["vendas_status"];
		$update_status=", vendas_status='".$vendas_status."'";
	}else{
		$update_status="";
	}

	$vendas_promotora=$_GET["vendas_promotora"];

	$update_fisico="";
	$registro_contrato_fisico = $row_old["vendas_contrato_fisico"];
	if ($_GET["vendas_contrato_fisico"]) {
		if ($_GET["vendas_contrato_fisico"] != $row_old["vendas_contrato_fisico"]){
			$vendas_contrato_fisico=$_GET["vendas_contrato_fisico"];
			$update_fisico=", vendas_contrato_fisico='".$vendas_contrato_fisico."'";
			$registro_contrato_fisico = $vendas_contrato_fisico;
		}
	}
	$update_fisico2="";
	if ($_GET["vendas_contrato_fisico2"]) {
		if ($_GET["vendas_contrato_fisico2"] != $row_old["vendas_contrato_fisico2"]){
			$vendas_contrato_fisico2=$_GET["vendas_contrato_fisico2"];
			$update_fisico2=", vendas_contrato_fisico2='".$vendas_contrato_fisico2."'";
		}
	}
	if (((!$row_old["vendas_envio_objeto"]) && ($_GET['vendas_envio_objeto'])) && (($row_old["vendas_envio_objeto"] != $_GET['vendas_envio_objeto']))){
		$update_fisico=", vendas_contrato_fisico='5'";
		$registro_contrato_fisico = "5";
	}

	$vendas_turno=$_GET["vendas_turno"];
	$vendas_seguro_protegido=$_GET["vendas_seguro_protegido"];
	$vendas_estoque=$_GET["vendas_estoque"];
	$vendas_obs=$_GET["vendas_obs"];
	$registro_restrito=$_GET["registro_restrito"];
	$user =& JFactory::getUser();
	$vendas_user=$user->username;
	$vendas_alteracao = date("Y-m-d H:i:s");
	if (($vendas_status_old != "6") && ($vendas_status == "6")){
		$vendas_dia_apr = date("Y-m-d");
		$update_dia_apr = "vendas_dia_apr='$vendas_dia_apr', ";
	}else{
		$update_dia_apr = "";
	}
	?>
	<?php

	if(!isset($_GET['vendas_banco']) ) { $update_banco = ""; }
	if(!isset($_GET['vendas_tipo_contrato']) ) { $update_tipo_contrato = ""; }
	if(!isset($_GET['vendas_percelas']) ) { $update_percelas = ""; }
	//if(!isset($_GET['vendas_tabela']) ) { $update_tabela = ""; }

	if ($diretoria == 1){
	$query = mysql_query("UPDATE sys_vendas SET vendas_consultor='$vendas_consultor', 
	vendas_portabilidade='$vendas_portabilidade', 
	vendas_valor='$vendas_valor',  
	vendas_valor_parcela='$vendas_valor_parcela', 
	vendas_margem='$vendas_margem', 
	vendas_liquido='$vendas_liquido', 
	vendas_comissao_fortune='$vendas_comissao_fortune', 
	vendas_receita_bruta='$vendas_receita_bruta', 
	vendas_receita_bonus='$vendas_receita_bonus', 
	vendas_receita_fr='$vendas_receita_fr', 
	vendas_impostos_perc='$vendas_impostos_perc', 
	vendas_impostos_perc_bonus='$vendas_impostos_perc_bonus', 
	vendas_impostos_plastico_ativacao='$vendas_impostos_plastico_ativacao', 
	vendas_impostos='$vendas_impostos', 
	vendas_impostos_flat='$vendas_impostos_flat', 
	vendas_impostos_bonus='$vendas_impostos_bonus', 
	vendas_taxa='$vendas_taxa', 
	vendas_receita='$vendas_receita', 
	vendas_coeficiente='$vendas_coeficiente', 
	vendas_base_contrato='$vendas_base_contrato', 
	vendas_base='$vendas_base', 
	vendas_cms_saldo='$vendas_cms_saldo', 
	vendas_impostos_perc_saldo='$vendas_impostos_perc_saldo', 
	vendas_receita_saldo='$vendas_receita_saldo', 
	vendas_impostos_saldo='$vendas_impostos_saldo', 
	vendas_cip='$vendas_cip', 
	vendas_pago_agente='$vendas_pago_agente', 
	vendas_dia_imp='$vendas_dia_imp', 
	vendas_dia_pago='$vendas_dia_pago', ".$update_dia_apr."
	vendas_averbacao_data='$vendas_averbacao_data',
	vendas_mes='$vendas_mes', 
	vendas_restituicao='$vendas_restituicao', 
	vendas_applus_ben='$vendas_applus_ben', 
	vendas_applus_parent='$vendas_applus_parent', 
	vendas_envio_objeto='$vendas_envio_objeto', 
	vendas_envio_empresa='$vendas_envio_empresa', 
	vendas_retorno_objeto='$vendas_retorno_objeto', 
	vendas_retorno_empresa='$vendas_retorno_empresa', 
	vendas_applus_valor='$vendas_applus_valor', 
	vendas_promotora='$vendas_promotora', 
	vendas_orgao='$vendas_orgao', 
	vendas_envio_data='$vendas_envio_data', 
	vendas_retorno_data='$vendas_retorno_data', 
	vendas_turno='$vendas_turno', 
	vendas_envio='$vendas_envio', 
	vendas_retorno='$vendas_retorno', 
	vendas_seguro_protegido='$vendas_seguro_protegido', 
	vendas_estoque='$vendas_estoque', 
	vendas_alteracao='$vendas_alteracao', 
	vendas_user='$vendas_user'".$update_status.$update_fisico.$update_fisico2.$update_pago_agente.$update_tabela.$update_proposta.$update_banco.$update_tipo_contrato.$update_percelas.$vendas_produto_update."
	WHERE vendas_id='$vendas_id' ") or die(mysql_error());
	echo "Venda Atualizada com Sucesso";
	}elseif ($sup_operacional == 1){
	$query = mysql_query("UPDATE sys_vendas SET vendas_consultor='$vendas_consultor', 
	vendas_portabilidade='$vendas_portabilidade', 
	vendas_valor='$vendas_valor',  
	vendas_valor_parcela='$vendas_valor_parcela', 
	vendas_margem='$vendas_margem', 
	vendas_liquido='$vendas_liquido', 
	vendas_comissao_fortune='$vendas_comissao_fortune', 
	vendas_receita_bruta='$vendas_receita_bruta', 
	vendas_receita_bonus='$vendas_receita_bonus', 
	vendas_impostos='$vendas_impostos', 
	vendas_impostos_perc='$vendas_impostos_perc', 
	vendas_impostos_perc_bonus='$vendas_impostos_perc_bonus', 
	vendas_impostos_plastico_ativacao='$vendas_impostos_plastico_ativacao', 
	vendas_taxa='$vendas_taxa', 
	vendas_receita='$vendas_receita', 
	vendas_coeficiente='$vendas_coeficiente', 
	vendas_base_contrato='$vendas_base_contrato', 
	vendas_base='$vendas_base', 
	vendas_cms_saldo='$vendas_cms_saldo', 
	vendas_impostos_perc_saldo='$vendas_impostos_perc_saldo', 
	vendas_receita_saldo='$vendas_receita_saldo', 
	vendas_impostos_saldo='$vendas_impostos_saldo', 
	vendas_cip='$vendas_cip', 
	vendas_dia_imp='$vendas_dia_imp', 
	vendas_dia_pago='$vendas_dia_pago', ".$update_dia_apr."
	vendas_averbacao_data='$vendas_averbacao_data',
	vendas_mes='$vendas_mes', 
	vendas_restituicao='$vendas_restituicao', 
	vendas_applus_ben='$vendas_applus_ben', 
	vendas_applus_parent='$vendas_applus_parent', 
	vendas_envio_objeto='$vendas_envio_objeto', 
	vendas_envio_empresa='$vendas_envio_empresa', 
	vendas_retorno_objeto='$vendas_retorno_objeto', 
	vendas_retorno_empresa='$vendas_retorno_empresa', 
	vendas_applus_valor='$vendas_applus_valor', 
	vendas_promotora='$vendas_promotora', 
	vendas_orgao='$vendas_orgao', 
	vendas_envio_data='$vendas_envio_data', 
	vendas_retorno_data='$vendas_retorno_data', 
	vendas_turno='$vendas_turno', 
	vendas_envio='$vendas_envio', 
	vendas_retorno='$vendas_retorno', 
	vendas_seguro_protegido='$vendas_seguro_protegido', 
	vendas_estoque='$vendas_estoque', 
	vendas_alteracao='$vendas_alteracao', 
	vendas_user='$vendas_user'".$update_status.$update_fisico.$update_fisico2.$update_pago_agente.$update_tabela.$update_proposta.$update_banco.$update_tipo_contrato.$update_percelas.$vendas_produto_update."
	WHERE vendas_id='$vendas_id' ") or die(mysql_error());
	echo "Venda Atualizada com Sucesso";
	}elseif ($administracao == 1){
	$query = mysql_query("UPDATE sys_vendas SET vendas_consultor='$vendas_consultor', 
	vendas_portabilidade='$vendas_portabilidade', 
	vendas_valor='$vendas_valor',  
	vendas_valor_parcela='$vendas_valor_parcela', 
	vendas_margem='$vendas_margem', 
	vendas_liquido='$vendas_liquido', 
	vendas_base_contrato='$vendas_base_contrato', 
	vendas_dia_imp='$vendas_dia_imp', 
	vendas_dia_pago='$vendas_dia_pago', ".$update_dia_apr."
	vendas_averbacao_data='$vendas_averbacao_data',
	vendas_mes='$vendas_mes', 
	vendas_applus_ben='$vendas_applus_ben', 
	vendas_applus_parent='$vendas_applus_parent', 
	vendas_envio_objeto='$vendas_envio_objeto', 
	vendas_envio_empresa='$vendas_envio_empresa', 
	vendas_retorno_objeto='$vendas_retorno_objeto', 
	vendas_retorno_empresa='$vendas_retorno_empresa', 
	vendas_applus_valor='$vendas_applus_valor', 
	vendas_promotora='$vendas_promotora', 
	vendas_envio_data='$vendas_envio_data', 
	vendas_retorno_data='$vendas_retorno_data', 
	vendas_turno='$vendas_turno', 
	vendas_envio='$vendas_envio', 
	vendas_retorno='$vendas_retorno', 
	vendas_seguro_protegido='$vendas_seguro_protegido', 
	vendas_estoque='$vendas_estoque', 
	vendas_alteracao='$vendas_alteracao', 
	vendas_user='$vendas_user'".$update_status.$update_fisico.$update_fisico2.$update_pago_agente.$update_tabela.$update_proposta.$update_banco.$update_tipo_contrato.$update_percelas.$vendas_produto_update."
	WHERE vendas_id='$vendas_id' ") or die(mysql_error());
	echo "Venda Atualizada com Sucesso";
	}else{
	$query = mysql_query("UPDATE sys_vendas SET vendas_valor='$vendas_valor',  
	vendas_valor_parcela='$vendas_valor_parcela', 
	vendas_margem='$vendas_margem', 
	vendas_liquido='$vendas_liquido', 
	vendas_applus_ben='$vendas_applus_ben', 
	vendas_applus_parent='$vendas_applus_parent', 
	vendas_envio_objeto='$vendas_envio_objeto', 
	vendas_envio_empresa='$vendas_envio_empresa', 
	vendas_retorno_objeto='$vendas_retorno_objeto', 
	vendas_retorno_empresa='$vendas_retorno_empresa', 
	vendas_applus_valor='$vendas_applus_valor', 
	vendas_promotora='$vendas_promotora', 
	vendas_alteracao='$vendas_alteracao', 
	vendas_user='$vendas_user'".$update_status.$vendas_produto_update." 
	WHERE vendas_id='$vendas_id' ") or die(mysql_error());
	echo "Venda Atualizada com Sucesso";
	}
	if (!$registro_contrato_fisico)
	{
		$registro_contrato_fisico = $row_old["vendas_contrato_fisico"];
	}

	$sql = "INSERT INTO `sistema`.`sys_vendas_registros` (`registro_id`, 
	`vendas_id`, 
	`registro_usuario`, 
	`registro_obs`,
	`registro_status_old`,
	`registro_status`, 
	`registro_data`, 
	`registro_contrato_fisico`, 
	`registro_restrito`) 
	VALUES (NULL, 
	'$vendas_id',
	'$vendas_user',
	'$vendas_obs',
	'$vendas_status_old',
	'$vendas_status',
	'$vendas_alteracao',
	'$registro_contrato_fisico',
	'$registro_restrito');";
	if (mysql_query($sql,$con)){
		$acionamento_id = mysql_insert_id();
		echo "Histórico Registrado com Sucesso. </br>";
	} else {
		die('Error: ' . mysql_error());
	}

	if ($_GET["notifica_agente"] == "1"){
		include("sistema/vendas/envia_email.php");
	}
	mysql_close($con);
	?>
	<br>
	<?php if($_GET["salvar"] == "salvar_fechar"):?>
		<meta http-equiv="Refresh" content="1; url=<?php echo $row_url["url_consulta_clientes"]; if($erro_proposta){echo "&erro_proposta=".$erro_proposta;}?>">
	<?php else: ?>
		<meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER['HTTP_REFERER']; if($erro_proposta){echo "&erro_proposta=".$erro_proposta;}?>">
	<?php endif; ?>
	<table width="100%" height="99%" border="0" align="center" cellpadding="0" cellspacing="2" bgcolor="#eeeee0">
	<div align="center">
	</br>
	<img src="sistema/imagens/calculando.gif">
	</br>
	<strong> SALVANDO VENDA! </strong></br>
	<br/>
	</div>
<?php endif;?>