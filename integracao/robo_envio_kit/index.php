<head>
    <title>Log de execução do robô de envio dos kits</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        
    </style>
</head>
<?php

include 'connect.php';

try {
 

    // Consulta SQL para selecionar todos os dados da tabela
    $stmt = $conn->prepare("SELECT * FROM sys_logs_disparos_kit ORDER BY id DESC");
    $stmt->execute();

    // Recupera os dados como um array associativo
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(count($results) > 0){
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Venda ID</th>
                    <th>Envio SMS</th>
                    <th>Status Send SMS</th>
                    <th>Message SMS</th>
                    <th>Telefone</th>
                    <th>Envio Email</th>
                    <th>Status Send Email</th>
                    <th>Message Email</th>
                    <th>Email</th>
                    <th>URL</th>
                    <th>Created At</th>
                </tr>";
        
        // Loop para exibir cada linha de dados na tabela HTML
        foreach($results as $row) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['venda_id']}</td>
                    <td>{$row['envio_sms']}</td>
                    <td>{$row['status_send_sms']}</td>
                    <td>{$row['message_sms']}</td>
                    <td>{$row['telefone']}</td>
                    <td>{$row['envio_email']}</td>
                    <td>{$row['status_send_email']}</td>
                    <td>{$row['message_email']}</td>
                    <td>{$row['email']}</td>
                    <td><a href='{$row['url']}' target='blank'>Link</td>
                    <td>{$row['created_at']}</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum registro encontrado.";
    }
    
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

// Fecha a conexão
$conn = null;

?>
