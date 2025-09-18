<?php
if (!$row_tabela["vendas_banco_fortcoins"]){$row_tabela["vendas_banco_fortcoins"] = 1.33;}
if (($vendas_tipo_contrato == "12")||($vendas_tipo_contrato == "13")){
	$vendas_base_contrato = 0;
	$vendas_base_prod = 0;
}else{
	$vendas_portabilidade_saldo = $row["vendas_portabilidade_saldo"];
	$vendas_portabilidade_saldo_outros = $row["vendas_portabilidade_saldo_outros"];

	$cms_subtotal = 0;
	$cms_valor = 0;
	$cms_subtotal_geral = 0;
	$cms_total = 0;
	$cms_subtotal_dif = 0;
	$cms_dif = 0;
	$cms_ant_dif = 0;
	$aux_flat_fr_bonus_saldo =  0;
	$cms_fortcoins = 0;
	$vendas_fortcoins = 0;

	$query = mysql_query("DELETE FROM sys_vendas_cms WHERE vendas_id=$vendas_id;") or die(mysql_error());
	$result_cms = mysql_query("SELECT cms_id, tipo_id, cms_perc, cms_valor
	FROM sys_vendas_tabelas_cms WHERE tabela_id = '".$vendas_tabela."';") 
	or die(mysql_error());

	if (mysql_num_rows($result_cms)){
		while($row_cms = mysql_fetch_array( $result_cms )) {
			$cms_subtotal = 0;
			$cms_subtotal_dif = 0;
			$cms_subtotal_geral = 0;
			$cms_subtotal_ant = 0;
			$cms_subtotal_ant_sem_dif = 0;
			
			//AF Flat:
			if($row_cms["tipo_id"] == 1){
				$cms_subtotal = (($vendas_valor * $row_cms['cms_perc']) / 100);
				$aux_flat_bonus = $aux_flat_bonus + $row_cms['cms_perc'];
				$aux_flat_fracionada = $aux_flat_fracionada + $row_cms['cms_perc'];
				$aux_flat_antecipacao = $aux_flat_antecipacao + $row_cms['cms_perc'];
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_vendas_valor = $vendas_valor;}
			}
			
			// AF Bônus:
			if($row_cms["tipo_id"] == 2){
				$cms_subtotal = (($vendas_valor * $row_cms['cms_perc']) / 100);
				$aux_flat_bonus = $aux_flat_bonus + $row_cms['cms_perc'];
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_vendas_valor = $vendas_valor;}
			}
			
			// AF Diferido:
			if($row_cms["tipo_id"] == 3){
				$cms_subtotal_dif = (($vendas_valor * $row_cms['cms_perc']) / 100);
				$aux_flat_fracionada = $aux_flat_fracionada + $row_cms['cms_perc'];
				$compara_antecipacao_3 = $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_vendas_valor = $vendas_valor;}
			}
			
			// AF Antecipação Diferido:
			if($row_cms["tipo_id"] == 4){
				$aux_flat_antecipacao = $aux_flat_antecipacao + $row_cms['cms_perc'];
				if ((!$compara_antecipacao_3)||($vendas_tipo_contrato == "6")||($vendas_tipo_contrato == "7")||($vendas_tipo_contrato == "10")){
					$cms_subtotal_ant_sem_dif = (($vendas_valor * $row_cms['cms_perc']) / 100);
				}else{$cms_subtotal_ant = (($vendas_valor * $row_cms['cms_perc']) / 100);}
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_vendas_valor = $vendas_valor;}
			}
			
			// Saldo Devedor Flat:
			if($row_cms["tipo_id"] == 5){
				$cms_subtotal = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_portabilidade_saldo = $vendas_portabilidade_saldo;}
			} 
			
			// Saldo Devedor Bônus:
			if($row_cms["tipo_id"] == 6){
				$cms_subtotal = (($vendas_portabilidade_saldo* $row_cms['cms_perc']) / 100);
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_portabilidade_saldo = $vendas_portabilidade_saldo;}
			} 
			
			// Saldo Devedor Diferido:
			if($row_cms["tipo_id"] == 7){
				$cms_subtotal_dif = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);
				$compara_antecipacao_7 = $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_portabilidade_saldo = $vendas_portabilidade_saldo;}
			}
			
			// Saldo Devedor Antecipação Diferido:
			if($row_cms["tipo_id"] == 8){
				if ((!$compara_antecipacao_7)||($vendas_tipo_contrato == "6")||($vendas_tipo_contrato == "7")||($vendas_tipo_contrato == "10")){
					$cms_subtotal_ant_sem_dif = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);
				}else{$cms_subtotal_ant = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);}
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_portabilidade_saldo = $vendas_portabilidade_saldo;}
			}
			
			// Líquido Flat:
			if($row_cms["tipo_id"] == 9){
				$cms_subtotal = (($vendas_liquido * $row_cms['cms_perc']) / 100);
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_liquido = $vendas_liquido;}
			}
			
			// Líquido Bônus:
			if($row_cms["tipo_id"] == 10){
				$cms_subtotal = (($vendas_liquido * $row_cms['cms_perc']) / 100);
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_liquido = $vendas_liquido;}
			} 
			
			// Líquido Diferido:
			if($row_cms["tipo_id"] == 11){
				$cms_subtotal_dif = (($vendas_liquido * $row_cms['cms_perc']) / 100);
				$compara_antecipacao_11 = $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_liquido = $vendas_liquido;}
			}
			
			// Líquido Antecipação Diferido:
			if($row_cms["tipo_id"] == 12){
				if ((!$compara_antecipacao_11)||($vendas_tipo_contrato == "6")||($vendas_tipo_contrato == "7")||($vendas_tipo_contrato == "10")){
					$cms_subtotal_ant_sem_dif = (($vendas_liquido * $row_cms['cms_perc']) / 100);
				}else{$cms_subtotal_ant = (($vendas_liquido * $row_cms['cms_perc']) / 100);}
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_liquido = $vendas_liquido;}
			}
			
			// PMT Flat:
			if($row_cms["tipo_id"] == 13){
				$cms_subtotal = (($vendas_valor_parcela * $row_cms['cms_perc']) / 100) * $vendas_percelas;
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_valor_parcela = $vendas_valor_parcela;}
			} 
			
			// PMT Bônus:
			if($row_cms["tipo_id"] == 14){
				$cms_subtotal = (($vendas_valor_parcela * $row_cms['cms_perc']) / 100) * $vendas_percelas;
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_valor_parcela = $vendas_valor_parcela;}
			} 
			
			// PMT Diferido:
			if($row_cms["tipo_id"] == 15){
				$cms_subtotal_dif = (($vendas_valor_parcela * $row_cms['cms_perc']) / 100) * $vendas_percelas;
				$compara_antecipacao_15 = $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_valor_parcela = $vendas_valor_parcela;}
			}
			
			// PMT Antecipação Diferido:
			if($row_cms["tipo_id"] == 16){
				if ((!$compara_antecipacao_15)||($vendas_tipo_contrato == "6")||($vendas_tipo_contrato == "7")||($vendas_tipo_contrato == "10")){
					$cms_subtotal_ant_sem_dif = (($vendas_valor_parcela * $row_cms['cms_perc']) / 100) * $vendas_percelas;
				}else{$cms_subtotal_ant = (($vendas_valor_parcela * $row_cms['cms_perc']) / 100) * $vendas_percelas;}
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_valor_parcela = $vendas_valor_parcela;}
			}
			
			// CMS Plastico (17) e CMS Ativacao (18), são calculados automaticamente, pois são em valor:
			
			// Saldo Devedor OUTROS BANCOS:
			if($row_cms["tipo_id"] == 19){
				$cms_subtotal = (($vendas_portabilidade_saldo_outros * $row_cms['cms_perc']) / 100);
				$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
				if (!$row_tabela["tabela_base_contrato"]){$base_saldo_outros = $vendas_portabilidade_saldo_outros;}
			}
			
			if (!$row_tabela["tabela_base_contrato"]){
				$vendas_base_contrato = $base_vendas_valor + $base_portabilidade_saldo + $base_liquido + $base_valor_parcela + $base_saldo_outros;
				if($vendas_base_contrato > $vendas_valor){$vendas_base_contrato = $vendas_valor;}
			}
			
			$cms_perc = $row_cms["cms_perc"];
			$cms_valor = $row_cms["cms_valor"];
			
			$cms_fortcoins = ($cms_subtotal + $cms_valor + ($cms_subtotal_ant/2) + ($cms_subtotal_ant_sem_dif/2)) * $row_tabela["vendas_banco_fortcoins"];
			$vendas_fortcoins = $vendas_fortcoins + $cms_fortcoins;
			$cms_subtotal_geral = $cms_subtotal + $cms_valor + $cms_subtotal_dif + $cms_subtotal_ant + $cms_subtotal_ant_sem_dif;
			$cms_total = $cms_total + $cms_subtotal + $cms_valor + $cms_subtotal_ant_sem_dif + $cms_subtotal_ant;
			$cms_dif = $cms_dif + $cms_subtotal_dif;
			$cms_ant_dif = $cms_ant_dif + $cms_subtotal_ant;
			
			if ($cms_subtotal_ant_sem_dif){$cms_receita = 2;}else{$cms_receita = 1;}
			
			//echo "cms_perc: ".$cms_perc."<br>";
			//echo "cms_valor: ".$cms_valor."<br>";
			//echo "cms_subtotal_geral: ".$cms_subtotal_geral."<br>";
			//echo "cms_total: ".$cms_total."<br>";
			//echo "cms_dif: ".$cms_dif."<br><br>";
			//echo "vendas_portabilidade_saldo: ".$row['vendas_portabilidade_saldo']."<br>";
			
			$tipo_id = $row_cms["tipo_id"];
			
			if (!$venda_nova){
				$sql = "INSERT INTO `sistema`.`sys_vendas_cms` (`cms_id`, 
				`vendas_id`, 
				`tipo_id`, 
				`cms_perc`, 
				`cms_valor`, 
				`cms_subtotal`, 
				`cms_fortcoins`, 
				`cms_receita`) 
				VALUES (NULL,
				'$vendas_id',
				'$tipo_id',
				'$cms_perc',
				'$cms_valor',
				'$cms_subtotal_geral',
				'$cms_fortcoins',
				'$cms_receita');"; 
				if (mysql_query($sql,$con)){
					$cms_id = mysql_insert_id();
				} else {
					die('Error: ' . mysql_error());
				}
			}
		}
			
		if ($row_tabela["tabela_base_contrato"] == "1"){
			$vendas_base_contrato = $vendas_valor;
		}elseif ($row_tabela["tabela_base_contrato"] == "2"){
			$vendas_base_contrato = $vendas_liquido;
		}else{
			$vendas_base_contrato = $base_vendas_valor + $base_portabilidade_saldo + $base_liquido + $base_valor_parcela + $base_saldo_outros;
			if($vendas_base_contrato > $vendas_valor){$vendas_base_contrato = $vendas_valor;}
		}
			
		$vendas_cms_perc_af = (($cms_total + $cms_ant_dif) * 100) / $vendas_base_contrato;
		// mais de 10 B1...  de 7 ate 9.99 B2 100%...   abaixo de 7 B2 50%

		if (($vendas_cms_perc_af >= 10)&&($row_tabela["tabela_base"] != "2")){
			$vendas_base = "1";
			$vendas_base_prod = $vendas_base_contrato;
		}elseif ($vendas_cms_perc_af >= 7){
			$vendas_base = "2";
			if ($row_tabela["tabela_perc_base_prod"] > 0){
				$vendas_base_prod = ($vendas_base_contrato * $row_tabela["tabela_perc_base_prod"]) / 100;
			}else{
				$vendas_base_prod = $vendas_base_contrato;
			}
		}else{
			$vendas_base = "2";
			if ($row_tabela["tabela_perc_base_prod"] > 0){
				$vendas_base_prod = ($vendas_base_contrato * $row_tabela["tabela_perc_base_prod"]) / 100;
			}else{
				$vendas_base_prod = ($cms_total + $cms_ant_dif) * $coeficiente_base;
			}
		}
	}else{
		
		$aux_flat_bonus = $vendas_bonus + $vendas_juros;
		$aux_flat_fracionada = $vendas_juros_fr + $vendas_juros;
		if ((($aux_flat_bonus >= 10)||($aux_flat_fracionada >= 15)||($row_tabela["tabela_base"] == "1"))&&($row_tabela["tabela_base"] != "2")){
			$vendas_base = "1";
			$vendas_base_prod = $vendas_base_contrato;
			
		}else{
			$vendas_base = "2";
			if ($row_tabela["tabela_perc_base_prod"] > 0){
				$vendas_base_prod = ($vendas_base_contrato * $row_tabela["tabela_perc_base_prod"]) / 100;
			}else{
				$aux_flat_fr_bonus_saldo = $vendas_juros_fr + $vendas_bonus + $vendas_juros + $vendas_cms_saldo;
				$aux_base = ($vendas_base_contrato * $aux_flat_fr_bonus_saldo) / 100;
				$vendas_base_prod = $aux_base * $coeficiente_base;

			}
		}
	}
}

?>