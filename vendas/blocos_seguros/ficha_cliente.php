<div class="thepet" id="ficha_clientes">
	<div class="linha">
		<div class="coluna campo-titulo">Código da Venda:</div>
		<div class="coluna campo-valor" id="vendas_id"><?php echo $row["vendas_id"];?></div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Nome do Cliente:</div>
		<div class="coluna campo-valor">
			<a href="<?php echo $link_cliente;?>"><strong id="nome"><?php echo $row_client["cliente_nome"];?></strong></a></br>
		</div>	
		<div class="coluna campo-titulo">Matricula:</div>
		<div class="coluna campo-valor" id="matricula"><?php echo $row_client["cliente_beneficio"];?></div>
	</div>
    <div class="linha">
        <div class="coluna campo-titulo">CPF:</div>
		<div class="coluna campo-valor" id="cpf"><?php echo $row["cliente_cpf"];?></div>
		<div class="coluna campo-titulo">Matricula Instituidor:</div>
		<div class="coluna campo-valor"><strong><?php echo $row_client["cliente_pagamento"];?></strong> - <strong>Empregador: <?php echo $row_client["cliente_empregador"];?></strong></div>
	</div>
		<div class="linha">
			<div class="coluna campo-titulo">RG:</div>
			<div class="coluna campo-valor" id="rg"><?php echo $row["cliente_rg"];?></div>
			<div class="coluna campo-titulo">SEXO:</div>
			<div class="coluna campo-valor" id="cliente_sexo"><?php echo $row_client["cliente_sexo"];?></div>
		</div>
    <div class="linha">
		<div class="coluna campo-titulo">Órgão:</div>
		<div class="coluna campo-valor">

			<input name="vendas_consultor" type="hidden" value="<?php echo $row["vendas_consultor"];?>" />
		
            <?php if ($administracao == 1):?>
                <select name="vendas_orgao" onchange="consultaAjax();">
					<?php
					if (!$row['vendas_orgao']){echo "<option value='' selected>Não Informado</option>";}
					$result_orgao = mysql_query("SELECT * FROM sys_orgaos ORDER BY orgao_nome;")
					or die(mysql_error());
					while($row_orgao = mysql_fetch_array( $result_orgao )) {
						if ($row_orgao["orgao_nome"] == $row["vendas_orgao"]){$selected = "selected";}else{$selected = "";}
						echo "<option id='orgao' value='{$row_orgao['orgao_nome']}'{$selected}>{$row_orgao['orgao_label']}</option>";
					}
					?>
                </select>
            <?php else: ?>
            </br><strong id="orgao"><?php echo $row["vendas_orgao"];?></strong>
            <?php endif;?>
		</div>
	</div>
	
	<!-- ORGAO -->
	<div class="linha">
		<div class="coluna campo-titulo">Nascimento:</div>
		<div class="coluna campo-valor" id="nascimento">
			<?php $data_nascimento = implode(preg_match("~\/~", $row_client['cliente_nascimento']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row_client['cliente_nascimento']) == 0 ? "-" : "/", $row_client['cliente_nascimento']))); ?>
			<?php echo $data_nascimento; ?>
		</div>
		<div class="coluna campo-titulo">CEP:</div>
		<div class="coluna campo-valor" id="cep"><?php echo $row_client["cliente_cep"];?></div>
	</div>
	<?php if ($row['vendas_banco'] == 7): ?>
		<div class="linha">
			<div class="coluna campo-titulo">Profissão:</div>
				<div class="coluna campo-valor">
					<select name="cliente_cargo_cod" id="cliente_cargo_cod">
						<option value="0"> -- carregando aguarde... </option>
					</select>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="linha">
		<div class="coluna campo-titulo">Endereço:</div>
		<div class="coluna campo-valor" id="endereco"><?php echo $row_client["cliente_endereco"];?></div>
		<div class="coluna campo-titulo">Bairro:</div>
		<div class="coluna campo-valor" id="bairro"><?php echo $row_client["cliente_bairro"];?></div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Cidade / Estado:</div>
		<div class="coluna campo-valor"><?php echo $row_client["cliente_cidade"];?> - <?php echo $row_client["cliente_uf"];?></div>
		<div class="coluna campo-titulo">Data da Venda:</div>
    <?php 
		$yr=strval(substr($row["vendas_dia_venda"],0,4));
		$mo=strval(substr($row["vendas_dia_venda"],5,2));
		$da=strval(substr($row["vendas_dia_venda"],8,2));
		$hr=strval(substr($row["vendas_dia_venda"],11,2));
		$mi=strval(substr($row["vendas_dia_venda"],14,2));
		$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
		$data_venda_dia = date("d/m/Y", mktime ($hr,$mi,0,$mo,$da,$yr));
		$data_venda_hora = date("H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
    ?>  
		<div class="coluna campo-valor">
			<input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-1" name="dp-normal-1" maxlength="10" size="8" value="<?php echo $data_venda_dia;?>" />
			<input type="text" name="data_venda_hora" value="<?php echo $data_venda_hora;?>" size="6" maxlength="8"<?php if ($edicao == 0){echo " readonly='true'";}?>/>
		</div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Email:</div>
		<div class="coluna campo-valor" id="cliente_email"><?php echo $row_client["cliente_email"];?></div>
		<?php if ($row['vendas_banco'] == 11 ): ?>
			<div class="coluna campo-titulo">Envio do Kit:</div>
			<div class="coluna campo-valor">
				<?php
				// Função para encontrar a data de criação do objeto com o maior id
				function getCreationDateOfHighestId($data) {
					$maxId = null;
					$creationDate = null;

					foreach ($data as $item) {
						if ($maxId === null || $item['id'] > $maxId) {
							$maxId = $item['id'];
							$creationDate = $item['created_at'];
						}
					}

					return $creationDate;
				}

				$data_envio = getCreationDateOfHighestId($kits);
				$data_envio = date('d/m/Y H:i:s', strtotime($data_envio));
				if ($data_envio) {
					echo $data_envio;
				} else {
					echo 'Não enviado';
				}

				?>
			</div>
		<?php endif; ?>
	</div>

	<div class="linha">
		<div class="coluna campo-titulo">Telefone / Celular:</div>
		<div class="coluna campo-valor"><?php echo $row_client["cliente_telefone"];?></div>
		<input type="hidden" name="cliente_email" value="<?php echo $row_client["cliente_email"];?>" />		
		<div class="coluna campo-titulo">Telefones da Venda:</div>
		<div class="coluna campo-valor"><input type="text" name="vendas_telefone" class="texto" id="vendas_telefone" value="<?php echo $row["vendas_telefone"];?>" size="11" maxlength="12"<?php if ($edicao == 0){echo " readonly='true'";}?>/> &nbsp; 
		<?php if ($row["vendas_telefone"]){$vendas_telefone = $row["vendas_telefone"];}else{$vendas_telefone = $row_client["cliente_telefone"];} ?>
		<input type="text" name="vendas_telefone2" ID="holdtext" class="texto" value="<?php echo $row["vendas_telefone2"];?>" size="11" maxlength="12"<?php if ($edicao == 0){echo " readonly='true'";}?>/> &nbsp; 
		<?php if ($row["vendas_telefone2"]){$vendas_telefone2 = $row["vendas_telefone2"];}else{$vendas_telefone2 = $row_client["cliente_telefone"];} ?></div>

	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Dados do Consultor:</div>
		<div class="coluna campo-valor">
		<!-- DADOS CONSULTOR -->
            <?php if ($administracao == 1):?>
			<select name='vendas_consultor'>
            <?php
                $result_user_form = mysql_query("SELECT id,name,unidade FROM jos_users ORDER BY name;")
                or die(mysql_error());
                while($row_user_form = mysql_fetch_array( $result_user_form )) {
                    if ($row_user_form["id"] == $row["vendas_consultor"]){$selected = "selected";}else{$selected = "";}
                    echo "<option value='{$row_user_form['id']}'{$selected}>{$row_user_form['name']}</option>";
                }
            ?>	
			</select>
				
            <?php else:?>		
                <strong><?php echo $row_user['name'];?></strong> (<?php echo $row_user['username'];?>)</br>
                <input name="vendas_consultor" type="hidden" value="<?php echo $row["vendas_consultor"];?>" />
                <?php while($row_grupo = mysql_fetch_array( $result_grupo )){$user_groups = $user_groups.$row_grupo['title']." | ";}?>
                <span style="font-size:6pt"><?php echo $user_groups;?></span>
            <?php endif;?>		
        <!-- FIM DADOS CONSULTOR -->
		</div>
		<div class="coluna campo-titulo">Vendedor/Agente:</div>
            <div class="coluna campo-valor"><div align="left"><input type="text" name="vendas_vendedor" value="<?php echo $row["vendas_vendedor"];?>" size="30" <?php if ($edicao == 0){echo " readonly='true'";}?>/></div></div>
	</div>	
</div>

<?php $v = var_export($row["vendas_id"], TRUE); ?>

<?php $vendas_base_prod_rs = ($row['vendas_base_prod']>0) ? number_format($row['vendas_base_prod'], 2, ',', '.') : '0' ; ?>
