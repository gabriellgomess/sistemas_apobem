<?php
$user =& JFactory::getUser();
$username=$user->username;
$consultor=$user->name;
$user_id=$user->id;
$diretoria = 0;
$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $user_id . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '10'){$administracao = 1;}
	if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if ($row_grupo_user['id'] == '19'){$financeiro = 1;}
}

$venda_origem_id=$_GET["venda_origem_id"];
$cliente_cpf=$_GET["clients_cpf"];
$clients_cpf=$_GET["clients_cpf"];
include("sistema/cliente/espelha_confere.php");
if ($row_espelha_confere["total"]){
	
	include("sistema/connect_db02.php");
	include("sistema/utf8.php");

	include("sistema/cliente/espelha_existente.php");
	
	include("sistema/connect.php");
	include("sistema/utf8.php");
	
	include("sistema/cliente/espelha_atualiza.php");
}else{

	include("sistema/connect_db02.php");
	include("sistema/utf8.php");

	include("sistema/cliente/espelha.php");

	include("sistema/connect.php");
	include("sistema/utf8.php");
	
	include("sistema/cliente/espelha_insere.php");
}

$user =& JFactory::getUser();
$vendas_user=$user->username;
if ($_GET["vendas_consultor"]){$vendas_consultor=$_GET["vendas_consultor"];}else{$vendas_consultor=$user_id;}
$vendas_apolice=$_GET["vendas_apolice"];

$result_apolice = mysql_query("SELECT apolice_valor, apolice_tipo, apolice_status, apolice_cms_vendedor FROM sys_vendas_apolices WHERE apolice_id = '".$vendas_apolice."';")
or die(mysql_error());
$row_apolice = mysql_fetch_array( $result_apolice );

if ($_GET["apolice_valor"]){
	$vendas_valor = moedaBR_to_modaDB($_GET["apolice_valor"]);
}else{
	$vendas_valor=$row_apolice['apolice_valor'];
}

$vendas_comissao_vendedor = (($vendas_valor * $row_apolice['apolice_cms_vendedor']) / 100);

if ($_GET["vendas_dia_desconto"]){$vendas_dia_desconto=$_GET["vendas_dia_desconto"];}else{$vendas_dia_desconto="01";}
$vendas_pgto=$_GET["vendas_pgto"];
$vendas_cartao_adm=$_GET["vendas_cartao_adm"];
$vendas_cartao_band=$_GET["vendas_cartao_band"];
$vendas_cartao_num=$_GET["vendas_cartao_num"];
$vendas_cartao_cvv=$_GET["vendas_cartao_cvv"];
$vendas_cartao_validade_mes=$_GET["vendas_cartao_validade_mes"];
$vendas_cartao_validade_ano=$_GET["vendas_cartao_validade_ano"];
$vendas_debito_banco=$_GET["vendas_debito_banco"];
$vendas_debito_ag_dig=$_GET["vendas_debito_ag_dig"];
$vendas_debito_ag=$_GET["vendas_debito_ag"];
$vendas_debito_cc=$_GET["vendas_debito_cc"];
$vendas_debito_cc_dig=$_GET["vendas_debito_cc_dig"];
$vendas_debito_banco_2=$_GET["vendas_debito_banco_2"];
$vendas_debito_ag_2=$_GET["vendas_debito_ag_2"];
$vendas_debito_cc_2=$_GET["vendas_debito_cc_2"];
$vendas_debito_banco_3=$_GET["vendas_debito_banco_3"];
$vendas_debito_ag_3=$_GET["vendas_debito_ag_3"];
$vendas_debito_cc_3=$_GET["vendas_debito_cc_3"];
$vendas_banco=$_GET["vendas_banco"];
$forma_envio_kitcert = $_GET["forma_envio_kitcert"];

if ($_GET["vendas_status"]){$vendas_status=$_GET["vendas_status"];}else{$vendas_status=$row_apolice['apolice_status'];}
if (date('l') == "Saturday"){if (date("H:i:s") <= "13:05"){$vendas_turno="3";}else{$vendas_turno="4";}}
else{if (date("H:i:s") <= "15:05"){$vendas_turno="1";}else{$vendas_turno="2";}}
$vendas_obs=$_GET["vendas_obs"];
if ($administracao == 1){
	if ($_GET["dp-normal-1"]){$vendas_dia_venda = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));
	}else{$vendas_dia_venda = date("Y-m-d H:i:s");}
}else{$vendas_dia_venda = date("Y-m-d H:i:s");}

$vendas_telefone = "";
$vendas_telefone2 = "";

if($_GET["vendas_telefone"]){$vendas_telefone=$_GET["vendas_telefone"];}
if($_GET["vendas_telefone2"]){$vendas_telefone2=$_GET["vendas_telefone2"];}

if($_GET["apolice_tipo"] != "2" && (ctype_space($vendas_telefone) || ctype_space($vendas_telefone2) || $vendas_telefone == "" || $vendas_telefone2 == "") )
{
	$campo_vazio = $campo_vazio."* Os campos 'Telefone Fixo' e 'Telefone Celular' são obrigatórios.<br />";
}

?>
<?php
if (!$campo_vazio){
	include("../connect.php");
	$sql = "INSERT INTO `sistema`.`sys_vendas_seguros` (`vendas_id`, 
	`venda_origem_id`, 
	`cliente_cpf`, 
	`vendas_consultor`, 
	`vendas_apolice`, 
	`vendas_valor`, 
	`vendas_comissao_vendedor`, 
	`vendas_dia_desconto`, 
	`vendas_pgto`, 
	`vendas_cartao_adm`, 
	`vendas_cartao_band`, 
	`vendas_cartao_num`, 
	`vendas_cartao_cvv`,
	`vendas_cartao_validade_mes`, 
	`vendas_cartao_validade_ano`, 
	`vendas_ben`, 
	`vendas_parent`, 
	`vendas_debito_banco`, 
	`vendas_debito_ag_dig`, 
	`vendas_debito_ag`, 
	`vendas_debito_cc`, 
	`vendas_debito_cc_dig`, 
	`vendas_debito_banco_2`, 
	`vendas_debito_ag_2`, 
	`vendas_debito_cc_2`, 
	`vendas_debito_banco_3`, 
	`vendas_debito_ag_3`, 
	`vendas_debito_cc_3`, 
	`vendas_banco`, 
	`vendas_dia_venda`,
	`vendas_status`, 
	`vendas_turno`, 
	`vendas_user`, 
	`vendas_orgao`, 
	`vendas_telefone`, 
	`vendas_telefone2`, 
	`vendas_obs`,
	`forma_envio_kitcert`) 
	VALUES (NULL, 
	'$venda_origem_id',
	'$cliente_cpf',
	'$vendas_consultor',
	'$vendas_apolice',
	'$vendas_valor',
	'$vendas_comissao_vendedor',
	'$vendas_dia_desconto',
	'$vendas_pgto',
	'$vendas_cartao_adm',
	'$vendas_cartao_band',
	'$vendas_cartao_num',
	'$vendas_cartao_cvv',
	'$vendas_cartao_validade_mes',
	'$vendas_cartao_validade_ano',
	'$vendas_ben',
	'$vendas_parent',
	'$vendas_debito_banco',
	'$vendas_debito_ag_dig',
	'$vendas_debito_ag',
	'$vendas_debito_cc',
	'$vendas_debito_cc_dig',
	'$vendas_debito_banco_2',
	'$vendas_debito_ag_2',
	'$vendas_debito_cc_2',
	'$vendas_debito_banco_3',
	'$vendas_debito_ag_3',
	'$vendas_debito_cc_3',
	'$vendas_banco',
	'$vendas_dia_venda',
	'$vendas_status',
	'$vendas_turno',
	'$vendas_user',
	'$vendas_orgao',
	'$vendas_telefone',
	'$vendas_telefone2',
	'$vendas_obs',
	'$forma_envio_kitcert'
	);"; 
	if (mysql_query($sql,$con)){
		$vendas_id = mysql_insert_id();

		$update_transaction_query ="UPDATE sys_vendas_transacoes_tef
			SET transacao_venda_id = '".$vendas_id."'
			WHERE transacao_token =".$_GET['token_transactions'].";";
		$result_transaction = mysql_query($update_transaction_query);

		echo "Venda de SEGURO Cadastrada com Sucesso. </br>";
		
		if($vendas_banco == 11){
			$update_venda ="UPDATE sys_vendas_seguros 
				SET vendas_proposta = '".$vendas_id."'
				WHERE vendas_id =".$vendas_id.";";
			$result_venda = mysql_query($update_venda);
		}

	} else {
		die('Error: ' . mysql_error());
	}
	
	for ($i = 1; $i <= 20; $i++) {
		if ($_GET["ben_nome".$i]){
			$ben_nome = $_GET["ben_nome".$i];
			echo "ben_nome".$i.": ".$_GET["ben_nome".$i];
			$ben_nasc = implode(preg_match("~\/~", $_GET["ben_nasc".$i]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["ben_nasc".$i]) == 0 ? "-" : "/", $_GET["ben_nasc".$i])));
			echo "ben_nasc".$i.": ".$_GET["ben_nasc".$i];
			$ben_parent = $_GET["ben_parent".$i];
			echo "ben_parent".$i.": ".$_GET["ben_parent".$i];
			$ben_perc = $_GET["ben_perc".$i];
			if(strpos($ben_perc,".")){$ben_perc=substr_replace($ben_perc, '', strpos($ben_perc, "."), 1);}
			if(!strpos($ben_perc,".")&&(strpos($ben_perc,","))){$ben_perc=substr_replace($ben_perc, '.', strpos($ben_perc, ","), 1);}
			echo "ben_perc".$i.": ".$_GET["ben_perc".$i];
			
			$sql = "INSERT INTO `sistema`.`sys_vendas_ben` (`ben_id`, 
			`vendas_id`, 
			`ben_nome`, 
			`ben_nasc`, 
			`ben_parent`, 
			`ben_perc`)
			VALUES (NULL, 
			'$vendas_id',
			'$ben_nome',
			'$ben_nasc',
			'$ben_parent',
			'$ben_perc');"; 
			if (mysql_query($sql,$con)){
			echo "Beneficiário Cadastrado com Sucesso. </br>";
			} else {
				die('Error: ' . mysql_error());
			}
		}
	}
	
	if ($vendas_orgao != "Exercito"){
		$query = mysql_query("UPDATE sys_inss_clientes SET cliente_venda='1' WHERE cliente_cpf='$cliente_cpf' ") or die(mysql_error());
		echo "Cliente Atualizado com Sucesso <br/>";
	}

	mysql_close($con);
}
?>
<?php if (!$campo_vazio): ?>
<div align="center">Cliente: <strong><?php echo $_GET["clients_nm"];?></strong></br>
CPF do Cliente: <strong><?php echo $cliente_cpf;?></strong></br>
Valor: <strong><?php echo $vendas_valor;?></strong></br>
Dia de Desconto: <strong><?php echo $vendas_dia_desconto;?></strong></br>
<img src="sistema/imagens/ok.png"/>
</br>
<?php if ($venda_origem_id): ?>
	<a href="index.php?option=com_k2&view=item&layout=item&id=341&Itemid=398&acao=edita_venda&vendas_id=<?php echo $venda_origem_id;?>" target="_parent"><button class="button validate png" type="button">Concluir</button></a> 
<?php else: ?>
	<strong> VENDA CADASTRADA COM SUCESSO! </strong></br>
	Status: Aguardando auditor.<br/>
	<!-- <a href="index.php?option=com_k2&view=item&layout=item&id=341&Itemid=476" target="_parent"><button class="button validate png" type="button">Minhas Vendas</button></a>  -->
	<a href="index.php" target="_parent"><button class="button validate png" type="button">Página Inicial</button></a></br>
<?php endif; ?>
</div>
<?php else: ?>
<strong>O(s) Campo(s) abaixo não foram preenchidos corretamente!</strong></br>
<strong><?php echo $campo_vazio; ?></strong>
<strong>Por favor, revise os campos e tente novamente!</strong></br>
<button class="button validate png" type="button" onClick="history.go(-1)">VOLTAR</button>
<?php endif; ?>

<?php
	
	// FUNÇÃO AJUSTA DATA DO FORMULÁRIO PARA O FORMATO DO BANCO DE DADOS
	function dataBR_to_dataDB($dataBr) {

		return implode("-",array_reverse(explode("-", str_replace("/","-",$dataBr))));
	}
	
	// FUNÇÃO AJUSTA VALOR MOEDA DO FORMULÁRIO PARA O FORMATO DO BANCO DE DADOS
	function moedaBR_to_modaDB($moedaBr) {

		return str_replace(",",".",str_replace(".","",$moedaBr));
	}

?>