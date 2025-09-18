<div class="linha">
	<h3 class="mypets2">Histórico da Venda:</h3>
	<div class="thepet2">
		<table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
		<tr>
			<td width="15%"><div align="left"><strong>Consultor / Data</strong></div></td>
			<td width="60%"><div align="left"><strong>Observação</strong></div></td>
			<td width="20%"><div align="left"><strong>Status / Físico</strong></div></td>
		</tr>
		<tr>
		<td colspan="4">
		<div class="scroller_cobranca">
				<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
					<tbody>
		<?php
					include("sistema/latin1.php");
					$result_registros = mysql_query("SELECT * FROM sys_vendas_registros WHERE vendas_id = '" . $vendas_id . "'".$select_registro_restrito." ORDER BY registro_data DESC;")
					or die(mysql_error());
					//include("sistema/utf8.php");
					while($row_registros = mysql_fetch_array( $result_registros )) {
						if ($row_registros['registro_restrito'] == '1'){
							$style = "style='font-size:8pt; font-style: italic;'";
							$img_private = "<img src='sistema/imagens/private.png'>";
						}else{
							$style = "style='font-size:8pt; color: #333;'";
							$img_private = "";
						}
						echo "<tr class='even'>";
						$yr=strval(substr($row_registros["registro_data"],0,4));
						$mo=strval(substr($row_registros["registro_data"],5,2));
						$da=strval(substr($row_registros["registro_data"],8,2));
						$hr=strval(substr($row_registros["registro_data"],11,2));
						$mi=strval(substr($row_registros["registro_data"],14,2));
						$data_contato = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
						echo "<td width='14%'><div align='left'><span {$style}>{$row_registros['registro_usuario']}<br/>{$data_contato}</span> &nbsp;{$img_private}</div></td>";
						echo "<td width='60.5%'><div align='left'><span {$style}>".nl2br($row_registros['registro_obs'])."</span></div></td>";
						
						include("sistema/latin1.php");
						$result_status = mysql_query("SELECT * FROM sys_vendas_status WHERE status_id = " . $row_registros['registro_status'] . ";")
						or die(mysql_error());
						$row_status = mysql_fetch_array( $result_status );
						if ($row_status['status_nm']){$status = " Status: <strong>".utf8_encode($row_status['status_nm'])."</strong>";}else{$status = "";}
						echo "<td width='20%'><div align='left'><span {$style}><img src='sistema/imagens/status_{$row_registros['registro_status']}.png'> <br/>".$status;
						
						if ($row['vendas_produto'] == 1){
							include("sistema/utf8.php");
							$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos ORDER BY contrato_etapa;")
							or die(mysql_error());
							$largura_fisicos = 50 / (mysql_num_rows( $result_fisicos ) - 1); 
							echo "<div id='container-sbar'>";
							while($row_fisicos = mysql_fetch_array( $result_fisicos )) {
								if ($row_fisicos["contrato_id"] == $row_registros['registro_contrato_fisico']){echo "<div class='sbar sbar-active' style='background-color: ".$row_fisicos['contrato_cor']."1);' title='Status Físico ".$row_fisicos['contrato_nome']."'>".$row_fisicos['contrato_nome']."</div>";}
								else {echo "<div class='sbar' style='width: ".$largura_fisicos."%;'><div class='sbar-inside'></div></div>";}
							}
							echo "</div>";
						}               
						
						echo "</div></td>";
						
						echo "</tr>"; 
					} ?>
					</tbody>
		</table></div>
		</td>
		</tr>
		</table>
	</div>
</div>