
<?php
    $ip =$_SERVER["REMOTE_ADDR"]; //Pego o IP
    $cod_imovel = $imovel['CODIGO']; //pego o host

$con = mysqli_connect("localhost","root","aux@2021","intranet");
if ($con -> connect_error){
  die('Could not connect: ' . $con->connect_error);
  }
?>

<?php 
mysqli_query($con, "SET NAMES 'utf8'");
mysqli_query($con, "SET CHARACTER SET utf8");
mysqli_query($con, 'SET character_set_connection=utf8');
mysqli_query($con, 'SET character_set_client=utf8');
mysqli_query($con, 'SET character_set_results=utf8');
?>

<?php
$agora = date("Ymd_His");
$nome_arquivo = "RelatorioConecta_".$agora;

// Determina que o arquivo é uma planilha do Excel
header("Content-type: application/vnd.ms-excel; charset: UTF-8");

// Força o download do arquivo
header("Content-type: application/force-download");

// Seta o nome do arquivo
header("Content-Disposition: attachment; filename=".$nome_arquivo.".xls");

// Imprime o conteúdo da nossa tabela no arquivo que será gerado
header("Pragma: no-cache");

echo pack("CCC",0xef,0xbb,0xbf);


if ($_GET["ordemi"]) {
    $ordem = $_GET["ordemi"];
} else {
    $ordem = "fb_id";
}

$links_filtros = "index.php/consultar-causas?";

if ($_GET["userName"]) {
    $filtros_sql = $filtros_sql . " AND g1fda_k2_users.userName LIKE '%" . addslashes($_GET['userName']) . "%'";
}
if ($_GET["name"]) {
    $filtros_sql = $filtros_sql . " AND g1fda_users.name LIKE '%" . addslashes($_GET['name']) . "%'";
}
if ($_GET["dp-normal-1"]){
    $filtros_sql = $filtros_sql . " AND fb_data > '".$_GET['dp-normal-1']." 00:00:00'";
}
if ($_GET["dp-normal-2"]){
    $filtros_sql = $filtros_sql . " AND fb_data < '".$_GET['dp-normal-1']." 23:59:59'";
}

$consulta_sql = "SELECT sis_feedbacks.fb_id, 
				fb_autor, 
				date_format(str_to_date(fb_data, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') AS data, 
				date_format(str_to_date(solicitacao_data, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') AS data_solicitacao, 
				fb_texto, 
				fb_alteracao, 
				fb_avaliado, 
				image, 
				g1fda_k2_users.userName, 
				name, 
				g1fda_users.username 
				FROM sis_feedbacks 
				INNER JOIN g1fda_users ON sis_feedbacks.fb_avaliado = g1fda_users.id 
				INNER JOIN g1fda_k2_users ON sis_feedbacks.fb_autor = g1fda_k2_users.userID 
				LEFT JOIN sis_feedbacks_solicitacoes ON sis_feedbacks.solicitacao_id = sis_feedbacks_solicitacoes.solicitacao_id  
				WHERE 1 " . $filtros_sql . " ORDER BY " . $ordem . " DESC;";
//echo $consulta_sql;
$result = mysqli_query($con, $consulta_sql);
?>

<?php
    $totalclientes = 0;
    $exibindo = 1;
    $numero = $exibindo;
?>

<table>
    <tr>
        <td>Avaliador</td>
        <td>Avaliado</td>
        <td>Data de Solicitação</td>
        <td>Data de Feedback</td>
        <td>Código</td>
        <td>Textos</td>
    </tr>
    
    <?php while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) : ?>

        <?php
            $result_solicitacao = mysqli_query($con,"SELECT fb_id, solicitacao_solicitante, solicitacao_texto, date_format(str_to_date(solicitacao_data, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') AS data, image, userName FROM sis_feedbacks_solicitacoes 
								                INNER JOIN g1fda_k2_users ON sis_feedbacks_solicitacoes.solicitacao_solicitante = g1fda_k2_users.userID 
							                    WHERE fb_id = '".$row['fb_id']."';")
								                or die(mysqli_error($con));
            $solicitacao_num_rows = mysqli_num_rows($result_solicitacao);

            $result_resposta = mysqli_query($con,"SELECT fb_id, resposta_autor, date_format(str_to_date(resposta_data, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') AS data, resposta_texto, resposta_alteracao, resposta_usuario, image, userName FROM sis_feedbacks_respostas 
                                                INNER JOIN g1fda_k2_users ON sis_feedbacks_respostas.resposta_autor = g1fda_k2_users.userID 
                                                WHERE fb_id = '".$row['fb_id']."';")
                                                or die(mysqli_error($con));
            $resposta_num_rows = mysqli_num_rows($result_resposta); 
        ?>

        <tr>
            <td><?php echo $row['userName']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['data_solicitacao']; ?></td>
            <td><?php echo $row['data']; ?></td>
            <td><?php echo $row['fb_id']; ?></td>
            <?php while($row_solicitacao = mysqli_fetch_array( $result_solicitacao )): ?>
                <td>Solicitação: <?php echo $row_solicitacao['solicitacao_texto'];?></td>
            <?php endwhile?>
            <td>Feedback: <?php echo $row['fb_texto'];?></td>
            <?php while($row_resposta = mysqli_fetch_array( $result_resposta )): ?>
                <td>Resposta: <?php echo $row_resposta['resposta_texto'];?></td>
            <?php endwhile ?>
        </tr>

        <?php
            $exibindo = $exibindo + 1;
            $numero = $numero + 1;
        ?>
    <?php endwhile?>
    
    <?php
        $exibindo = $exibindo - 1;
    ?>

    <tr>
        <td>Total de <?php echo $exibindo;?> registros</td>
    </tr>
</table>