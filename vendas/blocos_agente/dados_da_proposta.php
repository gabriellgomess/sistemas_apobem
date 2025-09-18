<?php
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

<div class="linha">
	<h3 class="mypets2">Dados da Proposta:</h3>
	<div class="thepet2">

		<div class="linha">
			<div class="coluna campo-titulo">Número da Proposta:</div>
			<div class="coluna campo-valor">
				<input onKeyUp="sonumero(this);" type="text" name="vendas_proposta" value="<?php echo $row['vendas_proposta'];?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return SomenteNumero(event)'";}else{echo " readonly='true'";}?>/>
			</div>
			<?php if(($row['vendas_tipo_contrato'] == "3")||($row['vendas_tipo_contrato'] == "14")||($row['vendas_tipo_contrato'] == "15")): ?>
				<div class="coluna campo-titulo">Número da Portabilidade:</div>
				<div class="coluna campo-valor">
					<input type="text" name="vendas_portabilidade" value="<?php echo $row['vendas_portabilidade'];?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return SomenteNumero(event)'";}else{echo " readonly='true'";}?>/>
				</div>
			<?php endif;?>
		</div>
		<div class="linha">
			<div class="coluna campo-titulo">Produto:</div>
			<div class="coluna campo-valor">
				<select name="vendas_produto">
				<?php 
					$result_produtos = mysql_query("SELECT * FROM sys_vendas_produtos;") or die(mysql_error());
					while($row_produto = mysql_fetch_array( $result_produtos )) {
						if ($row_produto["produto_id"] == $row['vendas_produto']){$selected = "selected";}else{$selected = "";}
						echo "<option value='".$row_produto['produto_id']."'{$selected}>".$row_produto['produto_nome']."</option>";
					}
				?>
				</select>
			</div>
		</div>
		<div class="linha">
			<?php if (($edicao == 1) && ($row['vendas_status'] != "9")): ?>
				<?php if ($row['vendas_tipo_contrato'] != "6"): ?>
					<?php //MARCACAO #################################################################?>
					<div id="retornoAjax" onchange="consultaAjax();">
						<div class="linha">
							<div class="coluna campo-titulo">Banco:</div>
							<div class="coluna campo-valor">
								<select name="vendas_banco">
								<option value="<?php echo $row['vendas_banco'];?>"><?php echo $row['vendas_banco'];?></option>
								<?php 
									$result_banks = mysql_query("SELECT * FROM sys_vendas_bancos WHERE vendas_bancos_employer LIKE '%" . $row['clients_employer'] . "%' AND (vendas_bancos_produtos LIKE '%TODOS%' OR vendas_bancos_produtos LIKE '%" . $row['vendas_produto'] . "%') ORDER BY vendas_bancos_nome;") 
									or die(mysql_error()); 
									while($row_banks = mysql_fetch_array( $result_banks )) {
										echo "<option value='".$row_banks['vendas_bancos_nome']."'>".$row_banks['vendas_bancos_nome']."</option>";
									}
								?>
								</select>
							</div>
							<div class="coluna campo-titulo">Tipo de Contrato:</div>
							<div class="coluna campo-valor">
								<select name="vendas_tipo_contrato">
								<option value="">Selecione o Tipo de Contrato</option>
								<?php
									$result_tipos = mysql_query("SELECT * FROM sys_vendas_tipos;")
									or die(mysql_error());
									while($row_tipos = mysql_fetch_array( $result_tipos )) {
										if ($row_tipos["tipo_id"] == $row["vendas_tipo_contrato"]){$selected = "selected";}else{$selected = "";}
										echo "<option value='{$row_tipos['tipo_id']}'{$selected}>{$row_tipos['tipo_nome']}</option>";
									}
								?>
								</select>
							</div>
						</div>
						
						<div class="linha">
							<div class="coluna campo-titulo">Prazo:</div>
							<div class="coluna campo-valor">
								<select name="vendas_percelas">
								<?php
									echo "<option value='{$row['vendas_percelas']}' selected>{$row['vendas_percelas']} X</option>";
								
									$result_bank = mysql_query("SELECT vendas_bancos_id FROM sys_vendas_bancos WHERE vendas_bancos_nome LIKE '%" . $row['vendas_banco'] . "%';") 
									or die(mysql_error());
									$row_bank = mysql_fetch_array( $result_bank );
									
									$vendas_tipo_contrato = $row["vendas_tipo_contrato"];
									$hoje = date("Y-m-d");
									$result_prazo = mysql_query("SELECT DISTINCT tabela_prazo FROM sys_vendas_tabelas WHERE 
									tabela_banco = '".$row_bank['vendas_bancos_id']."' 
									AND tabela_operacao like '%".$vendas_tipo_contrato."%' 
									AND tabela_orgao like '%".$row['vendas_orgao']."%' 
									AND tabela_vigencia_ini <= '".$hoje."' 
									AND tabela_vigencia_fim >= '".$hoje."' ".$select_permissao_tabela."
									AND tabela_ativa = '1' 
									AND tabela_perfil_venda != 2;")
									or die(mysql_error());
									while($row_prazo = mysql_fetch_array( $result_prazo )) {
										echo "<option value='{$row_prazo['tabela_prazo']}'>-- {$row_prazo['tabela_prazo']} X</option>";
									}
								?>
								</select>
							</div>
							<div class="coluna campo-titulo">Tabela: (cód.: <?php echo $row['vendas_tabela']; ?>)</div>
							<div class="coluna campo-valor">
								<select name="vendas_tabela"<?php if ($edicao == 1){echo " ";}else{echo " readonly='true'";}?>>
								<?php
									$result_tabela_atual = mysql_query("SELECT tabela_nome, tabela_prazo, tabela_tipo FROM sys_vendas_tabelas WHERE tabela_id = '".$row['vendas_tabela']."';")
									or die(mysql_error());
									$row_tabela_atual = mysql_fetch_array( $result_tabela_atual );
									if ($row_tabela_atual['tabela_nome']){
									echo "<option value='{$row['vendas_tabela']}' selected>{$row_tabela_atual['tabela_nome']}. - Tipo: {$row_tabela_atual['tabela_tipo']}</option>";
									}else{echo "<option value='{$row['vendas_tabela']}' selected>{$row['vendas_tabela']} (tabela antiga)</option>";}
								
									$vendas_percelas = $row["vendas_percelas"];
									$dia = date("d");
									$tabela_dia = "tabela_dia_".$dia;
									$result_tabela = mysql_query("SELECT tabela_id, tabela_nome, tabela_prazo, tabela_tipo, ".$tabela_dia." FROM sys_vendas_tabelas WHERE 
									tabela_banco = '".$row_bank['vendas_bancos_id']."' 
									AND tabela_operacao like '%".$vendas_tipo_contrato."%' 
									AND tabela_prazo = '".$vendas_percelas."' 
									AND tabela_orgao like '%".$row['vendas_orgao']."%' 
									AND tabela_vigencia_ini <= '".$hoje."' 
									AND tabela_vigencia_fim >= '".$hoje."' ".$select_permissao_tabela."
									AND tabela_ativa = '1' 
									AND tabela_perfil_venda != 2;")
									or die(mysql_error());
									while($row_tabela = mysql_fetch_array( $result_tabela )) {
										echo "<option value='{$row_tabela['tabela_id']}'>CODIGO({$row_tabela['tabela_id']}) -- {$row_tabela['tabela_nome']}. - Tipo: {$row_tabela['tabela_tipo']}</option>";
									}
								?>
								</select>
							</div>
						</div>
					</div>
				<?php //FIM MARCACAO ##################################################################?>
				<?php else:?>
					<div id="retornoAjax" onchange="consultaAjax();">
						<div class="linha">
							<div class="coluna campo-titulo">Banco:</div>
							<div class="coluna campo-valor">
								<select name="vendas_banco">
									<option value="<?php echo $row['vendas_banco'];?>"><?php echo $row['vendas_banco'];?></option>
									<?php 
									$result_banks = mysql_query("SELECT * FROM sys_vendas_bancos WHERE vendas_bancos_employer LIKE '%" . $row['clients_employer'] . "%' AND (vendas_bancos_produtos LIKE '%TODOS%' OR vendas_bancos_produtos LIKE '%" . $row['vendas_produto'] . "%') ORDER BY vendas_bancos_nome;") 
									or die(mysql_error()); 
									while($row_banks = mysql_fetch_array( $result_banks )) {
									echo "<option value='".$row_banks['vendas_bancos_nome']."'>".$row_banks['vendas_bancos_nome']."</option>";
									}
									?>
								</select>
							</div>
							<div class="coluna campo-titulo">Tipo de Contrato:</div>
							<div class="coluna campo-valor">
								<?php
								$result_tipos = mysql_query("SELECT tipo_nome FROM sys_vendas_tipos WHERE tipo_id = '".$row['vendas_tipo_contrato']."';")
								or die(mysql_error());
								$row_tipos = mysql_fetch_array( $result_tipos );
								echo $row_tipos['tipo_nome'];
								?>
							</div>
						</div>
						<div class="linha">
							<div class="coluna campo-titulo">Tabela:</div>
							<div class="coluna campo-valor">
								<?php
									$result_tabela_atual = mysql_query("SELECT tabela_nome, tabela_prazo, tabela_tipo, tabela_id FROM sys_vendas_tabelas WHERE tabela_id = '".$row['vendas_tabela']."';")
									or die(mysql_error());
									$row_tabela_atual = mysql_fetch_array( $result_tabela_atual );
									if ($row_tabela_atual['tabela_nome']){
										if ($row['vendas_tipo_contrato'] == "6"){echo "<strong>CODIGO({$row_tabela_atual['tabela_id']}) -- {$row_tabela_atual['tabela_nome']}.</strong>";}
										else{echo "<strong>CODIGO({$row_tabela_atual['tabela_id']}) -- {$row_tabela_atual['tabela_nome']}. <br /> Tipo: {$row_tabela_atual['tabela_tipo']}</strong>";}
									}else{echo "<strong>{$row['vendas_tabela']} (tabela antiga)</strong>";}
								?>
								<input name="vendas_tabela" type="hidden" value="<?php echo $row['vendas_tabela']; ?>" />
							</div>
						</div>
					</div>
				<?php endif;?>
			<?php else:?>
				<div id="retornoAjax" onchange="consultaAjax();">
					<div class="linha">
						<div class="coluna campo-titulo">Banco:</div>
						<div class="coluna campo-valor"><?php echo $row['vendas_banco'];?></div>
						<div class="coluna campo-titulo">Tipo de Contrato:</div>
						<div class="coluna campo-valor">
							<?php
							$result_tipos = mysql_query("SELECT tipo_nome FROM sys_vendas_tipos WHERE tipo_id = '".$row['vendas_tipo_contrato']."';")
							or die(mysql_error());
							$row_tipos = mysql_fetch_array( $result_tipos );
							echo $row_tipos['tipo_nome'];
							?>
						</div>
					</div>
					<div class="linha">
						<?php if ($row['vendas_tipo_contrato'] != "6"): ?>
							<div class="coluna campo-titulo">Prazo:</div>
							<div class="coluna campo-valor"><?php echo $row['vendas_percelas']; ?> X </div>
						<?php endif;?>
						<div class="coluna campo-titulo">Tabela:</div>
						<div class="coluna campo-valor">
							<?php
								$result_tabela_atual = mysql_query("SELECT tabela_nome, tabela_prazo, tabela_tipo FROM sys_vendas_tabelas WHERE tabela_id = '".$row['vendas_tabela']."';")
								or die(mysql_error());
								$row_tabela_atual = mysql_fetch_array( $result_tabela_atual );
								if ($row_tabela_atual['tabela_nome']){
									if ($row['vendas_tipo_contrato'] == "6"){echo "<strong>CODIGO({$row_tabela_atual['tabela_id']}) -- {$row_tabela_atual['tabela_nome']}.</strong>";}
									else{echo "<strong>CODIGO({$row_tabela_atual['tabela_id']}) -- {$row_tabela_atual['tabela_nome']}. <br /> Tipo: {$row_tabela_atual['tabela_tipo']}</strong>";}
								}else{echo "<strong>{$row['vendas_tabela']} (tabela antiga)</strong>";}
							?>
							<input name="vendas_tabela" type="hidden" value="<?php echo $row['vendas_tabela']; ?>" />
						</div>
					</div>
				</div>
			<?php endif;?>
		</div>
			<div class="linha">
				<div class="coluna campo-titulo">AF. Valor do Contrato: R$</div>
				<div class="coluna campo-valor">
					<?php $valor_venda = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '' ;?>
					<input type="text" name="vendas_valor" value="<?php echo $valor_venda;?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return(MascaraMoeda(this,'.',',',event))'";}else{echo " readonly='true'";}?>/>
				</div>
				<div class="coluna campo-titulo">Valor da Parcela: R$</div>
				<div class="coluna campo-valor">
					<?php $vendas_valor_parcela = ($row['vendas_valor_parcela']>0) ? number_format($row['vendas_valor_parcela'], 2, ',', '.') : '' ;?>
					<input type="text" name="vendas_valor_parcela" value="<?php echo $vendas_valor_parcela;?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return(MascaraMoeda(this,'.',',',event))'";}else{echo " readonly='true'";}?>/>
				</div>
			</div>

			<div class="linha">
				<div class="coluna campo-titulo">Margem: R$</div>
				<div class="coluna campo-valor">
					<?php $vendas_margem = ($row['vendas_margem']!=0) ? number_format($row['vendas_margem'], 2, ',', '.') : '' ;?>
					<input type="text" name="vendas_margem" value="<?php echo $vendas_margem;?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return(MascaraMoeda(this,'.',',',event))'";}else{echo " readonly='true'";}?>/>
				</div>
				<div class="coluna campo-titulo">Líquido: R$</div>
				<div class="coluna campo-valor">
					<?php $vendas_liquido = ($row['vendas_liquido']>0) ? number_format($row['vendas_liquido'], 2, ',', '.') : '' ;?>
					<input type="text" name="vendas_liquido" value="<?php echo $vendas_liquido;?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return(MascaraMoeda(this,'.',',',event))'";}else{echo " readonly='true'";}?>/>
				</div>
			</div>

			<div class="linha">
		<?php if ($row['vendas_tipo_contrato'] != "6"): ?>
				<div class="coluna campo-titulo">Coeficiente:</div>
				<div class="coluna campo-valor">
					<input type="text" name="vendas_coeficiente" value="<?php echo $row['vendas_coeficiente'];?>" size="15"<?php if ($edicao == 0){echo " readonly='true'";}?>/>
				</div>
		<?php endif;?>
				<div class="coluna campo-titulo">Status:</div>
				<div class="coluna campo-valor">
					<?php echo $vendas_status_nm;?></br>
					<img src='sistema/imagens/status_<?php echo $row['vendas_status'];?>.png'>
				</div>
			</div>

	<?php $vendas_base_contrato = ($row['vendas_base_contrato']>0) ? number_format($row['vendas_base_contrato'], 2, ',', '.') : '0' ;?>
	<?php $vendas_receita_rs = ($row['vendas_receita']) ? number_format($row['vendas_receita'], 2, ',', '.') : '0' ;?>
	<?php if ($sup_operacional == 1) :?>
			<div class="linha" style="text-align: left; padding: 10px;">
				Base do Contrato: <strong><?php if ($vendas_base_contrato > 0){echo "R$ ".$vendas_base_contrato;}else{echo "Aguardando Implantação.";}?></strong><br/>
				<?php 
					$vendas_cms_vendedor_flat = ($row['vendas_cms_vendedor_flat']) ? "R$ ".number_format($row['vendas_cms_vendedor_flat'], 2, ',', '.') : '0' ; 
					$vendas_cms_vendedor_saldo = ($row['vendas_cms_vendedor_saldo']) ? "R$ ".number_format($row['vendas_cms_vendedor_saldo'], 2, ',', '.') : '0' ; 
					$vendas_comissao_vendedor = ($row['vendas_comissao_vendedor']) ? "R$ ".number_format($row['vendas_comissao_vendedor'], 2, ',', '.') : 'Aguardando cálculo.' ; 
					$vendas_comissao_vendedor_perc = ($row['vendas_comissao_vendedor_perc']>0) ? number_format($row['vendas_comissao_vendedor_perc'], 2, ',', '.') : '0' ;
					$vendas_cms_vendedor_saldo_perc = ($row['vendas_cms_vendedor_saldo_perc']>0) ? number_format($row['vendas_cms_vendedor_saldo_perc'], 2, ',', '.') : '0' ;
				?>
				Percentual de Comissão Agente Flat: <strong><?php if($vendas_comissao_vendedor_perc){echo $vendas_comissao_vendedor_perc."%";}else{echo "0%";} ?></strong><br/>
				<?php if ($row["vendas_tipo_contrato"] == 3): ?>Percentual de Comissão Agente Saldo Devedor: <strong><?php if($vendas_cms_vendedor_saldo_perc){echo $vendas_cms_vendedor_saldo_perc."%";}else{echo "0%";} ?></strong><br/><?php endif; ?>
					<?php $vendas_cip_rs = ($row['vendas_cip']>0) ? number_format($row['vendas_cip'], 2, ',', '.') : '0' ;?>
					CIP: <strong>R$ <?php if($vendas_cip_rs){echo $vendas_cip_rs;}else{echo "0,00";}?></strong><br/>
				<?php if ($row["vendas_tipo_contrato"] == 3): ?>
					Comissão Agente sob Flat: <strong><?php echo $vendas_cms_vendedor_flat;?></strong><br/>
					Comissão Agente sob Saldo Devedor: <strong><?php echo $vendas_cms_vendedor_saldo;?></strong><br/>
				<?php endif; ?>
				<?php if (($row['vendas_tipo_contrato'] == "6")||($row['vendas_tipo_contrato'] == "10")): ?>
					<?php $vendas_cms_vendedor_plastico = ($row['vendas_cms_vendedor_plastico']) ? "R$ ".number_format($row['vendas_cms_vendedor_plastico'], 2, ',', '.') : 'Aguardando cálculo.' ; ?>
					Comissão Plástico Agente: <strong><?php if($vendas_cms_vendedor_plastico){echo $vendas_cms_vendedor_plastico;}else{echo "0%";} ?></strong><br/>
					<?php $vendas_cms_vendedor_ativacao = ($row['vendas_cms_vendedor_ativacao']) ? "R$ ".number_format($row['vendas_cms_vendedor_ativacao'], 2, ',', '.') : 'Aguardando cálculo.' ; ?>
					Comissão Ativação Agente: <strong><?php if($vendas_cms_vendedor_ativacao){echo $vendas_cms_vendedor_ativacao;}else{echo "0%";} ?></strong><br/>
				<?php endif; ?>
				Comissão Total do Agente: <strong><?php echo $vendas_comissao_vendedor;?></strong><br/>
			</div>
	<?php endif; ?>
		<div class="linha">
			<?php if ($row["vendas_produto"] == 1) :?>
				<div class="coluna campo-titulo"><?php if ($row["vendas_tipo_contrato"] == 6){echo "Contrato Físico Termo:";}else{echo "Contrato Físico Básico:";} ?></div>
				<div class="coluna campo-valor">
					<?php
					$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos ORDER BY contrato_etapa;")
					or die(mysql_error());
					$largura_fisicos = 50 / (mysql_num_rows( $result_fisicos ) - 1); 
					echo "<div id='container-sbar'>";
					while($row_fisicos = mysql_fetch_array( $result_fisicos )) {
						if ($row_fisicos["contrato_id"] == $row['vendas_contrato_fisico']){echo "<div class='sbar sbar-active' style='background-color: ".$row_fisicos['contrato_cor']."1);' title='Status Físico ".$row_fisicos['contrato_nome']."'>".$row_fisicos['contrato_nome']."</div>";}
						else {echo "<div class='sbar' style='width: ".$largura_fisicos."%;'><div class='sbar-inside'></div></div>";}
					}
					echo "</div>";
					?>
				</div>
				<?php if (($row["vendas_tipo_contrato"] == 3) || ($row["vendas_tipo_contrato"] == 6)) :?>
					<div class="coluna campo-titulo"><?php if ($row["vendas_tipo_contrato"] == 3){echo "Contrato Físico Portabilidade:";}else{echo "Contrato Físico CCB:";} ?></div>
					<div class="coluna campo-valor">
						<?php
						$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos ORDER BY contrato_etapa;")
						or die(mysql_error());
						$largura_fisicos = 50 / (mysql_num_rows( $result_fisicos ) - 1); 
						echo "<div id='container-sbar'>";
						while($row_fisicos = mysql_fetch_array( $result_fisicos )) {
							if ($row_fisicos["contrato_id"] == $row['vendas_contrato_fisico']){echo "<div class='sbar sbar-active' style='background-color: ".$row_fisicos['contrato_cor']."1);' title='Status Físico ".$row_fisicos['contrato_nome']."'>".$row_fisicos['contrato_nome']."</div>";}
							else {echo "<div class='sbar' style='width: ".$largura_fisicos."%;'><div class='sbar-inside'></div></div>";}
						}
						echo "</div>";
						?>
					</div>
				<?php endif;?>
			<?php endif;?>
		</div>

		<div class="linha">
			<div class="coluna campo-titulo">Venda em Estoque:</div>
			<div class="coluna campo-valor">
				<?php if ($edicao == 1): ?>
					<select name="vendas_estoque">
					  <option value="0" <?php if ($row['vendas_estoque'] == "0"){echo "selected";}?>>Não</option>
					  <option value="1" <?php if ($row['vendas_estoque'] == "1"){echo "selected";}?>>Sim</option>
					</select>
				<?php else:?>
					<strong><?php if ($row['vendas_estoque'] == "1"){echo "Sim";}else{echo "Não";}?></strong>
				<?php endif;?>
			</div>
		</div>
