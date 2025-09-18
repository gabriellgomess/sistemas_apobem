<?php
//CONSULTANDO CLIENTES NO DESTINO...
$result_espelha_confere = mysql_query("SELECT COUNT(cliente_cpf) AS total 
						FROM sys_inss_clientes
						WHERE sys_inss_clientes.cliente_cpf = " . $clients_cpf . ";") 
or die(mysql_error()); 
$row_espelha_confere = mysql_fetch_array( $result_espelha_confere );
?>