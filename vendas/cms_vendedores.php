<?php
include("../connect.php");
$result = mysql_query("SELECT vendas_id, vendas_base, vendas_base_prod, nivel, tabela_cms_consultor_interno FROM `sys_vendas` 
INNER JOIN jos_users ON sys_vendas.vendas_consultor = jos_users.id 
INNER JOIN sys_vendas_tabelas ON sys_vendas.vendas_tabela = sys_vendas_tabelas.tabela_id 
WHERE `vendas_mes` = '06/2017' AND (`vendas_status` = 8 OR `vendas_status` = 9) AND `vendas_comissao_vendedor` = 0 AND jos_users.nivel <> 4 AND jos_users.nivel <> 8 AND vendas_base_prod > 0;")
or die(mysql_error());

while($row = mysql_fetch_array( $result )) {
	if (!$vendas_base_prod){$vendas_base_prod = $row["vendas_base_prod"];}

	if ($_GET["user_situacao"] == "1"){
		if ($row["vendas_base"] == "2"){$aux_comissao_vendedor = 0.4;}else{$aux_comissao_vendedor = 0.8;}
	}else{
		if ($row["nivel"] == "2"){if ($row["vendas_base"] == "2"){$aux_comissao_vendedor = 1;}else{$aux_comissao_vendedor = 1.5;}}
		else{
			if ($row['tabela_cms_consultor_interno']){
				$aux_comissao_vendedor = $row['tabela_cms_consultor_interno'];
			}else{
				if ($row["vendas_base"] == "2"){$aux_comissao_vendedor = 0.5;}else{$aux_comissao_vendedor = 1;}
			}
		}
	}
	$vendas_comissao_vendedor = ($row["vendas_base_prod"] * $aux_comissao_vendedor) / 100;
	echo "UPDATE sys_vendas SET vendas_comissao_vendedor='".$vendas_comissao_vendedor."' WHERE vendas_id = '".$row['vendas_id']."'; - BASE: ".$row['vendas_base']." - BASE PROD: ".$row['vendas_base_prod']." - NIVEL: ".$row['nivel']."<br>";

	//$query = mysql_query("UPDATE sys_vendas SET vendas_comissao_vendedor='".$vendas_comissao_vendedor."' WHERE vendas_id = '".$row['vendas_id']."';") or die(mysql_error());
	//echo "Venda Atualizada com Sucesso<br>";	
	
}

?>