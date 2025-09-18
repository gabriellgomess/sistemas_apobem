<div class="thepet">	
	<div class="linha">
		<div class="coluna campo-titulo">Código da Venda:</div>
		<div class="coluna campo-valor"><?php echo $row["vendas_id"];?></div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Nome do Cliente:</div>
		<div class="coluna campo-valor">
			<?php echo $row_client["cliente_nome"];?>
			<?php if ($row_venda_cliente["cliente"]): ?>
						<a href="index.php?option=com_k2&view=item&id=192:edicao-de-ficha-de-cliente&Itemid=123&tmpl=component&print=1&venda_cliente_id=<?php echo $row_client["venda_cliente_id"]; ?>&vendas_produto=<?php echo $row["vendas_produto"]; ?>&origem=agentes&acao=edita_ficha" rel="lyteframe" rev="width: 700px; height: 620px; scroll:no;" title="Editar Cliente <?php echo $row["cliente_nome"]; ?>"><img src="sistema/imagens/edit.png"></a></br>
			<?php else: ?>
						<a href="index.php?option=com_k2&view=item&id=193:nova-ficha-de-cliente&Itemid=123&tmpl=component&print=1&cpf=<?php echo $row["clients_cpf"]; ?>&vendas_id=<?php echo $row["vendas_id"]; ?>&vendas_orgao=<?php echo $row["vendas_orgao"]; ?>&vendas_produto=<?php echo $row["vendas_produto"]; ?>&origem=agentes&acao=nova_ficha" rel="lyteframe" rev="width: 700px; height: 620px; scroll:no;" title="Editar Cliente <?php echo $row["cliente_nome"]; ?>"><img src="sistema/imagens/edit.png">
						<span style="color: red;"><strong>* Ficha Pendente!</strong></span></a></br>
			<?php endif; ?>
		</div>
		<div class="coluna campo-titulo">CPF:</div>
		<div class="coluna campo-valor"><?php echo $row["clients_cpf"];?></div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Orgão:</div>
		<div class="coluna campo-valor">
				<?php if ($administracao == 1):?>
					<select name="vendas_orgao" onchange="consultaAjax();">
					<?php
						if (!$row['vendas_orgao']){echo "<option value='' selected>Não Informado</option>";}
						$result_orgao = mysql_query("SELECT * FROM sys_orgaos ORDER BY orgao_nome;")
						or die(mysql_error());
						while($row_orgao = mysql_fetch_array( $result_orgao )) {
							if ($row_orgao["orgao_nome"] == $row["vendas_orgao"]){$selected = "selected";}else{$selected = "";}
							echo "<option value='{$row_orgao['orgao_nome']}'{$selected}>{$row_orgao['orgao_label']}</option>";
						}
					?>
	                </select>
				<?php else: ?>
				<strong><?php echo $row['vendas_orgao'];?></strong>
				<?php endif;?>
		</div>
		<div class="coluna campo-titulo">Data da Venda:</div>
		<div class="coluna campo-valor">
			<?php 
				$yr=strval(substr($row["vendas_dia_venda"],0,4));
				$mo=strval(substr($row["vendas_dia_venda"],5,2));
				$da=strval(substr($row["vendas_dia_venda"],8,2));
				$hr=strval(substr($row["vendas_dia_venda"],11,2));
				$mi=strval(substr($row["vendas_dia_venda"],14,2));
				$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));			
				echo $data_venda;
			?>
		</div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Dados do Agente:</div>
		<div class="coluna campo-valor">	
			<?php if ($administracao == 1):?>
				<select name='vendas_consultor'>
				<?php
					$result_user_form = mysql_query("SELECT id,name,unidade,perfil FROM jos_users ORDER BY name;")
					or die(mysql_error());
					while($row_user_form = mysql_fetch_array( $result_user_form )) {
						if ($row_user_form["id"] == $row["vendas_consultor"]){$selected = "selected";}else{$selected = "";}
				        switch ($row_user_form['perfil']) {
				            case '1':
				                $perfil = " - ( Bronze )";
				                break;
				            case '2':
				                $perfil = " - ( Prata )";
				                break;
				            case '3':
				                $perfil = " - ( Ouro )";
				                break;
				            default:
				                $perfil = "";
				                break;
				        }        
						echo "<option value='{$row_user_form['id']}'{$selected}>{$row_user_form['name']}{$perfil}</option>";
					}
				?>	
				</select>
			<?php else:?>		
				<?php echo $row_user['name'];?> (<?php echo $row_user['username'];?>)</br>
				<input name="vendas_consultor" type="hidden" value="<?php echo $row["vendas_consultor"];?>" />
				<?php while($row_grupo = mysql_fetch_array( $result_grupo )){$user_groups = $user_groups.$row_grupo['title']." | ";}?>
				<span style="font-size:6pt"><?php echo $user_groups;?></span>
			<?php endif;?>
				<span style="font-size:8pt"><?php echo $row_user['email'];?></span>
		</div>
		<div class="coluna campo-titulo">Unidade:</div>
		<div class="coluna campo-valor">
			<?php echo $row_user['unidade'];?>
		</div>
	</div>
</div>