<tr>
<td>
<h3 class="mypets2">Gerencia da Venda:</h3>
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
						<th>Sub-total R$:</th>
					</tr>
					<?php 
					$result_cms_venda = mysql_query("SELECT cms_id, tipo_id, cms_perc, cms_valor, cms_subtotal, cms_obs FROM sys_vendas_cms WHERE vendas_id=".$vendas_id.";")
						or die(mysql_error()); 
						$cont=1;
					?>
					<?php while($row_cms_venda = mysql_fetch_array( $result_cms_venda )): ?>
					<?php if($cms_tipo_recebimento==2): ?>
                    	<?php $cms_tipo_recebimento=""; ?>
                	<?php else: ?>  
					<tr class="removivel">
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
							if(($row_cms_venda["tipo_id"] == 1)||($row_cms_venda["tipo_id"] == 2)||($row_cms_venda["tipo_id"] == 3)||($row_cms_venda["tipo_id"] == 4)){echo $valor_venda;}
							if(($row_cms_venda["tipo_id"] == 5)||($row_cms_venda["tipo_id"] == 6)||($row_cms_venda["tipo_id"] == 7)||($row_cms_venda["tipo_id"] == 8)){echo $total_saldos_label;}
							if(($row_cms_venda["tipo_id"] == 9)||($row_cms_venda["tipo_id"] == 10)||($row_cms_venda["tipo_id"] == 11)||($row_cms_venda["tipo_id"] == 12)){echo $vendas_liquido;}
							if(($row_cms_venda["tipo_id"] == 13)||($row_cms_venda["tipo_id"] == 14)||($row_cms_venda["tipo_id"] == 15)||($row_cms_venda["tipo_id"] == 16)){echo $vendas_valor_parcela;}
							?>
						</td>
						<td><?php echo number_format($row_cms_venda['cms_perc'], 2, ',', '.'); ?> %</td>
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
						<td>R$ <?php echo number_format($row_cms_venda['cms_subtotal'], 2, ',', '.'); ?></td>
					</tr>
					<?php endif; ?>
					<?php endwhile; ?>
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

	<table width="100%" class="blocos">
		<?php if($cont == 1): ?>
                <tr>
				<?php $vendas_juros = ($row['vendas_juros']>0) ? number_format($row['vendas_juros'], 2, ',', '.') : '0' ;?>
					<td width="60%"><div align="right"><strong>CMS Flat:</strong></div></td>
					<td width="40%"><div align="left"><strong><?php echo $vendas_juros; ?> %</strong></div></td>
                </tr>
				<tr>
				<?php $vendas_bonus = ($row['vendas_bonus']>0) ? number_format($row['vendas_bonus'], 2, ',', '.') : '0' ;?>
                  <td><div align="right"><strong>CMS Bônus:</strong></div></td>
				  <td><div align="left"><strong><?php echo $vendas_bonus; ?> %</strong></div></td>
                </tr>
				<tr>
				<?php $vendas_receita_bruta_rs = ($row['vendas_receita_bruta']>0) ? number_format($row['vendas_receita_bruta'], 2, ',', '.') : '0' ;?>
                 <td><div align="right"><strong>Receita Flat:</strong></div></td>
                 <td><div align="left"><strong>R$ <?php echo $vendas_receita_bruta_rs;?></strong></div></td>
                </tr>
				<tr>
				<?php $vendas_receita_bonus = ($row['vendas_receita_bonus']>0) ? number_format($row['vendas_receita_bonus'], 2, ',', '.') : '0' ;?>
                  <td><div align="right"><strong>Receita Bônus:</strong></div></td>
				  <td><div align="left"><strong>R$ <?php echo $vendas_receita_bonus;?></strong></div></td>
                </tr>
				<tr>
		<?php endif;?>
				<?php $vendas_comissao_vendedor_perc = ($row['vendas_comissao_vendedor_perc']>0) ? number_format($row['vendas_comissao_vendedor_perc'], 2, ',', '.') : '0' ;?>
                  <td><div align="right"><strong>Percentual de Comissão Agente:</strong></div></td>
				  <td><div align="left"><strong><?php echo $vendas_comissao_vendedor_perc;?> %</strong></div></td>
                </tr>
				<tr>
				<?php $vendas_comissao_vendedor = ($row['vendas_comissao_vendedor']>0) ? number_format($row['vendas_comissao_vendedor'], 2, ',', '.') : '0' ;?>
                  <td><div align="right"><strong>Comissão Agente:</strong></div></td>
				  <td><div align="left"><strong>R$ <?php echo $vendas_comissao_vendedor;?></strong></div></td>
                </tr>
				<tr>
				<?php $vendas_taxa_rs = ($row['vendas_taxa']>0) ? number_format($row['vendas_taxa'], 2, ',', '.') : '0' ;?>
                  <td><div align="right"><strong>Taxa:</strong></div></td>
				  <td><div align="left"><strong>R$ <?php echo $vendas_taxa_rs;?></strong></div></td>
                </tr>
				<tr>	
                  <td><div align="right"><label for="vendas_receita">Receita Líquida:</label></div></td>
				  <td><div align="left">
				<strong>R$ <?php echo $vendas_receita_rs;?></strong>
				<input type="hidden" name="vendas_receita" value="<?php echo $vendas_receita_rs;?>"/>
				  </div></td>
                </tr>				
            </tbody>
		</table>
		</div>
</td>
</tr>