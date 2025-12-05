<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['usuarioLogado']) || !isset($_POST['tipo']) || !isset($_POST['item_id'])){
    echo json_encode(['success' => false]);
    exit;
}

$conexao = conexao();
$usuario_id = $_SESSION['usuarioLogado'];
$tipo = $_POST['tipo']; // 'build' ou 'sessao'
$item_id = $_POST['item_id'];

// Verificar se já está nos favoritos
if($tipo == 'build'){
    $sql = "SELECT id FROM builds_favoritas WHERE usuario_id = :usuario_id AND build_id = :item_id";
} else {
    $sql = "SELECT id FROM sessoes_favoritas WHERE usuario_id = :usuario_id AND sessao_id = :item_id";
}

$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->bindParam(':item_id', $item_id);
$stmt->execute();
$favorito = $stmt->fetch();

if($favorito){
    // Remover dos favoritos
    if($tipo == 'build'){
        $sql = "DELETE FROM builds_favoritas WHERE usuario_id = :usuario_id AND build_id = :item_id";
    } else {
        $sql = "DELETE FROM sessoes_favoritas WHERE usuario_id = :usuario_id AND sessao_id = :item_id";
    }
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':item_id', $item_id);
    $stmt->execute();
    
    echo json_encode(['success' => true, 'favorito' => false]);
} else {
    // Adicionar aos favoritos
    if($tipo == 'build'){
        $sql = "INSERT INTO builds_favoritas (usuario_id, build_id) VALUES (:usuario_id, :item_id)";
    } else {
        $sql = "INSERT INTO sessoes_favoritas (usuario_id, sessao_id) VALUES (:usuario_id, :item_id)";
    }
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':item_id', $item_id);
    $stmt->execute();
    
    echo json_encode(['success' => true, 'favorito' => true]);
}
?>