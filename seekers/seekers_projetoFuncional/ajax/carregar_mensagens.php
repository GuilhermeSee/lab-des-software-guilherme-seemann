<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['usuarioLogado']) || !isset($_GET['sessao_id'])){
    echo json_encode(['mensagens' => []]);
    exit;
}

$conexao = conexao();
$sessao_id = $_GET['sessao_id'];
$ultima_atualizacao = isset($_GET['ultima_atualizacao']) ? $_GET['ultima_atualizacao'] : 0;

// Verificar se o usuário participa da sessão (ou se é chat AI)
$sql = "SELECT s.id, s.tipo_sessao FROM sessoes_jogo s 
        LEFT JOIN participantes_sessao p ON s.id = p.sessao_id AND p.usuario_id = :usuario_id
        WHERE s.id = :sessao_id AND (s.criador_id = :usuario_id2 OR p.usuario_id IS NOT NULL OR s.tipo_sessao = 'AI_CHAT')";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
$stmt->bindParam(':usuario_id2', $_SESSION['usuarioLogado']);
$stmt->execute();
$sessao_info = $stmt->fetch();

if(!$sessao_info){
    echo json_encode(['mensagens' => []]);
    exit;
}

// Buscar mensagens novas (filtrar para AI chat se necessário)
if($sessao_info['tipo_sessao'] == 'AI_CHAT') {
    $sql = "SELECT m.*, 
            CASE 
                WHEN m.tipo = 'ai' THEN 'Seekers AI' 
                WHEN u.username IS NOT NULL THEN u.username 
                ELSE 'Sistema' 
            END as username,
            m.tipo
            FROM mensagens_sessao m 
            LEFT JOIN usuarios u ON m.usuario_id = u.id 
            WHERE m.sessao_id = :sessao_id 
            AND (m.usuario_id = :usuario_id OR m.tipo = 'ai')
            AND UNIX_TIMESTAMP(m.data_envio) * 1000 > :ultima_atualizacao
            ORDER BY m.data_envio ASC";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
    $stmt->bindParam(':ultima_atualizacao', $ultima_atualizacao);
} else {
    $sql = "SELECT m.*, u.username, m.tipo FROM mensagens_sessao m 
            LEFT JOIN usuarios u ON m.usuario_id = u.id 
            WHERE m.sessao_id = :sessao_id AND UNIX_TIMESTAMP(m.data_envio) * 1000 > :ultima_atualizacao
            ORDER BY m.data_envio ASC";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->bindParam(':ultima_atualizacao', $ultima_atualizacao);
}
$stmt->execute();
$mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Formatar mensagens
$mensagens_formatadas = [];
foreach($mensagens as $msg){
    $mensagens_formatadas[] = [
        'usuario_id' => $msg['usuario_id'],
        'username' => $msg['username'] ?? 'Sistema',
        'mensagem' => htmlspecialchars($msg['mensagem']),
        'horario' => date('H:i', strtotime($msg['data_envio'])),
        'tipo' => $msg['tipo'] ?? 'mensagem'
    ];
}

echo json_encode(['mensagens' => $mensagens_formatadas]);
?>