<?php
include("../../connect_seguro.php");
include("../../utf8.php");
?>


<?php if ($_GET["vendas_pgto"]) : ?>
	<?php
	if ($_GET["apolice_pgto"]) :
		$result_pgto_label = mysqli_query($con, "SELECT pgto_nm FROM sys_vendas_pgto WHERE pgto_id=" . $_GET['vendas_pgto'] . ";")
			or die(mysqli_error($con));
		$row_pgto_label = mysqli_fetch_array($result_pgto_label);
	?>
		<div class="linha_flex">
			<div class="coluna campo-titulo">&nbsp;</div>
			<div class="coluna campo-valor">
				<?php echo $row_pgto_label['pgto_nm']; ?>
				<input name='vendas_pgto' type='hidden' value="<?php echo $_GET['vendas_pgto']; ?>" />
			</div>
		</div>
	<?php endif; ?>
	<?php if ($_GET["vendas_pgto"] == 1) : ?>
		<?php
		if ($_GET['clients_cpf']) {
			$result_cliente = mysqli_query($con, "SELECT cliente_banco, cliente_agencia, cliente_conta FROM sys_inss_clientes WHERE cliente_cpf = " . $_GET['clients_cpf'] . ";")
				or die(mysqli_error($con));
			$row_cliente = mysqli_fetch_array($result_cliente);
		}
		?>
      <div style="display: flex; flex-direction: column; gap: 12px;">
         <div class="linha_flex">
            <span>1º</span>
            <div class="input-special">
               <input type="text" id="vendas_debito_banco" class="info-inputs cad-venda not-required" name="vendas_debito_banco" value="<?php echo $row_cliente["cliente_banco"]; ?>" size="8" maxlength="8" oninput="somenteNumeros(this)" required/>
               <label class="cad-venda " for="vendas_debito_banco">Banco código</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_ag" class="info-inputs cad-venda not-required" name="vendas_debito_ag" value="<?php echo $row_cliente["cliente_agencia"]; ?>" size="8" maxlength="4" oninput="somenteNumeros(this)" required/>
               <label class="cad-venda " for="vendas_debito_ag">Agência</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_ag_dig" class="info-inputs cad-venda not-required" name="vendas_debito_ag_dig" value="" size="8" maxlength="1" oninput="somenteNumeros(this)"/>
               <label class="cad-venda " for="vendas_debito_ag_dig">Agência dígito</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_cc" class="info-inputs cad-venda not-required" name="vendas_debito_cc" value="<?php echo $row_cliente["cliente_conta"]; ?>" size="8" maxlength="8" oninput="somenteNumeros(this)" required/>
               <label class="cad-venda " for="vendas_debito_cc">Conta corrente</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_cc_dig" class="info-inputs cad-venda not-required" name="vendas_debito_cc_dig" style="width: 121px;" maxlength="10" oninput="somenteNumeros(this)" required/>
               <label class="cad-venda " for="vendas_debito_cc_dig">Conta dígito</label>
            </div>
         </div>
      
         <div class="linha_flex">
            <span>2º</span>
            <div class="input-special">
               <input type="text" id="vendas_debito_banco_2" class="info-inputs cad-venda not-required" name="vendas_debito_banco_2" value="<?php echo $row_cliente["cliente_banco"]; ?>" size="8" maxlength="8" oninput="somenteNumeros(this)"/>
               <label class="cad-venda " for="vendas_debito_banco_2">Banco código</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_ag_2" class="info-inputs cad-venda not-required" name="vendas_debito_ag_2" value="<?php echo $row_cliente["cliente_agencia"]; ?>" size="8" maxlength="4" oninput="somenteNumeros(this)"/>

               <label class="cad-venda " for="vendas_debito_ag_2">Agência</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_ag_dig_2" class="info-inputs cad-venda not-required" name="vendas_debito_ag_dig_2" value="" size="8" maxlength="1" oninput="somenteNumeros(this)"/>
               <label class="cad-venda " for="vendas_debito_ag_dig_2">Agência dígito</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_cc_2" class="info-inputs cad-venda not-required" name="vendas_debito_cc_2" value="<?php echo $row_cliente["cliente_conta"]; ?>" size="8" maxlength="8" oninput="somenteNumeros(this)"/>

               <label class="cad-venda " for="vendas_debito_cc_2">Conta corrente</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_cc_dig_2" class="info-inputs cad-venda not-required" name="vendas_debito_cc_dig_2" style="width: 121px;" maxlength="10" oninput="somenteNumeros(this)"/>
               <label class="cad-venda " for="vendas_debito_cc_dig_2">Conta dígito</label>
            </div>
         </div>

         <div class="linha_flex">
            <span>3º</span>
            <div class="input-special">
               <input type="text" id="vendas_debito_banco_3" class="info-inputs cad-venda not-required" name="vendas_debito_banco_3" value="<?php echo $row_cliente["cliente_banco"]; ?>" size="8" maxlength="8" oninput="somenteNumeros(this)"/>
               <label class="cad-venda " for="vendas_debito_banco_3">Banco código</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_ag_3" class="info-inputs cad-venda not-required" name="vendas_debito_ag_3" value="<?php echo $row_cliente["cliente_agencia"]; ?>" size="8" maxlength="4" oninput="somenteNumeros(this)"/>

               <label class="cad-venda " for="vendas_debito_ag_3">Agência</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_ag_dig_3" class="info-inputs cad-venda not-required" name="vendas_debito_ag_dig_3" value="" size="8" maxlength="1" oninput="somenteNumeros(this)"/>
               <label class="cad-venda " for="vendas_debito_ag_dig_3">Agência dígito</label>
            </div>


            <div class="input-special">
               <input type="text" id="vendas_debito_cc_3" class="info-inputs cad-venda not-required" name="vendas_debito_cc_3" value="<?php echo $row_cliente["cliente_conta"]; ?>" size="8" maxlength="8" oninput="somenteNumeros(this)"/>

               <label class="cad-venda " for="vendas_debito_cc_3">Conta corrente</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_debito_cc_dig_3" class="info-inputs cad-venda not-required" name="vendas_debito_cc_dig_3" style="width: 121px;" maxlength="10" oninput="somenteNumeros(this)"/>
               <label class="cad-venda " for="vendas_debito_cc_dig_3">Conta dígito</label>
            </div>
         </div>
      </div>


	<?php elseif ($_GET["vendas_pgto"] == 2) : ?>

		<?php
		if ($_GET['clients_cpf']) {
			$result_cliente_cartoes = mysqli_query($con, "SELECT * FROM sys_clientes_cartoes WHERE cliente_cpf = '" . $_GET['clients_cpf'] . "';")
				or die(mysqli_error($con));
		}
		//echo "rows: ".mysqli_num_rows($result_cliente_cartoes);
		if (mysqli_num_rows($result_cliente_cartoes)) :
		?>
			<div class="linha_flex" style="background: #cc6f31; line-height: 10px; color: #fff;">
				<div class="coluna" style="width: 60%;">Opções de Cartão do Cliente:</div>
				<div class="coluna" style="width: 20%;">Validade:</div>
				<div class="coluna" style="width: 15%;">Bandeira:</div>
			</div>
			<?php while ($row_cliente_cartao = mysqli_fetch_array($result_cliente_cartoes)) : ?>
				<div class="linha_flex">
					<div class="coluna" style="width: 5%;"><input type="radio" id="cliente-cartao" name="cliente-cartao" value="<?php echo $row_cliente_cartao["cartao_numero"]; ?>"></div>
					<div class="coluna" id="numMascarado" style="width: 55%;"><?php echo substr_replace($row_cliente_cartao["cartao_numero"], '*', 15, 16); ?></div>
					<div class="coluna" style="width: 20%;"><?php echo $row_cliente_cartao["cartao_validade"]; ?></div>
					<div class="coluna" id="bandeira" style="width: 15%;">X</div>
					<div class="coluna" id="mesCartao" hidden style="width: 15%;"><?php echo substr($row_cliente_cartao["cartao_validade"], 0, 2); ?></div>
					<div class="coluna" id="anoCartao" hidden style="width: 15%;"><?php echo substr($row_cliente_cartao["cartao_validade"], 3, 8); ?></div>

				</div>
			<?php endwhile; ?>
		<?php endif; ?>
      <div style="display: flex; flex-direction: column; gap: 12px;">
         <div class="linha_flex">
            <div class="input-special">
               <select id="vendas_cartao_adm" class="info-inputs cad-venda" name="vendas_cartao_adm" required>
                  <option value=''></option>
                  <?php
                  $sql_cartoes = "SELECT * FROM sys_vendas_cartoes";
                  $result_cartoes = mysqli_query($con, $sql_cartoes) or die(mysqli_error($con));
                  while ($row_cartao = mysqli_fetch_array($result_cartoes)) :
                  ?>
                     <option value='<?php echo $row_cartao['codigo']; ?>'><?php echo $row_cartao['nome']; ?></option>
                  <?php
                  endwhile;
                  ?>
               </select>
               <label class="cad-venda " for="vendas_cartao_adm">Bandeira Cartão</label>
            </div>
         </div>
         <div class="linha_flex">
            <div class="input-special">
               <input type="text" id="vendas_cartao_num" class="info-inputs cad-venda" name="vendas_cartao_num" id="vendas_cartao_num" size="19" maxlength="19" autocomplete="off" oninput="somenteNumeros(this)" required/>
               <label class="cad-venda " for="vendas_cartao_num">Nº do Cartão</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_cartao_validade_mes" class="info-inputs cad-venda" name="vendas_cartao_validade_mes" id="vendas_cartao_num" maxlength="2" placeholder="(mm)" autocomplete="off" oninput="somenteNumeros(this)" style="width: 130px;" required/>
               <label class="cad-venda " for="vendas_cartao_validade_mes">Validade mês</label>
            </div>
            <div class="input-special">
               <input type="text" id="vendas_cartao_validade_ano" class="info-inputs cad-venda" name="vendas_cartao_validade_ano" id="vendas_cartao_num" maxlength="4" placeholder="(aaaa)" autocomplete="off" oninput="somenteNumeros(this)" style="width: 130px;" required/>
               <label class="cad-venda " for="vendas_cartao_validade_ano">Validade ano</label>
            </div>
         </div>

         <?php if ($_GET['vendas_banco'] == 11) : ?>
            <div class="linha_flex">
               <div class="input-special no-style-top">
                  <input type="date" class="info-inputs  cad-venda" name="vendas_vencimento_fatura" id="vendas_vencimento_fatura">
                  <label for="vendas_vencimento_fatura" class="cad-venda">Vencimento Fatura</label>
               </div>
               <!-- position: relative; left: 50%; transform: translateX(-50%); -->
               <button id="consulta_cartao_credito_api" style="width: 310px;">Verificar o cartão</button>
               <div id="result_cartao_credito_api" style="text-align: center; padding: 10px; display: none;"></div>
            </div>
         <?php endif; ?>
      </div>
	<?php elseif ($_GET["vendas_pgto"] == 6) : ?>
		<div class="linha_flex">
			<div align="center">Chave PIX deve ser enviada pela auditoria.</div>
		</div>
	<?php else : ?>
		<div class="linha_flex">
			<div align="center">Dados de Desconto em Folha já salvos na Ficha do cliente.</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
<script>
   formValidation.loadIndividualValidation(form_seguros);
</script>