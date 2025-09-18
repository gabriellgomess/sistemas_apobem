<?php 
if (($row["vendas_proposta"])&&($row["vendas_proposta"]>1)){
	$result_transacoes = mysql_query("SELECT * FROM sys_vendas_transacoes WHERE transacao_proposta = " . $row['vendas_proposta'] . " ORDER BY transacao_data DESC;") 
	or die(mysql_error());
}
?>
<table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
<tr>
<td colspan="5"><div align="right"><a href="index.php?option=com_k2&view=item&id=64:cadastro-de-recebimento&Itemid=123&tmpl=component&print=1&vendas_proposta=<?php echo $row["vendas_proposta"]; ?>&acao=novo_recebimento" rel="lyteframe" rev="width: 700px; height: 600px; scroll:no;" title="Nova Recebimento Fracionado para <?php echo $row["vendas_proposta"]; ?>"><img src="sistema/imagens/novo_peq.png"></a></div></td>
</tr>
		<tr>
			<td width="10%"><div align="left">Parcela:</div></td>
			<td width="13%"><div align="left">Valor:</div></td>
			<td width="22%"><div align="left">Imposto/Repasse:</div></td>
			<td width="15%"><div align="left">Líquido:</div></td>
			<td width="40%"><div align="left">Datas:</div></td>
		</tr>
		<tr>
			<td colspan="5">
			<div class="scroller_calendar">
					<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
						<tbody>
			<?php
						while($row_transacoes = mysql_fetch_array( $result_transacoes )) {
							if ($row_transacoes['transacao_valor']>=0){$cor_linha = "blue";}else{$cor_linha = "red";}
							echo "<tr class='even'>";
							echo "<td width='10%'><div align='left'><span style='font-size:8pt;'>{$row_transacoes['transacao_parcela']}</span></div></td>";
			$transacao_valor = ($row_transacoes['transacao_valor']<>0) ? 'R$ '.number_format($row_transacoes['transacao_valor'], 2, ',', '.') : 'Não informado' ;
							echo "<td width='13%'><div align='left'><span style='font-size:8pt; color:".$cor_linha.";'>{$transacao_valor}</span></div></td>";
			$transacao_imposto = ($row_transacoes['transacao_imposto']<>0) ? 'R$ '.number_format($row_transacoes['transacao_imposto'], 2, ',', '.') : 'R$ 0,00' ;
			$transacao_repasse = ($row_transacoes['transacao_repasse']<>0) ? 'R$ '.number_format($row_transacoes['transacao_repasse'], 2, ',', '.') : 'R$ 0,00' ;
							echo "<td width='25%'><div align='left'><span style='font-size:7pt;'>Imposto: {$transacao_imposto}</span><br><span style='font-size:7pt;'>Repasse: {$transacao_repasse}</span></div></td>";
			$transacao_receita_liquida = ($row_transacoes['transacao_receita_liquida']<>0) ? 'R$ '.number_format($row_transacoes['transacao_receita_liquida'], 2, ',', '.') : 'R$ 0,00' ;
							echo "<td width='15%'><div align='left'><span style='font-size:8pt; color:".$cor_linha.";'>{$transacao_receita_liquida}</span></div></td>";
			$transacao_data = implode(preg_match("~\/~", $row_transacoes['transacao_data']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row_transacoes['transacao_data']) == 0 ? "-" : "/", $row_transacoes['transacao_data'])));
			$yr=strval(substr($row_transacoes["transacao_data_importacao"],0,4));
			$mo=strval(substr($row_transacoes["transacao_data_importacao"],5,2));
			$da=strval(substr($row_transacoes["transacao_data_importacao"],8,2));
			$hr=strval(substr($row_transacoes["transacao_data_importacao"],11,2));
			$mi=strval(substr($row_transacoes["transacao_data_importacao"],14,2));
			$transacao_data_importacao = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
							echo "<td width='35%'><div align='left'><span style='font-size:8pt;'>Recebimento: {$transacao_data}<br>Importação: {$transacao_data_importacao}</span></div></td>";
							echo "<td width='5%'><div align='right'><a title='EXCLUIR RECEBIMENTO Nº: {$row_transacoes['transacao_id']}' href='index.php?option=com_k2&view=item&id=88:excluir-recebimento&Itemid=123&tmpl=component&print=1&transacao_id={$row_transacoes['transacao_id']}&acao=exclui_recebimento' rel='lyteframe' rev='width: 550px; height: 400px; scroll:no;'><img src='sistema/imagens/delete.png'></a></div></td>";
							echo "</tr>";
							$total_transacoes = $total_transacoes + $row_transacoes['transacao_valor'];
							$total_transacoes_impostos = $total_transacoes_impostos + $row_transacoes['transacao_imposto'];
							$total_transacoes_repasse = $total_transacoes_repasse + $row_transacoes['transacao_repasse'];
							$total_transacoes_liquida = $total_transacoes_liquida + $row_transacoes['transacao_receita_liquida'];
						}
			$a_receber = $row['vendas_receita_fr'] - $total_transacoes;
			$total_transacoes = ($total_transacoes>0) ? "R$ ".number_format($total_transacoes, 2, ',', '.') : '0' ;
			$total_transacoes_impostos = ($total_transacoes_impostos>0) ? "R$ ".number_format($total_transacoes_impostos, 2, ',', '.') : '0' ;
			$total_transacoes_repasse = ($total_transacoes_repasse>0) ? "R$ ".number_format($total_transacoes_repasse, 2, ',', '.') : '0' ;
			$total_transacoes_liquida = ($total_transacoes_liquida>0) ? "R$ ".number_format($total_transacoes_liquida, 2, ',', '.') : '0' ;
			$a_receber = ($a_receber>0) ? "R$ ".number_format($a_receber, 2, ',', '.') : '0' ;
			?>
						</tbody>
			</table></div>
			</td>
		</tr>
		<tr>
			<td colspan="5"><div align="center"><strong>Totais Fracionados</strong></div></td>
		</tr>
		<tr>
			<td colspan="3"><div align="right">Recebido:</div></td>
			<td colspan="2">
				<div align="left">
					<span style="color: blue;">Valor Bruto: <strong><?php echo $total_transacoes;?></strong></span><br>
					<span style="color: red;">Imposto: <strong><?php echo $total_transacoes_impostos;?></strong></span><br>
					<span style="color: red;">Repasse: <strong><?php echo $total_transacoes_repasse;?></strong></span><br>
					<span style="color: green;">Líquido: <strong><?php echo $total_transacoes_liquida;?></strong></span>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="3"><div align="right">A Receber:</div></td>
			<td colspan="2"><div align="left"><strong><?php echo $a_receber;?></strong></div></td>
		</tr>
</table>