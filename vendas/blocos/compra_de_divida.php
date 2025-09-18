<div class="linha">
	<h3 class="mypets2">Portabilidade / Compra / Refinanciamento:</h3>
	<div class="thepet2">
		<table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
				<tr>
					<td width="25%"><div align="left">Banco:<br />nº do contrato:</div></td>
					<td width="22%"><div align="left">Valor da Parcela:<br />saldo devedor:</div></td>
					<td width="22%"><div align="left">Prazo do Contrato:<br />parcelas em aberto:</div></td>
					<td width="22%"><div align="left">Vencimento:</div></td>
					<td width="5%"><div align="right">
	<?php if ($edicao == 1): ?>
					<a href="index.php?option=com_k2&view=item&id=182:cadastro-de-divida&Itemid=123&tmpl=component&print=1&vendas_id=<?php echo $vendas_id; ?>&acao=nova_divida" rel="lyteframe" rev="width: 700px; height: 650px; scroll:no;" title="Nova dívida para <?php echo $row["cliente_nome"]; ?>"><img src="sistema/imagens/novo_peq.png"></a>
	<?php endif;?>
					</div></td>
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
						$result_banco_compra = mysql_query("SELECT banco_codigo,banco_nome,banco_titulo FROM sys_vendas_bancos_compra WHERE banco_id = " . $row_compras['compra_banco'] . ";")
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
										echo "<a title='EDITAR DÍVIDA Nº: {$row_compras['compra_id']}' href='index.php?option=com_k2&view=item&id=182:editar-divida&Itemid=123&tmpl=component&print=1&compra_id={$row_compras['compra_id']}&origem=edita_propria&acao=edita_divida' rel='lyteframe' rev='width: 650px; height: 500px; scroll:no;'><img src='sistema/imagens/edit.png'></a>";
									}
									echo "</div></td>";
									echo "</tr>";
									if ($row_banco_compra['banco_titulo'] != $row['vendas_banco']){
										$total_saldos_outros_bancos = $total_saldos_outros_bancos + $row_compras['compra_saldo'];
									}
								}
								
					$total_parcelas = ($total_parcelas>0) ? "R$ ".number_format($total_parcelas, 2, ',', '.') : '0' ;
					$total_saldos_label = ($total_saldos>0) ? number_format($total_saldos, 2, ',', '.') : '0' ;
					$total_saldos_outros_bancos_label = ($total_saldos_outros_bancos>0) ? number_format($total_saldos_outros_bancos, 2, ',', '.') : '0' ;
					?>
								</tbody>
					</table></div>
					</td>
				</tr>
				<tr>
					<td colspan="2"><div align="right">Total de Parcelas:</div></td>
					<td><div align="left"><strong><?php echo $total_parcelas;?></strong></div></td>
				</tr>
				<?php if($row['vendas_tipo_contrato'] == 5): ?>
					<tr>
						<td colspan="2"><div align="right">Saldo Devedor de Outros Bancos:</div></td>
						<td><div align="left"><strong>R$ <?php echo $total_saldos_outros_bancos_label;?></strong></div></td>
						<input type="hidden" name="vendas_portabilidade_saldo_outros" value="<?php echo $total_saldos_outros_bancos;?>"/>
					</tr>
				<?php endif;?>
				<tr>
					<td colspan="2"><div align="right">Saldo Devedor Total:</div></td>
					<td><div align="left"><strong>R$ <?php echo $total_saldos_label;?></strong></div></td>
					<input type="hidden" name="vendas_portabilidade_saldo" value="<?php echo 0+$total_saldos;?>"/>
				</tr>
		</table>
	</div>
</div>