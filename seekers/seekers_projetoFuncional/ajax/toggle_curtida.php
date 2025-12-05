<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['usuarioLogado'])){
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit;
}

if(!isset($_POST['build_id'])){
    echo json_encode(['success' => false, 'message' => 'ID da build não fornecido']);
    exit;
}

$build_id = $_POST['build_id'];
$usuario_id = $_SESSION['usuarioLogado'];

$conexao = conexao();

// Verificar se build existe
$sql = "SELECT curtidas FROM builds WHERE id = :build_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':build_id', $build_id);
$stmt->execute();
$build = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$build){
    echo json_encode(['success' => false, 'message' => 'Build não encontrada']);
    exit;
}

// Verificar se usuário já curtiu
$sql = "SELECT id FROM curtidas_builds WHERE usuario_id = :usuario_id AND build_id = :build_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->bindParam(':build_id', $build_id);
$stmt->execute();
$curtida_existente = $stmt->fetch();

if($curtida_existente){
    // Remover curtida
    $sql = "DELETE FROM curtidas_builds WHERE usuario_id = :usuario_id AND build_id = :build_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':build_id', $build_id);
    
    if($stmt->execute()){
        // Decrementar contador
        $novas_curtidas = max(0, $build['curtidas'] - 1);
        $sql = "UPDATE builds SET curtidas = :curtidas WHERE id = :build_id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':curtidas', $novas_curtidas);
        $stmt->bindParam(':build_id', $build_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'curtidas' => $novas_curtidas, 'action' => 'removed', 'liked' => false]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover curtida']);
    }
} else {
    // Adicionar curtida
    $sql = "INSERT INTO curtidas_builds (usuario_id, build_id) VALUES (:usuario_id, :build_id)";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':build_id', $build_id);
    
    if($stmt->execute()){
        // Incrementar contador
        $novas_curtidas = $build['curtidas'] + 1;
        $sql = "UPDATE builds SET curtidas = :curtidas WHERE id = :build_id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':curtidas', $novas_curtidas);
        $stmt->bindParam(':build_id', $build_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'curtidas' => $novas_curtidas, 'action' => 'added', 'liked' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao curtir build']);
    }
}