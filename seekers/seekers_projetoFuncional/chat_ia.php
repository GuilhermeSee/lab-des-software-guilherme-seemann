<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';
require_once 'config/env.php';

$conexao = conexao();
$sql = "SELECT id FROM sessoes_jogo WHERE tipo_sessao = 'AI_CHAT' LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$sessao_ai = $stmt->fetch(PDO::FETCH_ASSOC);
$sessao_id = $sessao_ai['id'];

$sql = "SELECT m.*, 
        CASE 
            WHEN m.tipo = 'ai' THEN 'Seekers AI' 
            WHEN u.username IS NOT NULL THEN u.username 
            ELSE 'Sistema' 
        END as username
        FROM mensagens_sessao m 
        LEFT JOIN usuarios u ON m.usuario_id = u.id 
        WHERE m.sessao_id = :sessao_id 
        AND (m.usuario_id = :usuario_id OR m.tipo = 'ai')
        ORDER BY m.data_envio ASC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
$stmt->execute();
$mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mensagens_filtradas = [];
$mensagens_usuario = [];

foreach($mensagens as $msg) {
    if($msg['usuario_id'] == $_SESSION['usuarioLogado']) {
        $mensagens_usuario[] = $msg;
    }
}

for($i = 0; $i < count($mensagens); $i++) {
    $msg = $mensagens[$i];
    
    if($msg['usuario_id'] == $_SESSION['usuarioLogado']) {
        $mensagens_filtradas[] = $msg;
        
        if(isset($mensagens[$i + 1]) && $mensagens[$i + 1]['tipo'] == 'ai') {
            $mensagens_filtradas[] = $mensagens[$i + 1];
            $i++;
        }
    }
}

$mensagens = $mensagens_filtradas;


$titulo = "Chat com IA";
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">🧝‍♀️ Chat com IA - Assistente Souls-like</h4>
                    <small class="text-muted">Tire suas dúvidas sobre builds, estratégias e dicas</small>
                </div>
                <div class="card-body">
                    <div id="chat-messages" style="height: 400px; overflow-y: auto; border: 1px solid #4a4a6a; padding: 15px; margin-bottom: 20px; background: #1a1a2e;">
                        <?php if(empty($mensagens)): ?>
                            <p class="text-muted text-center">🧝‍♀️ Olá! Sou Arauto Esmeralda e estou aqui para guiar todos os guerreiros em seu caminho árduo. Pergunte sobre builds, estratégias, dicas ou qualquer dúvida!</p>
                        <?php else: ?>
                            <?php foreach($mensagens as $msg): ?>
                                <?php if(isset($msg['tipo']) && $msg['tipo'] == 'ai'): ?>
                                    <div class="mb-3">
                                        <div class="d-inline-block p-2 rounded bg-success" style="max-width: 70%;">
                                            <strong>🧝‍♀️ <?= htmlspecialchars($msg['username']) ?>:</strong><br>
                                            <?= nl2br(htmlspecialchars($msg['mensagem'])) ?>
                                            <br><small class="text-light" style="opacity: 0.8;"><?= date('H:i', strtotime($msg['data_envio'])) ?></small>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3 text-end">
                                        <div class="d-inline-block p-2 rounded bg-primary" style="max-width: 70%;">
                                            <strong><?= htmlspecialchars($msg['username']) ?>:</strong><br>
                                            <?= nl2br(htmlspecialchars($msg['mensagem'])) ?>
                                            <br><small class="text-light" style="opacity: 0.8;"><?= date('H:i', strtotime($msg['data_envio'])) ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <form id="chatForm">
                        <div class="input-group">
                            <input type="text" class="form-control" id="mensagemInput" placeholder="Digite sua pergunta..." required>
                            <button type="submit" class="btn btn-primary" id="enviarBtn">Enviar</button>
                        </div>
                    </form>

                    <div class="mt-3">
                        <a href="dashboard.php" class="btn btn-secondary">Voltar ao Perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;

document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const mensagem = document.getElementById('mensagemInput').value.trim();
    if(!mensagem) return;
    
    const enviarBtn = document.getElementById('enviarBtn');
    enviarBtn.disabled = true;
    enviarBtn.textContent = 'Enviando...';
    
    const userMsg = document.createElement('div');
    userMsg.className = 'mb-3 text-end';
    userMsg.innerHTML = `
        <div class="d-inline-block p-2 rounded bg-primary" style="max-width: 70%;">
            <strong>Você:</strong><br>
            ${mensagem.replace(/\n/g, '<br>')}
            <br><small class="text-light" style="opacity: 0.8;">${new Date().toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}</small>
        </div>
    `;
    document.getElementById('chat-messages').appendChild(userMsg);
    
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typing';
    typingDiv.className = 'mb-3';
    typingDiv.innerHTML = `
        <div class="d-inline-block p-2 rounded bg-success" style="max-width: 70%;">
            <strong>🧝‍♀️ Seekers AI:</strong><br>
            <em>Digitando...</em>
        </div>
    `;
    document.getElementById('chat-messages').appendChild(typingDiv);
    document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
    fetch('ajax/chat_ia.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'mensagem=' + encodeURIComponent(mensagem) + '&sessao_id=<?= $sessao_id ?>'
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('typing').remove();
        document.getElementById('mensagemInput').value = '';
        enviarBtn.disabled = false;
        enviarBtn.textContent = 'Enviar';
        
        if(data.success) {
            location.reload();
        } else {
            alert('Erro: ' + data.error);
        }
    })
    .catch(error => {
        document.getElementById('typing').remove();
        enviarBtn.disabled = false;
        enviarBtn.textContent = 'Enviar';
        alert('Erro na requisição');
    });
});
</script>

<?php include 'includes/footer.php'; ?>