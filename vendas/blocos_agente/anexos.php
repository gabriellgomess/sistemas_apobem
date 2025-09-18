<h3 class="mypets2">Anexos:</h3>
<div class="thepet2">
<table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
<tr>
    <td width="35%"><div align="left">Anexo</div></td>
	<td width="24%"><div align="left">Criado por</div></td>
	<td width="35%"><div align="left">Data / tipo</div></td>
	<td width="6%"><div align="center"><a href="http://portal.grupofortune.com.br/portal/sistema/anexos/index_acionamento.php?vendas_id=<?php echo $vendas_id; ?>&anexo_usuario=<?php echo $username; ?>" rel="lyteframe" rev="width: 500px; height: 500px; scroll:no;" title="Anexar Arquivo"><img src="sistema/imagens/novo_peq.png"></a></div></td>
</tr>
<tr>
<td colspan="4">
<div class="scroller_calendar">
        <table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
            <tbody>
<?php
			while($row_anexos = mysql_fetch_array( $result_anexos )) {
				echo "<tr class='even'>";

				
				if(($row_anexos["anexo_tipo"] == "application/msword")
				||($row_anexos["anexo_tipo"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
				||($row_anexos["anexo_tipo"] == "application/rtf")){$anexo_icone = "sistema/imagens/iconeword.png";}
				
				if($row_anexos["anexo_tipo"] == "application/pdf"){$anexo_icone = "sistema/imagens/iconepdf.png";}
				
				if(($row_anexos["anexo_tipo"] == "image/jpeg")
				||($row_anexos["anexo_tipo"] == "image/jpg")
				||($row_anexos["anexo_tipo"] == "image/pjpeg")
				||($row_anexos["anexo_tipo"] == "image/gif")){$anexo_icone = "sistema/imagens/iconejpg.png";}
				
				if(($row_anexos["anexo_tipo"] == "application/zip")
				||($row_anexos["anexo_tipo"] == "application/x-zip-compressed")
				||($row_anexos["anexo_tipo"] == "multipart/x-zip")
				||($row_anexos["anexo_tipo"] == "application/x-compressed")
				||($row_anexos["anexo_tipo"] == "application/octet-stream")){$anexo_icone = "sistema/imagens/iconezip.png";}
				
				if(($row_anexos["anexo_tipo"] == "image/x-png")
				||($row_anexos["anexo_tipo"] == "image/png")){$anexo_icone == "sistema/imagens/iconepng.png";}
				
				if(($row_anexos["anexo_tipo"] == "image/x-png")
				||($row_anexos["anexo_tipo"] == "image/png")){$anexo_icone == "sistema/imagens/iconepng.png";}
				
				if (substr($row_anexos['anexo_caminho'], 0, 7) == "anexos/"){
					$anexo_caminho = "http://acionamento.grupofortune.com.br/sistema/".$row_anexos['anexo_caminho'];
				}elseif (substr($row_anexos['anexo_caminho'], 0, 7) == "anexos2"){
					$anexo_caminho = "http://portal.grupofortune.com.br/portal/sistema/anexos/".substr($row_anexos['anexo_caminho'], 8);
				}else{
					$anexo_caminho = "http://portal.grupofortune.com.br/portal/".$row_anexos['anexo_caminho'];
				}
				echo "<td width='35%'><div align='center'><span style='font-size:8pt'><a href='".utf8_encode($anexo_caminho)."' target='_blank'><img width='32' height='32' src='".$anexo_icone."'></a></span>";
				echo "<span style='font-size:7pt'><a href='{$anexo_caminho}' target='_blank'>".utf8_encode($row_anexos['anexo_nome'])."</a></span></div></td>";
				echo "<td width='25%'><div align='left'><span style='font-size:8pt'>{$row_anexos['anexo_usuario']}</span></div></td>";
				
				$yr=strval(substr($row_anexos["anexo_data"],0,4));
				$mo=strval(substr($row_anexos["anexo_data"],5,2));
				$da=strval(substr($row_anexos["anexo_data"],8,2));
				$hr=strval(substr($row_anexos["anexo_data"],11,2));
				$mi=strval(substr($row_anexos["anexo_data"],14,2));
				$anexo_data = date("d/m/Y H:i", mktime ($hr,$mi,0,$mo,$da,$yr));
				echo "<td width='35%'><div align='left'><span style='font-size:7pt'>{$anexo_data}<br />";
				
				if ($row_anexos['anexo_documento'] == "99"){echo "Outros</span></div></td>";}
				else{
					$result_tipos = mysql_query("SELECT tipo_nome FROM sys_vendas_anexos_tipos WHERE tipo_id = '".$row_anexos['anexo_documento']."';") 
					or die(mysql_error());
					$row_tipos = mysql_fetch_array( $result_tipos );
					echo $row_tipos["tipo_nome"]."</span></div></td>";
				}
				
				if ($administracao == 1){echo "<td width='5%'><div align='left'><a title='EXCLUIR ANEXO {$row_anexos['anexo_nome']}' href='anexos/exclui.php?anexo_id={$row_anexos['anexo_id']}&anexo_nome={$row_anexos['anexo_nome']}' rel='lyteframe' rev='width: 550px; height: 400px; scroll:no;'><img src='sistema/imagens/delete.png'></a></span></div></td>";}
				echo "</tr>"; 
			} ?>
			
            </tbody>
</table></div>
</td>
</tr>
</table>
</div>