<?php
session_start();

if(!isset($_SESSION['usuarioLogado'])){
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

$titulo = "Notificações";
include 'includes/header.php';

$conexao = conexao();

// Marcar todas como lidas
if(isset($_GET['marcar_lidas'])){
    $sql = "UPDATE notificacoes SET lida = 1 WHERE usuario_id = :usuario_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
    $stmt->execute();
    header("Location: notificacoes.php");
    exit;
}

// Buscar notificações
$sql = "SELECT * FROM notificacoes WHERE usuario_id = :usuario_id ORDER BY data_criacao DESC";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':usuario_id', $_SESSION['usuarioLogado']);
$stmt->execute();
$notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-warning">Notificações</h2>
                <a href="notificacoes.php?marcar_lidas=1" class="btn btn-outline-primary">Marcar Todas como Lidas</a>
            </div>

            <?php if(empty($notificacoes)): ?>
                <div class="alert alert-info">
                    Você não tem notificações.
                </div>
            <?php else: ?>
                <?php foreach($notificacoes as $notificacao): ?>
                    <div class="card mb-3 <?= !$notificacao['lida'] ? 'border-warning' : '' ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title">
                                        <?= htmlspecialchars($notificacao['titulo']) ?>
                                        <?php if(!$notificacao['lida']): ?>
                                            <span class="badge bg-warning text-dark">Nova</span>
                                        <?php endif; ?>
                                    </h6>
                                    <p class="card-text"><?= htmlspecialchars($notificacao['mensagem']) ?></p>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($notificacao['data_criacao'])) ?></small>
                                </div>
                                <div>
                                    <?php if($notificacao['tipo'] == 'solicitacao_participacao'): ?>
                                        <?php 
                                        $dados = json_decode($notificacao['dados_extras'], true);
                                        ?>
                                        <a href="gerenciar_solicitacoes.php?sessao_id=<?= $dados['sessao_id'] ?>" class="btn btn-primary btn-sm">
                                            Gerenciar
                                        </a>
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

<?php include 'includes/footer.php'; ?>