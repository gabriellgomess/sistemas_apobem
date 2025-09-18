<?php
$vendas_id=$_GET["vendas_id"];
$vendas_pgto=$_GET["vendas_pgto"];
$vendas_banco=$_GET["vendas_banco"];
$vendas_orgao=$_GET["vendas_orgao"];
?>
<?php if (!$_GET["vendas_dia_desconto"]):?>
	<meta http-equiv="Refresh" content="5; url=index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_seguro&vendas_id=<?php echo $vendas_id;?>">
	<div align="center"><strong>Campo DIA DE DESCONTO vazio ou inválido, tente novamente!</strong></br>
		<?php else: ?>
			<?php if (($_GET["salvar"] != "salvar")&&($_GET["salvar"] != "salvar_fechar")):?>
			<?php 
			$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_pgto='$vendas_pgto', vendas_banco='$vendas_banco' WHERE vendas_id='$vendas_id' ") or die(mysql_error());
			echo "";
			?>
			<meta http-equiv="Refresh" content="0; url=index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_seguro&vendas_id=<?php echo $vendas_id;?>">
			<div align="center">Forma de Pagamento e Seguradora Atualizadas com Sucesso!</strong></br>

				<?php elseif (($_GET["vendas_status"] == "3")&&(!$_GET["vendas_gravacao"])):?>
				<div align="center">
					Informe o Caminho da Gravação no S: para auditar a Venda!<br /><br />
					<button class="button validate png" onclick="history.go(-1)" type="button">Voltar</button>
				</div>
				<?php else: ?>
					<?php
					$user =& JFactory::getUser();
					$username=$user->username;
					$userid=$user->id;

					$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $userid . ";") 
					or die(mysql_error());
					while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
						if (($row_grupo_user['id'] == '10')||($row_grupo_user['id'] == '26')){$administracao = 1;}
						if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
						if ($row_grupo_user['id'] == '11'){$sup_operacional = 1;}
					}

					$result_apolice = mysql_query("SELECT apolice_valor, 
						apolice_cms_vendedor, 
						cbocod, 
						cbodesc, 
						vltotprem, 
						vltotcap 
						FROM sys_vendas_apolices WHERE apolice_id='".$_GET['vendas_apolice']."';")
					or die(mysql_error());
					$row_apolice = mysql_fetch_array( $result_apolice );

					$result_old = mysql_query("SELECT cliente_cpf, vendas_dia_venda, vendas_status, vendas_dia_venda FROM sys_vendas_seguros 
						WHERE vendas_id='".$vendas_id."';")
					or die(mysql_error());
					$row_old = mysql_fetch_array( $result_old );



					$vendas_consultor=$_GET["vendas_consultor"];
					$vendas_apolice=$_GET["vendas_apolice"];
					$vendas_proposta=$_GET["vendas_proposta"];
					$vendas_num_apolice=$_GET["vendas_num_apolice"];
					if ($_GET["vendas_valor"]){
						$vendas_valor=$_GET["vendas_valor"];
						if(strpos($vendas_valor,".")){$vendas_valor=substr_replace($vendas_valor, '', strpos($vendas_valor, "."), 1);}
						if(!strpos($vendas_valor,".")&&(strpos($vendas_valor,","))){$vendas_valor=substr_replace($vendas_valor, '.', strpos($vendas_valor, ","), 1);}
					}else{$vendas_valor=$row_apolice["apolice_valor"];}
					$vendas_dia_desconto=$_GET["vendas_dia_desconto"];

					$vendas_comissao_vendedor = (($vendas_valor * $row_apolice['apolice_cms_vendedor']) / 100);
					$campos_update = $campos_update.", vendas_comissao_vendedor='".$vendas_comissao_vendedor."'";

					if ($_GET["vendas_cartao_adm"]){$campos_update = $campos_update.", vendas_cartao_adm='".$_GET['vendas_cartao_adm']."'";}
					if ($_GET["vendas_cartao_band"]){$campos_update = $campos_update.", vendas_cartao_band='".$_GET['vendas_cartao_band']."'";}
					if ($_GET["vendas_cartao_num"]){$campos_update = $campos_update.", vendas_cartao_num='".$_GET['vendas_cartao_num']."'";}
					if ($_GET["vendas_cartao_validade_mes"]){$campos_update = $campos_update.", vendas_cartao_validade_mes='".$_GET['vendas_cartao_validade_mes']."'";}
					if ($_GET["vendas_cartao_validade_ano"]){$campos_update = $campos_update.", vendas_cartao_validade_ano='".$_GET['vendas_cartao_validade_ano']."'";}
					$vendas_ben=$_GET["vendas_ben"];
					$vendas_parent=$_GET["vendas_parent"];
					$vendas_debito_banco=$_GET["vendas_debito_banco"];
					$vendas_debito_ag=$_GET["vendas_debito_ag"];
					$vendas_debito_ag_dig=$_GET["vendas_debito_ag_dig"];
					$vendas_debito_cc=$_GET["vendas_debito_cc"];
					$vendas_debito_cc_dig=$_GET["vendas_debito_cc_dig"];
					$vendas_debito_banco_2=$_GET["vendas_debito_banco_2"];
					$vendas_debito_ag_2=$_GET["vendas_debito_ag_2"];
					$vendas_debito_cc_2=$_GET["vendas_debito_cc_2"];
					$vendas_debito_banco_3=$_GET["vendas_debito_banco_3"];
					$vendas_debito_ag_3=$_GET["vendas_debito_ag_3"];
					$vendas_debito_cc_3=$_GET["vendas_debito_cc_3"];
					$vendas_status=$_GET["vendas_status"];
					$vendas_telefone=$_GET["vendas_telefone"];
					$vendas_telefone2=$_GET["vendas_telefone2"];
					$vendas_gravacao=$_GET["vendas_gravacao"];
					$vendas_gravacao = mysql_real_escape_string($vendas_gravacao, $con);

					if ($_GET["dp-normal-1"]){$vendas_dia_venda = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));}
					$vendas_dia_venda = $vendas_dia_venda." ".$_GET["data_venda_hora"];
					if ($_GET["dp-normal-2"]){$vendas_dia_ativacao = implode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "-" : "/", $_GET["dp-normal-2"])));}
					$vendas_turno=$_GET["vendas_turno"];
					$vendas_obs=$_GET["vendas_obs"];
					$vendas_user=$username;
					$vendas_alteracao = date("Y-m-d H:i:s");
//if ((($_GET["vendas_status_old"] == "1") || ($_GET["vendas_status_old"] == "2") || ($_GET["vendas_status_old"] == "10") || ($_GET["vendas_status_old"] == "5")) && ($vendas_status == "3")){$vendas_dia_venda = $vendas_alteracao;}
					?>
					<?php
					if ($vendas_banco == 7 && $vendas_pgto != 3 && $row_old["vendas_status"] != "3" && $vendas_status == "3" && $row_apolice["cbocod"] > 0){ include("sistema/integracao/mbm/insere_proposta.php"); }	

					if ($administracao == 1)
					{
						$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_consultor='$vendas_consultor', 
							vendas_apolice='$vendas_apolice', 
							vendas_proposta='$vendas_proposta', 
							vendas_num_apolice='$vendas_num_apolice', 
							vendas_valor='$vendas_valor', 
							vendas_dia_desconto='$vendas_dia_desconto',  
							vendas_pgto='$vendas_pgto'".$campos_update." ,
							vendas_ben='$vendas_ben', 
							vendas_parent='$vendas_parent', 
							vendas_debito_banco='$vendas_debito_banco', 
							vendas_debito_ag='$vendas_debito_ag', 
							vendas_debito_ag_dig='$vendas_debito_ag_dig', 
							vendas_debito_cc='$vendas_debito_cc', 
							vendas_debito_cc_dig='$vendas_debito_cc_dig', 
							vendas_debito_banco_2='$vendas_debito_banco_2', 
							vendas_debito_ag_2='$vendas_debito_ag_2', 
							vendas_debito_cc_2='$vendas_debito_cc_2', 
							vendas_debito_banco_3='$vendas_debito_banco_3', 
							vendas_debito_ag_3='$vendas_debito_ag_3', 
							vendas_debito_cc_3='$vendas_debito_cc_3', 
							vendas_banco='$vendas_banco', 
							vendas_status='$vendas_status', 
							vendas_dia_venda='$vendas_dia_venda', 
							vendas_dia_ativacao='$vendas_dia_ativacao', 
							vendas_turno='$vendas_turno', 
							vendas_alteracao='$vendas_alteracao', 
							vendas_telefone='$vendas_telefone', 
							vendas_telefone2='$vendas_telefone2', 
							vendas_gravacao='$vendas_gravacao', 
							vendas_user='$vendas_user' 
							WHERE vendas_id='$vendas_id' ") or die(mysql_error());
						echo "Venda Atualizada com Sucesso";
					}else{
						$query = mysql_query("UPDATE sys_vendas_seguros SET vendas_apolice='$vendas_apolice', 
							vendas_proposta='$vendas_proposta', 
							vendas_num_apolice='$vendas_num_apolice', 
							vendas_valor='$vendas_valor', 
							vendas_dia_desconto='$vendas_dia_desconto',  
							vendas_pgto='$vendas_pgto'".$campos_update." ,
							vendas_ben='$vendas_ben', 
							vendas_parent='$vendas_parent', 
							vendas_debito_banco='$vendas_debito_banco', 
							vendas_debito_ag='$vendas_debito_ag', 
							vendas_debito_ag_dig='$vendas_debito_ag_dig', 
							vendas_debito_cc='$vendas_debito_cc', 
							vendas_debito_cc_dig='$vendas_debito_cc_dig', 
							vendas_debito_banco_2='$vendas_debito_banco_2', 
							vendas_debito_ag_2='$vendas_debito_ag_2', 
							vendas_debito_cc_2='$vendas_debito_cc_2', 
							vendas_debito_banco_3='$vendas_debito_banco_3', 
							vendas_debito_ag_3='$vendas_debito_ag_3', 
							vendas_debito_cc_3='$vendas_debito_cc_3', 
							vendas_banco='$vendas_banco', 
							vendas_status='$vendas_status', 
							vendas_alteracao='$vendas_alteracao', 
							vendas_telefone='$vendas_telefone', 
							vendas_telefone2='$vendas_telefone2', 
							vendas_user='$vendas_user' 
							WHERE vendas_id='$vendas_id' ") or die(mysql_error());
						echo "Venda Atualizada com Sucesso";
					}
					if ($_GET["cliente_cargo_cod"]){
						$query = mysql_query("UPDATE sys_inss_clientes SET cliente_cargo_cod='".$_GET['cliente_cargo_cod']."' 
						WHERE cliente_cpf='".$row_old['cliente_cpf']."';") or die(mysql_error());
						echo "<br>Cargo do cliente Atualizado com Sucesso<br>";
					}

					$sql = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, 
					`vendas_id`, 
					`registro_usuario`, 
					`registro_obs`, 
					`registro_status`, 
					`registro_data`, 
					`registro_contrato_fisico`) 
					VALUES (NULL, 
					'$vendas_id',
					'$vendas_user',
					'$vendas_obs',
					'$vendas_status',
					'$vendas_alteracao',
					'$vendas_contrato_fisico');"; 

					if (mysql_query($sql,$con)){
						$acionamento_id = mysql_insert_id();
						echo "Histórico Registrado com Sucesso. </br>";
					} else {
						die('Error: ' . mysql_error());
					}
					?>
					<br>
					<?php if(((!$retornoJson->ttError->error)&&(!$err)&&($userid != 42))||($vendas_banco != 7)||($vendas_pgto == 3)): ?>
						<meta http-equiv="Refresh" content="2; url=<?php echo $_SERVER['HTTP_REFERER']; if($_GET["salvar"] == "salvar_fechar"){echo "&fechar=1";}?>">
						<table width="100%" height="99%" border="0" align="center" cellpadding="0" cellspacing="2" bgcolor="#eeeee0">
							<div align="center">
							</br>
							<img src="sistema/imagens/calculando.gif">
						</br>
						<strong> SALVANDO VENDA! </strong></br>
						<br/>
					<?php else: ?>	
						<a href="<?php echo $_SERVER['HTTP_REFERER']; if($_GET["salvar"] == "salvar_fechar"){echo "&fechar=1";}?>"><button class="button validate png" type="button">Prosseguir</button></a>
					<?php endif;?>
				</div>
			<?php endif;?>
			<?php endif;?>			