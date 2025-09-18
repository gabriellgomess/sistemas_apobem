<?php

$cms_subtotal = 0;
$cms_valor = 0;
$cms_subtotal_geral = 0;
$cms_total = 0;
$cms_subtotal_dif = 0;
$cms_dif = 0;
$aux_flat_fr_bonus_saldo =  0;
$cms_ant_dif = 0;
$vendas_fortcoins = 0;
$cms_fortcoins = 0;

$result_cms = mysql_query("SELECT cms_id, tipo_id, cms_perc, cms_valor, cms_imposto 
FROM sys_vendas_tabelas_cms WHERE tabela_id = '".$vendas_tabela."';") 
or die(mysql_error());

if (!$vendas_banco_fortcoins){$vendas_banco_fortcoins = 1.33;}
if ($row_tabela["tabela_fortcoins"]){$vendas_banco_fortcoins = $row_tabela["tabela_fortcoins"];}
if ($row_tabela["tabela_fortcoins_zero"]){$vendas_banco_fortcoins = 0;}

if (mysql_num_rows($result_cms)){
	while($row_cms = mysql_fetch_array( $result_cms )) {
		$cms_subtotal = 0;
		$cms_subtotal_dif = 0;
		$cms_subtotal_geral = 0;
		$cms_subtotal_ant = 0;
		$cms_subtotal_ant_sem_dif = 0;
		$cms_nova = 1;
		
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
			$cms_subtotal = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);
			$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
			if (!$row_tabela["tabela_base_contrato"]){$base_portabilidade_saldo + $vendas_portabilidade_saldo;}
		} 
		
		// Saldo Devedor Diferido:
		if($row_cms["tipo_id"] == 7){
			$cms_subtotal_dif = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);
			$compara_antecipacao_7 = $row_cms['cms_perc'];
			if (!$row_tabela["tabela_base_contrato"]){$base_portabilidade_saldo + $vendas_portabilidade_saldo;}
		}
		
		// Saldo Devedor Antecipação Diferido:
		if($row_cms["tipo_id"] == 8){
			if ((!$compara_antecipacao_7)||($vendas_tipo_contrato == "6")||($vendas_tipo_contrato == "7")||($vendas_tipo_contrato == "10")){
				$cms_subtotal_ant_sem_dif = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);
			}else{$cms_subtotal_ant = (($vendas_portabilidade_saldo * $row_cms['cms_perc']) / 100);}
			$aux_flat_fr_bonus_saldo = $aux_flat_fr_bonus_saldo + $row_cms['cms_perc'];
			if (!$row_tabela["tabela_base_contrato"]){$base_portabilidade_saldo + $vendas_portabilidade_saldo;}
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
		
		$cms_valor = $row_cms["cms_valor"];
		$cms_fortcoins = ($cms_subtotal + $cms_valor + ($cms_subtotal_ant) + ($cms_subtotal_ant_sem_dif)) * $vendas_banco_fortcoins;
		$vendas_fortcoins = $vendas_fortcoins + $cms_fortcoins;
		$cms_subtotal_geral = $cms_subtotal + $cms_valor + $cms_subtotal_dif + $cms_subtotal_ant + $cms_subtotal_ant_sem_dif;
		$cms_total = $cms_total + $cms_subtotal + $cms_valor + $cms_subtotal_ant_sem_dif + $cms_subtotal_ant;
		$calculo_fc = $calculo_fc."tipo_id: ".$row_cms['tipo_id'].", cms_total part: ".$cms_total."<br>";
	}
}
//$vendas_fortcoins = $cms_total * $vendas_banco_fortcoins;
$vendas_fortcoins = ($vendas_fortcoins>0) ? number_format($vendas_fortcoins, 2, ',', '.') : '0' ;  

?>