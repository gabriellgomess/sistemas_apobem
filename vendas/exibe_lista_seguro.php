<?php
while ($row = mysql_fetch_array($result)) {
   $endereco_link = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_seguro&vendas_id={$row['vendas_id']}";
   echo "<tr class='even'><div align='left'><td width='3%'>";
   echo "<a href='{$endereco_link}'><span style='color:#666666; font-size:8pt'>{$numero}</span></a></td><td width='25%'>";
   if ($row["vendas_orgao"] == "Exercito") {
      
      if ($row['cliente_nome']) {
         echo "<a href='" . $endereco_link . "'>" . $row['cliente_nome'] . "<br />";
         echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['cliente_cpf']}</span>";
      } else {
         echo "<a href='" . $endereco_link . "'>" . $row['clients_nm'] . "<br />";
         echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']}</span>";
      }

      /* Código abaixo foi comentado em 14/12/2023 devido a um chamado no qual um cliente aparecia na lista sem nome e cpf a mostra
         Para reverter o comportamento de antes, basta descomentar o código abaixo e remover o if/else acima */
         
      // echo "<a href='" . $endereco_link . "'>" . $row['clients_nm'] . "<br />";
      // echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']}</span>";
   } else {
      if ($row['cliente_nome']) {
         echo "<a href='" . $endereco_link . "'>" . $row['cliente_nome'] . "<br />";
         echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['cliente_cpf']}</span>";
      } else {
         echo "<a href='" . $endereco_link . "'>" . $row['clients_nm'] . "<br />";
         echo "<span style='color:#666666; font-size:8pt'>CPF: {$row['clients_cpf']}</span>";
      }
   }
   if ($contagem) {
      $link_num = "index.php?option=com_k2&view=item&layout=item&id=64&Itemid=440&nome=" . $nome . "&prec=" . $prec . "&cpf=" . $row['cliente_cpf'] . $pag_mes . "&consultor_unidade=" . $pag_unidade . "&vendas_consultor=" . $vendas_consultor . "&vendas_vendedor=" . $vendas_vendedor . "&vendas_status=" . $pag_status . "&vendas_contrato_fisico=" . $pag_contrato . "&vendas_promotora=" . $vendas_promotora . "&vendas_banco=" . $vendas_banco . "&vendas_orgao=" . $vendas_orgao . "&vendas_tipo_contrato=" . $vendas_tipo_contrato . "&vendas_seguro_protegido=" . $vendas_seguro_protegido . "&dp-normal-3=" . $pag_data_imp_ini . "&dp-normal-4=" . $pag_data_imp_fim;
      echo " | <a href='" . $link_num . "'>Nº de Vendas: <strong>" . $row['contagem'] . "</strong></a>";
   }
   echo "</td><td width='12%'>";

   $result_apolice = mysql_query("SELECT apolice_nome FROM sys_vendas_apolices WHERE apolice_id = " . $row['vendas_apolice'] . ";")
      or die(mysql_error());
   $row_apolice = mysql_fetch_array($result_apolice);
   $vendas_valor = ($row['vendas_valor'] > 0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0';
   echo "<a href='{$endereco_link}'>R$ {$vendas_valor}</a>";
   echo "<span style='color:#666666; font-size:8pt'>{$row_apolice['apolice_nome']}</span></td><td width='21%'>";
   $result_user = mysql_query("SELECT name FROM jos_users WHERE id = " . $row['vendas_consultor'] . ";")
      or die(mysql_error());
   $row_user = mysql_fetch_array($result_user);
   echo "<a href='{$endereco_link}'>{$row_user['name']}</a>";
   $yr = strval(substr($row["vendas_dia_venda"], 0, 4));
   $mo = strval(substr($row["vendas_dia_venda"], 5, 2));
   $da = strval(substr($row["vendas_dia_venda"], 8, 2));
   $hr = strval(substr($row["vendas_dia_venda"], 11, 2));
   $mi = strval(substr($row["vendas_dia_venda"], 14, 2));
   $data_venda = date("d/m/Y H:i:s", mktime($hr, $mi, 0, $mo, $da, $yr));
   echo "<span style='color:#666666; font-size:8pt'>{$data_venda}</span></td><td width='15%'>";
   $result_status = mysql_query("SELECT status_nm, status_img FROM sys_vendas_status_seg WHERE status_id = " . $row['vendas_status'] . ";")
      or die(mysql_error());
   $row_status = mysql_fetch_array($result_status);
   if ($row["vendas_status"] > 0) {
      echo "<a href='{$endereco_link}'><span style='color:#666666; font-size:8pt'>{$row_status['status_nm']}</span></a>";
   } else {
      echo "<span style='color:#666666; font-size:8pt'>Enviada p/ implantação</span></a>";
   }
   if ($row["vendas_gravacao"]) {
      echo "<img style='float:right; margin-top: -10px;' src='sistema/imagens/tape_2.png'>";
   } else {
      echo "<img style='float:right; margin-top: -10px;' src='sistema/imagens/tape_1.png'>";
   }
   echo "<img src='sistema/imagens/status_{$row_status['status_img']}.png'></a>";
   echo "<div>" . ucfirst(mb_strtolower($row['vendas_status_motivo'])) . "</div>";
   echo "</td><td width='6%'>";
   echo "<a href='{$endereco_link}'><strong>{$row['vendas_id']}</strong></a>";
   if ($exclusao_vendas_seguros == 1) {
      echo "<hr><a title='EXCLUIR VENDA Nº: {$row['vendas_id']}' href='index.php?option=com_k2&view=item&id=64:excluir-venda&Itemid=123&tmpl=component&print=1&vendas_id={$row['vendas_id']}&clients_cpf={$row['clients_cpf']}&clients_nm={$row['clients_nm']}&acao=exclui_venda_seguro' rel='lyteframe' rev='width: 550px; height: 400px; scroll:no;'><img src='sistema/imagens/delete.png'></a>";
   }
   echo "</td></div></tr>";
   $exibindo = $exibindo + 1;
   $numero = $numero + 1;
}
