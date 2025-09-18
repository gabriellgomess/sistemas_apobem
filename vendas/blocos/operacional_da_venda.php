<?php  ?>
<div class="linha">
	<h3 class="mypets2">Operacional da Venda:</h3>
	<div class="thepet2">
		<?php if ($administracao == 1 || ($coordenador_plataformas && $operacional_fisico) ) :?>
			<div class="linha">
				<div class="coluna campo-titulo">Status:</div>
				<div class="coluna campo-valor">
                    <select id="vendas_status" name="vendas_status">
						<?php
						if ($vendas_status_proximo){
							if ($row['vendas_status'] != 9)
							{
								$filtro_proximo = "OR status_id = '15' OR status_id = '7' OR status_id = '33'";
							}
							if ($row['vendas_status'] == 6)
							{
								$filtro_proximo = $filtro_proximo." OR status_id = '8'";
							}
							if ($row['vendas_status'] == 11	)//conferida
							{
								$filtro_proximo = $filtro_proximo." OR status_id = '14'";//em backoffice
							}
							$result_status = mysql_query("SELECT * FROM sys_vendas_status 
							WHERE status_id = '" . $vendas_status_proximo . "' 
							OR status_id = '" . $row['vendas_status'] . "'
							OR status_anterior LIKE '%," . $row['vendas_status'] . ",%' 
							".$filtro_proximo."
							ORDER BY status_etapa;")
							or die(mysql_error());
						}else{
							$result_status = mysql_query("SELECT * FROM sys_vendas_status WHERE status_tipo like '%," . $row['vendas_tipo_contrato'] . ",%' ORDER BY status_etapa;")
							or die(mysql_error());
						}
						while($row_status = mysql_fetch_array( $result_status )) {
							if ($row_status["status_id"] == $row["vendas_status"]){$selected = "selected";}else{$selected = "";}
							echo "<option value='{$row_status['status_id']}'{$selected}>{$row_status['status_nm']}</option>";
						}
						?>
                    </select>
				</div>
				<div class="coluna campo-titulo">Base Contrato: R$</div>
				<?php $vendas_base_contrato = ($row['vendas_base_contrato']>0) ? number_format($row['vendas_base_contrato'], 2, ',', '.') : '0' ;?>
				<div class="coluna campo-valor"><input type="text" name="vendas_base_contrato" value="<?php echo $vendas_base_contrato;?>" size="15" onKeyPress="return(MascaraMoeda(this,'.',',',event))" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/></div>
				<div class="coluna campo-titulo">Restituição: R$</div>
				<?php $vendas_restituicao = ($row['vendas_restituicao']>0) ? number_format($row['vendas_restituicao'], 2, ',', '.') : '0' ;?>
				<div class="coluna campo-valor"><input type="text" name="vendas_restituicao" value="<?php echo $vendas_restituicao;?>" size="15" onKeyPress="return(MascaraMoeda(this,'.',',',event))" <?php if ($row['vendas_status'] == "9"){echo "readonly='true'";} ?>/></div>
				<div class="coluna campo-titulo">Base de Produção: R$</div>
				<?php $vendas_base_prod_rs = ($row['vendas_base_prod']>0) ? number_format($row['vendas_base_prod'], 2, ',', '.') : '0' ;?>
				<div class="coluna campo-valor"><input type="text" name="vendas_base_prod" value="<?php echo $vendas_base_prod_rs;?>" size="15" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></strong></div>
				<div class="coluna campo-titulo">Dia da Implantação:</div>
				<?php $vendas_dia_imp = implode(preg_match("~\/~", $row['vendas_dia_imp']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_imp']) == 0 ? "-" : "/", $row['vendas_dia_imp'])));?>
				<div class="coluna campo-valor"><p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-5" name="dp-normal-5" maxlength="10" size="10" readonly="true" value="<?php echo $vendas_dia_imp;?>" /></p></div>
				<div class="coluna campo-titulo">Dia Pago:</div>
				<?php $vendas_dia_pago = implode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "-" : "/", $row['vendas_dia_pago'])));?>
				<div class="coluna campo-valor"><p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-6" name="dp-normal-6" maxlength="10" size="10" readonly="true" value="<?php echo $vendas_dia_pago;?>" /></p></div>
				
				<div class="coluna campo-titulo">Data da Averbação:</div>
				<?php $vendas_averbacao_data = implode(preg_match("~\/~", $row['vendas_averbacao_data']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_averbacao_data']) == 0 ? "-" : "/", $row['vendas_averbacao_data'])));?>
				<div class="coluna campo-valor"><p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="vendas_averbacao_data" name="vendas_averbacao_data" maxlength="10" size="10" readonly="true" value="<?php echo $vendas_averbacao_data;?>" /></p></div>

				<div class="coluna campo-titulo">Código da Base:</div>
				<div class="coluna campo-valor"><?php echo $row['vendas_base'];?></div>
				<div class="coluna campo-titulo">Mês de Referência:</div>
				<div class="coluna campo-valor"><?php if ($row['vendas_mes'] == ""){echo "Não Informado";}else{echo $row['vendas_mes'];} ?></div>

				<?php if ((($admin_fisicos == 1)||($diretoria == 1))&&($row["vendas_produto"] == 1)) :?>
					<?php if (($row["vendas_tipo_contrato"] == 3) || ($row["vendas_tipo_contrato"] == 6)) :?>
						<div class="coluna campo-titulo"><?php if ($row["vendas_tipo_contrato"] == 3){echo "Contrato Físico Portabilidade:";}else{echo "Contrato Físico CCB:";} ?></div>
						<div class="coluna campo-valor">
							<select name="vendas_contrato_fisico2">
								<?php
								$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos;")
								or die(mysql_error());
								while($row_fisicos = mysql_fetch_array( $result_fisicos )) {
									$selected_fisicos = "";
									if ($row['vendas_contrato_fisico2'] == $row_fisicos["contrato_id"]){$selected_fisicos = " selected";}
									echo "<option value='{$row_fisicos['contrato_id']}'{$selected_fisicos}>{$row_fisicos['contrato_nome']}</option>";
								}
								?>
							</select>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php elseif ($edicao == 1):?>
			<div class="linha">
				<div class="coluna campo-titulo">Status:</div>
				<div class="coluna campo-valor">
                    <select name="vendas_status">
						<?php
						if ($vendas_status_proximo){
							$result_status = mysql_query("SELECT * FROM sys_vendas_status WHERE 
							status_id = '" . $vendas_status_proximo . "' 
							OR status_id = '" . $row['vendas_status'] . "' 
							OR status_anterior LIKE '%," . $row['vendas_status'] . ",%' 
							OR status_id = '7' 
							ORDER BY status_etapa;") or die(mysql_error());
						}else{
							$result_status = mysql_query("SELECT * FROM sys_vendas_status WHERE status_tipo like '%," . $row['vendas_tipo_contrato'] . ",%' AND status_liberado = '1' ORDER BY status_etapa;")
							or die(mysql_error());							
						}
						while($row_status = mysql_fetch_array( $result_status )) {
							if ($row_status["status_id"] == $row["vendas_status"]){$selected = "selected";}else{$selected = "";}
							echo "<option value='{$row_status['status_id']}'{$selected}>{$row_status['status_nm']}</option>";
						}
						?>
                    </select>
				</div>
			</div>

			<?php $vendas_dia_imp = implode(preg_match("~\/~", $row['vendas_dia_imp']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_imp']) == 0 ? "-" : "/", $row['vendas_dia_imp'])));?>
			<input type="hidden" id="dp-normal-5" name="dp-normal-5" readonly="true" value="<?php echo $vendas_dia_imp;?>" />
			<?php $vendas_dia_pago = implode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "-" : "/", $row['vendas_dia_pago'])));?>
			<input type="hidden" id="dp-normal-6" name="dp-normal-6" readonly="true" value="<?php echo $vendas_dia_pago;?>" />
			<?php $vendas_averbacao_data = implode(preg_match("~\/~", $row['vendas_averbacao_data']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_averbacao_data']) == 0 ? "-" : "/", $row['vendas_averbacao_data'])));?>
			<input type="hidden" id="vendas_averbacao_data" name="vendas_averbacao_data" readonly="true" value="<?php echo $vendas_averbacao_data;?>" />
		<?php else: ?>
			<input type="hidden" name="vendas_status" value="<?php echo $row["vendas_status"]; ?>">
			<?php $vendas_dia_imp = implode(preg_match("~\/~", $row['vendas_dia_imp']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_imp']) == 0 ? "-" : "/", $row['vendas_dia_imp'])));?>
			<input type="hidden" id="dp-normal-5" name="dp-normal-5" readonly="true" value="<?php echo $vendas_dia_imp;?>" />
			<?php $vendas_dia_pago = implode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "-" : "/", $row['vendas_dia_pago'])));?>
			<input type="hidden" id="dp-normal-6" name="dp-normal-6" readonly="true" value="<?php echo $vendas_dia_pago;?>" />
			<?php $vendas_averbacao_data = implode(preg_match("~\/~", $row['vendas_averbacao_data']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_averbacao_data']) == 0 ? "-" : "/", $row['vendas_averbacao_data'])));?>
			<input type="hidden" id="vendas_averbacao_data" name="vendas_averbacao_data" readonly="true" value="<?php echo $vendas_averbacao_data;?>" />
		<?php endif; ?>
		
<?php if($super_user || $administracao || $operacional_fisico): ?>
	<div class="linha">
		<div class="coluna campo-titulo">Promotora:</div>
		<div class="coluna campo-valor">
		    <select name="vendas_promotora">
			<?php
			if ((!$row['vendas_promotora']) || ($row['vendas_promotora'] == "Nao Informado")) {echo "<option value='Nao Informado' selected>---- Selecione a Promotora ----</option>";}
			$result_promo = mysql_query("SELECT promotora_nome FROM sys_vendas_promotoras ORDER BY promotora_nome;")
			or die(mysql_error());
			while($row_promo = mysql_fetch_array( $result_promo )) {
				if ($row_promo["promotora_nome"] == $row["vendas_promotora"]){$selected = "selected";}else{$selected = "";}
				echo "<option value='{$row_promo['promotora_nome']}'{$selected}>{$row_promo['promotora_nome']}</option>";
			}
			?>
		    </select>  
		</div>
	
	</div>
<?php else: ?>
<!-- 	<div class="linha">
		<div class="coluna campo-titulo">Promotora:</div>
		<div class="coluna campo-valor">
			<?php echo $row["vendas_promotora"]; ?> -->
			<input type="hidden" name="vendas_promotora" value="<?php echo $row["vendas_promotora"]; ?>">	    
<!-- 		</div>
	</div> -->
<?php endif; ?>

		<?php if ((($admin_fisicos == 1)||($diretoria == 1)||($coordenador_plataformas == 1)||($gerente_plataformas == 1)||(strpos($user_unidade,"PLATAFORMA") !== false))&&($row["vendas_produto"] == 1)) :?>
			<div class="coluna campo-titulo"><?php if ($row["vendas_tipo_contrato"] == 6){echo "Contrato Físico Termo:";}else{echo "Contrato Físico Básico:";} ?></div>
			<div class="coluna campo-valor">
			<?php if( ($gerente_plataformas == 1 || strpos($user_unidade,"PLATAFORMA") !== false) && ($admin_fisicos != 1 && $diretoria != 1) && ($coordenador_plataformas != 1) ): ?>
                <select name="vendas_contrato_fisico">
                    <?php
					$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos;")
					or die(mysql_error());
					while($row_fisicos = mysql_fetch_array( $result_fisicos )) {
						$selected_fisicos = "";
						if( $row_fisicos["contrato_id"] != 101 /*NA LOJA*/ 
							 && $row_fisicos["contrato_id"] != $row['vendas_contrato_fisico'])
						{
							 $disabled = " disabled"; }else{ $disabled = "";
						}
						if($row['vendas_contrato_fisico'] == $row_fisicos["contrato_id"]){$selected_fisicos = " selected";}
						echo "<option value='{$row_fisicos['contrato_id']}'{$selected_fisicos}{$disabled}>{$row_fisicos['contrato_nome']}</option>";
					}
                    ?>
                </select>
			<?php else: ?>
                <select name="vendas_contrato_fisico">
                    <?php
					$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos;")
					or die(mysql_error());
					while($row_fisicos = mysql_fetch_array( $result_fisicos )) {
						$selected_fisicos = "";
						if ($row['vendas_contrato_fisico'] == $row_fisicos["contrato_id"]){$selected_fisicos = " selected";}
						echo "<option value='{$row_fisicos['contrato_id']}'{$selected_fisicos}>{$row_fisicos['contrato_nome']}</option>";
					}
                    ?>
                </select>
        	<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ($row["vendas_produto"] == 1) :?>
			<div class="linha">
				<div class="coluna campo-titulo">Método de Envio:</div>
				<div class="coluna campo-valor">
					<select name="vendas_envio">
					<?php
					if (!$row['vendas_envio']){echo "<option value='' selected>Não Informado</option>";}
					$result_envio = mysql_query("SELECT * FROM sys_vendas_envio ORDER BY envio_id;")
					or die(mysql_error());
					while($row_envio = mysql_fetch_array( $result_envio )) {
						if ($row_envio["envio_id"] == $row["vendas_envio"]){$selected = "selected";}else{$selected = "";}
						if($row_envio["envio_id"]!=3)
						{
							echo "<option value='{$row_envio['envio_id']}'{$selected}>{$row_envio['envio_nome']}</option>";    
						}else{
							if($selected == "selected"){
							echo "<option value='{$row_envio['envio_id']}'{$selected}>{$row_envio['envio_nome']}</option>";      
							}
						}                           
					}
					?>
					</select>
				</div>
				<?php if ($row['vendas_envio'] == 1): ?>
					<div class="coluna campo-titulo">Contato:</div>
					<div class="coluna campo-valor"><input value="<?php echo $row['vendas_envio_objeto']; ?>" name="vendas_envio_objeto" type="text" size="20" maxlength="30"/></div>
					<div class="coluna campo-titulo">Nome da empresa:</div>
					<div class="coluna campo-valor"><input value="<?php echo $row['vendas_envio_empresa']; ?>" name="vendas_envio_empresa" type="text" size="20" maxlength="30"/></div>
				<?php else:?>
					<div class="coluna campo-titulo">Nº do objeto:</div>
					<div class="coluna campo-valor"><input value="<?php echo $row['vendas_envio_objeto']; ?>" name="vendas_envio_objeto" type="text" size="20" maxlength="30"/></div>
					<div class="coluna campo-titulo">Data de envio:</div>
					<?php $vendas_envio_data = implode(preg_match("~\/~", $row['vendas_envio_data']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_envio_data']) == 0 ? "-" : "/", $row['vendas_envio_data'])));?>
					<div class="coluna campo-valor"><p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-7" name="dp-normal-7" maxlength="10" size="10" readonly="true" value="<?php echo $vendas_envio_data;?>" /></p></div>
				<?php endif;?>
				<?php if ($operacional_fisico): ?>
					<div class="coluna campo-titulo">Valor: R$</div>
					<div class="coluna campo-valor"><input value="<?php echo $vendas_envio_valor; ?>" name="vendas_envio_valor" type="text" size="8" maxlength="9" onKeyPress="return(MascaraMoeda(this,'.',',',event))"/></div>
				<?php endif;?>
			</div>

			<?php if ($row['vendas_envio_objeto']): ?>
				<div class="linha">
					<a href="http://www.linkcorreios.com.br/?id=<?php echo $row['vendas_envio_objeto']; ?>" rel="lyteframe" rev="width: 600px; height: 400px; scroll:no;" title="Rastreio de Objeto"><img src="sistema/imagens/correios.png"><br />Rastrear Objeto</a>
				</div>
			<?php endif; ?>

			<div class="linha">
				<div class="coluna campo-titulo">Método de Retorno:</div>
				<div class="coluna campo-valor">
					<select name="vendas_retorno">
						<?php
						if (!$row['vendas_retorno']){echo "<option value='' selected>Não Informado</option>";}
						$result_retorno = mysql_query("SELECT * FROM sys_vendas_retorno ORDER BY retorno_id;")
						or die(mysql_error());
						while($row_retorno = mysql_fetch_array( $result_retorno )) {
							if ($row_retorno["retorno_id"] == $row["vendas_retorno"]){$selected = "selected";}else{$selected = "";}
							echo "<option value='{$row_retorno['retorno_id']}'{$selected}>{$row_retorno['retorno_nome']}</option>";
						}
						?>
					</select>
				</div>
				<?php if ($row['vendas_retorno'] == 1): ?>
					<div class="coluna campo-titulo">Contato:</div>
					<div class="coluna campo-valor"><input value="<?php echo $row['vendas_retorno_objeto']; ?>" name="vendas_retorno_objeto" type="text" size="20" maxlength="30"/></div>
					<div class="coluna campo-titulo">Nome da empresa:</div>
					<div class="coluna campo-valor"><input value="<?php echo $row['vendas_retorno_empresa']; ?>" name="vendas_retorno_empresa" type="text" size="20" maxlength="30"/></div>
				<?php else:?>
					<div class="coluna campo-titulo">Nº do objeto:</div>
					<div class="coluna campo-valor"><input value="<?php echo $row['vendas_retorno_objeto']; ?>" name="vendas_retorno_objeto" type="text" size="20" maxlength="30"/></div>
					<div class="coluna campo-titulo">Data de Retorno:</div>
					<?php $vendas_retorno_data = implode(preg_match("~\/~", $row['vendas_retorno_data']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_retorno_data']) == 0 ? "-" : "/", $row['vendas_retorno_data'])));?>
					<div class="coluna campo-valor"><p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-8" name="dp-normal-8" maxlength="10" size="10" readonly="true" value="<?php echo $vendas_retorno_data;?>" /></p></div>
				<?php endif;?>
			</div>
			<?php if ($row['vendas_retorno_objeto']): ?>
				<div class="linha">
					<a href="http://www.linkcorreios.com.br/?id=<?php echo $row['vendas_retorno_objeto']; ?>" rel="lyteframe" rev="width: 600px; height: 400px; scroll:no;" title="Rastreio de Objeto"><img src="sistema/imagens/correios.png"><br />Rastrear Objeto</a>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>