<?php
session_start();
require_once 'config/database.php';

if(!isset($_GET['id'])){
    header("Location: sessoes.php");
    exit;
}

$sessao_id = $_GET['id'];
$conexao = conexao();

$sql = "SELECT s.*, u.username FROM sessoes_jogo s 
        JOIN usuarios u ON s.criador_id = u.id 
        WHERE s.id = :id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $sessao_id);
$stmt->execute();
$sessao = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$sessao){
    header("Location: sessoes.php");
    exit;
}

$titulo = "Sessão - " . $sessao['jogo'];
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-white mb-0"><?= htmlspecialchars($sessao['jogo']) ?></h4>
                    <small class="text-muted">
                        Criada por <?= htmlspecialchars($sessao['username']) ?> em <?= date('d/m/Y H:i', strtotime($sessao['criado_em'])) ?>
                    </small>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-warning">Informações da Sessão</h6>
                            <p><strong>Plataforma:</strong> <?= htmlspecialchars($sessao['plataforma']) ?></p>
                            <p><strong>Tipo:</strong> <?= htmlspecialchars($sessao['tipo_sessao']) ?></p>
                            <p><strong>Máximo de Participantes:</strong> <?= $sessao['max_participantes'] ?></p>
                            <p><strong>Status:</strong> 
                                <span class="badge <?= $sessao['status'] == 'aberta' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= ucfirst($sessao['status']) ?>
                                </span>
                            </p>
                            <?php if($sessao['usa_mods']): ?>
                                <p><strong>Mods:</strong> <span class="badge bg-warning">Permitidos</span></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if(isset($_SESSION['usuarioLogado']) && $sessao['status'] == 'aberta'): ?>
                                <?php
                                $sql_participa = "SELECT id FROM participantes_sessao WHERE usuario_id = :usuario_id AND sessao_id = :sessao_id";
                                $stmt_participa = $conexao->prepare($sql_participa);
                                $stmt_participa->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
                                $stmt_participa->bindParam(':sessao_id', $sessao['id']);
                                $stmt_participa->execute();
                                $participa = $stmt_participa->fetch();
                                ?>
                                
                                <?php if($_SESSION['usuarioLogado'] == $sessao['criador_id'] || $participa): ?>
                                    <a href="chat_sessao.php?id=<?= $sessao['id'] ?>" class="btn btn-success mb-2">
                                        💬 Entrar no Chat
                                    </a><br>
                                <?php endif; ?>
                                
                                <?php if($_SESSION['usuarioLogado'] != $sessao['criador_id'] && !$participa): ?>
                                    <button class="btn btn-success mb-2" onclick="participarSessao(<?= $sessao['id'] ?>)">
                                        Participar da Sessão
                                    </button><br>
                                <?php elseif($_SESSION['usuarioLogado'] == $sessao['criador_id']): ?>
                                    <a href="editar_sessao.php?id=<?= $sessao['id'] ?>" class="btn btn-warning mb-2">
                                        Editar Sessão
                                    </a><br>
                                    <a href="gerenciar_solicitacoes.php?id=<?= $sessao['id'] ?>" class="btn btn-info mb-2">
                                        Gerenciar Solicitações
                                    </a><br>
                                <?php endif; ?>
                                <?php
                                $sql_fav = "SELECT id FROM sessoes_favoritas WHERE usuario_id = :usuario_id AND sessao_id = :sessao_id";
                                $stmt_fav = $conexao->prepare($sql_fav);
                                $stmt_fav->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
                                $stmt_fav->bindParam(':sessao_id', $sessao['id']);
                                $stmt_fav->execute();
                                $ja_favoritou = $stmt_fav->fetch();
                                ?>
                                <button class="btn <?= $ja_favoritou ? 'btn-warning' : 'btn-outline-primary' ?>" onclick="toggleFavorito('sessao', <?= $sessao['id'] ?>)">
                                    ⭐ <?= $ja_favoritou ? 'Remover dos Favoritos' : 'Adicionar aos Favoritos' ?>
                                </button>
                            <?php else: ?>
                                <p class="text-muted">Faça login para participar desta sessão</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h6 class="text-warning">Descrição</h6>
                    <p><?= nl2br(htmlspecialchars($sessao['descricao'])) ?></p>

                    <?php if(!empty($sessao['senha_sessao'])): ?>
                        <div class="alert alert-info">
                            <strong>Sessão Privada:</strong> Esta sessão possui senha. Entre em contato com o criador para obter acesso.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="text-white mb-0">Outras Sessões de <?= htmlspecialchars($sessao['jogo']) ?></h6>
                </div>
                <div class="card-body">
                    <?php
                    $sql = "SELECT s.id, s.jogo, s.plataforma, s.tipo_sessao, s.descricao, u.username 
                            FROM sessoes_jogo s 
                            JOIN usuarios u ON s.criador_id = u.id 
                            WHERE s.jogo = :jogo AND s.id != :id AND s.status = 'aberta' AND s.tipo_sessao != 'AI_CHAT' 
                            ORDER BY s.criado_em DESC";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':jogo', $sessao['jogo']);
                    $stmt->bindParam(':id', $sessao_id);
                    $stmt->execute();
                    $sessoes_relacionadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <?php if(empty($sessoes_relacionadas)): ?>
                        <p class="text-muted">Nenhuma outra sessão ativa para <?= htmlspecialchars($sessao['jogo']) ?>.</p>
                        <a href="criar_sessao.php" class="btn btn-outline-primary btn-sm">Criar Nova Sessão</a>
                    <?php else: ?>
                        <p class="text-info mb-3">Encontradas <?= count($sessoes_relacionadas) ?> sessões ativas:</p>
                        <?php foreach($sessoes_relacionadas as $sessao_rel): ?>
                            <div class="mb-3">
                                <div class="card bg-dark border-primary">
                                    <div class="card-body py-2">
                                        <h6 class="mb-1 text-warning"><?= htmlspecialchars($sessao_rel['tipo_sessao']) ?></h6>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($sessao_rel['plataforma']) ?><br>
                                            Por: <?= htmlspecialchars($sessao_rel['username']) ?>
                                        </small>
                                        <p class="mb-2 mt-1"><small><?= htmlspecialchars(substr($sessao_rel['descricao'], 0, 60)) ?>...</small></p>
                                        <div class="d-flex justify-content-between">
                                            <a href="sessao_detalhes.php?id=<?= $sessao_rel['id'] ?>" class="btn btn-primary btn-sm">Ver</a>
                                            <?php if(isset($_SESSION['usuarioLogado'])): ?>
                                                <button class="btn btn-success btn-sm" onclick="participarSessao(<?= $sessao_rel['id'] ?>)">Participar</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <a href="sessoes.php" class="btn btn-outline-primary btn-sm w-100">Ver Todas as Sessões</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="text-white mb-0">Dicas para Sessões</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li>• Seja respeitoso com outros jogadores</li>
                        <li>• Comunique-se claramente sobre objetivos</li>
                        <li>• Tenha paciência com jogadores iniciantes</li>
                        <li>• Use o chat de voz quando possível</li>
                        <li>• Respeite o estilo de jogo de cada um</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function participarSessao(sessaoId) {
    if(!<?= isset($_SESSION['usuarioLogado']) ? 'true' : 'false' ?>) {
        alert('Faça login para participar de sessões');
        return;
    }
    
    if(confirm('Deseja participar desta sessão? O criador será notificado.')) {
        alert('Solicitação enviada! Aguarde a confirmação do criador.');
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
</script>

<?php include 'includes/footer.php'; ?>