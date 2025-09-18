<?php // DADOS DA PROPOSTA sistema seguros ?>
<div class="linha">
	<h3 class="mypets2">Dados da Proposta:</h3>
	<div class="thepet2">
		<div class="linha">
			<div class="coluna campo-titulo">Número da Proposta:</div>
			<div class="coluna campo-valor"><input type="text" name="vendas_proposta" value="<?php echo $row['vendas_proposta'];?>" size="15"<?php if ($edicao == 1){echo " onKeyPress=\"return SomenteNumero(event)\"";}else{echo " readonly='true'";}?>/></div>
			<?php if(($row['vendas_tipo_contrato'] == "2")||($row['vendas_tipo_contrato'] == "3")||($row['vendas_tipo_contrato'] == "4")||($row['vendas_tipo_contrato'] == "5")||($row['vendas_tipo_contrato'] == "12")||($row['vendas_tipo_contrato'] == "13")||($row['vendas_tipo_contrato'] == "14")||($row['vendas_tipo_contrato'] == "15")||($row['vendas_tipo_contrato'] == "20")): ?>
				<div class="coluna campo-titulo">Número da Portabilidade:</div>
				<div class="coluna campo-valor"><input type="text" name="vendas_portabilidade" value="<?php echo $row['vendas_portabilidade'];?>" size="15"<?php if ($edicao == 1){echo " onKeyPress=\"return SomenteNumero(event)\"";}else{echo " readonly='true'";}?>/></div>
				<?php if($row['vendas_tipo_contrato'] == "14"): ?>

					<div class="coluna campo-titulo">Número da Portabilidade 2:</div>
					<div class="coluna campo-valor"><input type="text" name="vendas_portabilidade_2" value="<?php echo $row['vendas_portabilidade_2'];?>" size="15"<?php if ($edicao == 1){echo " onKeyPress=\"return SomenteNumero(event)\"";}else{echo " readonly='true'";}?>/></div>
					<div class="coluna campo-titulo">Número da Portabilidade 3:</div>
					<div class="coluna campo-valor"><input type="text" name="vendas_portabilidade_3" value="<?php echo $row['vendas_portabilidade_3'];?>" size="15"<?php if ($edicao == 1){echo " onKeyPress=\"return SomenteNumero(event)\"";}else{echo " readonly='true'";}?>/></div>

				<?php endif;?>		
			<?php endif;?>
			<div class="coluna campo-titulo">Produto:</div>
			<div class="coluna campo-valor">
			<?php if($edicao == 1): ?>
				<select name="vendas_produto" >
				<?php 
					$result_produtos = mysql_query("SELECT * FROM sys_vendas_produtos;") or die(mysql_error());
					while($row_produto = mysql_fetch_array( $result_produtos )) {
						if ($row_produto["produto_id"] == $row['vendas_produto']){$selected = "selected";}else{$selected = "";}
						echo "<option value='".$row_produto['produto_id']."'{$selected}>".$row_produto['produto_nome']."</option>";
					}
				?>
				</select>
			<?php else: ?>
				<?php 
					$result_produtos = mysql_query("SELECT produto_nome FROM sys_vendas_produtos WHERE produto_id = '".$row['vendas_produto']."';") 
					or die(mysql_error());
					$row_produtos = mysql_fetch_array( $result_produtos );
					echo $row_produtos["produto_nome"];
				?>
			<?php endif; ?>
			</div>
		</div>
		<div class="linha">
			<?php if (($edicao == 1) && ($row['vendas_status'] != "9")): ?>
				<?php if ($row['vendas_tipo_contrato'] != "6"): ?>
					<?php //MARCACAO #################################################################?>
					<div id="retornoAjax" onchange="consultaAjaxDadosProposta();">
						<div class="linha">
							<div class="coluna campo-titulo">Banco:</div>
							<div class="coluna campo-valor">
								<select name="vendas_banco">
								<option value="<?php echo $row['vendas_banco'];?>"><?php echo $row['vendas_banco'];?></option>
								<?php 
									$result_banks = mysql_query("SELECT * FROM sys_vendas_bancos ORDER BY vendas_bancos_nome;") 
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
									AND tabela_ativa = '1';")
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
									AND tabela_ativa = '1';")
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
					<div id="retornoAjax" onchange="consultaAjaxDadosProposta();">
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
									$result_tabela_atual = mysql_query("SELECT tabela_id, tabela_nome, tabela_prazo, tabela_tipo FROM sys_vendas_tabelas WHERE tabela_id = '".$row['vendas_tabela']."';")
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
				<div id="retornoAjax" onchange="consultaAjaxDadosProposta();">
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
							<input name="vendas_percelas" type="hidden" value="<?php echo $row['vendas_percelas']; ?>" />
						<?php endif;?>
						<div class="coluna campo-titulo">Tabela:</div>
						<div class="coluna campo-valor">
							<?php
								$result_tabela_atual = mysql_query("SELECT tabela_id, tabela_nome, tabela_prazo, tabela_tipo FROM sys_vendas_tabelas WHERE tabela_id = '".$row['vendas_tabela']."';")
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
			<div class="coluna campo-titulo">AF. Valor do Contrato: R$ </div>
			<div class="coluna campo-valor">
				<?php $valor_venda = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '' ;?>
				<input type="text" name="vendas_valor" value="<?php echo $valor_venda;?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return(MascaraMoeda(this,'.',',',event))'";}else{echo " readonly='true'";}?>/>
			</div>
			<div class="coluna campo-titulo">AF. Valor da Parcela: R$ </div>
			<div class="coluna campo-valor">
				<?php $vendas_valor_parcela = ($row['vendas_valor_parcela']>0) ? number_format($row['vendas_valor_parcela'], 2, ',', '.') : '' ;?>
				<input type="text" name="vendas_valor_parcela" value="<?php echo $vendas_valor_parcela;?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return(MascaraMoeda(this,'.',',',event))'";}else{echo " readonly='true'";}?>/>
			</div>
			<div class="coluna campo-titulo">Margem: R$ </div>
			<div class="coluna campo-valor">
				<?php $vendas_margem = ($row['vendas_margem']!=0) ? number_format($row['vendas_margem'], 2, ',', '.') : '' ;?>
				<input type="text" name="vendas_margem" value="<?php echo $vendas_margem;?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return(MascaraMoeda(this,'.',',',event))'";}else{echo " readonly='true'";}?>/>
			</div>
			<div class="coluna campo-titulo">Líquido: R$ </div>
			<div class="coluna campo-valor">
				<?php $vendas_liquido = ($row['vendas_liquido']>0) ? number_format($row['vendas_liquido'], 2, ',', '.') : '' ;?>
				<input type="text" name="vendas_liquido" value="<?php echo $vendas_liquido;?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return(MascaraMoeda(this,'.',',',event))'";}else{echo " readonly='true'";}?>/>
			</div>
			<?php if ($row['vendas_tipo_contrato'] != "6"): ?>
				<div class="coluna campo-titulo">Coeficiente:</div>
				<div class="coluna campo-valor"><input type="text" name="vendas_coeficiente" value="<?php echo $row['vendas_coeficiente'];?>" size="15"<?php if ($edicao == 0){echo " readonly='true'";}?>/></div>
			<?php endif;?>
		</div>
		<div class="linha">
			<div class="coluna campo-titulo">Status:</div>
			<div class="coluna campo-valor">
				<?php echo $vendas_status_nm;?><br> 
				<img src='sistema/imagens/status_<?php echo $row['vendas_status'];?>.png' style="float: left; margin-right: 10px;"> 
				<?php if ($row['vendas_status'] == "100"){echo " <span style='color: red;'>Venda ainda NÃO enviada para implantação!<br />Preencha corretamente a Ficha Cadastral e os dados na venda para que ela siga para Implantação!</span>";} ?>
			</div>
			
			<div class="coluna campo-titulo">Origem da Venda:</div>
			<div class="coluna campo-valor">
				<select name="vendas_origem">
				<optgroup label="Origem da Venda">		
					<?php
					if (!$row['vendas_origem']) {echo "<option value='' disabled selected>------ SELECIONE ------</option>";}
					$result_origem = mysql_query("SELECT * FROM sys_vendas_origens ORDER BY origem_nome;")
					or die(mysql_error());
					while($row_origem = mysql_fetch_array( $result_origem )) {
					if ($row_origem["origem_id"] == $row['vendas_origem']){$selected_origem = " selected";}else{$selected_origem = "";}
					echo "<option value='{$row_origem['origem_id']}'{$selected_origem}>{$row_origem['origem_nome']}</option>";
					}
					?>
				</optgroup>
				</select>	
			</div>
		</div>
		<div class="linha">
			<?php if ((($sup_operacional == 1)||($row["vendas_consultor"] == $userid)||($supervisor_equipe_vendas == 1)||($operacional_fisico==1))&&(!$consultor_mei)) :?>
				<?php $vendas_fortcoins = ($row['vendas_fortcoins']>0) ? "BS¢ ".number_format($row['vendas_fortcoins'], 2, ',', '.') : 'Aguardando cálculo.' ; ?>
				<div class="coluna campo-titulo">$% Consultor:</div>
				<div class="coluna campo-valor">
					<img class="css_pointer" alt="Visualizar." title="Visualizar." src='templates/gk_music/images/search_shadow.png' onclick="document.getElementById('ver_comissao').style.display = 'inline-block'; this.style.display = 'none';" />
					<strong id="ver_comissao" style="display: none;" ><?php echo $vendas_fortcoins;?></strong>
				</div>
			<?php endif;?>
		</div>
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
                  <input type="hidden" name="vendas_estoque" value="<?php echo $row['vendas_estoque']; ?>">
                <?php endif;?>
			</div>
		</div>
		<div class="linha">
			<div class="coluna campo-titulo">Liberação de Margem:</div>	
			<div class="coluna campo-valor">
				<?php if ($edicao == 1): ?>	
				<select name="vendas_jud">
				  <option value="1" <?php if ($row['vendas_jud'] == "1"){echo "selected";}?>>Normal</option>
				  <option value="2" <?php if ($row['vendas_jud'] == "2"){echo "selected";}?>>Via Jurídico</option>
				</select>
				<?php else:?>
					<strong><?php if ($row['vendas_jud'] == "1"){echo "Normal";}else{echo "Via Jurídico";}?></strong>
					<input type="hidden" name="vendas_jud" value="<?php echo $row['vendas_jud']; ?>">
				<?php endif;?>
			</div>
		</div>
	</div>
</div>