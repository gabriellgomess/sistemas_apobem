<div class="linha">
    <h3 id="opvenda" class="mypets2">Operacional da Venda:</h3>
    <div class="thepet2">
        <div class="linha" id="status_op">
            <div class="coluna campo-titulo">Status:</div>
            <div class="coluna campo-valor" style="position: relative;" id="fpagamentos"><div id="alerta_pagamento"></div>
				<?php	
				$date1=date_create($row_client['cliente_nascimento']);
				$date2=date_create(date("Y-m-d"));
				if(($row['vendas_dia_ativacao']) && ($row['vendas_dia_ativacao'] != "0000-00-00")){$date2=date_create($row['vendas_dia_ativacao']);}
				$diff=date_diff($date1,$date2);
				$idade_anos = ($diff->format("%y Year"));
				$idade_meses = ($diff->format("%m Month"));
				$total_idade_meses = $idade_anos * 12 + $idade_meses;
				
				if($total_idade_meses > 923 && $row["vendas_banco"] == 11){
					$filtro_idade = " AND (status_img != 6 OR status_id = ".$row['vendas_status'].")";
					echo "IDADE NÃO PERMITIDA! IDADE EM MESES: ".$total_idade_meses."<br>";
				}
				//$queryFiltroIdade = "SELECT status_id,status_nm FROM sys_vendas_status_seg WHERE status_pai=0".$filtro_idade." ORDER BY status_nm ASC;";
				?>
				<select id="select_venda_status" name="vendas_status" onchange="verificaEnderecoGravacao()">	
					<?php
					$result_status_list = mysql_query("SELECT status_id,status_nm FROM sys_vendas_status_seg WHERE status_pai=0".$filtro_idade." ORDER BY status_nm ASC;")
					or die(mysql_error());
					while($row_status_list = mysql_fetch_array( $result_status_list )) {

						if ($row_status_list["status_id"] == $row["vendas_status"]){$selected = " selected";}else{$selected = "";}
						if( $row_status_list["status_id"] == 19 || $row_status_list["status_id"] == 76 )
						{
							if($selected == " selected" || $sup_operacional_seg || $auditores_seguros || $super_user || $retencao || $cobranca || $diretoria)
							{
								echo "<option value='{$row_status_list['status_id']}'{$selected}>{$row_status_list['status_nm']}</option>";
								$result_substatus_list = mysql_query("SELECT status_id,status_nm FROM sys_vendas_status_seg WHERE status_pai=".$row_status_list['status_id']." ORDER BY status_id;")
								or die(mysql_error());
								while($row_substatus_list = mysql_fetch_array( $result_substatus_list )) {
									if ($row_substatus_list["status_id"] == $row["vendas_status"]){$selected = " selected";}else{$selected = "";}
									echo "<option value='{$row_substatus_list['status_id']}'{$selected}> -- {$row_substatus_list['status_nm']}</option>";	
								}
							}

						}else
						{
							echo "<option value='{$row_status_list['status_id']}'{$selected}>{$row_status_list['status_nm']}</option>";
								$result_substatus_list = mysql_query("SELECT status_id,status_nm FROM sys_vendas_status_seg WHERE status_pai=".$row_status_list['status_id']." ORDER BY status_id;")
								or die(mysql_error());
								while($row_substatus_list = mysql_fetch_array( $result_substatus_list )) {
									if ($row_substatus_list["status_id"] == $row["vendas_status"]){$selected = " selected";}else{$selected = "";}
									echo "<option value='{$row_substatus_list['status_id']}'{$selected}> -- {$row_substatus_list['status_nm']}</option>";	
								}
						}						
					}
					?>
                </select>
                <a href="index.php?option=com_k2&view=item&id=88:cadastro-de-acionamento&Itemid=123&tmpl=component&print=1&clients_cpf=<?php echo $row["cliente_cpf"]; ?>&username=<?php echo $username; ?>&clients_nm=<?php echo $row_client["cliente_nome"]; ?>&clients_employer=INSS&acao=novo_acionamento&adiar=<?php echo $row["vendas_id"]; ?>" rel="lyteframe" rev="width: 650px; height: 500px; scroll:no;" title="Adiar Venda nº <?php echo $row["vendas_id"]; ?>"><button name="adiar_venda" type="button" value="Adiar Venda">Adiar Venda</button></a>
			</div>
			<div class="coluna campo-titulo">Data de Ativação:</div>
                <div class="coluna campo-valor">
                	<?php $vendas_dia_ativacao = implode(preg_match("~\/~", $row['vendas_dia_ativacao']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_ativacao']) == 0 ? "-" : "/", $row['vendas_dia_ativacao'])));?>
                	<p class="lastup"><input type="text" class="w8em format-d-m-y highlight-days-67" id="dp-normal-2" name="dp-normal-2" maxlength="10" size="8" value="<?php echo $vendas_dia_ativacao;?>" /></p> 
            </div>
            <?php
                if ($row['vendas_dia_intencionamento']) {
                    $vendas_dia_intencionamento = implode(preg_match("~\/~", $row['vendas_dia_intencionamento']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_intencionamento']) == 0 ? "-" : "/", $row['vendas_dia_intencionamento'])));
                }
            ?>
        	<div class="coluna campo-titulo">Data Intencionamento:</div>
            <div class="coluna campo-valor">
            	<p class="lastup">
            		<input type="text" class="w8em format-d-m-y highlight-days-67" id="data_intencao" name="data_intencionamento" maxlength="10" size="8" value="<?php echo $vendas_dia_intencionamento; ?>" />
            	</p>
        	</div>

            <div class="coluna campo-titulo">Forma Envio Kit-Certificado:</div>
            <div class="coluna campo-valor">
                <select id="forma_envio_kitcert" name="forma_envio_kitcert">
                    <option value="">--- Selecione ---</option>
                    <?php 
                        $sql_kc = "SELECT * FROM sys_vendas_seg_kitcert_envio_tipo";
                        $result_kc = mysql_query($sql_kc) or die(mysql_error());
                        while ($row_kc = mysql_fetch_assoc($result_kc)):
                        ?>
                        <option value="<?php echo $row_kc['id']; ?>" <?php if($row_kc['id'] == $row["forma_envio_kitcert"]){ echo " selected"; }; ?>><?php echo $row_kc['nome']; ?></option>
                        <?php
                        endwhile;
                    ?>
                </select>
            </div>

        </div>
        <div class="linha">
            <div class="coluna campo-titulo">Endereço da Gravação, no S:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_gravacao" onchange="verificaEnderecoGravacao()" onkeyup="verificaEnderecoGravacao()" value="<?php echo $row["vendas_gravacao"];?>" size="70" maxlength="100"<?php if ($edicao == 0){echo " readonly='true'";}?>/>			
                </br>
                <span id='msg-addendereco' style='color: red;'></span>
            </div>
            <div class="coluna campo-titulo">Início da auditoria:</div>
            <div class="coluna campo-valor">
            	<?php echo $row["vendas_inicio_auditoria"]; ?>
            </div>
        </div>
    </div>
</div>