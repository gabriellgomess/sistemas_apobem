<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
	<tbody>
		<!-- <td id="preload"></td> -->

		<?php
		include("../../connect.php");
		include("../../utf8.php");
		$result = mysql_query("SELECT * FROM sys_vendas_seguros 
		INNER JOIN sys_vendas_apolices ON (sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id) 
		WHERE vendas_id = '" . $_GET['vendas_id'] . "';") 
		or die(mysql_error());  
		$row = mysql_fetch_array( $result );
		$result_transacoes = mysql_query("SELECT transacao_id, transacao_valor, transacao_data_importacao, transacao_mes, transacao_parcela, transacao_recebido, transacao_motivo FROM sys_vendas_transacoes_seg WHERE transacao_proposta = '" . $row['vendas_proposta'] . "' OR transacao_proposta = '" . $row['vendas_id'] . "' ORDER BY transacao_parcela DESC;") 
		or die(mysql_error());

		while($row_transacoes = mysql_fetch_array( $result_transacoes )) {
			$cor_linha = "#757575";
			$aux_parcela = $transacoes - 1;
			if($row_transacoes['transacao_parcela'] < $aux_parcela){echo "<tr class='even' id='controle_api' style='color: red;'><td colspan='8'>PARCELA ".$aux_parcela." NÃO RECEBIDA!</td></tr>";}
			$transacoes = $row_transacoes['transacao_parcela'];
			if($row_transacoes['transacao_recebido'] == 2){
				$cor_linha = "#ab8601";
			}elseif($row_transacoes['transacao_valor'] > 0){
				$cor_linha = "green";
			}elseif($row_transacoes['transacao_valor'] < 0){
				$cor_linha = "red";
			}
			if($transacoes < 10){
				$transacoes = 0 . $transacoes;
			}
			echo "<tr class='even' id='controle_api' style='color: ".$cor_linha.";'>";
			if($row_transacoes['api_status']){

				$exp_id = explode('_', $row_transacoes['transacao_id']);

				if($exp_id[2] < 10){
					$exp_id[2] = 0 . $exp_id[2];
				}

				echo "<td width='4.0%'><div align='left'><span style='font-size:8pt;'>{$row_transacoes['transacao_id']}</span></div></td>";
				echo "<td width='4.0%'><div align='left'><span style='font-size:8pt'>{$row_transacoes['transacao_mes']}</span></div></td>";
				echo "<td width='3.9%'><div align='left'><span style='font-size:8pt'>{$transacoes} de ".$exp_id[2]."</span></div></td>";
				$total_transacoes = $total_transacoes + $row_transacoes['transacao_valor'];
				if($diretoria || $financeiro){
					$transacao_valor = ($row_transacoes['transacao_valor']>0) ? 'R$ '.$vendas_valor ." ( líquido R$ ".number_format($row_transacoes['transacao_valor'], 2, ',', '.') ." )" : 'Não informado' ;
				}else{
					$transacao_valor = ($row_transacoes['transacao_valor']>0) ? "R$ ".number_format($vendas_valor, 2, ',', '.') : 'Não informado' ;
				}
				echo "<td width='6.2%'><div align='left'><span style='font-size:8pt'>{$transacao_valor}</span></div></td>";
				$yr=strval(substr($row_transacoes["transacao_data_importacao"],0,4));
				$mo=strval(substr($row_transacoes["transacao_data_importacao"],5,2));
				$da=strval(substr($row_transacoes["transacao_data_importacao"],8,2));
				$hr=strval(substr($row_transacoes["transacao_data_importacao"],11,2));
				$mi=strval(substr($row_transacoes["transacao_data_importacao"],14,2));
				$transacao_data_importacao = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
				echo "<td width='5.5%'><div align='left'><span style='font-size:8pt'>{$transacao_data_importacao}</span></div></td>";
				echo "<td width='5.6%'><div align='left'><span style='font-size:8pt'>{$row_transacoes['api_tipo_cobranca']}</span></div></td>";
				echo "<td width='5%'><div align='left'><span id='status' st='{$row_transacoes['api_status']}' style='font-size:8pt'>{$row_transacoes['api_status']}</span></div></td>";
				echo "<td width='1%'><div align='right' style='cursor: pointer;' id='{$row_transacoes['api_cobranca_url']}' class='abre_link'><img src='sistema/imagens/docs.png'></div></td>";
				echo "<td width='1%'><div align='right' style='cursor: pointer;' id='{$row_transacoes['api_id']}' class='excluir_pagamento'><img src='sistema/imagens/delete2.png'></div></td>";
			}else{
				echo "<tr class='even' style='color: ".$cor_linha.";'>";
				echo "<td width='20%'><div align='left'><span style='font-size:8pt'>{$row_transacoes['transacao_id']}</span></div></td>";
				echo "<td width='20%'><div align='left'><span style='font-size:8pt'>{$row_transacoes['transacao_mes']}</span></div></td>";
				echo "<td width='10%'><div align='left'><span style='font-size:8pt'>{$transacoes} de {$api_transacoes_rows}</span></div></td>";
				$total_transacoes = $total_transacoes + $row_transacoes['transacao_valor'];
				if($diretoria || $financeiro){
					$transacao_valor = ($row_transacoes['transacao_valor']>0) ? 'R$ '.$vendas_valor ." ( líquido R$ ".number_format($row_transacoes['transacao_valor'], 2, ',', '.') ." )" : 'Não informado' ;
				}else{
					$transacao_valor = ($row_transacoes['transacao_valor']>0) ? "R$ ".number_format($vendas_valor, 2, ',', '.') : 'Não informado' ;
				}
				echo "<td width='25%'><div align='left'><span style='font-size:8pt'>{$transacao_valor}</span></div></td>";
				$yr=strval(substr($row_transacoes["transacao_data_importacao"],0,4));
				$mo=strval(substr($row_transacoes["transacao_data_importacao"],5,2));
				$da=strval(substr($row_transacoes["transacao_data_importacao"],8,2));
				$hr=strval(substr($row_transacoes["transacao_data_importacao"],11,2));
				$mi=strval(substr($row_transacoes["transacao_data_importacao"],14,2));
				$transacao_data_importacao = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
				echo "<td width='37%'><div align='left'><span style='font-size:8pt'>{$transacao_data_importacao}</span></div></td>";
				echo "<td width='5%'><div align='right'><a id='btn_excluir_parcela' title='EXCLUIR RECEBIMENTO Nº: {$row_transacoes['transacao_id']}' href='index.php?option=com_k2&view=item&id=88:excluir-recebimento-seguro&Itemid=123&tmpl=component&print=1&transacao_id={$row_transacoes['transacao_id']}&acao=exclui_recebimento_seguro' rel='lyteframe' rev='width: 550px; height: 400px; scroll:no;'><img src='sistema/imagens/delete.png'></a></div></td>";
			}
			echo "</tr>";
			//$row_transacoes['transacao_motivo'] = 51; 5365
			if($row_transacoes['transacao_motivo']){
				$result_motivo = mysql_query("SELECT retorno_definicao FROM sys_vendas_transacoes_retorno WHERE retorno_codigo = '" . $row_transacoes['transacao_motivo'] . "';")
				or die(mysql_error());
				$row_motivo = mysql_fetch_array( $result_motivo );
				echo "<tr style='color: ".$cor_linha.";'><td colspan='4' style='text-align: center;'>";
				echo "Motivo do não recebimento: ".$row_transacoes['transacao_motivo']." - ".$row_motivo['retorno_definicao']."<br></td>";
				echo "<td colspan='2' style='text-align: right;'>Cobrar Parcela <input type='checkbox' onChange='cobrarParcelas();' id='{$row_transacoes['transacao_id']}' name='cobrar' class='cobrar_parcelas' value='{$row['vendas_valor']}'></td>";
				echo "</tr>";
				  
			}
		}
		$a_receber = $row['vendas_recebido_agenc'] - $total_transacoes;
		$total_transacoes = ($total_transacoes>0) ? "R$ ".number_format($total_transacoes, 2, ',', '.') : '0' ;
		$a_receber = ($a_receber>0) ? "R$ ".number_format($a_receber, 2, ',', '.') : '0' ;
		?>
	</tbody>
</table>