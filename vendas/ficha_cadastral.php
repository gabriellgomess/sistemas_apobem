<?php
$user =& JFactory::getUser();
$username=$user->username;
$userid=$user->id;
$vendas_id=$_GET["vendas_id"];
$prec=$_GET["prec"];
$dorm=$_GET["dorm"];
$administracao = 0;
$franquiado = 0;
?>
<?php
$result = mysql_query("SELECT * FROM sys_vendas WHERE sys_vendas.vendas_id = '" . $vendas_id . "';") 
or die(mysql_error());  
$row = mysql_fetch_array( $result );
?>

<?php
if ($_GET["bloquear"] == "bloquear"){
$row["vendas_status"] = 14;
$query = mysql_query("UPDATE sys_vendas SET vendas_status='14', vendas_user='".$username."' WHERE vendas_id='$vendas_id' ") or die(mysql_error());
echo "Venda colocada em BackOffice Sucesso!<br/>";
}

$result_venda_cliente = mysql_query("SELECT COUNT(venda_cliente_id) AS cliente FROM sys_vendas_clientes WHERE vendas_id = '" . $vendas_id . "';") or die(mysql_error());  
$row_venda_cliente = mysql_fetch_array( $result_venda_cliente );

if ($row_venda_cliente["cliente"]){
	$result_client = mysql_query("SELECT *, (YEAR(CURDATE()) - YEAR(cliente_nascimento)) - (RIGHT(CURDATE(),5) < RIGHT(cliente_nascimento,5)) AS age FROM sys_vendas_clientes WHERE vendas_id = '" . $vendas_id . "';") or die(mysql_error());  
	$row_client = mysql_fetch_array( $result_client );
}else{
	$result_client = mysql_query("SELECT *, (YEAR(CURDATE()) - YEAR(cliente_nascimento)) - (RIGHT(CURDATE(),5) < RIGHT(cliente_nascimento,5)) AS age 
	FROM sys_inss_clientes WHERE cliente_cpf = '" . $row['clients_cpf'] . "';") 
	or die(mysql_error());  
	$row_client = mysql_fetch_array( $result_client );
	if (!$row_client["cliente_nome"]){
		$result_client = mysql_query("SELECT 
		(YEAR(CURDATE()) - YEAR(clients_birth)) - (RIGHT(CURDATE(),5) < RIGHT(clients_birth,5)) AS age, 
		clients_nm AS cliente_nome, 
		clients_birth AS cliente_nascimento, 
		clients_rg AS cliente_rg, 
		clients_street_complet AS cliente_endereco, 
		clients_district AS cliente_bairro, 
		clients_city AS cliente_cidade, 
		clients_postalcode AS cliente_cep, 
		clients_state AS cliente_uf, 
		clients_contact_phone1 AS cliente_telefone, 
		clients_contact_phone2 AS cliente_celular, 
		clients_bank_ag AS cliente_agencia, 
		clients_bank_account AS cliente_conta, 
		clients_idt_margem
		FROM sys_clients WHERE clients_cpf = '" . $row['clients_cpf'] . "';") 
		or die(mysql_error());  
		$row_client = mysql_fetch_array( $result_client );
	}
}

include("sistema/utf8.php");
$result_user = mysql_query("SELECT username, name, situacao, nivel, unidade, equipe_nome FROM jos_users 
LEFT JOIN sys_equipes ON jos_users.equipe_id = sys_equipes.equipe_id 
WHERE id = '" . $row['vendas_consultor'] . "';") 
or die(mysql_error());
$row_user = mysql_fetch_array( $result_user );

$result_user_nivel = mysql_query("SELECT nivel, unidade FROM jos_users WHERE id = '" . $userid . "';") 
or die(mysql_error());
$row_user_nivel = mysql_fetch_array( $result_user_nivel );

$result_grupo = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $row['vendas_consultor'] . ";") 
or die(mysql_error());

$result_grupo_user = mysql_query("SELECT * FROM jos_user_usergroup_map INNER JOIN jos_usergroups ON jos_user_usergroup_map.group_id = jos_usergroups.id WHERE user_id = " . $userid . ";") 
or die(mysql_error());
while($row_grupo_user = mysql_fetch_array( $result_grupo_user )){
	if ($row_grupo_user['id'] == '10'){$administracao = 1;}
	if ($row_grupo_user['id'] == '18'){$diretoria = 1;}
	if ($row_grupo_user['id'] == '19'){$financeiro = 1;}
	if ($row_grupo_user['id'] == '21'){$franquiado = 1;}
	if ($row_grupo_user['id'] == '11'){$sup_operacional = 1;}
	if ($row_grupo_user['id'] == '12'){$sup_vendas = 1;}
	if ($row_grupo_user['id'] == '37'){$supervisor_equipe_vendas = 1;}
	if ($row_grupo_user['id'] == '58'){$coordenador_plataformas = 1; $administracao = 0; $supervisor_equipe_vendas = 1;}
	if ($row_grupo_user['id'] == '66'){$gerente_comercial_agentes = 1;}
	if ($row_grupo_user['id'] == '68'){$representante_comercial_seguros = 1;}
	if ($row_grupo_user['id'] == '79'){$supervisor_comercial_agentes = 1;}
	if ($row_grupo_user['id'] == '73'){$gerente_regional = 1;}
}
if ($administracao == 1){$select_permissao_tabela = "";}else{$select_permissao_tabela = "AND tabela_permissao = '1' ";}
if (($row_user_nivel["nivel"] == "6")&&($row_user_nivel["unidade"] == $row_user["unidade"])){$sup_operacional = 1; $administracao = 1;}
if (($row_user_nivel["nivel"] == "7")&&($row_user_nivel["unidade"] == $row_user["unidade"])){$administracao = 1;}


$result_anexos = mysql_query("SELECT * FROM sys_vendas_anexos WHERE vendas_id = " . $row['vendas_id'] . ";") 
or die(mysql_error());

if ($administracao == 0){$select_registro_restrito = " AND registro_restrito = '0'";}else{$select_registro_restrito = "";}
$result_registros = mysql_query("SELECT * FROM sys_vendas_registros WHERE vendas_id = '" . $vendas_id . "'".$select_registro_restrito." ORDER BY registro_data DESC;")
or die(mysql_error());

if ($row['vendas_tipo_contrato'] != "6"){
	$result_compras = mysql_query("SELECT * FROM sys_vendas_compras WHERE vendas_id = " . $row['vendas_id'] . ";") 
	or die(mysql_error());
}

$result_status_nm = mysql_query("SELECT status_nm, status_liberado, status_proximo FROM sys_vendas_status WHERE status_id = " . $row['vendas_status'] . ";")
or die(mysql_error());
$row_status_nm = mysql_fetch_array( $result_status_nm );
$vendas_status_nm = $row_status_nm["status_nm"];
$vendas_status_proximo = $row_status_nm["status_proximo"];

//$perc_base = ($row['vendas_base_contrato'] / $row['vendas_valor']) * 100;
if ($row['vendas_juros'] >= 10){
	$vendas_base = "1";
	$vendas_base_prod = $row['vendas_base_contrato'];
}else{
	$vendas_base = "2";
	if ($row['vendas_base_prod'] > 0){$vendas_base_prod = $row['vendas_base_prod'];}else{
	$aux_base = ($row['vendas_valor'] * $row['vendas_juros']) / 100;
	$vendas_base_prod = $aux_base * 10;	
	}
}
$vendas_base_prod_rs = ($row['vendas_base_prod']>0) ? number_format($row['vendas_base_prod'], 2, ',', '.') : '0' ;
?>
<?php if (		  
		  ( ($userid != $row["vendas_consultor"])&&
		  	($administracao != 1)&&
		  	($row_user_nivel["nivel"] != "5")&&
		  	($row_user_nivel["nivel"] != "6")&&
		  	($row_user_nivel["nivel"] != "7")&&
		  	($supervisor_equipe_vendas != 1)&&
			($gerente_regional != 1)&&
			($supervisor_comercial_agentes != 1)&&
			($gerente_comercial_agentes != 1)&&
			($coordenador_plataformas != 1)&&
			($pos_venda != 1)
			)
		):
?>
<div align="center">
	VOCÊ NÃO POSSUI ACESSO A ESTA PÁGINA! </br>
	Entre em contato com a sua supervisão, para solicitar este acesso.
</div>
<?php else: ?> 
<style>
table { 
    border-collapse: collapse; 
}
tr { 
    border-bottom:1pt solid #ccc;
}
</style>
<form action="index.php" method="GET">
					<input name="option" type="hidden" id="option" value="com_k2" />
					<input name="view" type="hidden" id="view" value="item" />
					<input name="id" type="hidden" id="id" value="64" />
					<input name="Itemid" type="hidden" id="Itemid" value="398" />
					<input name="username" type="hidden" id="username" value="<?php echo $username; ?>" />
					<input name="vendas_id" type="hidden" id="vendas_id" value="<?php echo $row["vendas_id"]; ?>" />
					<input name="user_situacao" type="hidden" id="user_situacao" value="<?php echo $row_user["situacao"]; ?>" />
					<input name="user_nivel" type="hidden" id="user_nivel" value="<?php echo $row_user["nivel"]; ?>" />
					<input name="vendas_status_old" type="hidden" id="vendas_status_old" value="<?php echo $row['vendas_status']; ?>" />
<div align="center">
	<style type="text/css">
		table td{
		    padding: 2px 5px;
		}
		h2{
			font-size: 18px !important;
    		line-height: 0 !important;
		}
		h3{
			margin: 2px;
			text-align: center;
			text-transform: uppercase;
		}
		#gkPrintTop {		
		    padding: 5px 0;
		}
		@media print {

		}
	</style>
    <table width="100%" height="99%" align="center" cellpadding="2" cellspacing="2"> 
		<tr>
			<td id="t1">
				<div align="right">Código da Venda:</div>
			</td>
			<td id="t2">
				<div align="left" style="float: left;">
					<strong>
						<?php echo $row["vendas_id"];?>
					</strong>
				</div>				
			</td>
			<?php $vendas_dia_imp = implode(preg_match("~\/~", $row['vendas_dia_imp']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_imp']) == 0 ? "-" : "/", $row['vendas_dia_imp'])));?>
			<td id="t4">
				<div align="right">Implantação:</div>
			</td>
			<td>
				<div align="left">
					<strong>
						<?php if($vendas_dia_imp){echo $vendas_dia_imp;}else{echo "____/____/_______";}?>
					</strong>
				</div>
			</td>			
		</tr>
		<tr>
			<td>
				<div align="right" style="float: right;">Nº da Proposta:</div>
			</td>
			<td id="t3" colspan="3">
				<div align="left" style="float: left;">
					<strong>
						<?php echo $row['vendas_proposta'];?>
					</strong>
				</div>
			</td>
		</tr>
		<tr><td colspan="4"><div align="left"><h3>Dados do Cliente:</h3></div></td></tr>
		<tr>
			<td><div align="right">Nome:</div></td>
			<td colspan="3"><div align="left"><strong><?php echo $row_client["cliente_nome"];?></strong></td>
		</tr>
		<tr>
			<td><div align="right">CPF:</div></td>
			<td><div align="left"><strong><?php echo $row["clients_cpf"];?></strong></div></td>
			<td><div align="right">RG:</div></td>
			<?php $cliente_rg_dt = implode(preg_match("~\/~", $row_client['cliente_rg_dt']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row_client['cliente_rg_dt']) == 0 ? "-" : "/", $row_client['cliente_rg_dt']))); ?>
			<td><div align="left"><strong><?php echo $row_client["cliente_rg"];?></strong> data: <strong><?php echo $cliente_rg_dt;?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Órgão Expedidor:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_rg_exp"];?></strong></div></td>
			<td><div align="right">Orgão:</div></td>
			<td><div align="left"><strong><?php echo $row["vendas_orgao"];?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Nascimento:</div></td>
			<?php $data_nascimento = implode(preg_match("~\/~", $row_client['cliente_nascimento']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row_client['cliente_nascimento']) == 0 ? "-" : "/", $row_client['cliente_nascimento']))); ?>	
			<td><div align="left"><strong><?php echo $data_nascimento; ?></strong></div></td>
			<td><div align="right">Idade:</div></td>
			<td><div align="left"><strong><?php echo $row_client["age"];?> anos</strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Naturalidade:</div></td>	
			<td><div align="left"><strong><?php echo $row_client["cliente_naturalidade"];?></strong></div></td>
			<td><div align="right">Estado Civil:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_est_civil"];?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Nome do Pai:</div></td>
			<td colspan="3"><div align="left"><strong><?php echo $row_client["cliente_pai"];?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Nome da Mãe:</div></td>
			<td colspan="3"><div align="left"><strong><?php echo $row_client["cliente_mae"];?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Endereço:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_endereco"];?></strong></div></td>

			<td><div align="right">Número:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_endereco_numero"];?></strong></div></td>
		</tr>		
		<tr>
			<td><div align="right">Complemento:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_complemento"];?></strong></div></td>

			<td><div align="right">Bairro:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_bairro"];?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Cidade / Estado:</div></td>
			<td colspan="3"><div align="left"><strong><?php echo $row_client["cliente_cidade"];?> - <?php echo $row_client["cliente_uf"];?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">CEP:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_cep"];?></strong></div></td>
			<td><div align="right">Telefone / Celular:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_telefone"];?> / <?php echo $row_client["cliente_celular"];?></strong></div></td>
		</tr>

		<tr>
			<td><div align="right">Email:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_email"];?></strong></div></td>
		</tr>
		
		<tr>
			<?php $cliente_salario = ($row_client['cliente_salario']>0) ? number_format($row_client['cliente_salario'], 2, ',', '.') : '0' ;?>
			<td><div align="right">Salário Bruto:</div></td>
			<td><div align="left"><strong>R$ <?php echo $cliente_salario;?></strong></div></td>
			<td><div align="right">Sexo:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_sexo"];?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Matrícula:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_beneficio"];?></strong> Espécie: <strong><?php echo $row_client["cliente_beneficio_especie"];?></div></td>
			<td><div align="right">Banco: <strong><?php echo $row_client["cliente_banco"];?> &nbsp; </strong></div></td>
			<td><div align="left">Agência: <strong><?php echo $row_client["cliente_agencia"]; if($row_client["cliente_agencia_digito"]){echo " - ".$row_client["cliente_agencia_digito"];}?></strong><br />Conta: <strong><?php echo $row_client["cliente_conta"];?> - <?php echo $row_client["cliente_conta_digito"];?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Senha:</div></td>
			<td colspan="3"><div align="left"><strong><?php echo $row_client["cliente_senha"];?></strong></div></td>
		</tr>
		<tr><td colspan="4"><div align="left"><h3>Meu INSS:</h3></div></td></tr>
		<tr>
			<td><div align="right">Login:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_login_inss"];?></strong></div></td>
			<td><div align="right">Senha:</div></td>
			<td><div align="left"><strong><?php echo $row_client["cliente_senha_inss"];?></strong></div></td>
		</tr>
		<tr><td colspan="4"><div align="left"><h3>Dados da Proposta:</h3></div></td></tr>
	<?php if($row['vendas_tipo_contrato'] == "3"): ?>
		<tr>
			<td><div align="right">Nº Portabilidade:</div></td>
			<td><div align="left"><strong><?php echo $row['vendas_portabilidade'];?></strong></div></td>
		</tr>
<!--		<tr>
			<td><div align="right">Taxa da Portabilidade:</div></td>
			<td><div align="left"><strong><?php echo $row['vendas_portabilidade_taxa'];?></strong></div></td>
		</tr>-->
	<?php endif;?>
		<tr>
			<td><div align="right">Produto:</td>
			<td><strong>
	<?php 
				$result_produtos = mysql_query("SELECT produto_nome FROM sys_vendas_produtos WHERE produto_id = '".$row['vendas_produto']."';") 
				or die(mysql_error());
				$row_produtos = mysql_fetch_array( $result_produtos );
				echo $row_produtos["produto_nome"];
	?>		
				</strong>
			</td>
			<td><div align="right">Banco:</div></td>
			<td><div align="left"><strong><?php echo $row['vendas_banco'];?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Tipo de Contrato:</div></td>
			<td><div align="left"><strong>
	<?php 
				$result_tipos = mysql_query("SELECT tipo_nome FROM sys_vendas_tipos WHERE tipo_id = '".$row['vendas_tipo_contrato']."';") 
				or die(mysql_error());
				$row_tipos = mysql_fetch_array( $result_tipos );
				echo $row_tipos["tipo_nome"];
	?>		
			</strong></div></td>
			<td><div align="right">Prazo:</div></td>
			<td><div align="left"><strong><?php echo $row['vendas_percelas']; ?> X</strong></div></td>
		</tr>
		<?php
			$result_tabela_atual = mysql_query("SELECT tabela_nome, tabela_prazo, tabela_tipo FROM sys_vendas_tabelas WHERE tabela_id = '".$row['vendas_tabela']."';")
			or die(mysql_error());
			$row_tabela_atual = mysql_fetch_array( $result_tabela_atual );
		?>
		<tr>
			<td><div align="right">Tabela:</div></td>
			<td><div align="left"><strong><?php echo $row_tabela_atual['tabela_nome']; ?></strong></div></td>
			<?php $valor_venda = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '' ;?>
			<td><div align="right">AF. Valor do Contrato:</div></td>
			<td><div align="left"><strong>R$ <?php echo $valor_venda;?></strong></div></td>
		</tr>
		<tr><?php $vendas_valor_parcela = ($row['vendas_valor_parcela']>0) ? number_format($row['vendas_valor_parcela'], 2, ',', '.') : '' ;?>
			<td><div align="right">Valor da Parcela:</div></td>
			<td><div align="left"><strong>R$ <?php echo $vendas_valor_parcela;?></strong></div></td>
			<?php $vendas_margem = ($row['vendas_margem']!=0) ? number_format($row['vendas_margem'], 2, ',', '.') : '' ;?>
			<td><div align="right">Margem:</div></td>
			<td><div align="left"><strong>R$ <?php echo $vendas_margem;?></strong></div></td>
		</tr>
		<tr><?php $vendas_liquido = ($row['vendas_liquido']>0) ? number_format($row['vendas_liquido'], 2, ',', '.') : '' ;?>
			<td><div align="right">Líquido:</div></td>
			<td <?php if ($row['vendas_tipo_contrato'] == "6"){ echo "colspan='3'"; } ?>><div align="left"><strong>R$ <?php echo $vendas_liquido;?></strong></div></td>
<?php if ($row['vendas_tipo_contrato'] != "6"): ?>
			<td><div align="right">Coeficiente:</div></td>
			<td><div align="left"><strong><?php echo $row['vendas_coeficiente'];?></strong></div></td>
<?php endif;?>
		</tr>
		<tr>
			<td><div align="right">Data da Venda:</div></td>
			<td><div align="left">
				<strong><?php 
			$yr=strval(substr($row["vendas_dia_venda"],0,4));
			$mo=strval(substr($row["vendas_dia_venda"],5,2));
			$da=strval(substr($row["vendas_dia_venda"],8,2));
			$hr=strval(substr($row["vendas_dia_venda"],11,2));
			$mi=strval(substr($row["vendas_dia_venda"],14,2));
			$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));			
				echo $data_venda;?></strong>
				</div>
			</td>
			<td><div align="right">Dados do Consultor:</div></td>		
			<td><div align="left"><strong><?php echo $row_user['name'];?></strong> (<?php echo $row_user['username'];?>)</div></td>	
		</tr>
		<tr>
			<td><div align="right">Unidade:</div></td>	
			<td><div align="left"><strong><?php echo $row_user['unidade'];?></strong></div></td>
			<td><div align="right">Equipe Atual:</div></td>	
			<td><div align="left"><strong><?php echo $row_user['equipe_nome'];?></strong></div></td>
		</tr>
		<tr>
			<td><div align="right">Registro de Abertura:</div></td>
			<td colspan="3"><div align="left"><strong><?php echo $row["vendas_obs"]; ?></strong></div></td>		
		</tr>
	</table>
</td>
</tr>
<?php if ($row['vendas_tipo_contrato'] != "6"): ?>
<tr>
<td><div align="left"><h3>Compra de Dívida:</h3></div>
	<table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
			<tr>
				<td width="25%"><div align="left"><strong>Banco:<br />nº do contrato:</strong></div></td>
				<td width="22%"><div align="left"><strong>Valor da Parcela:<br />saldo devedor:</strong></div></td>
				<td width="22%"><div align="left"><strong>Prazo do Contrato:<br />parcelas em aberto:</strong></div></td>
				<td width="22%"><div align="left"><strong>Vencimento:</strong></div></td>
				<td width="5%"><div align="right"></div></td>
			</tr>
			<tr>
				<td colspan="5">
				<div class="scroller_calendar">
						<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
							<tbody>
				<?php
							while($row_compras = mysql_fetch_array( $result_compras )) {
								echo "<tr class='even'>";
				$total_parcelas = $total_parcelas + $row_compras['compra_valor'];
				$total_saldos = $total_saldos + $row_compras['compra_saldo'];
				if ($row_compras['compra_banco']){
					$result_banco_compra = mysql_query("SELECT banco_codigo,banco_nome FROM sys_vendas_bancos_compra WHERE banco_id = " . $row_compras['compra_banco'] . ";")
					or die(mysql_error());
					$row_banco_compra = mysql_fetch_array( $result_banco_compra );
					$compra_banco = $row_banco_compra['banco_codigo']." - ".$row_banco_compra['banco_nome'];
				}else {$compra_banco = $row_compras['compra_banco_txt'];}
								echo "<td width='25%'><div align='left'><span style='font-size:8pt;'>{$compra_banco}<br />{$row_compras['compra_contrato']}</span></div></td>";
				$compra_valor = ($row_compras['compra_valor']<>0) ? 'R$ '.number_format($row_compras['compra_valor'], 2, ',', '.') : 'Não informado' ;
				$compra_saldo = ($row_compras['compra_saldo']<>0) ? 'R$ '.number_format($row_compras['compra_saldo'], 2, ',', '.') : 'Não informado' ;
								echo "<td width='22%'><div align='left'><span style='font-size:8pt;'>{$compra_valor}<br />{$compra_saldo}</span></div></td>";
								echo "<td width='22%'><div align='left'><span style='font-size:8pt;'>{$row_compras['compra_prazo']}<br />{$row_compras['compra_parcelas']}</span></div></td>";
				$compra_venc = implode(preg_match("~\/~", $row_compras['compra_venc']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row_compras['compra_venc']) == 0 ? "-" : "/", $row_compras['compra_venc'])));
								echo "<td width='22%'><div align='left'><span style='font-size:8pt;'>{$compra_venc}</span></div></td>";
								echo "<td width='5%'><div align='right'>";
								if ($edicao == 1){
									echo "<a title='EXCLUIR DÍVIDA Nº: {$row_compras['compra_id']}' href='index.php?option=com_k2&view=item&id=182:excluir-divida&Itemid=123&tmpl=component&print=1&compra_id={$row_compras['compra_id']}&acao=exclui_divida' rel='lyteframe' rev='width: 550px; height: 400px; scroll:no;'><img src='sistema/imagens/delete.png'></a>";
									echo "<a title='EDITAR DÍVIDA Nº: {$row_compras['compra_id']}' href='index.php?option=com_k2&view=item&id=182:editar-divida&Itemid=123&tmpl=component&print=1&compra_id={$row_compras['compra_id']}&acao=edita_divida' rel='lyteframe' rev='width: 650px; height: 500px; scroll:no;'><img src='sistema/imagens/edit.png'></a>";
								}	
								echo "</div></td>";
								echo "</tr>"; 
							}
				$total_parcelas = ($total_parcelas>0) ? "R$ ".number_format($total_parcelas, 2, ',', '.') : '0' ;
				$total_saldos_label = ($total_saldos>0) ? "R$ ".number_format($total_saldos, 2, ',', '.') : '0' ;
				?>
							</tbody>
				</table></div>
				</td>
			</tr>
			<tr>
				<td colspan="2"><div align="right">Total de Parcelas:</div></td>
				<td colspan="3"><div align="left"><strong><?php echo $total_parcelas;?></strong></div></td>
			</tr>
			<tr>
				<td colspan="2"><div align="right">Saldo Devedor Total:</div></td>
				<td colspan="3"><div align="left"><strong><?php echo $total_saldos_label;?></strong></div></td>
				<input type="hidden" name="vendas_portabilidade_saldo" value="<?php echo $total_saldos;?>"/>
			</tr>
	</table>
		</div>
</td>
</tr>
<?php endif;?>
</tbody>
</table>
</div>
</form>
<?php endif; ?>