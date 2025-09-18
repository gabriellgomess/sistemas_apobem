<?php 

?>
<div class="linha">
    <h3 class="mypets2" style="text-align: center">Histórico da Venda:</h3>
    <div class="thepet2">
        <div class="linha">
            <table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                <tr>
                    <td width="15%"><div align="left"><strong>Consultor / Data</strong></div></td>
                    <td width="60%"><div align="left"><strong>Observação</strong></div></td>
                    <td width="20%"><div align="left"><strong>Status</strong></div></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="scroller_cobranca">
                            <table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                                <tbody>
                                <?php
                                while($row_registros = mysql_fetch_array( $result_registros )) {
                                    $sql_status_nome = "SELECT status_nm
                                            FROM sys_vendas_status_seg 
                                            WHERE status_id = '".$row_registros['registro_status']."' LIMIT 0,1;";
                                    $result_status_nome = mysql_query($sql_status_nome) or die("Error:" . mysql_error());
                                    $row_status_nome = mysql_fetch_assoc($result_status_nome);
                                    $status_nome = $row_status_nome['status_nm'];

                                    echo "<tr class='even'>";
                                    $yr=strval(substr($row_registros["registro_data"],0,4));
                                    $mo=strval(substr($row_registros["registro_data"],5,2));
                                    $da=strval(substr($row_registros["registro_data"],8,2));
                                    $hr=strval(substr($row_registros["registro_data"],11,2));
                                    $mi=strval(substr($row_registros["registro_data"],14,2));
                                    $data_contato = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
                                    echo "<td width='14%'><div align='left'><span style='font-size:8pt'>{$row_registros['registro_usuario']}<br/>{$data_contato}</span></div></td>";
                                    echo "<td width='60.5%'><div align='left'><span style='font-size:7pt'>".nl2br($row_registros['registro_obs'])."</span></div></td>";
                                    echo "<td width='20%'><div align='left'><span style='font-size:8pt'><span>{$status_nome}</span><br><img src='sistema/imagens/status_{$row_registros['status_img']}.png'></span></div></td>";
                                    echo "</tr>"; 
                                } 
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>