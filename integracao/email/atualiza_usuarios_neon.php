<?php 
date_default_timezone_set('America/Sao_Paulo');
set_time_limit(30);
$criterios=0;

$ldap_pagedresults = true;
$ldap_pagesize = 1;
$ldap_cookie = '';

// config
$ldapserver = '10.3.100.96';
$ldapuser      = 'AP\intranet.consulta'; 
$ldappass     = 'Aux@intra1712';
$ldaptree    = "OU=Users Agencias,DC=ap,DC=netz";
echo 'Antes do CONNECT:';
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
$result = mysqli_query($con,"SELECT id, username, admin FROM g1fda_users WHERE block=0 AND admin=0 ORDER BY username ASC;") 
or die(mysqli_error($con));

// connect
$ldapconn = ldap_connect($ldapserver) or die("Sem conexão com o Servidor do Domínio.\r");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

// binding to ldap server
$ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Erro ao consultar os dados do AD: ".ldap_error($ldapconn));
ldap_control_paged_result($ldapconn, $ldap_pagesize, true, $ldap_cookie);
$atualizador_usuarios = 0;
$atualizador_atualizados = 0;
echo "<table>";
while($row = mysqli_fetch_array( $result )) {

	if($ldapconn) {

		// verify binding
		if ($ldapbind) {
			
			//Filtro NOME
			$filtro = "(&(cn=*)";
			
			//Filtro USERMANE
			$filtro = $filtro."(samaccountname=".$row['username'].")";
			echo "<tr><td>(samaccountname=".$row['username'].")</td>";
			
			//Filtro FIM
			$filtro_fim = "(objectClass=user))";
			$filtro = $filtro.$filtro_fim;
			//echo "FILTRO: ".$filtro;
			
			$result_ldap = ldap_search($ldapconn,$ldaptree,$filtro) or die ("Error in search query: ".ldap_error($ldapconn));
			ldap_sort($ldapconn, $result_ldap, 'cn');
			$data = ldap_get_entries($ldapconn, $result_ldap);
			
			if (ldap_count_entries($ldapconn, $result_ldap)){
				//echo '";" A consulta localizou ' . ldap_count_entries($ldapconn, $result_ldap) . ' usuario valido!';
				
				$name = $data[0]["cn"][0];
				$chefia = $data[0]['postofficebox'][0];
				$setor = $data[0]['department'][0];
				$area = $data[0]['wWWHomePage'][0];
				$ramal = $data[0]['telephonenumber'][0];
				$celular = $data[0]['mobile'][0];
				$agencia = $data[0]['physicaldeliveryofficename'][0];
				if ($data[0]['userAccountControl'][0] == "514"){
					$block = "1"; $user_status = "BLOQUEADO";
					$query = mysqli_query($con,"UPDATE g1fda_users SET usuario_pares=REPLACE(usuario_pares, ',".$row['id']."', '') WHERE usuario_pares LIKE '%,".$row['id']."%';") or die(mysqli_error($con));
				}else{$block = "0"; $user_status = "ATIVO";}
				$query = mysqli_query($con,"UPDATE g1fda_users SET name='$name', 
				chefia='$chefia', 
				area='$area', 
				setor='$setor', 
				ramal='$ramal', 
				celular='$celular', 
				agencia='$agencia', 
				block='$block' WHERE username='".$row['username']."'") or die(mysqli_error($con));
			}else{
				$query = mysqli_query($con,"UPDATE g1fda_users SET block='1' WHERE username='".$row['username']."' AND empresa=1;") or die(mysqli_error($con));
				$user_status = "BLOQUEADO - Fora da OU raiz, ou da NEON!";
			}
			$atualizador_atualizados++;
			echo "<td>".$user_status."</td></tr>";
		} else {
			echo '* Nenhum usuario encontrado para o login "'.$row['username'].'"\r';
		}
	}
	$atualizador_usuarios++;
}
echo "</table>";
$atualizador_data = date("Y-m-d H:i:s");
$sql = "INSERT INTO `intranet`.`sis_atualizador_usuarios` (`atualizador_id`, 
`atualizador_data`, 
`atualizador_usuarios`, 
`atualizador_atualizados`) 
VALUES (NULL, 
'$atualizador_data',
'$atualizador_usuarios',
'$atualizador_atualizados');"; 
if (!mysqli_query($con,$sql))
  {
  die('Error: ' . mysqli_error($con));
  }
echo "LOG Cadastrado com sucesso.</br>";
// all done? clean up
ldap_close($ldapconn);
mysqli_close($con);
?>