<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit;
}

$sessao_id = $_GET['id'];
$conexao = conexao();

// Verificar se o usuário participa da sessão
$sql = "SELECT s.*, u.username as criador FROM sessoes_jogo s 
        JOIN usuarios u ON s.criador_id = u.id 
        LEFT JOIN participantes_sessao p ON s.id = p.sessao_id AND p.usuario_id = :usuario_id
        WHERE s.id = :sessao_id AND (s.criador_id = :usuario_id2 OR p.usuario_id IS NOT NULL)";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
$stmt->bindParam(':usuario_id2', $_SESSION['usuarioLogado']);
$stmt->execute();
$sessao = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$sessao){
    header("Location: dashboard.php");
    exit;
}

// Processar envio de mensagem
if(isset($_POST['mensagem']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    $mensagem = trim($_POST['mensagem']);
    if(!empty($mensagem)){
        $sql = "INSERT INTO mensagens_sessao (sessao_id, usuario_id, mensagem) VALUES (:sessao_id, :usuario_id, :mensagem)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':sessao_id', $sessao_id);
        $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
        $stmt->bindParam(':mensagem', $mensagem);
        $stmt->execute();
        
        // Redirecionar para evitar reenvio
        header("Location: chat_sessao.php?id=" . $sessao_id);
        exit;
    }
}

// Marcar mensagens como lidas
$sql = "INSERT INTO mensagens_lidas (usuario_id, sessao_id, ultima_leitura) 
        VALUES (:usuario_id, :sessao_id, NOW()) 
        ON DUPLICATE KEY UPDATE ultima_leitura = NOW()";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->execute();

// Buscar mensagens
$sql = "SELECT m.*, u.username FROM mensagens_sessao m 
        LEFT JOIN usuarios u ON m.usuario_id = u.id 
        WHERE m.sessao_id = :sessao_id 
        ORDER BY m.data_envio ASC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':sessao_id', $sessao_id);
$stmt->execute();
$mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

$titulo = "Chat - " . $sessao['jogo'];
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0">Chat: <?= htmlspecialchars($sessao['jogo']) ?></h4>
                    <small class="text-muted">
                        <?= htmlspecialchars($sessao['tipo_sessao']) ?> - <?= htmlspecialchars($sessao['plataforma']) ?> 
                        | Criado por: <?= htmlspecialchars($sessao['criador']) ?>
                    </small>
                </div>
                <div class="card-body">
                    <div id="chat-messages" style="height: 400px; overflow-y: auto; border: 1px solid #4a4a6a; padding: 15px; margin-bottom: 20px; background: #1a1a2e;">
                        <?php if(empty($mensagens)): ?>
                            <p class="text-muted text-center">Nenhuma mensagem ainda. Seja o primeiro a falar!</p>
                        <?php else: ?>
                            <?php foreach($mensagens as $msg): ?>
                                <?php if(isset($msg['tipo']) && $msg['tipo'] == 'sistema'): ?>
                                    <div class="mb-2 text-center">
                                        <small class="text-warning bg-dark px-2 py-1 rounded">
                                            🔔 <?= htmlspecialchars($msg['mensagem']) ?> - <?= date('H:i', strtotime($msg['data_envio'])) ?>
                                        </small>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3 <?= $msg['usuario_id'] == $_SESSION['usuarioLogado'] ? 'text-end' : '' ?>">
                                        <div class="d-inline-block p-2 rounded <?= $msg['usuario_id'] == $_SESSION['usuarioLogado'] ? 'bg-primary' : 'bg-secondary' ?>" style="max-width: 70%;">
                                            <strong><?= htmlspecialchars($msg['username']) ?>:</strong><br>
                                            <?= nl2br(htmlspecialchars($msg['mensagem'])) ?>
                                            <br><small class="text-light" style="opacity: 0.8;"><?= date('H:i', strtotime($msg['data_envio'])) ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <form method="post" id="chatForm">
                        <div class="input-group">
                            <input type="text" class="form-control" name="mensagem" id="mensagemInput" placeholder="Digite sua mensagem..." required autocomplete="off">
                            <button type="submit" class="btn btn-primary" id="enviarBtn">Enviar</button>
                        </div>
                    </form>

                    <div class="mt-3">
                        <a href="sessao_detalhes.php?id=<?= $sessao_id ?>" class="btn btn-outline-primary">Ver Detalhes da Sessão</a>
                        <a href="dashboard.php" class="btn btn-secondary">Voltar ao Perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-scroll para a última mensagem
document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;

let ultimaAtualizacao = new Date().getTime();

// Função para carregar mensagens via AJAX
function carregarMensagens() {
    $.get('ajax/carregar_mensagens.php', {
        sessao_id: <?= $sessao_id ?>,
        ultima_atualizacao: ultimaAtualizacao
    }, function(data) {
        if(data.mensagens && data.mensagens.length > 0) {
            data.mensagens.forEach(function(msg) {
                let messageHtml;
                if(msg.tipo === 'sistema') {
                    messageHtml = `
                        <div class="mb-2 text-center">
                            <small class="text-warning bg-dark px-2 py-1 rounded">
                                🔔 ${msg.mensagem} - ${msg.horario}
                            </small>
                        </div>
                    `;
                } else {
                    const isOwn = msg.usuario_id == <?= $_SESSION['usuarioLogado'] ?>;
                    messageHtml = `
                        <div class="mb-3 ${isOwn ? 'text-end' : ''}">
                            <div class="d-inline-block p-2 rounded ${isOwn ? 'bg-primary' : 'bg-secondary'}" style="max-width: 70%;">
                                <strong>${msg.username}:</strong><br>
                                ${msg.mensagem.replace(/\n/g, '<br>')}
                                <br><small class="text-light" style="opacity: 0.8;">${msg.horario}</small>
                            </div>
                        </div>
                    `;
                }
                $('#chat-messages').append(messageHtml);
            });
            
            // Auto-scroll
            document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
            ultimaAtualizacao = new Date().getTime();
        }
    }, 'json');
}

// Recarregar mensagens a cada 3 segundos
setInterval(carregarMensagens, 3000);

// Atualizar contador de notificações no header
setInterval(function() {
    $.get('ajax/contar_notificacoes.php', function(data) {
        if(data.count > 0) {
            $('.navbar .badge').text(data.count).show();
        } else {
            $('.navbar .badge').hide();
        }
    }, 'json');
}, 5000);

// Prevenir envio duplicado
$('#chatForm').on('submit', function(e) {
    $('#enviarBtn').prop('disabled', true);
    setTimeout(function() {
        $('#enviarBtn').prop('disabled', false);
    }, 2000);
});

// Focar no campo de mensagem
$('#mensagemInput').focus();

// Prevenir perda de foco e limpar campo
$('#chatForm').on('submit', function(e) {
    e.preventDefault();
    
    const mensagem = $('#mensagemInput').val().trim();
    if(mensagem) {
        $.post('', {
            mensagem: mensagem
        }, function() {
            $('#mensagemInput').val('').focus();
            setTimeout(function() {
                location.reload();
            }, 500);
        });
    }
    return false;
});

// Enter para enviar
$('#mensagemInput').on('keypress', function(e) {
    if(e.which === 13) {
        $('#chatForm').submit();
        return false;
    }
});
</script>

<?php include 'includes/footer.php'; ?>