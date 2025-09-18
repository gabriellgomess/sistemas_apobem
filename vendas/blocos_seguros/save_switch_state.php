<?php
// Conexão com o banco de dados
$host = '10.100.0.22';
$db = 'sistema';
$user = 'root';
$pass = 'Theredpil2001';

$venda_id = $_POST['venda_id'];
$libera_cobranca = $_POST['libera_cobranca'];
$userid = $_POST['userid'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO sys_libera_cobranca (venda_id, libera_cobranca, usuario) VALUES (:venda_id, :libera_cobranca, :userid)");
    $stmt->bindParam(':libera_cobranca', $libera_cobranca, PDO::PARAM_INT);
    $stmt->bindParam(':venda_id', $venda_id, PDO::PARAM_INT);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
