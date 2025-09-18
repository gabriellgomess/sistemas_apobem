<?php 
set_time_limit(30);
//Consulta USUÁRIOS NO SISTEMA
$con = mysqli_connect("localhost","root","aux@2021","intranet");
if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

mysqli_query($con,"SET character_set_results=utf8", $con);
mb_language('uni'); 
mb_internal_encoding('UTF-8');
mysqli_query($con,"set names 'utf8'",$con);

$ldap_pagedresults = true;
$ldap_pagesize = 1000;
$ldap_cookie = '';
$ldapserver = '10.3.100.96';
$ldapuser      = 'AP\intranet.consulta'; 
$ldappass     = 'Aux@intra1712';
$ldaptree    = "OU=Users Agencias Aux,OU=Users Internos,OU=Users Agencias,DC=ap,DC=netz";

// connect
$ldapconn = ldap_connect($ldapserver) or die("Sem conexão com o Servidor do Domínio.");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

if($ldapconn) {
	// binding to ldap server
	$ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Erro ao consultar os dados do AD: ".ldap_error($ldapconn));
	ldap_control_paged_result($ldapconn, $ldap_pagesize, true, $ldap_cookie);
	// verify binding
	if ($ldapbind) {
	
		$filtro = "(!(userAccountControl=514))";
		
		$result = ldap_search($ldapconn,$ldaptree,$filtro) or die ("Error in search query: ".ldap_error($ldapconn));
		ldap_sort($ldapconn, $result, 'cn');
		$data = ldap_get_entries($ldapconn, $result);
		
		echo "<table>";
			echo "<tr><td>A consulta localizou " . ldap_count_entries($ldapconn, $result) . " usuários!</td></tr>";
			echo "<tr>";
				echo "<td>NOME:</td>";
				echo "<td>LOGIN:</td>";
				echo "<td>EMAIL:</td>";
				echo "<td>CHEFIA:</td>";
				echo "<td>SETOR:</td>";
				echo "<td>AREA:</td>";
				echo "<td>RAMAL:</td>";
				echo "<td>CELULAR:</td>";
				echo "<td>AGENCIA:</td>";
				echo "<td>BLOQUEADO:</td>";
			echo "</tr>";
			
			$usuarios_novos = 0;
			for ($i=0; $i<$data["count"]; $i++) {
				$name = $data[$i]["cn"][0];
				$username = $data[$i]["samaccountname"][0];
				$email = $data[$i]["mail"][0];
				$registerDate = date("Y-m-d H:i:s");
				
				$chefia = $data[$i]['postofficebox'][0];
				$setor = $data[$i]['department'][0];
				$cargo = $data[$i]['title'][0];
				$area = $data[$i]['wWWHomePage'][0];
				$ramal = $data[$i]['telephonenumber'][0];
				$celular = $data[$i]['mobile'][0];
				$agencia = $data[$i]['physicaldeliveryofficename'][0];
				
				$result_usuario = mysqli_query($con,"SELECT COUNT(id) AS total FROM g1fda_users WHERE username = '" . $data[$i]['samaccountname'][0] . "';")
				or die(mysqli_error($con));
				$row_usuario = mysqli_fetch_array( $result_usuario );
				if (!$row_usuario["total"]){
					$sql = "INSERT IGNORE INTO `intranet`.`g1fda_users` (`name`, 
					`username`, 
					`email`, 
					`password`, 
					`registerDate`, 
					`chefia`, 
					`setor`, 
					`cargo`, 
					`area`, 
					`ramal`, 
					`celular`, 
					`empresa`, 
					`agencia`) 
					VALUES ('$name',
					'$username',
					'$email',
					'',
					'$registerDate',
					'$chefia',
					'$setor',
					'$cargo',
					'$area',
					'$ramal',
					'$celular',
					1,
					'$agencia');"; 
					//echo $sql;
					if (mysqli_query($con,$sql)){
						$user_id = mysqli_insert_id($con);
						//$grupos = "usuario cadastrado OK! <br>";
					} else {
						die('Error: ' . mysqli_error($con));
					}
					
					$grupos = "";
					$valores_insert = "('".$user_id."', '2')";
					$sql = "INSERT IGNORE INTO `intranet`.`g1fda_user_usergroup_map` (`user_id`, `group_id`) VALUES ".$valores_insert.";"; 
					if (mysqli_query($con,$sql)){
						$grupos = "grupos mapeados OK! <br>";
					} else {
						die('Error: ' . mysqli_error($con));
					}
					
					$grupos = "";
					$valores_insert = "('".$user_id."', '2')";
					$sql = "INSERT IGNORE INTO `intranet`.`g1fda_user_usergroup_map` (`user_id`, `group_id`) VALUES ".$valores_insert.";"; 
					if (mysqli_query($con,$sql)){
						$grupos = "grupos mapeados OK! <br>";
					} else {
						die('Error: ' . mysqli_error($con));
					}
					
					$sql = "INSERT IGNORE INTO `intranet`.`g1fda_k2_users` (`userID `, `userName`) VALUES ('$user_id', '$name');"; 
					if (mysqli_query($con,$sql)){
						$k2_user_id = mysqli_insert_id($con);
					} else {
						die('Error: ' . mysqli_error($con));
					}
					
					$cor_linha = "red";
					$usuarios_novos++;
				}else{$cor_linha = "black";}
				
				echo "<tr style='color: ".$cor_linha.";'>";
					echo "<td>". $name ."</td>";
					echo "<td>". $username ."</td>";
					echo "<td>". $email."</td>";
					echo "<td>". $chefia."</td>";
					echo "<td>". $setor."</td>";
					echo "<td>". $area."</td>";
					echo "<td>". $ramal."</td>";
					echo "<td>". $celular."</td>";
					echo "<td>". $agencia."</td>";
					echo "<td>". $block."</td>";
					echo "<td>". $grupos."</td>";
				echo "</tr>";
			}
			echo "<tr><td>A consulta localizou " . ldap_count_entries($ldapconn, $result) . " usuários!</td></tr>";
			echo "<tr><td>" . $usuarios_novos . " usuários novos cadastrados!</td></tr>";
		echo "</table>";
	} else {
		echo "<div align='center'>Nenhum contato encontrado...</div>";
	}
}
// all done? clean up
ldap_close($ldapconn);
mysqli_close($con);
?>