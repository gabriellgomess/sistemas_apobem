<div class="linha">
	<h3 class="mypets2">Gerenciar Venda:</h3>
	<div class="thepet2">
		<div class="linha">Comissões:</div>
		<div class="linha">
			<div align="center" style="text-align: center; font-weight: bold; width: 100%; margin: auto;">
				<table id="comissoes_tbl" width="100%">
					<tr>
						<th>#</th>
						<th>Tipo:</th>
						<th>Valor de Referência:</th>
						<th>CMS %:</th>
						<th>Antecipação:</th>
						<th>CMS R$:</th>
						<th>Impostos:</th>
						<th>Sub-total R$:</th>
					</tr>
					<?php 
					$result_cms_venda = mysql_query("SELECT cms_id, tipo_id, cms_perc, cms_valor, cms_subtotal, cms_obs, cms_tipo_soma, cms_imposto FROM sys_vendas_cms 
					INNER JOIN sys_vendas_cms_tipos ON sys_vendas_cms.tipo_id = sys_vendas_cms_tipos.cms_tipo_id 
					WHERE vendas_id=".$vendas_id.";")
						or die(mysql_error()); 
						$cont=1;
					?>
					<?php while($row_cms_venda = mysql_fetch_array( $result_cms_venda )): ?>
					<?php if($cms_tipo_recebimento==2): ?>
                    	<?php $cms_tipo_recebimento=""; ?>
                	<?php else: ?> 
					<?php if($row_cms_venda["cms_tipo_soma"] == 1){$cor_linha = "blue";}else{$cor_linha = "red"; $repasse_novo = 1;} ?>
					<tr class="removivel" style="color: <?php echo $cor_linha; ?>;">
						<td>
							<?php echo $cont; $cont++; ?>
						</td>
						<td>
							<?php
								$result_cms_tipo = mysql_query("SELECT cms_tipo_id, cms_tipo_nome, cms_tipo_recebimento FROM sys_vendas_cms_tipos WHERE cms_tipo_id = '".$row_cms_venda['tipo_id']."';")
								or die(mysql_error());								
								$row_cms_tipo = mysql_fetch_array( $result_cms_tipo );
								$cms_tipo_recebimento = $row_cms_tipo['cms_tipo_recebimento'];
								echo "{$row_cms_tipo['cms_tipo_nome']}";								
							?>
						</td>
						<td>
							R$ 
							<?php 
							if(($row_cms_venda["tipo_id"] == 1)||($row_cms_venda["tipo_id"] == 2)||($row_cms_venda["tipo_id"] == 3)||($row_cms_venda["tipo_id"] == 4)||($row_cms_venda["tipo_id"] == 20)||($row_cms_venda["tipo_id"] == 21)||($row_cms_venda["tipo_id"] == 22)||($row_cms_venda["tipo_id"] == 23)){echo $valor_venda;}
							if(($row_cms_venda["tipo_id"] == 5)||($row_cms_venda["tipo_id"] == 6)||($row_cms_venda["tipo_id"] == 7)||($row_cms_venda["tipo_id"] == 8)||($row_cms_venda["tipo_id"] == 24)||($row_cms_venda["tipo_id"] == 25)||($row_cms_venda["tipo_id"] == 26)||($row_cms_venda["tipo_id"] == 27)){echo $total_saldos_label;}
							if(($row_cms_venda["tipo_id"] == 9)||($row_cms_venda["tipo_id"] == 10)||($row_cms_venda["tipo_id"] == 11)||($row_cms_venda["tipo_id"] == 12)||($row_cms_venda["tipo_id"] == 28)||($row_cms_venda["tipo_id"] == 29)||($row_cms_venda["tipo_id"] == 30)||($row_cms_venda["tipo_id"] == 31)){echo $vendas_liquido;}
							if(($row_cms_venda["tipo_id"] == 13)||($row_cms_venda["tipo_id"] == 14)||($row_cms_venda["tipo_id"] == 15)||($row_cms_venda["tipo_id"] == 16)||($row_cms_venda["tipo_id"] == 32)||($row_cms_venda["tipo_id"] == 33)||($row_cms_venda["tipo_id"] == 36)||($row_cms_venda["tipo_id"] == 37)){
								$valor_referencia_pmt = $row['vendas_valor_parcela'] * $row['vendas_percelas'];
								$valor_referencia_pmt = ($valor_referencia_pmt>0) ? number_format($valor_referencia_pmt, 2, ',', '.') : '' ;
								echo $vendas_valor_parcela." * ".$row['vendas_percelas']." = ".$valor_referencia_pmt;
							}
							if(($row_cms_venda["tipo_id"] == 19)||($row_cms_venda["tipo_id"] == 40)){echo $total_saldos_outros_bancos_label;}
							?>
						</td>
						<td>
							<?php echo number_format($row_cms_venda['cms_perc'], 2, ',', '.'); ?> %
							<?php if($cms_tipo_recebimento==2){echo "(R$ ".number_format($row_cms_venda['cms_subtotal'], 2, ',', '.').")";} ?>
						</td>
						<td id="antecipacao">
                            <?php if($cms_tipo_recebimento==2): ?>
                                <?php 
                                    $result_cms_antecipacao = mysql_query("SELECT cms_perc, cms_subtotal FROM sys_vendas_cms WHERE vendas_id=".$vendas_id." AND tipo_id=".($row_cms_venda['tipo_id']+1).";")
                                        or die(mysql_error());
                                    $row_cms_antecipacao = mysql_fetch_array( $result_cms_antecipacao );
                                    if($row_cms_venda['cms_perc'] != 0)
                                    { 
                                        $cms_antecipacao = $row_cms_antecipacao['cms_perc']*100/$row_cms_venda['cms_perc'];
                                        $cms_antecipacao_real = $row_cms_antecipacao['cms_perc'];
                                    }else{
                                        $cms_antecipacao = $row_cms_antecipacao['cms_perc'];
                                        $cms_antecipacao_real = $row_cms_antecipacao['cms_perc'];
                                    }                                    
                                ?>                 
                                <?php echo number_format($cms_antecipacao, 2, ',', '.'); ?> % 
                                <span style="font-size: 10px;">(<?php echo number_format($cms_antecipacao_real, 2, ',', '.'); ?> %)</span>
								[R$ <?php echo number_format($row_cms_antecipacao['cms_subtotal'], 2, ',', '.'); ?>]
                            <?php endif; ?>
                        </td>
						<td>R$ <?php echo number_format($row_cms_venda['cms_valor'], 2, ',', '.'); ?></td>
						<td>
							<?php if($row_cms_venda['cms_tipo_soma']==1){if($row_cms_venda['cms_imposto']==1){echo "SIM";}else{echo "NÃO";}} ?>
						</td>
						<td>R$ 
							<?php 
								if($row_cms_venda['cms_tipo_soma']==1){
									if($cms_tipo_recebimento==2){
										echo number_format($row_cms_antecipacao['cms_subtotal'], 2, ',', '.');
										$totais_cms = $totais_cms + $row_cms_antecipacao['cms_subtotal'];
									}else{
										echo number_format($row_cms_venda['cms_subtotal'], 2, ',', '.');
										if($cms_tipo_recebimento==1){$totais_cms = $totais_cms + $row_cms_venda['cms_subtotal'];}
									}
								}else{echo number_format($row_cms_venda['cms_subtotal'], 2, ',', '.');}
							?>
						</td>
					</tr>
					<?php endif; ?>
					<?php endwhile; ?>
					<tr>
						<td colspan="7"></td>
						<td>R$ <?php echo number_format($totais_cms, 2, ',', '.'); ?></td>
					</tr>
				</table>
			</div>
		</div>
<style type="text/css">
    #comissoes_tbl th, #comissoes_tbl td{
        border: solid 1px #fff;
    }
    #comissoes_tbl th
    {
        background-color: #5474a9;  
    }
    .remove_cms_x{
	    color: red;
	    cursor: pointer;
    }
</style>
	<?php if($cont == 1): ?>
		<div class="linha">
			<div class="coluna campo-titulo">CMS AF:</div>
			<div class="coluna campo-valor">
				<?php $vendas_juros = ($row['vendas_juros']>0) ? number_format($row['vendas_juros'], 2, ',', '.') : '0' ;?>
				<input value="<?php echo $vendas_juros; ?>" name="vendas_juros" type="text" size="3" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> %
			</div>
			<?php if ($row['vendas_tipo_contrato'] == "6"): ?>
				<div class="coluna campo-titulo">Impostos:</div>
				<div class="coluna campo-valor">
					<?php $vendas_impostos_perc = ($row['vendas_impostos_perc']>0) ? number_format($row['vendas_impostos_perc'], 2, ',', '.') : '0' ;?>
					<input value="<?php echo $vendas_impostos_perc;?>" name="vendas_impostos_perc" type="text" size="5" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> % 
					<?php $vendas_impostos = ($row['vendas_impostos']>0) ? number_format($row['vendas_impostos'], 2, ',', '.') : '0' ;?>
					(<?php if($vendas_impostos){echo "<strong>R$ ".$vendas_impostos."</strong>";}?>)
				</div>
			<?php else:?>
				<div class="coluna campo-titulo">Impostos sob Flat:</div>
				<div class="coluna campo-valor">
					<?php $vendas_impostos_perc = ($row['vendas_impostos_perc']>0) ? number_format($row['vendas_impostos_perc'], 2, ',', '.') : '0' ;?>
					<input value="<?php echo $vendas_impostos_perc;?>" name="vendas_impostos_perc" type="text" size="5" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> % 
					<?php $vendas_impostos_flat = ($row['vendas_impostos_flat']>0) ? number_format($row['vendas_impostos_flat'], 2, ',', '.') : '0' ;?>
					<?php if($vendas_impostos_flat){echo "(R$ ".$vendas_impostos_flat.")";}?>
				</div>
			<?php endif;?>
		</div>
		<?php if ($row['vendas_tipo_contrato'] == "3"): ?>
			<div class="linha">
				<div class="coluna campo-titulo">CMS Líquido:</div>
				<div class="coluna campo-valor">
					<?php $vendas_juros_liquido = ($row['vendas_juros_liquido']>0) ? number_format($row['vendas_juros_liquido'], 2, ',', '.') : '0' ;?>
					<input value="<?php echo $vendas_juros_liquido; ?>" name="vendas_juros_liquido" type="text" size="3" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> %
				</div>
			</div>
			<div class="linha">
				<div class="coluna campo-titulo">CMS Saldo Devedor:</div>
				<div class="coluna campo-valor">
					<?php $vendas_cms_saldo = ($row['vendas_cms_saldo']>0) ? number_format($row['vendas_cms_saldo'], 2, ',', '.') : '0' ;?>
					<input value="<?php echo $vendas_cms_saldo; ?>" name="vendas_cms_saldo" type="text" size="3" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> %
				</div>
				<div class="coluna campo-titulo">Impostos sob Saldo Devedor:</div>
				<div class="coluna campo-valor">
					<?php $vendas_impostos_perc_saldo = ($row['vendas_impostos_perc_saldo']>0) ? number_format($row['vendas_impostos_perc_saldo'], 2, ',', '.') : '0' ;?>
					<input value="<?php echo $vendas_impostos_perc_saldo;?>" name="vendas_impostos_perc_saldo" type="text" size="5" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> % 
					<?php $vendas_impostos_saldo = ($row['vendas_impostos_saldo']>0) ? number_format($row['vendas_impostos_saldo'], 2, ',', '.') : '0' ;?>
					<?php if($vendas_impostos_saldo){echo "(R$ ".$vendas_impostos_saldo.")";}?>
				</div>
			</div>
		<?php endif;?>
		<div class="linha">
			<div class="coluna campo-titulo">CMS Bônus:</div>
			<div class="coluna campo-valor">
				<?php $vendas_bonus = ($row['vendas_bonus']>0) ? number_format($row['vendas_bonus'], 2, ',', '.') : '0' ;?>
				<input value="<?php echo $vendas_bonus; ?>" name="vendas_bonus" type="text" size="3" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> %
			</div>
			<div class="coluna campo-titulo">Impostos sob Bônus:</div>
			<div class="coluna campo-valor">
				<?php $vendas_impostos_perc_bonus = ($row['vendas_impostos_perc_bonus']>0) ? number_format($row['vendas_impostos_perc_bonus'], 2, ',', '.') : '0' ;?>
				<input value="<?php echo $vendas_impostos_perc_bonus;?>" name="vendas_impostos_perc_bonus" type="text" size="5" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> % 
				<?php $vendas_impostos_bonus = ($row['vendas_impostos_bonus']>0) ? number_format($row['vendas_impostos_bonus'], 2, ',', '.') : '0' ;?>
				<?php if($vendas_impostos_bonus){echo "(R$ ".$vendas_impostos_bonus.")";}?>
			</div>
		</div>
		<div class="linha">
			<div class="coluna campo-titulo">CMS Fracionada:</div>
			<div class="coluna campo-valor">
				<?php $vendas_juros_fr = ($row['vendas_juros_fr']>0) ? number_format($row['vendas_juros_fr'], 2, ',', '.') : '0' ;?>
				<input value="<?php echo $vendas_juros_fr; ?>" name="vendas_juros_fr" type="text" size="3" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> %
			</div>
		</div>
		<div class="linha">
			<div class="coluna campo-titulo">CMS PMT:</div>
			<div class="coluna campo-valor">
				<?php $vendas_pmt = ($row['vendas_pmt']>0) ? number_format($row['vendas_pmt'], 2, ',', '.') : '0' ;?>
				<input value="<?php echo $vendas_pmt; ?>" name="vendas_pmt" type="text" size="3" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> %
			</div>
		</div>
		
	<?php else: ?>

		<div class="linha">
			<?php if ($row['vendas_tipo_contrato'] == "6"): ?>
				<div class="coluna campo-titulo">Impostos:</div>
				<div class="coluna campo-valor">
					<?php $vendas_impostos_perc = ($row['vendas_impostos_perc']>0) ? number_format($row['vendas_impostos_perc'], 2, ',', '.') : '0' ;?>
					<input value="<?php echo $vendas_impostos_perc;?>" name="vendas_impostos_perc" type="text" size="5" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> % 
					<?php $vendas_impostos = ($row['vendas_impostos']>0) ? number_format($row['vendas_impostos'], 2, ',', '.') : '0' ;?>
					(<?php if($vendas_impostos){echo "<strong>R$ ".$vendas_impostos."</strong>";}?>)
				</div>
			<?php else:?>
				<div class="coluna campo-titulo">Impostos sob Flat:</div>
				<div class="coluna campo-valor">
					<?php $vendas_impostos_perc = ($row['vendas_impostos_perc']>0) ? number_format($row['vendas_impostos_perc'], 2, ',', '.') : '0' ;?>
					<input value="<?php echo $vendas_impostos_perc;?>" name="vendas_impostos_perc" type="text" size="5" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> % 
					<?php $vendas_impostos_flat = ($row['vendas_impostos_flat']>0) ? number_format($row['vendas_impostos_flat'], 2, ',', '.') : '0' ;?>
					<?php if($vendas_impostos_flat){echo "(R$ ".$vendas_impostos_flat.")";}?>
				</div>
			<?php endif;?>
		</div>
		<div class="linha">
			<div class="coluna campo-titulo">Impostos sob Saldo Devedor:</div>
			<div class="coluna campo-valor">
				<?php $vendas_impostos_perc_saldo = ($row['vendas_impostos_perc_saldo']>0) ? number_format($row['vendas_impostos_perc_saldo'], 2, ',', '.') : '0' ;?>
				<input value="<?php echo $vendas_impostos_perc_saldo;?>" name="vendas_impostos_perc_saldo" type="text" size="5" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> % 
				<?php $vendas_impostos_saldo = ($row['vendas_impostos_saldo']>0) ? number_format($row['vendas_impostos_saldo'], 2, ',', '.') : '0' ;?>
				<?php if($vendas_impostos_saldo){echo "(R$ ".$vendas_impostos_saldo.")";}?>
			</div>
			<div class="coluna campo-titulo">Impostos sob Bônus:</div>
			<div class="coluna campo-valor">
				<?php $vendas_impostos_perc_bonus = ($row['vendas_impostos_perc_bonus']>0) ? number_format($row['vendas_impostos_perc_bonus'], 2, ',', '.') : '0' ;?>
				<input value="<?php echo $vendas_impostos_perc_bonus;?>" name="vendas_impostos_perc_bonus" type="text" size="5" maxlength="5" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/> % 
				<?php $vendas_impostos_bonus = ($row['vendas_impostos_bonus']>0) ? number_format($row['vendas_impostos_bonus'], 2, ',', '.') : '0' ;?>
				<?php if($vendas_impostos_bonus){echo "(R$ ".$vendas_impostos_bonus.")";}?>
			</div>
		</div>
	
	<?php endif; ?>
		
		<?php if ($row['vendas_tipo_contrato'] != "6"): ?>
			<div class="linha">
				<div class="coluna campo-titulo">Total de Impostos:</div>
				<div class="coluna campo-valor">
					<?php $vendas_impostos = ($row['vendas_impostos']>0) ? number_format($row['vendas_impostos'], 2, ',', '.') : '0' ;?>
					<?php echo "R$ ".$vendas_impostos."";?>
				</div>
			</div>
		<?php endif;?>
		<div class="linha">
			<div class="coluna campo-titulo">Receita Flat: R$</div>
			<?php $vendas_receita_bruta_rs = ($row['vendas_receita_bruta']>0) ? number_format($row['vendas_receita_bruta'], 2, ',', '.') : '0' ;?>
			<div class="coluna campo-valor"><input type="text" name="vendas_receita_bruta" value="<?php echo $vendas_receita_bruta_rs;?>" size="10" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></div>
			<div class="coluna campo-titulo">Receita Fracionada: R$</div>
			<?php $vendas_receita_fr_rs = ($row['vendas_receita_fr']>0) ? number_format($row['vendas_receita_fr'], 2, ',', '.') : '0' ;?>
			<div class="coluna campo-valor"><input type="text" name="vendas_receita_fr" value="<?php echo $vendas_receita_fr_rs;?>" size="10" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></div>
		</div>
		<div class="linha">
			<div class="coluna campo-titulo">Receita Bônus: R$</div>
			<?php $vendas_receita_bonus = ($row['vendas_receita_bonus']>0) ? number_format($row['vendas_receita_bonus'], 2, ',', '.') : '0' ;?>
			<div class="coluna campo-valor"><input type="text" name="vendas_receita_bonus" value="<?php echo $vendas_receita_bonus;?>" size="10" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></div>
		</div>
		<?php if (($row['vendas_tipo_contrato'] == "6") || ($row['vendas_tipo_contrato'] == "10")): ?>
			<div class="linha">
				<div class="coluna campo-titulo">Receita Plástico: R$</div>
				<?php $vendas_receita_plastico = ($row['vendas_receita_plastico']>0) ? number_format($row['vendas_receita_plastico'], 2, ',', '.') : '0' ;?>
				<div class="coluna campo-valor"><input type="text" name="vendas_receita_plastico" value="<?php echo $vendas_receita_plastico;?>" size="10" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></div>
				<div class="coluna campo-titulo">Receita Ativação: R$</div>
				<?php $vendas_receita_ativacao = ($row['vendas_receita_ativacao']>0) ? number_format($row['vendas_receita_ativacao'], 2, ',', '.') : '0' ;?>
				<div class="coluna campo-valor"><input type="text" name="vendas_receita_ativacao" value="<?php echo $vendas_receita_ativacao;?>" size="10" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></div> 
				<input type="checkbox" name="vendas_receita_ativacao_ok" value="2" <?php if ($_GET["vendas_receita_ativacao_ok"]){echo "checked";}?>><strong>Recebido OK</strong>
			</div>
			<div class="linha">
				<div class="coluna campo-titulo">Comissão Plástico Agente: R$</div>
				<?php $vendas_cms_vendedor_plastico = ($row['vendas_cms_vendedor_plastico']>0) ? number_format($row['vendas_cms_vendedor_plastico'], 2, ',', '.') : '0' ;?>
				<div class="coluna campo-valor"><input type="text" name="vendas_cms_vendedor_plastico" value="<?php echo $vendas_cms_vendedor_plastico;?>" size="10" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></div>
				<div class="coluna campo-titulo">Comissão Ativação Agente: R$</div>
				<?php $vendas_cms_vendedor_ativacao = ($row['vendas_cms_vendedor_ativacao']>0) ? number_format($row['vendas_cms_vendedor_ativacao'], 2, ',', '.') : '0' ;?>
				<div class="coluna campo-valor"><input type="text" name="vendas_cms_vendedor_ativacao" value="<?php echo $vendas_cms_vendedor_ativacao;?>" size="10" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></div> 
			</div>
		<?php endif;?>
		<div class="linha">
			<div class="coluna campo-titulo">CIP: R$</div>
			<div class="coluna campo-valor"><input type="text" name="vendas_cip" value="<?php echo $vendas_cip_rs;?>" size="10" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></div>
			<div class="coluna campo-titulo">Percentual de Comissão Agente:</div>
			<div class="coluna campo-valor">
				  <?php $vendas_comissao_vendedor_perc = ($row['vendas_comissao_vendedor_perc']>0) ? number_format($row['vendas_comissao_vendedor_perc'], 2, ',', '.') : '0' ;?>
				  <input value="<?php if($vendas_comissao_vendedor_perc){echo $vendas_comissao_vendedor_perc;}else{echo "0";} ?>" name="vendas_comissao_vendedor_perc" type="text" size="3" maxlength="5"/>%. 
				  <?php $vendas_cms_vendedor_saldo_perc = ($row['vendas_cms_vendedor_saldo_perc']>0) ? number_format($row['vendas_cms_vendedor_saldo_perc'], 2, ',', '.') : '0' ;?>
				  Saldo Devedor: <input value="<?php if($vendas_cms_vendedor_saldo_perc){echo $vendas_cms_vendedor_saldo_perc;}else{echo "0";} ?>" name="vendas_cms_vendedor_saldo_perc" type="text" size="3" maxlength="5"/>% 
			</div>
		</div>
		<div class="linha">
			<div class="coluna campo-titulo">Taxa: R$</div>
			<?php $vendas_taxa_rs = ($row['vendas_taxa']>0) ? number_format($row['vendas_taxa'], 2, ',', '.') : '0' ;?>
			<div class="coluna campo-valor"><input type="text" name="vendas_taxa" value="<?php echo $vendas_taxa_rs;?>" size="10" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></div>
			<div class="coluna campo-titulo">Comissão Agente:</div>
			<div class="coluna campo-valor">
				<?php 
				if(!$repasse_novo){
					if($vendas_cms_vendedor_flat){echo "<strong>Flat: ".$vendas_cms_vendedor_flat."</strong><br>";}
					if($vendas_cms_vendedor_saldo){echo "<strong>Saldo Devedor: ".$vendas_cms_vendedor_saldo."</strong><br>";}
				}
				if($vendas_comissao_vendedor){echo "<strong>Total: ".$vendas_comissao_vendedor."</strong><br>";}
				?>
				<?php if (($diretoria == 1) || ($financeiro == 1)): ?>
				   <input type="checkbox" name="vendas_pago_agente" value="2" <?php if ($row['vendas_pago_agente']=="2"){echo "checked";} ?>><strong>Pago ao agente.</strong>
				<?php endif;?>
			</div>
		</div>
		<div class="linha">
			<div class="coluna campo-titulo">Receita Líquida: R$</div>
			<?php $vendas_receita_rs = ($row['vendas_receita']<>0) ? number_format($row['vendas_receita'], 2, ',', '.') : '0' ;?>
			<div class="coluna campo-valor">
				<?php if (($diretoria == 1)||($userid == 165)) :?>
					<input type="text" name="vendas_receita" value="<?php echo $vendas_receita_rs;?>" size="10" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/>
				<?php else:?>
					R$ <?php echo $vendas_receita_rs;?>
					<input type="hidden" name="vendas_receita" value="<?php echo $vendas_receita_rs;?>"/>
				<?php endif;?>
				<?php if ($diretoria == 1) :?><input type="checkbox" name="calc_rec" value="1"><strong>Forçar Valor da Receita</strong><?php endif;?>
			</div>
		</div>
	</div>
</div>