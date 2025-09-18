

<div class="linha">
    <h3 class="mypets2">Controle de Parcelas:</h3>
	<div hidden id="modalEdit" class="modalEdit">teste</div>
    <div class="thepet2">
        <div class="linha">
            <?php  
			$result_transacoes = $_POST["result_transacoes"];
            $api_transacoes_rows = mysql_num_rows($result_transacoes);
            $api_transacoes = mysql_fetch_array($result_transacoes); 

            if($api_transacoes['api_status']):
                $api_colspan = 8;
                ?>
				<div hidden id="modalEdit" class="modalEdit">teste</div>
                <table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                    <tr>
                        <td colspan="<?php echo $api_colspan; ?>"><div align="right"><a href="index.php?option=com_k2&view=item&id=64:cadastro-de-recebimento&Itemid=123&tmpl=component&print=1&vendas_proposta=<?php echo $row["vendas_proposta"]; ?>&acao=novo_recebimento" rel="lyteframe" rev="width: 700px; height: 600px; scroll:no;" title="Nova Recebimento Fracionado para <?php echo $row["vendas_proposta"]; ?>"><img src="sistema/imagens/novo_peq.png"></a></div></td>
                    </tr>
                    <tr>
                        <td width="3.5%"><div align="left">ID:</div></td>
						<td width="3.5%"><div align="left">Vigência:</div></td>
                        <td width="3.5%"><div align="left">Parcela:</div></td>
                        <td width="6%"><div align="left">Valor:</div></td>
                        <td width="4%"><div align="left">Data da Importação:</div></td>
                        <td width="6%"><div align="left">Forma de Pagamento:</div></td>
                        <td width="5%"><div align="left">Status:</div></td>
                        <td width="1%"><div align="left"></div></td>
                        <td width="1%"><div align="left"></div></td>
                        <?php
                    else:
                        $api_colspan = 6;
                        ?>
                        <table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                            <tr>
                                <td colspan="<?php echo $api_colspan; ?>"><div align="right"><a href="index.php?option=com_k2&view=item&id=64:cadastro-de-recebimento&Itemid=123&tmpl=component&print=1&vendas_proposta=<?php echo $row["vendas_proposta"]; ?>&acao=novo_recebimento" rel="lyteframe" rev="width: 700px; height: 600px; scroll:no;" title="Nova Recebimento Fracionado para <?php echo $row["vendas_proposta"]; ?>"><img src="sistema/imagens/novo_peq.png"></a></div></td>
                            </tr>
                            <td width="18%"><div align="left">ID:</div></td>
							<td width="09%"><div align="left">Vigência:</div></td>
                            <td width="09%"><div align="left">Parcela:</div></td>
                            <td width="22%"><div align="left">Valor:</div></td>
                            <td width="27%"><div align="left">Data da Importação:</div></td>
                            <td width="05%"><div align="left">Deletar</div></td>
                            <?php 
                        endif;
                        ?>
                    </tr>
                    <tr>
                        <td colspan="<?php echo $api_colspan; ?>">
                            <div class="scroller_calendar" id="lista_parcelas">
                                <table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                                    <tbody>
                                        <!-- <td id="preload"></td> -->

                                        <?php

                                        mysql_data_seek($result_transacoes, 0);

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
                                                echo "<td width='6.2%'><div align='left'><span style='font-size:8pt' class='valoresSpan'>{$transacao_valor}</span></div></td>";
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
                                                echo "<td width='20%'><div align='left'><span style='font-size:8pt' class='idLink' id='idLink'>{$row_transacoes['transacao_id']}</span></div></td>";
												echo "<td width='10%'><div align='left'><span style='font-size:8pt'>{$row_transacoes['transacao_mes']}</span></div></td>";
                                                echo "<td width='10%'><div align='left'><span style='font-size:8pt'>{$transacoes} de {$api_transacoes_rows}</span></div></td>";
                                                $total_transacoes = $total_transacoes + $row_transacoes['transacao_valor'];
												if($diretoria || $financeiro){
													$transacao_valor = ($row_transacoes['transacao_valor']>0) ? 'R$ '.$vendas_valor ." ( líquido R$ ".number_format($row_transacoes['transacao_valor'], 2, ',', '.') ." )" : 'Não informado' ;
												}else{
													$transacao_valor = ($row_transacoes['transacao_valor']>0) ? "R$ ".number_format($vendas_valor, 2, ',', '.') : 'Não informado' ;
												}
                                                echo "<td width='25%'><div align='left'><span style='font-size:8pt' class='valoresSpan'>{$transacao_valor}</span></div></td>";
                                                $yr=strval(substr($row_transacoes["transacao_data_importacao"],0,4));
                                                $mo=strval(substr($row_transacoes["transacao_data_importacao"],5,2));
                                                $da=strval(substr($row_transacoes["transacao_data_importacao"],8,2));
                                                $hr=strval(substr($row_transacoes["transacao_data_importacao"],11,2));
                                                $mi=strval(substr($row_transacoes["transacao_data_importacao"],14,2));
                                                $transacao_data_importacao = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
                                                echo "<td width='32%'><div align='left'><span style='font-size:8pt'>{$transacao_data_importacao}</span></div></td>";
                                                echo "<td width='10%'><div align='right'><a id='btn_excluir_parcela' title='EXCLUIR RECEBIMENTO Nº: {$row_transacoes['transacao_id']}' href='index.php?option=com_k2&view=item&id=88:excluir-recebimento-seguro&Itemid=123&tmpl=component&print=1&transacao_id={$row_transacoes['transacao_id']}&acao=exclui_recebimento_seguro' rel='lyteframe' rev='width: 550px; height: 400px; scroll:no;'><img src='sistema/imagens/delete.png'></a></div></td>";
                                            }
											echo "</tr>";
											//$row_transacoes['transacao_motivo'] = 51; 5365
											if(($row_transacoes['transacao_motivo']) && ($row_tef_dia['total'] == 0)){
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
                            </div>
                        </td>
                    </tr>
					<tr id="cobrar_parcelas_dropdown" style="display: none;">
					<td colspan="5" style='text-align: right;'>
							<div class="linha" style='text-align: center; font-weight: bold;'>COBRAR PARCELAS ATRASADAS!</div>
							<div class="linha">
								<div class="coluna campo-titulo-cartao">Total a cobrar:</div>
								<div class="coluna campo-valor"><span id="total_cobrar_rs"></span></div>
								<input name="total_cobrar" id="total_cobrar" type="hidden" value="0" />
							</div>
							<div class="linha">
								<!-- ADM DO CARTAO -->
								<div class="coluna campo-titulo-cartao">Selecione</div>
								<div class="coluna campo-valor">                
									<select id="forma_pagamento" name="forma_pagamento">
									<option value=''>--FORMA DE PAGAMENTO--</option>
									<option value='1'>CARTÃO DE CREDITO</option>
									<option value='2'>BOLETO</option>
									</select>
								</div>
							</div>

						</div> 
						</td>		
					</tr>
					<tr id="boleto_asas" style="display: none;">
					<td colspan="5" style='text-align: right;'>
							<div class="linha">
								<!-- ADM DO CARTAO -->
								<div class="coluna campo-titulo-cartao">SELECIONE A DATA DE VENCIMENTO DO BOLETO</div>
								<div class="coluna campo-valor">                
									<input class="dataVencimentoBoleto" type="date"></input>
									</select>
									<BR>
									
									<div class="coluna campo-titulo-cartao"  style="margin-left: -278px;" id="emailTitulo" >EMAIL: </div>
									
									<div class="coluna campo-valor" id="cliente_email_editar_div"><input style="margin-left: -5px;" id="cliente_email_editar"></input></div>
									<BR>
									<div class="coluna campo-titulo-cartao"  style="margin-left: -423px;" id="tituloFone" >FONE: </div>
									
									<div class="coluna campo-valor" id="cliente_fone_editar_div"><input style="margin-left: -151px;" id="cliente_fone_editar"></input></div>
									<BR>
									<BR>

								
										<button type="button" class="postAsaas"> Emitir Boleto </button>
										

								</div>

								<div class="coluna campo-titulo-cartao" id="linkBoleto2" style="display: none;">LINK BOLETO</div>
								<div class="coluna campo-valor" id="linkBoleto" style="display: none;"><a class="link" href="" target="_blank"></a> </div>
							</div>

						</div> 
						</td>		
					</tr>

					<tr id="cobrar_parcelas" style="display: none;">
						<td colspan="5" style='text-align: right;'>
							<div class="linha" style='text-align: center; font-weight: bold;'>COBRAR PARCELAS ATRASADAS CARTÃO DE CREDITO!</div>
							<div class="linha">
								<!-- ADM DO CARTAO -->
								<div class="coluna campo-titulo-cartao">Adm. do Cartão:</div>
								<div class="coluna campo-valor">                
									<select id="atrasadas_cartao_adm" name="vendas_cartao_adm">
									<option value=''>--Cartão--</option>
									<?php 
										mysql_data_seek($result_cartoes, 0);
										while ($row_cartao = mysql_fetch_assoc($result_cartoes)):
									?>
										<option value='<?php echo $row_cartao['codigo']; ?>'><?php echo $row_cartao['nome']; ?></option>
									<?php
										endwhile;
									?>
									</select>
								</div>
							</div>

							<div class="linha">
								<!-- NUM DO CARTAO -->
								<div class="coluna campo-titulo-cartao">N° do Cartão:</div>
								<div class="coluna campo-valor">
									<input type="text" autocomplete="off" name="atrasadas_cartao_num" id="atrasadas_cartao_num" value="<?php //echo $row["vendas_cartao_num"];?>" size="19" maxlength="19" <?php if($edicao == 0){echo " readonly='true'";}else{echo " onKeyPress='return SomenteNumero(event)'";}?>/>
								</div>
							</div>

							<div class="linha">
								<!-- VALIDADE -->
								<div class="coluna campo-titulo-cartao">Validade:</div>
								<div class="coluna campo-valor">
									<input type="text" name="atrasadas_cartao_validade_mes" value="<?php //echo $row["vendas_cartao_validade_mes"];?>" size="2" maxlength="2" <?php if ($edicao == 0){echo " readonly='true'";}?>/>&nbsp;(MM) 
									<input type="text" name="atrasadas_cartao_validade_ano" value="<?php //echo $row["vendas_cartao_validade_ano"];?>" size="4" maxlength="4" <?php if ($edicao == 0){echo " readonly='true'";}?>/>&nbsp;(AAAA)
								</div>
								<div style="display: none;">
									<div class="coluna campo-titulo-cartao">CVV:</div>
									<div class="coluna campo-valor">
										<input type="text" autocomplete="off" size="3" maxlength="3" name="cartao_cvv" value="666<?php //echo $row["vendas_cartao_cvv"]; ?>">
									</div>
								</div>
							</div>
							<div class="linha" style="padding: 15px 0px 15px 0px;">
								<div class="coluna campo-titulo-cartao">&nbsp;</div>
								<div class="coluna campo-valor">
									<div style="text-align: center; padding: 10px; display: none;" id="result_cartao_atrasadas_api"></div>
									<span class="button" 
											id="consulta_cartao_atrasadas_api"
											venda_id ="<?php echo $row['vendas_id']; ?>"
											style="height: 20px; font-size: 10px; line-height: 0;" 
											onclick='return false;'>
												Cobrar Parcelas
									</span>

								
								</div>
							</div>
						</div> 
						</td>						
					</tr>
					<?php if($diretoria || $financeiro): ?>
						<?php $vendas_recebido_agenc = ($row['vendas_recebido_agenc']>0) ? number_format($row['vendas_recebido_agenc'], 2, ',', '.') : '0' ;?>
						<tr>
							<td colspan="2"><div align="right">Agenciamento Recebido:</div></td>
							<td><div align="left">R$ <?php echo $vendas_recebido_agenc;?></div></td>
							<?php if ($row['vendas_pendencia'] == 1): ?>
								<td colspan="2"><div align="right"><span style="color:red"><strong>* Cliente com Parcelas em aberto!</strong></span></div></td>
							<?php endif; ?>
						</tr>
						<?php $vendas_recebido_prolabore = ($row['vendas_recebido_prolabore']>0) ? number_format($row['vendas_recebido_prolabore'], 2, ',', '.') : '0' ;?>
						<tr>
							<td colspan="2"><div align="right">Prolabore Recebido:</div></td>
							<td colspan="3"><div align="left">R$ <?php echo $vendas_recebido_prolabore;?></div></td>
						</tr>
						<tr>
							<td colspan="2"><div align="right"><strong>Total Recebido:</strong></div></td>
							<td colspan="3"><div align="left"><strong><span style="color:blue;"><?php echo $total_transacoes;?></span></strong></div></td>
						</tr>
						<?php if ($row['vendas_agenc_vendedor']): ?>
							<?php $vendas_agenc_vendedor = ($row['vendas_agenc_vendedor']>0) ? number_format($row['vendas_agenc_vendedor'], 2, ',', '.') : '0' ;?>
							<tr>
								<td colspan="2"><div align="right">Agenciamento do Vendedor:</div></td>
								<td colspan="3"><div align="left">R$ <?php echo $vendas_agenc_vendedor;?></div></td>
							</tr>
						<?php endif; ?>
						<?php if ($row['vendas_prolabore_vendedor']): ?>
							<?php $vendas_prolabore_vendedor = ($row['vendas_prolabore_vendedor']>0) ? number_format($row['vendas_prolabore_vendedor'], 2, ',', '.') : '0' ;?>
							<tr>
								<td colspan="2"><div align="right">Prolabore do Vendedor:</div></td>
								<td colspan="3"><div align="left">R$ <?php echo $vendas_prolabore_vendedor;?></div></td>
							</tr>
						<?php endif; ?>
						<?php $vendas_comissao_vendedor = ($row['vendas_comissao_vendedor']>0) ? number_format($row['vendas_comissao_vendedor'], 2, ',', '.') : '0' ;?>
						<tr>
							<td colspan="2"><div align="right"><strong>Total de repasse Vendedor:</strong></div></td>
							<td colspan="3"><div align="left"><strong><span style="color:red;">R$ <?php echo $vendas_comissao_vendedor;?></span></strong></div></td>
						</tr>
						<?php $vendas_receita = ($row['vendas_receita']>0) ? number_format($row['vendas_receita'], 2, ',', '.') : '0' ;?>
						<tr>
							<td colspan="2"><div align="right"><strong>Receita:</strong></div></td>
							<td colspan="3"><div align="left"><strong><span style="color:green;">R$ <?php echo $vendas_receita;?></span></strong></div></td>
						</tr>
					<?php endif; ?>
                </table>
            </div>
        </div>
    </div>