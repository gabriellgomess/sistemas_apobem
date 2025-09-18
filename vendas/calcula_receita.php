<?php

if ($row_tabela["tabela_base_contrato"] == "1"){
	$vendas_base_contrato = $vendas_valor;
}elseif ($row_tabela["tabela_base_contrato"] == "2"){
	$vendas_base_contrato = $vendas_liquido;
}else{
	if (($vendas_tipo_contrato == "2")||
	($vendas_tipo_contrato == "6")||
	($vendas_tipo_contrato == "7")||
	($vendas_tipo_contrato == "10")){$vendas_base_contrato = $vendas_liquido;}else{$vendas_base_contrato = $vendas_valor;}
}

$aux_flat_bonus = $vendas_bonus + $vendas_juros;
$aux_flat_fracionada = $vendas_juros_fr + $vendas_juros;
if ((($aux_flat_bonus >= 10)||($aux_flat_fracionada >= 15)||($row_tabela["tabela_base"] == "1"))&&($row_tabela["tabela_base"] != "2")){
	$vendas_base = "1";
	//$vendas_base_contrato = $vendas_valor;
	$vendas_base_prod = $vendas_base_contrato;
}else{
	$vendas_base = "2";
	if ($row_tabela["tabela_perc_base_prod"] > 0){
		$vendas_base_prod = $vendas_base_contrato;
	}else{
		$aux_flat_fr_bonus_saldo = $vendas_juros_fr + $vendas_bonus + $vendas_juros + $vendas_cms_saldo;
		$aux_base = ($vendas_base_contrato * $aux_flat_fr_bonus_saldo) / 100;
		$vendas_base_prod = $aux_base * $coeficiente_base;
	}
}

?>