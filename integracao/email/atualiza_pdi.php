<?php 

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

include("../connect.php");
include("../utf8.php");

$pdi_id = $_POST['pdi_id'];
$pdi_titulo = $_POST['titulo'];
$pdi_acao_como = $_POST['acao_como'];
$pdi_situacao = $_POST['situacao'];
$pdi_usuario = $_POST['pdi_usuario'];
$pdi_prazo = $_POST['prazo'];

mysqli_query($con,"DELETE FROM `sis_pdis_acoes` WHERE `pdi_id` = '{$pdi_id}'") or die(mysqli_error($con));

if($_POST["count_acoes"]){
	for ($i = 1; $i <= $_POST["count_acoes"]; $i++) {
		if ($_POST["acao_como-".$i]){
 			if($_POST["acao_como-".$i]){$acao_texto = addslashes($_POST["acao_como-".$i]);}else{$acao_texto = "";}
			
			if($i > 1){
                $valores_insert = $valores_insert.",";
            }
			$valores_insert = $valores_insert."(NULL, 
			'$pdi_id',
			'$pdi_usuario',
             NOW(),
			'$acao_texto'
			)";
		}
	}
	$query = "INSERT INTO `sis_pdis_acoes` (`acao_id`, 
				`pdi_id`, 
				`acao_usuario`, 
				`acao_data`, 
				`acao_texto`)
			VALUES ".$valores_insert.";";
	mysqli_query($con, $query) or die(mysqli_error($con));
}

mysqli_query($con,"UPDATE `sis_pdis` SET `pdi_titulo`='{$pdi_titulo}',`pdi_texto`='{$pdi_acao_como}',`pdi_status`='{$pdi_situacao}',`pdi_conclusao`={$pdi_prazo}, `pdi_alteracao` = now(), `pdi_usuario` = {$pdi_usuario} WHERE `pdi_id` = '{$pdi_id}'") or die(mysqli_error($con));

echo "PDI Atualizado com sucesso!";
?>