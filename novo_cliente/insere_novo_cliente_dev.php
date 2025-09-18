<?php
$campos = array(
    'cliente_cpf',
    'cliente_nome',
    'cliente_nascimento',
    'cliente_sexo',
    'cliente_empregador',
    'cliente_categoria',
    'cliente_beneficio',
    'cliente_especie',
    'cliente_rg',
    'cliente_situacao',
    'cliente_pai',
    'cliente_mae',
    'cliente_banco',
    'cliente_agencia',
    'cliente_conta',
    'cliente_cep',
    'cliente_bairro',
    'cliente_cidade',
    'cliente_endereco',
    'cliente_uf',
    'cliente_telefone',
    'cliente_celular',
    'cliente_email'
);

$dados = new stdClass();
$camposInserir = array();
$valores = array();

foreach ($campos as $campo) {
    if (isset($_POST[$campo])) {
        $valor = $_POST[$campo];
        if (!empty($valor)) {
            $dados->$campo = $valor;
            $camposInserir[] = $campo;
            $valores[] = "'" . $valor . "'";
        }
    }
}

if (!empty($camposInserir)) {
    $username = ""; // Defina a variável $username
    $camposSql = implode(',', $camposInserir);
    $valoresSql = implode(',', $valores);

    $dados->cliente_cpf = str_replace(".", "", $dados->cliente_cpf);
    $dados->cliente_cpf = str_replace("-", "", $dados->cliente_cpf);

    $sql_novo_user = "INSERT INTO sistema.sys_inss_clientes ($camposSql, cliente_usuario, cliente_alteracao)
            VALUES ($valoresSql, '" . $username . "', NOW());";



    // if (!mysqli_query($con, $sql)) {
    //     die('Erro: ' . mysqli_error($con));
    // } else {
    //     //header('Location: /sistema/index.php?option=com_k2&view=item&id=4&Itemid=117&cpf=' . $_POST['cliente_cpf']);
    // }
} else {
    echo "Nenhum campo a ser inserido.";
}



function verificaCampos($dados)
{
    $qtd_erros = 0;

    if (strlen($dados->cliente_cpf) != 11) {
        $qtd_erros = 1;
        return $qtd_erros;
    }
    if (trim($dados->cliente_nome) == "") {
        $qtd_erros = 1;
        return $qtd_erros;
    }
    if ($dados->cliente_nascimento == "") {
        $qtd_erros = 1;
        return $qtd_erros;
    }

    return $qtd_erros;
}
echo "verifica dados " . verificaCampos($dados);

if (verificaCampos($dados) == 0) {
    include("../../connect_db02.php");
    include("../../utf8.php");
    //procura cliente no db02
    echo "verificando campos";


	echo "SELECT cliente_cpf 
			FROM sys_inss_clientes 
			WHERE cliente_cpf = '" . $dados->cliente_cpf . "' 
			LIMIT 0,1;";
			
    $result = mysqli_query(
        $con,
        "
			SELECT cliente_cpf 
			FROM sys_inss_clientes 
			WHERE cliente_cpf = '" . $dados->cliente_cpf . "' 
			LIMIT 0,1;"
    ) or die(mysqli_error($con));


    if (!mysqli_num_rows($result)) {
        include("../../connect.php");
        include("../../utf8.php");

        //procura cliente no db01
		 echo "procura cliente no db01";
        $result = mysqli_query(
            $con,
            "
			SELECT cliente_cpf 
			FROM sys_inss_clientes 
			WHERE cliente_cpf = '" . $dados->cliente_cpf . "'
			LIMIT 0,1;"
        ) or die(mysqli_error($con));
		
		echo "
			SELECT cliente_cpf 
			FROM sys_inss_clientes 
			WHERE cliente_cpf = '" . $dados->cliente_cpf . "'
			LIMIT 0,1;";
        // Caso o cliente tenha sido encontrado no DB01, e como não foi encontrado no DB02, realiza o espelhamento,
        // 	copiando o usuário do DB01 para o DB02 permitindo assim a devida consulta do usuário.
        if (mysqli_num_rows($result)) {
            $cliente_cpf = $dados->cliente_cpf;
			$clients_cpf = $dados->cliente_cpf;
            include("../../cliente/espelha.php");
            include("../../connect_db02.php");
            include("../../utf8.php");
            include("../../cliente/espelha_insere.php");
        }
        echo "espelhando no db02";
    }

    if (mysqli_num_rows($result)) {
        // header('Location: /sistema/index.php?option=com_k2&view=item&id=4&Itemid=117&cpf='.$dados->cpf);
    } else {
        echo "insirindo o novo usuario";
        $sql = $sql_novo_user;
        if (!mysqli_query($con, $sql)) {
            die('Erro: ' . mysqli_error($con));
        } else {
            //  header('Location: /sistema/index.php?option=com_k2&view=item&id=4&Itemid=117&cpf='.$dados->cpf);
        }
        include("../../connect_db02.php");
        include("../../utf8.php");
        if (!mysqli_query($con, $sql)) {
            die('Erro: ' . mysqli_error($con));
        } else {
            //  header('Location: /sistema/index.php?option=com_k2&view=item&id=4&Itemid=117&cpf='.$dados->cpf);
        }
    }
    mysqli_close($con);
} else {
    echo $response->mensagem;
}
