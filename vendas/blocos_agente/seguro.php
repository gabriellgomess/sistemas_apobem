<?php if($row['vendas_tipo_contrato'] != "6"): ?>
	<div class="linha">
		<h3 class="mypets2">Seguro:</h3>
		<div class="thepet2">

			<div class="linha">
				<div class="coluna campo-titulo">Nome do Beneficiário:</div>
				<div class="coluna campo-valor">
					<input type="text" name="vendas_applus_ben" value="<?php echo $row["vendas_applus_ben"];?>" size="20"<?php if ($edicao == 0){echo " readonly='true'";}?>/>
				</div>

				<div class="coluna campo-titulo">Parentesco:</div>
				<div class="coluna campo-valor">
					<input type="text" name="vendas_applus_parent" value="<?php echo $row["vendas_applus_parent"];?>" size="20"<?php if ($edicao == 0){echo " readonly='true'";}?>/>
				</div>
			</div>

			<div class="linha">
				<div class="coluna campo-titulo">Valor da Parcela:<br>(somente números)</div>
				<div class="coluna campo-valor">
					<?php $vendas_applus_valor = ($row['vendas_applus_valor']>0) ? number_format($row['vendas_applus_valor'], 2, ',', '.') : '0' ;?>
					R$ <input type="text" name="vendas_applus_valor" value="<?php echo $vendas_applus_valor;?>" size="15"<?php if ($edicao == 1){echo " onKeyPress='return(MascaraMoeda(this,'.',',',event))'";}else{echo " readonly='true'";}?>/>
				</div>

				<div class="coluna campo-titulo">Seguro Consignado Protegido:</div>
				<div class="coluna campo-valor">
					<select name="vendas_seguro_protegido">
				  		<option value="1"<?php if ($row['vendas_seguro_protegido'] == "1"){echo " selected";}?>>Não</option>
				  		<option value="2"<?php if ($row['vendas_seguro_protegido'] == "2"){echo " selected";}?>>Sim</option>
					</select>
				</div>
			</div>
	</div>
<?php endif;?>