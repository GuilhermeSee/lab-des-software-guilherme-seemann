<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

$titulo = "Dashboard";
include 'includes/header.php';

$conexao = conexao();

// Buscar dados do usuário
$sql = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $_SESSION['usuarioLogado']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar builds do usuário
$sql = "SELECT * FROM builds WHERE autor_id = :id ORDER BY criado_em DESC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $_SESSION['usuarioLogado']);
$stmt->execute();
$minhas_builds = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar sessões do usuário
$sql = "SELECT * FROM sessoes_jogo WHERE criador_id = :id ORDER BY criado_em DESC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $_SESSION['usuarioLogado']);
$stmt->execute();
$minhas_sessoes = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Buscar sessões que o usuário participa (incluindo as próprias, excluindo AI_CHAT)
$sql = "SELECT s.*, u.username as criador FROM sessoes_jogo s 
        JOIN usuarios u ON s.criador_id = u.id 
        LEFT JOIN participantes_sessao p ON s.id = p.sessao_id AND p.usuario_id = :id
        WHERE (p.usuario_id = :id2 OR s.criador_id = :id3) AND s.status = 'aberta' AND s.tipo_sessao != 'AI_CHAT' 
        ORDER BY s.criado_em DESC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $_SESSION['usuarioLogado']);
$stmt->bindParam(':id2', $_SESSION['usuarioLogado']);
$stmt->bindParam(':id3', $_SESSION['usuarioLogado']);
$stmt->execute();
$sessoes_participando = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar builds favoritas
$sql = "SELECT b.*, u.username as autor FROM builds b 
        JOIN usuarios u ON b.autor_id = u.id 
        JOIN builds_favoritas bf ON b.id = bf.build_id 
        WHERE bf.usuario_id = :id ORDER BY bf.data_favorito DESC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $_SESSION['usuarioLogado']);
$stmt->execute();
$builds_favoritas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar sessões favoritas
$sql = "SELECT s.*, u.username as criador FROM sessoes_jogo s 
        JOIN usuarios u ON s.criador_id = u.id 
        JOIN sessoes_favoritas sf ON s.id = sf.sessao_id 
        WHERE sf.usuario_id = :id ORDER BY sf.data_favorito DESC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $_SESSION['usuarioLogado']);
$stmt->execute();
$sessoes_favoritas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="text-warning mb-0"><?= htmlspecialchars($usuario['username']) ?></h3>
                    <small class="text-muted">Membro desde <?= date('d/m/Y', strtotime($usuario['data_criacao'])) ?></small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <?php if(!empty($usuario['bio'])): ?>
                                <p><strong>Bio:</strong> <?= htmlspecialchars($usuario['bio']) ?></p>
                            <?php endif; ?>
                            <?php 
                            $plataformas = json_decode($usuario['plataformas'], true);
                            $jogos = json_decode($usuario['jogos_preferidos'], true);
                            ?>
                            <?php if(!empty($plataformas)): ?>
                                <p><strong>Plataformas:</strong> <?= implode(', ', $plataformas) ?></p>
                            <?php endif; ?>
                            <?php if(!empty($jogos)): ?>
                                <p><strong>Jogos Preferidos:</strong> <?= implode(', ', $jogos) ?></p>
                            <?php endif; ?>
                            <?php if($usuario['usa_mods']): ?>
                                <span class="badge bg-warning">Usa Mods</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="perfil.php" class="btn btn-outline-primary mb-2">Editar Perfil</a>
                            <a href="favoritos.php" class="btn btn-outline-warning mb-2">Meus Favoritos</a><br>
                            <small class="text-muted">Último acesso: <?= date('d/m/Y H:i', strtotime($usuario['ultimo_acesso'])) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Estatísticas</h5>
                </div>
                <div class="card-body">
                    <p><strong>Builds Criadas:</strong> <?= count($minhas_builds) ?></p>
                    <p><strong>Sessões Criadas:</strong> <?= count($minhas_sessoes) ?></p>
                    <p><strong>Total de Curtidas:</strong> <?= array_sum(array_column($minhas_builds, 'curtidas')) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="text-white mb-0">Minhas Builds</h5>
                    <a href="criar_build.php" class="btn btn-primary btn-sm">Nova Build</a>
                </div>
                <div class="card-body">
                    <?php if(empty($minhas_builds)): ?>
                        <p class="text-muted">Você ainda não criou nenhuma build. <a href="criar_build.php" class="text-warning">Criar primeira build</a></p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Jogo</th>
                                        <th>Nível</th>
                                        <th>Curtidas</th>
                                        <th>Criada em</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($minhas_builds as $build): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($build['nome']) ?></td>
                                            <td><?= htmlspecialchars($build['jogo']) ?></td>
                                            <td><?= $build['nivel'] ?></td>
                                            <td>❤️ <?= $build['curtidas'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($build['criado_em'])) ?></td>
                                            <td>
                                                <a href="build_detalhes.php?id=<?= $build['id'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>
                                                <a href="editar_build.php?id=<?= $build['id'] ?>" class="btn btn-sm btn-outline-warning">Editar</a>
                                                <button class="btn btn-sm btn-outline-danger" onclick="excluirBuild(<?= $build['id'] ?>)">Excluir</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Sessões Participando</h5>
                </div>
                <div class="card-body">
                    <!-- Chat com IA - Sempre visível -->
                    <div class="card mb-3 bg-success border-success">
                        <div class="card-body">
                            <h6 class="card-title">🧝‍♀️ Arauto Esmeralda</h6>
                            <p class="card-text">
                                <small class="text-light">
                                    Olá! Sou Arauto Esmeralda e estou aqui para guiar todos os guerreiros em seu caminho árduo. <br>
                                    Pergunte sobre builds, estratégias, dicas ou qualquer dúvida!
                                </small>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="chat_ia.php" class="btn btn-light btn-sm">
                                        🧝‍♀️ Chat com IA
                                    </a>
                                </div>
                                <div>
                                    <small class="text-light">Sempre disponível</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(empty($sessoes_participando)): ?>
                        <p class="text-muted">Você não está participando de nenhuma sessão.</p>
                    <?php else: ?>
                        <?php foreach($sessoes_participando as $sessao): ?>
                            <?php
                            // Buscar participantes da sessão
                            $sql_part = "SELECT u.username FROM participantes_sessao p 
                                        JOIN usuarios u ON p.usuario_id = u.id 
                                        WHERE p.sessao_id = :sessao_id";
                            $stmt_part = $conexao->prepare($sql_part);
                            $stmt_part->bindParam(':sessao_id', $sessao['id']);
                            $stmt_part->execute();
                            $participantes = $stmt_part->fetchAll(PDO::FETCH_ASSOC);
                            
                            // Incluir criador sempre
                            array_unshift($participantes, ['username' => $sessao['criador']]);
                            
                            // Contar mensagens não lidas
                            $sql_msg = "SELECT COUNT(*) as nao_lidas FROM mensagens_sessao m
                                       LEFT JOIN mensagens_lidas ml ON m.sessao_id = ml.sessao_id AND ml.usuario_id = :usuario_id
                                       WHERE m.sessao_id = :sessao_id AND m.usuario_id != :usuario_id2
                                       AND (ml.ultima_leitura IS NULL OR m.data_envio > ml.ultima_leitura)";
                            $stmt_msg = $conexao->prepare($sql_msg);
                            $stmt_msg->bindParam(':sessao_id', $sessao['id']);
                            $stmt_msg->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
                            $stmt_msg->bindParam(':usuario_id2', $_SESSION['usuarioLogado']);
                            $stmt_msg->execute();
                            $mensagens_nao_lidas = $stmt_msg->fetch()['nao_lidas'];
                            ?>
                            <div class="card mb-3 bg-dark border-primary">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($sessao['jogo']) ?></h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <?= $sessao['criador_id'] == $_SESSION['usuarioLogado'] ? 'Sua sessão' : 'Criada por: ' . htmlspecialchars($sessao['criador']) ?><br>
                                            <?= htmlspecialchars($sessao['tipo_sessao']) ?> - <?= htmlspecialchars($sessao['plataforma']) ?>
                                        </small>
                                    </p>
                                    <div class="mb-2">
                                        <small class="text-info">Participantes (<?= count($participantes) ?>):</small><br>
                                        <small class="text-muted">
                                            <?php foreach($participantes as $i => $part): ?>
                                                <?= htmlspecialchars($part['username']) ?><?= $i < count($participantes) - 1 ? ', ' : '' ?>
                                            <?php endforeach; ?>
                                        </small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="chat_sessao.php?id=<?= $sessao['id'] ?>" class="btn btn-primary btn-sm">
                                                💬 Chat <?php if($mensagens_nao_lidas > 0): ?><span class="badge bg-danger"><?= $mensagens_nao_lidas ?></span><?php endif; ?>
                                            </a>
                                            <a href="sessao_detalhes.php?id=<?= $sessao['id'] ?>" class="btn btn-outline-primary btn-sm">Ver</a>
                                        </div>
                                        <div>
                                            <?php if($sessao['criador_id'] == $_SESSION['usuarioLogado']): ?>
                                                <a href="editar_sessao.php?id=<?= $sessao['id'] ?>" class="btn btn-outline-warning btn-sm">Editar</a>
                                                <button class="btn btn-outline-danger btn-sm" onclick="excluirSessao(<?= $sessao['id'] ?>)">Excluir</button>
                                            <?php else: ?>
                                                <button class="btn btn-outline-danger btn-sm" onclick="sairSessao(<?= $sessao['id'] ?>)">Sair</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white mb-0">Meus Favoritos</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-warning">Builds Favoritas</h6>
                    <?php if(empty($builds_favoritas)): ?>
                        <p class="text-muted small">Nenhuma build favoritada</p>
                    <?php else: ?>
                        <?php foreach(array_slice($builds_favoritas, 0, 3) as $build): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-dark rounded">
                                <div>
                                    <strong><?= htmlspecialchars($build['nome']) ?></strong><br>
                                    <small class="text-muted">por <?= htmlspecialchars($build['autor']) ?></small>
                                </div>
                                <div>
                                    <a href="build_detalhes.php?id=<?= $build['id'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="toggleFavorito('build', <?= $build['id'] ?>)">❤️</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <h6 class="text-warning mt-3">Sessões Favoritas</h6>
                    <?php if(empty($sessoes_favoritas)): ?>
                        <p class="text-muted small">Nenhuma sessão favoritada</p>
                    <?php else: ?>
                        <?php foreach(array_slice($sessoes_favoritas, 0, 3) as $sessao): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-dark rounded">
                                <div>
                                    <strong><?= htmlspecialchars($sessao['jogo']) ?></strong><br>
                                    <small class="text-muted">por <?= htmlspecialchars($sessao['criador']) ?></small>
                                </div>
                                <div>
                                    <a href="sessao_detalhes.php?id=<?= $sessao['id'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="toggleFavorito('sessao', <?= $sessao['id'] ?>)">⭐</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sairSessao(sessaoId) {
    if(confirm('Tem certeza que deseja sair desta sessão?')) {
        $.post('ajax/sair_sessao.php', {
            sessao_id: sessaoId
        }, function(data) {
            if(data.success) {
                location.reload();
            } else {
                alert('Erro ao sair da sessão: ' + (data.message || 'Erro desconhecido'));
            }
        }, 'json');
    }
}

function toggleFavorito(tipo, itemId) {
    $.post('ajax/toggle_favorito.php', {
        tipo: tipo,
        item_id: itemId
    }, function(data) {
        if(data.success) {
            location.reload();
        } else {
            alert('Erro ao alterar favorito');
        }
    }, 'json');
}

function excluirBuild(buildId) {
    if(confirm('Tem certeza que deseja excluir esta build?')) {
        if(confirm('CONFIRMAÇÃO FINAL: Excluir build permanentemente?')) {
            window.location.href = 'ajax/apagar_build.php?id=' + buildId;
        }
    }
}

function excluirSessao(sessaoId) {
    if(confirm('Tem certeza que deseja excluir esta sessão?')) {
        if(confirm('CONFIRMAÇÃO FINAL: Excluir sessão permanentemente?')) {
            window.location.href = 'ajax/apagar_sessao.php?id=' + sessaoId;
        }
    }
}
</script>

<?php include 'includes/footer.php'; ?>