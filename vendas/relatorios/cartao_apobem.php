<link href="templates/gk_music/css/template.portal.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	.bloco_consulta_titulo, .bloco_agendamentos {
	    background: #2b5797;
	    color: white;
	    border-radius: 10px 10px 0 0;
	    margin: 0;
	}
	#table_cabecalho{
		margin-bottom: 0;
	}
	#painel_busca{
		border-radius: 0 0 10px 10px;
		margin-bottom: 10px;
		background-color: #f1f1f1;
		box-shadow: 0px 2px 16px -4px rgba(0,0,0,0.3);
		border: none;
	}
	.table_resultados{
	    border-radius: 10px;
	    padding: 10px 5px;
	    background-color: #f1f1f1;
	    margin-top: 10px;
	    margin-bottom: 10px;
	}
	#paginacao{
		border-radius: 10px;
	    background-color: #f1f1f1;
	    border: none;
	    box-shadow: 0px 2px 10px -2px rgba(0,0,0,0.3);
	    margin-bottom: 10px;
	}
	#agendamentos{
	    background-color: #f1f1f1;
	    border-radius: 0 0 10px 10px;
	}
	#bloco_consulta{
		margin-bottom: 10px;
	}
</style>
<form action="index.php" method="GET">
	<input name="option" type="hidden" id="option" value="com_k2" />
	<input name="view" type="hidden" id="view" value="item" />
	<input name="id" type="hidden" id="id" value="601" />
	<input name="Itemid" type="hidden" id="Itemid" value="620" />

	<div align="center">
		<h3 class="bloco_consulta_titulo">Filtros:</h3>
	</div>
	<div id="bloco_consulta" class="thepet">
		<div id="painel_busca" class="css_form_container">
			<div class="css_form_group">
				<div class="css_form_campo">
					Mês de cobrança: <input id="mes" name="mes" placeholder="mm/aaaa" value="<?php echo $_GET['mes'];?>" type="text" maxlength="7" size="7" /> 
					<button name="buscar" type="submit" value="buscar">Gerar Relatório</button>
				</div> 
			</div>
		</div>
	</div>
	<?php if(($_GET["buscar"]) && ($_GET["mes"])): ?>
		<?php 
		include("sistema/utf8.php");
		$filtros_vendas = " AND vendas_dia_ativacao <= '".substr($_GET['mes'], 3, 4)."-".substr($_GET['mes'], 0, 2)."-31'";
		$filtros_transacoes = " AND transacao_data >= '".substr($_GET['mes'], 3, 4)."-".substr($_GET['mes'], 0, 2)."-01' AND transacao_data <= '".substr($_GET['mes'], 3, 4)."-".substr($_GET['mes'], 0, 2)."-31'";
		$result = mysql_query("SELECT vendas_status, status_nm, COUNT(DISTINCT(vendas_id)) AS total FROM `sys_vendas_seguros` 
		INNER JOIN sys_vendas_status_seg ON sys_vendas_seguros.vendas_status = sys_vendas_status_seg.status_id 
		WHERE `vendas_pgto` = 2 AND `vendas_banco` = 11".$filtros_vendas." GROUP BY vendas_status ORDER BY status_nm ASC;") 
		or die(mysql_error());
		 ?>
		<div align="center">
			<h3 class="bloco_consulta_titulo">Relatório:</h3>
		</div>
		<div id="bloco_consulta" class="thepet" style="text-align: center; background-color: #ccc;">
			<div style="text-align: center; margin: auto; width: 800px;">
				<div class="linha" style="background-color: #ccc; text-align: center;">
					<div class="coluna" style="width: 27%;">Status Atual:</div>
					<div class="coluna" style="width: 13%;">Total:</div>
					<div class="coluna" style="width: 14%;">Cobradas:</div>
					<div class="coluna" style="width: 14%;">Pagas:</div>
					<div class="coluna" style="width: 14%;">Não Pagas:</div>
					<div class="coluna" style="width: 14%;">Não Cobradas:</div>
				</div>
				<?php while($row = mysql_fetch_array( $result )): ?>
					<div class="linha" style="text-align: center;">
						<div class="coluna" style="width: 27%; font-size: 10px;"><?php echo $row["status_nm"]; ?></div>
						<div class="coluna" style="width: 13%; font-weight: bold;">
							<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="sistema/vendas/relatorios/cartao_apobem_vendas_xls.php?mes=<?php echo $_GET["mes"]; ?>&vendas_status=<?php echo $row['vendas_status']; ?>"><?php echo $row["total"]; ?></a>
						</div>
						<?php 
						$result_cobradas = mysql_query("SELECT COUNT(DISTINCT(vendas_id)) AS total FROM `sys_vendas_seguros` 
						INNER JOIN sys_vendas_transacoes_seg ON sys_vendas_seguros.vendas_id = sys_vendas_transacoes_seg.transacao_id_venda 
						WHERE `vendas_pgto` = 2 AND `vendas_banco` = 11 AND vendas_status = ".$row['vendas_status']." " . $filtros_transacoes . ";") 
						or die(mysql_error());
						$row_cobradas = mysql_fetch_array( $result_cobradas );
						?>
						<div class="coluna" style="width: 14%; font-weight: bold;">
							<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="sistema/vendas/relatorios/cartao_apobem_transacoes_xls.php?mes=<?php echo $_GET["mes"]; ?>&vendas_status=<?php echo $row['vendas_status']; ?>"><?php echo $row_cobradas["total"]; ?></a>
						</div>
						
						<?php 
						$result_recebidos = mysql_query("SELECT transacao_recebido, COUNT(DISTINCT(vendas_id)) AS total FROM `sys_vendas_seguros` 
						INNER JOIN sys_vendas_transacoes_seg ON sys_vendas_seguros.vendas_id = sys_vendas_transacoes_seg.transacao_id_venda 
						WHERE `vendas_pgto` = 2 AND `vendas_banco` = 11 AND transacao_recebido = 1 AND vendas_status = ".$row['vendas_status']." " . $filtros_transacoes . ";") 
						or die(mysql_error());
						$row_recebidos = mysql_fetch_array( $result_recebidos );
						?>
						<div class="coluna" style="width: 14%; font-weight: bold;">
							<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="sistema/vendas/relatorios/cartao_apobem_transacoes_xls.php?mes=<?php echo $_GET["mes"]; ?>&vendas_status=<?php echo $row['vendas_status']; ?>&transacao_recebido=1"><?php echo $row_recebidos["total"]; ?></a>
						</div>
						
						<?php 
						$nao_recebidos = 0;
						$nao_recebidos = $row_cobradas["total"] - $row_recebidos["total"];
						if($nao_recebidos){$color = "orange";}else{$color = "green";}
						?>
						<div class="coluna" style="width: 14%; font-weight: bold;">
							<a class="itemPrintLink" style="color: <?php echo $color; ?>;" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="sistema/vendas/relatorios/cartao_apobem_transacoes_xls.php?mes=<?php echo $_GET["mes"]; ?>&vendas_status=<?php echo $row['vendas_status']; ?>&transacao_recebido=2"><?php echo $nao_recebidos; ?></a>
						</div>
						
						<?php
						$nao_cobrados = 0;
						$nao_cobrados = $row["total"] - $row_cobradas["total"];
						if($nao_cobrados){$color = "red";}else{$color = "green";}
						?>
						<div class="coluna" style="width: 14%; font-weight: bold; font-size: 16px;">
							<a class="itemPrintLink" style="color: <?php echo $color; ?>;" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="sistema/vendas/relatorios/cartao_apobem_nao_cobradas_xls.php?mes=<?php echo $_GET["mes"]; ?>&vendas_status=<?php echo $row['vendas_status']; ?>"><?php echo $nao_cobrados; ?></a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	<?php endif; ?>
</form>