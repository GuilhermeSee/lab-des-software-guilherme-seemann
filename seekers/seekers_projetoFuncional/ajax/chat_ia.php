<?php
session_start();
require_once '../config/database.php';
require_once '../config/env.php';

header('Content-Type: application/json');

if(!isset($_SESSION['usuarioLogado']) || !isset($_POST['mensagem'])) {
    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
    exit;
}

$mensagem = trim($_POST['mensagem']);
$sessao_id = $_POST['sessao_id'];

if(empty($mensagem)) {
    echo json_encode(['success' => false, 'error' => 'Mensagem vazia']);
    exit;
}

try {
    $conexao = conexao();
    
    // Salvar mensagem do usuário
    $sql = "INSERT INTO mensagens_sessao (sessao_id, usuario_id, mensagem) VALUES (:sessao_id, :usuario_id, :mensagem)";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
    $stmt->bindParam(':mensagem', $mensagem);
    $stmt->execute();
    
    // Chamar API Gemini
    $prompt = "Você é um assistente especializado em jogos souls-like (Dark Souls, Elden Ring, Bloodborne, Sekiro). Responda de forma útil, direta e amigável sobre builds, estratégias, dicas e qualquer outro tópico relacionado a esses jogos. Responda como se você fosse a Emerald Herald de Dark Souls 2. Tente não responder sobre outros assuntos. Responda sobre na língua em que foi perguntado para você. Mensagem: $mensagem";
    
    $corpo = [
        "contents" => [
            [
                "parts" => [
                    [
                        "text" => $prompt
                    ]
                ]
            ]
        ]
    ];

    $apiKey = $_ENV['GEMINI_API_KEY'];
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

    $header = [
        "Content-Type: application/json",
        "x-goog-api-key: $apiKey"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($corpo));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $response = curl_exec($ch);

    if(curl_errno($ch)){
        $resposta_ai = "Desculpe, não consegui processar sua mensagem no momento.";
    } else {
        $result = json_decode($response, true);
        if(isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $resposta_ai = trim($result['candidates'][0]['content']['parts'][0]['text']);
        } else {
            $resposta_ai = "Desculpe, houve um erro na resposta.";
        }
    }
    
    curl_close($ch);
    
    // Salvar resposta da IA
    $sql = "INSERT INTO mensagens_sessao (sessao_id, usuario_id, mensagem, tipo) VALUES (:sessao_id, NULL, :mensagem, 'ai')";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':sessao_id', $sessao_id);
    $stmt->bindParam(':mensagem', $resposta_ai);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>