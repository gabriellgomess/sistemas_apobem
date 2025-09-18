<div class="linha">
    <h3 class="mypets2">Dados da Proposta:</h3>
    <div class="thepet2">
        <div class="linha">
            <!-- NUMERO DA PROPOSTA -->
            <div class="coluna campo-titulo">Nº da Proposta:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_proposta" value="<?php echo $row["vendas_proposta"]; ?>" size="15" maxlength="20" <?php if ($edicao == 1) {
                                                                                                                                        echo " utils='numerico'";
                                                                                                                                    } else {
                                                                                                                                        echo " readonly='true'";
                                                                                                                                    } ?> />
            </div>
            <div class="coluna campo-titulo">Status:</div>
            <div class="coluna campo-valor">
                <?php
                if ($row_status_nm['status_pai']) {
                    $result_status_pai = mysql_query("SELECT status_nm FROM sys_vendas_status_seg WHERE status_id = " . $row_status_nm['status_pai'] . ";")
                        or die(mysql_error());
                    $row_status_pai = mysql_fetch_array($result_status_pai);
                    if ($administracao == 1) {
                        $vendas_status_nm = $row_status_pai['status_nm'] . " - " . $vendas_status_nm;
                    } else {
                        $vendas_status_nm = $row_status_pai['status_nm'];
                    }
                }
                ?>
                <input type="hidden" name="vendas_user" value="<?php echo $row["vendas_user"] ?>" />
                <?php echo $vendas_status_nm; ?></br>
                <img src='sistema/imagens/status_<?php echo $vendas_status_img; ?>.png'>
            </div>
        </div>
        <div class="linha">
            <!--  NUMERO DA APOLICE -->
            <div class="coluna campo-titulo">Nº da Apólice:</div>
            <div class="coluna campo-valor">
                <input type="text" name="vendas_num_apolice" value="<?php echo $row["vendas_num_apolice"]; ?>" size="15" maxlength="20" <?php if ($edicao == 0) {
                                                                                                                                            echo " readonly='true'";
                                                                                                                                        } ?> />
            </div>

            <!-- APOLICE -->
            <div class="coluna campo-titulo">Apólice:</div>
            <div class="coluna campo-valor">
                <?php if ($edicao == 1) : ?>
                    <select name='vendas_apolice'>
                        <?php
                        $result_apolice = mysql_query("SELECT * FROM sys_vendas_apolices WHERE apolice_banco='" . $row['vendas_banco'] . "' ORDER BY apolice_nome;")
                            or die(mysql_error());
                        while ($row_apolice = mysql_fetch_array($result_apolice)) {
                            if ($row_apolice["apolice_id"] == $row["vendas_apolice"]) {
                                $selected = "selected";
                                $vendas_valor = $row_apolice['apolice_valor'];
                            } else {
                                $selected = "";
                            }
                            $apolice_valor = ($row_apolice['apolice_valor'] > 0) ? number_format($row_apolice['apolice_valor'], 2, ',', '.') : '0';
                            echo "<option value='{$row_apolice['apolice_id']}' apolice_valor='{$row_apolice['apolice_valor']}' apolice_tipo='{$row_apolice['apolice_nome']}' {$selected}>{$row_apolice['apolice_nome']} - R$ {$apolice_valor}</option>";
                        }
                        ?>
                    </select>
                    <?php if (!$vendas_valor) : ?>
                        <?php $vendas_valor = ($row['vendas_valor'] > 0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0'; ?>
                        R$ <input value="<?php echo $vendas_valor; ?>" name="vendas_valor" type="text" size="6" maxlength="6" onKeyPress="return(MascaraMoeda(this,'.',',',event))" />
                    <?php endif; ?>
                <?php else : ?>
                    <input name="vendas_apolice" type="hidden" id="vendas_apolice" value="<?php echo $row['vendas_apolice']; ?>" />
                    <?php $vendas_valor = ($row['vendas_valor'] > 0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0'; ?>
                    R$ <?php echo $vendas_valor; ?>
                    <input type="hidden" name="vendas_valor" value="<?php echo $vendas_valor; ?>" />
                <?php endif; ?>
            </div>
        </div>
        <div class="linha">
            <!-- SEGURADORA -->
            <div class="coluna campo-titulo">Seguradora:</div>
            <div class="coluna campo-valor">
                <?php if ($edicao == 1) : ?>
                    <select name="vendas_banco" onchange="this.form.submit()">
                        <?php
                        $result_banco = mysql_query("SELECT * FROM sys_vendas_banco_seg ORDER BY banco_nm;")
                            or die(mysql_error());
                        if (!$row["vendas_banco"]) {
                            echo "<option value='' selected> --- Selecione --- </option>";
                        }
                        while ($row_banco = mysql_fetch_array($result_banco)) {
                            if ($row_banco["banco_id"] == $row["vendas_banco"]) {
                                $selected = " selected";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='{$row_banco['banco_id']}'{$selected}>{$row_banco['banco_nm']}</option>";
                        }
                        ?>
                    </select>
                <?php else : ?>
                    <input name="vendas_banco" type="hidden" id="vendas_banco" value="<?php echo $row['vendas_banco']; ?>" />
                    <?php
                    $result_banco = mysql_query("SELECT * FROM sys_vendas_banco_seg WHERE banco_id='" . $row['vendas_banco'] . "';")
                        or die(mysql_error());
                    $row_banco = mysql_fetch_array($result_banco);
                    echo $row_banco['banco_nm'];
                    ?>
                <?php endif; ?>
            </div>
            <!-- VENCIMENTO -->
            <div class="coluna campo-titulo">Vencimento:</div>
            <div class="coluna campo-valor">
                <?php if ($edicao == 1 || $auditores == 1) : ?>
                    <select name='vendas_dia_desconto'>
                        <?php
                        if (!$row["vendas_dia_desconto"]) {
                            echo "<option value='' selected> --- Não Informado --- </option>";
                        }
                        for ($i = 1; $i < 31; $i++) {
                            if ($i < 10) {
                                $dia = "0" . $i;
                            } else {
                                $dia = $i;
                            }
                            if ($dia == $row["vendas_dia_desconto"]) {
                                $selected = " selected";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $dia . "'" . $selected . ">" . $dia . "</option>";
                        }
                        ?>
                    </select>
                <?php else : ?>
                    Vencimento dia <?php echo $row['vendas_dia_desconto']; ?>.
                    <input type="hidden" name="vendas_dia_desconto" value="<?php echo $row['vendas_dia_desconto']; ?>">
                <?php endif; ?>
            </div>
            <input type="hidden" name="vencimento" value="<?php echo $row['vendas_dia_desconto']; ?>" />
        </div>
        <div class="linha">
            <div class="coluna campo-titulo">Alterado em:</div>
            <div class="coluna campo-valor">
                <?php
                $yr = strval(substr($row["vendas_alteracao"], 0, 4));
                $mo = strval(substr($row["vendas_alteracao"], 5, 2));
                $da = strval(substr($row["vendas_alteracao"], 8, 2));
                $hr = strval(substr($row["vendas_alteracao"], 11, 2));
                $mi = strval(substr($row["vendas_alteracao"], 14, 2));
                $data_alteracao = date("d/m/Y H:i:s", mktime($hr, $mi, 0, $mo, $da, $yr));
                ?>
                <?php if ($data_alteracao == "30/11/1999 00:00:00") : ?>
                    <strong>Nunca Alterada.</strong>
                <?php else : ?>
                    <strong><?php echo $data_alteracao; ?></strong> " , por "<strong><?php if ($row["vendas_user"] == "Importer") {
                                                                                            echo "Importador automático";
                                                                                        } else {
                                                                                            echo $row["vendas_user"];
                                                                                        } ?></strong> "
                <?php endif; ?>
            </div>
            <?php if ($administracao == 1) : ?>
                <div class="coluna campo-titulo">Turno do acionamento:</div>
                <div class="coluna campo-valor">
                    <select name='vendas_turno'>
                        <?php
                        $result_turno = mysql_query("SELECT * FROM sys_vendas_turno;")
                            or die(mysql_error());
                        while ($row_turno = mysql_fetch_array($result_turno)) {
                            if ($row_turno["sys_vendas_turno_id"] == $row["vendas_turno"]) {
                                $selected = "selected";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='{$row_turno['sys_vendas_turno_id']}'{$selected}>{$row_turno['sys_vendas_turno_nome']}</option>";
                        }
                        ?>
                    </select>
                </div>
            <?php else : ?>
                <div class="coluna campo-titulo">Turno do acionamento:</div>
                <div class="coluna campo-valor">
                    <?php
                    $result_turno = mysql_query("SELECT sys_vendas_turno_nome FROM sys_vendas_turno WHERE sys_vendas_turno_id = " . $row['vendas_turno'] . ";")
                        or die(mysql_error());
                    $row_turno = mysql_fetch_array($result_turno);
                    echo $row_turno["sys_vendas_turno_nome"];
                    ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>