<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['usuarioLogado'])){
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit;
}

if(!isset($_POST['tipo']) || !isset($_POST['item_id'])){
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$tipo = $_POST['tipo'];
$item_id = $_POST['item_id'];
$usuario_id = $_SESSION['usuarioLogado'];

$conexao = conexao();

// Verificar se já está nos favoritos
$sql = "SELECT id FROM favoritos WHERE usuario_id = :usuario_id AND tipo = :tipo AND item_id = :item_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->bindParam(':tipo', $tipo);
$stmt->bindParam(':item_id', $item_id);
$stmt->execute();

if($stmt->fetch()){
    echo json_encode(['success' => false, 'message' => 'Item já está nos favoritos']);
    exit;
}

// Adicionar aos favoritos
$sql = "INSERT INTO favoritos (usuario_id, tipo, item_id) VALUES (:usuario_id, :tipo, :item_id)";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->bindParam(':tipo', $tipo);
$stmt->bindParam(':item_id', $item_id);

if($stmt->execute()){
    echo json_encode(['success' => true, 'message' => 'Adicionado aos favoritos']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar favorito']);
}