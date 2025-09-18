<?php

	$result_user = mysql_query("SELECT equipe_tipo FROM jos_users 
								INNER JOIN sys_equipes ON jos_users.equipe_id = sys_equipes.equipe_id 
								WHERE id = '" . $user_id . "';") 
	or die(mysql_error());
	$array_user_id = mysql_fetch_array( $result_user );
	$equipe_tipo = $array_user_id["equipe_tipo"];
	/*Equipe de valor 2 = plataforma*/
    $image_profile_path = "anexos2/upload/fotos/foto_" . $row["clients_cpf"] . ".png";

    if( file_exists($image_profile_path) ){
        include("templates/template_ficha_cliente_com_foto.php");
    }else{
        include("templates/template_ficha_cliente_sem_foto.php");
    }
?>