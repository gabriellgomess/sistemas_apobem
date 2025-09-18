<?php
if( $_GET['nome'] )
{
    $dados->nome = $_GET['nome'];
}
if( $_GET['cpf'] )
{
    $dados->cpf = $_GET['cpf'];
}
if( $_GET['nasc'] )
{
    $dados->nasc = $_GET['nasc'];
}

function verificaCampos($dados)
{
    $response->erro = 0;
    $response->mensagem = "";

    if( strlen($dados->cpf) != 11 )
    {
        $response->erro = 1;
        $response->mensagem = "CPF do Cliente precisa ter 11 caracteres!";
        return $response;
    }
    if( trim($dados->nome) == "")
    {
        $response->erro = 1;
        $response->mensagem = "O nome do cliente é obrigatório.";
        return $response;
    }
    if( $dados->nasc == "")
    {
        $response->erro = 1;
        $response->mensagem = "A data de nascimento é obrigatória.";
        return $response;
    }

    return $response;
}

$response = verificaCampos($dados);
if($response->erro == 0)
{
    include("sistema/connect_db02.php");
    include("sistema/utf8.php");
    //procura cliente no db02
    $result = mysqli_query($con, "
			SELECT cliente_cpf 
			FROM sys_inss_clientes 
			WHERE cliente_cpf = '" . $dados->cpf . "' 
			LIMIT 0,1;"
		) or die(mysqli_error($con));


    if ( ! mysqli_num_rows($result) )
    {
		include("sistema/connect.php");
		include("sistema/utf8.php");

        //procura cliente no db01
		$result = mysqli_query($con, "
			SELECT cliente_cpf 
			FROM sys_inss_clientes 
			WHERE cliente_cpf = '" . $dados->cpf . "'
			LIMIT 0,1;"
		) or die(mysqli_error($con));

		// Caso o cliente tenha sido encontrado no DB01, e como não foi encontrado no DB02, realiza o espelhamento,
		// 	copiando o usuário do DB01 para o DB02 permitindo assim a devida consulta do usuário.
		if(mysqli_num_rows($result))
		{
			$clients_cpf = $dados->cpf;
			include("sistema/cliente/espelha.php");
			include("sistema/connect_db02.php");
			include("sistema/utf8.php");
			include("sistema/cliente/espelha_insere.php");
		}
    }
    
    if (mysqli_num_rows($result))
    {
        header('Location: /sistema/index.php?option=com_k2&view=item&id=4&Itemid=117&cpf='.$dados->cpf);
    }else{
        $sql = "INSERT INTO sistema.sys_inss_clientes (
            cliente_cpf,
            cliente_nome,
            cliente_nascimento,
            cliente_usuario,
            cliente_alteracao
        ) 
        VALUES (
            '".$dados->cpf."',
            '".$dados->nome."',
            '".$dados->nasc."',
            '".$username."',
            NOW()
        );"; 
        if (!mysqli_query($con,$sql))
        {
            die('Erro: ' . mysqli_error($con));
        }else{
            header('Location: /sistema/index.php?option=com_k2&view=item&id=4&Itemid=117&cpf='.$dados->cpf);
        }

    }
    mysqli_close($con);
}else{
    echo $response->mensagem;
}
?>