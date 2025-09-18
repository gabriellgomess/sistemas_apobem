<script type="text/javascript" src="sistema/vendas/js/softwareexpress/verifica_cartao_edita_venda.js"></script>
<div class="linha">
    <h3 class="mypets2">Dados Financeiros:</h3>
    <div class="thepet2">
        <div class="linha">
            <!-- FORMA PAGAMENTO -->
            <div class="coluna campo-titulo">Forma de Pagamento:</div>
            <div class="coluna campo-valor">
                <select name="vendas_pgto" onchange="this.form.submit()">
					<option value="">--Selecione--</option>
					<?php
						$result_pgto = mysql_query("SELECT * FROM sys_vendas_pgto ORDER BY pgto_nm;")
							or die(mysql_error());
							while($row_pgto = mysql_fetch_array( $result_pgto ))
							{
								if ($row_pgto["pgto_id"] == $row["vendas_pgto"]){$selected = "selected";}else{$selected = "";}
								echo "<option value='{$row_pgto['pgto_id']}'{$selected}>{$row_pgto['pgto_nm']}</option>";
							}
					?>
				</select>
            </div>
        </div> <!-- precisa ser removido apos o ajuste do alinhamento -->
        <!-- carrega os campos adicionais por js -->
        <div id="campos_cobranca"></div>
        <!-- carrega os campos adicionais por js -->
        <?php if ($row["vendas_pgto"] == 1) { ?>
        <div class="linha" style="position: relative">
            <div class="coluna campo-titulo">Banco:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_debito_banco" value="<?php echo $row["vendas_debito_banco"];?>" size="1" maxlength="4"<?php if ($edicao == 1){echo " onKeyPress='return SomenteNumero(event)'";}else{echo " readonly='true'";}?>/>
            </div>
        <!--/div-->

        <!--div class="linha"-->
            <div class="coluna campo-titulo">Agência: </div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_debito_ag" value="<?php echo $row["vendas_debito_ag"];?>" size="3" maxlength="5"<?php if ($edicao != 1){echo " readonly='true'";}?>/>-
                <input type="text" name="vendas_debito_ag_dig" value="<?php echo $row["vendas_debito_ag_dig"];?>" size="1" maxlength="3"<?php if ($edicao != 1){echo " readonly='true'";}?>/>
            </div>
            <div class="coluna campo-titulo">Conta Corrente:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_debito_cc" value="<?php echo $row["vendas_debito_cc"];?>" size="8" maxlength="12"<?php if ($edicao != 1){echo " readonly='true'";}?>/>
                <input type="text" name="vendas_debito_cc_dig" value="<?php echo $row["vendas_debito_cc_dig"];?>" size="1" maxlength="2" <?php if ($edicao != 1){echo " readonly='true'";}?>/>
            </div>
            <div id="verifica_conta" style="cursor: pointer; display: inline-block; position: absolute; right: 40px; top: 5px;" onClick="verificaConta();">Verificar</div>
        </div>

        <div class="linha">
            <div class="coluna campo-titulo">Banco:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_debito_banco_2" value="<?php echo $row["vendas_debito_banco_2"];?>" size="4" maxlength="4"<?php if ($edicao == 1){echo " onKeyPress='return SomenteNumero(event)'";}else{echo " readonly='true'";}?>/>
            </div>
            <div class="coluna campo-titulo">Agência: </div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_debito_ag_2" value="<?php echo $row["vendas_debito_ag_2"];?>" size="8" maxlength="8"<?php if ($edicao != 1){echo " readonly='true'";}?>/>
            </div>
        <!--/div-->

        <!--div class="linha"-->
            <div class="coluna campo-titulo">Conta Corrente:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_debito_cc_2" value="<?php echo $row["vendas_debito_cc_2"];?>" size="12" maxlength="14"<?php if ($edicao != 1){echo " readonly='true'";}?>/>
                <input type="text" name="vendas_debito_cc_dig_2" value="<?php echo $row["vendas_debito_cc_dig_2"];?>" size="1" maxlength="2" <?php if ($edicao != 1){echo " readonly='true'";}?>/>
            </div>
        </div>

        <div class="linha">
            <div class="coluna campo-titulo">Banco:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_debito_banco_3" value="<?php echo $row["vendas_debito_banco_3"];?>" size="4" maxlength="4"<?php if ($edicao == 1){echo " onKeyPress='return SomenteNumero(event)'";}else{echo " readonly='true'";}?>/>
            </div>
            <div class="coluna campo-titulo">Agência: </div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_debito_ag_3" value="<?php echo $row["vendas_debito_ag_3"];?>" size="8" maxlength="8"<?php if ($edicao != 1){echo " readonly='true'";}?>/>
            </div>
        <!--/div-->

        <!--div class="linha"-->
            <div class="coluna campo-titulo">Conta Corrente:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_debito_cc_3" value="<?php echo $row["vendas_debito_cc_3"];?>" size="12" maxlength="14"<?php if ($edicao != 1){echo " readonly='true'";}?>/>
                <input type="text" name="vendas_debito_cc_dig_3" value="<?php echo $row["vendas_debito_cc_dig_3"];?>" size="1" maxlength="2" <?php if ($edicao != 1){echo " readonly='true'";}?>/>
            </div>
        </div>
    <?php }elseif ($row["vendas_pgto"] == 4 || $row["vendas_pgto"] == 3 ){?>
        <!-- nada para mostrar -->
        <?php }elseif ($edicao == 1 || $supervisor_equipe_vendas == 1 || $auditores_seguros = 1 || $super_user){ ?>

            <div class="linha">
                <table class="blocos">
                    <thead>
                        <tr>
                            <th>Administradora</th>
                            <th>Nº</th>
                            <th>Validade</th>
                            <th>Vencimento da Fatura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo getBandeiraCartaoById($row["vendas_cartao_adm"]);?></td>
                            <td><?php echo substr($row["vendas_cartao_num"], 0, 4)."********".substr($row["vendas_cartao_num"], -4);?></td>
                            <td><?php echo $row["vendas_cartao_validade_mes"];?>/<?php echo $row["vendas_cartao_validade_ano"];?></td>
                            <td><?php echo implode("/",array_reverse(explode("/", str_replace("-","/",$row["vendas_vencimento_fatura"])))); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="linha">
                <h3 style="margin: 0;">Atualizar Dados do Cartão</h3>
            </div>

        <div class="linha">
            <!-- ADM DO CARTAO -->
            <div class="coluna campo-titulo">Adm. do Cartão:</div>
            <div class="coluna campo-valor">                
                <select id="vendas_cartao_adm" name="vendas_cartao_adm">
                <option value=''>--Cartão--</option>
                <?php 
                    $sql_cartoes = "SELECT * FROM sys_vendas_cartoes";
                    $result_cartoes = mysql_query($sql_cartoes) or die(mysql_error());
                    while ($row_cartao = mysql_fetch_assoc($result_cartoes)):
                ?>
                    <option value='<?php echo $row_cartao['codigo']; ?>'><?php echo $row_cartao['nome']; ?></option>
                <?php
                    endwhile;
                ?>
                </select>
            </div>
        </div>

        <div class="linha">
            <!-- BANDEIRA -->
<!--             <div class="coluna campo-titulo">Bandeira:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_cartao_band" value="<?php //echo $row["vendas_cartao_band"];?>" size="10" maxlength="20"<?php if ($edicao == 0){echo " readonly='true'";}?>/>
            </div> -->

            <!-- NUM DO CARTAO -->
            <div class="coluna campo-titulo">N° do Cartão:</div>
            <div class="coluna campo-valor">
                <input type="text" autocomplete="off" name="vendas_cartao_num" id="vendas_cartao_num" value="<?php //echo $row["vendas_cartao_num"];?>" size="19" maxlength="19" <?php if($edicao == 0){echo " readonly='true'";}else{echo " onKeyPress='return SomenteNumero(event)'";}?>/>
            </div>
        </div>

        <div class="linha">
            <!-- VALIDADE -->
            <div class="coluna campo-titulo">Validade:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_cartao_validade_mes" value="<?php //echo $row["vendas_cartao_validade_mes"];?>" size="2" maxlength="2" <?php if ($edicao == 0){echo " readonly='true'";}?>/>&nbsp;(MM) 
				<input type="text" name="vendas_cartao_validade_ano" value="<?php //echo $row["vendas_cartao_validade_ano"];?>" size="4" maxlength="4" <?php if ($edicao == 0){echo " readonly='true'";}?>/>&nbsp;(AAAA)
            </div>
            <div style="display: none;">
                <div class="coluna campo-titulo">CVV:</div>
                <div class="coluna campo-valor">
                    <input type="text" autocomplete="off" size="3" maxlength="3" name="cartao_cvv" value="666<?php //echo $row["vendas_cartao_cvv"]; ?>">
                </div>
            </div>
        </div>
        <?php if ($row["vendas_banco"]==11) : ?>

        <div class="linha">
            <div class="coluna campo-titulo">Vencimento Fatura:</div>
            <div class="coluna campo-valor">
                <input id="vendas_vencimento_fatura" name="vendas_vencimento_fatura" type="date">
            </div>
        </div>

            <div class="container_consulta_cartao_credito_api" <?php if ($row["vendas_status"] != 100): ?>style="display: none;"<?php endif; ?>>
                <div class="linha" style="padding: 15px 0px 15px 0px;">

                    <div style="text-align: center; padding: 10px; display: none;" id="result_cartao_credito_api"></div>

                    <button class="button" 
                            id="consulta_cartao_credito_api"
                            venda_id ="<?php echo $row['vendas_id']; ?>"
                            style="position: relative; left: 50%; width: 300px;  transform: translate(-50%, 0%);" 
                            onclick='return false;'>
                                Verificar disponibilidade do cartão
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <?php }else{ ?>
        </div>
        <?php } ?>
    </div>  
</div>

<?php 
function getBandeiraCartaoById($id)
{
    $sql_cartoes = "SELECT * FROM sys_vendas_cartoes WHERE id='".$id."'";
    $result_cartoes = mysql_query($sql_cartoes) or die(mysql_error());
    $row_cartao = mysql_fetch_assoc($result_cartoes);
    return $row_cartao['nome'];
}
?>