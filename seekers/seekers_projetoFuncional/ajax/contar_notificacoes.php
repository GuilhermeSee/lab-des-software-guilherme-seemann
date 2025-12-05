<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['usuarioLogado'])){
    echo json_encode(['count' => 0]);
    exit;
}

$conexao = conexao();

// Contar notificações não lidas
$sql = "SELECT COUNT(*) as total FROM notificacoes WHERE usuario_id = :id AND lida = 0";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $_SESSION['usuarioLogado']);
$stmt->execute();
$count = $stmt->fetch()['total'];

echo json_encode(['count' => $count]);
?>