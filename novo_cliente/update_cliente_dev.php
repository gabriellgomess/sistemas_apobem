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
    'cliente_email',
    'cliente_orgao'
);

$dados = new stdClass();
$camposAtualizar = array();

foreach ($campos as $campo) {
    if (isset($_POST[$campo])) {
        $valor = $_POST[$campo];
        if (!empty($valor)) {
            $dados->$campo = $valor;
            $camposAtualizar[] = "$campo = '" . $valor . "'";
        }
    }
}

if (!empty($camposAtualizar)) {
    $username = ""; // Defina a variável $username
    $valoresSql = implode(',', $camposAtualizar);

    // Supondo que o CPF seja a chave primária ou um identificador único na tabela
   $cliente_cpf = str_replace(".", "", $_POST["cliente_cpf"]);
   $cliente_cpf = str_replace("-", "", $cliente_cpf);

   //  $cliente_cpf = $_POST['cliente_cpf']; 

    $sql_atualizar_registro = "UPDATE sistema.sys_inss_clientes 
                               SET $valoresSql, cliente_usuario = '$username', cliente_alteracao = NOW()
                               WHERE cliente_cpf = '$cliente_cpf';";
} else {
    echo "Nenhum campo a ser atualizado.";
}

function verificaCampos($dados){
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


if (verificaCampos($dados) == 0) {
    include("../../connect_db02.php");
    include("../../utf8.php");
    //procura cliente no db02

    $sql = "SELECT cliente_cpf 
            FROM sys_inss_clientes 
            WHERE cliente_cpf = '$dados->cliente_cpf' 
            LIMIT 0,1;";
    $result = mysqli_query( $con, $sql ) or die(mysqli_error($con));


    if (!mysqli_num_rows($result)) {
        include("../../connect.php");
        include("../../utf8.php");

        //procura cliente no db01
        $result = mysqli_query(
            $con,
            "
			SELECT cliente_cpf 
			FROM sys_inss_clientes 
			WHERE cliente_cpf = '" . $dados->cliente_cpf . "'
			LIMIT 0,1;"
        ) or die(mysqli_error($con));
        // Caso o cliente tenha sido encontrado no DB01, e como não foi encontrado no DB02, realiza o espelhamento,
        // 	copiando o usuário do DB01 para o DB02 permitindo assim a devida consulta do usuário.
        if (mysqli_num_rows($result)) {
            $cliente_cpf = $dados->cliente_cpf;
            include("../../cliente/espelha.php");
            include("../../connect_db02.php");
            include("../../utf8.php");
            include("../../cliente/espelha_insere.php");
        }
    }

    if (mysqli_num_rows($result)) {
        include("../../connect.php");
        include("../../utf8.php");
        $sql = $sql_atualizar_registro;
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
            echo "Dados atualizados com sucesso!";
        }
    }

    mysqli_close($con);

} else{
    echo "Verifique os dados informados.";
}
