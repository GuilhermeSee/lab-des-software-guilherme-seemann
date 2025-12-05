<?php
session_start();
require_once 'config/database.php';

$titulo = "Sessões de Jogo";
include 'includes/header.php';

// Buscar sessões abertas
$conexao = conexao();
$sql = "SELECT s.*, u.username FROM sessoes_jogo s 
        JOIN usuarios u ON s.criador_id = u.id 
        WHERE s.status = 'aberta' AND s.tipo_sessao != 'AI_CHAT' 
        ORDER BY s.criado_em DESC";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$sessoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-warning">Sessões de Jogo Abertas</h2>
                <?php if(isset($_SESSION['usuarioLogado'])): ?>
                    <a href="criar_sessao.php" class="btn btn-primary">Criar Nova Sessão</a>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php if(empty($sessoes)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            Nenhuma sessão aberta no momento. 
                            <?php if(isset($_SESSION['usuarioLogado'])): ?>
                                <a href="criar_sessao.php" class="text-warning">Seja o primeiro a criar uma!</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach($sessoes as $sessao): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card sessao-card h-100 <?= isset($_SESSION['usuarioLogado']) && $sessao['criador_id'] == $_SESSION['usuarioLogado'] ? 'border-warning' : '' ?>">
                                <div class="card-body">
                                    <?php if(isset($_SESSION['usuarioLogado']) && $sessao['criador_id'] == $_SESSION['usuarioLogado']): ?>
                                        <span class="badge bg-warning text-dark mb-2">Sua Sessão</span>
                                    <?php endif; ?>
                                    <h5 class="card-title"><?= htmlspecialchars($sessao['jogo']) ?></h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <strong>Plataforma:</strong> <?= htmlspecialchars($sessao['plataforma']) ?><br>
                                            <strong>Tipo:</strong> <?= htmlspecialchars($sessao['tipo_sessao']) ?><br>
                                            <strong>Criador:</strong> <?= htmlspecialchars($sessao['username']) ?><br>
                                            <strong>Participantes:</strong> 1/<?= $sessao['max_participantes'] ?>
                                        </small>
                                    </p>
                                    <p class="card-text"><?= htmlspecialchars($sessao['descricao']) ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-success status-aberta">Aberta</span>
                                        <?php if($sessao['usa_mods']): ?>
                                            <span class="badge bg-warning">Com Mods</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <a href="sessao_detalhes.php?id=<?= $sessao['id'] ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                                        <?php if(isset($_SESSION['usuarioLogado']) && $_SESSION['usuarioLogado'] != $sessao['criador_id']): ?>
                                            <button class="btn btn-success btn-sm" onclick="participarSessao(<?= $sessao['id'] ?>)">Participar</button>
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
</div>

<script>
function participarSessao(sessaoId) {
    <?php if(!isset($_SESSION['usuarioLogado'])): ?>
        alert('Faça login para participar de sessões');
        return;
    <?php endif; ?>
    
    if(confirm('Deseja solicitar participação nesta sessão?')) {
        $.ajax({
            url: 'ajax/solicitar_participacao.php',
            method: 'POST',
            data: { sessao_id: sessaoId },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
            },
            error: function() {
                alert('Erro ao enviar solicitação');
            }
        });
    }
}
</script>

<?php include 'includes/footer.php'; ?>