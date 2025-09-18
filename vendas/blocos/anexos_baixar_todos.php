<?php include("../../connect.php"); ?>
<?php include("../../utf8.php"); ?>

<?php
$user_id = $_GET['user_id'];
$vendas_consultor = $_GET['vendas_consultor'];
$vendas_id = $_GET['vendas_id'];
$administracao = $_GET['administracao'];

$result_anexos = mysql_query("SELECT *, DATE_FORMAT(anexo_data,'%d/%m/%Y %H:%i:%s') AS data 
					   FROM sys_vendas_anexos						   
					   WHERE vendas_id = " . $vendas_id . ";")
or die(mysql_error());

$result_venda = mysql_query("SELECT clients_cpf FROM sys_vendas WHERE vendas_id = '" . $vendas_id . "';")
or die(mysql_error());

$row_venda = mysql_fetch_array( $result_venda );
?>

<?php while($row_anexos = mysql_fetch_array( $result_anexos )): ?>
<?php
	if (strpos($row_anexos['anexo_caminho'], 'sistema/') !== false){

		if(strpos($row_anexos['anexo_caminho'],"http") !== false){ // se tem http é porque o link já está completo.
			$link_anexo = $row_anexos['anexo_caminho'];
		}else{
			$link_anexo = "http://portal.grupofortune.com.br/portal/".$row_anexos['anexo_caminho'];
		}

	}elseif (strpos($row_anexos['anexo_caminho'], 'https://www.grupofortune.com.br/anexos/') !== false){
		$link_anexo = $row_anexos['anexo_caminho'];
	}else{
		$anexo_encontrado = 0;
		$result_servidor = mysql_query("SELECT servidor_id, servidor_url 
										FROM sys_vendas_anexos_servidores 
										WHERE servidor_data_ini <= '" . $row_anexos['anexo_data'] . "' 
										AND servidor_data_fim >= '" . $row_anexos['anexo_data'] . "' LIMIT 0, 1;")
							or die(mysql_error());
		$row_servidor = mysql_fetch_array( $result_servidor );
		$headers = get_headers($row_servidor['servidor_url'].$row_anexos['vendas_id']."/".rawurlencode($row_anexos['anexo_nome']));
		if($headers[0] != "HTTP/1.1 404 Not Found")
		{
			$link_anexo = $row_servidor['servidor_url'].$row_anexos['vendas_id']."/".$row_anexos['anexo_nome'];
			$anexo_encontrado = 1;
		}else{
			$result_servidores_bkp = mysql_query("SELECT servidor_id, servidor_url, servidor_ip FROM sys_vendas_anexos_servidores WHERE servidor_id != '" . $row_servidor['servidor_id'] . "';")
			or die(mysql_error());
			while($row_servidores_bkp = mysql_fetch_array( $result_servidores_bkp ))
			{
				$headers_bkp = get_headers($row_servidores_bkp['servidor_url'].$row_anexos['vendas_id']."/".rawurlencode($row_anexos['anexo_nome']));
				$headers_docs = get_headers("http://".$row_servidores_bkp['servidor_ip']."/sistema/anexos2/upload/clientes_docs/".$row_venda['clients_cpf']."/".rawurlencode($row_anexos['anexo_nome']));
				if($headers_bkp[0] != "HTTP/1.1 404 Not Found"){
					$link_anexo = $row_servidores_bkp['servidor_url'].$row_anexos['vendas_id']."/".$row_anexos['anexo_nome'];
					$anexo_encontrado = 1;
					break;
				}elseif($headers_docs[0] != "HTTP/1.1 404 Not Found"){
					$link_anexo = "http://".$row_servidores_bkp['servidor_ip']."/sistema/anexos2/upload/clientes_docs/".$row_venda['clients_cpf']."/".$row_anexos['anexo_nome'];
					$anexo_encontrado = 1;
					break;
				}
			}
		}
		if($anexo_encontrado == 0)
		{
			$link_anexo = "https://grupofortune.com.br/sistema/anexos2/upload/vendas/".$row_anexos['vendas_id']."/".$row_anexos['anexo_nome'];
		}
	}

	// if(get_headers($link_anexo)[0] == "HTTP/1.1 200 OK")
	// {	
		$files[] = $link_anexo;
	// }
?>
<?php endwhile; ?>
<?php mysql_close($con); ?>

<?php 
$zipname = 'file.zip';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);

# create new zip object
$zip = new ZipArchive();

# create a temp file & open it
$tmp_file = tempnam('.', '');
$zip->open($tmp_file, ZipArchive::CREATE);

# loop through each file
foreach ($files as $file) {
    # download file
	$file_fixed = str_replace(' ', '%20', $file);

    $download_file = file_get_contents( $file_fixed );

    #add it to the zip
    $zip->addFromString(basename($file), $download_file);
}

# close zip
$zip->close();

# send the file to the browser as a download
header('Content-disposition: attachment; filename="anexos.zip"');
header('Content-type: application/zip');
readfile($tmp_file);
unlink($tmp_file);
?>


