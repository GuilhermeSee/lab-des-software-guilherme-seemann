<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if(!isset($_SESSION['usuarioLogado'])){
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit;
}

if(!isset($_POST['sessao_id'])){
    echo json_encode(['success' => false, 'message' => 'ID da sessão não fornecido']);
    exit;
}

$sessao_id = $_POST['sessao_id'];
$usuario_id = $_SESSION['usuarioLogado'];

$conexao = conexao();

// Verificar se sessão existe e está aberta
$sql = "SELECT s.*, u.username as criador FROM sessoes_jogo s 
        JOIN usuarios u ON s.criador_id = u.id 
        WHERE s.id = :sessao_id AND s.status = 'aberta'";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->execute();
$sessao = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$sessao){
    echo json_encode(['success' => false, 'message' => 'Sessão não encontrada ou fechada']);
    exit;
}

// Verificar se não é o próprio criador
if($sessao['criador_id'] == $usuario_id){
    echo json_encode(['success' => false, 'message' => 'Você não pode participar da sua própria sessão']);
    exit;
}

// Verificar se já solicitou
$sql = "SELECT id FROM solicitacoes_participacao WHERE sessao_id = :sessao_id AND solicitante_id = :usuario_id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();

if($stmt->fetch()){
    echo json_encode(['success' => false, 'message' => 'Você já solicitou participação nesta sessão']);
    exit;
}

// Inserir solicitação
$sql = "INSERT INTO solicitacoes_participacao (sessao_id, solicitante_id) VALUES (:sessao_id, :usuario_id)";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->bindParam(':usuario_id', $usuario_id);

if($stmt->execute()){
    // Criar notificação para o criador da sessão
    $sql = "INSERT INTO notificacoes (usuario_id, tipo, titulo, mensagem, dados_extras) 
            VALUES (:criador_id, 'solicitacao_participacao', 'Nova solicitação de participação', 
                    :mensagem, :dados)";
    $stmt = $conexao->prepare($sql);
    
    $sql_usuario = "SELECT username FROM usuarios WHERE id = :id";
    $stmt_usuario = $conexao->prepare($sql_usuario);
    $stmt_usuario->bindParam(':id', $usuario_id);
    $stmt_usuario->execute();
    $solicitante = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
    
    $mensagem = $solicitante['username'] . ' quer participar da sua sessão de ' . $sessao['jogo'];
    $dados = json_encode(['sessao_id' => $sessao_id, 'solicitante_id' => $usuario_id]);
    
    $stmt->bindParam(':criador_id', $sessao['criador_id']);
    $stmt->bindParam(':mensagem', $mensagem);
    $stmt->bindParam(':dados', $dados);
    $stmt->execute();
    
    echo json_encode(['success' => true, 'message' => 'Solicitação enviada! O criador será notificado.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao enviar solicitação']);
}