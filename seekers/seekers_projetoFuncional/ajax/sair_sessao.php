<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['usuarioLogado']) || !isset($_POST['sessao_id'])){
    echo json_encode(['success' => false]);
    exit;
}

$conexao = conexao();
$usuario_id = $_SESSION['usuarioLogado'];
$sessao_id = $_POST['sessao_id'];

// Verificar se o usuário não é o criador da sessão
$sql = "SELECT criador_id FROM sessoes_jogo WHERE id = :sessao_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->execute();
$sessao = $stmt->fetch();

if($sessao && $sessao['criador_id'] == $usuario_id){
    echo json_encode(['success' => false, 'message' => 'Você não pode sair da própria sessão']);
    exit;
}

// Buscar nome do usuário
$sql = "SELECT username FROM usuarios WHERE id = :usuario_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();
$usuario = $stmt->fetch();

// Remover da sessão
$sql = "DELETE FROM participantes_sessao WHERE usuario_id = :usuario_id AND sessao_id = :sessao_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->execute();

// Remover solicitações pendentes
$sql = "DELETE FROM solicitacoes_participacao WHERE solicitante_id = :usuario_id AND sessao_id = :sessao_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->execute();

// Adicionar mensagem de saída no chat
if($usuario) {
    $mensagem = $usuario['username'] . " saiu da sessão";
    $sql = "INSERT INTO mensagens_sessao (sessao_id, usuario_id, mensagem, tipo) VALUES (:sessao_id, :usuario_id, :mensagem, 'sistema')";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':mensagem', $mensagem);
    $stmt->execute();
}

echo json_encode(['success' => true]);
?>