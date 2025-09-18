<div class="linha">
	<h3 class="mypets2">Seguros:</h3>
	<div class="thepet2">
		<table width="99%" class="blocos">
		<!--
				<tr>
				<td>
				<div align="left">          
					<label for="vendas_applus_ben">Nome do Beneficiário:</label></br>
					<input type="text" name="vendas_applus_ben" value="<?php echo $row["vendas_applus_ben"];?>" size="20"<?php if ($edicao == 0){echo " readonly='true'";}?>/>
					</div>
				</td>
				<td>
				<div align="left">          
					<label for="vendas_applus_parent">Parentesco:</label></br>
					<input type="text" name="vendas_applus_parent" value="<?php echo $row["vendas_applus_parent"];?>" size="20"<?php if ($edicao == 0){echo " readonly='true'";}?>/>
					</div>
				</td>       
			</tr>
		-->
				<tr>
				<td>
				<div align="left">          
					<label for="vendas_applus_valor">Valor do Seguro:</label>(somente números)</br>
					<?php $vendas_applus_valor = ($row['vendas_applus_valor']>0) ? number_format($row['vendas_applus_valor'], 2, ',', '.') : '0' ;?>
					R$ <input type="text" name="vendas_applus_valor" value="<?php echo $vendas_applus_valor;?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return(MascaraMoeda(this,'.',',',event))'";}else{echo " readonly='true'";}?>/>
					</div>
				</td>
				<td style="text-align: left;"> <label for="vendas_seguro_protegido">Seguro Prestamista:</label></br>
					<select name="vendas_seguro_protegido">
					  <option value="1"<?php if ($row['vendas_seguro_protegido'] == "1"){echo " selected";}?>>Não</option>
					  <option value="2"<?php if ($row['vendas_seguro_protegido'] == "2"){echo " selected";}?>>Sim</option>
					</select>
				</td>
				<tr>
					<td colspan="2">
						<a href="index.php?option=com_k2&view=item&id=64:cadastro-de-venda&Itemid=123&tmpl=component&print=1&venda_origem_id=<?php echo $vendas_id; ?>&clients_cpf=<?php echo $row['clients_cpf']; ?>&username=<?php echo $username; ?>&clients_nm=<?php echo $row["cliente_nome"]; ?>&vendas_consultor=<?php echo $row["vendas_consultor"]; ?>&clients_employer=INSS&acao=nova_venda_seguro" rel="lyteframe" rev="width: 700px; height: 650px; scroll:no;" title="Nova Venda de SEGURO para <?php echo $row["cliente_nome"]; ?>"><button name="nova_venda_seguro" type="button" value="Nova Venda de SEGURO">Adicionar Apólice</button></a>
					</td>
				</tr>
			</tr>
		</table>
		<table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
				<tr>
					<td width="25%"><div align="left">Seguradora:</div></td>
					<td width="22%"><div align="left">Apólice:</div></td>
					<td width="22%"><div align="left">Status:</div></td>
				</tr>
				<tr>
					<td colspan="5">
					<div class="scroller_calendar">
							<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
								<tbody>
					<?php
					$result_seg = mysql_query("SELECT vendas_id, apolice_nome, banco_nm, status_nm FROM sys_vendas_seguros 
					INNER JOIN sys_vendas_apolices ON sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id 
					INNER JOIN sys_vendas_banco_seg ON sys_vendas_seguros.vendas_banco = sys_vendas_banco_seg.banco_id 
					INNER JOIN sys_vendas_status_seg ON sys_vendas_seguros.vendas_status = sys_vendas_status_seg.status_id 
					WHERE venda_origem_id = '" . $vendas_id . "';")
					or die(mysql_error());
					while($row_seg = mysql_fetch_array( $result_seg )) {
						$link_seg = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_seguro&vendas_id=".$row_seg['vendas_id'];
						echo "<tr class='even'>";
						echo "<td width='25%'><div align='left'><a href='".$link_seg."' target='_blank'><span style='font-size:8pt;'>{$row_seg['banco_nm']}</span></a></div></td>";
						echo "<td width='22%'><div align='left'><a href='".$link_seg."' target='_blank'><span style='font-size:8pt;'>{$row_seg['apolice_nome']}</span></a></div></td>";
						echo "<td width='22%'><div align='left'><a href='".$link_seg."' target='_blank'><span style='font-size:8pt;'>{$row_seg['status_nm']}</span></a></div></td>";
						echo "</tr>"; 
					}
					?>
								</tbody>
					</table></div>
					</td>
				</tr>
		</table>
	</div>
</div>