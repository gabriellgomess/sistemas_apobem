<div class="linha">
    <h3 class="mypets2">Histórico de Transações:</h3>
    <div class="thepet2">
        <div class="linha" style="max-height: 600px; overflow-y: auto;">

<?php
    $result_trans_hist = mysql_query("SELECT *
    FROM sys_vendas_transacoes_tef
    WHERE transacao_venda_id = '" . $vendas_id . "' OR (transacao_cliente_cpf = '".$row['cliente_cpf']."' AND transacao_valor = '".$row['vendas_valor']."');")
    or die(mysql_error());
?>

<?php if(mysql_num_rows($result_trans_hist)): ?>
    <?php while($row_trans_hist = mysql_fetch_array($result_trans_hist)): ?>
    <?php 
        	$trans_id = $row_trans_hist['transacao_id'];

        	$result_trans_logs = mysql_query("SELECT *
    	    FROM sys_vendas_transacoes_tef_log
    	    WHERE transacao_id = '".$trans_id."' ORDER BY transacao_id, log_id;")
    	    or die(mysql_error());
    ?>
    <div class="bloco_trans_hist">
    			<table class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2" style="margin: 0;">
                    <tr>
                        <th><div align="left">Código:</div></th>
                        <th><div align="left">Tipo:</div></th>
                        <th><div align="left">Dia débito:</div></th>
                        <th><div align="left">Status:</div></th>
                        <th><div align="left">Usuário:</div></th>
                        <th><div align="left">Cartão validade:</div></th>
                        <th><div align="left">Cartão números finais:</div></th>
                    </tr>
                        <?php
                            $transactions_status = '';

                            if($row_trans_hist["transacao_status"] == 'VER') {
                                $transactions_status = 'Verificado';
                            }
                            if($row_trans_hist["transacao_status"] == 'NOV') {
                                $transactions_status = 'Novo';
                            }
                            if($row_trans_hist["transacao_status"] == 'INV') {
                                $transactions_status = 'Inválido';
                            }
                            if($row_trans_hist["transacao_status"] == 'INA') {
                                $transactions_status = 'Recorrência Inativada';
                            }
                            if($row_trans_hist["transacao_status"] == 'EST') {
                                $transactions_status = 'Pagamento Estornado';
                            }
                            if($row_trans_hist["transacao_status"] == 'PPC') {
                                $transactions_status = 'Pendente de confirmação';
                            }
                            if($row_trans_hist["transacao_status"] == 'PPN') {
                                $transactions_status = 'Desfeita';
                            }
                            if($row_trans_hist["transacao_status"] == 'EXP') {
                                $transactions_status = 'Expirado';
                            }
                            if($row_trans_hist["transacao_status"] == 'PEN') {
                                $transactions_status = 'Aguardando Pagamento';
                            }
                            if($row_trans_hist["transacao_status"] == 'CON') {
                                $transactions_status = 'Confirmado';
                            }
                        ?>
                        <tr>
                            <td><?php echo $row_trans_hist["transacao_id"]; ?></td>
                            <td><?php echo $row_trans_hist["transacao_tipo_plano"]; ?></td>
                            <td><?php echo $row_trans_hist["transacao_dia_debito"] ;?></td> 
                            <td><?php echo '('.$row_trans_hist["transacao_status"].') '.$transactions_status; ?></td> 
                            <td><?php echo $row_trans_hist["transacao_username"] ?></td> 
                            <td><?php echo $row_trans_hist["transacao_cartao_validade_mes"].'/'.$row_trans_hist["transacao_cartao_validade_ano"]; ?></td> 
                            <td><?php echo substr($row_trans_hist["transacao_cartao_num"], -4); ?></td>
                        </tr>
                </table>

                <table class="blocos hist_table" width="100%" border="0" align="center" cellpadding="0" cellspacing="2" style="margin: 0;">
                    <tr>
    					<th>Id do log</th>
    					<th>Usuário</th>
    					<th>Data</th>
    					<th>Cod Erro</th>
    					<th>Status</th>
    					<th>USN</th>
    					<th>Resposta da API</th>
                    </tr>
                <?php if(mysql_num_rows($result_trans_logs)): ?>
                    <?php while($row_trans_logs = mysql_fetch_array($result_trans_logs)): ?>
                    <tr>
    					<td><?php echo $row_trans_logs['log_id']; ?></td>
    					<td><?php echo getUserNameById($row_trans_logs['user_id']); ?></td>
    					<td><?php echo _datetimeDB_to_datetimeBR($row_trans_logs['data']); ?></td>
    					<td><?php echo $row_trans_logs['erro_cod']; ?></td>
    					<td><?php echo $row_trans_logs['status']; ?></td>
    					<td><?php echo $row_trans_logs['esitef_usn']; ?></td>
    					<td><?php echo jsonToDebug($row_trans_logs['response_json']); ?></td>
    				</tr>
    				<?php endwhile; ?>
                <?php endif; ?>
               	</table>
    </div>
    	<?php endwhile; ?>
    <?php endif; ?>
        </div>
    </div>
</div>

<style type="text/css">
.json_table,
.json_table th,
.json_table td {
    border: solid 1px #ccc;
    line-height: 1.2;
    margin: 0;
}
.hist_table th{
	background: #888;
    line-height: 1.2;
    border: solid 1px #ccc;
}
.hist_table td{
    line-height: 1.2;
    padding: 3px 2px;
}

.bloco_trans_hist {
    margin: 10px;
    box-shadow: 0px 0px 2px 2px #ccc;
}
</style>

<?php 
function jsonToDebug($jsonText = '')
{
    $arr = json_decode($jsonText, true);
    $html = "";
    if ($arr && is_array($arr)) {
        $html .= _arrayToHtmlTableRecursive($arr);
    }
    return $html;
}

function _arrayToHtmlTableRecursive($arr) {
    $str = "<table class='json_table'><tbody>";
    foreach ($arr as $key => $val) {
        $str .= "<tr>";
        $str .= "<td><strong>$key</strong></td>";
        $str .= "<td>";
        if (is_array($val)) {
            if (!empty($val)) {
                $str .= _arrayToHtmlTableRecursive($val);
            }
        } else {
            $str .= "$val";
        }
        $str .= "</td></tr>";
    }
    $str .= "</tbody></table>";

    return $str;
}

function _datetimeDB_to_datetimeBR($datetimeDb) {
	$datetimeDb = explode(" ", $datetimeDb);
	return implode("/",array_reverse(explode("/", str_replace("-","/",$datetimeDb[0]))))." ".$datetimeDb[1];
}

function getUserNameById($id){
	$result = mysql_query("SELECT username FROM jos_users WHERE id = '".$id."' LIMIT 0,1;") or die("Erro na consulta getUserNameById. ".mysql_error());
	$row = mysql_fetch_array($result);	
	return $row['username'];
}
?>
