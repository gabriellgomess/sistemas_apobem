<?php

$tipo_cob = (isset($_POST['tipo_cob'])) ? $_POST['tipo_cob'] : '';
$edicao_dados_financeiros = (isset($_POST['edicao'])) ? $_POST['edicao'] : $edicao;

?>

        <?php if ($row["vendas_pgto"] == 1 || $tipo_cob == 1) { ?>
            <div class="linha" style="position: relative">
                <div class="coluna campo-titulo">Banco:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_debito_banco" value="<?php echo $row["vendas_debito_banco"];?>" size="1" maxlength="4"<?php if ($edicao_dados_financeiros == 1){echo " onKeyPress='return SomenteNumero(event)'";}else{echo " readonly='true'";}?>/>
                </div>
                <!--/div-->

                <!--div class="linha"-->
                <div class="coluna campo-titulo">Agência: </div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_debito_ag" value="<?php echo $row["vendas_debito_ag"];?>" size="3" maxlength="5"<?php if ($edicao_dados_financeiros != 1){echo " readonly='true'";}?>/>-
                    <input type="text" name="vendas_debito_ag_dig" value="<?php echo $row["vendas_debito_ag_dig"];?>" size="1" maxlength="3"<?php if ($edicao_dados_financeiros != 1){echo " readonly='true'";}?>/>
                </div>
                <div class="coluna campo-titulo">Conta Corrente:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_debito_cc" value="<?php echo $row["vendas_debito_cc"];?>" size="8" maxlength="12"<?php if ($edicao_dados_financeiros != 1){echo " readonly='true'";}?>/>
                </div>
                <div id="verifica_conta" style="cursor: pointer; display: inline-block; position: absolute; right: 40px; top: 5px;" onClick="verificaConta();">Verificar</div>
            </div>

            <div class="linha">
                <div class="coluna campo-titulo">Banco:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_debito_banco_2" value="<?php echo $row["vendas_debito_banco_2"];?>" size="4" maxlength="4"<?php if ($edicao_dados_financeiros == 1){echo " onKeyPress='return SomenteNumero(event)'";}else{echo " readonly='true'";}?>/>
                </div>
                <div class="coluna campo-titulo">Agência: </div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_debito_ag_2" value="<?php echo $row["vendas_debito_ag_2"];?>" size="8" maxlength="8"<?php if ($edicao_dados_financeiros != 1){echo " readonly='true'";}?>/>
                </div>
                <!--/div-->

                <!--div class="linha"-->
                <div class="coluna campo-titulo">Conta Corrente:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_debito_cc_2" value="<?php echo $row["vendas_debito_cc_2"];?>" size="12" maxlength="14"<?php if ($edicao_dados_financeiros != 1){echo " readonly='true'";}?>/>
                </div>
            </div>

            <div class="linha">
                <div class="coluna campo-titulo">Banco:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_debito_banco_3" value="<?php echo $row["vendas_debito_banco_3"];?>" size="4" maxlength="4"<?php if ($edicao_dados_financeiros == 1){echo " onKeyPress='return SomenteNumero(event)'";}else{echo " readonly='true'";}?>/>
                </div>
                <div class="coluna campo-titulo">Agência: </div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_debito_ag_3" value="<?php echo $row["vendas_debito_ag_3"];?>" size="8" maxlength="8"<?php if ($edicao_dados_financeiros != 1){echo " readonly='true'";}?>/>
                </div>
                <!--/div-->

                <!--div class="linha"-->
                <div class="coluna campo-titulo">Conta Corrente:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_debito_cc_3" value="<?php echo $row["vendas_debito_cc_3"];?>" size="12" maxlength="14"<?php if ($edicao_dados_financeiros != 1){echo " readonly='true'";}?>/>
                </div>
            </div>
        <?php }elseif ($row["vendas_pgto"] == 4 || $row["vendas_pgto"] == 3 || $tipo_cob == 3 || $tipo_cob == 4 || $tipo_cob == 5){ ?>
            
        <?php }elseif (($edicao_dados_financeiros == 1) || ($supervisor_equipe_vendas == 1 || $tipo_cob == 2)|| $super_user ){ ?>
            <div class="linha">
                <!-- ADM DO CARTAO -->
                <div class="coluna campo-titulo">Adm. do Cartão:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_cartao_adm" value="<?php echo $row["vendas_cartao_adm"];?>" size="15" maxlength="20"<?php if ($edicao_dados_financeiros == 0){echo " readonly='true'";}?>/>
                </div>
            </div>

            <div class="linha">
                <!-- BANDEIRA -->
                <div class="coluna campo-titulo">Bandeira:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_cartao_band" value="<?php echo $row["vendas_cartao_band"];?>" size="10" maxlength="20"<?php if ($edicao_dados_financeiros == 0){echo " readonly='true'";}?>/>
                </div>

                <div class="coluna campo-titulo">N° do Cartão:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_cartao_num" id="vendas_cartao_num" value="<?php echo $row["vendas_cartao_num"];?>" size="19" maxlength="19"<?php if ($edicao_dados_financeiros == 1){echo " onKeyPress='return SomenteNumero(event)'";}else{echo " readonly='true'";}?>/>
                </div>
            </div>

            <div class="linha">
                <!-- VALIDADE -->
                <div class="coluna campo-titulo">Validade:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="vendas_cartao_validade_mes" value="<?php echo $row["vendas_cartao_validade_mes"];?>" size="2" maxlength="2"<?php if ($edicao_dados_financeiros == 0){echo " readonly='true'";}?>/>&nbsp;(MM) 
                    <input type="text" name="vendas_cartao_validade_ano" value="<?php echo $row["vendas_cartao_validade_ano"];?>" size="4" maxlength="4"<?php if ($edicao_dados_financeiros == 0){echo " readonly='true'";}?>/>&nbsp;(AAAA)
                </div>
                <div id="cvv_campo" style="display: none;">
                <div class="coluna campo-titulo">CVV:</div>
                <div class="coluna campo-valor">
                    <input type="text" name="cartao_cvv" id="cartao_cvv" value="<?php echo $row["vendas_cartao_cvv"];?>" size="2" maxlength="4"<?php if ($edicao_dados_financeiros == 1){echo " onKeyPress='return SomenteNumero(event)'";}else{echo " readonly='true'";}?>/>
                </div>
                </div>
            </div>
        <?php }else{ ?>
        </div>
    <?php } ?>
</div>  
</div>