<?php
include("../../connect.php");
include("../../utf8.php");

$anexo_id=$_GET["anexo_id"];

$sql = "DELETE FROM sys_vendas_anexos WHERE anexo_id=".$anexo_id;

if (mysql_query($sql, $con)){	
?>
	<div class="alert-box msuccess" style="width: 100%; display: inline-block; text-align: center;">
		<span>Anexo removido com sucesso!</span>
		<span class="msg-close" onclick="this.parentElement.remove()">X</span>
	</div>
<?php
}
else {
	die('Erro durante a exclusão do anexo.\n Error: ' . mysql_error());
}

// Fecha conexão
mysql_close($con);
?>