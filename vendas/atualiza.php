<?php	
// SISTEMA SEGUROS
// vairiaveis do usuário
$user =& JFactory::getUser();
$username=$user->username;
$userid=$user->id;

// grupos do usuário
$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $userid . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if($row_grupo_user['id'] == '10'){$administracao = 1;}
	if($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if($row_grupo_user['id'] == '11'){$sup_operacional = 1;}
	if($row_grupo_user['id'] == '41'){$operacional_fisico = 1;}
	if($row_grupo_user['id'] == '61'){$operacional_equipes = 1; $administracao = 1;}
}

// nível do usuário
$result_user_nivel = mysql_query("SELECT nivel, unidade FROM jos_users WHERE id = '" . $userid . "';") 
or die(mysql_error());
$row_user_nivel = mysql_fetch_array( $result_user_nivel );

if(($row_user_nivel["nivel"] == "6")&&($row_user_nivel["unidade"] == $row_user["unidade"])){$sup_operacional = 1;}
if(($row_user_nivel["nivel"] == "7")&&($row_user_nivel["unidade"] == $row_user["unidade"])){$administracao = 1;}

// alguns gets
$vendas_id=$_GET["vendas_id"];
// consulta os dados da venda antes de atualizá-la
$result_old = mysql_query("SELECT clients_cpf, 
vendas_pos_venda, 
vendas_proposta, 
vendas_status, 
vendas_tipo_contrato, 
vendas_percelas, 
vendas_tabela,
vendas_valor,
vendas_banco, 
vendas_envio_objeto, 
vendas_contrato_fisico, 
vendas_contrato_fisico2, 
vendas_base_prod, 
vendas_portabilidade_saldo 
FROM sys_vendas WHERE vendas_id = '" . $vendas_id . "';") 
or die(mysql_error());  
$row_old = mysql_fetch_array( $result_old );

// preenche algumas variáveis com os dados antigos da venda.
$vendas_tipo_contrato = $row_old["vendas_tipo_contrato"];
$vendas_status_old = $row_old['vendas_status'];

$vendas_consultor=$_GET["vendas_consultor"];
$vendas_percelas=$_GET["vendas_percelas"];
$vendas_tabela=$_GET["vendas_tabela"];
$vendas_status=$_GET["vendas_status"];

if(($row_old["vendas_status"] != "6") && ($vendas_status == "6"))
	{
		$vendas_dia_apr = date("Y-m-d");
		$update_dia_apr = "vendas_dia_apr='$vendas_dia_apr', ";
	}else{
		$update_dia_apr = "";
	}


// se a variável $vendas_contrato_fisico possui algum valor considerado como true define os update baseado na variável...
$vendas_contrato_fisico = $_GET["vendas_contrato_fisico"];
if($vendas_contrato_fisico)
{
	$vendas_contrato_fisico = $vendas_contrato_fisico;			
	$update_contrato_fisico = ", vendas_contrato_fisico='".$vendas_contrato_fisico."'";
	$registro_contrato_fisico = $vendas_contrato_fisico;
// ... se não, o update do contrato físico será ignorado.
}else{		
	$update_contrato_fisico = "";
	$registro_contrato_fisico = $row_old["vendas_contrato_fisico"];
}
$update_contrato_fisico2="";
if($_GET["vendas_contrato_fisico2"])
{
	if($_GET["vendas_contrato_fisico2"] != $row_old["vendas_contrato_fisico2"])
	{
		$vendas_contrato_fisico2=$_GET["vendas_contrato_fisico2"];
		$update_contrato_fisico2=", vendas_contrato_fisico2='".$vendas_contrato_fisico2."'";
	}
}

if($_GET["vendas_jud"])
	{
		$vendas_jud=$_GET["vendas_jud"];
		$update_vendas_jud=", vendas_jud='".$vendas_jud."'";
	}else{
		$update_vendas_jud="";
	}

if($_GET["vendas_origem"])
	{
		$vendas_origem=$_GET["vendas_origem"];
		$update_vendas_origem=", vendas_origem='".$vendas_origem."'";
	}else{
		$update_vendas_origem="";
	}	

$vendas_envio=$_GET["vendas_envio"];
$vendas_envio_objeto=$_GET["vendas_envio_objeto"];
$vendas_envio_empresa=$_GET["vendas_envio_empresa"];
$vendas_envio_data = implode(preg_match("~\/~", $_GET["dp-normal-7"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-7"]) == 0 ? "-" : "/", $_GET["dp-normal-7"])));
$vendas_retorno=$_GET["vendas_retorno"];
$vendas_retorno_objeto=$_GET["vendas_retorno_objeto"];
$vendas_retorno_empresa=$_GET["vendas_retorno_empresa"];
$vendas_retorno_data = implode(preg_match("~\/~", $_GET["dp-normal-8"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-8"]) == 0 ? "-" : "/", $_GET["dp-normal-8"])));
$user =& JFactory::getUser();
$vendas_user=$user->username;
$vendas_alteracao = date("Y-m-d H:i:s");
$registro_restrito=$_GET["registro_restrito"];

$vendas_dia_imp = implode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "-" : "/", $_GET["dp-normal-5"])));
$vendas_dia_pago = implode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "-" : "/", $_GET["dp-normal-6"])));

$vendas_averbacao_data = implode(preg_match("~\/~", $_GET["vendas_averbacao_data"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["vendas_averbacao_data"]) == 0 ? "-" : "/", $_GET["vendas_averbacao_data"])));

if($_GET['vendas_produto']){
	$vendas_produto = $_GET['vendas_produto'];
	$vendas_produto_update = ",vendas_produto='".$vendas_produto."'";
}

// verifica se existe a data dp-normal-6 e se ela não é 00/00/0000 e preenche variável de acordo.
if(($_GET["dp-normal-6"])&&($_GET["dp-normal-6"] != "00/00/0000"))
{
	$vendas_mes = strval(substr($_GET["dp-normal-6"],3,7));
}else{
	$vendas_mes = strval(substr($_GET["dp-normal-5"],3,7));
}

// consulta se o mês da venda já existe e, caso não exista, cadastra o mês da venda.
$result_mes = mysql_query("SELECT mes_nome FROM sys_vendas_mes WHERE mes_nome='".$vendas_mes."';") or die(mysql_error());
$row_mes = mysql_fetch_array( $result_mes );
if($row_mes["mes_nome"] != $vendas_mes){
	$sql = "INSERT INTO `sistema`.`sys_vendas_mes` (`mes_id`, 
	`mes_nome`, 
	`mes_label`, 
	`mes_tipo`) 
	VALUES (NULL, 
	'$vendas_mes',
	'$vendas_mes',
	'1');"; 
	if(mysql_query($sql,$con)){
		echo "Novo Mês Registrado com Sucesso. </br>";
	} else {
		die('Error: ' . mysql_error());
	}
}

// se o vendas_banco atual que veio pelo get é diferente do antigo e é true, então preenche o update do banco.
if(($row_old["vendas_banco"] != $_GET['vendas_banco']) && ($_GET['vendas_banco']))
{
	$update_banco = ", vendas_banco='".$_GET['vendas_banco']."'";
}

// se temos o get dp-normal-9 (data do agendamento) entramos nesse contexto e...
if($_GET["dp-normal-9"]){	
	// ...usamos o cpf contido na consulta da venda ($row_old)
	$clients_cpf = $row_old["clients_cpf"];

	// ...realiza-se o espelhamento do cliente
	include("sistema/cliente/espelha_confere.php");
		if(!$row_espelha_confere["total"]){
			include("sistema/connect_db02.php");
			include("sistema/utf8.php");

			include("sistema/cliente/espelha.php");

			include("sistema/connect.php");
			include("sistema/utf8.php");
			
			include("sistema/cliente/espelha_insere.php");
		}

		include("sistema/connect_db02.php");
		include("sistema/utf8.php");

	// ...captura-se mais alguns get e valores de datas.
	$data = implode(preg_match("~\/~", $_GET["dp-normal-9"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-9"]) == 0 ? "-" : "/", $_GET["dp-normal-9"])));
	$calendar_date_schedule=$data." ".$_GET["hora"].":".$_GET["minuto"].":00";
	$calendar_date_contact = date("Y-m-d H:i:s");

	// ...deleta qualquer agendamento realizado no sistema daquele usuário para aquele cliente.
	$query = mysql_query("DELETE FROM sys_clients_calendar WHERE clients_cpf='$clients_cpf' AND username = '$username';") or die(mysql_error());
	echo "Agendamentos antigos Excluidos com Sucesso <br/>";

	// ...insere um novo agendamento.
	$sql = "INSERT INTO `sistema`.`sys_clients_calendar` (`id`, 
	`clients_cpf`, 
	`criador`, 
	`username`, 
	`calendar_date_contact`, 
	`calendar_date_schedule`, 
	`acionamento_id`, 
	`agendamento_parecer`, 
	`clients_employer`) 
	VALUES (NULL, 
	'$clients_cpf',
	'$username',
	'$username',
	'$calendar_date_contact',
	'$calendar_date_schedule',
	'$vendas_id',
	'62',
	'$acionamento_empregador');"; 
	if(!mysql_query($sql,$con))
	  {
	  die('Error: ' . mysql_error());
	  }
	echo "Agendamento Cadastrado com Sucesso.";

	include("sistema/connect.php");
	include("sistema/utf8.php");	
}
// caso concluido pos venda, remove-se os agendamentos:
if(($_GET["vendas_pos_venda"] == 4)&&($row_old["vendas_pos_venda"] != 4)){
	// ...deleta qualquer agendamento realizado no sistema daquele usuário para aquele cliente.
	$query = mysql_query("DELETE FROM sys_clients_calendar WHERE clients_cpf='$clients_cpf' AND username = '$username';") or die(mysql_error());
	echo "Agendamentos antigos Excluidos com Sucesso <br/>";	
}

// consulta url_consulta_clientes da tabela dos usuários pelo id.
$result_url = mysql_query("SELECT url_consulta_clientes FROM jos_users WHERE id = " . $userid . ";") 
or die(mysql_error());
$row_url = mysql_fetch_array( $result_url );
?>

<?php 
	// se recebeu o get salvar com o valor diferente 'salvar' e também diferente de 'salvar_fechar' segue...
?>
<?php if(($_GET["salvar"] != "salvar")&&($_GET["salvar"] != "salvar_fechar")):?>
	<?php 
	// se salvar for igual 'observacao' segue...
	if($_GET["salvar"] == "observacao")
	{
		$vendas_obs=$_GET["vendas_obs"];
		
		// se tem o vendas_pos_venda vindo do get define o vendas pos vendas como tal...
		if($_GET["vendas_pos_venda"])
		{
			$update_pos_venda = ", vendas_pos_venda='".$_GET['vendas_pos_venda']."'";
		// ... se não se o vendas_pos_venda antigo for 3 o update pos venda será 2
		}elseif($row_old["vendas_pos_venda"] == 3){
			$update_pos_venda = ", vendas_pos_venda='2'";
		}
		
		// realiza o update da venda de acordo com os critérios e variáveis estabelecidos...
		$query = mysql_query("UPDATE sys_vendas SET 
		vendas_envio='$vendas_envio', 
		vendas_envio_objeto='$vendas_envio_objeto', 
		vendas_envio_empresa='$vendas_envio_empresa', 
		vendas_envio_data='$vendas_envio_data', 
		vendas_retorno='$vendas_retorno', 
		vendas_retorno_objeto='$vendas_retorno_objeto', 
		vendas_retorno_empresa='$vendas_retorno_empresa', 
		vendas_retorno_data='$vendas_retorno_data', 
		vendas_alteracao='$vendas_alteracao',
		".$update_dia_apr."
		vendas_user='$vendas_user'".$update_contrato_fisico.$update_pos_venda.$vendas_produto_update." 
		WHERE vendas_id='".$vendas_id."' ") or die(mysql_error());
		echo "Venda Atualizada com Sucesso";
	}

	if($_GET["vendas_portabilidade_saldo"])
	{
		$contratos_de_compra = array("2", "3", "4", "5", "6", "9", "13", "14", "15", "17");
    	if ( in_array($vendas_tipo_contrato, $contratos_de_compra) )
    	{
			$vendas_portabilidade_saldo=$_GET["vendas_portabilidade_saldo"];
			if($vendas_portabilidade_saldo != $row_old['vendas_portabilidade_saldo'])
			{
				$saldo_devedor_obs = " Saldo devedor alterado de ".$row_old['vendas_portabilidade_saldo']." para ".$vendas_portabilidade_saldo.". ";
				$vendas_obs .= $saldo_devedor_obs;
			}
		}
	}

	//Faz o registro do histórico da venda com as informações estabelecidas.	
	$sql = "INSERT INTO `sistema`.`sys_vendas_registros` (`registro_id`, 
		`vendas_id`, 
		`registro_usuario`, 
		`registro_obs`, 
		`registro_status_old`,
		`registro_status`, 
		`registro_tabela`, 
		`registro_data`, 
		`registro_contrato_fisico`, 
		`registro_restrito`) 
		VALUES (NULL, 
		'$vendas_id',
		'$vendas_user',
		'$vendas_obs',
		'$vendas_status_old',
		'$vendas_status',
		'$registro_tabela',
		'$vendas_alteracao',
		'$registro_contrato_fisico',
		'$registro_restrito');";
	if(mysql_query($sql,$con)){
		$acionamento_id = mysql_insert_id();
		echo "</br>Histórico Registrado com Sucesso. </br>";
	} else {
		die('Error: ' . mysql_error());
	}
		mysql_close($con);
	?>

	<?php 
	//faz o redirecionamento para para páqgina definina e encerra com sucesso. ?>
	<meta http-equiv="Refresh" content="0; url=<?php echo $_SERVER['HTTP_REFERER'].$coeficiente_atualizado;?>">
	<div align="center">Venda: <strong><?php echo $vendas_id;?></strong></br>
	<div align="center">Prazo: <strong><?php echo $vendas_percelas;?></strong></br>
	<div align="center">Tabela: <strong><?php echo $vendas_tabela;?></strong></br>
	Prazo e/ou Tabela Atualizados com Sucesso!

<?php 
// mas caso o get 'salvar' seja igual a 'salvar' ou 'salvar_fechar' então...
// ... verifica se o (status antigo da venda é maior ou igual a 18 e menor ou igual a 20) e...
// ... verifica se o (status selecionado para venda é maior ou igual a 22 e menor ou igual a 23) e...
// ... verifica se não há nenhuma observação preenchida para esta atualização.
// atendendo aos critérios o campo observação é obrigatório, segue... ?>
<?php elseif( ( ( ($row_old["vendas_status"] >= "18") && ($row_old["vendas_status"] <= "20") ) 
				&& (($vendas_status >= "22") && ($vendas_status <= "23") ) ) 
				&& (!$_GET["vendas_obs"])): ?>

	<div align="center">
	<br />
	* Favor preencher o campo Observação para Revisar a venda!<br /><br />
	<button class="button validate png" onclick="history.go(-1)" type="button">Voltar</button>
	</div>
<?php 
// ... caso não atenda os critérios...
// verifica se o status antigo é diferente de 7 e o status atual é 7 e o campo observação tem menos de 3 caracteres...
// ... nesse caso a observação também é obrigatória, segue... ?>
<?php elseif(($row_old["vendas_status"] != "7") && ($vendas_status == "7") && (strlen($_GET["vendas_obs"]) < 3)): ?>

	<div align="center">
	<br />
	* Favor preencher corretamente o campo Observação para Reprovar a venda!<br /><br />
	<button class="button validate png" onclick="history.go(-1)" type="button">Voltar</button>
	</div>
<?php 
// verifica se o status selecionado para atualizar a venda é 8 e se a data dp-normal-6 não foi definida ou é igual a 00/00/0000...
// nesse caso é obrigatório informar essa data. Segue... ?>
<?php elseif(($vendas_status == "8") && ((!$_GET["dp-normal-6"]) || ($_GET["dp-normal-6"] == "00/00/0000"))): ?>
	<div align="center">
	<br />
	* Favor informar o dia Pago, para atualizar o status para Pago!<br /><br />
	<button class="button validate png" onclick="history.go(-1)" type="button">Voltar</button>
	</div>
<?php 
// verifica se o usuário é da administração e se o status da venda é diferente de 1,7,10,11,13,14,19,23 e 100...
// além disso, verifica se o get vendas_promotora não foi enviado ou se o valor desse get é 'Nao Informado'
// nesse caso é obrigatório informar a promotora ao tentar salvar a venda. Segue... ?>
<?php elseif(($administracao) && 
				($vendas_status != "1") && 
				($vendas_status != "7") && 
				($vendas_status != "10") && 
				($vendas_status != "11") && 
				($vendas_status != "13") && 
				($vendas_status != "14") && 
				($vendas_status != "19") && 
				($vendas_status != "23") && 
				($vendas_status != "33") && 
				($vendas_status != "100") && 
				((!$_GET["vendas_promotora"]) || ($_GET["vendas_promotora"] == "Nao Informado"))): ?>

	<div align="center">
	<br />
	* Favor informar a Promotora para atualizar esta Venda!<br /><br />
	<button class="button validate png" onclick="history.go(-1)" type="button">Voltar</button>
	</div>
<?php 
// caso nenhum dos if's acima tenha sido atendidos, segue... ?>
<?php else: ?>
	<?php
	// consulta algumas informações do consultor enviado pelo get.
	$result_user = mysql_query("SELECT unidade, equipe_id, situacao, nivel, empresa, data_contrato_90 FROM jos_users WHERE id = " . $vendas_consultor . ";") 
	or die(mysql_error());
	$row_user = mysql_fetch_array( $result_user );

	// define algumas variáveis com as informações do consultor.
	$vendas_unidade = $row_user["unidade"];
	$vendas_equipe = $row_user["equipe_id"];
	$consultor_situacao = $row_user["situacao"];
	$consultor_nivel = $row_user["nivel"];
	$consultor_empresa = $row_user["empresa"];

	// verifica se a data_contrato_90 foi definida com algo que resulte em true e se ela não é igual a 0000-00-00...
	// verifica também se a variável $vendas_dia_pago foi definida com algo que retorne true. Segue...
	if( ($row_user["data_contrato_90"])&&($row_user["data_contrato_90"] != "0000-00-00") 
		&& ($vendas_dia_pago) )
	{
		// verifica se a variavel $vendas_dia_pago é maior que da data_contrato_90 do consultor.
		// caso seja a variável $consultor_situação é preenchida com 4, senão é preenchida com 1.
		if($vendas_dia_pago > $row_user["data_contrato_90"])
		{
			$consultor_situacao = 4;
		}else{
			$consultor_situacao = 1;
		}
	}

	// verifica se o status anterior é diferente do status atual selecionado para a venda.
	// caso seja, será realizado o update do status na venda.
	if($row_old["vendas_status"] != $vendas_status)
	{
		$update_vendas_unidade = ", vendas_unidade='".$row_user['unidade']."'";
	}

	// verifica se a vendas_proposta antiga da venda é diferente da vendas_proposta selecionada atual que veio pelo get. Segue...
	if($row_old["vendas_proposta"] != $_GET["vendas_proposta"])
	{
		// ... verifica se vendas_proposta enviada pelo get é diferente de "" (vazio)
		// caso seja, segue...
		if($_GET["vendas_proposta"] != "")
		{
			// consulta o vendas_id de vendas que tenham o mesmo valor em vendas_proposta que vem do get, mas que tenham o vendas_id diferente do enviado pelo get.
			$result_proposta = mysql_query("SELECT vendas_id FROM sys_vendas WHERE vendas_proposta = '" . $_GET['vendas_proposta'] . "' AND vendas_id != '".$_GET['vendas_id']."';")
				or die(mysql_error());
			$row_proposta = mysql_fetch_array( $result_proposta );

			// verifica se temos resultado com valor para o vendas_id recém consultado...
			// caso alguma outra venda possua o mesmo número de contrato carrega variável $erro_proposta
			// caso não, carrega variável para update da proposta $update_proposta.
			if($row_proposta["vendas_id"])
			{
				$erro_proposta = $row_proposta['vendas_id']; $update_proposta = "";
			}
			else{				
				$update_proposta = ", vendas_proposta='".$_GET['vendas_proposta']."'";
			}
		}else{
			// se o get vendas_proposta for vazio, realiza o update dele com um valor vazio.
			$update_proposta = ", vendas_proposta='".$_GET['vendas_proposta']."'";
		}		
	}

	// Mais alguns gets
	$vendas_portabilidade=$_GET["vendas_portabilidade"];
	$vendas_portabilidade_2=$_GET["vendas_portabilidade_2"];
	$vendas_portabilidade_3=$_GET["vendas_portabilidade_3"];
	$vendas_vendedor=$_GET["vendas_vendedor"];
	$vendas_orgao=$_GET["vendas_orgao"];
	$vendas_valor=$_GET["vendas_valor"];
	$vendas_valor_parcela=$_GET["vendas_valor_parcela"];
	$vendas_compra_prazo=$_GET["vendas_compra_prazo"];
	$vendas_compra_parcelas=$_GET["vendas_compra_parcelas"];
	$vendas_margem=$_GET["vendas_margem"];
	$vendas_liquido=$_GET["vendas_liquido"];
	$vendas_receita_saldo = (($_GET["vendas_portabilidade_saldo"] * $vendas_cms_saldo) / 100);
	$vendas_impostos_perc_saldo=$_GET["vendas_impostos_perc_saldo"];
	
	$vendas_portabilidade_saldo=$_GET["vendas_portabilidade_saldo"];
	$update_tabela = $update_tabela.", vendas_portabilidade_saldo='".$vendas_portabilidade_saldo."'";

	$vendas_portabilidade_saldo_outros=$_GET["vendas_portabilidade_saldo_outros"];
	$update_tabela = $update_tabela.", vendas_portabilidade_saldo_outros='".$vendas_portabilidade_saldo_outros."'";
	$vendas_cms_saldo=$_GET["vendas_cms_saldo"];

	$vendas_estoque=$_GET["vendas_estoque"];
	$vendas_obs=$_GET["vendas_obs"];	
	$vendas_intencionada=$_GET["vendas_intencionada"];

	$contratos_de_compra = array("2", "3", "4", "5", "6", "9", "13", "14", "15", "17");
	if ( in_array($vendas_tipo_contrato, $contratos_de_compra) )
	{
		if($vendas_portabilidade_saldo != $row_old['vendas_portabilidade_saldo'])
		{
			$saldo_devedor_obs = " Saldo devedor alterado de ".$row_old['vendas_portabilidade_saldo']." para ".$vendas_portabilidade_saldo.". ";
			$vendas_obs .= $saldo_devedor_obs;
		}
	}

	if(strpos($vendas_valor,"."))
	{
		$vendas_valor=substr_replace($vendas_valor, '', strpos($vendas_valor, "."), 1);
	}
	if(!strpos($vendas_valor,".")&&(strpos($vendas_valor,",")))
	{
		$vendas_valor=substr_replace($vendas_valor, '.', strpos($vendas_valor, ","), 1);
	}
	
	if(strpos($vendas_valor_parcela,"."))
	{
		$vendas_valor_parcela=substr_replace($vendas_valor_parcela, '', strpos($vendas_valor_parcela, "."), 1);
	}
	if(!strpos($vendas_valor_parcela,".")&&(strpos($vendas_valor_parcela,",")))
	{
		$vendas_valor_parcela=substr_replace($vendas_valor_parcela, '.', strpos($vendas_valor_parcela, ","), 1);
	}
	
	if(strpos($vendas_margem,"."))
	{
		$vendas_margem=substr_replace($vendas_margem, '', strpos($vendas_margem, "."), 1);
	}
	if(!strpos($vendas_margem,".")&&(strpos($vendas_margem,",")))
	{
		$vendas_margem=substr_replace($vendas_margem, '.', strpos($vendas_margem, ","), 1);
	}
	
	if(strpos($vendas_liquido,"."))
	{
		$vendas_liquido=substr_replace($vendas_liquido, '', strpos($vendas_liquido, "."), 1);
	}
	if(!strpos($vendas_liquido,".")&&(strpos($vendas_liquido,","))){$vendas_liquido=substr_replace($vendas_liquido, '.', strpos($vendas_liquido, ","), 1);}

	if($_GET["vendas_base_contrato"])
	{
		$vendas_base_contrato=$_GET["vendas_base_contrato"];
		if(strpos($vendas_base_contrato,".")){$vendas_base_contrato=substr_replace($vendas_base_contrato, '', strpos($vendas_base_contrato, "."), 1);}
		if(!strpos($vendas_base_contrato,".")&&(strpos($vendas_base_contrato,","))){$vendas_base_contrato=substr_replace($vendas_base_contrato, '.', strpos($vendas_base_contrato, ","), 1);}
	}

	if($operacional_fisico)
	{
		if($_GET["vendas_envio_valor"])
		{
			$vendas_envio_valor=$_GET["vendas_envio_valor"];
			if(strpos($vendas_envio_valor,"."))
			{
				$vendas_envio_valor=substr_replace($vendas_envio_valor, '', strpos($vendas_envio_valor, "."), 1);
			}
			if(!strpos($vendas_envio_valor,".")&&(strpos($vendas_envio_valor,",")))
			{
				$vendas_envio_valor=substr_replace($vendas_envio_valor, '.', strpos($vendas_envio_valor, ","), 1);
			}
			$update_envio_valor = ", vendas_envio_valor='".$vendas_envio_valor."'";
		}
	}

	if(strpos($vendas_cms_saldo,"."))
	{
		$vendas_cms_saldo=substr_replace($vendas_cms_saldo, '', strpos($vendas_cms_saldo, "."), 1);
	}
	if(!strpos($vendas_cms_saldo,".")&&(strpos($vendas_cms_saldo,",")))
	{
		$vendas_cms_saldo=substr_replace($vendas_cms_saldo, '.', strpos($vendas_cms_saldo, ","), 1);
	}
	
	if(strpos($vendas_impostos_perc_saldo,"."))
	{
		$vendas_impostos_perc_saldo=substr_replace($vendas_impostos_perc_saldo, '', strpos($vendas_impostos_perc_saldo, "."), 1);
	}
	if(!strpos($vendas_impostos_perc_saldo,".")&&(strpos($vendas_impostos_perc_saldo,",")))
	{
		$vendas_impostos_perc_saldo=substr_replace($vendas_impostos_perc_saldo, '.', strpos($vendas_impostos_perc_saldo, ","), 1);
	}

	$vendas_impostos_saldo = (($vendas_receita_saldo * $vendas_impostos_perc_saldo) / 100);

	if($_GET["vendas_taxa"])
	{
		$vendas_taxa = $_GET["vendas_taxa"];
		if(strpos($vendas_taxa,"."))
		{
			$vendas_taxa=substr_replace($vendas_taxa, '', strpos($vendas_taxa, "."), 1);
		}
		if(!strpos($vendas_taxa,".")&&(strpos($vendas_taxa,",")))
		{
			$vendas_taxa=substr_replace($vendas_taxa, '.', strpos($vendas_taxa, ","), 1);
		}
	}else{
		$vendas_taxa = 0;
	}

	$dia = date("d");
	$tabela_dia = "tabela_dia_".$dia;
	$result_tabela = mysql_query("SELECT tabela_id, 
	tabela_codigo, 
	tabela_base, 
	tabela_coeficiente_base2, 
	tabela_base_contrato, 
	tabela_perc_base_prod, 
	tabela_cms_consultor_interno, 
	tabela_juros, 
	tabela_juros_liquido, 
	tabela_juros_fr, 
	tabela_cms_pmt, 
	tabela_cms_saldo, 
	tabela_bonus, 
	vendas_banco_fortcoins, 
	tabela_fortcoins, 
	tabela_fortcoins_zero, 
	tabela_tipo,".$tabela_dia." FROM sys_vendas_tabelas 
	INNER JOIN sys_vendas_bancos ON sys_vendas_tabelas.tabela_banco = sys_vendas_bancos.vendas_bancos_id 
	WHERE tabela_id = '".$vendas_tabela."';") 
	or die(mysql_error());
	$row_tabela = mysql_fetch_array( $result_tabela );

	if((($row_old["vendas_status"] != "6") && ($vendas_status == "6")) || 
		(($row_old["vendas_status"] != "8") && ($vendas_status == "8")) || 
		(($row_old["vendas_status"] != "17") && ($vendas_status == "17")) || 
		($_GET["update_tabela"] == "1")||($_GET["calc_rec"] == "1"))
	{
		if($row_old["vendas_tabela"])
		{
			$result_tabela_old = mysql_query("SELECT tabela_tipo FROM sys_vendas_tabelas WHERE tabela_id = " . $row_old['vendas_tabela'] . ";") 
			or die(mysql_error());
			$row_tabela_old = mysql_fetch_array( $result_tabela_old );

			if(($row_tabela_old["tabela_tipo"] != "FLEX") && ($row_tabela["tabela_tipo"] == "FLEX")){$update_status=", vendas_status='24'";}		
		}
		
		if($_GET["atualiza_coeficiente"] == "1")
		{
			$update_coeficiente = ", vendas_coeficiente='".$row_tabela[$tabela_dia]."'";
		}
		
		if($row_tabela["tabela_id"])
		{
			$update_tabela = ", vendas_juros='".$row_tabela['tabela_juros'].
				"', vendas_juros_liquido='".$row_tabela['tabela_juros_liquido'].
				"', vendas_juros_fr='".$row_tabela['tabela_juros_fr'].
				"', vendas_pmt='".$row_tabela['tabela_cms_pmt'].
				"', vendas_cms_saldo='".$row_tabela['tabela_cms_saldo'].
				"', vendas_bonus='".$row_tabela['tabela_bonus']."'";

			if(($vendas_tipo_contrato == "6") || ($vendas_tipo_contrato == "10"))
			{
				$update_tabela =  $update_tabela.", vendas_receita_plastico='".$row_tabela['tabela_cms_plastico']."', vendas_receita_ativacao='".$row_tabela['tabela_cms_ativacao']."'";
			}
			
			// ### CALCULO AUTOMATICO DE BASE DE PRODUÇÃO! ###
			$vendas_juros = $row_tabela['tabela_juros'];
			$vendas_juros_liquido = $row_tabela['tabela_juros_liquido'];
			$vendas_juros_fr = $row_tabela['tabela_juros_fr'];
			$vendas_pmt = $row_tabela['tabela_cms_pmt'];
			$vendas_cms_saldo = $row_tabela['tabela_cms_saldo'];
			$vendas_bonus = $row_tabela['tabela_bonus'];

			if($row_tabela['tabela_coeficiente_base2'])
			{
				$coeficiente_base = $row_tabela['tabela_coeficiente_base2'];
			}else{
				$coeficiente_base = 5.0;
			}
			include("sistema/vendas/calcula_base.php");
			
			$update_tabela = $update_tabela.", vendas_base='".$vendas_base."', 
				vendas_base_contrato='".$vendas_base_contrato."', 
				vendas_comissao_vendedor='".$vendas_comissao_vendedor."', 
				vendas_cms_perc_af='".$vendas_cms_perc_af."', 
				vendas_base_prod='".$vendas_base_prod."'";
			
			if($cms_nova)
			{
				$vendas_receita = $vendas_receita_bruta + $vendas_receita_plastico_calc + $vendas_receita_ativacao_calc - $vendas_taxa - $vendas_impostos - $vendas_impostos_plastico_ativacao;
				$vendas_receita_fr = $cms_dif;
				$vendas_receita_ant_dif = $cms_ant_dif;
				$vendas_receita_bruta = $cms_total;
				$vendas_impostos = (($base_calculo_impostos) * $vendas_impostos_perc) / 100;
				$vendas_receita = $vendas_receita_bruta - $vendas_taxa - $vendas_impostos;
				$vendas_receita_fr = $cms_dif;
				$vendas_receita_ant_dif = $cms_ant_dif;
				$update_tabela = $update_tabela."
					, vendas_receita_bruta='".$vendas_receita_bruta."'
					, vendas_receita_fr='".$vendas_receita_fr."'
					, vendas_receita_ant_dif='".$vendas_receita_ant_dif."'
					, vendas_receita='".$vendas_receita."'
					, vendas_fortcoins='".$vendas_fortcoins."'";
			}else{
				$vendas_receita_bruta = $vendas_comissao_fortune + $vendas_receita_saldo + $vendas_comissao_fortune_liquido;
				$vendas_fortcoins = $vendas_receita_bruta * 1.33;
				$vendas_receita_pmt = (($vendas_valor_parcela * $vendas_pmt) / 100);
				$vendas_receita_fr = (($vendas_base_contrato * $vendas_juros_fr) / 100) + ($vendas_receita_pmt * $vendas_percelas);
				$vendas_receita_bonus = $vendas_receita_bonus - $vendas_impostos_bonus;
				$vendas_receita = $vendas_receita_bruta + $vendas_receita_bonus + $vendas_receita_plastico_calc + $vendas_receita_ativacao_calc - $vendas_taxa - $vendas_impostos - $vendas_impostos_plastico_ativacao;
				$update_tabela = $update_tabela."
					, vendas_comissao_fortune='".$vendas_comissao_fortune."'
					, vendas_receita_bruta='".$vendas_receita_bruta."'
					, vendas_receita_fr='".$vendas_receita_fr."'
					, vendas_receita_ant_dif='".$vendas_receita_ant_dif."'
					, vendas_receita_bonus='".$vendas_receita_bonus."'
					, vendas_receita='".$vendas_receita."'
					, vendas_fortcoins='".$vendas_fortcoins."'";
			}
		}
		if($_GET["update_tabela"] == "1")
		{
			$update_tipo_contrato = ", vendas_tipo_contrato='".$_GET['vendas_tipo_contrato']."'";
			$update_percelas = ", vendas_percelas='".$_GET['vendas_percelas']."'";
			$update_tabela = $update_tabela.", vendas_tabela='".$vendas_tabela."'";
			
			$query = mysql_query("UPDATE sys_vendas_tabelas SET tabela_venda='2' WHERE tabela_id='$vendas_tabela' ") or die(mysql_error());
			echo "Tabela Atualizada com Sucesso <br/>";
			$sql = "INSERT INTO `sistema`.`sys_vendas_registros` (`registro_id`, 
			`vendas_id`, 
			`registro_usuario`, 
			`registro_obs`,
			`registro_status_old`,
			`registro_status`, 
			`registro_tabela`, 
			`registro_data`, 
			`registro_contrato_fisico`,
			`registro_restrito`) 
			VALUES (NULL, 
			'$vendas_id',
			'$vendas_user',
			'Alterado o Prazo e/ou a Tabela da venda de cód. ".$row_old["vendas_tabela"]." (".$row_old["vendas_percelas"]." x) para cód. ".$vendas_tabela." (".$_GET['vendas_percelas']." x).".$saldo_devedor_obs."',
			'$vendas_status_old',
			'$vendas_status',
			'$registro_tabela',
			'$vendas_alteracao',
			'$registro_contrato_fisico',
			'$registro_restrito');"; 
			if(mysql_query($sql,$con)){
				$acionamento_id = mysql_insert_id();
				echo "</br>Histórico Registrado com Sucesso. </br>";
			} else {
				die('Error: ' . mysql_error());
			}
		}
		
	}elseif($_GET["calc"] == "1"){
		
		if($_GET["coeficiente_base"])
		{
			$coeficiente_base=$_GET["coeficiente_base"];
			if(strpos($coeficiente_base,"."))
			{
				$coeficiente_base=substr_replace($coeficiente_base, '', strpos($coeficiente_base, "."), 1);
			}
			if(!strpos($coeficiente_base,".")&&(strpos($coeficiente_base,",")))
			{
				$coeficiente_base=substr_replace($coeficiente_base, '.', strpos($coeficiente_base, ","), 1);
			}
		}elseif($row_tabela['tabela_coeficiente_base2']){
			$coeficiente_base = $row_tabela['tabela_coeficiente_base2'];
		}else{
			$coeficiente_base = 5.0;
		}
		
		// ### CALCULO AUTOMATICO DE BASE DE PRODUÇÃO! ###
		include("sistema/vendas/calcula_base.php");
		
		$update_tabela = $update_tabela.", vendas_base='".$vendas_base."', 
			vendas_base_contrato='".$vendas_base_contrato."', 
			vendas_comissao_vendedor='".$vendas_comissao_vendedor."', 
			vendas_cms_perc_af='".$vendas_cms_perc_af."', 
			vendas_base_prod='".$vendas_base_prod."'";

	}elseif($sup_operacional == 1){
		$vendas_juros=$_GET["vendas_juros"];
		if(strpos($vendas_juros,"."))
		{
			$vendas_juros=substr_replace($vendas_juros, '', strpos($vendas_juros, "."), 1);
		}
		if(!strpos($vendas_juros,".")&&(strpos($vendas_juros,",")))
		{
			$vendas_juros=substr_replace($vendas_juros, '.', strpos($vendas_juros, ","), 1);
		}
		
		$vendas_juros_liquido=$_GET["vendas_juros_liquido"];
		if(strpos($vendas_juros_liquido,"."))
		{
			$vendas_juros_liquido=substr_replace($vendas_juros_liquido, '', strpos($vendas_juros_liquido, "."), 1);
		}
		if(!strpos($vendas_juros_liquido,".")&&(strpos($vendas_juros_liquido,",")))
		{
			$vendas_juros_liquido=substr_replace($vendas_juros_liquido, '.', strpos($vendas_juros_liquido, ","), 1);
		}
		
		$vendas_juros_fr=$_GET["vendas_juros_fr"];
		if(strpos($vendas_juros_fr,"."))
		{
			$vendas_juros_fr=substr_replace($vendas_juros_fr, '', strpos($vendas_juros_fr, "."), 1);
		}
		if(!strpos($vendas_juros_fr,".")&&(strpos($vendas_juros_fr,",")))
		{
			$vendas_juros_fr=substr_replace($vendas_juros_fr, '.', strpos($vendas_juros_fr, ","), 1);
		}

		$vendas_bonus = $_GET["vendas_bonus"];
		if(strpos($vendas_bonus,"."))
		{
			$vendas_bonus=substr_replace($vendas_bonus, '', strpos($vendas_bonus, "."), 1);
		}
		if(!strpos($vendas_bonus,".")&&(strpos($vendas_bonus,",")))
		{
			$vendas_bonus=substr_replace($vendas_bonus, '.', strpos($vendas_bonus, ","), 1);
		}
		
		$vendas_base_contrato=$_GET["vendas_base_contrato"];
		if(strpos($vendas_base_contrato,"."))
		{
			$vendas_base_contrato=substr_replace($vendas_base_contrato, '', strpos($vendas_base_contrato, "."), 1);
		}
		if(!strpos($vendas_base_contrato,".")&&(strpos($vendas_base_contrato,",")))
		{
			$vendas_base_contrato=substr_replace($vendas_base_contrato, '.', strpos($vendas_base_contrato, ","), 1);
		}
		
		$vendas_base_prod=$_GET["vendas_base_prod"];
		if(strpos($vendas_base_prod,"."))
		{
			$vendas_base_prod=substr_replace($vendas_base_prod, '', strpos($vendas_base_prod, "."), 1);
		}
		if(!strpos($vendas_base_prod,".")&&(strpos($vendas_base_prod,",")))
		{
			$vendas_base_prod=substr_replace($vendas_base_prod, '.', strpos($vendas_base_prod, ","), 1);
		}
		if($_GET["vendas_base"])
		{
			$vendas_base=$_GET["vendas_base"];
		}
		
		$update_tabela = $update_tabela.", vendas_juros='".$vendas_juros."', 
			vendas_juros_liquido='".$vendas_juros_liquido."', 
			vendas_juros_fr='".$vendas_juros_fr."', 
			vendas_bonus='".$vendas_bonus."', 
			vendas_base_prod='".$vendas_base_prod."', 
			vendas_comissao_vendedor_perc='".$vendas_comissao_vendedor_perc."'";
		
		if($_GET["vendas_pmt"])
		{
			$vendas_pmt = $_GET["vendas_pmt"];
			if(strpos($vendas_pmt,"."))
			{
				$vendas_pmt=substr_replace($vendas_pmt, '', strpos($vendas_pmt, "."), 1);
			}
			if(!strpos($vendas_pmt,".")&&(strpos($vendas_pmt,",")))
			{
				$vendas_pmt=substr_replace($vendas_pmt, '.', strpos($vendas_pmt, ","), 1);
			}
			$vendas_receita_pmt = (($vendas_valor_parcela * $vendas_pmt) / 100);
			$update_tabela = $update_tabela.", vendas_pmt='".$vendas_pmt."', vendas_receita_pmt='".$vendas_receita_pmt."'";
		}
		
		if(($vendas_tipo_contrato == "6") || ($vendas_tipo_contrato == "10"))
		{
			$vendas_receita_plastico=$_GET["vendas_receita_plastico"];
			if(strpos($vendas_receita_plastico,"."))
			{
				$vendas_receita_plastico=substr_replace($vendas_receita_plastico, '', strpos($vendas_receita_plastico, "."), 1);
			}
			if(!strpos($vendas_receita_plastico,".")&&(strpos($vendas_receita_plastico,",")))
			{
				$vendas_receita_plastico=substr_replace($vendas_receita_plastico, '.', strpos($vendas_receita_plastico, ","), 1);
			}

			$vendas_receita_ativacao=$_GET["vendas_receita_ativacao"];
			if(strpos($vendas_receita_ativacao,"."))
			{
				$vendas_receita_ativacao=substr_replace($vendas_receita_ativacao, '', strpos($vendas_receita_ativacao, "."), 1);
			}
			if(!strpos($vendas_receita_ativacao,".")&&(strpos($vendas_receita_ativacao,",")))
			{
				$vendas_receita_ativacao=substr_replace($vendas_receita_ativacao, '.', strpos($vendas_receita_ativacao, ","), 1);
			}
			
			$update_tabela = $update_tabela.", vendas_receita_plastico='".$vendas_receita_plastico."', vendas_receita_ativacao='".$vendas_receita_ativacao."'";
		}
	}else{
		$vendas_restituicao=$_GET["vendas_restituicao"];
		if(strpos($vendas_restituicao,"."))
		{
			$vendas_restituicao=substr_replace($vendas_restituicao, '', strpos($vendas_restituicao, "."), 1);
		}
		if(!strpos($vendas_restituicao,".")&&(strpos($vendas_restituicao,",")))
		{
			$vendas_restituicao=substr_replace($vendas_restituicao, '.', strpos($vendas_restituicao, ","), 1);
		}
		if($_GET["atualiza_coeficiente"] != "1")
		{
			$update_coeficiente = ", vendas_coeficiente='".$_GET['vendas_coeficiente']."'";
		}
		$vendas_base_contrato=$_GET["vendas_base_contrato"];
		if(strpos($vendas_base_contrato,"."))
		{
			$vendas_base_contrato=substr_replace($vendas_base_contrato, '', strpos($vendas_base_contrato, "."), 1);
		}
		if(!strpos($vendas_base_contrato,".")&&(strpos($vendas_base_contrato,",")))
		{
			$vendas_base_contrato=substr_replace($vendas_base_contrato, '.', strpos($vendas_base_contrato, ","), 1);
		}

		if($_GET["vendas_impostos_perc"])
		{
			$vendas_impostos_perc=$_GET["vendas_impostos_perc"];
			if(strpos($vendas_impostos_perc,"."))
			{
				$vendas_impostos_perc=substr_replace($vendas_impostos_perc, '', strpos($vendas_impostos_perc, "."), 1);
			}
			if(!strpos($vendas_impostos_perc,".")&&(strpos($vendas_impostos_perc,",")))
			{
				$vendas_impostos_perc=substr_replace($vendas_impostos_perc, '.', strpos($vendas_impostos_perc, ","), 1);
			}
		}

		if($_GET["vendas_impostos_perc_bonus"])
		{
			$vendas_impostos_perc_bonus=$_GET["vendas_impostos_perc_bonus"];
			if(strpos($vendas_impostos_perc_bonus,"."))
			{
				$vendas_impostos_perc_bonus=substr_replace($vendas_impostos_perc_bonus, '', strpos($vendas_impostos_perc_bonus, "."), 1);
			}
			if(!strpos($vendas_impostos_perc_bonus,".")&&(strpos($vendas_impostos_perc_bonus,",")))
			{
				$vendas_impostos_perc_bonus=substr_replace($vendas_impostos_perc_bonus, '.', strpos($vendas_impostos_perc_bonus, ","), 1);
			}
		}
	}

	$vendas_receita_bonus = (($vendas_base_contrato * $vendas_bonus) / 100);
	$vendas_comissao_fortune = (($vendas_base_contrato * $vendas_juros) / 100);

	if($_GET["vendas_receita_plastico_ok"])
	{
		$vendas_receita_plastico_calc = $vendas_receita_plastico;
	}
	if($_GET["vendas_receita_ativacao_ok"])
	{
		$vendas_receita_ativacao_calc = $vendas_receita_ativacao;
	}

	if((!$vendas_base_prod)&&($vendas_base_prod !== 0))
	{
		$vendas_base_prod = $row_old["vendas_base_prod"];
	}

	if(($vendas_tipo_contrato == "6") || ($vendas_tipo_contrato == "10"))
	{
		$vendas_impostos = (($vendas_receita_bruta) * $vendas_impostos_perc) / 100;
		$vendas_impostos_plastico_ativacao = (($vendas_receita_plastico_calc + $vendas_receita_ativacao_calc) * $vendas_impostos_perc) / 100;
	}else{
		$vendas_impostos_flat = (($vendas_receita_bruta * $vendas_impostos_perc) / 100);
		$vendas_impostos_bonus = (($vendas_receita_bonus * $vendas_impostos_perc_bonus) / 100);
		$vendas_impostos = $vendas_impostos_flat + $vendas_impostos_saldo;
	}

	$vendas_applus_ben=$_GET["vendas_applus_ben"];
	$vendas_applus_parent=$_GET["vendas_applus_parent"];
	$vendas_applus_valor=$_GET["vendas_applus_valor"];
	if(strpos($vendas_applus_valor,"."))
	{
		$vendas_applus_valor=substr_replace($vendas_applus_valor, '', strpos($vendas_applus_valor, "."), 1);
	}
	if(!strpos($vendas_applus_valor,".")&&(strpos($vendas_applus_valor,",")))
	{
		$vendas_applus_valor=substr_replace($vendas_applus_valor, '.', strpos($vendas_applus_valor, ","), 1);
	}

	if($vendas_status)
	{
		$vendas_status=$vendas_status;
		$update_status=", vendas_status='".$vendas_status."'";
	}else{
		$update_status="";
	}

	if($_GET["vendas_promotora"])
	{
		$vendas_promotora=$_GET["vendas_promotora"];
		$update_promotora=", vendas_promotora='".$vendas_promotora."'";
	}else{
		$update_promotora="";
	}

	if(((!$row_old["vendas_envio_objeto"]) && ($_GET['vendas_envio_objeto'])) && (($row_old["vendas_envio_objeto"] != $_GET['vendas_envio_objeto'])))
	{
		$update_contrato_fisico=", vendas_contrato_fisico='5'";
		$registro_contrato_fisico = "5";
	}

	$vendas_turno=$_GET["vendas_turno"];
	$vendas_seguro_protegido=$_GET["vendas_seguro_protegido"];
	if(strpos($vendas_seguro_protegido,"."))
	{
		$vendas_seguro_protegido=substr_replace($vendas_seguro_protegido, '', strpos($vendas_seguro_protegido, "."), 1);
	}
	if(!strpos($vendas_seguro_protegido,".")&&(strpos($vendas_seguro_protegido,",")))
	{
		$vendas_seguro_protegido=substr_replace($vendas_seguro_protegido, '.', strpos($vendas_seguro_protegido, ","), 1);
	}	

	if(($row_old["vendas_status"] != "4") && ($vendas_status == "4"))
	{
		$update_pos_venda = ", vendas_pos_venda='1'";
	}elseif($_GET["vendas_pos_venda"]){
		$update_pos_venda = ", vendas_pos_venda='".$_GET['vendas_pos_venda']."'";
	}elseif($row_old["vendas_pos_venda"] == 3){
		$update_pos_venda = ", vendas_pos_venda='2'";
	}
	
	if($row_old['vendas_status']== "9")
	{
		$update_banco = "";
		$update_tipo_contrato = "";
		$update_percelas = "";
		$update_tabela = "";
	}

	//echo "update_tabela: ".$update_tabela."<br>";

	if($sup_operacional == 1)
	{
		$query = mysql_query("UPDATE sys_vendas SET vendas_consultor='$vendas_consultor', 
		vendas_vendedor='$vendas_vendedor', 
		vendas_portabilidade='$vendas_portabilidade', 
		vendas_portabilidade_2='$vendas_portabilidade_2',
		vendas_portabilidade_3='$vendas_portabilidade_3',
		vendas_valor='$vendas_valor',  
		vendas_valor_parcela='$vendas_valor_parcela', 
		vendas_margem='$vendas_margem', 
		vendas_liquido='$vendas_liquido', 
		vendas_taxa='$vendas_taxa', 
		vendas_impostos_perc='$vendas_impostos_perc', 
		vendas_impostos_perc_bonus='$vendas_impostos_perc_bonus', 
		vendas_impostos='$vendas_impostos', 
		vendas_impostos_flat='$vendas_impostos_flat', 
		vendas_impostos_bonus='$vendas_impostos_bonus', 
		vendas_base_contrato='$vendas_base_contrato', 
		vendas_base='$vendas_base', 
		vendas_cms_saldo='$vendas_cms_saldo', 
		vendas_impostos_perc_saldo='$vendas_impostos_perc_saldo', 
		vendas_receita_saldo='$vendas_receita_saldo', 
		vendas_impostos_saldo='$vendas_impostos_saldo', 
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
		vendas_orgao='$vendas_orgao', 
		vendas_turno='$vendas_turno', 
		vendas_envio='$vendas_envio', 
		vendas_envio_empresa='$vendas_envio_empresa', 
		vendas_envio_data='$vendas_envio_data', 
		vendas_retorno='$vendas_retorno', 
		vendas_retorno_objeto='$vendas_retorno_objeto', 
		vendas_retorno_empresa='$vendas_retorno_empresa', 
		vendas_retorno_data='$vendas_retorno_data', 
		vendas_seguro_protegido='$vendas_seguro_protegido', 
		vendas_estoque='$vendas_estoque', 
		vendas_intencionada='$vendas_intencionada', 
		vendas_alteracao='$vendas_alteracao', 
		vendas_user='$vendas_user'".$update_status.$update_vendas_jud.$update_vendas_origem.$update_promotora.$update_contrato_fisico.$update_contrato_fisico2.$update_pos_venda.$update_tabela.$update_proposta.$update_coeficiente.$update_banco.$update_tipo_contrato.$update_percelas.$update_envio_valor.$update_vendas_unidade.$vendas_produto_update."
		WHERE vendas_id='$vendas_id' ") or die(mysql_error());
		echo "Venda Atualizada com Sucesso";
	}elseif($administracao == 1){
		$query = mysql_query("UPDATE sys_vendas SET vendas_consultor='$vendas_consultor', 
		vendas_vendedor='$vendas_vendedor', 
		vendas_portabilidade='$vendas_portabilidade',
		vendas_portabilidade_2='$vendas_portabilidade_2',
		vendas_portabilidade_3='$vendas_portabilidade_3', 
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
		vendas_applus_valor='$vendas_applus_valor', 
		vendas_orgao='$vendas_orgao', 
		vendas_envio='$vendas_envio', 
		vendas_envio_objeto='$vendas_envio_objeto', 
		vendas_envio_empresa='$vendas_envio_empresa', 
		vendas_envio_data='$vendas_envio_data', 
		vendas_retorno='$vendas_retorno', 
		vendas_retorno_objeto='$vendas_retorno_objeto', 
		vendas_retorno_empresa='$vendas_retorno_empresa', 
		vendas_retorno_data='$vendas_retorno_data', 
		vendas_turno='$vendas_turno', 
		vendas_seguro_protegido='$vendas_seguro_protegido', 
		vendas_estoque='$vendas_estoque', 
		vendas_intencionada='$vendas_intencionada', 
		vendas_alteracao='$vendas_alteracao', 
		vendas_user='$vendas_user'".$update_status.$update_vendas_jud.$update_vendas_origem.$update_promotora.$update_contrato_fisico.$update_contrato_fisico2.$update_pos_venda.$update_tabela.$update_proposta.$update_coeficiente.$update_banco.$update_tipo_contrato.$update_percelas.$update_envio_valor.$update_vendas_unidade.$vendas_produto_update."
		WHERE vendas_id='$vendas_id' ") or die(mysql_error());
		echo "Venda Atualizada com Sucesso";
	}else{
		$query = mysql_query("UPDATE sys_vendas SET vendas_vendedor='$vendas_vendedor', 
		vendas_valor='$vendas_valor',  
		vendas_valor_parcela='$vendas_valor_parcela', 
		vendas_margem='$vendas_margem', 
		vendas_liquido='$vendas_liquido', 
		vendas_coeficiente='$vendas_coeficiente', 
		vendas_applus_ben='$vendas_applus_ben', 
		vendas_applus_parent='$vendas_applus_parent', 
		vendas_applus_valor='$vendas_applus_valor', 
		vendas_envio='$vendas_envio', 
		vendas_envio_objeto='$vendas_envio_objeto', 
		vendas_envio_empresa='$vendas_envio_empresa', 
		vendas_envio_data='$vendas_envio_data', 
		vendas_retorno='$vendas_retorno', 
		vendas_retorno_objeto='$vendas_retorno_objeto', 
		vendas_retorno_empresa='$vendas_retorno_empresa', 
		vendas_retorno_data='$vendas_retorno_data', 
		vendas_intencionada='$vendas_intencionada', 
		vendas_alteracao='$vendas_alteracao',
		".$update_dia_apr."
		vendas_user='$vendas_user'".$update_status.$update_vendas_jud.$update_vendas_origem.$update_promotora.$update_pos_venda.$vendas_produto_update."
		WHERE vendas_id='$vendas_id' ") or die(mysql_error());
		echo "Venda Atualizada com Sucesso";
	}

	$sql = "INSERT INTO `sistema`.`sys_vendas_registros` (`registro_id`, 
	`vendas_id`, 
	`registro_usuario`, 
	`registro_obs`, 
	`registro_status_old`,
	`registro_status`, 
	`registro_tabela`, 
	`registro_data`, 
	`registro_contrato_fisico`, 
	`registro_restrito`) 
	VALUES (NULL, 
	'$vendas_id',
	'$vendas_user',
	'$vendas_obs',
	'$vendas_status_old',
	'$vendas_status',
	'$registro_tabela',
	'$vendas_alteracao',
	'$registro_contrato_fisico',
	'$registro_restrito');";
	if(mysql_query($sql,$con)){
		$acionamento_id = mysql_insert_id();
		echo "</br>Histórico Registrado com Sucesso.  </br>";
	} else {
		die('Error: ' . mysql_error());
	}
	
	### CHAMADA API DE DIGITAÇÃO:
	
	#### CARTÃO:
	if((($vendas_tipo_contrato == "6")||($vendas_tipo_contrato == "10"))&&($vendas_status == "11")&&($vendas_orgao == "INSS")){
		if($_GET['vendas_banco'] == "PAN"){
			//echo "antes de chamar API<br>";
			include("sistema/integracao/webdec/pan/insere_cartao.php");
			//echo "depois da API<br>";
		}
		if(($_GET['vendas_banco'] == "BMG")&&($user_id == 42)){
			//echo "antes de chamar API<br>";
			include("sistema/integracao/webdec/bmg/insere_cartao.php");
			//echo "depois da API<br>";
		}
	}
	
	### FIM CHAMADA API DIGITAÇÃO.

	if($_GET['notificar_consultor'])
	{
		include("sistema/vendas/emails/email_venda_alteracao.php");
	}
	
	$enviar_sms = 0;

	if($_GET['notificar_cliente_sms'])
	{ 
		$notificar_cliente_sms = $_GET['notificar_cliente_sms'];
	}else{
		$notificar_cliente_sms = 0;
	}

	if ((($row_old["vendas_status"] != "8") && ($vendas_status == "8"))
			||
		(($row_old["vendas_status"] != "7") && ($vendas_status == "7"))
			&& $notificar_cliente_sms) {
		$result_venda_cliente = mysql_query("SELECT COUNT(venda_cliente_id) AS cliente FROM sys_vendas_clientes WHERE vendas_id = '" . $vendas_id . "';") or die(mysql_error());  
		$row_venda_cliente = mysql_fetch_array( $result_venda_cliente );
		
		if ($row_venda_cliente["cliente"]){
			$result_client = mysql_query("SELECT cliente_nome, cliente_celular FROM sys_vendas_clientes WHERE vendas_id = '" . $vendas_id . "';") or die(mysql_error());  
			$row_client = mysql_fetch_array( $result_client );
		}else{
			$result_client = mysql_query("SELECT cliente_nome, cliente_celular FROM sys_inss_clientes WHERE cliente_cpf = '" . $row_old['clients_cpf'] . "';") 
			or die(mysql_error());  
			$row_client = mysql_fetch_array( $result_client );
			if (!$row_client["cliente_nome"]){
				$result_client = mysql_query("SELECT clients_nm AS cliente_nome, clients_contact_phone2 AS cliente_celular WHERE clients_cpf = '" . $row_old['clients_cpf'] . "';") 
				or die(mysql_error());  
				$row_client = mysql_fetch_array( $result_client );
			}
		}
		if (($vendas_id)&&($row_client['cliente_nome'])&&($row_client['cliente_celular'])){
			if($vendas_status == "7"){$enviar_sms = 2;}
		}
	}
?>

	<script language="javascript">
	function enviaSMS(vendas_id, cliente_nome, cliente_celular, enviar_sms) {
		if(enviar_sms){
			var xmlhttp = new XMLHttpRequest();

			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4) {
					if(xmlhttp.status == 200)
					{
						console.log("SMS enviado ok.");
					}else{
						alert("Ocorreu um erro na tentativa do envio de SMS.\nErro: "+xmlhttp.status+"\nTente novamente.");
					}
				}
			};
			
			xmlhttp.open("GET", "https://www.grupofortune.com.br/portal/sistema/integracao/sms_producao/sms_venda.php?vendas_id="+vendas_id+"&cliente_nome="+cliente_nome+"&cliente_celular="+cliente_celular+"&enviar_sms="+enviar_sms, true);
			xmlhttp.send(); 
		}
	}

	window.onload = function(){ enviaSMS(<?php echo $vendas_id;?>, <?php echo "'".$row_client['cliente_nome']."'";?>, <?php echo "'".$row_client['cliente_celular']."'";?>, <?php echo $enviar_sms;?>); }
	</script>

	<?php mysql_close($con); ?>

	<br>
	<?php if($userid != 42):?>
		<?php if($_GET["salvar"] == "salvar_fechar"):?>
			<meta http-equiv="Refresh" content="2; url=<?php echo $row_url["url_consulta_clientes"]; if($erro_proposta){echo "&erro_proposta=".$erro_proposta;}?>">
		<?php else: ?>
			<meta http-equiv="Refresh" content="2; url=<?php echo $_SERVER['HTTP_REFERER']; if($erro_proposta){echo "&erro_proposta=".$erro_proposta;}?>">
		<?php endif; ?>
	<?php endif; ?>
	<table width="100%" height="99%" border="0" align="center" cellpadding="0" cellspacing="2" bgcolor="#eeeee0">
	<div align="center">
	</br>
	<img src="sistema/imagens/calculando.gif">
	</br>
	<strong> SALVANDO VENDA! </strong></br>
	<br/>
	</div>
<?php endif; ?>