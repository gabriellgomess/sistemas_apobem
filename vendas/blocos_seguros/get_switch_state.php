<?php
// Conexão com o banco de dados
$host = '10.100.0.22';
$db = 'sistema';
$user = 'root';
$pass = 'Theredpil2001';

$venda_id = $_POST['venda_id'];


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT libera_cobranca FROM sys_libera_cobranca WHERE venda_id = :venda_id ORDER BY id DESC LIMIT 1");
    $stmt->bindParam(':venda_id', $venda_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    
    echo json_encode(['success' => true, 'libera_cobranca' => $result['libera_cobranca']]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
