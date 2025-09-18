<div class="thepet">
	<div class="linha linha-com-imagem">
		<div class="coluna">
            <div class="coluna campo-titulo">Código da Venda:</div>
            <div class="coluna campo-valor"><?php echo $row["vendas_id"];?></div>
        
            <div class="coluna campo-titulo">Nome do Cliente:</div>
            <div class="coluna campo-valor">
                <?php 
                if ($row["vendas_orgao"] == "Exercito"){
                    $link_cliente = "index.php?option=com_k2&view=item&layout=item&id=62&Itemid=272&acao=edita_cliente&cpf=".$row['clients_cpf'];
                }else{
                    $link_cliente = "index.php?option=com_k2&view=item&layout=item&id=62&Itemid=272&acao=edita_cliente_inss&cpf=".$row['clients_cpf'];
                }
                ?> 
                <a href="<?php echo $link_cliente;?>"><strong><?php echo utf8_decode($row_client["cliente_nome"]);?></strong></a>
                <?php if ($row_venda_cliente["cliente"]): ?>
                    <?php if ($edicao): ?>
                            <a href="index.php?option=com_k2&view=item&id=192:edicao-de-ficha-de-cliente&Itemid=123&tmpl=component&print=1&venda_cliente_id=<?php echo $row_client["venda_cliente_id"]; ?>&vendas_produto=<?php echo $row["vendas_produto"]; ?>&acao=edita_ficha" rel="lyteframe" rev="width: 700px; height: 620px; scroll:no;" title="Editar Cliente <?php echo $row["cliente_nome"]; ?>"><img src="sistema/imagens/edit.png"></a>
                    <?php endif; ?>
                    </br>
                <?php else: ?>
                    <a href="index.php?option=com_k2&view=item&id=193:nova-ficha-de-cliente&Itemid=123&tmpl=component&print=1&cpf=<?php echo $row["clients_cpf"]; ?>&vendas_id=<?php echo $row["vendas_id"]; ?>&vendas_orgao=<?php echo $row["vendas_orgao"]; ?>&vendas_produto=<?php echo $row["vendas_produto"]; ?>&equipe_tipo=<?php echo $equipe_tipo; ?>&acao=nova_ficha" rel="lyteframe" rev="width: 700px; height: 620px; scroll:no;" title="Editar Cliente <?php echo $row["cliente_nome"]; ?>"><img src="sistema/imagens/edit.png">
                    <span style="color: red;"><strong>* Ficha Pendente!</strong></span></a></br>
                <?php endif; ?>
            </div>	
            <div class="coluna campo-titulo">CPF:</div>
            <div class="coluna campo-valor"><?php echo $row["clients_cpf"];?></div>
            <div class="coluna campo-titulo">Nascimento:</div>
            <div class="coluna campo-valor">
                <?php $data_nascimento = implode(preg_match("~\/~", $row_client['cliente_nascimento']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row_client['cliente_nascimento']) == 0 ? "-" : "/", $row_client['cliente_nascimento']))); ?>
                <?php echo $data_nascimento; ?>
            </div>
        </div>
        <div class="coluna">
            <img src="<?php echo $image_profile_path; ?>" style="height: 130px; box-shadow: 2px 2px 2px black; margin-right: 10%;">
        </div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Órgão:</div>
		<div class="coluna campo-valor">
            <?php if ($administracao == 1):?>
                <select name="vendas_orgao" onchange="consultaAjaxDadosProposta();">
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
            <strong><?php echo $row["vendas_orgao"];?></strong>
            <input type="hidden" name="vendas_orgao" value="<?php echo $row["vendas_orgao"];?>">
            <?php endif;?>
		</div>
		
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Endereço:</div>
		<div class="coluna campo-valor"><?php echo utf8_decode($row_client["cliente_endereco"]);?></div>
		<div class="coluna campo-titulo">Bairro:</div>
		<div class="coluna campo-valor"><?php echo utf8_decode($row_client["cliente_bairro"]);?></div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Endereço de Entrega:</div>
		<div class="coluna campo-valor">
			<?php if ($row_client["cliente_endereco_entrega"]):?>
				<strong><?php echo utf8_decode($row_client["cliente_endereco_entrega"]);?></strong>
				Nº: <strong><?php echo utf8_decode($row_client["cliente_numero"]);?></strong>, Complemento:  <strong><?php echo $row_client["cliente_complemento"];?></strong>
			<?php else: ?>
				Não informado.
			<?php endif;?>
		</div>
		<div class="coluna campo-titulo">Cidade / Estado:</div>
		<div class="coluna campo-valor"><?php echo $row_client["cliente_cidade"];?> - <?php echo $row_client["cliente_uf"];?></div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">CEP:</div>
		<div class="coluna campo-valor"><?php echo $row_client["cliente_cep"];?></div>
		<div class="coluna campo-titulo">Telefone:</div>
		<div class="coluna campo-valor"><?php echo $row_client["cliente_telefone"];?></div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Telefone Pós Venda:</div>
		<div class="coluna campo-valor"><?php echo $row_client["cliente_celular"];?></div>
		<div class="coluna campo-titulo">Data da Venda:</div>
		<?php 
		$yr=strval(substr($row["vendas_dia_venda"],0,4));
		$mo=strval(substr($row["vendas_dia_venda"],5,2));
		$da=strval(substr($row["vendas_dia_venda"],8,2));
		$hr=strval(substr($row["vendas_dia_venda"],11,2));
		$mi=strval(substr($row["vendas_dia_venda"],14,2));
		$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
		?>
		<div class="coluna campo-valor"><?php echo $data_venda;?></div>
	</div>
	<div class="linha">
		<div class="coluna campo-titulo">Consultor:</div>
		<div class="coluna campo-valor">
			<?php if ($administracao == 1):?>
				<select name='vendas_consultor'>
					<?php
					$result_user_form = mysql_query("SELECT id,name,username,unidade FROM jos_users WHERE nivel != 4 ORDER BY name;")
					or die(mysql_error());
					while($row_user_form = mysql_fetch_array( $result_user_form )) {
						if ($row_user_form["id"] == $row["vendas_consultor"]){$selected = "selected";}else{$selected = "";}
						echo "<option value='{$row_user_form['id']}'{$selected}>{$row_user_form['name']} ({$row_user_form['username']})</option>";
					}
					?>  
				</select>      
			<?php else:?>
					<strong><?php echo $row_user['name'];?></strong> (<?php echo $row_user['username'];?>)</br>
					<input name="vendas_consultor" type="hidden" value="<?php echo $row["vendas_consultor"];?>" />
					<?php while($row_grupo = mysql_fetch_array( $result_grupo )){$user_groups = $user_groups.$row_grupo['title']." | ";}?>
					<span style="font-size:6pt"><?php echo $user_groups;?></span>
			<?php endif;?>
		</div>
		<div class="coluna campo-titulo">Unidade:</div>
		<div class="coluna campo-valor"><?php echo $row_user['unidade'];?></div>
	</div>	
</div>
<?php $vendas_base_prod_rs = ($row['vendas_base_prod']>0) ? number_format($row['vendas_base_prod'], 2, ',', '.') : '0' ; ?>
<style>
    .linha-com-imagem .campo-titulo{
        width: 30%;
    }
    .linha-com-imagem .campo-valor{
        width: 65%;
    }
</style>