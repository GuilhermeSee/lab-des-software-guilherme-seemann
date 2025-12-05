<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['usuarioLogado']) || !isset($_POST['sessao_id']) || !isset($_POST['usuario_id'])){
    echo json_encode(['success' => false]);
    exit;
}

$conexao = conexao();
$criador_id = $_SESSION['usuarioLogado'];
$sessao_id = $_POST['sessao_id'];
$usuario_id = $_POST['usuario_id'];

// Verificar se o usuário é o criador da sessão
$sql = "SELECT id FROM sessoes_jogo WHERE id = :sessao_id AND criador_id = :criador_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->bindParam(':criador_id', $criador_id);
$stmt->execute();

if(!$stmt->fetch()){
    echo json_encode(['success' => false]);
    exit;
}

// Buscar nome do usuário
$sql = "SELECT username FROM usuarios WHERE id = :usuario_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();
$usuario = $stmt->fetch();

// Remover participante
$sql = "DELETE FROM participantes_sessao WHERE sessao_id = :sessao_id AND usuario_id = :usuario_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();

// Adicionar mensagem de remoção no chat
if($usuario) {
    $mensagem = $usuario['username'] . " foi removido da sessão";
    $sql = "INSERT INTO mensagens_sessao (sessao_id, usuario_id, mensagem, tipo) VALUES (:sessao_id, :criador_id, :mensagem, 'sistema')";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->bindParam(':criador_id', $criador_id);
    $stmt->bindParam(':mensagem', $mensagem);
    $stmt->execute();
}

echo json_encode(['success' => true]);
?>